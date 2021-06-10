<?php
namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\SupplierModel;

class Pemesanan extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PemesananModel dan SupplierModel
        $this->PemesananModel = new PemesananModel();
        $this->SupplierModel = new SupplierModel();
    }

    public function index(){

        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        $data['supplier'] = $this->SupplierModel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['supplier'] as $row):
            array_push($ar, array($row['id_supplier'],0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        //hasil array jumlah data kosan
        $data['infosupplier'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/Daftarsupplier', $data);
    }

    //input pemesanan
    public function inputpemesanan($id_supplier){
        helper('rupiah');
        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        
        $data['id_supplier'] = $id_supplier;
        //print_r($id_supplier);
        
        $hasil = $this->SupplierModel->getSupplierByIdSupplier($id_supplier);

        foreach($hasil as $row):
            $id_supplier = $row->id_supplier;
            $nama = $row->nama_supplier;
            $alamat = $row->alamat_supplier;
            $telp_supplier = $row->telepon_supplier;
            $jenis_material = $row->jenis_material;
            $harga_material = $row->harga_material;
        endforeach;

        
        $data['supplier'] = $hasil;
        $data['id_supplier'] = $id_supplier; //id kosan
        $data['nama'] = $nama;
        $data['alamat'] = $alamat;
        $data['telp_supplier'] = $telp_supplier;
        $data['jenis_material'] = $jenis_material;
        $data['harga_material'] = $harga_material;
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
        helper('rupiah');
       //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //   return redirect()->to(base_url('home')); 
        //}


        $data['id_supplier'] = $_POST['id_supplier'];

        //print_r($id_kamar);
        $hasil = $this->SupplierModel->getSupplierByIdSupplier($data['id_supplier']);

        foreach($hasil as $row):
            $id_supplier = $row->id_supplier;
            $nama = $row->nama_supplier;
            $alamat = $row->alamat_supplier;
            $jenis_material = $row->jenis_material;
        endforeach;

        //print_r($hasil);

        
        $data['supplier'] = $hasil;
        $data['id_supplier'] = $id_supplier; //id kosan
        $data['nama'] = $nama;
        $data['alamat'] = $alamat;
        $data['jenis_material'] = $jenis_material;
        
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['tanggal_pesan']) and isset($_POST['besar_bayar'])){
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
                        'nama' => $data['nama'],
                        'alamat' => $data['alamat'],
                        'telp_supplier' => $data['telp_supplier']
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
                // $data['supplier'] = $this->SupplierModel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananModel->getAll();

                $data['supplier'] = $hasil;  

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
}



