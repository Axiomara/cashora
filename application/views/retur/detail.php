<div id="main">

  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <!-- PAGE HEADING -->
  <div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h3 class="mb-1">Detail Retur</h3>
        <p class="text-muted mb-0">Informasi lengkap retur transaksi</p>
      </div>

      <?php
        $badgeClass = 'bg-success';
        $badgeText  = 'Retur Selesai';

        if ($status_retur == 'sebagian') {
          $badgeClass = 'bg-warning text-dark';
          $badgeText  = 'Retur Sebagian';
        }

        if ($status_retur == 'belum') {
          $badgeClass = 'bg-secondary';
          $badgeText  = 'Belum Diretur';
        }
      ?>

      <span class="badge <?= $badgeClass ?> px-3 py-2">
        <?= $badgeText ?>
      </span>
    </div>
  </div>

  <div class="page-content">
    <div class="row g-4">

      <!-- LEFT PANEL -->
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body p-4">

            <h5 class="mb-3">Informasi Retur</h5>

            <div class="mb-3">
              <div class="text-muted small">Kode Retur</div>
              <div class="fw-semibold fs-5">
                <?= $retur->kode_retur ?>
              </div>
            </div>

            <div class="mb-3">
              <div class="text-muted small">Tanggal Retur</div>
              <div>
                <?= date('d-m-Y H:i', strtotime($retur->tanggal)) ?>
              </div>
            </div>

            <div class="mb-3">
              <div class="text-muted small">Total Retur</div>
              <div class="fw-bold text-danger fs-5">
                Rp<?= number_format($retur->total_retur, 0, ',', '.') ?>
              </div>
            </div>

            <hr>

            <a href="<?= base_url('transaksi/detail/'.$retur->id_transaksi) ?>"
               class="btn btn-light w-100">
              <i class="bi bi-arrow-left me-1"></i>
              Kembali ke Detail Transaksi
            </a>

          </div>
        </div>
      </div>

      <!-- RIGHT PANEL -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

            <h5 class="mb-3">Detail Barang Diretur</h5>

            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="text-muted small">
                  <tr>
                    <th>Barang</th>
                    <th class="text-center" style="width:120px;">Qty Retur</th>
                    <th class="text-end" style="width:150px;">Harga</th>
                    <th class="text-end" style="width:150px;">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($detail as $d): ?>
                    <tr>
                      <td>
                        <div class="fw-semibold"><?= $d->nama_barang ?></div>
                        <small class="text-muted"><?= $d->kode_barang ?></small>
                      </td>

                      <td class="text-center">
                        <span class="badge bg-light text-dark px-3 py-2">
                          <?= $d->qty ?>
                        </span>
                      </td>

                      <td class="text-end">
                        Rp<?= number_format($d->harga, 0, ',', '.') ?>
                      </td>

                      <td class="text-end fw-semibold">
                        Rp<?= number_format($d->subtotal, 0, ',', '.') ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>