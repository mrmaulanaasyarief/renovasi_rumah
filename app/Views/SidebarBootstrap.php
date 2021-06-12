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
              <li><a class="dropdown-item" href="<?= base_url('jasadesain/listjasadesain') ?>">Jasa Desain</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Customer/listcustomer') ?>">Customer</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Pegawai/listpegawai') ?>">Pegawai</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Material/Daftarmaterial') ?>">Material</a></li>
              <li><a class="dropdown-item" href="<?= base_url('Supplier/listsupplier') ?>">Supplier</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="shopping-cart"></span>
            Transaksi
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="<?= base_url('PemesananRenovasi') ?>">Pemesanan Renovasi</a></li>
              <!--<li><a class="dropdown-item" href="<?= base_url('PemesananJasaDesain') ?>">Pemesanan Jasa Desain</a></li>
              <li><a class="dropdown-item" href="<?= base_url('PemesananPegawai') ?>">Pemesanan Pegawai</a></li>
              <li><a class="dropdown-item" href="<?= base_url('PemesananMaterial') ?>">Pemesanan Material</a></li>
              <li><a class="dropdown-item" href="<?= base_url('#') ?>">Pembayaran</a></li>-->
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= base_url('#') ?>">Perubahan Status</a></li>
            </ul>
        </li>

          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="shopping-cart"></span>
              Products
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="users"></span>
              Customers
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="bar-chart-2"></span>
              Reports
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="layers"></span>
              Integrations
            </a>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Saved reports</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Current month
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Last quarter
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Social engagement
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Year-end sale
            </a>
          </li>
        </ul>
      </div>
    </nav>