<?php

namespace App\Models;

use CodeIgniter\Model;

class AlatModel extends Model
{
    protected $table = 'alat';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data kos
    public function insertData(){
        $nama = $_POST['namaalat'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        $hasil = $this->db->query("INSERT INTO alat SET nama_alat = ?, alamat=?, telepon=? ", array($nama, $alamat, $telepon));
        return $hasil;
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function editData($id){
        $dbResult = $this->db->query("SELECT * FROM alat WHERE id_alat = ?", array($id));
        return $dbResult->getResult();
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function updateData(){
        $id = $_POST['id_alat'];
        $nama = $_POST['namaalat'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        $hasil = $this->db->query("UPDATE alat SET nama_alat = ?, alamat=?, telepon=? WHERE id_alat =? ", array($nama, $alamat, $telepon, $id));
        return $hasil;
    }

    //untuk menghapus data kos sesuai ID yang dipilih
    public function deleteData($id){
        $hasil = $this->db->query("DELETE FROM alat WHERE id_alat =? ", array($id));
        return $hasil;
    }
}