<?php

namespace App\Models;

use CodeIgniter\Model;

class Pemesanan_Model extends Model
{
    protected $table = 'pemesanan';

    //untuk memasukkan data pemesanan
    public function insertData(){
        $id_penghuni = $_POST['id_penghuni'];
        //echo $id_penghuni;
        
        $id_kamar = $_POST['id_kamar'];
        $tgl_selesai = $_POST['tgl_selesai2'];
        $tgl_mulai = $_POST['tgl_mulai'];
        $harga_deal = $_POST['harga_deal']; 
        $besar_bayar = $_POST['besar_bayar'];
        
        //jangan lupa jika memakai masking maka dihilangkan dulu nilai maskingnya agar yang masuk ke db adalah murni numeriknya
        $harga_deal = preg_replace( '/[^0-9 ]/i', '', $harga_deal);
        $besar_bayar = preg_replace( '/[^0-9 ]/i', '', $besar_bayar);

        //jika besar bayar >= harga
        if($besar_bayar<$harga_deal){
            //statusnya belum lunas
            $status = "Belum Lunas"; 
        }else{
            $status = "Lunas"; 
        }

        //masukkan ke pemesanan
        $sql = "INSERT INTO pemesanan SET id_penghuni = ?, id_kamar=?, tgl_pesan=CURRENT_DATE, tgl_mulai=?, 
                tgl_selesai = ?,status_bayar = ?,status_kamar='Isi' ,harga_deal=?
        ";
        $hasil = $this->db->query($sql, array($id_penghuni, $id_kamar, $tgl_mulai, $tgl_selesai, $status, $harga_deal));

        //dapatkan data id_pemesanan
        $dbResult = $this->db->query("SELECT MAX(id_pesan) as id_pesan FROM pemesanan WHERE id_penghuni = ? AND id_kamar=? ", array($id_penghuni, $id_kamar));
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$id_pesan = $row->id_pesan;
		}


        //update status kemar menjadi isi pada master data kamar 
        $sql = "UPDATE kamar SET status = 'Isi' WHERE id = ?";
        $hasil = $this->db->query($sql, array($id_kamar));

        //dapatkan id kosan
        $sql = "SELECT id_kos FROM kamar WHERE id = ?";
        $dbResult = $this->db->query($sql, array($id_kamar));
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$id_kos = $row->id_kos;
		}        

        //generate nomer kuitansi dengan format KWI-20190520-3-001
        //KWI-THN_BLN_TGL-IDKOSAN-NOMOR_URUT
        $sql = "SELECT substring(IFNULL(MAX(no_kuitansi),0),16)+0 as urutan FROM pembayaran 
                WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = ".$id_kos." 
                AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = DATE_FORMAT(CURRENT_DATE,'%Y%m%d')";
        $dbResult = $this->db->query($sql);
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
		{
			$urutan = $row->urutan;
		}        

        //format nomor kuitansi
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
        $sql = "INSERT INTO pembayaran SET id_pembayaran=?, id_pemesanan = ?, no_kuitansi=?, tgl_bayar=CURRENT_DATE, besar_bayar=?";
        $hasil = $this->db->query($sql, array($id_transaksi, $id_pesan, $nomor_kuitansi, $besar_bayar));

        //pencatatan jurnal pada saat daftar kosan (piutang pada pendapatan diterima dimuka)
        $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                    SELECT a.id_pembayaran as id_transaksi, b.kode_akun,a.tgl_bayar,b.posisi, ".$harga_deal." as besar_bayar,b.kelompok,b.transaksi
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

        return $hasil;
        
    }

    //method untuk menampilkan status kamar
    public function getListKamar($id_kosan){
        $sql = "SELECT a.*,ifnull(b.tgl_selesai,'-') as tgl_selesai,ifnull(c.nama,'-') as nama
                FROM kamar a
                LEFT OUTER JOIN 
                (SELECT * FROM pemesanan WHERE status_kamar = 'Isi') b
                ON (a.id=b.id_kamar)
                LEFT OUTER JOIN penghuni c
                ON (b.id_penghuni=c.id)
                WHERE id_kos = ?
                ORDER BY a.lantai, a.nomer
                ";

        $dbResult = $this->db->query($sql, array($id_kosan));
        $hasil = $dbResult->getResult();        
        return $hasil;
    }

    //method untuk pindah kamar kosan
    public function updateKamar($id_kamar_lama, $id_kamar_baru){
        //Cari tahu id_pemesanan sebagai kunci untuk update data
        $sql = "SELECT MAX(id_pesan) as id_pesan FROM pemesanan
                WHERE id_kamar = ? AND status_kamar = 'Isi'";

        $dbResult = $this->db->query($sql, array($id_kamar_lama));
        $hasil = $dbResult->getResult();      
        foreach ($hasil as $row)
		{
			$id_pesan = $row->id_pesan;
		}  
        
        //UPDATE data kamar di set ke Kosong untuk status kamarnya
        $sql = "UPDATE kamar SET `status` = 'Kosong' WHERE id = ?";
        $hasil = $this->db->query($sql, array($id_kamar_lama));

        //UPDATE data kamar baru di set ke Isi untuk status kamarnya
        $sql = "UPDATE kamar SET `status` = 'Isi' WHERE id = ?";
        $hasil = $this->db->query($sql, array($id_kamar_baru));

        //UPDATE data pemesanan lama di set id_kamarnya ke id kamar yang baru
        $sql = "UPDATE pemesanan SET id_kamar = ? WHERE id_pesan = ?";
        $hasil = $this->db->query($sql, array($id_kamar_baru, $id_pesan));

        return $hasil;
    }

    //method untuk mengubah pesanan kamar yg isi ke kosong
    public function updateStatusKamar($id_kamar){
        //ubah status kamar menjadi kosong
        $sql = "UPDATE kamar SET `status` = 'Kosong' WHERE id = ?";
        $hasil = $this->db->query($sql, array($id_kamar));

        //ubah status kamar pada tabel pemesanan menjadi Selesai
        $sql = "UPDATE pemesanan SET status_kamar = 'Selesai' 
                WHERE id_pesan = (SELECT MAX(id_pesan) FROM pemesanan WHERE id_kamar = ?)";
        $hasil = $this->db->query($sql, array($id_kamar));

        return $hasil;
    }

}