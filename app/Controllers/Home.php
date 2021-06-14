<?php

namespace App\Controllers;

//load model database yang akan digunakan yaitu akun
use App\Models\AkunModel;

class Home extends BaseController
{
	public function __construct()
    {
        //load kelas AkunModel
        $this->akunmodel = new AkunModel();
		session_start();
    }

	public function index()
	{
		if(!isset($_SESSION['nama'])){
            return view('login'); 
        }
		echo view('HeaderBootstrap');
		echo view('SidebarBootstrap');
		echo view('BodyBootstrap');
	}

	public function logout()
	{
		if(isset($_SESSION['nama'])){
			$_SESSION['nama']=NULL;
        }
		return view('login');
	}

	public function ceklogin()
	{
		$hasil = $this->akunmodel->cekUsernamePwd();

		//iterasi hasil query
		foreach ($hasil as $row)
		{
			$jml = $row->jml;
		}
		
		//nilai jml adalah 1 menunjukkan bahwa pasangan username dan password cocok
		if($jml>0){	
			//update last login
			$hasil = $this->akunmodel->updatelastlogin();
			
			//dapat waktu last login
			$hasil = $this->akunmodel->getlastlogin($_POST['inputUsername']);

			$lastlogin = '';
			foreach ($hasil as $row)
			{
				$lastlogin = $row->last_login;
			}

			//dapatkan kelompok user
			$hasil = $this->akunmodel->getGroupUser($_POST['inputUsername']);
			
			$kelompok = '';
			foreach ($hasil as $row)
			{
				$kelompok = $row->user_group;
			}

			//dapatkan id
			$hasil = $this->akunmodel->getIdUser($_POST['inputUsername']);
			
			$id = '';
			foreach ($hasil as $row)
			{
				$id = $row->id;
			}

			//ciptakan session
			$_SESSION['nama']  = $_POST['inputUsername'];
			$_SESSION['kelompok']  = $kelompok;
			$_SESSION['lastlogin']  = $lastlogin;
			$_SESSION['id']  = $id;

			//jika pasangan sama maka diarahkan ke welcome page
			//return view('welcome_message');
			echo view('HeaderBootstrap');
			echo view('SidebarBootstrap');
			echo view('BodyBootstrap');
		}else{
			//jika tidak sama maka dikembalikan ke ceklogin
			$data['pesan'] = 'Pasangan username dan password tidak tepat';
			return view('login', $data);
		}
		
	}

}
