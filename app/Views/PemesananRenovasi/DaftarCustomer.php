<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pesanan Renovasi Customer</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
        </div>
      </div>

      <canvas class="my-4 w-100" id="myChart" width="900" height="380" hidden></canvas>

      
      <div class="row">
      <?php
              $i = 1;
              foreach($customer as $row):
                if(fmod($i,3)==0){
                  ?> 
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= ucfirst($row['nama'])." (ID = ".$row['id_customer'].")";?>
                                    </h5>
                                    <p class="card-text"><?= ucfirst($row['alamat']).' ('.$row['no_hp'].')';?>
                                    </p>
                                    
                                    <a href="<?= base_url('PemesananRenovasi/InputPemesanan/'.$row['id_customer'].'/'.$row['nama']) ?>" class="btn btn-primary">Pesan Renovasi</a>
                                </div>
                            </div>
                        </div>
                        <p>
                      </div>  
                      <div class="row">
                  <?php
                }else{
                  ?>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= ucfirst($row['nama'])." (ID = ".$row['id_customer'].")";?>
                                    </h5>
                                    <p class="card-text"><?= ucfirst($row['alamat']).' ('.$row['no_hp'].')';?>
                                    </p>
                                    <a href="<?= base_url('PemesananRenovasi/InputPemesanan/'.$row['id_customer'].'/'.$row['nama']) ?>" class="btn btn-primary">Pesan Renovasi</a>
                                    
                                </div>
                            </div>
                        </div>

                      
                  <?php
                } 
                $i = $i + 1; 
              endforeach;    
              //echo count($koskosan);
      ?>
      </div>

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Daftar Pesanan Renovasi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          
        </div>
      </div>

      <canvas class="my-4 w-100" id="myChart" width="900" height="380" hidden></canvas>
            <div>
              <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">ID Pesanan</th>
                    <th scope="col">Tanggal Pesanan</th>
                    <th scope="col">Nama Customer</th>
                    <th scope="col">Tanggal Renovasi</th>
                    <th scope="col">Jenis Renovasi</th>
                    <th scope="col">Harga Deal</th>
                    <th scope="col">Status Bayar</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($renovasi as $row):
                  ?>
                  <tr>
                    <th scope="row"><?= $row['id_pesan'] ?></th>
                    <td><?= $row['tgl_pesan'] ?></td>
                    <td><?php
                      foreach($customer as $row2):
                        if($row2['id_customer']==$row['id_customer']){
                          echo(ucfirst($row2['nama']));
                        }
                      endforeach;?>
                    </td>
                    <td><?= $row['tgl_renovasi'] ?></td>
                    <td><?= $row['jenis_renovasi'] ?></td>
                    <td><?= rupiah($row['harga_deal']) ?></td>
                    <td><?= $row['status_bayar'] ?></td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Tambah Pesanan
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="<?= base_url('PemesananJasaDesain/index/'.$row['id_pesan']) ?>">Pemesanan Jasa Desain</a>
                          <a class="dropdown-item" href="<?= base_url('PemesananPegawai/index/'.$row['id_pesan']) ?>">Pemesanan Pegawai</a>
                          <a class="dropdown-item" href="<?= base_url('PemesananMaterial/index/'.$row['id_pesan']) ?>">Pemesanan Material</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <?php
                    endforeach;
                  ?>
                </tbody>
              </table>
            </div>

              <?php
                  //menampilkan sesi nama
                  //echo $_SESSION['nama'];
              ?>
     
    </main>
  </div>
</div>

<!-- Modals -->     

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>



      <script>
          function deleteConfirm(url){
              url2 = "<?= base_url('kosan/daftarkosan') ?>"; //diisi dengan halaman ini
              
              var tomboldelete = document.getElementById('btn-delete')  
              tomboldelete.setAttribute("href", url); //akan meload kontroller delete

              var tomboldelete = document.getElementById('btn-batal')
              tomboldelete.setAttribute("href", url2); //akan meload halaman ini

              var tombolbatal = document.getElementById('btn-tutup')
              tombolbatal.setAttribute("href", url2); //akan meload halaman ini

              var pesan = "Data dengan ID <b>"
              var pesan2 = " </b>akan dihapus"
              var n = url.lastIndexOf("/")
              var res = url.substring(n+1);
              document.getElementById("xid").innerHTML = pesan.concat(res,pesan2);

              var myModal = new bootstrap.Modal(document.getElementById('deleteModal'), {  keyboard: false });
              
              myModal.show();
            
          }
      </script>
      <!-- Logout Delete Confirmation-->
      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
              <a id="btn-tutup" class="btn btn-secondary" href="#">X</a>
            </div>
            <div class="modal-body" id="xid"></div>
            <div class="modal-footer">
              <a id="btn-batal" class="btn btn-secondary" href="#">Batalkan</a>
              <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>
            </div>
          </div>
        </div>
      </div>    

<!-- Bootstrap JS -->
<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?= base_url('dashboard/dashboard.js') ?>"></script>
 


    </body>
</html>