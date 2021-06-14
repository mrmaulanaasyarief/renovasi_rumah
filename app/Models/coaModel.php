<?php

namespace App\Models;

use CodeIgniter\Model;

class CoaModel extends Model
{
    protected $table = 'coa';

    public function getAll(){
        $dbResult = $this->db->query("SELECT * FROM coa ORDER BY kode_coa");
        return $dbResult->getResult();
    }

    //untuk list buku besar
    public function getNamaAkun(){
        $sql = "SELECT b.kode_akun, a.nama_coa
                FROM coa a
                JOIN jurnal b on (a.kode_coa=b.kode_akun)
                GROUP BY b.kode_akun, a.nama_coa
                ORDER BY 2";
        $dbResult = $this->db->query($sql);
        return $dbResult->getResult();        
    }

    //untuk data jurnal
    public function getJurnalUmum($tahun, $bulan){
        $sql = "SELECT a.*,b.nama_coa  
                FROM jurnal a
		        JOIN coa b ON (a.kode_akun=b.kode_coa)
                WHERE  year(a.tgl_jurnal) = ? AND DATE_FORMAT(a.tgl_jurnal,'%m') = ?
                ORDER BY a.tgl_jurnal, a.id_transaksi, a.kelompok,a.posisi_d_c DESC";
        $dbResult = $this->db->query($sql, array($tahun, $bulan));
        return $dbResult->getResult();
    }

    //untuk data list tahun
    public function getPeriodeTahun(){
        $dbResult = $this->db->query("SELECT DISTINCT(YEAR(tgl_jurnal)) as tahun FROM `jurnal` UNION SELECT 2020 as tahun ORDER BY 1");
        return $dbResult->getResult();
    }

    //untuk data list tahun
    public function getPeriodeTahunBulan(){
        $dbResult = $this->db->query("SELECT DISTINCT(YEAR(tgl_jurnal)) as tahun, 
            DATE_FORMAT(tgl_jurnal,'%M') AS bulan, DATE_FORMAT(tgl_jurnal,'%m') as bulan_angka
        FROM jurnal ORDER BY 1");
        return $dbResult->getResult();
    }

    //untuk data list tahun
    public function getPeriodeBulan($tahun){
        $sql = "SELECT DATE_FORMAT(tgl_jurnal,'%M') as bulan, DATE_FORMAT(tgl_jurnal,'%m') as bulan_angka 
                FROM `jurnal` WHERE YEAR(tgl_jurnal) = ?
                GROUP BY DATE_FORMAT(tgl_jurnal,'%M'), DATE_FORMAT(tgl_jurnal,'%m') ORDER BY 2";
        $dbResult = $this->db->query($sql, array($tahun));
        //dikembalikan dalam bentuk array
        return $dbResult->getResult('array');
    }

    //untuk data buku besar
    public function getBukuBesar($tahun, $bulan, $kodecoa){
        $sql = "
                    SELECT a.*,b.nama_coa  
                    FROM jurnal a
                    JOIN coa b ON (a.kode_akun=b.kode_coa)
                    WHERE  year(a.tgl_jurnal) = ? AND DATE_FORMAT(a.tgl_jurnal,'%m') = ?
                    AND b.kode_coa = ?
                    ORDER BY a.tgl_jurnal, a.id_transaksi, a.kelompok,a.posisi_d_c DESC
                ";
        $dbResult = $this->db->query($sql, array($tahun, $bulan, $kodecoa));
        return $dbResult->getResult();        
    }

    //get data posisi saldo normal
    public function getPosisiSaldoNormal($akun){
        //lihat posisi saldo awal normal
        // $sql = "SELECT posisi_d_c
        //         FROM coa 
        //         WHERE kode_coa = ?";
        $sql = "SELECT j.posisi_d_c
                FROM coa c
                CROSS JOIN jurnal j
                WHERE c.kode_coa = ? AND j.kode_akun=c.kode_coa";
        
        $dbResult = $this->db->query($sql, array($akun));
        $hasil = $dbResult->getResult('array');
        foreach($hasil as $cacah):
            $posisi_saldo_normal = $cacah['posisi_d_c'];
        endforeach;
        return $posisi_saldo_normal;
    }

    //untuk mengetahui saldo awal buku besar
    public function getSaldoAwal($bulan,$tahun,$akun){
        $posisi_saldo_normal = $this->getPosisiSaldoNormal($akun);
        $bulan = str_pad($bulan,2,"0",STR_PAD_LEFT);
        $waktu = $tahun."-".$bulan;
        $sql = "
                    SELECT tbl1.posisi_d_c,ifnull(tbl2.total,0) as nominal FROM
                    (
                        SELECT 'c' posisi_d_c
                        UNION
                        SELECT 'd' posisi_d_c
                    ) tbl1
                    LEFT OUTER JOIN
                    (
                        Select a.posisi_d_c,sum(a.nominal) as total
                        FROM jurnal a
                        JOIN coa b ON (a.kode_akun=b.kode_coa)
                        WHERE a.kode_akun = ? 
                        AND date_format(a.tgl_jurnal,'%Y-%m') < ?
                        GROUP BY  a.posisi_d_c
                        ORDER BY id_transaksi
                    ) tbl2
                    ON (tbl1.posisi_d_c = tbl2.posisi_d_c)
        
        ";
        $dbResult = $this->db->query($sql, array($akun, $bulan));
        $hasil = $dbResult->getResult('array');
        $saldo_debet = 0;
        $saldo_kredit = 0;
        foreach($hasil as $cacah):
            if(strcmp($cacah['posisi_d_c'],'d')==0){
                $saldo_debet = $saldo_debet + $cacah['nominal'];
            }else{
                $saldo_kredit = $saldo_kredit + $cacah['nominal'];
            }
        endforeach;

        if(strcmp($posisi_saldo_normal,'d')==0){
            $saldo = $saldo_debet - $saldo_kredit;
        }else{
            $saldo =  $saldo_kredit - $saldo_debet;
        }
        return $saldo;
    }

    //menghitung pendapatan Operasional
    public function getPendapatanOperasional($id_kos, $bulan,$tahun){
        $bulan = str_pad($bulan,2,"0",STR_PAD_LEFT);
        $waktu = $bulan."-".$tahun;
        $sql = "
                Select ifnull(sum(a.nominal),0) as total
                FROM jurnal a
                JOIN pendapatan b 
                ON (a.id_transaksi=b.id_transaksi)
                JOIN pemesanan c 
                ON(b.id_pemesanan=c.id_pesan)
                JOIN kamar d
                ON (c.id_kamar=d.id)
                WHERE a.kode_akun = '41' AND d.id_kos = ?
                AND date_format(a.tgl_jurnal,'%m-%Y') = ?    
        ";
        $query = $this->db->query($sql, array($id_kos, $waktu));
        foreach($query->getResult() as $row):
            $total = $row->total;
        endforeach;
        return $total;
    }

    //mendapatkan kode coa untuk Beban
    public function getBeban($id_kos, $bulan,$tahun){
        $bulan = str_pad($bulan,2,"0",STR_PAD_LEFT);
        $waktu = $bulan."-".$tahun;
        $sql = "SELECT a.kode_coa,a.nama_coa,SUM(nominal) as total
                FROM coa a
                JOIN jurnal b ON (a.kode_coa=b.kode_akun)
                JOIN pembebanan c ON (b.id_transaksi=c.id_transaksi)
                WHERE a.kode_coa LIKE '5%' and length(a.kode_coa)>1
                AND date_format(b.tgl_jurnal,'%m-%Y') = ?  AND c.id_kos = ?
                GROUP BY a.kode_coa,a.nama_coa
                ORDER BY length(a.kode_coa) ASC, a.kode_coa
                ";
        $dbResult = $this->db->query($sql, array($waktu,$id_kos));
        return $dbResult->getResult();   
    }

    //memproses perhitungan akrual pendapatan setiap akhir bulan
    public function setPendapatanAkrual(){
        //cari seluruh transaksi pemesanan untuk dibuatkan pencatatan akrual secara otomatis
        //inisialisasi array untuk setiap id_pemesanan akan dibuatkan sejumlah bulannya
        $ar = array();
        $sql = "
                    SELECT a.*,
                           PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM a.tgl_selesai), EXTRACT(YEAR_MONTH FROM a.tgl_mulai)) as lama_kos_dlm_bulan,
                           a.harga_deal/PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM a.tgl_selesai), EXTRACT(YEAR_MONTH FROM a.tgl_mulai)) as harga_per_bulan 
                    FROM pemesanan a
        ";
        $dbResult = $this->db->query($sql);
        foreach($dbResult->getResult() as $row):
            //echo $row->id_pesan."-".$row->harga_deal."-".$row->tgl_mulai."-".$row->tgl_selesai."<br>";
            array_push($ar,["id_pesan" => $row->id_pesan, "harga_deal" => $row->harga_deal, "tgl_mulai"=> $row->tgl_mulai, "tgl_selesai" =>$row->tgl_selesai, "lama_kos_dlm_bulan" =>$row->lama_kos_dlm_bulan, "harga_per_bulan" =>$row->harga_per_bulan]);
        endforeach; 

        //echo "<hr>";
        //echo count($ar);
        //echo "panjang array dari $ar =".count($ar)."<br>";
        //membuatkan jurnal yg ideal untuk mencatatkan akrualnya
        
        for($i=0;$i<count($ar);$i++){
            //echo $ar[$i]['id_pesan']."-".$ar[$i]['lama_kos_dlm_bulan']."-".$ar[$i]['harga_per_bulan']."<br>###";
            //looping per jumlah bulan
            $total_tagihan = 0;
            for($j=1;$j<=$ar[$i]['lama_kos_dlm_bulan'];$j++){
                //echo $j;
                $total_tagihan = $total_tagihan + $ar[$i]['harga_per_bulan'];
                $sql = "SELECT LAST_DAY(DATE_ADD('".$ar[$i]['tgl_mulai']."', INTERVAL ".$j." MONTH)) as tgl_akhir_bulan";
                $dbResult = $this->db->query($sql);
                $hasil = $dbResult->getResult(); 
                foreach($hasil as $row):
                    //echo "Today is " . date("Y-m-d") . "=";
                    if($row->tgl_akhir_bulan<=date("Y-m-d")){
                        //echo "masuk sini ".$row->tgl_akhir_bulan."<br>";
                        //cek apakah sudah ada dijurnal
                        $sql = "SELECT COUNT(*) AS jml FROM pendapatan WHERE id_pemesanan = ".$ar[$i]['id_pesan'];
                        $dbResult = $this->db->query($sql);
                        $hasil2 = $dbResult->getResult(); 
                        foreach($hasil2 as $row2):
                            $jml = $row2->jml;
                        endforeach;
                        //jika jml == 0 maka cek apakah bulan looping adalah dibawah bulan sekarang
                        if( ($jml==0) and ( (substr($row->tgl_akhir_bulan,0,7)<date("Y-m")) or ($row->tgl_akhir_bulan==date("Y-m-d")) ) ){
                            //maka cek dulu jumlah bayarnya apakah sudah lebih besar dari tagihan akumulasi per bulan
                            $sql = "SELECT SUM(besar_bayar) as akumulasi_bayar
                                    FROM pembayaran
                                    WHERE id_pemesanan = ".$ar[$i]['id_pesan'];
                            $dbResult = $this->db->query($sql);   
                            $hasil3 = $dbResult->getResult();     
                            foreach($hasil3 as $row3):
                                $akumulasi_bayar = $row3->akumulasi_bayar;
                            endforeach; 
                            //bandingkan apakah akumulasi bayar lebih besar atau sama dengan besar tagihan dibulan looping
                            if($akumulasi_bayar>=$total_tagihan){
                                //dapatkan id transaksi
                                $dbResult = $this->db->query("SELECT IFNULL(MAX(id_transaksi),0) as id_transaksi from view_transaksi");

                                $hasil4 = $dbResult->getResult();
                                //cacah hasilnya
                                foreach ($hasil4 as $row4)
                                {
                                    $id_transaksi = $row4->id_transaksi;
                                }
                                $id_transaksi = $id_transaksi+1; //naikkan 1 untuk id baru modal yang dimasukkan

                                //kalau iya maka baru dimasukkan ke jurnal dan pendapatan
                                $sql = "INSERT INTO pendapatan VALUES(".$id_transaksi.",".$total_tagihan.",'".$row->tgl_akhir_bulan."',".$ar[$i]['id_pesan'].")";
                                $hasil4 = $this->db->query($sql);
                                
                                //kemudian dimasukkan ke jurnal
                                $sql = "    INSERT INTO jurnal
                                            SELECT ".$id_transaksi." as id_transaksi,
                                                   a.kode_akun, '".$row->tgl_akhir_bulan."' as tgl_jurnal,
                                                   a.posisi, ".$total_tagihan." as nominal, a.kelompok, 'pembayaran' as transaksi
                                            FROM transaksi_coa a
                                            WHERE a.transaksi = 'pembayaran' and a.kelompok = 3
                                            ORDER BY posisi DESC
                                        ";
                                $hasil5 = $this->db->query($sql);        
                            }
                        }
                    }
                    
                endforeach;

                //cari di transaksi jurnal apakah sudah ada atau belum
            }
            //echo "<br>";
        } 

    }

}