<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table = 'pemesanan';
    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data pemesanan
    public function insertData(){
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

        //masukkan ke pemesanan
        $sql = "INSERT INTO pemesanan SET id_jasa_desain=?, tgl_pesan=?, tgl_desain=?, 
                status_bayar=?,harga_deal=?
        ";
        $hasil = $this->db->query($sql, array($id_jasa_desain, $tgl_pesan, $tgl_desain, $status_bayar, $harga_deal));

        //dapatkan data id_pemesanan
        $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan WHERE id_jasa_desain = ? ", array($id_jasa_desain));
        $hasil = $dbResult->getResult();
        
    }    

}