<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table = 'v_laporan';

    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pembayaran
                WHERE id_pembayaran = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();       
    }
    //untuk data list tahun
    public function getPeriodeTahun(){
        $dbResult = $this->db->query("SELECT DISTINCT(YEAR(tgl_bayar)) as tahun FROM v_laporan ORDER BY 1");
        return $dbResult->getResult();
    }

    //untuk data list tahun
    public function getPeriodeTahunBulan(){
        $dbResult = $this->db->query("SELECT DISTINCT(YEAR(tgl_bayar)) as tahun, 
            DATE_FORMAT(tgl_bayar,'%M') AS bulan, DATE_FORMAT(tgl_bayar,'%m') as bulan_angka
        FROM v_laporan ORDER BY 1");
        return $dbResult->getResult();
    }

    //untuk data list tahun
    public function getPeriodeBulan($tahun){
        $sql = "SELECT DATE_FORMAT(tgl_bayar,'%M') as bulan, DATE_FORMAT(tgl_bayar,'%m') as bulan_angka 
                FROM v_laporan WHERE YEAR(tgl_bayar) = ?
                GROUP BY DATE_FORMAT(tgl_bayar,'%M'), DATE_FORMAT(tgl_bayar,'%m') ORDER BY 2";
        $dbResult = $this->db->query($sql, array($tahun));
        //dikembalikan dalam bentuk array
        return $dbResult->getResult('array');
    }

    //untuk data jurnal
    public function getJurnalUmum($tahun, $bulan){
        $sql = "SELECT *  
                FROM v_laporan
                WHERE  YEAR(tgl_bayar) = ? AND DATE_FORMAT(tgl_bayar,'%m') = ?";
        $dbResult = $this->db->query($sql, array($tahun, $bulan));
        return $dbResult->getResult();
    }
}