<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table = 'pemesanan';

    //untuk memasukkan data pemesanan
    public function insertData(){
        $id_customer = $_POST['id_customer'];
        $tgl_pesan = $_POST['tgl_pesan'];
        $tgl_renovasi = $_POST['tgl_renovasi'];
        $harga_deal = $_POST['harga_deal']; 
        $jenis_renovasi = $_POST['jenis_renovasi'];
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
        $sql = "INSERT INTO pemesanan SET id_customer=?, tgl_pesan=?, tgl_renovasi=?, jenis_renovasi=?,
                status_bayar=?,harga_deal=?
        ";
        $hasil = $this->db->query($sql, array($id_customer, $tgl_pesan, $tgl_renovasi, $jenis_renovasi, $status_bayar, $harga_deal));

        //dapatkan data id_pemesanan
        $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan WHERE id_customer = ? ", array($id_customer));
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$id_pesan = $row->id_pesan;
		}
        $sql = "INSERT INTO pembayaran SET id_pembayaran=?, id_pemesanan = ?, no_kuitansi=?, tgl_bayar=CURRENT_DATEx, besar_bayar=?";
        $hasil = $this->db->query($sql, array($id_transaksi, $id_pesan, $nomor_kuitansi, $besar_bayar));

    }    

}