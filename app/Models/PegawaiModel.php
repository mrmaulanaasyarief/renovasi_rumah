<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = 'pegawai';

    public function getAll(){
        return $this->findAll();
    }

    public function getPegawaiByIdPegawai($id){
        $dbResult = $this->db->query("SELECT * FROM pegawai WHERE id_pegawai = ?", array($id));
        return $dbResult->getResult();
    }
    //untuk memasukkan data kos
    public function insertData(){
        $nama = $_POST['nama_pegawai'];
        $alamat = $_POST['alamat_pegawai'];
        $telp = $_POST['telp_pegawai'];
        $jenis = $_POST['jenis_pegawai'];
        $hasil = $this->db->query("INSERT INTO pegawai SET nama_pegawai=?, alamat_pegawai=?, telp_pegawai=?, jenis_pegawai=? ", array($nama, $alamat, $telp, $jenis));
        return $hasil;
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function editData($id){
        $dbResult = $this->db->query("SELECT * FROM pegawai WHERE id_pegawai = ?", array($id));
        return $dbResult->getResult();
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function updateData(){
        $id = $_POST['id_pegawai'];
        $nama = $_POST['nama_pegawai'];
        $alamat = $_POST['alamat_pegawai'];
        $telp = $_POST['telp_pegawai'];
        $jenis = $_POST['jenis_pegawai'];
        $hasil = $this->db->query("UPDATE pegawai SET nama_pegawai = ?, alamat_pegawai=?, telp_pegawai=?, jenis_pegawai=? WHERE id_pegawai =? ", array($nama, $alamat, $telp, $jenis, $id));
        return $hasil;
    }

    //untuk menghapus data kos sesuai ID yang dipilih
    public function deleteData($id){
        $hasil = $this->db->query("DELETE FROM pegawai WHERE id_pegawai =? ", array($id));
        return $hasil;
    }
}