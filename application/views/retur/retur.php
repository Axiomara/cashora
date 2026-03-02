<div id="main">
  <div class="page-heading mb-3">
    <h3>Retur Barang</h3>
    <p class="text-muted">Pilih barang yang akan diretur</p>
  </div>

  <div class="page-content">

    <!-- ALERT ERROR -->
    <?php if ($this->session->flashdata('error')) : ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('retur/simpan') ?>" id="formRetur">
      <input type="hidden" name="id_transaksi" value="<?= $transaksi->id_transaksi ?>">

      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

          <?php foreach ($detail as $d): ?>

            <?php 
              // kalau controller kirim sisa_qty, pakai itu
              $max_qty = $d->qty; 
              if (isset($d->sisa_qty)) {
                  $max_qty = $d->sisa_qty;
              }
            ?>

            <div class="row align-items-center mb-4">
              <div class="col-md-6">
                <div class="fw-semibold"><?= $d->nama_barang ?></div>
                <small class="text-muted">
                  Dibeli: <?= $d->qty ?> |
                  Sisa bisa retur: <?= $max_qty ?>
                </small>
              </div>

              <div class="col-md-3">
                <input type="number"
                       name="items[<?= $d->id_detail ?>]"
                       class="form-control form-control-sm"
                       min="0"
                       max="<?= $max_qty ?>"
                       placeholder="0">
                <div class="invalid-feedback">
                  Qty melebihi batas maksimal
                </div>
              </div>
            </div>

          <?php endforeach; ?>

          <div class="text-end">
            <button type="submit" class="btn btn-danger px-4">
              <i class="bi bi-arrow-counterclockwise me-1"></i>
              Simpan Retur
            </button>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('formRetur').addEventListener('submit', function(e) {

  let valid = true;

  const inputs = this.querySelectorAll('input[type="number"]');

  inputs.forEach(input => {
      const max = parseInt(input.getAttribute('max')) || 0;
      const value = parseInt(input.value) || 0;

      if (value > max) {
          input.classList.add('is-invalid');
          valid = false;
      } else {
          input.classList.remove('is-invalid');
      }
  });

  if (!valid) {
      e.preventDefault();
  }
});
</script>