<?php
namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\PemesananModel;
use App\Models\PembayaranModel;

class Pembayaran extends BaseController
{
	public function __construct()
    {
        session_start();
        $this->CustomerModel = new CustomerModel();
        $this->PemesananModel = new PemesananModel();
        $this->PembayaranModel = new PembayaranModel();
    }

    public function index()
	{
		//tambahkan pengecekan login
       
        $data['customer'] = $this->CustomerModel->getAll();
        //mengisi array dua dimensi dengan pola [0]= id kosan, [1]  = jml kamar, [2] jml kamar terisi, [3] jml kamar kosong
        //Array ( [0] => Array ( [0] => 6 [1] => 0 [2] => 0 [3] => 0 ) 
        //[1] => Array ( [0] => 7 [1] => 0 [2] => 0 [3] => 0 ) 
        //[2] => Array ( [0] => 17 [1] => 0 [2] => 0 [3] => 0 ) )
        $ar = array();
        $i = 0;
        foreach($data['customer'] as $row):
            array_push($ar, array($row['id_customer'],0,0,0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        //hasil array jumlah data kosan
        $data['infocustomer'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/DaftarCustomer', $data);
	}


    //list history pembayaran berdasarkan id kamar tertentu
    public function listpembayaran($id_customer){
        helper('rupiah');
        //tambahkan pengecekan login
        //getHistoryPembayaranByIdKamar($id_kamar)
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdCustomer($id_customer);

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pesan = $row->id_pesan;
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $jenis_renovasi = $row->jenis_renovasi;
            $harga_deal = $row->harga_deal;
            $totalbayar =  $totalbayar + $row->besar_bayar;
        endforeach;
        $sisa_bayar= $harga_deal-$totalbayar;

        $data['id_pesan'] = $id_pesan;
        $data['nama'] = $nama;
        $data['jenis_renovasi'] = $jenis_renovasi;
        $data['harga_deal'] = rupiah($harga_deal);
        $data['totalbayar'] = rupiah($totalbayar);
        $data['sisa_bayar'] = rupiah($sisa_bayar);

        //dapatkan nomor kuitansinya
        $nokuitansi = $this->PembayaranModel->getNoKuitansi($id_customer);
        $data['nokuitansi'] = $nokuitansi;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Listpembayaran', $data);

    }

    //method untuk memasukkan data pembayaran
    public function inputpembayaran($id_pesan,$nokuitansi,$nama){
        helper('rupiah');
        //tambahkan pengecekan login
        
        //mencari nama kosan berdasarkan id_pesan
        $data['nama'] = $nama;

        $hasil= $this->PembayaranModel->getDataCustomerByIdPesan($id_pesan);
        foreach($hasil as $row):
            $infocustomer = 'nama '.$row->nama.' ('.$row->nomer.')';
            $tgl  = $row->tanggal_sekarang;
            $id_customer = $row->id_customer;
        endforeach;

        $data['infocustomer'] = $infocustomer;
        $data['tanggal'] = $tgl;

        //getHistoryPembayaranByIdKamar($id_kamar)
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdCustomer($id_customer);

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pesan = $row->id_pesan;
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $jenis_renovasi = $row->jenis_renovasi;
            $harga_deal = $row->harga_deal;
            $totalbayar =  $totalbayar + $row->besar_bayar;
        endforeach;
        $sisa_bayar= $harga_deal-$totalbayar;

        $data['id_pesan'] = $id_pesan;
        $data['nama'] = $nama;
        $data['jenis_renovasi'] = $jenis_renovasi;
        $data['harga_deal'] = rupiah($harga_deal);
        $data['totalbayar'] = rupiah($totalbayar);
        $data['sisa_bayar'] = rupiah($sisa_bayar);

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Inputpembayaran', $data);
    }

    public function prosespembayaran(){
        helper('rupiah');
        //tambahkan pengecekan login

        ///////////////
        //mencari nama kosan berdasarkan id_pesan
        $data['nama'] = $_POST['nama'];

        $hasil= $this->PembayaranModel->getDataKamarByIdCustomer($_POST['id_customer']);
        foreach($hasil as $row):
            $infokamar = 'nama '.$row->nama.' ('.$row->nomer.')';
            $tgl  = $row->tanggal_sekarang;
            $id_customer = $row->id;
        endforeach;

        $data['infocustomer'] = $infocustomer;
        $data['tanggal'] = $tgl;

        //getHistoryPembayaranByIdKamar($id_kamar)
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdCustomer($id_customer);

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pesan = $row->id_pesan;
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $jenis_renovasi = $row->jenis_renovasi;
            $harga_deal = $row->harga_deal;
            $totalbayar =  $totalbayar + $row->besar_bayar;
        endforeach;
        $sisa_bayar= $harga_deal-$totalbayar;

        $data['id_pesan'] = $id_pesan;
        $data['nama'] = $nama;
        $data['jenis_renovasi'] = $jenis_renovasi;
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
                        'nama' => $data['nama'],
                        'infocustomer' => $data['infocustomer'],
                        'tanggal' => $data['tanggal'],
                        'id_pesan' => $data['id_pesan'],
                        'harga_deal' => $data['harga_deal'],
                        'totalbayar' => $data['totalbayar'],
                        'sisa_bayar' => $data['sisa_bayar'],
                        'nokuitansi' => $data['nokuitansi']

                    ]);
            }else{
                //jika validasi tidak menemukan error
                $hasil = $this->PembayaranModel->inputDataPembayaran($_POST['id_pemesanan'],$this->PembayaranModel->getNoKuitansi($id_customer));
                if($hasil->connID->affected_rows>0){
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                $data['customer'] = $customer;
                $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdCustomer($id_customer);

                $totalbayar = 0; $sisa_bayar= 0;
                foreach($data['pembayaran'] as $row):
                $id_pesan = $row->id_pesan;
                $id_customer = $row->id_customer;
                $nama = $row->nama;
                $jenis_renovasi = $row->jenis_renovasi;
                $harga_deal = $row->harga_deal;
                $totalbayar =  $totalbayar + $row->besar_bayar;
                endforeach;
                $sisa_bayar= $harga_deal-$totalbayar;

                $data['id_pesan'] = $id_pesan;
                $data['nama'] = $nama;
                $data['customer'] = $customer;
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