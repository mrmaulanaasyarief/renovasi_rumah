<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Pemesanan Material </h1>
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
        
      ?>
        <div class="row">
        <?= form_open('pemesanan/prosesInput') ?>
        <input type="hidden" id="nama" name="nama" value="<?= $nama?>">
                <div class="mb-3">
                <label for="id_material" class="form-label">Pilih Material</label>
                    <select class="form-select" aria-label="Default select example" id="id_material" name="id_material">
                        <?php
                            //looping penghuni
                            foreach($material as $row):
                                $id = $row->id_material;
                                $nama = $row->nama;
                                $satuan = $row->satuan;
                                if(set_value('id_material')==$id){
                                  ?>
                                    <option value="<?= $id ?>" selected><?= $nama.' ('.$satuan.')'?></option>
                                  <?php
                                }else{
                                  ?>
                                    <option value="<?= $id ?>"><?= $nama.' ('.$satuan.')' ?></option>
                                  <?php
                                }
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tanggal_pesan">Tanggal Pesan Material</label>
                    <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?= set_value('tanggal_pesan')?>" placeholder="Diisi dengan tanggal" onchange="myFunction()">
                </div>
                <div class="mb-3">
                    <label for="tanggal_ambil">Tanggal Ambil Material</label>
                    <input type="date" class="form-control" id="tanggal_ambil" name="tanggal_ambil" value="<?= set_value('tanggal_ambil')?>" placeholder="Diisi dengan tanggal" onchange="myFunction()">
                </div>
                <div class="mb-3">
                    <label for="harga_awal">Harga Awal</label>
                    <input type="text" class="form-control" id="harga_awal" name="harga_awal" value="<?= number_format($harga)?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="total_trans">Harga Jadi</label>
                    <input type="text" class="form-control" id="total_trans" name="total_trans" value="<?= set_value('total_trans')?>" onchange="myFunction()" placeholder="Diisi harga kesepakatan">
                </div>
                <div class="mb-3">
                    <label for="besar_bayar">Pembayaran DP/Pelunasan</label>
                    <input type="text" class="form-control" id="besar_bayar" name="besar_bayar" value="<?= set_value('besar_bayar')?>" onchange="myFunction()" placeholder="Diisi dengan besar pembayaran">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </main>
  </div>
</div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?= base_url('dashboard/dashboard.js') ?>"></script>
  
    <script>
		$(document).ready(function(){
			// Format mata uang.
			$('#besar_bayar').mask('0,000,000,000,000,000', {reverse: true});		
      $('#total_trans').mask('0,000,000,000,000,000', {reverse: true});	
			
		})
	 </script> 



<script>
      //untuk fungsi number format di javascript
      function number_format (number, decimals, decPoint, thousandsSep) { 
          number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
          var n = !isFinite(+number) ? 0 : +number
          var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
          var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
          var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
          var s = ''
          helper('rupiah');

          var toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec)
            return '' + (Math.round(n * k) / k)
              .toFixed(prec)
          }

          // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
          s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
          if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
          }
          if ((s[1] || '').length < prec) {
            s[1] = s[1] || ''
            s[1] += new Array(prec - s[1].length + 1).join('0')
          }

          return s.join(dec)
      }

  //fungsi untuk mengganti nilai sesuai dengan pilihan user
  function myFunction(){
    //memilih elemen  
    var tanggal_pesan = document.getElementById("tanggal_pesan"); 

    //menghilangkan karakter selian numerik
    //harga_awal_bersih =harga_awal.value.replaceAll(".", "");
    //mengambil substring dari hasil toISOString berupa string 2021-09-05T00:00:00.000Z

    //var idx = document.getElementById("idx");
    //idx.innerHTML = dt.toISOString();
    //idx.innerHTML = durasi;
  }
</script>
  
  </body>
</html>