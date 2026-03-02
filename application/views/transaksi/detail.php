<div id="main">

  <!-- PAGE HEADING -->
  <div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h3 class="mb-1">Detail Transaksi</h3>
        <p class="text-muted mb-0">Informasi lengkap transaksi</p>
      </div>

      <?php if ($status_retur === 'belum'): ?>
        <span class="badge bg-success px-3 py-2">Belum Diretur</span>
      <?php elseif ($status_retur === 'sebagian'): ?>
        <span class="badge bg-warning text-dark px-3 py-2">Retur Sebagian</span>
      <?php elseif ($status_retur === 'selesai'): ?>
        <span class="badge bg-danger px-3 py-2">Sudah Diretur</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="page-content">
    <div class="row g-4">

      <!-- ================= INFO TRANSAKSI ================= -->
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body p-4">

            <h5 class="mb-4">Informasi Transaksi</h5>

            <div class="mb-3">
              <small class="text-muted">Kode Transaksi</small>
              <div class="fw-semibold fs-5">
                <?= $transaksi->kode_transaksi ?>
              </div>
            </div>

            <div class="mb-3">
              <small class="text-muted">Tanggal</small>
              <div>
                <?= date('d-m-Y H:i', strtotime($transaksi->tanggal)) ?>
              </div>
            </div>

            <div class="mb-3">
              <small class="text-muted">Total Belanja</small>
              <div class="fw-semibold">
                Rp<?= number_format($transaksi->total,0,',','.') ?>
              </div>
            </div>

            <?php if ($total_retur > 0): ?>
              <div class="mb-3">
                <small class="text-muted">Total Diretur</small>
                <div class="fw-semibold text-danger">
                  Rp<?= number_format($total_retur,0,',','.') ?>
                </div>
              </div>
            <?php endif; ?>

            <hr>

            <!-- BUTTON RETUR -->
            <?php if (!empty($masih_ada_sisa) && $masih_ada_sisa): ?>
              <a href="<?= base_url('transaksi/go_retur/'.$transaksi->id_transaksi) ?>"
                 class="btn btn-warning w-100 mt-3">
                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Proses Retur
              </a>
            <?php else: ?>
              <button class="btn btn-secondary w-100 mt-3" disabled>
                <i class="bi bi-check-circle me-1"></i>
                Semua Barang Sudah Diretur
              </button>
            <?php endif; ?>

          </div>
        </div>
      </div>

      <!-- ================= DETAIL BARANG ================= -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

            <h5 class="mb-4">Detail Barang</h5>

            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="text-muted small">
                  <tr>
                    <th>Barang</th>
                    <th class="text-end">Harga</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($detail as $d): ?>
                    <tr>
                      <td>
                        <div class="fw-semibold"><?= $d->nama_barang ?></div>
                        <small class="text-muted"><?= $d->kode_barang ?></small>
                      </td>
                      <td class="text-end">
                        Rp<?= number_format($d->harga,0,',','.') ?>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-light text-dark px-3 py-2">
                          <?= $d->qty ?>
                        </span>
                      </td>
                      <td class="text-end fw-semibold">
                        Rp<?= number_format($d->subtotal,0,',','.') ?>
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

    <!-- ================= RIWAYAT RETUR ================= -->
    <?php if (!empty($retur)): ?>

      <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">

          <h5 class="mb-4">Riwayat Retur</h5>

          <?php 
          $grouped = [];
          foreach ($retur as $r) {
              $grouped[$r->kode_retur]['tanggal'] = $r->tanggal;
              $grouped[$r->kode_retur]['total']   = $r->total_retur;
              $grouped[$r->kode_retur]['items'][] = $r;
          }
          ?>

          <?php foreach ($grouped as $kode => $dataRetur): ?>

            <div class="border rounded p-3 mb-3">

              <div class="d-flex justify-content-between mb-2">
                <div>
                  <div class="fw-semibold"><?= $kode ?></div>
                  <small class="text-muted">
                    <?= date('d-m-Y H:i', strtotime($dataRetur['tanggal'])) ?>
                  </small>
                </div>
                <div class="fw-bold text-danger">
                  Rp<?= number_format($dataRetur['total'],0,',','.') ?>
                </div>
              </div>

              <!-- DETAIL BARANG YANG DIRETUR -->
              <ul class="list-group list-group-flush small">
                <?php foreach ($dataRetur['items'] as $item): ?>
                  <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                      <?= $item->nama_barang ?>
                      <span class="text-muted">(<?= $item->kode_barang ?>)</span>
                    </div>
                    <div class="fw-semibold text-danger">
                      x<?= $item->qty ?>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>

            </div>

          <?php endforeach; ?>

        </div>
      </div>

    <?php endif; ?>

  </div>
</div>