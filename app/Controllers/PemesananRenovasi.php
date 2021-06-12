<?php
namespace App\Controllers;

use App\Models\PemesananRenovasiModel;
use App\Models\CustomerModel;

class PemesananRenovasi extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PemesananRenovasiModel dan SupplierModel
        $this->PemesananRenovasiModel = new PemesananRenovasiModel();
        $this->CustomerModel = new CustomerModel();
    }

    public function index(){

        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        $data['customer'] = $this->CustomerModel->getAll();
        $data['renovasi'] = $this->PemesananRenovasiModel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['customer'] as $row):
            array_push($ar, array($row['id_customer'],0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;
        $data['infocustomer'] = $ar;

        // $ar = array();
        // $i = 0;
        // foreach($data['renovasi'] as $row):
        //     array_push($ar, array($row['id_pesan'],0)); //inisialisasi array [1],[2],[3] dengan 0
        //     $i++;
        // endforeach;
        // $data['infopesanan'] = $ar;

        //hasil array jumlah data kosan

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('PemesananRenovasi/DaftarCustomer', $data);
    }

    //input pemesanan
    public function inputpemesanan($id_customer){
        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        
        $data['id_customer'] = $id_customer;
        //print_r($id_supplier);
        
        $hasil = $this->CustomerModel->getCustomerByIdCustomer($id_customer);

        foreach($hasil as $row):
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $alamat = $row->alamat;
            $no_hp = $row->no_hp;
        endforeach;

        
        $data['customer'] = $hasil;
        $data['id_customer'] = $id_customer; //id kosan
        $data['nama'] = $nama;
        $data['alamat'] = $alamat;
        $data['no_hp'] = $no_hp;
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananRenovasi/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
       //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //   return redirect()->to(base_url('home')); 
        //}

        helper('rupiah');
        $data['id_customer'] = $_POST['id_customer'];

        //print_r($id_kamar);
        $hasil = $this->CustomerModel->getCustomerByIdCustomer($data['id_customer']);

        foreach($hasil as $row):
            $id_customer = $row->id_customer;
            $nama = $row->nama;
            $alamat = $row->alamat;
            $no_hp = $row->no_hp;
        endforeach;

        //print_r($hasil);
        
        $data['customer'] = $hasil;
        $data['id_customer'] = $id_customer; //id kosan
        $data['nama'] = $nama;
        $data['alamat'] = $alamat;
        
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['tgl_pesan']) and isset($_POST['besar_bayar'])){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'tgl_pesan' => 'required',
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'tgl_pesan' => [
                                        'required' => 'Tanggal mulai tidak boleh kosong',
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
                echo view('PemesananRenovasi/Inputpemesanan',[
                        'validation' => $this->validator,
                        'id_customer' => $data['id_customer'],
                        'nama' => $data['nama'],
                        'alamat' => $data['alamat'],
                        'no_hp' => $data['no_hp']
                    ]);
            }else{
                //disini diisi dengan kalau berhasil / tidak ada error
                //panggil metod dari kosan model untuk diinputkan datanya
                //$hasil = $this->PemesananRenovasiModel->insertData();
                $hasil = $this->PemesananRenovasiModel->insertData();                                
                {
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                // $data['supplier'] = $this->SupplierModel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananRenovasiModel->getAll();

                $data['customer'] = $hasil;  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('PemesananRenovasi/ListCustomer', $data);                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananRenovasi/Inputpemesanan', $data);
        }
    }    
}










/*<?php
namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\SupplierModel;

class Pemesanan extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PenghuniModel dan KosanModel
        $this->PemesananModel = new PemesananModel();
        $this->SupplierModel = new SupplierModel();
    }

    public function index(){

        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['supplier'] = $this->suppliermodel->getAll();

        //hasil array jumlah data kosan
        $data['infosupplier'] = $this->suppliermodel->getAll();

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/listsupplier', $data);
    }

    //input pemesanan
    public function inputpemesanan($id, $nama){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        
        $data['id_supplier'] = $id;
        $data['nama_supplier'] =$nama;
        
        $hasil = $this->suppliermodel->getSupplierByIdSupplier($id);

        foreach($hasil as $row):
            $id = $row->id_supplier;
            $nama = $row->nama;
            $harga = $row->harga;
        endforeach;

        
        $data['supplier'] = $hasil;
        $data['id_supplier'] = $id; //id kosan
        $data['nama_supplier'] = $nama;
        $data['harga'] = $harga;
        
        $data['supplier'] = $this->SupplierModel->getAllOrderByName();
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
       //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        
        $data['id_supplier'] = $_POST['id_supplier'];
        $data['nama_supplier'] = $_POST['nama_supplier'];
        

        //print_r($id_kamar);
        $hasil = $this->suppliermodel->getKamarByIdKamar($data['id_supplier']);

        foreach($hasil as $row):
            $id = $row->id_supplier;
            $nama = $row->nama;
            $harga = $row->harga;
        endforeach;

        //print_r($hasil);

        
        $data['supplier'] = $hasil;
        $data['id_supplier'] = $id; //id kosan
        $data['nama_supplier'] = $nama;
        $data['harga'] = $harga;
        
        $data['supplier'] = $this->SupplierModel->getAllOrderByName();
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['tgl_pesan'])  and isset($_POST['besar_bayar'])){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'tanggal_pesan' => 'required',
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'tanggal_pesan' => [
                                        'required' => 'Tanggal mulai tidak boleh kosong',
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
                echo view('Pemesanan/Inputpemesanan',[
                        'validation' => $this->validator,
                        'id_supplier' => $data['id_supplier'],
                        'nama_supplier' => $data['nama_supplier'],
                        'harga' => $data['harga'],
                    ]);
            }else{
                //disini diisi dengan kalau berhasil / tidak ada error
                //panggil metod dari kosan model untuk diinputkan datanya
                //$hasil = $this->PemesananModel->insertData();
                $hasil = $this->PemesananModel->insertData();
                
                
                if($hasil->connID->affected_rows>0){
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                $data['supplier'] = $this->suppliermodel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananModel->getListSupplier($data['id']);

                $data['supplier'] = $hasil;
                $data['id_supplier'] = $data['id_supplier'];  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('Pemesanan/ListSupplier', $data);
                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);
        }
    }    

    //perubahan status pemesanan
    public function perubahan(){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $data['supplier'] = $this->model->getAll();
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/Rubah', $data);
    }

    //perubahan status pemesanan
    public function perubahan_list_kamar($id, $kos){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        //$hasil = $this->kamarmodel->getKamar($id);
        $hasil = $this->PemesananModel->getListSupplier($id);

        $data['supplier'] = $hasil;
        //print_r($hasil);
        $data['id_supplier'] = $id;
        $data['nama_supplier'] = $nama;
        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/Listsupplierubahstatus', $data);
    }

    //ubah status kamar
    public function ubahstatussupplier($id){
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        $this->PemesananModel->updateStatusSupplier($id);

        return redirect()->to(base_url('pemesanan/perubahan')); 
    }

} */