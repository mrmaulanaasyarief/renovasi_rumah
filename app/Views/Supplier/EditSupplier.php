<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Supplier</h1>
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
        foreach($supplier as $row):
          $id = $row->id_supplier;
          $nama = $row->nama_supplier;
          $alamat = $row->alamat_supplier;
          $telp_supplier = $row->telepon_supplier;
          $jenis_material = $row->jenis_material;
        endforeach;
      ?>
        <div class="row">
        <?= form_open('supplier/editsupplierproses') ?>
            
            <input type="hidden" id="id_supplier" name="id_supplier" value="<?= $id?>">
                <div class="mb-3">
                    <label for="nama_supplier" class="form-label">Nama Supplier</label>
                    <?php
                        //jika set value namakosan tidak kosong maka isi $nama diganti dengan isian dari user
                        if(strlen(set_value('nama_supplier'))>0){
                          $nama = set_value('nama_supplier');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="<?= $nama?>" placeholder="Diisi dengan nama supplier">
                </div>
                <div class="mb-3">
                    <label for="alamat_supplier">Alamat</label>
                    <?php
                        //jika set value alamat tidak kosong maka isi $alamat diganti dengan isian dari user
                        if(strlen(set_value('alamat_supplier'))>0){
                          $alamat = set_value('alamat_supplier');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="alamat_supplier" name="alamat_supplier" value="<?= $alamat?>" placeholder="Diisi dengan alamat">
                </div>
                <div class="mb-3">
                    <label for="telepon_supplier">Telepon</label> 
                    <?php
                        //jika set value telepon tidak kosong maka isi $telepon diganti dengan isian dari user
                        if(strlen(set_value('telepon_supplier'))>0){
                          $telp_supplier = set_value('telepon_supplier');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="telepon_supplier" name="telepon_supplier" value="<?= $telp_supplier?>" placeholder="Diisi dengan Nomor Telepon (081221405677)">
                </div>
                <div class="mb-3">
                    <label for="jenis_material">Jenis Material</label> 
                    <?php
                        //jika set value telepon tidak kosong maka isi $telepon diganti dengan isian dari user
                        if(strlen(set_value('jenis_material'))>0){
                          $jenis_material = set_value('jenis_material');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="jenis_material" name="jenis_material" value="<?= $jenis_material?>" placeholder="Diisi dengan jenis material">
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