<?php
namespace App\Controllers;

use App\Models\PemesananPegawaiModel;
use App\Models\PegawaiModel;

class PemesananPegawai extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PemesananModel dan PegawaiModel
        $this->PemesananPegawaiModel = new PemesananPegawaiModel();
        $this->PegawaiModel = new PegawaiModel();
    }

    public function index(){

        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        $data['pegawai'] = $this->PegawaiModel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['pegawai'] as $row):
            array_push($ar, array($row['id_pegawai'],0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        //hasil array jumlah data kosan
        $data['infopegawai'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('PemesananPegawai/DaftarPegawai', $data);
    }

    //input pemesanan
    public function inputpemesanan($id_pegawai){
        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        
        $data['id_pegawai'] = $id_pegawai;
        //print_r($id_pegawai);
        
        $hasil = $this->PegawaiModel->getPegawaiByIdPegawai($id_pegawai);

        foreach($hasil as $row):
            $id_pegawai = $row->id_pegawai;
            $nama = $row->nama_pegawai;
            $alamat = $row->alamat_pegawai;
            $telp_pegawai = $row->telp_pegawai;
        endforeach;

        
        $data['pegawai'] = $hasil;
        $data['id_pegawai'] = $id_pegawai; //id kosan
        $data['nama_pegawai'] = $nama;
        $data['alamat_pegawai'] = $alamat;
        $data['telp_pegawai'] = $telp_pegawai;
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananPegawai/InputPemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
       //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //   return redirect()->to(base_url('home')); 
        //}


        $data['id_pegawai'] = $_POST['id_pegawai'];

        //print_r($id_kamar);
        $hasil = $this->PegawaiModel->getPegawaiByIdPegawai($data['id_pegawai']);

        foreach($hasil as $row):
            $id_pegawai = $row->id_pegawai;
            $nama = $row->nama_pegawai;
            $alamat = $row->alamat_pegawai;
            $telp_pegawai = $row->telp_pegawai;
        endforeach;

        //print_r($hasil);

        
        $data['pegawai'] = $hasil;
        $data['id_pegawai'] = $id_pegawai; //id kosan
        $data['nama_pegawai'] = $nama;
        $data['alamat_pegawai'] = $alamat;
        $data['telp_pegawai'] = $telp_pegawai;
        
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['jumlah_hari']) and isset($_POST['gaji'])){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'jumlah_hari' => 'required',
                                'gaji' => 'required'
                        ],
                                [   // Errors
                                    'jumlah_hari' => [
                                        'required' => 'Jumlah hari kerja tidak boleh kosong',
                                    ],
                                    'gaji' => [
                                        'required' => 'Tarif kerja pegawai tidak boleh kosong'
                                    ]
                                ]
                )
            ){
                //disini diisi kalau ada error
                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('PemesananPegawai/InputPemesanan',[
                        'validation' => $this->validator,
                        'jumlah_hari' => $_POST['jumlah_hari'],
                        'gaji' => $_POST['gaji']
                    ]);
            }else{
                //disini diisi dengan kalau berhasil / tidak ada error
                //panggil metod dari kosan model untuk diinputkan datanya
                //$hasil = $this->PemesananModel->insertData();
                $hasil = $this->PemesananPegawaiModel->insertData();
                
                
                if($hasil->connID->affected_rows>0){
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                // $data['pegawai'] = $this->PegawaiModel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananPegawaiModel->getAll();

                $data['pegawai'] = $hasil;  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('PemesananPegawai/ListPegawai', $data);
                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananPegawai/InputPemesanan', $data);
        }
    }    
}