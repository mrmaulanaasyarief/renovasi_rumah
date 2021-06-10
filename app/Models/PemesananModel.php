<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table = 'pemesanan_supplier';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data pemesanan
    public function insertData(){
        $id_supplier = $_POST['id_supplier'];
        $tgl_pesan = $_POST['tanggal_pesan'];
        $tgl_ambil = $_POST['tanggal_ambil'];
        $harga = $_POST['harga'];
        $jumlah = $_POST['jumlah'];
        $diskon = $_POST['diskon']; 
        $alamat = $_POST['alamat_kirim'];
        $ongkir = $_POST['ongkir'];
        $total_jual = (($harga * $jumlah) - $diskon) + $ongkir ; 
        $besar_bayar = $_POST['besar_bayar'];
        
        //jangan lupa jika memakai masking maka dihilangkan dulu nilai maskingnya agar yang masuk ke db adalah murni numeriknya
        $harga = preg_replace( '/[^0-9 ]/i', '', $harga);
        $diskon = preg_replace( '/[^0-9 ]/i', '', $diskon);
        $ongkir = preg_replace( '/[^0-9 ]/i', '', $ongkir);
        $total_jual = preg_replace( '/[^0-9 ]/i', '', $total_jual);

        //jika besar bayar >= harga
        if($besar_bayar<$total_jual){
            //statusnya belum lunas
            $status = "Belum Lunas"; 
        }else{
            $status = "Lunas"; 
        }

        //masukkan ke pemesanan
        $sql = "INSERT INTO pemesanan_supplier SET id_supplier=?, tanggal_pesan=?, tanggal_ambil=?, 
                harga = ?,diskon = ?,alamat_kirim=? ,ongkos_kirim=?, total_jual=?, status_bayar = ?
        ";
        $hasil = $this->db->query($sql, array($id_supplier, $tgl_pesan, $tgl_ambil, $harga, $diskon, $alamat, $ongkir, $total_jual,$status));
        return $hasil;
        //dapatkan data id_pemesanan
        // $dbResult = $this->db->query("SELECT MAX(id_pemesanan) as id_pemesanan FROM pemesanan_supplier WHERE id_supplier = ?", array($id_supplier));
        // $hasil = $dbResult->getResult();
        // foreach ($hasil as $row)
		// {
		// 	$id_pesan = $row->id_pemesanan;
		// }

        //format nomor kuitansi
        // $nomor_kuitansi = "KWI-".date("Ymd")."-".$id_pesan."-"; //-001;

        //dapatkan id transaksi untuk pembayaran
        /*$dbResult = $this->db->query("SELECT IFNULL(MAX(id_transaksi),0) as id_transaksi from view_transaksi");

        $hasil = $dbResult->getResult();
        //cacah hasilnya
        foreach ($hasil as $row)
        {
            $id_transaksi = $row->id_transaksi;
        }
        $id_transaksi = $id_transaksi+1; //naikkan 1 untuk id baru modal yang dimasukkan

        //masukkan ke pembayaran
        $sql = "INSERT INTO pembayaran_supplier SET id_pembayaran=?, id_pemesanan = ?, no_kuitansi=?, tgl_bayar=CURRENT_DATE, besar_bayar=?";
        $hasil = $this->db->query($sql, array($id_transaksi, $id_pesan, $nomor_kuitansi, $besar_bayar));*/
    }

}







