<?php
namespace App\Controllers;

use App\Models\SupplierModel;

class Supplier extends BaseController
{
	public function __construct()
    {
        //load kelas AkunModel
        $this->suppliermodel = new SupplierModel();
    }

    public function index()
	{

        //cek dulu apakah kondisi form sudah diisi atau belum, kalau belum berarti tidak perlu memanggil fungsi validasi
        if(isset($_POST['nama_supplier']) and isset($_POST['alamat_supplier']) and isset($_POST['telepon_supplier'])){
                //
                $validation =  \Config\Services::validation();

                if (! $this->validate([
                        'nama_supplier' => 'required',
                        'alamat_supplier' => 'required',
                        'telepon_supplier' => 'required|numeric',
                        'jenis_material' => 'required',
                        'harga_material' => 'required|numeric'

                ],
                        [   // Errors
                            'nama_supplier' => [
                                'required' => 'Nama Supplier tidak boleh kosong',
                            ],
                            'alamat_supplier' => [
                                'required' => 'Alamat Supplier tidak boleh kosong'
                            ],
                            'telepon_supplier' => [
                                'required' => 'Nomor telepon tidak boleh kosong',
                                'numeric' => 'Nomor telepon harus angka'
                            ],
                            'jenis_material' => [
                                'required' => 'Jenis pegawai tidak boleh kosong',
                            ],
                            'harga_material' => [
                                'required' => 'Harga material tidak boleh kosong',
                                'numeric' => 'Harga material harus angka'
                            ]
                        ]
                ))
                {                
                    
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Supplier/InputSupplier',[
                        'validation' => $this->validator,
                    ]);

                }
                else
                {
                    //panggil metod dari kosan model untuk diinputkan datanya
                    $hasil = $this->suppliermodel->insertData();
                    if($hasil->connID->affected_rows>0){
                        ?>
                        <script type="text/javascript">
                            alert("Sukses ditambahkan");
                        </script>
                        <?php	
                    }
                    $data['supplier'] = $this->suppliermodel->getAll();
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Supplier/listsupplier', $data);
                }    
                //
        }else{
                    //kondisi awal ketika di akses, jadi tidak perlu memanggil validasi
                    echo view('HeaderBootstrap');
                    echo view('SidebarBootstrap');
                    echo view('Supplier/InputSupplier');
        }
	}

    public function listsupplier(){
        $data['supplier'] = $this->suppliermodel->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Supplier/listsupplier', $data);
    }

    public function editsupplier($id){
        $data['supplier'] = $this->suppliermodel->editData($id);
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Supplier/EditSupplier', $data);
    }

    public function editsupplierproses(){
        
        $id = $_POST['id_supplier'];
        $nama = $_POST['nama_supplier'];
        $alamat = $_POST['alamat_supplier'];
        $telp_supplier = $_POST['telepon_supplier'];
        $jenis_material = $_POST['jenis_material'];
        $harga_material = $_POST['harga_material'];
        

        $validation =  \Config\Services::validation();

        if (! $this->validate([
                'nama_supplier' => 'required',
                'alamat_supplier' => 'required',
                'telepon_supplier' => 'required|numeric',
                'jenis_material' => 'required',
                'harga_material' => 'required|numeric'

        ],
                [   // Errors
                    'nama_supplier' => [
                        'required' => 'Nama Supplier tidak boleh kosong',
                    ],
                    'alamat_supplier' => [
                        'required' => 'Alamat Supplier tidak boleh kosong'
                    ],
                    'telepon_supplier' => [
                        'required' => 'Nomor telepon tidak boleh kosong',
                        'numeric' => 'Nomor telepon harus angka'
                    ],
                    'jenis_material' => [
                        'required' => 'Jenis material tidak boleh kosong'
                    ],
                    'harga_material' => [
                        'required' => 'Harga material tidak boleh kosong',
                        'numeric' => 'Harga material harus angka'
                    ]
                ]
        ))
        {                
            
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Supplier/EditSupplier',[
                'validation' => $this->validator,
                'supplier' => $this->suppliermodel->editData($id)
            ]);

        }
        else
        {
            //panggil metod dari kosan model untuk diinputkan datanya
            $hasil = $this->suppliermodel->updateData();
            if($hasil->connID->affected_rows>0){
                ?>
                <script type="text/javascript">
                    alert("Sukses diupdate");
                </script>
                <?php	
            }
            $data['supplier'] = $this->suppliermodel->getAll();
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Supplier/listsupplier', $data);
        }    
    }

    public function deletesupplier($id){
		$this->suppliermodel->deleteData($id);

		return redirect()->to(base_url('Supplier/listsupplier')); 
	}
}