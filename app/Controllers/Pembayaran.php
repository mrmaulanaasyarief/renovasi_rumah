<?php
namespace App\Controllers;

use App\Models\PemesananRenovasiModel;
use App\Models\PemesananJasaDesainModel;
use App\Models\PemesananPegawaiModel;
use App\Models\PemesananMaterialModel;
use App\Models\CustomerModel;
use App\Models\PembayaranModel;


class Pembayaran extends BaseController
{
	public function __construct()
    {
        session_start();
        $this->PemesananRenovasiModel = new PemesananRenovasiModel();
        $this->PemesananJasaDesainModel = new PemesananJasaDesainModel();
        $this->PemesananPegawaiModel = new PemesananPegawaiModel();
        $this->PemesananMaterialModel = new PemesananMaterialModel();
        $this->CustomerModel = new CustomerModel();
        $this->PembayaranModel = new PembayaranModel();
    }

    public function index()
	{
		//tambahkan pengecekan login
        // if(!isset($_SESSION['nama'])){
        //     return redirect()->to(base_url('home')); 
        // }

        $data['pesananrenovasi'] = $this->PemesananRenovasiModel->getAll();
        
        $ar = [];

        $i = 0;
        foreach($data['pesananrenovasi'] as $row):
            array_push($ar, array($row['id_pesan'],0,0,0,'','','',$row['id_customer'])); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        for($i=0;$i<count($ar);$i++){
            $customer = $this->CustomerModel->getCustomerByIdCustomer($ar[$i][7]);
            foreach($customer as $row):
                $ar[$i][4] = $row->nama;
                $ar[$i][5] = $row->alamat;
                $ar[$i][6] = $row->no_hp;
            endforeach;

            $ar[$i][1] = $this->PemesananJasaDesainModel->isRenov($ar[$i][0]);
            $ar[$i][2] = $this->PemesananPegawaiModel->isRenov($ar[$i][0]);
            $ar[$i][3] = $this->PemesananMaterialModel->isRenov($ar[$i][0]);
        }
        //hasil array jumlah data kosan
        $data['infopesananrenovasi'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/DaftarRenovasi', $data);
	}

    //listPesanan
    public function ListPesanan($id, $cus){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $hasil['renovasi'] = $this->PemesananRenovasiModel->getById($id);
        $hasil['jasadesain'] = $this->PemesananJasaDesainModel->getByRenovId($id);
        $hasil['pegawai'] = $this->PemesananPegawaiModel->getByRenovId($id);
        $hasil['material'] = $this->PemesananMaterialModel->getByRenovId($id);
        $data = $hasil;
        
        $data['id_renov'] = $id;

        $customer = $this->CustomerModel->getCustomerByIdCustomer($cus);
        foreach($customer as $row):
            $data['nama'] = $row->nama;
            $data['alamat'] = $row->alamat;
            $data['no_hp'] = $row->no_hp;
        endforeach;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/ListPesanan', $data);
    }

    //list history pembayaran berdasarkan id kamar tertentu
    public function ListPembayaran($id_renov, $jenis_pemesanan, $id_pemesanan = NULL){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        if($id_pemesanan == NULL){
            $id_pemesanan = $id_renov;
        }
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdPemesanan($id_pemesanan, $jenis_pemesanan);

        if($jenis_pemesanan == 'Renovasi'){
            $data['harga_deal'] = $this->PemesananRenovasiModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Jasa Desain'){
            $data['harga_deal'] = $this->PemesananJasaDesainModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Material'){
            $data['harga_deal'] = $this->PemesananMaterialModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Pegawai'){
            $data['harga_deal'] = $this->PemesananPegawaiModel->getHargaDeal($id_pemesanan);
        }

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pembayaran = $row->id_pembayaran;
            $id_pemesanan = $row->id_pemesanan;
            $jenis_pemesanan = $row->jenis_pemesanan;
            $no_kuitansi = $row->no_kuitansi;
            $tgl_bayar = $row->tgl_bayar;
            $besar_bayar = $row->besar_bayar;
            $totalbayar =  $totalbayar + $besar_bayar;
        endforeach;
        $sisa_bayar= $data['harga_deal']-$totalbayar;

        $data['customer'] = $this->PemesananRenovasiModel->getCustomerByIdRenov($id_renov);
        foreach($data['customer'] as $row):
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $alamat = $row->alamat;
            $no_hp = $row->no_hp;
        endforeach;

        $data['nama_customer'] = ucfirst($nama);
        $data['alamat_customer'] = ucfirst($alamat);
        $data['no_hp_customer'] = $no_hp;
        $data['id_renov'] = $id_renov;
        $data['jenis_pemesanan'] = $jenis_pemesanan;
        $data['id_pemesanan'] = $id_pemesanan;
        $data['harga_deal'] = $data['harga_deal'];
        $data['totalbayar'] = $totalbayar;
        $data['sisa_bayar'] = $sisa_bayar;

        // //dapatkan nomor kuitansinya
        // $nokuitansi = $this->PembayaranModel->getNoKuitansi($id_kos);
        // $data['nokuitansi'] = $nokuitansi;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Listpembayaran', $data);

    }

    //method untuk memasukkan data pembayaran
    public function InputPembayaran($id_renov, $jenis_pemesanan, $id_pemesanan = NULL){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $no_kuitansi = $this->PembayaranModel->getNoKuitansi($id_renov, $id_pemesanan);

        if($id_pemesanan == NULL){
            $id_pemesanan = $id_renov;
        }
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdPemesanan($id_pemesanan, $jenis_pemesanan);

        if($jenis_pemesanan == 'Renovasi'){
            $data['harga_deal'] = $this->PemesananRenovasiModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'JasaDesain'){
            $data['harga_deal'] = $this->PemesananJasaDesainModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Material'){
            $data['harga_deal'] = $this->PemesananMaterialModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Pegawai'){
            $data['harga_deal'] = $this->PemesananPegawaiModel->getHargaDeal($id_pemesanan);
        }

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pembayaran = $row->id_pembayaran;
            $id_pemesanan = $row->id_pemesanan;
            $jenis_pemesanan = $row->jenis_pemesanan;
            // $no_kuitansi = $row->no_kuitansi;
            $tgl_bayar = $row->tgl_bayar;
            $besar_bayar = $row->besar_bayar;
            $totalbayar =  $totalbayar + $besar_bayar;
        endforeach;
        $sisa_bayar= $data['harga_deal']-$totalbayar;

        $data['customer'] = $this->PemesananRenovasiModel->getCustomerByIdRenov($id_renov);
        foreach($data['customer'] as $row):
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $alamat = $row->alamat;
            $no_hp = $row->no_hp;
        endforeach;

        $data['nama_customer'] = ucfirst($nama);
        $data['id_renov'] = $id_renov;
        $data['jenis_pemesanan'] = $jenis_pemesanan;
        $data['id_pemesanan'] = $id_pemesanan;
        $data['no_kuitansi'] = $no_kuitansi;
        $data['harga_deal'] = $data['harga_deal'];
        $data['totalbayar'] = $totalbayar;
        $data['sisa_bayar'] = $sisa_bayar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembayaran/Inputpembayaran', $data);
    }

    public function ProsesPembayaran(){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        ///////////////
        //mencari nama kosan berdasarkan id_pesan
        $data['id_renov'] = $_POST['id_renov'];
        $data['jenis_pemesanan'] = $_POST['jenis_pemesanan'];
        $data['id_pemesanan'] = $_POST['id_pemesanan'];
        $data['no_kuitansi'] = $_POST['no_kuitansi'];
        $data['harga_pesanan'] = $_POST['harga_pesanan'];
        $data['totalbayar'] = $_POST['totalbayar'];
        $data['sisa_bayar'] = $_POST['sisa_bayar'];
        $data['besar_bayar'] = $_POST['besar_bayar'];
        $data['tgl_bayar'] = $_POST['tgl_bayar'];
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
                        'jenis_pemesanan' => $data['jenis_pemesanan'],
                        'id_pemesanan' => $data['id_pemesanan'],
                        'no_kuitansi' => $data['no_kuitansi'],
                        'harga_pesanan' => $data['harga_pesanan'],
                        'harga_deal' => $data['harga_deal'],
                        'totalbayar' => $data['totalbayar'],
                        'sisa_bayar' => $data['sisa_bayar']

                    ]);
            }else{
                //jika validasi tidak menemukan error
                if($data['besar_bayar']>0){
                    $hasil = $this->PembayaranModel->inputDataPembayaran();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                }
                
                
                $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdPemesanan($data['id_pemesanan'], $data['jenis_pemesanan']);
                $data['harga_deal'] = $data['harga_pesanan'];
        
                $totalbayar = 0; $sisa_bayar= 0;
                foreach($data['pembayaran'] as $row):
                    $id_pembayaran = $row->id_pembayaran;
                    $id_pemesanan = $row->id_pemesanan;
                    $jenis_pemesanan = $row->jenis_pemesanan;
                    $no_kuitansi = $row->no_kuitansi;
                    $tgl_bayar = $row->tgl_bayar;
                    $besar_bayar = $row->besar_bayar;
                    $totalbayar =  $totalbayar + $besar_bayar;
                endforeach;
                $sisa_bayar= $data['harga_deal']-$totalbayar;
        
                $data['customer'] = $this->PemesananRenovasiModel->getCustomerByIdRenov($data['id_renov']);
                foreach($data['customer'] as $row):
                    $id_customer = $row->id_customer;
                    $nama = $row->nama;
                    $alamat = $row->alamat;
                    $no_hp = $row->no_hp;
                endforeach;
        
                $data['nama_customer'] = ucfirst($nama);
                $data['alamat_customer'] = ucfirst($alamat);
                $data['no_hp_customer'] = $no_hp;
                // $data['id_renov'] = $id_renov;
                // $data['jenis_pemesanan'] = $jenis_pemesanan;
                // $data['id_pemesanan'] = $id_pemesanan;
                $data['harga_deal'] = rupiah($data['harga_deal']);
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