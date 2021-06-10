<?php
namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\CustomerModel;

class Pemesanan extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PenghuniModel dan KosanModel
        $this->PemesananModel = new PemesananModel();
        $this->CustomerModel = new CustomerModel();
    }

    public function index(){

        $data['customer'] = $this->CustomerModel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['customer'] as $row):
            array_push($ar, array($row['id_customer'],0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        //hasil array jumlah data kosan
        $data['infocustomer'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/Daftarcustomer', $data);
    }

    //input pemesanan
    public function inputpemesanan($id_customer){
        
        $data['id_customer'] = $id_customer;
        
        $hasil = $this->CustomerModel->getCustomerByIdCustomer($id_customer);

        foreach($hasil as $row):
            $id = $row->id_customer;
            $nama = $row->nama;
            $alamat = $row->alamat;
            $no_hp = $row->no_hp;
        endforeach;

        
        $data['customer'] = $hasil;
        $data['id'] = $id; //id kosan
        $data['nama'] = $nama;
        $data['alamat'] = $alamat;
        $data['no_hp'] = $no_hp;
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
        
        $data['id_customer'] = $_POST['id_customer'];
        

        //print_r($id_kamar);
        $hasil = $this->CustomerModel->getCustomerByIdCustomer($data['id_customer']);

        foreach($hasil as $row):
            $id = $row->id_customer;
            $nama = $row->nama;
            $alamat = $row->alamat;
            $no_hp = $row->no_hp;
        endforeach;

        //print_r($hasil);

        
        $data['customer'] = $hasil;
        $data['id'] = $id; //id kosan
        $data['nama'] = $nama;
        $data['alamat'] = $alamat;
        $data['no_hp'] = $no_hp;
        
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['tgl_renovasi']) and isset($_POST['harga_deal']) and isset($_POST['besar_bayar'])){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'tgl_renovasi' => 'required',
                                'harga_deal' => 'required',
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'tgl_renovasi' => [
                                        'required' => 'Tanggal mulai tidak boleh kosong',
                                    ],
                                    'harga_deal' => [
                                        'required' => 'Harga deal tidak boleh kosong'
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
                        'id' => $data['id'],
                        'nama' => $data['nama'],
                        'alamat' => $data['alamat'],
                        'no_hp' => $data['no_hp']
                    ]);
            }else{
                //disini diisi dengan kalau berhasil / tidak ada error
                //panggil metod dari kosan model untuk diinputkan datanya
                //$hasil = $this->PemesananModel->insertData();
                $hasil = $this->PemesananModel->insertData();
                
                
                {
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                $data['customer'] = $this->CustomerModel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananModel->insertdata($data['id_customer']);

                $data['id_customer'] = $data['id_customer'];  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('Pemesanan/DaftarCustomer', $data);
                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);
        }
    }    
}