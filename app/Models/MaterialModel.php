<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'material';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data kos
    public function insertData(){
        $nama = $_POST['namamaterial'];
        $jm = $_POST['jenismaterial'];
        $satuan = $_POST['satuan'];
        $harga = $_POST['harga'];
        $hasil = $this->db->query("INSERT INTO material SET nama = ?, jenis_material=?, satuan=?, harga=?", array($nama, $jm, $satuan, $harga));
        return $hasil;
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function editData($id){
        $dbResult = $this->db->query("SELECT * FROM material WHERE id_material = ?", array($id));
        return $dbResult->getResult();
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function updateData(){
        $id = $_POST['id_material'];
        $nama = $_POST['namamaterial'];
        $jm = $_POST['jenismaterial'];
        $satuan = $_POST['satuan'];
        $harga = $_POST['harga'];
        $hasil = $this->db->query("UPDATE material SET nama = ?, jenis_material=?, satuan=?, harga=? WHERE id_material =? ", array($nama, $jm, $satuan, $harga, $id));
        return $hasil;
    }

    //untuk menghapus data kos sesuai ID yang dipilih
    public function deleteData($id){
        $hasil = $this->db->query("DELETE FROM material WHERE id_material =? ", array($id));
        return $hasil;
    }

    public function getMaterialByIdMaterial($id){
        $dbResult = $this->db->query("SELECT * FROM material WHERE id_material = ?", array($id));
        return $dbResult->getResult();
    }
}