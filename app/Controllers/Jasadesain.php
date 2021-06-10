<?php
namespace App\Controllers;

use App\Models\JasadesainModel;

class Jasadesain extends BaseController
{
	public function __construct()
    {
        //load kelas AkunModel
        $this->jasadesainmodel = new JasadesainModel();
    }

    public function index()
	{

        //cek dulu apakah kondisi form sudah diisi atau belum, kalau belum berarti tidak perlu memanggil fungsi validasi
        if(isset($_POST['jenis_jasa_desain']) and isset($_POST['tipe_desain'])){
                //
                $validation =  \Config\Services::validation();

                if (! $this->validate([
                        'jenis_jasa_desain' => 'required',
                        'tipe_desain' => 'required',
                        
                ],
                        [   // Errors
                            'jenis_jasa_desain' => [
                                'required' => 'jenis jasa desain tidak boleh kosong',
                            ],
                            'tipe_desain' => [
                                'required' => 'Tipe desain tidak boleh kosong '
                            ]
                        ]
                ))
                {                
                    
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Jasadesain/InputJasadesain',[
                        'validation' => $this->validator,
                    ]);

                }
                else
                {
                    //panggil metod dari jasa desain model untuk diinputkan datanya
                    $hasil = $this->jasadesainmodel->insertData();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                    $data['jasadesain'] = $this->jasadesainmodel->getAll();
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Jasadesain/listJasadesain', $data);
                }    
                //
        }else{
                    //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Jasadesain/InputJasadesain');
        }
	}

    public function listjasadesain(){
        $data['jasadesain'] = $this->jasadesainmodel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Jasadesain/listJasadesain', $data);
    }

    public function editjasadesain($id){
        $data['jasadesain'] = $this->jasadesainmodel->editData($id);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Jasadesain/EditJasadesain', $data);
    }

    public function editjasadesainproses(){
        
        $id = $_POST['id_jasa_desain'];
        $jenis_jasa_desain = $_POST['jenis_jasa_desain'];
        $tipe_desain = $_POST['tipe_desain'];
        
        

        $validation =  \Config\Services::validation();

        if (! $this->validate([
                'jenis_jasa_desain' => 'required',
                'tipe_desain' => 'required',
                
        ],
                [   // Errors
                    'jenis_jasa_desain' => [
                        'required' => 'jenis jasa desain tidak boleh kosong',
                    ],
                    'tipe_desain' => [
                        'required' => 'tipe desain tidak boleh kosong'
                    ]
                    
                ]
        ))
        {                
            
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('jasadesain/Editjasadesain',[
                'validation' => $this->validator,
                'jasadesain' => $this->jasadesainmodel->editData($id)
            ]);

        }
        else
        {
            //panggil metod dari jasa desain model untuk diinputkan datanya
            $hasil = $this->jasadesainmodel->updateData();
            if($hasil->connID->affected_rows>0){
                ?>
                <script type="text/javascript">
                    alert("Sukses diupdate");
                </script>
                <?php	
            }
            $data['jasadesain'] = $this->jasadesainmodel->getAll();
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Jasadesain/listJasadesain', $data);
        }    
    }

    public function deletejasadesain($id){
		$this->jasadesainmodel->deleteData($id);

		return redirect()->to(base_url('Jasadesain/listJasadesain')); 
	}
}