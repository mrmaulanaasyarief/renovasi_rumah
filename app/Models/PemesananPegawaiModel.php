<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananPegawaiModel extends Model
{
    protected $table = 'pemesanan_pegawai';

    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pemesanan_pegawai
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();
    }

    public function getHargaDeal($id){
        $sql = "SELECT total_gaji
                FROM pemesanan_pegawai
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        $hasil = $dbResult->getResult();
        
        foreach ($hasil as $row)
		{
			$harga_deal = $row->total_gaji;
		}
        return $harga_deal;
    }

    public function getByRenovId($id_renovasi){
        $sql = "SELECT *
                FROM pemesanan_pegawai
                WHERE id_renovasi = ?
                ";
        $dbResult = $this->db->query($sql, array($id_renovasi));
        return $dbResult->getResult();
    }

    public function isRenov($id_renovasi){
        $sql = "SELECT COUNT(1) AS jml
                FROM pemesanan_pegawai
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

        if($total_bayar>0){

            //dapatkan data id_pemesanan
            $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan_pegawai WHERE id_pegawai = ? ", array($id_pegawai));
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
                     AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = ".date("Ymd", strtotime($tanggal_pesan));
            $dbResult = $this->db->query($sql);
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $urutan = $row->urutan;
            }        
    
            //format nomor kuitansi
            $nomor_kuitansi = "KWI-".date("Ymd", strtotime($tanggal_pesan))."-".($id_renovasi)."-".($id_pesan)."-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;
    
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
                    'Pegawai', 
                    $nomor_kuitansi,
                    $tanggal_pesan, 
                    $total_bayar)
                );
        }

        //dapatkan id transaksi untuk pembayaran
        $dbResult = $this->db->query("SELECT IFNULL(MAX(id_pembayaran),0) as id_transaksi from pembayaran");

        $hasil2 = $dbResult->getResult();
        //cacah hasilnya
        foreach ($hasil2 as $row)
        {
            $id_transaksi = $row->id_transaksi;
        }

        //pencatatan jurnal pada saat daftar renovasi (piutang pada pendapatan diterima dimuka)
                $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                SELECT a.id_pembayaran as id_transaksi, b.kode_akun, a.tgl_bayar, b.posisi, ".$total_gaji." as besar_bayar,b.kelompok,b.transaksi
                FROM pembayaran a
                CROSS JOIN transaksi_coa b
                WHERE a.id_pembayaran = ? AND b.transaksi = 'pembebanan' AND b.kelompok = 2
        ";
        $dbResult = $this->db->query($sql, array($id_transaksi));

        //pencatatan jurnal pada saat pembayaran DP (kas pada piutang)
        $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                SELECT a.id_pembayaran as id_transaksi, b.kode_akun,a.tgl_bayar,b.posisi,a.besar_bayar,b.kelompok,b.transaksi
                FROM pembayaran a
                CROSS JOIN transaksi_coa b
                WHERE a.id_pembayaran = ? AND b.transaksi = 'pembebanan' AND b.kelompok = 3
        ";
        $dbResult = $this->db->query($sql, array($id_transaksi));

        return $hasil;
    }
}