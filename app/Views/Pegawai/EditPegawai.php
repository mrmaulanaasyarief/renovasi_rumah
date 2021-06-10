<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Pegawai</h1>
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
        foreach($pegawai as $row):
          $id = $row->id_pegawai;
          $nama = $row->nama_pegawai;
          $alamat = $row->alamat_pegawai;
          $telp = $row->telp_pegawai;
          $jenis = $row->jenis_pegawai;
        endforeach;
      ?>
        <div class="row">
        <?= form_open('pegawai/editpegawaiproses') ?>
            
            <input type="hidden" id="id_pegawai" name="id_pegawai" value="<?= $id?>">
                <div class="mb-3">
                    <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                    <?php
                        //jika set value namakosan tidak kosong maka isi $nama diganti dengan isian dari user
                        if(strlen(set_value('nama_pegawai'))>0){
                          $nama = set_value('nama_pegawai');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" value="<?= $nama?>" placeholder="Diisi dengan nama pegawai">
                </div>
                <div class="mb-3">
                    <label for="alamat_pegawai">Alamat Pegawai</label>
                    <?php
                        //jika set value alamat tidak kosong maka isi $alamat diganti dengan isian dari user
                        if(strlen(set_value('alamat_pegawai'))>0){
                          $alamat = set_value('alamat_pegawai');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="alamat_pegawai" name="alamat_pegawai" value="<?= $alamat?>" placeholder="Diisi dengan Alamat">
                </div>
                <div class="mb-3">
                    <label for="telp_pegawai">Telepon</label> 
                    <?php
                        //jika set value telepon tidak kosong maka isi $telepon diganti dengan isian dari user
                        if(strlen(set_value('telp_pegawai'))>0){
                          $telp = set_value('telp_pegawai');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="telp_pegawai" name="telp_pegawai" value="<?= $telp?>" placeholder="Diisi dengan Nomor Telepon (081321405677)">
                </div>
                <div class="mb-3">
                    <label for="jenis_pegawai">Jenis Pegaswai</label> 
                    <?php
                        //jika set value telepon tidak kosong maka isi $telepon diganti dengan isian dari user
                        if(strlen(set_value('jenis_pegawai'))>0){
                          $jenis = set_value('jenis_pegawai');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="jenis_pegawai" name="jenis_pegawai" value="<?= $jenis?>" placeholder="Diisi dengan jenis pegawai">
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