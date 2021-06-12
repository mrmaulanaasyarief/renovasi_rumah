<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananMaterialModel extends Model
{
    protected $table = 'pemesanan_material';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data pemesanan
    public function insertData(){
        $id_renovasi= $_POST['id_renovasi'];
        $id_material = $_POST['id_material'];
        $tanggal_pesan = $_POST['tanggal_pesan'];
        $tanggal_ambil = $_POST['tanggal_ambil'];
        $total_trans = $_POST['total_trans'];
        $besar_bayar = $_POST['besar_bayar'];

        //jangan lupa jika memakai masking maka dihilangkan dulu nilai maskingnya agar yang masuk ke db adalah murni numeriknya
        $total_trans = preg_replace( '/[^0-9 ]/i', '', $total_trans);
        $besar_bayar = preg_replace( '/[^0-9 ]/i', '', $besar_bayar);

        //jika besar bayar >= harga
        if($besar_bayar<$total_trans){
            //statusnya belum lunas
            $status_bayar = "Belum Lunas"; 
        }else{
            $status_bayar = "Lunas"; 
        }

        //masukkan ke pemesanan_material
        $sql = "INSERT INTO pemesanan_material SET id_renovasi=?, id_material=?, tanggal_pesan=?, tanggal_ambil=?, 
        status_bayar=?,total_trans=?
        ";
        $hasil = $this->db->query($sql, array($id_renovasi, $id_material, $tanggal_pesan, $tanggal_ambil, $status_bayar, $total_trans));

        return $hasil;
        //dapatkan data id_pemesanan
        // $dbResult = $this->db->query("SELECT MAX(id_pencatatan) as id_pencatatan FROM pencatatan_peg WHERE id_pegawai = ?", array($id_pegawai));
        // $hasil = $dbResult->getResult();
        // foreach ($hasil as $row)
		// {
		// 	$id_pencatatan = $row->id_pencatatan;
		// }

       /* //format nomor kuitansi
        $nomor_kuitansi = "KWI-".date("Ymd")."-".$id_kos."-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;

        //dapatkan id transaksi untuk pembayaran
        $dbResult = $this->db->query("SELECT IFNULL(MAX(id_transaksi),0) as id_transaksi from view_transaksi");

        $hasil = $dbResult->getResult();
        //cacah hasilnya
        foreach ($hasil as $row)
        {
            $id_transaksi = $row->id_transaksi;
        }
        $id_transaksi = $id_transaksi+1; //naikkan 1 untuk id baru modal yang dimasukkan

        //masukkan ke pembayaran
        $sql = "INSERT INTO pembayaran_pegawai SET id_pembayaran=?, id_pencatatan = ?, no_kuitansi=?, tgl_bayar=CURRENT_DATE, besar_bayar=?";
        $hasil = $this->db->query($sql, array($id_transaksi, $id_pesan, $nomor_kuitansi, $besar_bayar));*/
    }
}