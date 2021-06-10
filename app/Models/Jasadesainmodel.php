<?php

namespace App\Models;

use CodeIgniter\Model;

class JasadesainModel extends Model
{
    protected $table = 'jasa_desain';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data jasa desain
    public function insertData(){
        $jenis_jasa_desain = $_POST['jenis_jasa_desain'];
        $tipe_desain = $_POST['tipe_desain'];
        $hasil = $this->db->query("INSERT INTO jasa_desain SET jenis_jasa_desain = ?, tipe_desain=?", array($jenis_jasa_desain, $tipe_desain));
        return $hasil;
    }

    //untuk mendapatkan data jasa desain sesuai dengan ID untuk diedit
    public function editData($id){
        $dbResult = $this->db->query("SELECT * FROM jasa_desain WHERE id_jasa_desain = ?", array($id));
        return $dbResult->getResult();
    }

    //untuk mendapatkan data jasa desain sesuai dengan ID untuk diedit
    public function updateData(){
        $id = $_POST['id_jasa_desain'];
        $jenis_jasa_desain = $_POST['jenis_jasa_desain'];
        $tipe_desain = $_POST['tipe_desain'];
        $hasil = $this->db->query("UPDATE jasa_desain SET jenis_jasa_desain = ?, tipe_desain=? WHERE id_jasa_desain =? ", array($jenis_jasa_desain, $tipe_desain, $id));
        return $hasil;
    }

    //untuk menghapus data jasa desain sesuai ID yang dipilih
    public function deleteData($id){
        $hasil = $this->db->query("DELETE FROM jasa_desain WHERE id_jasa_desain =? ", array($id));
        return $hasil;
    }
    public function getJasadesainByIdJasadesain($id){
        $dbResult = $this->db->query("SELECT * FROM jasa_desain WHERE id_jasa_desain = ?", array($id));
        return $dbResult->getResult();
    }
}