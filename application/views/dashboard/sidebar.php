<body>

<script src="<?= base_url('assets/assets/static/js/initTheme.js') ?>"></script>

<div id="app">

    <div id="sidebar">

        <div class="sidebar-wrapper active">

            <div class="sidebar-header position-relative">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="sidebar-toggler x">
                        <a href="#" class="sidebar-hide d-xl-none d-block">
                            <i class="bi bi-x bi-middle"></i>
                        </a>
                    </div>

                </div>

            </div>


            <div class="sidebar-menu">

                <ul class="menu">

                    <li class="sidebar-title">Menu</li>


                    <!-- DASHBOARD -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == '' || $this->uri->uri_string() == 'dashboard') 
                        ? 'active' : '' ?>">

                        <a href="<?= base_url('dashboard') ?>" class="sidebar-link">

                            <i class="bi bi-grid-fill"></i>

                            <span>Dashboard</span>

                        </a>

                    </li>


                    <!-- KASIR -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == 'transaksi') 
                        ? 'active' : '' ?>">

                        <a href="<?= base_url('transaksi') ?>" class="sidebar-link">

                            <i class="bi bi-cash-coin"></i>

                            <span>Kasir</span>

                        </a>

                    </li>


                    <!-- RIWAYAT TRANSAKSI -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == 'transaksi/riwayat') 
                        ? 'active' : '' ?>">

                        <a href="<?= base_url('transaksi/riwayat') ?>" class="sidebar-link">

                            <i class="bi bi-clock-history"></i>

                            <span>Riwayat Transaksi</span>

                        </a>

                    </li>


                    <!-- INVENTORY -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == 'barang') 
                        ? 'active' : '' ?>">

                        <a href="<?= base_url('barang') ?>" class="sidebar-link">

                            <i class="bi bi-box-seam-fill"></i>

                            <span>Inventory</span>

                        </a>

                    </li>


                    <!-- SUPPLIER -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == 'supplier') 
                        ? 'active' : '' ?>">

                        <a href="<?= site_url('supplier') ?>" class="sidebar-link">

                            <i class="bi bi-truck"></i>

                            <span>Supplier</span>

                        </a>

                    </li>

                    <!-- SUPPLIER -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == 'laporan') 
                        ? 'active' : '' ?>">

                        <a href="<?= site_url('laporan') ?>" class="sidebar-link">

                            <i class="bi bi-bar-chart-line"></i>

                            <span>Laporan</span>

                        </a>

                    </li>


                    <!-- PEMBELIAN -->
                    <li class="sidebar-item <?= 
                        ($this->uri->uri_string() == 'pembelian') 
                        ? 'active' : '' ?>">

                        <a href="<?= site_url('pembelian') ?>" class="sidebar-link">

                            <i class="bi bi-bag-plus"></i>

                            <span>Pembelian</span>

                        </a>

                    </li>


                </ul>

            </div>

        </div>

    </div>