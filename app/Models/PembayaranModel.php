<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran';

    public function getAll(){
        return $this->findAll();
    }

    public function getById($id){
        $sql = "SELECT *
                FROM pembayaran
                WHERE id_pembayaran = ?
                ";
        $dbResult = $this->db->query($sql, array($id));
        return $dbResult->getResult();       
    }

    public function getLaporan(){
        $sql = "SELECT *
                FROM v_laporan
                ";
        $dbResult = $this->db->query($sql, array());
        return $dbResult->getResult();       
    }

    //method untuk menampilkan informasi data pembayaran
    public function getInfoPembayaran(){
        $sql = "(SELECT pr.id_pesan, c.nama, pr.harga_deal, pb.no_kuitansi, pb.tgl_bayar, pb.besar_bayar, pb.jenis_pemesanan
                    FROM customer c
                JOIN pemesanan_renovasi pr ON pr.id_customer=c.id_customer
                JOIN pembayaran pb ON pb.id_pemesanan=pr.id_pesan AND pb.jenis_pemesanan='Renovasi')
                UNION
                (SELECT pj.id_pesan, c.nama, pj.harga_deal, pb.no_kuitansi, pb.tgl_bayar, pb.besar_bayar, pb.jenis_pemesanan
                    FROM customer c
                JOIN pemesanan_renovasi pr ON pr.id_customer=c.id_customer
                JOIN pemesanan_jasa_desain pj ON pj.id_renovasi=pr.id_pesan
                JOIN pembayaran pb ON pb.id_pemesanan=pj.id_pesan AND pb.jenis_pemesanan='Jasa Desain')
                UNION
                (SELECT pm.id_pesan, c.nama, pm.total_trans, pb.no_kuitansi, pb.tgl_bayar, pb.besar_bayar, pb.jenis_pemesanan
                    FROM customer c
                JOIN pemesanan_renovasi pr ON pr.id_customer=c.id_customer
                JOIN pemesanan_material pm ON pm.id_renovasi=pr.id_pesan
                JOIN pembayaran pb ON pb.id_pemesanan=pm.id_pesan AND pb.jenis_pemesanan='Material')
                UNION
                (SELECT pg.id_pesan, c.nama, pg.total_gaji, pb.no_kuitansi, pb.tgl_bayar, pb.besar_bayar, pb.jenis_pemesanan
                    FROM customer c
                JOIN pemesanan_renovasi pr ON pr.id_customer=c.id_customer
                JOIN pemesanan_pegawai pg ON pg.id_renovasi=pr.id_pesan
                JOIN pembayaran pb ON pb.id_pemesanan=pg.id_pesan AND pb.jenis_pemesanan='Pegawai')
                UNION
                (SELECT ps.id_pesan, s.nama_supplier, ps.total_harga, pb.no_kuitansi, pb.tgl_bayar, pb.besar_bayar, pb.jenis_pemesanan
                    FROM supplier s
                JOIN pemesanan_supplier ps ON ps.id_supplier=s.id_supplier
                JOIN pembayaran pb ON pb.id_pemesanan=ps.id_pesan AND pb.jenis_pemesanan='Supplier')";
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

    //method untuk menampilkan history pembayaran untuk id pemesanan tertentu
    public function getHistoryPembayaranByIdPemesanan($id_pemesanan, $jenis_pemesanan){
        $sql = "SELECT *
                FROM pembayaran
                WHERE id_pemesanan = ? AND jenis_pemesanan = ?
                ";
        $dbResult = $this->db->query($sql, array($id_pemesanan, $jenis_pemesanan));
        return $dbResult->getResult();    
    }

    //dapatkan nomor kuitansi
    public function getNoKuitansi($id_renov, $id_pemesanan){
        //generate nomer kuitansi dengan format KWI-20190520-3-001
        //KWI-THN_BLN_TGL-IDKOSAN-NOMOR_URUT
        if($id_pemesanan == NULL){
            $sql = "SELECT SUBSTRING(IFNULL(MAX(no_kuitansi),0),20)+0 as urutan, DATE_FORMAT(CURRENT_DATE,'%Y%m%d') as skrg FROM pembayaran 
                    WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -3),'-',1) = ".$id_renov."
                    AND SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = 0 
                    AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = DATE_FORMAT(CURRENT_DATE,'%Y%m%d')";
            $dbResult = $this->db->query($sql);
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $urutan = $row->urutan;
                $tgl = $row->skrg;
            }        

            //format nomor kuitansi
            $nomor_kuitansi = "KWI-".$tgl."-".$id_renov."-0-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;

            return $nomor_kuitansi;
        }else{
            $sql = "SELECT SUBSTRING(IFNULL(MAX(no_kuitansi),0),20)+0 as urutan, DATE_FORMAT(CURRENT_DATE,'%Y%m%d') as skrg FROM pembayaran 
                    WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -3),'-',1) = ".$id_renov." 
                    AND SUBSTRING_INDEX(SUBSTRING_INDEX(no_kuitansi, '-', -2),'-',1) = ".$id_pemesanan." 
                    AND SUBSTRING(SUBSTRING_INDEX(no_kuitansi, '-', 2),5) = DATE_FORMAT(CURRENT_DATE,'%Y%m%d')";
            $dbResult = $this->db->query($sql);
            $hasil = $dbResult->getResult();
            foreach ($hasil as $row)
            {
                $urutan = $row->urutan;
                $tgl = $row->skrg;
            }        

            //format nomor kuitansi
            $nomor_kuitansi = "KWI-".$tgl."-".$id_renov."-".$id_pemesanan."-".str_pad(($urutan+1),3,"0",STR_PAD_LEFT); //-001;

            return $nomor_kuitansi;
        }
    }

    //untuk input data pembayaran
    public function inputDataPembayaran(){

        $id_renov = $_POST['jenis_pemesanan'];
        $jenis_pemesanan = $_POST['jenis_pemesanan'];
        $id_pemesanan = $_POST['id_pemesanan'];
        $no_kuitansi = $_POST['no_kuitansi'];
        $harga_deal = $_POST['harga_deal'];
        $totalbayar = $_POST['totalbayar'];
        $sisa_bayar = $_POST['sisa_bayar'];
        $besar_bayar = $_POST['besar_bayar'];
        $tgl_bayar = $_POST['tgl_bayar'];

        $besar_bayar = preg_replace( '/[^0-9 ]/i', '', $besar_bayar);
        $totalbayar = preg_replace( '/[^0-9 ]/i', '', $totalbayar);
        $sisa_bayar = preg_replace( '/[^0-9 ]/i', '', $sisa_bayar);
        $harga_deal = preg_replace( '/[^0-9 ]/i', '', $harga_deal);

        $sql = "INSERT INTO pembayaran SET 
                        id_pemesanan = ?, 
                        jenis_pemesanan = ?, 
                        no_kuitansi=?, 
                        tgl_bayar=DATE_FORMAT(CURRENT_DATE,'%Y%m%d'), 
                        besar_bayar=?";
        $dbHasil = $this->db->query($sql, 
            array(
                $id_pemesanan, 
                $jenis_pemesanan, 
                $no_kuitansi,
                $besar_bayar)
            );

        // //pencatatan jurnal pada saat pembayaran (kas pada piutang)
        // $sql = "    INSERT INTO jurnal
        //             SELECT a.id_pembayaran as id_transaksi, b.kode_akun,a.tgl_bayar,b.posisi,a.besar_bayar,b.kelompok,b.transaksi
        //             FROM pembayaran a
        //             CROSS JOIN transaksi_coa b
        //             WHERE a.id_pembayaran = ? AND b.transaksi = 'pembayaran' AND b.kelompok = 1
        //     ";
        // $hasil = $this->db->query($sql, array($id_transaksi));

        //cek apakah sudah lunas atau belum, jika sudah lunas, maka statusnya diganti menjadi lunas pada tabel pemesana
        // $sql = "    SELECT SUM(a.besar_bayar) as besar_bayar,
        //                 (SELECT harga_deal FROM pemesanan WHERE id_pesan = a.id_pemesanan) as harga_deal
        //             FROM pembayaran a
        //             WHERE a.id_pemesanan = ?
        //         ";
        // $dbResult = $this->db->query($sql, array($id_pesan));
        // $hasil = $dbResult->getResult();
        // foreach ($hasil as $row)
		// {
		// 	$besar_bayar = $row->besar_bayar;
        //     $harga_deal = $row->harga_deal;
		// }  

        if(($harga_deal-($besar_bayar+$totalbayar))<=0){
            if($jenis_pemesanan == 'Renovasi'){
                $sql = "UPDATE pemesanan_renovasi SET status_bayar = 'Lunas' WHERE id_pesan =?";
            }else if($jenis_pemesanan == 'Supplier'){
                $sql = "UPDATE pemesanan_supplier SET status_bayar = 'Lunas' WHERE id_pesan =?";
            }else if($jenis_pemesanan == 'Jasa Desain'){
                $sql = "UPDATE pemesanan_jasa_desain SET status_bayar = 'Lunas' WHERE id_pesan =?";
            }else if($jenis_pemesanan == 'Material'){
                $sql = "UPDATE pemesanan_material SET status_bayar = 'Lunas' WHERE id_pesan =?";
            }else if($jenis_pemesanan == 'Pegawai'){
                $sql = "UPDATE pemesanan_pegawai SET status_gaji = 'Lunas' WHERE id_pesan =?";
            }
            $dbResult = $this->db->query($sql, array($id_pemesanan));
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