<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananJasaDesainModel extends Model
{
    protected $table = 'pemesanan_jasa_desain';
    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pemesanan_jasa_desain
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();
    }

    public function getHargaDeal($id){
        $sql = "SELECT harga_deal
                FROM pemesanan_jasa_desain
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        $hasil = $dbResult->getResult();
        
        foreach ($hasil as $row)
		{
			$harga_deal = $row->harga_deal;
		}
        return $harga_deal;
    }

    public function getByRenovId($id_renovasi){
        $sql = "SELECT *
                FROM pemesanan_jasa_desain
                WHERE id_renovasi = ?
                ";
        $dbResult = $this->db->query($sql, array($id_renovasi));
        return $dbResult->getResult();
    }

    public function isRenov($id_renovasi){
        $sql = "SELECT COUNT(1) AS jml
                FROM pemesanan_jasa_desain
                WHERE id_renovasi = ?
                ";
        $dbResult = $this->db->query($sql, array($id_renovasi));
        
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$jml = $row->jml;
		}
        return $jml;
    }

    //untuk memasukkan data pemesanan_jasa_desain
    public function insertData(){
        $id_renovasi= $_POST['id_renovasi'];
        $id_jasa_desain= $_POST['id_jasa_desain'];
        $tgl_pesan = $_POST['tgl_pesan'];
        $tgl_desain = $_POST['tgl_desain'];
        $harga_deal = $_POST['harga_deal']; 
        $besar_bayar = $_POST['besar_bayar'];
        
        //jangan lupa jika memakai masking maka dihilangkan dulu nilai maskingnya agar yang masuk ke db adalah murni numeriknya
        $harga_deal = preg_replace( '/[^0-9 ]/i', '', $harga_deal);
        $besar_bayar = preg_replace( '/[^0-9 ]/i', '', $besar_bayar);

        //jika besar bayar >= harga
        if($besar_bayar<$harga_deal){
            //statusnya belum lunas
            $status_bayar = "Belum Lunas"; 
        }else{
            $status_bayar = "Lunas"; 
        }

        //masukkan ke pemesanan_jasa_desain
        $sql = "INSERT INTO pemesanan_jasa_desain SET id_renovasi=?, id_jasa_desain=?, tgl_pesan=?, tgl_desain=?, 
                status_bayar=?,harga_deal=?
        ";
        $hasil = $this->db->query($sql, array($id_renovasi, $id_jasa_desain, $tgl_pesan, $tgl_desain, $status_bayar, $harga_deal));

        if($besar_bayar>0){

            //dapatkan data id_pemesanan
            $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan_jasa_desain WHERE id_jasa_desain = ? ", array($id_jasa_desain));
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $id_pesan = $row->id_pesan;
            }
            
            //generate nomer kuitansi dengan format KWI-20190520-1-1-001
            //KWI-THN_BLN_TGL-IDRENOVASI-IDPESAN-NOMOR_URUT
            $sql = "SELECT substring(IFNULL(MAX(no_kuitansi),0),20)+0 as urutan FROM pembayaran 
                     WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -3),'-',1) = ".$id_renovasi." 
                     AND SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = ".$id_pesan." 
                     AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = ".date("Ymd", strtotime($tgl_pesan));
            $dbResult = $this->db->query($sql);
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $urutan = $row->urutan;
            }        
    
            //format nomor kuitansi
            $nomor_kuitansi = "KWI-".date("Ymd", strtotime($tgl_pesan))."-".($id_renovasi)."-".($id_pesan)."-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;
    
            // //dapatkan id transaksi untuk pembayaran
            // $dbResult = $this->db->query("SELECT IFNULL(MAX(id_transaksi),+1) as id_transaksi from view_transaksi");
    
            // $hasil = $dbResult->getResult();
            // //cacah hasilnya
            // foreach ($hasil as $row)
            // {
            //     $id_transaksi = $row->id_transaksi;
            // }
            // $id_transaksi = $id_transaksi+1; //naikkan 1 untuk id baru modal yang dimasukkan
    
            //masukkan ke pembayaran
            $sql = "INSERT INTO pembayaran SET 
                        id_pemesanan = ?, 
                        jenis_pemesanan = ?, 
                        no_kuitansi=?, 
                        tgl_bayar=?, 
                        besar_bayar=?";
            $hasil = $this->db->query($sql, 
                array(
                    $id_pesan, 
                    'Jasa Desain', 
                    $nomor_kuitansi,
                    $tgl_pesan, 
                    $besar_bayar)
                );
        }
        
    }    

}