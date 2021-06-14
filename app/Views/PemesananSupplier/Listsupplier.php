<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List Supplier</h1>
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

     <div class="container">
        <table class="table table-responsive">
            <thead>
                <th>ID PESANAN</th>
                <th>ID SUPPLIER</th>
                <th>TANGGAL PESAN</th>
                <th>TANGGAL AMBIL</th>
                <th>JUMLAH UNIT</th>
                <th>HARGA</th>
                <th>STATUS BAYAR</th>      

                <tbody>
                <?php foreach($supplier as $row) {?>
                    <tr>
                       <td><?= $row['id_pesan']?></td>
                        <td><?= $row['id_supplier']?></td>
                        <td><?= $row['tgl_pesan']?></td>
                        <td><?= $row['tgl_ambil']?></td>
                        <td><?= $row['jumlah_unit']?></td>
                        <td><?= rupiah($row['total_harga'])?></td>
                        <td><?= $row['status_bayar']?></td>
                    </tr>

                    <?php } ?>
                </tbody>
            </thead>
        </table>
     </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?= base_url('dashboard/dashboard.js') ?>"></script>

  
  </body>
</html>