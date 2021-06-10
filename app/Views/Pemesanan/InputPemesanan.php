<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Pemesanan Supplier: <p>Nama Supplier: <?= $nama;?></p> <p>Material: <?= $jenis_material;?></p></h1>
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
        
                <div class="mb-3">
                    <label for="tanggal_pesan">Tanggal Pesan</label>
                    <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?= set_value('tanggal_pesan')?>" placeholder="Diisi dengan tanggal pesan" onchange="myFunction()">
                </div>
                <div class="mb-3">
                    
                    <input type="hidden" class="form-control"  name="id_supplier" value="<?= $id_supplier?>" >
                </div>
                <div class="mb-3">
                    <label for="tanggal_ambil">Tanggal Ambil</label>
                    <input type="date" class="form-control" id="tanggal_ambil" name="tanggal_ambil" value="<?= set_value('tanggal_ambil')?>" placeholder="Diisi dengan tanggal ambil" onchange="myFunction()">
                    <div id="idx"></div>
                </div>
                <div class="mb-3">
                    <label for="harga">Harga</label>
                    <input type="text" class="form-control" id="harga" name="harga" value="<?= $harga_material ?>" placeholder="Diisi dengan harga ">
                </div>
                <div class="mb-3">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" onchange="changeTotal1()" placeholder="Diisi dengan harga ">
                </div>
                <div class="mb-3">
                    <label for="diskon">Diskon</label>
                    <input type="text" class="form-control" id="diskon" name="diskon" onchange="changeTotal2()" placeholder="Diisi harga total diskon">
                </div>
                <div class="mb-3">
                    <label for="ongkir">Ongkos Kirim</label>
                    <input type="text" class="form-control" id="ongkir" name="ongkir" onchange="changeTotal3()" placeholder="Diisi dengan ongkos kirim">
                </div>
                <div class="mb-3">
                    <label for="total_jual">Total Jual</label>
                    <input type="text" class="form-control" id="total" name="total" disabled>
                </div>
                <div class="mb-3">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control"  name="alamat_kirim" placeholder="Diisi dengan alamat ">
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
      $('#harga_deal').mask('0,000,000,000,000,000', {reverse: true});	
			
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

      function changeTotal1(){
    //memilih elemen 
    var total = document.getElementById("total");
    var jumlah = document.getElementById("jumlah").value;
    var harga = document.getElementById("harga").value;
    total.value = jumlah * harga

  }

  function changeTotal2(){
    //memilih elemen 
    var total = document.getElementById("total");
    var diskon = document.getElementById("diskon").value;


    total.value = total.value - diskon

  }

  function changeTotal3(){
    //memilih elemen 
    //memilih elemen 
    var total = +document.getElementById("total").value;
    var ongkir = +document.getElementById("ongkir").value;

    var container = document.getElementById("total");
    container.value = total + ongkir;

    

  }
</script>
  
  </body>
</html>

