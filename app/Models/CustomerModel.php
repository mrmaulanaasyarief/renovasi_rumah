<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customer';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data kos
    public function insertData(){
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        $hasil = $this->db->query("INSERT INTO customer SET nama = ?, alamat=?, no_hp=? ", array($nama, $alamat, $no_hp));
        return $hasil;
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function editData($id){
        $dbResult = $this->db->query("SELECT * FROM customer WHERE id_customer = ?", array($id));
        return $dbResult->getResult();
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function updateData(){
        $id = $_POST['id_customer'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        $hasil = $this->db->query("UPDATE customer SET nama = ?, alamat=?, no_hp=? WHERE id_customer =? ", array($nama, $alamat, $no_hp, $id));
        return $hasil;
    }

    //untuk menghapus data kos sesuai ID yang dipilih
    public function deleteData($id){
        $hasil = $this->db->query("DELETE FROM customer WHERE id_customer =? ", array($id));
        return $hasil;
    }
}