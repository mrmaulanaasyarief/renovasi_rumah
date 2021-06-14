<?php
namespace App\Controllers;

use App\Models\PemesananSupplierModel;
use App\Models\SupplierModel;

class PemesananSupplier extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PemesananSupplierModel dan PegawaiModel
        $this->PemesananSupplierModel = new PemesananSupplierModel();
        $this->SupplierModel = new SupplierModel();
    }

    public function index(){

        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
           return redirect()->to(base_url('home')); 
        }

        $data['supplier'] = $this->SupplierModel->getAll();

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('PemesananSupplier/Daftarsupplier', $data);
    }

    //input PemesananMaterial
    public function inputpemesanan($id_supplier){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
           return redirect()->to(base_url('home')); 
        }

        $hasil = $this->SupplierModel->getById($id_supplier);

        foreach($hasil as $row):
            $data['id_supplier'] = $row->id_supplier;
            $data['nama_supplier'] = $row->nama_supplier;
            $data['jenis_material'] = $row->jenis_material;
            $data['harga_material'] = $row->harga_material;
        endforeach;
    
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('PemesananSupplier/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
       //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
          return redirect()->to(base_url('home')); 
        }

        $hasil = $this->SupplierModel->getById($_POST['id_supplier']);

        foreach($hasil as $row):
            $data['id_supplier'] = $row->id_supplier;
            $data['nama_supplier'] = $row->nama_supplier;
            $data['jenis_material'] = $row->jenis_material;
            $data['harga_material'] = $row->harga_material;
        endforeach;
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset(
            $_POST['tanggal_pesan']) and 
            isset($_POST['tanggal_ambil']) and 
            isset($_POST['jumlah_unit']) and
            isset($_POST['besar_bayar'])
            ){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'tanggal_pesan' => 'required',
                                'tanggal_ambil' => 'required',
                                'jumlah_unit' => 'required',
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'tanggal_pesan' => [
                                        'required' => 'Harap pilih tanggal pesan',
                                    ],
                                    'tanggal_ambil' => [
                                        'required' => 'Harap pilih tanggal pesan',
                                    ],
                                    'jumlah_unit' => [
                                        'required' => 'Jumlah unit tidak boleh kosong'
                                    ],
                                    'besar_bayar' => [
                                        'required' => 'Besar bayar tidak boleh kosong'
                                    ]
                                ]
                )
            ){
                //disini diisi kalau ada error
                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('PemesananSupplier/Inputpemesanan',[
                        'validation' => $this->validator,
                        'id_supplier' => $data['id_supplier'],
                        'nama_supplier' => $data['nama_supplier'],
                        'jenis_material' => $data['jenis_material'],
                        'harga_material' => $data['harga_material']
                    ]);
            }else{
                $hasil = $this->PemesananSupplierModel->insertData();
                
                if($hasil->connID->affected_rows>0){
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                
                $hasil = $this->PemesananSupplierModel->getAll();

                $data['supplier'] = $hasil;  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('PemesananSupplier/Listsupplier', $data);
                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananSupplier/Inputpemesanan', $data);
        }
    }    
}