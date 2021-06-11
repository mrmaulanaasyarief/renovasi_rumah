<?php

namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\MaterialModel;
use Dompdf\Options;

class Datapemesanan extends BaseController
{
	public function __construct()
    {
		session_start();
        $this->PemesananModel = new PemesananModel();
        $this->MaterialModel = new MaterialModel();
        helper('rupiah');
        helper('waktu');
    }

    //data table pembayaran
    public function tabelpemesanan(){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['pemesanan'] = $this->PemesananModel->getInfoPemesanan();

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/ListPemesanan', $data);
    }

    //litys kosan
    public function daftarmaterial(){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['alatbahan'] = $this->materialmodel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['alatbahan'] as $row):
            array_push($ar, array($row['id_material'],0,0,0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        //hasil array jumlah data kosan
        $data['infomaterial'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/Daftarmaterial', $data);
    }
}    