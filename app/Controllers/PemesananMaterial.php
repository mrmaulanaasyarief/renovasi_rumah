<?php
namespace App\Controllers;

use App\Models\PemesananMaterialModel;

class PemesananMaterial extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PenghuniModel
        $this->PemesananMaterialModel = new PemesananMaterialModel();
    }

    //form input akan diakses dari index
    public function index()
	{
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        //di cek dulu, agar validasi tidak terpicu pada saat awal method ini diakses
        if( !isset($_POST['']) and !isset($_POST['nama']) and !isset($_POST['email']) and !isset($_POST['telepon']) ){
            //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Penghuni/InputPenghuni');
        }
        else{
            $validation =  \Config\Services::validation();
            //di cek dulu apakah data isian memenuhi rules validasi yang dibuat
            if (! $this->validate(
                        [
                            'ktp' => 'required',
                            'nama' => 'required',
                            'email' => 'valid_email',
                            'telepon' => 'required|numeric|is_natural'
                        ],
                                [   // Errors
                                    'ktp' => [
                                        'required' => 'Nomor KTP tidak boleh kosong',
                                    ],
                                    'nama' => [
                                        'required' => 'Nama tidak boleh kosong'
                                    ],
                                    'email' => [
                                        'valid_email' => 'Email harus valid cth hendro@gmail.com'
                                    ],
                                    'telepon' => [
                                        'required' => 'Nomor telepon tidak boleh kosong',
                                        'numeric' => 'Nomor telepon harus angka',
                                        'is_natural' => 'Nomor telepon harus dalam angka natural bukan minus (0 s/d 9)'
                                    ]
                                ]
                    )
            ){
                //kirim data error ke views, karena ada isian yang tidak sesuai rules
                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('Penghuni/InputPenghuni',[
                    'validation' => $this->validator,
                ]);

            }else{
                //blok ini adalah blok jika sukses, yaitu panggil method insertData()
                //panggil metod dari kosan model untuk diinputkan datanya
                $hasil = $this->PenghuniModel->insertData();
                return redirect()->to(base_url('penghuni/listpenghuni')); 
            }
        }
	}

    public function listpenghuni()
    {
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['penghuni'] = $this->PenghuniModel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        //echo view('Penghuni/ListPenghuni', $data);
        echo view('Penghuni/ListPenghuniDatatables', $data);
    }

}    