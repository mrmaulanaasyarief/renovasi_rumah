<?php
namespace App\Controllers;

use App\Models\CustomerModel;

class Customer extends BaseController
{
	public function __construct()
    {
        //load kelas AkunModel
        $this->customermodel = new CustomerModel();
    }

    public function index()
	{

        //cek dulu apakah kondisi form sudah diisi atau belum, kalau belum berarti tidak perlu memanggil fungsi validasi
        if(isset($_POST['nama']) and isset($_POST['alamat']) and isset($_POST['no_hp'])){
                //
                $validation =  \Config\Services::validation();

                if (! $this->validate([
                        'nama' => 'required',
                        'alamat' => 'required',
                        'no_hp' => 'required|numeric'
                ],
                        [   // Errors
                            'nama' => [
                                'required' => 'Nama Customer tidak boleh kosong',
                            ],
                            'alamat' => [
                                'required' => 'Alamat Customer tidak boleh kosong'
                            ],
                            'no_hp' => [
                                'required' => 'Nomor telepon tidak boleh kosong',
                                'numeric' => 'Nomor telepon harus angka'
                            ]
                        ]
                ))
                {                
                    
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Customer/InputCustomer',[
                        'validation' => $this->validator,
                    ]);

                }
                else
                {
                    //panggil metod dari kosan model untuk diinputkan datanya
                    $hasil = $this->customermodel->insertData();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                    $data['customer'] = $this->customermodel->getAll();
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Customer/listcustomer', $data);
                }    
                //
        }else{
                    //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Customer/InputCustomer');
        }
	}

    public function listcustomer(){
        $data['customer'] = $this->customermodel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Customer/listcustomer', $data);
    }

    public function editcustomer($id){
        $data['customer'] = $this->customermodel->editData($id);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Customer/EditCustomer', $data);
    }

    public function editcustomerproses(){
        
        $id = $_POST['id_customer'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        

        $validation =  \Config\Services::validation();

        if (! $this->validate([
                'nama' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required|numeric'
        ],
                [   // Errors
                    'nama' => [
                        'required' => 'Nama Customer tidak boleh kosong',
                    ],
                    'alamat' => [
                        'required' => 'Alamat Customer tidak boleh kosong'
                    ],
                    'no_hp' => [
                        'required' => 'Nomor telepon tidak boleh kosong',
                        'numeric' => 'Nomor telepon harus angka'
                    ]
                ]
        ))
        {                
            
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Customer/EditCustomer',[
                'validation' => $this->validator,
                'customer' => $this->customermodel->editData($id)
            ]);

        }
        else
        {
            //panggil metod dari kosan model untuk diinputkan datanya
            $hasil = $this->customermodel->updateData();
            if($hasil->connID->affected_rows>0){
                ?>
                <script type="text/javascript">
                    alert("Sukses diupdate");
                </script>
                <?php	
            }
            $data['customer'] = $this->customermodel->getAll();
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Customer/listcustomer', $data);
        }    
    }

    public function deletecustomer($id){
		$this->customermodel->deleteData($id);

		return redirect()->to(base_url('Customer/listcustomer')); 
	}
}