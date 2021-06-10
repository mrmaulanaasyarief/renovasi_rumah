<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';

    public function getAll(){
        return $this->findAll();
    }

    //untuk memasukkan data kos
    public function insertData(){
        $nama = $_POST['nama_supplier'];
        $alamat = $_POST['alamat_supplier'];
        $telp_supplier = $_POST['telepon_supplier'];
        $jenis_material = $_POST['jenis_material'];
        $harga_material = $_POST['harga_material'];
        $hasil = $this->db->query("INSERT INTO supplier SET nama_supplier=?, alamat_supplier=?, telepon_supplier=?, jenis_material=?, harga_material=? ", array($nama, $alamat, $telp_supplier, $jenis_material, $harga_material));
        return $hasil;
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function editData($id){
        $dbResult = $this->db->query("SELECT * FROM supplier WHERE id_supplier = ?", array($id));
        return $dbResult->getResult();
    }

    //untuk mendapatkan data kos sesuai dengan ID untuk diedit
    public function updateData(){
        $id = $_POST['id_supplier'];
        $nama = $_POST['nama_supplier'];
        $alamat = $_POST['alamat_supplier'];
        $telp_supplier = $_POST['telepon_supplier'];
        $jenis_material = $_POST['jenis_material'];
        $harga_material = $_POST['harga_material'];
        $hasil = $this->db->query("UPDATE supplier SET nama_supplier = ?, alamat_supplier=?, telepon_supplier=?, jenis_material=?, harga_material=? WHERE id_supplier =? ", array($nama, $alamat, $telp_supplier, $jenis_material, $harga_material, $id));
        return $hasil;
    }

    //untuk menghapus data kos sesuai ID yang dipilih
    public function deleteData($id){
        $hasil = $this->db->query("DELETE FROM supplier WHERE id_supplier =? ", array($id));
        return $hasil;
    }

    public function getSupplierByIdSupplier($id){
        $dbResult = $this->db->query("SELECT * FROM supplier WHERE id_supplier = ?", array($id));
        return $dbResult->getResult();
    }
}