<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Jasa Desain</h1>
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
        if(isset($validation)){
          echo $validation->listErrors();
        }

        //dapatkan data dari jasa desain  dan simpan ke variabel lokal
        foreach($jasadesain as $row):
          $id = $row->id_jasa_desain;
          $jenis_jasa_desain = $row->jenis_jasa_desain;
          $tipe_desain = $row->tipe_desain;
        endforeach;
      ?>
        <div class="row">
        <?= form_open('jasadesain/editjasadesainproses') ?>
            
            <input type="hidden" id="id_jasa_desain" name="id_jasa_desain" value="<?= $id?>">
                <div class="mb-3">
                    <label for="jenis_jasa_desain" class="form-label">Jenis jasa desain </label>
                    <?php
                        //jika set value jenis jasa desain tidak kosong maka isi $jenis_jasa_desain diganti dengan isian dari user
                        if(strlen(set_value('jenis_jasa_desain'))>0){
                          $jenis_jasa_desain= set_value('jenis_jasa_desain');
                        }
                
                    ?>
                    <input type="text" class="form-control" id="jenis_jasa_desain" name="jenis_jasa_desain" value="<?= $nama?>" placeholder="Diisi dengan jenis desain">
                </div>
                <div class="mb-3">
                <label for="tipe_desain" class="form-label">Tipe desain</label>
                    <select class="form-select" aria-label="Default select example" name="tipe_desain">
                        <?php
                            //jika set value tipe desain tidak kosong maka isi $tipe_desain diganti dengan isian dari user
                            if(strlen(set_value('tipe_desain'))>0){
                              $tipe_desain = set_value('tipe_desain');
                            }
                              echo set_value('tipe_desain');
                            $lk=""; $pr = ""; $cm = "";
                            if($tipe_desain=='Vintage'){$lk="selected";}
                            elseif($tipe_desain=='Bohemian'){$pr="selected";}
                            elseif($tipe_desain=='Industrial'){$pr="selected";}
                            else{$cm="selected";}
                        ?>
                        <option value="Vintage" <?= $lk ?>>Vintage</option>
                        <option value="Bohemian" <?= $pr ?>>Bohemian</option>
                        <option value="Industrial" <?= $cm ?>>Industrial</option>
                    </select>
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
