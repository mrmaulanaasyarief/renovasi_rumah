<?php
namespace App\Controllers;

use App\Models\MaterialModel;

class Material extends BaseController
{
	public function __construct()
    {
        //load kelas AkunModel
        $this->materialmodel = new MaterialModel();
    }

    public function index()
	{

        //cek dulu apakah kondisi form sudah diisi atau belum, kalau belum berarti tidak perlu memanggil fungsi validasi
        if(isset($_POST['namamaterial']) and isset($_POST['satuan']) and isset($_POST['harga'])){
                //
                $validation =  \Config\Services::validation();

                if (! $this->validate([
                        'namamaterial' => 'required',
                        'satuan' => 'required',
                        'harga' => 'required|numeric'
                ],
                        [   // Errors
                            'namamaterial' => [
                                'required' => 'Nama material tidak boleh kosong',
                            ],
                            'satuan' => [
                                'required' => 'Satuan material tidak boleh kosong'
                            ],
                            'harga' => [
                                'required' => 'Harga tidak boleh kosong',
                                'numeric' => 'Harga harus angka'
                            ]
                        ]
                ))
                {                
                    
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Material/Inputmaterial',[
                        'validation' => $this->validator,
                    ]);

                }
                else
                {
                    //panggil metod dari kosan model untuk diinputkan datanya
                    $hasil = $this->materialmodel->insertData();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                    $data['alatbahan'] = $this->materialmodel->getAll();
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('material/Daftarmaterial', $data);
                }    
                //
        }else{
                    //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('material/Inputmaterial');
        }
	}

    public function daftarmaterial(){
        $data['alatbahan'] = $this->materialmodel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('material/Daftarmaterial', $data);
    }

    public function editmaterial($id){
        $data['alatbahan'] = $this->materialmodel->editData($id);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('material/Editmaterial', $data);
    }

    public function editmaterialproses(){
        
        $id = $_POST['id_material'];
        $nama = $_POST['namamaterial'];
        $jm = $_POST['jenismaterial'];
        $satuan = $_POST['satuan'];
        $harga = $_POST['harga'];
        

        $validation =  \Config\Services::validation();

        if (! $this->validate([
                'namamaterial' => 'required',
                'satuan' => 'required',
                'harga' => 'required|numeric'
        ],
                [   // Errors
                    'namamaterial' => [
                        'required' => 'Nama material tidak boleh kosong',
                    ],
                    'satuan' => [
                        'required' => 'Satuan material tidak boleh kosong'
                    ],
                    'harga' => [
                        'required' => 'Harga tidak boleh kosong',
                        'numeric' => 'Harga harus angka'
                    ]
                ]
        ))
        {                
            
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('material/Editmaterial',[
                'validation' => $this->validator,
                'alatbahan' => $this->materialmodel->editData($id)
            ]);

        }
        else
        {
            //panggil metod dari kosan model untuk diinputkan datanya
            $hasil = $this->materialmodel->updateData();
            if($hasil->connID->affected_rows>0){
                ?>
                <script type="text/javascript">
                    alert("Sukses diupdate");
                </script>
                <?php	
            }
            $data['alatbahan'] = $this->materialmodel->getAll();
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('material/Daftarmaterial', $data);
        }    
    }

    public function deletematerial($id){
		$this->materialmodel->deleteData($id);

		return redirect()->to(base_url('material/daftarmaterial')); 
	}
}