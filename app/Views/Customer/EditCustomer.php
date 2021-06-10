<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Customer</h1>
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

      <?php
        if(isset($validation)){
          echo $validation->listErrors();
        }

        //dapatkan data dari koskosan dan simpan ke variabel lokal
        foreach($customer as $row):
          $id = $row->id_customer;
          $nama = $row->nama;
          $alamat = $row->alamat;
          $no_hp = $row->no_hp;
        endforeach;
      ?>
        <div class="row">
        <?= form_open('customer/editcustomerproses') ?>
            
            <input type="hidden" id="id_customer" name="id_customer" value="<?= $id?>">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Customer</label>
                    <?php
                        //jika set value namakosan tidak kosong maka isi $nama diganti dengan isian dari user
                        if(strlen(set_value('nama'))>0){
                          $nama = set_value('nama');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $nama?>" placeholder="Diisi dengan nama customer">
                </div>
                <div class="mb-3">
                    <label for="alamat">Alamat</label>
                    <?php
                        //jika set value alamat tidak kosong maka isi $alamat diganti dengan isian dari user
                        if(strlen(set_value('alamat'))>0){
                          $alamat = set_value('alamat');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $alamat?>" placeholder="Diisi dengan Alamat">
                </div>
                <div class="mb-3">
                    <label for="no_hp">Telepon</label> 
                    <?php
                        //jika set value telepon tidak kosong maka isi $telepon diganti dengan isian dari user
                        if(strlen(set_value('no_hp'))>0){
                          $no_hp = set_value('no_hp');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $no_hp?>" placeholder="Diisi dengan Nomor Telepon (081321405677)">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

    </main>
  </div>
</div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?= base_url('dashboard/dashboard.js') ?>"></script>
  </body>
</html>
