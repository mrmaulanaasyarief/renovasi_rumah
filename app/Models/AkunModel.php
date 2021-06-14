<?php

namespace App\Models;

use CodeIgniter\Model;

class AkunModel extends Model
{
    protected $table = 'akun';

    public function cekUsernamePwd(){
        //bind variabel untuk mencegah sql injection
        $nama = $_POST['inputUsername'];
        $sandi = $_POST['inputPassword'];
        $dbResult = $this->db->query(
            "SELECT COUNT(*) as jml, username as nama
            FROM akun WHERE username = ? AND pwd = ?", 
            array($nama, md5($sandi)));
        return $dbResult->getResult();
    }

}