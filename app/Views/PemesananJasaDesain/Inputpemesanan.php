<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Pemesanan Desain </h1>
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
        <?= form_open('pemesananJasaDesain/prosesInput') ?>
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="id_renovasi" name="id_renovasi" value="<?= $id_renovasi?>">
                </div>
                <div class="mb-3">
                <label for="id_jasa_desain" class="form-label">Pilih Jenis Jasa</label>
                    <select class="form-select" aria-label="Default select example" id="id_jasa_desain" name="id_jasa_desain">
                        <?php
                            //looping penghuni
                            foreach($jasa_desain as $row):
                                $id_jasa_desain = $row->id_jasa_desain;
                               
                                
                              

                                $jenis_jasa_desain = $row->jenis_jasa_desain;
                                $tipe_desain = $row->tipe_desain;
                                if(set_value('id_jasa_desain')==$id_jasa_desain){
                                  ?>
                                    <option value="<?= $id_jasa_desain ?>" selected><?= $jenis_jasa_desain.' ('.$tipe_desain.')'?></option>
                                  <?php
                                }else{
                                  ?>
                                    <option value="<?= $id_jasa_desain ?>"><?= $jenis_jasa_desain.' ('.$tipe_desain.')' ?></option>
                                  <?php
                                }
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tgl_pesan">Tanggal Pesan Desain</label>
                    <input type="date" class="form-control" id="tgl_pesan" name="tgl_pesan" value="<?= set_value('tgl_pesan')?>" placeholder="Diisi dengan tanggal" onchange="myFunction()">
                </div>
               
                <div class="mb-3">
                    <label for="tgl_desain">Tanggal Mulai Desain</label>
                    <input type="date" class="form-control" id="tgl_desain" name="tgl_desain" value="<?= set_value('tgl_desain')?>" placeholder="Diisi dengan tanggal" onchange="myFunction()">
                </div>
                <div class="mb-3">
                    <label for="harga_awal">Harga Awal</label>
                    <input type="text" class="form-control" id="harga_awal" name="harga_awal" onchange="myFunction()" placeholder="Diisi harga kesepakatan">
                </div>
                <div class="mb-3">
                    <label for="harga_deal">Harga Jadi</label>
                    <input type="text" class="form-control" id="harga_deal" name="harga_deal" onchange="myFunction()" placeholder="Diisi harga kesepakatan">
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
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
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
    var tgl_selesai = document.getElementById("tgl_selesai"); 
    var tgl_awal = document.getElementById("tgl_mulai"); 
    var tgl_selesai2 = document.getElementById("tgl_selesai2");
    var harga_awal = document.getElementById("harga_awal"); //mengambil elemen harga_awal

    //menghilangkan karakter selian numerik
    //harga_awal_bersih =harga_awal.value.replaceAll(".", "");

    var pembagi = 1;
    if(Number(durasi)==6){
      pembagi = 2; //harga per tahun dibagi 2
    }else if(Number(durasi)==1){
      pembagi = 12; //harga per tahun dibagi 12
    }

    //membagi harga dasar dengan 
    harga_awal.setAttribute("value", number_format(Math.round(harga_awal_bersih/pembagi)) );
    harga_awal.innerHTML = harga_awal_bersih/pembagi;

    //menambahkan nilai penambahan tanggal awal dengan durasi yang dipilih lalu diisikan sbg atribut value di input form tgl_selesai
    var dt = new Date(tgl_awal.value); 
    dt.setMonth( dt.getMonth() + Number(durasi) );
    tgl_selesai.setAttribute("value", dt.toISOString().substring(0, 10)); 
    tgl_selesai2.setAttribute("value", dt.toISOString().substring(0, 10)); 



    //mengambil substring dari hasil toISOString berupa string 2021-09-05T00:00:00.000Z

    //var idx = document.getElementById("idx");
    //idx.innerHTML = dt.toISOString();
    //idx.innerHTML = durasi;
  }
</script>
  
  </body>
</html>