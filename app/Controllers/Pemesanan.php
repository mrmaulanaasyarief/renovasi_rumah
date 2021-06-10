<?php
namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\JasadesainModel;

class Pemesanan extends BaseController
{
	public function __construct()
    {
        session_start();

        //load kelas PenghuniModel dan KosanModel
        $this->PemesananModel = new PemesananModel();
        $this->JasadesainModel = new JasadesainModel();
    }

    public function index(){

        $data['jasa_desain'] = $this->JasadesainModel->getAll();

        $ar = array();
        $i = 0;
        foreach($data['jasa_desain'] as $row):
            array_push($ar, array($row['id_jasa_desain'],0)); //inisialisasi array [1],[2],[3] dengan 0
            $i++;
        endforeach;

        //hasil array jumlah data kosan
        $data['infojasadesain'] = $ar;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Pemesanan/Daftarjasadesain', $data);
    }

    //input pemesanan
    public function inputpemesanan($id_jasa_desain){
        
        $data['id_jasa_desain'] = $id_jasa_desain;
        
        $hasil = $this->JasadesainModel->getJasadesainByIdJasadesain($id_jasa_desain);

        foreach($hasil as $row):
            $id = $row->id_jasa_desain;
            $jenis_jasa_desain = $row->jenis_jasa_desain;
            $tipe_desain = $row->tipe_desain;
            
        endforeach;

        
        $data['jasa_desain'] = $hasil;
        $data['id'] = $id; //id kosan
        $data['jenis_jasa_desain'] = $jenis_jasa_desain;
        $data['tipe_desain'] = $tipe_desain;
        
    
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);        
    }

    //input pemesanan
    public function prosesInput(){
        
        $data['id_jasa_desain'] = $_POST['id_jasa_desain'];
        

        //print_r($id_kamar);
        $hasil = $this->JasadesainModel->getJasadesainByIdJasadesain($data['id_jasa_desain']);

        foreach ($hasil as $row):
            $id = $row->id_jasa_desain;
            $jenis_jasa_desain = $row->jenis_jasa_desain;
            $tipe_desain = $row->tipe_desain;
            
        endforeach;

        //print_r($hasil);

        
        $data['jasa_desain'] = $hasil;
        $data['id'] = $id; //id kosan
        $data['jenis_jasa_desain'] = $jenis_jasa_desain;
        $data['tipe_desain'] = $tipe_desain;
       
        
        //echo $data['id'];
        //print_r($data['penghuni']);
        
        //cek apakah awal diakses, kalau awal berarti seluruh isian field yg bukan select/field dari db harusnya kosong
        if(isset($_POST['tgl_desain']) and isset($_POST['harga_deal']) and isset($_POST['besar_bayar'])){
            //load fungsi validasi
            $validation =  \Config\Services::validation();

            if (! $this->validate(
                        [
                                'tgl_desain' => 'required',
                                'harga_deal' => 'required',
                                'besar_bayar' => 'required'
                        ],
                                [   // Errors
                                    'tgl_desain' => [
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
                        'jenis_jasa_desain' => $data['jenis_jasa_desain'],
                        'tipe_desain' => $data['tipe_desain']
                        
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
                $data['jasa_desain'] = $this->JasadesainModel->getAll();

                //$hasil = $this->kamarmodel->getKamar($data['id']);
                $hasil = $this->PemesananModel->getAll();

                $data['jasa_desain'] = $hasil;  

                echo view('HeaderBootstrap');
                echo view('SidebarBootstrap');
                echo view('Pemesanan/Listjasadesain', $data);                
            }
        }else{
            //berarti kosong maka ditampilkan apa adanya, tidak perlu divalidasi
            echo view('HeaderBootstrap');
            echo view('SidebarBootstrap');
            echo view('Pemesanan/Inputpemesanan', $data);
        }
    }    
}
