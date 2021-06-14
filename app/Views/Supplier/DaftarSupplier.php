<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <?php
        $i=0;
        foreach($infopesanansuplier as $row):
          $i += 1;
        endforeach;  
      ?>
      
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pembayaran Pesanan Supplier</h1>
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

      <?php
        //print_r($infokosan);
        //Array ( [0] => Array ( [0] => 6 [1] => 33 [2] => 2 [3] => 31 ) [1] => Array ( [0] => 7 [1] => 1 [2] => 1 [3] => 0 ) [2] => Array ( [0] => 17 [1] => 2 [2] => 0 [3] => 2 ) )
      ?>
      <div class="row">
      <?php
          if($i>0):
              $i = 1;
              foreach($infopesanansuplier as $row):
                if(fmod($i,3)==0){
                  ?> 
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $infopesanansuplier[$i-1][2]." (ID = ".$infopesanansuplier[$i-1][0].")";?>                                      
                                    </h5>
                                    <p class="card-text"><?= $infopesanansuplier[$i-1][1].' ('.$infopesanansuplier[$i-1][4].')';?>
                                    </p>
                                    
                                    <a href="<?= base_url('Supplier/ListPembayaran/'.$infopesanansuplier[$i-1][0].'/'.$jenis_pemesanan) ?>" class="btn btn-primary">Lihat Pembayaran</a>
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
                                    <h5 class="card-title"><?= $infopesanansuplier[$i-1][2]." (ID = ".$infopesanansuplier[$i-1][0].")";?>                                      
                                    </h5>
                                    <p class="card-text"><?= $infopesanansuplier[$i-1][1].' ('.$infopesanansuplier[$i-1][4].')';?>
                                    </p>
                                    
                                    <a href="<?= base_url('Supplier/ListPembayaran/'.$infopesanansuplier[$i-1][0].'/'.$jenis_pemesanan) ?>" class="btn btn-primary">Lihat Pembayaran</a>
                                </div>
                            </div>
                        </div>
                  <?php
                } 
                $i = $i + 1; 
              endforeach;    
            else:
      ?>
      <p>Maaf, tidak ada pesanan untuk anda.</p>
      <?php endif; ?>
      </div>

     
    </main>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?= base_url('dashboard/dashboard.js') ?>"></script>
 


    </body>
</html>