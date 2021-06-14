<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananSupplierModel extends Model
{
    protected $table = 'pemesanan_supplier';

    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pemesanan_supplier
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();
    }

    public function getSupplierByIdPesan($id){
        $sql = "SELECT id_supplier
                FROM pemesanan_supplier
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        $hasil = $dbResult->getResult();
        
        foreach ($hasil as $row)
		{
			$id_supplier = $row->id_supplier;
		}

        $sql = "SELECT *
                FROM supplier
                WHERE id_supplier = ?
                ";
        $dbResult = $this->db->query($sql, array($id_supplier));
        return $dbResult->getResult();
    }

    public function getHargaDeal($id){
        $sql = "SELECT total_harga
                FROM pemesanan_supplier
                WHERE id_pesan = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        $hasil = $dbResult->getResult();
        
        foreach ($hasil as $row)
		{
			$harga_deal = $row->total_harga;
		}
        return $harga_deal;
    }

    //untuk memasukkan data pemesanan
    public function insertData(){
        $id_supplier= $_POST['id_supplier'];
        $tgl_pesan = $_POST['tanggal_pesan'];
        $tgl_ambil = $_POST['tanggal_ambil'];
        $harga = $_POST['harga_'];
        $jumlah_unit = $_POST['jumlah_unit'];
        $total_harga = $harga * $jumlah_unit;
        $besar_bayar = $_POST['besar_bayar'];

        //jangan lupa jika memakai masking maka dihilangkan dulu nilai maskingnya agar yang masuk ke db adalah murni numeriknya
        $total_harga = preg_replace( '/[^0-9 ]/i', '', $total_harga);
        $besar_bayar = preg_replace( '/[^0-9 ]/i', '', $besar_bayar);

        //jika besar bayar >= harga
        if($besar_bayar<$total_harga){
            //statusnya belum lunas
            $status_bayar = "Belum Lunas"; 
        }else{
            $status_bayar = "Lunas"; 
        }

        //masukkan ke pemesanan_supplier
        $sql = "INSERT INTO pemesanan_supplier SET id_supplier=?, tgl_pesan=?, tgl_ambil=?, 
        jumlah_unit=?, total_harga=?, status_bayar=?
        ";
        $hasil = $this->db->query($sql, array($id_supplier, $tgl_pesan, $tgl_ambil, $jumlah_unit, $total_harga, $status_bayar));

        if($besar_bayar>0){

            //dapatkan data id_pemesanan
            $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan_supplier WHERE id_supplier = ? ", array($id_supplier));
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $id_pesan = $row->id_pesan;
            }
            
            //generate nomer kuitansi dengan format KWI-20190520-1-1-001
            //KWI-THN_BLN_TGL-IDRENOVASI-IDPESAN-NOMOR_URUT
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
                    'Supplier', 
                    $nomor_kuitansi,
                    $tgl_pesan, 
                    $besar_bayar)
                );
        }
        
        return $hasil;

    }
}