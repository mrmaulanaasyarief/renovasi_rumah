<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Material</h1>
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
        foreach($alatbahan as $row):
          $id = $row->id_material;
          $nama = $row->nama;
          $jenis_material = $row->jenis_material;
          $satuan = $row->satuan;
          $harga = $row->harga;
        endforeach;
      ?>
        <div class="row">
        <?= form_open('material/editmaterialproses') ?>
            
            <input type="hidden" id="id_material" name="id_material" value="<?= $id?>">
                <div class="mb-3">
                    <label for="namamaterial" class="form-label">Nama Material</label>
                    <?php
                        //jika set value namakosan tidak kosong maka isi $nama diganti dengan isian dari user
                        if(strlen(set_value('namamaterial'))>0){
                          $nama = set_value('namamaterial');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="namamaterial" name="namamaterial" value="<?= $nama?>" placeholder="Diisi dengan nama material">
                </div>
                <div class="mb-3">
                <label for="jenismaterial" class="form-label">Jenis Material</label>
                    <select class="form-select" aria-label="Default select example" name="jenismaterial">
                        <?php
                            //jika set value jeniskosan tidak kosong maka isi $jenis_kos diganti dengan isian dari user
                            if(strlen(set_value('jenismaterial'))>0){
                              $jenis_material = set_value('jenismaterial');
                            }
                              echo set_value('jenis_material');
                            $alt=""; $bhn = "";
                            if($jenis_material=='Alat'){$alt="selected";}
                            else{$bhn="selected";}
                        ?>
                        <option value="Alat" <?= $alt ?>>Alat</option>
                        <option value="Bahan" <?= $bhn ?>>Bahan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="satuan">Satuan</label>
                    <?php
                        //jika set value alamat tidak kosong maka isi $alamat diganti dengan isian dari user
                        if(strlen(set_value('satuan'))>0){
                          $satuan = set_value('satuan');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="<?= $satuan?>" placeholder="Diisi dengan Satuan">
                </div>
                <div class="mb-3">
                    <label for="harga">Harga</label> 
                    <?php
                        //jika set value telepon tidak kosong maka isi $telepon diganti dengan isian dari user
                        if(strlen(set_value('harga'))>0){
                          $harga = set_value('harga');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="harga" name="harga" value="<?= $harga?>" placeholder="Diisi dengan Harga">
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
