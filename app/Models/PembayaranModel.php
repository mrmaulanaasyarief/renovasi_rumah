<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran_supplier';

    public function getAll(){
        return $this->findAll();
    }

    //method untuk menampilkan informasi data pembayaran
    public function getInfoPembayaran(){
        $sql = "SELECT c.id as id_supplier, c.nama as nama_supplier, e.alamat, 
                        e.jenis_renovasi,
                        CONCAT('nama ',a.nama,' (',a.nomer,')') AS customer, b.status_bayar, d.no_kuitansi,
                        a.id as id_supplier,d.tgl_bayar,d.besar_bayar,d.id_pembayaran
                        FROM customer a
                JOIN pemesanan b ON (a.id=b.id_customer)
                JOIN pembayaran c ON (b.id_pesan=d.id_pemesanan)";
        $dbResult = $this->db->query($sql);
        return $dbResult->getResult();        
    }

    //method untuk menampilkan informasi data pembayaran
    public function getInfoPembayaranById($id_pembayaran){
        $sql = "SELECT c.id as id_customer, c.nama as nama_penghuni, e.alamat, 
                        e.jenis_renovasi,
                        CONCAT('nama ',a.nama,' (',a.nomer,')') AS customer, b.status_bayar, d.no_kuitansi,
                        a.id as id_customer,d.tgl_bayar,d.besar_bayar,d.id_pembayaran,
                        b.harga_deal
                FROM customer a
                JOIN pemesanan b ON (a.id=b.id_customer)
                JOIN pembayaran c ON (b.id_pesan=d.id_pemesanan)
                WHERE c.id_pembayaran = ?
                ";
        $dbResult = $this->db->query($sql, array($id_pembayaran));
        return $dbResult->getResult();        
    }

    //method untuk menampilkan status pembayaran per kamar

    //method untuk menampilkan history pembayaran untuk id kamar tertentu
    public function getHistoryPembayaranByIdCustomer($id_customer){
        $sql = "SELECT c.id as id_customer, c.nama as nama_penghuni, e.alamat, 
                        e.jenis_renovasi,
                        CONCAT('nama ',a.nama,' (',a.nomer,')') AS customer, b.status_bayar, d.no_kuitansi,
                        a.id as id_customer,d.tgl_bayar,d.besar_bayar,d.id_pembayaran,b.harga_deal,
                        c.id_pesan
                        FROM customer a
                JOIN pemesanan b ON (a.id=b.id_customer)
                JOIN pembayaran c ON (b.id_pesan=d.id_pemesanan)
                WHERE c.id_pembayaran = ?
                ";
        $dbResult = $this->db->query($sql, array($id_kamar));
        return $dbResult->getResult();    
    }

    //dapatkan nomor kuitansi
    public function getNoKuitansi($id_kos){
        //generate nomer kuitansi dengan format KWI-20190520-3-001
        //KWI-THN_BLN_TGL-IDKOSAN-NOMOR_URUT
        $sql = "SELECT substring(IFNULL(MAX(no_kuitansi),0),16)+0 as urutan, DATE_FORMAT(CURRENT_DATE,'%Y%m%d') as skrg FROM pembayaran 
                WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = ".$id_customer." 
                AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = DATE_FORMAT(CURRENT_DATE,'%Y%m%d')";
        $dbResult = $this->db->query($sql);
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$urutan = $row->urutan;
            $tgl = $row->skrg;
		}        

        //format nomor kuitansi
        $nomor_kuitansi = "KWI-".$tgl."-".$id_customer."-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;

        return $nomor_kuitansi;
    }

    //untuk input data pembayaran
    public function inputDataPembayaran($id_pesan,$nokuitansi){

        //dapatkan id transaksi untuk pembayaran
        $dbResult = $this->db->query("SELECT IFNULL(MAX(id_transaksi),0) as id_transaksi from view_transaksi");

        $hasil = $dbResult->getResult();
        //cacah hasilnya
        foreach ($hasil as $row)
        {
            $id_transaksi = $row->id_transaksi;
        }
        $id_transaksi = $id_transaksi+1; //naikkan 1 untuk id baru modal yang dimasukkan

        $besar_bayar = preg_replace( '/[^0-9 ]/i', '', $_POST['besar_bayar']);
        $sql = "INSERT INTO pembayaran SET id_pembayaran = ?, id_pemesanan = ?, no_kuitansi = ?, tgl_bayar = CURRENT_DATE, besar_bayar = ?";
        $dbHasil = $this->db->query($sql, array($id_transaksi, $id_pesan,$nokuitansi,$besar_bayar));
        
        //cek apakah sudah lunas atau belum, jika sudah lunas, maka statusnya diganti menjadi lunas pada tabel pemesana
        $sql = "    SELECT SUM(a.besar_bayar) as besar_bayar,
                        (SELECT harga_deal FROM pemesanan WHERE id_pesan = a.id_pemesanan) as harga_deal
                    FROM pembayaran a
                    WHERE a.id_pemesanan = ?
                ";
        $dbResult = $this->db->query($sql, array($id_pesan));
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$besar_bayar = $row->besar_bayar;
            $harga_deal = $row->harga_deal;
		}  

        if(($harga_deal-$besar_bayar)<=0){
            $sql = "UPDATE pemesanan SET status_bayar = 'Lunas' WHERE id_pesan =?";
            $dbResult = $this->db->query($sql, array($id_pesan));
        }


        return $dbHasil;
    }

    //dapatkan data kamar berdasarkan id pesan
    public function getDataKamarByIdPesan($id_pesan){
        $sql = "SELECT a.*, CURRENT_DATE as tanggal_sekarang
                FROM customer a
                JOIN 
                (SELECT * FROM pemesanan WHERE status_bayar = 'Lunas') b 
                ON (a.id=b.id_customer)
                WHERE b.id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id_pesan));
        return $dbResult->getResult();    
    }
    

    //hitung sisa bayar berdasarkan id_pembayaran tertentu
    public function getSisaBayar($id_bayar){
        //dapatkan harga deal
        $sql = "SELECT harga_deal,id_pesan FROM pemesanan WHERE id_pesan =
                (SELECT id_pemesanan FROM pembayaran WHERE id_pembayaran = ?)
                ";
        $dbResult = $this->db->query($sql, array($id_bayar));
        foreach($dbResult->getResult() as $row):
            $harga_deal = $row->harga_deal;
            $id_pesan = $row->id_pesan;
        endforeach;    

        //hitung seluruh pembayaran untuk id_pesan
        $sql = "SELECT SUM(besar_bayar) AS besar_bayar FROM pembayaran
                WHERE id_pemesanan = ? AND id_pembayaran <= ?
                ";
        $dbResult = $this->db->query($sql, array($id_pesan, $id_bayar));
        foreach($dbResult->getResult() as $row):
            $besar_bayar = $row->besar_bayar;
        endforeach;     

        //hitung selisih sisa bayarnya
        return ($harga_deal-$besar_bayar);
    }
}