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
        $sql = "SELECT c.id as id_penghuni, c.ktp, c.nama as nama_penghuni, e.id_kos, 
                        e.nama as nama_kos,
                        CONCAT('Lt ',a.lantai,' (',a.nomer,')') AS kmr, b.status_bayar, d.no_kuitansi,
                        a.id as id_kamar,d.tgl_bayar,d.besar_bayar,d.id_pembayaran
                FROM kamar a
                JOIN pemesanan b ON (a.id=b.id_kamar)
                JOIN penghuni c ON (b.id_penghuni=c.id)
                JOIN pembayaran d ON (b.id_pesan=d.id_pemesanan)
                JOIN kos e ON (a.id_kos=e.id_kos)";
        $dbResult = $this->db->query($sql);
        return $dbResult->getResult();        
    }

    //method untuk menampilkan informasi data pembayaran
    public function getInfoPembayaranById($id_pembayaran){
        $sql = "SELECT c.id as id_penghuni, c.ktp, c.nama as nama_penghuni, e.id_kos, 
                        e.nama as nama_kos,
                        CONCAT('Lt ',a.lantai,' (',a.nomer,')') AS kmr, b.status_bayar, d.no_kuitansi,
                        a.id as id_kamar,d.tgl_bayar,d.besar_bayar,d.id_pembayaran,
                        b.harga_deal
                FROM kamar a
                JOIN pemesanan b ON (a.id=b.id_kamar)
                JOIN penghuni c ON (b.id_penghuni=c.id)
                JOIN pembayaran d ON (b.id_pesan=d.id_pemesanan)
                JOIN kos e ON (a.id_kos=e.id_kos)
                WHERE d.id_pembayaran = ?
                ";
        $dbResult = $this->db->query($sql, array($id_pembayaran));
        return $dbResult->getResult();        
    }

    //method untuk menampilkan status pembayaran per kamar
    public function getInfoPembayaranPerKamar($id_kos){
        $sql = "
                        SELECT  a.*,ifnull(b.tgl_selesai,'-') as tgl_selesai,ifnull(c.nama,'-') as nama,
                                b.id_pesan,b.harga_deal,d.total_bayar,b.harga_deal-d.total_bayar as sisa_bayar,
                                b.status_bayar,ifnull(b.tgl_mulai,'-') as tgl_mulai
                        FROM kamar a
                        LEFT OUTER JOIN 
                        (SELECT * FROM pemesanan WHERE status_kamar = 'Isi') b
                        ON (a.id=b.id_kamar)
                        LEFT OUTER JOIN penghuni c
                        ON (b.id_penghuni=c.id)
                        LEFT OUTER JOIN 
                        (   SELECT id_pemesanan,SUM(besar_bayar) as total_bayar
                            FROM pembayaran
                            GROUP BY id_pemesanan
                        ) d
                        ON (b.id_pesan=d.id_pemesanan)
                        WHERE id_kos = ?
                        ORDER BY a.lantai, a.nomer
                ";
        $dbResult = $this->db->query($sql, array($id_kos));
        return $dbResult->getResult();          
    }

    //method untuk menampilkan history pembayaran untuk id kamar tertentu
    public function getHistoryPembayaranByIdKamar($id_kamar){
        $sql = "SELECT c.id as id_penghuni, c.ktp, c.nama as nama_penghuni, e.id_kos, 
                        e.nama as nama_kos,
                        CONCAT('Lt ',a.lantai,' (',a.nomer,')') AS kmr, b.status_bayar, d.no_kuitansi,
                        a.id as id_kamar,d.tgl_bayar,d.besar_bayar,d.id_pembayaran,b.harga_deal,
                        b.id_pesan
                FROM kamar a
                JOIN 
                (SELECT * FROM pemesanan WHERE status_kamar = 'Isi') b 
                ON (a.id=b.id_kamar)
                JOIN penghuni c ON (b.id_penghuni=c.id)
                JOIN pembayaran d ON (b.id_pesan=d.id_pemesanan)
                JOIN kos e ON (a.id_kos=e.id_kos)
                WHERE a.id = ?
                ";
        $dbResult = $this->db->query($sql, array($id_kamar));
        return $dbResult->getResult();    
    }

    //dapatkan nomor kuitansi
    public function getNoKuitansi($id_kos){
        //generate nomer kuitansi dengan format KWI-20190520-3-001
        //KWI-THN_BLN_TGL-IDKOSAN-NOMOR_URUT
        $sql = "SELECT substring(IFNULL(MAX(no_kuitansi),0),16)+0 as urutan, DATE_FORMAT(CURRENT_DATE,'%Y%m%d') as skrg FROM pembayaran 
                WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = ".$id_kos." 
                AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = DATE_FORMAT(CURRENT_DATE,'%Y%m%d')";
        $dbResult = $this->db->query($sql);
        $hasil = $dbResult->getResult();
        foreach ($hasil as $row)
        {
            $urutan = $row->urutan;
            $tgl = $row->skrg;
        }        

        //format nomor kuitansi
        $nomor_kuitansi = "KWI-".$tgl."-".$id_kos."-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;

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

        //pencatatan jurnal pada saat pembayaran (kas pada piutang)
        $sql = "    INSERT INTO jurnal(`id_transaksi`, `kode_akun`, `tgl_jurnal`, `posisi_d_c`, `nominal`, `kelompok`, `transaksi`)
                    SELECT a.id_pembayaran as id_transaksi, b.kode_akun,a.tgl_bayar,b.posisi,a.besar_bayar,b.kelompok,b.transaksi
                    FROM pembayaran a
                    CROSS JOIN transaksi_coa b
                    WHERE a.id_pembayaran = ? AND b.transaksi = 'pembayaran' AND b.kelompok = 1
            ";
        $hasil = $this->db->query($sql, array($id_transaksi));

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
                FROM kamar a
                JOIN 
                (SELECT * FROM pemesanan WHERE status_kamar = 'Isi') b 
                ON (a.id=b.id_kamar)
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