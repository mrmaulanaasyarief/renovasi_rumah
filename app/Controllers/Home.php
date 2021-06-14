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
			$nama = $row->nama;
		}
		
		$_SESSION['nama']  = $nama;
		//nilai jml adalah 1 menunjukkan bahwa pasangan username dan password cocok
		if($jml>0){	

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
