<?php
namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\PencatatanModel;
use App\Models\PembayaranModel;

class Pembayaran extends BaseController
{
    public function __construct()
    {
        session_start();
        $this->pegawaimodel = new PegawaiModel();
        $this->PencatatanModel = new PemesananModel();
        $this->PembayaranModel = new PembayaranModel();
    }

    public function index()
    {
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['pegawai'] = $this->pegawaimodel->getAll();
        //mengisi array dua dimensi dengan pola [0]= id kosan, [1]  = jml kamar, [2] jml kamar terisi, [3] jml kamar kosong
        //Array ( [0] => Array ( [0] => 6 [1] => 0 [2] => 0 [3] => 0 ) 
        //[1] => Array ( [0] => 7 [1] => 0 [2] => 0 [3] => 0 ) 
        //[2] => Array ( [0] => 17 [1] => 0 [2] => 0 [3] => 0 ) )
        $ar = array();
        $i = 0;
        foreach($data['pegawai'] as $row):
            array_push($ar, array($row['id_pegawai'],0,0,0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        for($i=0;$i<count($ar);$i++){
            $ar[$i][1] = $this->pegawaimodel->getInfoKamar($ar[$i][0], 'all');
            $ar[$i][2] = $this->pegawaimodel->getInfoKamar($ar[$i][0], 'Isi');
            $ar[$i][3] = $this->pegawaimodel->getInfoKamar($ar[$i][0], 'Kosong');
        }
        //hasil array jumlah data kosan
        $data['infokosan'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Daftarkosan', $data);
    }

    //listkamar
    public function listkamar($id, $kos){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $hasil = $this->PembayaranModel->getInfoPembayaranPerKamar($id);
        $data['kamar'] = $hasil;

        //print_r($hasil);
        $data['idkos'] = $id;
        $data['namakos'] = $kos;
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/ListKamar', $data);
    }

    //list history pembayaran berdasarkan id kamar tertentu
    public function listpembayaran($id_kamar){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        //getHistoryPembayaranByIdKamar($id_kamar)
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdKamar($id_kamar);

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pesan = $row->id_pesan;
            $id_kos = $row->id_kos;
            $nama_kos = $row->nama_kos;
            $kmr = $row->kmr;
            $harga_deal = $row->harga_deal;
            $totalbayar =  $totalbayar + $row->besar_bayar;
        endforeach;
        $sisa_bayar= $harga_deal-$totalbayar;

        $data['id_pesan'] = $id_pesan;
        $data['nama_kos'] = $nama_kos;
        $data['kmr'] = $kmr;
        $data['harga_deal'] = rupiah($harga_deal);
        $data['totalbayar'] = rupiah($totalbayar);
        $data['sisa_bayar'] = rupiah($sisa_bayar);

        //dapatkan nomor kuitansinya
        $nokuitansi = $this->PembayaranModel->getNoKuitansi($id_kos);
        $data['nokuitansi'] = $nokuitansi;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Listpembayaran', $data);

    }

    //method untuk memasukkan data pembayaran
    public function inputpembayaran($id_pesan,$nokuitansi,$nama_kos){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        //mencari nama kosan berdasarkan id_pesan
        $data['nama_kos'] = $nama_kos;

        $hasil= $this->PembayaranModel->getDataKamarByIdPesan($id_pesan);
        foreach($hasil as $row):
            $infokamar = 'Lt '.$row->lantai.' ('.$row->nomer.')';
            $tgl  = $row->tanggal_sekarang;
            $id_kamar = $row->id;
        endforeach;

        $data['infokamar'] = $infokamar;
        $data['tanggal'] = $tgl;

        //getHistoryPembayaranByIdKamar($id_kamar)
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdKamar($id_kamar);

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pesan = $row->id_pesan;
            $id_kos = $row->id_kos;
            $nama_kos = $row->nama_kos;
            $kmr = $row->kmr;
            $harga_deal = $row->harga_deal;
            $totalbayar =  $totalbayar + $row->besar_bayar;
        endforeach;
        $sisa_bayar= $harga_deal-$totalbayar;

        $data['id_pesan'] = $id_pesan;
        $data['harga_deal'] = rupiah($harga_deal);
        $data['totalbayar'] = rupiah($totalbayar);
        $data['sisa_bayar'] = rupiah($sisa_bayar);
        $data['nokuitansi'] =$nokuitansi;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Inputpembayaran', $data);
    }

    public function prosespembayaran(){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        ///////////////
        //mencari nama kosan berdasarkan id_pesan
        $data['nama_kos'] = $_POST['nama_kos'];

        $hasil= $this->PembayaranModel->getDataKamarByIdPesan($_POST['id_pemesanan']);
        foreach($hasil as $row):
            $infokamar = 'Lt '.$row->lantai.' ('.$row->nomer.')';
            $tgl  = $row->tanggal_sekarang;
            $id_kamar = $row->id;
        endforeach;

        $data['infokamar'] = $infokamar;
        $data['tanggal'] = $tgl;

        //getHistoryPembayaranByIdKamar($id_kamar)
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdKamar($id_kamar);

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pesan = $row->id_pesan;
            $id_kos = $row->id_kos;
            $nama_kos = $row->nama_kos;
            $kmr = $row->kmr;
            $harga_deal = $row->harga_deal;
            $totalbayar =  $totalbayar + $row->besar_bayar;
        endforeach;
        $sisa_bayar= $harga_deal-$totalbayar;

        $data['id_pesan'] = $id_pesan;
        $data['harga_deal'] = rupiah($harga_deal);
        $data['totalbayar'] = rupiah($totalbayar);
        $data['sisa_bayar'] = rupiah($sisa_bayar);
        $data['nokuitansi'] =$_POST['no_kuitansi'];
        //////////////

        //cek dulu apakah sudah ada datanya
        if(isset($_POST['besar_bayar'])){
            $validation =  \Config\Services::validation();
            if (! $this->validate(
                        [
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'besar_bayar' => [
                                        'required' => 'Besar pembayaran tidak boleh kosong'
                                    ]
                                ]
                )
            )
            {
                //jika validasi menemukan error
                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('Pembayaran/Inputpembayaran',[
                        'validation' => $this->validator,
                        'nama_kos' => $data['nama_kos'],
                        'infokamar' => $data['infokamar'],
                        'tanggal' => $data['tanggal'],
                        'id_pesan' => $data['id_pesan'],
                        'harga_deal' => $data['harga_deal'],
                        'totalbayar' => $data['totalbayar'],
                        'sisa_bayar' => $data['sisa_bayar'],
                        'nokuitansi' => $data['nokuitansi']

                    ]);
            }else{
                //jika validasi tidak menemukan error
                $hasil = $this->PembayaranModel->inputDataPembayaran($_POST['id_pemesanan'],$this->PembayaranModel->getNoKuitansi($id_kos));
                if($hasil->connID->affected_rows>0){
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php   
                }
                $data['kmr'] = $kmr;
                $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdKamar($id_kamar);

                $totalbayar = 0; $sisa_bayar= 0;
                foreach($data['pembayaran'] as $row):
                    $id_pesan = $row->id_pesan;
                    $id_kos = $row->id_kos;
                    $nama_kos = $row->nama_kos;
                    $kmr = $row->kmr;
                    $harga_deal = $row->harga_deal;
                    $totalbayar =  $totalbayar + $row->besar_bayar;
                endforeach;
                $sisa_bayar= $harga_deal-$totalbayar;

                $data['id_pesan'] = $id_pesan;
                $data['nama_kos'] = $nama_kos;
                $data['kmr'] = $kmr;
                $data['harga_deal'] = rupiah($harga_deal);
                $data['totalbayar'] = rupiah($totalbayar);
                $data['sisa_bayar'] = rupiah($sisa_bayar);

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('Pembayaran/Listpembayaran', $data);
            }   

        }else{
            //tidak perlu dicek
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pembayaran/Inputpembayaran', $data);
        }
    }
}