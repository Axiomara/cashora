<body>

<div id="app">

    <!-- ========================= -->
    <!-- BAGIAN SIDEBAR            -->
    <!-- ========================= -->
    <div id="sidebar">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Logo / Judul bisa diletakkan di sini -->
                    
                    <!-- Tombol Close Sidebar (Mobile) -->
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
                    <li class="sidebar-item <?= ($this->uri->uri_string() == '' || $this->uri->uri_string() == 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard') ?>" class="sidebar-link">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- KASIR -->
                    <li class="sidebar-item <?= ($this->uri->uri_string() == 'transaksi') ? 'active' : '' ?>">
                        <a href="<?= base_url('transaksi') ?>" class="sidebar-link">
                            <i class="bi bi-cash-coin"></i>
                            <span>Kasir</span>
                        </a>
                    </li>

                    <!-- RIWAYAT TRANSAKSI -->
                    <li class="sidebar-item <?= ($this->uri->uri_string() == 'transaksi/riwayat') ? 'active' : '' ?>">
                        <a href="<?= base_url('transaksi/riwayat') ?>" class="sidebar-link">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Transaksi</span>
                        </a>
                    </li>

                    <!-- INVENTORY -->
                    <li class="sidebar-item <?= ($this->uri->uri_string() == 'barang') ? 'active' : '' ?>">
                        <a href="<?= base_url('barang') ?>" class="sidebar-link">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>Inventory</span>
                        </a>
                    </li>

                    <!-- SUPPLIER -->
                    <li class="sidebar-item <?= ($this->uri->uri_string() == 'supplier') ? 'active' : '' ?>">
                        <a href="<?= site_url('supplier') ?>" class="sidebar-link">
                            <i class="bi bi-truck"></i>
                            <span>Supplier</span>
                        </a>
                    </li>

                    <!-- PEMBELIAN -->
                    <li class="sidebar-item <?= ($this->uri->uri_string() == 'pembelian') ? 'active' : '' ?>">
                        <a href="<?= site_url('pembelian') ?>" class="sidebar-link">
                            <i class="bi bi-bag-plus"></i>
                            <span>Pembelian</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <!-- ========================= -->
    <!-- BAGIAN MAIN KONTEN        -->
    <!-- ========================= -->
    <div id="main" class="p-3 p-md-4">

        <!-- MOBILE SIDEBAR BUTTON -->
        <header class="mb-3 d-xl-none d-flex align-items-center">
            <!-- Class "burger-btn" di bawah ini otomatis dideteksi oleh JS template untuk membuka sidebar -->
            <a href="#" class="burger-btn text-dark text-decoration-none d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <span class="ms-2 fw-semibold text-muted">Menu</span>
        </header>

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Dashboard Laporan</h4>
                <small class="text-muted">Analisis bisnis secara realtime</small>
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="row g-3 mb-4">
            <!-- Tambahan col-6 agar di HP kotak menjadi 2 kolom rapi -->
            <div class="col-md-3 col-6">
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4 h-100">
                    <small class="text-muted">Pembelian</small>
                    <h5 class="fw-bold mt-2 text-danger text-break">
                        Rp<?= number_format($total['pembelian'],0,',','.') ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4 h-100">
                    <small class="text-muted">Penjualan</small>
                    <h5 class="fw-bold mt-2 text-primary text-break">
                        Rp<?= number_format($total['penjualan'],0,',','.') ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4 h-100">
                    <small class="text-muted">Laba Real</small>
                    <h5 class="fw-bold mt-2 text-success text-break">
                        Rp<?= number_format($laba_real,0,',','.') ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="bg-white border rounded-4 shadow-sm p-3 p-md-4 h-100">
                    <small class="text-muted">Transaksi</small>
                    <h5 class="fw-bold mt-2 text-break">
                        <?= $jumlah_transaksi ?>
                    </h5>
                </div>
            </div>
        </div>

        <!-- ANALYTICS -->
        <div class="row g-3 mb-4">

            <!-- BARANG TERLARIS -->
            <div class="col-md-6">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
                    <h6 class="fw-semibold mb-3">🔥 Barang Terlaris</h6>

                    <?php foreach($barang_terlaris as $b): ?>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span><?= $b->nama_barang ?></span>
                            <span class="fw-semibold"><?= $b->total_terjual ?> pcs</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- STOK MENIPIS -->
            <div class="col-md-6">
                <div class="bg-white border rounded-4 shadow-sm p-4 h-100">
                    <h6 class="fw-semibold mb-3 text-danger">🚨 Stok Menipis</h6>

                    <?php if (!empty($stok_menipis)) : ?>
                        <?php foreach($stok_menipis as $s): ?>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span><?= $s->nama_barang ?></span>
                                <span class="badge bg-danger"><?= $s->stok ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-success py-2 mt-3">
                            <small class="mb-0">Semua stok aman terkendali.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- LABA PER BARANG -->
        <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
            <h6 class="fw-semibold mb-3">💰 Laba per Barang</h6>

            <div class="table-responsive">
                <table class="table align-middle text-nowrap">
                    <thead class="small text-muted">
                        <tr>
                            <th>Barang</th>
                            <th>Terjual</th>
                            <th>Penjualan</th>
                            <th>Laba</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($laba_per_barang as $l): ?>
                        <tr>
                            <td><?= $l->nama_barang ?></td>
                            <td><?= $l->total_terjual ?></td>
                            <td>Rp<?= number_format($l->total_penjualan,0,',','.') ?></td>
                            <td class="text-success fw-semibold">
                                Rp<?= number_format($l->laba,0,',','.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SUPPLIER -->
        <div class="bg-white border rounded-4 shadow-sm p-4 mb-4">
            <h6 class="fw-semibold mb-3">📦 Supplier</h6>

            <?php foreach($supplier as $s): ?>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <span><?= $s->nama_supplier ?></span>
                    <span class="fw-semibold text-primary">
                        Rp<?= number_format($s->total_pembelian,0,',','.') ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- FILTER + TABLE -->
        <div class="bg-white border rounded-4 shadow-sm overflow-hidden mb-4">

            <div class="px-3 py-3 px-md-4 py-md-4 border-bottom bg-light">
                <form method="get" class="d-flex flex-wrap gap-2">
                    <input type="date" name="dari" value="<?= $dari ?>" class="form-control rounded-pill" style="max-width: 180px;">
                    <input type="date" name="sampai" value="<?= $sampai ?>" class="form-control rounded-pill" style="max-width: 180px;">
                    <button class="btn btn-dark rounded-pill px-4">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 text-nowrap">

                    <thead class="bg-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($pembelian as $p): ?>
                        <tr>
                            <td><?= $p->kode_pembelian ?></td>
                            <td><span class="badge bg-primary">Pembelian</span></td>
                            <td><?= date('d M Y H:i', strtotime($p->tanggal)) ?></td>
                            <td class="text-end">Rp<?= number_format($p->total,0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>

                        <?php foreach ($penjualan as $p): ?>
                        <tr>
                            <td><?= $p->kode_transaksi ?></td>
                            <td><span class="badge bg-success">Penjualan</span></td>
                            <td><?= date('d M Y H:i', strtotime($p->tanggal)) ?></td>
                            <td class="text-end">Rp<?= number_format($p->total,0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div>

        </div>

    </div>
    <!-- END MAIN KONTEN -->

</div>
<!-- END APP -->

</body>