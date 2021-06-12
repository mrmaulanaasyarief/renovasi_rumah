<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pesan Pegawai: <?= $nama_pegawai;?></h1>
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
        <?= form_open('PemesananPegawai/prosesInput') ?> 
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="id_renovasi" name="id_renovasi" value="<?= $id_renovasi?>">
                </div>   
                <div class="mb-3">
                    <input type="hidden" class="form-control" name="id_pegawai" value="<?= $id_pegawai ?>" ?>
                </div>
                <div class="mb-3">
                    <label for="tanggal_pesan">Tanggal Pesan Pegawai</label>
                    <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?= set_value('tanggal_pesan')?>" placeholder="Diisi dengan tanggal pemesanan pegawai">
                </div>
                <div class="mb-3">
                    <label for="tanggal_kerja">Tanggal Mulai Kerja</label>
                    <input type="date" class="form-control" id="tanggal_kerja" name="tanggal_kerja" value="<?= set_value('tanggal_kerja')?>" placeholder="Diisi dengan tanggal mulai kerja">
                </div>
                <div class="mb-3">
                    <label for="jumlah_hari">Jumlah Hari</label>
                    <input type="text" class="form-control" id="jumlah_hari" name="jumlah_hari" onchange="changeTotal()" placeholder="Diisi dengan jumlah hari kerja pegawai ">
                </div>
                <div class="mb-3">
                    <label for="gaji">Tarif Pegawai</label>
                    <input type="text" class="form-control" id="gaji" name="gaji" onchange="changeTotal()" placeholder="Diisi dengan tarif kerja pegawai" >
                </div>
                <div class="mb-3">
                    <label for="total_gaji">Total Gaji</label>
                    <input type="text" class="form-control" id="total_gaji" name="total_gaji" disabled>
                </div>
                <div class="mb-3">
                    <label for="total_bayar">Pembayaran Gaji/DP</label>
                    <input type="text" class="form-control" id="total_bayar" name="total_bayar" value="<?= set_value('total_bayar')?>" placeholder="Diisi dengan besar pembayaran gaji">
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
      //untuk fungsi number format di javascript masking
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

  //fungsi untuk mengganti nilai sesuai dengan pilihan user
  function changeTotal(){
    //memilih elemen 
    //memilih elemen 
    var jumlah_hari = +document.getElementById("jumlah_hari").value;
    var gaji = +document.getElementById("gaji").value;

    var container = document.getElementById("total_gaji");
    container.value = jumlah_hari * gaji;

    

  }
</script>
  
  </body>
</html>