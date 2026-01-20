<style>
    .kpi-icon {
  width: 48px;
  height: 48px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.kpi-icon i {
  font-size: 22px;
}

/* Variasi warna background + icon */
.kpi-icon.primary { background: #eef2ff; }
.kpi-icon.primary i { color: #4f46e5; }

.kpi-icon.info { background: #ecfeff; }
.kpi-icon.info i { color: #0891b2; }

.kpi-icon.success { background: #ecfdf5; }
.kpi-icon.success i { color: #16a34a; }

.kpi-icon.warning { background: #fff7ed; }
.kpi-icon.warning i { color: #ea580c; }

</style>

<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <!-- HEADING -->
  <div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-1">Dashboard Kasir</h3>
      <p class="text-muted mb-0">Ringkasan aktivitas kios hari ini</p>
    </div>

    <div class="d-flex gap-2">
      <a href="<?= base_url('transaksi') ?>" class="btn btn-primary">
        <i class="bi bi-cart-check me-1"></i> Transaksi Baru
      </a>
      <a href="<?= base_url('barang') ?>" class="btn btn-light">
        <i class="bi bi-box-seam me-1"></i> Kelola Barang
      </a>
    </div>
  </div>

  <div class="page-content">
    <section class="row g-3">

      <!-- LEFT -->
      <div class="col-12 col-lg-9">

        <!-- SUMMARY -->
        <div class="row g-3 mb-1">

          <!-- Omzet -->
         <div class="col-12 col-sm-6 col-lg-3">
  <div class="card shadow-sm border-0 h-100">
    <div class="card-body p-4">
      <div class="d-flex align-items-start justify-content-between gap-3">
        <div>
          <p class="text-muted mb-1">Omzet Hari Ini</p>
          <h4 class="mb-0">
            Rp<?= number_format($omzet_hari_ini ?? 0, 0, ',', '.') ?>
          </h4>
          <small class="text-muted">Total penjualan hari ini</small>
        </div>

        <div class="kpi-icon primary">
          <i class="bi bi-cash-coin"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Transaksi -->
<div class="col-12 col-sm-6 col-lg-3">
  <div class="card shadow-sm border-0 h-100">
    <div class="card-body p-4">
      <div class="d-flex align-items-start justify-content-between gap-3">
        <div>
          <p class="text-muted mb-1">Transaksi</p>
          <h4 class="mb-0"><?= $jumlah_transaksi ?? 0 ?></h4>
          <small class="text-muted">Transaksi hari ini</small>
        </div>

        <div class="kpi-icon info">
          <i class="bi bi-receipt"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Total Barang -->
<div class="col-12 col-sm-6 col-lg-3">
  <div class="card shadow-sm border-0 h-100">
    <div class="card-body p-4">
      <div class="d-flex align-items-start justify-content-between gap-3">
        <div>
          <p class="text-muted mb-1">Total Barang</p>
          <h4 class="mb-0"><?= $total_barang ?? 0 ?></h4>
          <small class="text-muted">Barang terdaftar</small>
        </div>

        <div class="kpi-icon success">
          <i class="bi bi-box-seam"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Stok Menipis -->
<div class="col-12 col-sm-6 col-lg-3">
  <div class="card shadow-sm border-0 h-100">
    <div class="card-body p-4">
      <div class="d-flex align-items-start justify-content-between gap-3">
        <div>
          <p class="text-muted mb-1">Stok Menipis</p>
          <h4 class="mb-0"><?= $stok_menipis ?? 0 ?></h4>
          <small class="text-muted">Perlu restock</small>
        </div>

        <div class="kpi-icon warning">
          <i class="bi bi-exclamation-triangle"></i>
        </div>
      </div>
    </div>
  </div>
</div>

        </div>

        <!-- CONTENT -->
        <div class="row g-3 mt-1">

          <!-- Grafik -->
          <div class="col-12 col-xl-7">
            <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <h5 class="mb-0">Grafik Penjualan</h5>
                    <small class="text-muted">7 hari terakhir</small>
                  </div>

                  <a href="<?= base_url('laporan') ?>" class="btn btn-sm btn-light">
                    <i class="bi bi-bar-chart me-1"></i> Laporan
                  </a>
                </div>
              </div>

              <div class="card-body px-4 pb-4 pt-2">
                <div id="chart-penjualan-harian" style="min-height: 250px;"></div>
              </div>
            </div>
          </div>

          <!-- Stok Menipis -->
          <div class="col-12 col-xl-5">
            <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <h5 class="mb-0">Stok Menipis</h5>
                    <small class="text-muted">Barang yang hampir habis</small>
                  </div>

                  <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-light">
                    <i class="bi bi-box-seam me-1"></i> Cek Barang
                  </a>
                </div>
              </div>

              <div class="card-body px-4 pb-4 pt-2">
                <div class="table-responsive">
                  <table class="table table-sm align-middle mb-0">
                    <thead>
                      <tr class="text-muted">
                        <th>Barang</th>
                        <th class="text-end">Stok</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($list_stok_menipis)) : ?>
                        <?php foreach ($list_stok_menipis as $b) : ?>
                          <tr>
                            <td>
                              <div class="fw-semibold"><?= $b->nama_barang ?></div>
                              <small class="text-muted"><?= $b->kode_barang ?></small>
                            </td>
                            <td class="text-end">
                              <span class="badge bg-warning text-dark px-3 py-2">
                                <?= $b->stok ?>
                              </span>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else : ?>
                        <tr>
                          <td colspan="2" class="text-center text-muted py-4">
                            Stok aman ✅
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>

        <!-- TRANSAKSI TERBARU -->
        <div class="row g-3 mt-1">
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                  <div>
                    <h5 class="mb-0">Transaksi Terbaru</h5>
                    <small class="text-muted">Riwayat transaksi terbaru</small>
                  </div>

                  <a href="<?= base_url('transaksi/riwayat') ?>" class="btn btn-sm btn-light">
                    <i class="bi bi-clock-history me-1"></i> Lihat Semua
                  </a>
                </div>
              </div>

              <div class="card-body px-4 pb-4 pt-2">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead>
                      <tr class="text-muted">
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th class="text-end">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($transaksi_terbaru)) : ?>
                        <?php foreach ($transaksi_terbaru as $t) : ?>
                          <tr>
                            <td class="fw-semibold"><?= $t->kode_transaksi ?></td>
                            <td class="text-muted">
                              <?= date('d-m-Y H:i', strtotime($t->tanggal)) ?>
                            </td>
                            <td class="text-end fw-semibold">
                              Rp<?= number_format($t->total, 0, ',', '.') ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else : ?>
                        <tr>
                          <td colspan="3" class="text-center text-muted py-4">
                            Belum ada transaksi hari ini
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT -->
      <div class="col-12 col-lg-3">

        <!-- USER CARD -->
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="avatar avatar-xl">
                <img src="<?= base_url('assets/compiled/jpg/1.jpg') ?>" alt="User">
              </div>
              <div class="ms-3">
                <h5 class="mb-1 fw-bold">
                  <?= $this->session->userdata('nama_lengkap') ?? 'Admin' ?>
                </h5>
                <p class="mb-0 text-muted">Kasir / Admin</p>
              </div>
            </div>

            <hr class="my-3">

            <div class="d-grid gap-2">
              <a href="<?= base_url('transaksi') ?>" class="btn btn-primary">
                <i class="bi bi-cart-check me-1"></i> Transaksi Baru
              </a>
              <a href="<?= base_url('barang') ?>" class="btn btn-light">
                <i class="bi bi-box-seam me-1"></i> Data Barang
              </a>
              <a href="<?= base_url('laporan') ?>" class="btn btn-light">
                <i class="bi bi-graph-up me-1"></i> Laporan
              </a>
              <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
              </a>
            </div>
          </div>
        </div>

        <!-- QUICK INFO -->
        <div class="card shadow-sm border-0 mt-3">
          <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="mb-0">Info Cepat</h5>
            <small class="text-muted">Status kios hari ini</small>
          </div>

          <div class="card-body px-4 pb-4 pt-2">
            <ul class="list-unstyled mb-0">
              <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Tanggal</span>
                <span class="fw-semibold"><?= date('d-m-Y') ?></span>
              </li>
              <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Jam</span>
                <span class="fw-semibold"><?= date('H:i') ?></span>
              </li>
              <li class="d-flex justify-content-between pt-2">
                <span class="text-muted">Mode</span>
                <span class="badge bg-success">Online</span>
              </li>
            </ul>
          </div>
        </div>

      </div>

    </section>
  </div>

  <footer></footer>
</div>
