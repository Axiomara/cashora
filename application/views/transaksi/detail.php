<div id="main">
  <div class="page-heading mb-3">
    <h3>Detail Transaksi</h3>
    <p class="text-muted">Informasi lengkap transaksi</p>
  </div>

  <div class="page-content">
    <div class="row g-3">

      <!-- INFO TRANSAKSI -->
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="mb-3">Info Transaksi</h5>

            <div class="mb-2">Kode: <b><?= $transaksi->kode_transaksi ?></b></div>
            <div class="mb-2">Tanggal: <?= date('d-m-Y H:i', strtotime($transaksi->tanggal)) ?></div>
            <div class="mb-2">Total: Rp<?= number_format($transaksi->total,0,',','.') ?></div>
            <div class="mb-2">Bayar: Rp<?= number_format($transaksi->bayar,0,',','.') ?></div>
            <div class="mb-2">Kembali: Rp<?= number_format($transaksi->kembalian,0,',','.') ?></div>

           <a href="<?= base_url('transaksi/go_retur/'.$transaksi->id_transaksi) ?>"
   class="btn btn-warning w-100 mt-3">
  <i class="bi bi-arrow-counterclockwise me-1"></i>
  Proses Retur
</a>



          </div>
        </div>
      </div>

      <!-- DETAIL BARANG -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="mb-3">Detail Barang</h5>

            <div class="table-responsive">
              <table class="table align-middle">
                <thead class="text-muted">
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
                      <?= $d->nama_barang ?><br>
                      <small class="text-muted"><?= $d->kode_barang ?></small>
                    </td>
                    <td class="text-end">Rp<?= number_format($d->harga,0,',','.') ?></td>
                    <td class="text-center"><?= $d->qty ?></td>
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

    <!-- RIWAYAT RETUR -->
    <?php if (!empty($retur)): ?>
    <div class="card shadow-sm border-0 mt-3">
      <div class="card-body">
        <h5>Riwayat Retur</h5>
        <ul class="list-group list-group-flush">
          <?php foreach ($retur as $r): ?>
          <li class="list-group-item d-flex justify-content-between">
            <span><?= $r->kode_retur ?> (<?= date('d-m-Y', strtotime($r->tanggal)) ?>)</span>
            <span class="fw-semibold">
              Rp<?= number_format($r->total_retur,0,',','.') ?>
            </span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>
