<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananRenovasiModel extends Model
{
    protected $table = 'pemesanan_renovasi';
    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pemesanan_renovasi
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();
    }

    public function getHargaDeal($id){
        $sql = "SELECT harga_deal
                FROM pemesanan_renovasi
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

    public function getCustomerByIdRenov($id){
        $sql = "SELECT id_customer
                FROM pemesanan_renovasi
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        $hasil = $dbResult->getResult();
        
        foreach ($hasil as $row)
		{
			$id_customer = $row->id_customer;
		}

        $sql = "SELECT *
                FROM customer
                WHERE id_customer = ?
                ";
        $dbResult = $this->db->query($sql, array($id_customer));
        return $dbResult->getResult();
    }

    //untuk memasukkan data pemesanan_renovasi
    public function insertData(){
        $id_customer = $_POST['id_customer'];
        $tgl_pesan = $_POST['tgl_pesan'];
        $tgl_renovasi = $_POST['tgl_renovasi'];
        $jenis_renovasi = $_POST['jenis_renovasi'];
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

        //masukkan ke pemesanan_renovasi
        $sql = "INSERT INTO pemesanan_renovasi SET id_customer=?, tgl_pesan=?, tgl_renovasi=?, jenis_renovasi=?, 
                status_bayar=?,harga_deal=?
        ";
        $hasil = $this->db->query($sql, array($id_customer, $tgl_pesan, $tgl_renovasi, $jenis_renovasi, $status_bayar, $harga_deal));

        if($besar_bayar>0){

            //dapatkan data id_pemesanan
            $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan_renovasi WHERE id_customer = ? ", array($id_customer));
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $id_pesan = $row->id_pesan;
            }
            
            //generate nomer kuitansi dengan format KWI-20190520-3-001
            //KWI-THN_BLN_TGL-IDPESAN-NOMOR_URUT
            $sql = "SELECT substring(IFNULL(MAX(no_kuitansi),0),20)+0 as urutan FROM pembayaran 
                     WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -3),'-',1) = ".$id_pesan." 
                     AND SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = 0 
                     AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = ".date("Ymd", strtotime($tgl_pesan));
            $dbResult = $this->db->query($sql);
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $urutan = $row->urutan;
            }        
    
            //format nomor kuitansi
            $nomor_kuitansi = "KWI-".date("Ymd", strtotime($tgl_pesan))."-".($id_pesan)."-0-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;
    
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
                    'Renovasi', 
                    $nomor_kuitansi,
                    $tgl_pesan, 
                    $besar_bayar)
            );

            //dapatkan id transaksi untuk pembayaran
            $dbResult = $this->db->query("SELECT IFNULL(MAX(id_pembayaran),0) as id_transaksi from pembayaran");

            $hasil = $dbResult->getResult();
            //cacah hasilnya
            foreach ($hasil as $row)
            {
                $id_transaksi = $row->id_transaksi;
            }

            //pencatatan jurnal pada saat daftar renovasi (piutang pada pendapatan diterima dimuka)
                    $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                    SELECT a.id_pembayaran as id_transaksi, b.kode_akun, a.tgl_bayar, b.posisi, ".$harga_deal." as besar_bayar,b.kelompok,b.transaksi
                    FROM pembayaran a
                    CROSS JOIN transaksi_coa b
                    WHERE a.id_pembayaran = ? AND b.transaksi = 'pembayaran' AND b.kelompok = 2
            ";
            $hasil = $this->db->query($sql, array($id_transaksi));

            //pencatatan jurnal pada saat pembayaran DP (kas pada piutang)
            $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                    SELECT a.id_pembayaran as id_transaksi, b.kode_akun,a.tgl_bayar,b.posisi,a.besar_bayar,b.kelompok,b.transaksi
                    FROM pembayaran a
                    CROSS JOIN transaksi_coa b
                    WHERE a.id_pembayaran = ? AND b.transaksi = 'pembayaran' AND b.kelompok = 1
            ";
            $hasil = $this->db->query($sql, array($id_transaksi));
        }
    }
}