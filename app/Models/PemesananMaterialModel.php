<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananMaterialModel extends Model
{
    protected $table = 'pemesanan_material';

    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pemesanan_material
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();
    }

    public function getHargaDeal($id){
        $sql = "SELECT total_trans
                FROM pemesanan_material
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        $hasil = $dbResult->getResult();
        
        foreach ($hasil as $row)
		{
			$harga_deal = $row->total_trans;
		}
        return $harga_deal;
    }

    public function getByRenovId($id_renovasi){
        $sql = "SELECT *
                FROM pemesanan_material
                WHERE id_renovasi = ?
                ";
        $dbResult = $this->db->query($sql, array($id_renovasi));
        return $dbResult->getResult();
    }

    public function isRenov($id_renovasi){
        $sql = "SELECT COUNT(1) AS jml
                FROM pemesanan_material
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

        if($besar_bayar>0){

            //dapatkan data id_pemesanan
            $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan_material WHERE id_material = ? ", array($id_material));
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
                    'Material', 
                    $nomor_kuitansi,
                    $tanggal_pesan, 
                    $besar_bayar)
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
                SELECT a.id_pembayaran as id_transaksi, b.kode_akun, a.tgl_bayar, b.posisi, ".$total_trans." as besar_bayar,b.kelompok,b.transaksi
                FROM pembayaran a
                CROSS JOIN transaksi_coa b
                WHERE a.id_pembayaran = ? AND b.transaksi = 'pembebanan' AND b.kelompok = 4
        ";
        $dbResult = $this->db->query($sql, array($id_transaksi));

        //pencatatan jurnal pada saat pembayaran DP (kas pada piutang)
        $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                SELECT a.id_pembayaran as id_transaksi, b.kode_akun,a.tgl_bayar,b.posisi,a.besar_bayar,b.kelompok,b.transaksi
                FROM pembayaran a
                CROSS JOIN transaksi_coa b
                WHERE a.id_pembayaran = ? AND b.transaksi = 'pembebanan' AND b.kelompok = 5
        ";
        $dbResult = $this->db->query($sql, array($id_transaksi));
        


        return $hasil;

    }
}