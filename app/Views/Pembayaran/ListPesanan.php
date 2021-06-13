<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Daftar Pesanan <?= ucfirst($nama)?></h1>
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
    <p>
    
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Pesanan Renovasi</h1>
      </div>
    <div class="table-responsive">
      <table id="example" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ID Pesanan</th>
              <th>Tanggal Pesan</th>
              <th>Tanggal Renovasi</th>
              <th>Jenis Renovasi</th>
              <th>Harga Deal</th>
              <th>Status Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
                <?php
                    foreach($renovasi as $row):
                        ?>
                            <tr>
                                <td><?= $row->id_pesan;?></td>
                                <td><?= $row->tgl_pesan;?></td>
                                <td><?= $row->tgl_renovasi?></td>
                                <td><?= $row->jenis_renovasi?></td>
                                <td><?= rupiah($row->harga_deal)?></td>
                                <td><?= $row->status_bayar?></td>
                                <td>
                                  <a href="<?= base_url('Pembayaran/ListPembayaran/'.$row->id_pesan.'/Renovasi') ?>" class="btn btn-primary" target="_blank">
                                    <span data-feather="info"></span> Lihat Detail
                                  </a>
                                </td>
                            </tr>
                        <?php
                    endforeach;    
                ?>
          </tbody>
        </table>

    <?php if(!empty($jasadesain)){ ?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Pesanan Jasa Desain</h1>
      </div>
    <div class="table-responsive">
      <table id="example" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ID Pesanan</th>
              <th>Tanggal Pesan</th>
              <th>Tanggal Desain</th>
              <th>ID Jasa Desain</th>
              <th>Harga Deal</th>
              <th>Status Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
                <?php
                    foreach($jasadesain as $row):
                        ?>
                            <tr>
                                <td><?= $row->id_pesan;?></td>
                                <td><?= $row->tgl_pesan;?></td>
                                <td><?= $row->tgl_desain?></td>
                                <td><?= $row->id_jasa_desain;?></td>
                                <td><?= rupiah($row->harga_deal)?></td>
                                <td><?= $row->status_bayar?></td>
                                <td>
                                  <a href="<?= base_url('Pembayaran/ListPembayaran/'.$row->id_renovasi.'/JasaDesain/'.$row->id_pesan) ?>" class="btn btn-primary" target="_blank">
                                    <span data-feather="info"></span> Lihat Detail
                                  </a>
                                </td>
                            </tr>
                        <?php
                    endforeach;    
                ?>
          </tbody>
        </table>
    <?php } ?>
    
    <?php if(!empty($pegawai)){ ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Pesanan Pegawai</h1>
      </div>
    <div class="table-responsive">
      <table id="example" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ID Pesanan</th>
              <th>Tanggal Pesan</th>
              <th>Tanggal Kerja</th>
              <th>ID Pegawai</th>
              <th>Total Gaji</th>
              <th>Status Gaji</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
                <?php
                    foreach($pegawai as $row):
                        ?>
                            <tr>
                                <td><?= $row->id_pesan;?></td>
                                <td><?= $row->tanggal_pesan;?></td>
                                <td><?= $row->tanggal_kerja?></td>
                                <td><?= $row->id_pegawai;?></td>
                                <td><?= rupiah($row->total_gaji)?></td>
                                <td><?= $row->status_gaji?></td>
                                <td>
                                  <a href="<?= base_url('Pembayaran/ListPembayaran/'.$row->id_renovasi.'/Pegawai/'.$row->id_pesan) ?>" class="btn btn-primary" target="_blank">
                                    <span data-feather="info"></span> Lihat Detail
                                  </a>
                                </td>
                            </tr>
                        <?php
                    endforeach;    
                ?>
          </tbody>
        </table>
    <?php } ?>
    
    <?php if(!empty($material)){ ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Pesanan Material</h1>
      </div>
    <div class="table-responsive">
      <table id="example" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ID Pesanan</th>
              <th>Tanggal Pesan</th>
              <th>Tanggal Ambil</th>
              <th>ID Material</th>
              <th>Total Harga</th>
              <th>Status Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
                <?php
                    foreach($material as $row):
                        ?>
                            <tr>
                                <td><?= $row->id_pesan;?></td>
                                <td><?= $row->tanggal_pesan;?></td>
                                <td><?= $row->tanggal_ambil?></td>
                                <td><?= $row->id_material;?></td>
                                <td><?= rupiah($row->total_trans)?></td>
                                <td><?= $row->status_bayar?></td>
                                <td>
                                  <a href="<?= base_url('Pembayaran/ListPembayaran/'.$row->id_renovasi.'/Material/'.$row->id_pesan) ?>" class="btn btn-primary" target="_blank">
                                    <span data-feather="info"></span> Lihat Detail
                                  </a>
                                </td>
                            </tr>
                        <?php
                    endforeach;    
                ?>
          </tbody>
        </table>
    <?php } ?>

    </main>
  </div>
</div>


    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?= base_url('dashboard/dashboard.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>
  </body>
</html>
