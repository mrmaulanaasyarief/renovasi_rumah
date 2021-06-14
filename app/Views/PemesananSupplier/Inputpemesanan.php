<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Pemesanan Supplier </h1>
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
          <?= form_open('PemesananSupplier/prosesInput') ?>

            <input type="hidden" class="form-control" id="id_supplier" name="id_supplier" value="<?= $id_supplier?>">
            <input type="hidden" class="form-control" id="harga_" name="harga_" value="<?= $harga_material?>">
            
            <div class="mb-3">
              <label for="nama_supplier" class="form-label">Supplier</label>
                <select class="form-select" aria-label="Default select example" id="nama_supplier" name="nama_supplier">
                    <option value="<?= $nama_supplier ?>" selected><?= ucfirst($nama_supplier) ?></option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jenis_material" class="form-label">Jenis Material</label>
                <select class="form-select" aria-label="Default select example" id="jenis_material" name="jenis_material">
                    <option value="<?= $jenis_material ?>" selected><?= ucfirst($jenis_material) ?></option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tanggal_pesan">Tanggal Pesan Material</label>
                <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?= set_value('tanggal_pesan')?>" placeholder="Diisi dengan tanggal">
            </div>
            <div class="mb-3">
                <label for="tanggal_ambil">Tanggal Ambil Material</label>
                <input type="date" class="form-control" id="tanggal_ambil" name="tanggal_ambil" value="<?= set_value('tanggal_ambil')?>" placeholder="Diisi dengan tanggal">
            </div>
            <div class="mb-3">
                <label for="harga_material">Harga Material</label>
                <input type="text" class="form-control" id="harga_material" name="harga_material" value="<?= rupiah($harga_material)?>" disabled>
            </div>
            <div class="mb-3">
                <label for="jumlah_unit">Jumlah Unit</label>
                <input type="text" class="form-control" id="jumlah_unit" name="jumlah_unit" onchange="changeTotal()" value="<?= set_value('jumlah_unit')?>" placeholder="Diisi jumlah unit yang dipesan">
            </div>
            <div class="mb-3">
                <label for="total_harga">Total Harga</label>
                <input type="text" class="form-control" id="total_harga" name="total_harga"  value="<?= set_value('total_harga')?>" disabled>
            </div>
            <div class="mb-3">
                <label for="besar_bayar">Pembayaran DP/Pelunasan</label>
                <input type="text" class="form-control" id="besar_bayar" name="besar_bayar" value="<?= set_value('besar_bayar')?>" placeholder="Diisi dengan besar pembayaran">
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
  
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
		$(document).ready(function(){
			// Format mata uang.
			$('#total_harga').mask('Rp 0,000,000,000,000,000', {reverse: true});		
			$('#besar_bayar').mask('0,000,000,000,000,000', {reverse: true});		
			
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

  
  function changeTotal(){
    //memilih elemen 
    //memilih elemen 
    var harga = +document.getElementById("harga_").value;
    var unit = +document.getElementById("jumlah_unit").value;

    var container = document.getElementById("total_harga");
    container.value = harga * unit;

  }
</script>
  
  </body>
</html>