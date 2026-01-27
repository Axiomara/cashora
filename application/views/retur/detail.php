<div id="main">
  <div class="page-heading mb-3">
    <h3>Detail Retur</h3>
    <p class="text-muted">Informasi retur transaksi</p>
  </div>

  <div class="page-content">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4">

        <!-- INFO RETUR -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="text-muted small">Kode Retur</div>
            <div class="fw-semibold"><?= $retur->kode_retur ?></div>
          </div>
          <div class="col-md-6 text-md-end">
            <div class="text-muted small">Tanggal</div>
            <div><?= date('d-m-Y H:i', strtotime($retur->tanggal)) ?></div>
          </div>
        </div>

        <hr>

        <!-- TABLE DETAIL -->
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr class="text-muted">
                <th>Barang</th>
                <th class="text-center">Qty Retur</th>
                <th class="text-end">Harga</th>
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
                  <td class="text-center"><?= $d->qty ?></td>
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

        <hr>

        <!-- TOTAL -->
        <div class="d-flex justify-content-between">
          <div class="fw-semibold">Total Retur</div>
          <div class="fw-bold text-danger">
            Rp<?= number_format($retur->total_retur, 0, ',', '.') ?>
          </div>
        </div>

        <div class="mt-4">
          <a href="<?= base_url('transaksi/riwayat') ?>" class="btn btn-light">
            ← Kembali ke Riwayat
          </a>
        </div>

      </div>
    </div>
  </div>
</div>
