<?php
namespace App\Controllers;

use App\Models\SupplierModel;
use App\Models\PemesananSupplierModel;
use App\Models\PembayaranModel;

class Supplier extends BaseController
{
	public function __construct()
    {
        session_start();
        //load kelas AkunModel
        $this->suppliermodel = new SupplierModel();
        $this->PemesananSupplierModel = new PemesananSupplierModel();
        $this->PembayaranModel = new PembayaranModel();
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

    

    public function pesanan(){
        // tambahkan pengecekan login
        if(!isset($_SESSION['nama']) or $_SESSION['kelompok']!='supplier'){
            return redirect()->to(base_url('home')); 
        }

        $data['pesanansuplier'] = $this->PemesananSupplierModel->getAll();
        // $data['pesanansuplier'] = $this->PemesananSupplierModel->getSupplierId($_SESSION['id']);
        
        $ar = [];

        $i = 0;
        foreach($data['pesanansuplier'] as $row):
            if($row['id_supplier'] == $_SESSION['id']){
                array_push($ar, array($row['id_pesan'],$row['id_supplier'],'','','')); //inisialisasi array [1],[2],[3] dengan 0
                $i++;
            }
        endforeach;
        
        for($i=0;$i<count($ar);$i++){
            $supplier = $this->suppliermodel->getById($ar[$i][1]);
            foreach($supplier as $row):
                $ar[$i][2] = $row->nama_supplier;
                $ar[$i][3] = $row->alamat_supplier;
                $ar[$i][4] = $row->telepon_supplier;
            endforeach;
        }
        //hasil array jumlah data kosan
        $data['infopesanansuplier'] = $ar;
        $data['jenis_pemesanan'] = 'Supplier';

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Supplier/DaftarSupplier', $data);
    }

    //list history pembayaran berdasarkan 
    public function ListPembayaran($id_renov, $jenis_pemesanan, $id_pemesanan = NULL){
        helper('rupiah');
        //tambahkan pengecekan login
        if(!isset($_SESSION['nama'])){
            return redirect()->to(base_url('home')); 
        }

        if($id_pemesanan == NULL){
            $id_pemesanan = $id_renov;
        }
        $data['pembayaran'] = $this->PembayaranModel->getHistoryPembayaranByIdPemesanan($id_pemesanan, $jenis_pemesanan);

        if($jenis_pemesanan == 'Renovasi'){
            $data['harga_deal'] = $this->PemesananRenovasiModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Supplier'){
            $data['harga_deal'] = $this->PemesananSupplierModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Jasa Desain'){
            $data['harga_deal'] = $this->PemesananJasaDesainModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Material'){
            $data['harga_deal'] = $this->PemesananMaterialModel->getHargaDeal($id_pemesanan);
        }else if($jenis_pemesanan == 'Pegawai'){
            $data['harga_deal'] = $this->PemesananPegawaiModel->getHargaDeal($id_pemesanan);
        }

        $totalbayar = 0; $sisa_bayar= 0;
        foreach($data['pembayaran'] as $row):
            $id_pembayaran = $row->id_pembayaran;
            $id_pemesanan = $row->id_pemesanan;
            $jenis_pemesanan = $row->jenis_pemesanan;
            $no_kuitansi = $row->no_kuitansi;
            $tgl_bayar = $row->tgl_bayar;
            $besar_bayar = $row->besar_bayar;
            $totalbayar =  $totalbayar + $besar_bayar;
        endforeach;
        $sisa_bayar= $data['harga_deal']-$totalbayar;

        if($jenis_pemesanan == 'Supplier'){
            $data['supplier'] = $this->PemesananSupplierModel->getSupplierByIdPesan($id_renov);
            foreach($data['supplier'] as $row):
                $id_customer = $row->id_supplier;
                $nama = $row->nama_supplier;
                $alamat = $row->alamat_supplier;
                $no_hp = $row->telepon_supplier;
            endforeach;
        }else{
            $data['customer'] = $this->PemesananRenovasiModel->getCustomerByIdRenov($id_renov);
            foreach($data['customer'] as $row):
                $id_customer = $row->id_customer;
                $nama = $row->nama;
                $alamat = $row->alamat;
                $no_hp = $row->no_hp;
            endforeach;
        }
        
        $data['nama_customer'] = ucfirst($nama);
        $data['alamat_customer'] = ucfirst($alamat);
        $data['no_hp_customer'] = $no_hp;
        $data['id_renov'] = $id_renov;
        $data['jenis_pemesanan'] = $jenis_pemesanan;
        $data['id_pemesanan'] = $id_pemesanan;
        $data['harga_deal'] = $data['harga_deal'];
        $data['totalbayar'] = $totalbayar;
        $data['sisa_bayar'] = $sisa_bayar;

        // //dapatkan nomor kuitansinya
        // $nokuitansi = $this->PembayaranModel->getNoKuitansi($id_kos);
        // $data['nokuitansi'] = $nokuitansi;

        echo view('HeaderBootstrap');
        echo view('SidebarBootstrap');
        echo view('Supplier/Listpembayaran', $data);

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