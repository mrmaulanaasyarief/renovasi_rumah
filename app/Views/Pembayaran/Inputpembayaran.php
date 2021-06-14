<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Pembayaran</h1>
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
        <?= form_open('Pembayaran/ProsesPembayaran') ?>
        <input type="hidden" id="id_renov" name="id_renov" value="<?= $id_renov?>">
        <input type="hidden" id="id_pemesanan" name="id_pemesanan" value="<?= $id_pemesanan?>">
        <input type="hidden" id="no_kuitansi" name="no_kuitansi" value="<?= $no_kuitansi?>">
        <input type="hidden" id="jenis_pemesanan" name="jenis_pemesanan" value="<?= $jenis_pemesanan?>">
        <input type="hidden" id="harga_deal" name="harga_deal" value="<?= $harga_deal?>">
        <input type="hidden" id="harga_pesanan" name="harga_pesanan" value="<?= $harga_deal?>">
        <input type="hidden" id="totalbayar" name="totalbayar" value="<?= $totalbayar?>">
        <input type="hidden" id="sisa_bayar" name="sisa_bayar" value="<?= $sisa_bayar?>">
        <input type="hidden" id="tgl_bayar" name="tgl_bayar" value="<?= date("m-d-Y")?>">
        
                <div class="mb-3">
                    <label for="customer" class="form-label">Nama <?php if($jenis_pemesanan == "Supplier"){echo 'Supplier';}else{echo 'Customer';} ?></label>
                    <input type="text" class="form-control" id="customer" name="customer" value="<?= $nama_customer?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="jenis_pemesanan_info" class="form-label">Jenis Pesanan</label>
                    <input type="text" class="form-control" id="jenis_pemesanan_info" name="jenis_pemesanan_info" value="<?= $jenis_pemesanan?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="harga_pesanan_info" class="form-label">Harga Pesanan</label>
                    <input type="text" class="form-control" id="harga_pesanan_info" name="harga_pesanan_info" value="<?= rupiah($harga_deal)?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="totalbayar_info" class="form-label">Total Bayar</label>
                    <input type="text" class="form-control" id="totalbayar_info" name="totalbayar_info" value="<?= rupiah($totalbayar)?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="sisa_bayar_info" class="form-label"><b>Sisa Bayar</b></label>
                    <input type="text" class="form-control" id="sisa_bayar_info" name="sisa_bayar_info" value="<?= rupiah($sisa_bayar)?>" disabled >
                </div>
                <div class="mb-3">
                    <label for="besar_bayar">Pembayaran</label>
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
  
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
		$(document).ready(function(){
			// Format mata uang.
			$('#besar_bayar').mask('0,000,000,000,000,000', {reverse: true});		
			
		})
	 </script> 
  
  </body>
</html>
