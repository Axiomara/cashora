<div id="main">

    <!-- MOBILE SIDEBAR BUTTON -->
    <header class="mb-3 d-xl-none">
        <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <!-- HEADER / PAGE HEADING -->
    <div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Laporan</h3>
            <p class="text-muted mb-0">Analisis bisnis secara realtime</p>
        </div>
    </div>

    <!-- START PAGE CONTENT -->
    <div class="page-content">

        <!-- SUMMARY -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Pembelian</small>
                        <h4 class="fw-bold mt-2 text-danger text-break mb-0">
                            Rp<?= number_format($total['pembelian'],0,',','.') ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Penjualan</small>
                        <h4 class="fw-bold mt-2 text-primary text-break mb-0">
                            Rp<?= number_format($total['penjualan'],0,',','.') ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Laba Real</small>
                        <h4 class="fw-bold mt-2 text-success text-break mb-0">
                            Rp<?= number_format($laba_real,0,',','.') ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Transaksi</small>
                        <h4 class="fw-bold mt-2 text-dark text-break mb-0">
                            <?= $jumlah_transaksi ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- ANALYTICS -->
        <div class="row g-3 mb-4">

            <!-- BARANG TERLARIS -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom px-4 py-3 rounded-top-4">
                        <h6 class="fw-bold mb-0">🔥 Barang Terlaris</h6>
                    </div>
                    <div class="card-body px-4 py-2">
                        <?php foreach($barang_terlaris as $b): ?>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light">
                                <span class="text-dark fw-medium"><?= $b->nama_barang ?></span>
                                <span class="badge bg-light text-dark px-2 py-1 rounded-pill fw-semibold border"><?= $b->total_terjual ?> pcs</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- STOK MENIPIS -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom px-4 py-3 rounded-top-4">
                        <h6 class="fw-bold mb-0 text-danger">🚨 Stok Menipis</h6>
                    </div>
                    <div class="card-body px-4 py-2">
                        <?php if (!empty($stok_menipis)) : ?>
                            <?php foreach($stok_menipis as $s): ?>
                                <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light">
                                    <span class="text-dark fw-medium"><?= $s->nama_barang ?></span>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill fw-semibold border border-danger border-opacity-25"><?= $s->stok ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 py-4 text-muted border-0">
                                <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                                <small class="fw-medium">Semua stok aman terkendali.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- LABA PER BARANG -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom px-4 py-3 rounded-top-4">
                <h6 class="fw-bold mb-0">💰 Laba per Barang</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                                <th class="ps-4 fw-semibold border-bottom-0">Barang</th>
                                <th class="fw-semibold border-bottom-0">Terjual</th>
                                <th class="fw-semibold border-bottom-0">Penjualan</th>
                                <th class="pe-4 fw-semibold border-bottom-0">Laba</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach($laba_per_barang as $l): ?>
                            <tr>
                                <td class="ps-4 py-3 text-dark fw-medium"><?= $l->nama_barang ?></td>
                                <td class="py-3 text-muted"><?= $l->total_terjual ?> pcs</td>
                                <td class="py-3 text-muted">Rp<?= number_format($l->total_penjualan,0,',','.') ?></td>
                                <td class="pe-4 py-3 text-success fw-bold">
                                    Rp<?= number_format($l->laba,0,',','.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SUPPLIER -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom px-4 py-3 rounded-top-4">
                <h6 class="fw-bold mb-0">📦 Pembelian ke Supplier</h6>
            </div>
            <div class="card-body px-4 py-2">
                <?php foreach($supplier as $s): ?>
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light">
                        <span class="text-dark fw-medium"><?= $s->nama_supplier ?></span>
                        <span class="fw-bold text-primary bg-primary bg-opacity-10 px-3 py-1 rounded-pill small">
                            Rp<?= number_format($s->total_pembelian,0,',','.') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- FILTER + TABLE -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom px-4 py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-bold mb-0">Daftar Transaksi</h6>
                    <form method="get" class="d-flex flex-wrap gap-2">
                        <input type="date" name="dari" value="<?= $dari ?>" class="form-control form-control-sm rounded-pill" style="max-width: 150px; background-color: #f8f9fa;">
                        <input type="date" name="sampai" value="<?= $sampai ?>" class="form-control form-control-sm rounded-pill" style="max-width: 150px; background-color: #f8f9fa;">
                        <button class="btn btn-dark btn-sm rounded-pill px-3">Filter</button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">

                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                                <th class="ps-4 fw-semibold border-bottom-0">Kode</th>
                                <th class="fw-semibold border-bottom-0">Tipe</th>
                                <th class="fw-semibold border-bottom-0">Tanggal</th>
                                <th class="pe-4 text-end fw-semibold border-bottom-0">Total</th>
                            </tr>
                        </thead>

                        <tbody class="border-top-0">

                            <?php foreach ($pembelian as $p): ?>
                            <tr>
                                <td class="ps-4 py-3 fw-medium text-dark"><?= $p->kode_pembelian ?></td>
                                <td class="py-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Pembelian</span>
                                </td>
                                <td class="py-3 text-muted"><?= date('d M Y H:i', strtotime($p->tanggal)) ?></td>
                                <td class="pe-4 text-end py-3 fw-bold text-dark">Rp<?= number_format($p->total,0,',','.') ?></td>
                            </tr>
                            <?php endforeach; ?>

                            <?php foreach ($penjualan as $p): ?>
                            <tr>
                                <td class="ps-4 py-3 fw-medium text-dark"><?= $p->kode_transaksi ?></td>
                                <td class="py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Penjualan</span>
                                </td>
                                <td class="py-3 text-muted"><?= date('d M Y H:i', strtotime($p->tanggal)) ?></td>
                                <td class="pe-4 text-end py-3 fw-bold text-dark">Rp<?= number_format($p->total,0,',','.') ?></td>
                            </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- END PAGE CONTENT -->

</div>
<!-- END MAIN -->