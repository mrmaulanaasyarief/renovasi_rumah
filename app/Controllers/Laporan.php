<?php

namespace App\Controllers;

use App\Models\PembayaranModel;

class Laporan extends BaseController
{
	public function __construct()
    {
		session_start();
        $this->PembayaranModel = new PembayaranModel();
        helper('rupiah');
        helper('waktu');
    }

    //data table pembayaran
    public function tabelpembayaran(){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['pembayaran'] = $this->PembayaranModel->getInfoPembayaran();

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/ListPembayaran', $data);
    }

    //cetak kuitansi
    public function kuitansi($id_pembayaran, $nama_customer, $sisa_bayar){
        helper('rupiah');
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['kuitansi'] = $this->PembayaranModel->getById($id_pembayaran);
        $data['nama_customer'] = $nama_customer;
        $data['sisa_bayar'] = $sisa_bayar;
        //echo view('Laporan/Cetakkuitansi', $data);
        //echo view('Laporan/Cetakkuitansi2', $data);
        
        $html = view('Laporan/Cetakkuitansidompdf', $data);

        //panggil dom untuk cetak pdf
        $dompdf = new \Dompdf\Dompdf(); 
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
        
    }

    //cetak kuitansi 2
    public function kuitansi2($id_pembayaran){
        helper('rupiah');
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['kuitansi'] = $this->PembayaranModel->getInfoPembayaranById($id_pembayaran);
        $data['sisa_bayar'] = $this->PembayaranModel->getSisaBayar($id_pembayaran);
        echo view('Laporan/Cetakkuitansi2', $data);
    
    }


    //litys kosan
    public function daftarkosan(){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['koskosan'] = $this->kosanmodel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['koskosan'] as $row):
            array_push($ar, array($row['id_kos'],0,0,0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        for($i=0;$i<count($ar);$i++){
            $ar[$i][1] = $this->kosanmodel->getInfoKamar($ar[$i][0], 'all');
            $ar[$i][2] = $this->kosanmodel->getInfoKamar($ar[$i][0], 'Isi');
            $ar[$i][3] = $this->kosanmodel->getInfoKamar($ar[$i][0], 'Kosong');
        }
        //hasil array jumlah data kosan
        $data['infokosan'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/Daftarkosan', $data);
    }

    //status pembayaran tiap kamar
    public function statusbayarperkamar($id_kos, $namakos){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        helper('rupiah');
        $data['pembayaran'] = $this->PembayaranModel->getInfoPembayaranPerKamar($id_kos);
        $data['namakos'] = $namakos;
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/ListPembayaranPerKosan', $data);
    }
    
    //lihat beban
    public function lihatbeban(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['beban'] = $this->PembebananModel->getListBeban();
        //maka kembalikan ke awal
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pembebanan/LihatBeban', $data);
    }

    //jurnal umum
    public function jurnalumum(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['koskosan'] = $this->kosanmodel->getAll();
        $data['tahun'] = $this->CoaModel->getPeriodeTahun();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/Jurnal', $data);
    }

    //json encode untuk list bulan
    public function listbulan($tahun){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        //encode
        echo json_encode($this->CoaModel->getPeriodeBulan($tahun));
    }

    //proses lihat jurnal umum
    public function lihatjurnalumum(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['jurnal'] = $this->CoaModel->getJurnalUmum($_POST['tahun'], $_POST['bulan']);
        $data['kosan'] = $this->kosanmodel->editData($_POST['namakos']);
        $data['bulan'] = $_POST['bulan'];
        $data['tahun'] = $_POST['tahun'];
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/LihatJurnal', $data);
    }

    //buku besar
    public function bukubesar(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['koskosan'] = $this->kosanmodel->getAll();
        $data['tahun'] = $this->CoaModel->getPeriodeTahun();
        $data['namaakun'] = $this->CoaModel->getNamaAkun();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/BukuBesar', $data);
    }

    //proses lihat buku besar
    public function lihatbukubesar(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['jurnal'] = $this->CoaModel->getJurnalUmum($_POST['tahun'], $_POST['bulan']);
        $data['kosan'] = $this->kosanmodel->editData($_POST['namakos']);
        
        $akun = $_POST['akun'];
        //explode untuk mendapatkan kode akun dan nama akun kode_akun|nama_akun
        $akuncacah = explode("|",$akun);
        //print_r($akuncacah);

        
        $data['bulan'] = $_POST['bulan'];
        $data['tahun'] = $_POST['tahun'];
        $data['kodeakun'] = $akuncacah[0];
        $data['namaakun'] = $akuncacah[1];
        $data['bukubesar'] = $this->CoaModel->getBukuBesar($data['tahun'], $data['bulan'], $data['kodeakun']);
        $data['saldoawal'] = $this->CoaModel->getSaldoAwal($data['bulan'],$data['tahun'],$data['kodeakun']);
        $data['posisisaldonormal'] = $this->CoaModel->getPosisiSaldoNormal($data['kodeakun']);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/LihatBukuBesar', $data);
        
    }

    //laba rugi
    public function labarugi(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['koskosan'] = $this->kosanmodel->getAll();
        $data['tahun'] = $this->CoaModel->getPeriodeTahun();

        //eksekusi pencatatan akrual jurnal
		$this->CoaModel->setPendapatanAkrual();

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/LabaRugi', $data);
    }

    //proses lihat laba rugi
    public function lihatlabarugi(){
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }
        $data['kosan'] = $this->kosanmodel->editData($_POST['namakos']);
        

        $data['bulan'] = $_POST['bulan'];
        $data['tahun'] = $_POST['tahun'];
        $data['pendapatan'] = $this->CoaModel->getPendapatanOperasional($_POST['namakos'], $data['bulan'],$data['tahun']);
        $data['beban'] = $this->CoaModel->getBeban($_POST['namakos'],$data['bulan'],$data['tahun']);

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Laporan/LihatLabaRugi', $data);
        
    }
}    