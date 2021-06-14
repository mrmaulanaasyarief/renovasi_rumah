<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>


          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="database"></span>
            Master Data
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="<?= base_url('JasaDesain/ListJasaDesain') ?>">Jasa Desain</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Customer/ListCustomer') ?>">Customer</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Pegawai/ListPegawai') ?>">Pegawai</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Material/DaftarMaterial') ?>">Material</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Supplier/ListSupplier') ?>">Supplier</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="shopping-cart"></span>
            Transaksi
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="<?= base_url('PemesananRenovasi') ?>">Pemesanan Renovasi</a></li>
              <li><a class="dropdown-item" href="<?= base_url('PemesananSupplier') ?>">Pemesanan Supplier</a></li>
              <!--<li><a class="dropdown-item" href="<?= base_url('PemesananJasaDesain') ?>">Pemesanan Jasa Desain</a></li>
              <li><a class="dropdown-item" href="<?= base_url('PemesananPegawai') ?>">Pemesanan Pegawai</a></li>
              <li><a class="dropdown-item" href="<?= base_url('PemesananMaterial') ?>">Pemesanan Material</a></li>-->
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= base_url('Pembayaran') ?>">Pembayaran</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Pembayaran/Supplier') ?>">Pembayaran Supplier</a></li>
              <!-- <li><a class="dropdown-item" href="<?= base_url('#') ?>">Perubahan Status</a></li> -->
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="clipboard"></span>
            Laporan
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="<?= base_url('laporan/bukubesar') ?>">Buku Besar</a></li>
              <li><a class="dropdown-item" href="<?= base_url('laporan/jurnalumum') ?>">Jurnal Umum</a></li>
              <li><a class="dropdown-item" href="<?= base_url('laporan/tabelpembayaran') ?>">Kuitansi</a></li>
              <!-- <li><a class="dropdown-item" href="<?= base_url('laporan/labarugi') ?>">Laba Rugi</a></li>
              <li><a class="dropdown-item" href="<?= base_url('laporan/daftarkosan') ?>">Pembayaran</a></li>
              <li><a class="dropdown-item" href="<?= base_url('pemodalan/listpemodalan') ?>">Pemodalan</a></li>
              <li><a class="dropdown-item" href="<?= base_url('laporan/lihatbeban') ?>">Pembebanan</a></li> -->
            </ul>
        </li>

        </ul>
      </div>
    </nav>