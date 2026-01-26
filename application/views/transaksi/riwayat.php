<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-1">Riwayat Transaksi</h3>
      <p class="text-muted mb-0">Kelola dan lihat transaksi yang sudah tersimpan</p>
    </div>

    <div class="d-flex gap-2">
      <a href="<?= base_url('transaksi') ?>" class="btn btn-primary shadow-sm">
        <i class="bi bi-cart-plus me-1"></i> Transaksi Baru
      </a>
      <a href="<?= base_url('dashboard') ?>" class="btn btn-light shadow-sm">
        <i class="bi bi-house-door me-1"></i> Dashboard
      </a>
    </div>
  </div>

  <div class="page-content">

    <!-- ALERT -->
    <?php if ($this->session->flashdata('success')) : ?>
      <div class="alert alert-success border-0 shadow-sm rounded-4">
        <i class="bi bi-check-circle me-1"></i>
        <?= $this->session->flashdata('success') ?>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
      <div class="alert alert-danger border-0 shadow-sm rounded-4">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <?= $this->session->flashdata('error') ?>
      </div>
    <?php endif; ?>

    <!-- FILTER CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body p-4">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
          <div>
            <h5 class="mb-1">Filter Transaksi</h5>
            <small class="text-muted">Cari berdasarkan kode & rentang tanggal</small>
          </div>

          <?php if (!empty($q) || !empty($from) || !empty($to)) : ?>
            <a href="<?= base_url('transaksi/riwayat') ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
              <i class="bi bi-x-circle me-1"></i> Reset Filter
            </a>
          <?php endif; ?>
        </div>

        <form method="get" action="<?= base_url('transaksi/riwayat') ?>" class="row g-2 align-items-end">

          <div class="col-12 col-md-5">
            <label class="form-label">Cari Kode</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-0 shadow-sm">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" name="q"
                class="form-control border-0 shadow-sm"
                placeholder="Contoh: TRX0001"
                value="<?= htmlspecialchars($q ?? '') ?>">
            </div>
          </div>

          <div class="col-6 col-md-3">
            <label class="form-label">Dari</label>
            <input type="date" name="from"
              class="form-control border-0 shadow-sm"
              value="<?= htmlspecialchars($from ?? '') ?>">
          </div>

          <div class="col-6 col-md-3">
            <label class="form-label">Sampai</label>
            <input type="date" name="to"
              class="form-control border-0 shadow-sm"
              value="<?= htmlspecialchars($to ?? '') ?>">
          </div>

          <div class="col-12 col-md-1 d-grid">
            <button class="btn btn-dark shadow-sm">
              <i class="bi bi-funnel"></i>
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">

        <div class="p-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h5 class="mb-1">Daftar Transaksi</h5>
            <small class="text-muted">
              Total data:
              <span class="badge bg-light text-dark rounded-pill">
                <?= $totalRows ?? 0 ?>
                </span>
            </small>
          </div>
        </div>

        <div class="table-responsive px-3 pb-3">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="text-muted small">
                <th style="width:70px;" class="ps-3">No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th class="text-end">Total</th>
                <th class="text-end">Bayar</th>
                <th class="text-end">Kembali</th>
                <th class="text-end pe-3" style="width:240px;">Aksi</th>
              </tr>
            </thead>

            <tbody>
              <?php if (!empty($list)) : ?>
                <?php $no = 1; foreach ($list as $t) : ?>
                  <tr>

                    <td class="ps-3">
                      <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                        <?= $no++ ?>
                      </span>
                    </td>

                    <td>
                      <div class="fw-semibold"><?= $t->kode_transaksi ?></div>
                      <small class="text-muted">ID: #<?= $t->id_transaksi ?></small>
                    </td>

                    <td>
                      <div class="fw-semibold"><?= date('d M Y', strtotime($t->tanggal)) ?></div>
                      <small class="text-muted"><?= date('H:i', strtotime($t->tanggal)) ?> WIB</small>
                    </td>

                    <td class="text-end">
                      <span class="fw-semibold">
                        Rp<?= number_format($t->total, 0, ',', '.') ?>
                      </span>
                    </td>

                    <td class="text-end text-muted">
                      Rp<?= number_format($t->bayar, 0, ',', '.') ?>
                    </td>

                    <td class="text-end">
                      <?php if ((int)$t->kembalian > 0) : ?>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                          Rp<?= number_format($t->kembalian, 0, ',', '.') ?>
                        </span>
                      <?php else : ?>
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                          Rp0
                        </span>
                      <?php endif; ?>
                    </td>

                    <td class="text-end pe-3">
                      <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <a href="<?= base_url('transaksi/detail/' . $t->id_transaksi) ?>"
                          class="btn btn-sm btn-light shadow-sm rounded-pill px-3">
                          <i class="bi bi-eye me-1"></i> Detail
                        </a>

                        <a href="<?= base_url('transaksi/nota-pdf/' . $t->id_transaksi) ?>"
                          class="btn btn-sm btn-danger shadow-sm rounded-pill px-3">
                          <i class="bi bi-file-earmark-pdf me-1"></i> Nota
                        </a>
                      </div>
                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center gap-2">
                      <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                        style="width:64px;height:64px;background:#f8f9fa;">
                        <i class="bi bi-receipt fs-2 text-muted"></i>
                      </div>
                      <div class="fw-semibold">Belum ada transaksi</div>
                      <div class="text-muted small">Silakan buat transaksi baru dulu ya ✅</div>
                      <a href="<?= base_url('transaksi') ?>" class="btn btn-primary mt-2 rounded-pill px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>

          </table>
            <?php if (!empty($pagination)) : ?>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 px-1">
                <small class="text-muted">
                Menampilkan <?= ($offset + 1) ?> - <?= min($offset + $perPage, $totalRows) ?> dari <?= $totalRows ?> data
                </small>
                <?= $pagination ?>
            </div>
            <?php endif; ?>

        </div>

      </div>
    </div>

  </div>
</div>

<style>
  /* bikin feel modern */
  .rounded-4 { border-radius: 18px !important; }
  .table-hover tbody tr:hover { background: #f8fafc; }
  .btn { border-radius: 14px; }
  .form-control:focus {
    box-shadow: 0 0 0 .2rem rgba(13,110,253,.15) !important;
  }
  .input-group-text { border-radius: 14px; }
</style>
