<div id="main">

  <!-- PAGE HEADING -->
  <div class="page-heading mb-3">
    <h3>Retur Transaksi</h3>
    <p class="text-muted">
      Proses pengembalian barang dari transaksi
    </p>
  </div>

  <!-- PAGE CONTENT -->
  <div class="page-content">
    <div class="row g-3">

      <!-- INFO TRANSAKSI -->
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="mb-3">Info Transaksi</h5>

            <div class="mb-2">
              Kode:
              <strong><?= $transaksi->kode_transaksi ?></strong>
            </div>

            <div class="mb-2">
              Tanggal:
              <?= date('d-m-Y H:i', strtotime($transaksi->tanggal)) ?>
            </div>

            <div class="mb-2">
              Total:
              Rp<?= number_format($transaksi->total,0,',','.') ?>
            </div>

            <span class="badge bg-warning text-dark mt-3 px-3 py-2">
              MODE RETUR
            </span>

            <a href="<?= base_url('transaksi/detail/'.$transaksi->id_transaksi) ?>"
               class="btn btn-light w-100 mt-3">
              <i class="bi bi-arrow-left me-1"></i>
              Kembali ke Detail
            </a>
          </div>
        </div>
      </div>

      <!-- FORM RETUR -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body">

            <h5 class="mb-3">Daftar Barang</h5>

            <form method="post" action="<?= base_url('retur/simpan') ?>" id="formRetur">
              <input type="hidden" name="id_transaksi"
                     value="<?= $transaksi->id_transaksi ?>">

              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="text-muted">
                    <tr>
                      <th>Barang</th>
                      <th class="text-center" style="width:110px;">Qty Beli</th>
                      <th class="text-center" style="width:160px;">Qty Retur</th>
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

                      <td class="text-center">
                        <input
                          type="number"
                          name="items[<?= $d->id_barang ?>]"
                          min="0"
                          max="<?= $d->qty ?>"
                          placeholder="0"
                          class="form-control text-center retur-qty"
                          style="max-width:120px;margin:auto;"
                        >
                        <small class="text-muted d-block mt-1">
                          Maks: <?= $d->qty ?>
                        </small>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <!-- ACTION -->
              <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-warning px-4">
                  <i class="bi bi-arrow-counterclockwise me-1"></i>
                  Simpan Retur
                </button>
              </div>

            </form>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<script>
document.getElementById("formRetur").addEventListener("submit", function (e) {
  const inputs = document.querySelectorAll(".retur-qty");
  let adaRetur = false;

  inputs.forEach(i => {
    const val = parseInt(i.value || 0);
    if (val > 0) adaRetur = true;
  });

  if (!adaRetur) {
    e.preventDefault();
    alert("Isi minimal 1 Qty Retur sebelum menyimpan.");
  }
});
</script>

