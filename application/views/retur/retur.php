<div id="main">
  <div class="page-heading mb-3">
    <h3>Retur Barang</h3>
    <p class="text-muted">Pilih barang yang akan diretur</p>
  </div>

  <div class="page-content">
    <form method="post" action="<?= base_url('retur/simpan') ?>">
      <input type="hidden" name="id_transaksi" value="<?= $transaksi->id_transaksi ?>">

      <div class="card shadow-sm border-0">
        <div class="card-body">

          <?php foreach ($detail as $d): ?>
            <div class="row align-items-center mb-3">
              <div class="col-md-6">
                <?= $d->nama_barang ?>
                <small class="text-muted">(Max <?= $d->qty ?>)</small>
              </div>
              <div class="col-md-3">
                <input type="number"
                       name="qty[<?= $d->id_barang ?>]"
                       class="form-control"
                       min="0"
                       max="<?= $d->qty ?>">
              </div>
            </div>
          <?php endforeach; ?>

          <button class="btn btn-danger mt-3">
            Simpan Retur
          </button>

        </div>
      </div>
    </form>
  </div>
</div>
