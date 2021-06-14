<?php
namespace App\Controllers;

use App\Models\PegawaiModel;

class Pegawai extends BaseController
{
	public function __construct()
    {
        session_start();
        //load kelas AkunModel
        $this->pegawaimodel = new PegawaiModel();
    }

    public function index()
	{

        //cek dulu apakah kondisi form sudah diisi atau belum, kalau belum berarti tidak perlu memanggil fungsi validasi
        if(isset($_POST['nama_pegawai']) and isset($_POST['alamat_pegawai']) and isset($_POST['telp_pegawai']) and isset($_POST['jenis_pegawai'])){
                //
                $validation =  \Config\Services::validation();

                if (! $this->validate([
                        'nama_pegawai' => 'required',
                        'alamat_pegawai' => 'required',
                        'telp_pegawai' => 'required|numeric',
                        'jenis_pegawai' => 'required'
                ],
                        [   // Errors
                            'nama_pegawai' => [
                                'required' => 'Nama Pegawai tidak boleh kosong',
                            ],
                            'alamat_pegawai' => [
                                'required' => 'Alamat Pegawai tidak boleh kosong'
                            ],
                            'telp_pegawai' => [
                                'required' => 'Nomor telepon tidak boleh kosong',
                                'numeric' => 'Nomor telepon harus angka'
                            ],
                            'jenis_pegawai' => [
                                'required' => 'Jenis Pegawai tidak boleh kosong',
                            ]
                        ]
                ))
                {                
                    
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Pegawai/InputPegawai',[
                        'validation' => $this->validator,
                    ]);

                }
                else
                {
                    //panggil metod dari kosan model untuk diinputkan datanya
                    $hasil = $this->pegawaimodel->insertData();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                    $data['pegawai'] = $this->pegawaimodel->getAll();
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Pegawai/listpegawai', $data);
                }    
                //
        }else{
                    //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Pegawai/InputPegawai');
        }
	}

    public function listpegawai(){
        $data['pegawai'] = $this->pegawaimodel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pegawai/listpegawai', $data);
    }

    public function editpegawai($id){
        $data['pegawai'] = $this->pegawaimodel->editData($id);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pegawai/EditPegawai', $data);
    }

    public function editpegawaiproses(){
        
        $id = $_POST['id_pegawai'];
        $nama = $_POST['nama_pegawai'];
        $alamat = $_POST['alamat_pegawai'];
        $telpon = $_POST['telp_pegawai'];
        $jenis = $_POST['jenis_pegawai'];
        

        $validation =  \Config\Services::validation();

        if (! $this->validate([
                'nama_pegawai' => 'required',
                'alamat_pegawai' => 'required',
                'telp_pegawai' => 'required|numeric',
                'jenis_pegawai' => 'required'
        ],
                [   // Errors
                    'nama_pegawai' => [
                        'required' => 'Nama Pegawai tidak boleh kosong',
                    ],
                    'alamat_pegawai' => [
                        'required' => 'Alamat Pegawai tidak boleh kosong'
                    ],
                    'telp_pegawai' => [
                        'required' => 'Nomor telepon tidak boleh kosong',
                        'numeric' => 'Nomor telepon harus angka'
                    ],
                    'jenis_pegawai' => [
                        'required' => 'Jenis Pegawai tidak boleh kosong'
                    ]
                ]
        ))
        {                
            
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pegawai/EditPegawai',[
                'validation' => $this->validator,
                'pegawai' => $this->pegawaimodel->editData($id)
            ]);

        }
        else
        {
            //panggil metod dari kosan model untuk diinputkan datanya
            $hasil = $this->pegawaimodel->updateData();
            if($hasil->connID->affected_rows>0){
                ?>
                <script type="text/javascript">
                    alert("Sukses diupdate");
                </script>
                <?php	
            }
            $data['pegawai'] = $this->pegawaimodel->getAll();
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pegawai/listpegawai', $data);
        }    
    }

    public function deletepegawai($id){
		$this->pegawaimodel->deleteData($id);

		return redirect()->to(base_url('Pegawai/listpegawai')); 
	}
}