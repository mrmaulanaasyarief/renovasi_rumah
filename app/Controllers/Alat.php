<?php
namespace App\Controllers;

use App\Models\AlatModel;

class Alat extends BaseController
{
	public function __construct()
    {
        //load kelas AkunModel
        $this->alatmodel = new AlatModel();
    }

    public function index()
	{

        //cek dulu apakah kondisi form sudah diisi atau belum, kalau belum berarti tidak perlu memanggil fungsi validasi
        if(isset($_POST['namaalat']) and isset($_POST['alamat']) and isset($_POST['telepon'])){
                //
                $validation =  \Config\Services::validation();

                if (! $this->validate([
                        'namaalat' => 'required',
                        'alamat' => 'required',
                        'telepon' => 'required|numeric'
                ],
                        [   // Errors
                            'namaalat' => [
                                'required' => 'Nama alat tidak boleh kosong',
                            ],
                            'alamat' => [
                                'required' => 'Alamat kosan tidak boleh kosong'
                            ],
                            'telepon' => [
                                'required' => 'Nomor telepon tidak boleh kosong',
                                'numeric' => 'Nomor telepon harus angka'
                            ]
                        ]
                ))
                {                
                    
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Alat/Inputalat',[
                        'validation' => $this->validator,
                    ]);

                }
                else
                {
                    //panggil metod dari kosan model untuk diinputkan datanya
                    $hasil = $this->alatmodel->insertData();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                    $data['alatalat'] = $this->alatmodel->getAll();
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('alat/Daftaralat', $data);
                }    
                //
        }else{
                    //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('alat/Inputalat');
        }
	}

    public function daftaralat(){
        $data['alatalat'] = $this->alatmodel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('alat/Daftaralat', $data);
    }

    public function editalat($id){
        $data['alatalat'] = $this->alatmodel->editData($id);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('alat/Editalat', $data);
    }

    public function editalatproses(){
        
        $id = $_POST['id_alat'];
        $nama = $_POST['namaalat'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];

        $validation =  \Config\Services::validation();

        if (! $this->validate([
                'namaalat' => 'required',
                'alamat' => 'required',
                'telepon' => 'required|numeric'
        ],
                [   // Errors
                    'namaalat' => [
                        'required' => 'Nama alat tidak boleh kosong',
                    ],
                    'alamat' => [
                        'required' => 'Alamat Supplier tidak boleh kosong'
                    ],
                    'telepon' => [
                        'required' => 'Nomor telepon tidak boleh kosong',
                        'numeric' => 'Nomor telepon harus angka'
                    ]
                ]
        ))
        {                
            
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('alat/Editalat',[
                'validation' => $this->validator,
                'alatalat' => $this->alatmodel->editData($id)
            ]);

        }
        else
        {
            //panggil metod dari kosan model untuk diinputkan datanya
            $hasil = $this->alatmodel->updateData();
            if($hasil->connID->affected_rows>0){
                ?>
                <script type="text/javascript">
                    alert("Sukses diupdate");
                </script>
                <?php	
            }
            $data['alatalat'] = $this->alatmodel->getAll();
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('alat/Daftaralat', $data);
        }    
    }

    public function deletealat($id){
		$this->alatmodel->deleteData($id);

		return redirect()->to(base_url('alat/daftaralat')); 
	}
}