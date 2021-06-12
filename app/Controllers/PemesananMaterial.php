<?php
namespace App\Controllers;

use App\Models\PemesananMaterialModel;
use App\Models\MaterialModel;

class PemesananMaterial extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PemesananMaterialModel dan PegawaiModel
        $this->PemesananMaterialModel = new PemesananMaterialModel();
        $this->MaterialModel = new MaterialModel();
    }

    public function index($id_renovasi = NULL){

        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}

        $data['alatbahan'] = $this->MaterialModel->getAll();
        $data['id_renovasi'] = $id_renovasi;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('PemesananMaterial/Daftarmaterial', $data);
    }

    //input PemesananMaterial
    public function inputpemesanan($id_renovasi, $id_material){
        //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //    return redirect()->to(base_url('home')); 
        //}
        $data['id_renovasi'] = $id_renovasi;
        $data['id_material'] = $id_material;
        //print_r($id_pegawai);
        
        $hasil = $this->MaterialModel->getMaterialByIdMaterial($id_material);

        foreach($hasil as $row):
            $id = $row->id_material;
            $nama = $row->nama;
            $satuan = $row->satuan;
            $harga = $row->harga;
        endforeach;
        
        $data['material'] = $hasil;
        $data['id_material'] = $id; //id kosan
        $data['nama'] = $nama;
        $data['satuan'] = $satuan;
        $data['harga'] = $harga;
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananMaterial/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
       //tambahkan pengecekan login
        //if(!isset($_SESSION['nama'])){
        //   return redirect()->to(base_url('home')); 
        //}


        $data['id_material'] = $_POST['id_material'];

        //print_r($id_kamar);
        $hasil = $this->MaterialModel->getMaterialByIdMaterial($data['id_material']);

        foreach($hasil as $row):
            $id = $row->id_material;
            $nama = $row->nama;
            $satuan = $row->satuan;
            $harga = $row->harga;
        endforeach;

        //print_r($hasil);

        
        $data['material'] = $hasil;
        $data['id_material'] = $id; //id kosan
        $data['nama'] = $nama;
        $data['satuan'] = $satuan;
        $data['harga'] = $harga;
        
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['tanggal_ambil']) and isset($_POST['total_trans']) and isset($_POST['besar_bayar'])){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'tanggal_ambil' => 'required',
                                'total_trans' => 'required',
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'tanggal_ambil' => [
                                        'required' => 'Tanggal ambil tidak boleh kosong',
                                    ],
                                    'total_trans' => [
                                        'required' => 'Total transaksi tidak boleh kosong'
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
                echo view('PemesananMaterial/Inputpemesanan',[
                        'validation' => $this->validator,
                        'id_material' => $data['id'],
                        'nama' => $data['nama'],
                        'satuan' => $data['satuan'],
                        'harga' => $data['harga']
                    ]);
            }else{
                $hasil = $this->PemesananMaterialModel->insertData();
                
                if($hasil->connID->affected_rows>0){
                    ?>
                    <script type="text/javascript">
                        alert("Sukses ditambahkan");
                    </script>
                    <?php	
                }
                // $data['pegawai'] = $this->PegawaiModel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananMaterialModel->getAll();

                $data['alatbahan'] = $hasil;  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('PemesananMaterial/Listmaterial', $data);
                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('PemesananMaterial/Inputpemesanan', $data);
        }
    }    
}