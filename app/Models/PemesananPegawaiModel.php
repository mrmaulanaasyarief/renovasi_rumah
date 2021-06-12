<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananPegawaiModel extends Model
{
    protected $table = 'pemesanan_pegawai';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data pemesanan
    public function insertData(){
        $id_renovasi= $_POST['id_renovasi'];
        $id_pegawai = $_POST['id_pegawai'];
        //echo $id_penghuni;

        $tanggal_pesan = $_POST['tanggal_pesan'];
        $tanggal_kerja = $_POST['tanggal_kerja'];
        $jumlah_hari = $_POST['jumlah_hari'];
        $gaji = $_POST['gaji'];
        $total_gaji = $gaji * $jumlah_hari;
        $total_bayar = $_POST['total_bayar'];;
        
        //jangan lupa jika memakai masking maka dihilangkan dulu nilai maskingnya agar yang masuk ke db adalah murni numeriknya reguler expression
        $gaji = preg_replace( '/[^0-9 ]/i', '', $gaji);
        $total_gaji = preg_replace( '/[^0-9 ]/i', '', $total_gaji);
        $total_bayar = preg_replace( '/[^0-9 ]/i', '', $total_bayar);

        //jika total bayar >= harga
        if($total_gaji>$total_bayar){
            //statusnya belum lunas
            $status_gaji = "Belum Lunas"; 
        }else{
            $status_gaji = "Lunas"; 
        }
        //masukkan ke pemesanan
        
        $sql = "INSERT INTO pemesanan_pegawai SET 
            id_renovasi=?,
            id_pegawai=?, 
            tanggal_pesan=?, 
            tanggal_kerja=?,
            jumlah_hari=?, 
            gaji=?, 
            total_gaji=?, 
            total_bayar=?, 
            status_gaji=?
        ";
        $hasil = $this->db->query($sql, array(
            $id_renovasi,
            $id_pegawai,
            $tanggal_pesan,
            $tanggal_kerja,
            $jumlah_hari,
            $gaji,
            $total_gaji,
            $total_bayar,
            $status_gaji
        ));

        $sql = "SELECT * FROM pemesanan_pegawai WHERE id_renovasi=? AND id_pegawai=? AND tanggal_pesan=? AND tanggal_kerja=? AND jumlah_hari=? AND gaji=? AND total_gaji=? AND total_bayar=? AND status_gaji=?
        ";
        $hasil = $this->db->query($sql, array($id_renovasi, $id_pegawai,$tanggal_pesan,$tanggal_kerja,$jumlah_hari,$gaji,$total_gaji,$total_bayar,$status_gaji));

        foreach($hasil->getResultArray() as $row){
            $id_pesan= $row['id_pesan'];
        }
        
        // $nomor_kuitansi = "KWI/".$tgl_penggajian."/".$id_penggajian."/".$id;
        // $sql = "INSERT INTO pembayaran_pegawai SET id_penggajian=?, no_kuitansi=?, tgl_bayar = ?, besar_bayar=?, status=?
        // ";
        // $hasil = $this->db->query($sql, array($id_penggajian,$nomor_kuitansi,$tgl_penggajian,0,0));

        

        return $hasil;
        //dapatkan data id_pemesanan
        // $dbResult = $this->db->query("SELECT MAX(id_penggajian) as id_penggajian FROM penggajian_peg WHERE id_pegawai = ?", array($id_pegawai));
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