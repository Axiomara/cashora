<div id="main">

  <!-- PAGE HEADING -->
  <div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h3 class="mb-1">Retur Transaksi</h3>
        <p class="text-muted mb-0">
          Proses pengembalian barang dari transaksi
        </p>
      </div>
      <span class="badge bg-warning text-dark px-3 py-2">
        MODE RETUR
      </span>
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
              <div class="fw-semibold fs-6">
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

            <hr>

            <div class="mb-3">
              <small class="text-muted">Estimasi Total Retur</small>
              <div class="fw-bold text-danger fs-5" id="totalReturPreview">
                Rp0
              </div>
            </div>

            <a href="<?= base_url('transaksi/detail/'.$transaksi->id_transaksi) ?>"
               class="btn btn-light w-100">
              <i class="bi bi-arrow-left me-1"></i>
              Kembali ke Detail
            </a>

          </div>
        </div>
      </div>

      <!-- ================= FORM RETUR ================= -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

            <h5 class="mb-4">Daftar Barang</h5>

            <form method="post"
                  action="<?= base_url('retur/simpan') ?>"
                  id="formRetur">

              <input type="hidden"
                     name="id_transaksi"
                     value="<?= $transaksi->id_transaksi ?>">

              <div class="table-responsive">
                <table class="table align-middle table-hover">
                  <thead class="text-muted small">
                    <tr>
                      <th>Barang</th>
                      <th class="text-center" style="width:110px;">Qty Beli</th>
                      <th class="text-center" style="width:140px;">Qty Retur</th>
                      <th class="text-end" style="width:120px;">Harga</th>
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
                          name="items[<?= $d->id_detail ?>]"
                          min="0"
                          max="<?= $d->sisa_qty ?>"
                          data-harga="<?= $d->harga ?>"
                          placeholder="0"
                          class="form-control form-control-sm text-center retur-qty"
                          style="max-width:100px;margin:auto;"
                        >
                        <small class="text-muted d-block mt-1">
                          Sisa Maks: <?= $d->sisa_qty ?>
                        </small>
                      </td>

                      <td class="text-end">
                        Rp<?= number_format($d->harga,0,',','.') ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
              </div>

              <div class="d-flex justify-content-end mt-4">
                <button type="submit"
                        class="btn btn-warning px-4"
                        id="btnSimpanRetur">
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
function formatRupiah(angka) {
  angka = parseInt(angka || 0);
  return "Rp" + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

const formRetur = document.getElementById("formRetur");
const inputs = document.querySelectorAll(".retur-qty");
const totalPreview = document.getElementById("totalReturPreview");

function hitungTotalRetur() {
  let total = 0;

  inputs.forEach(i => {
    const qty = parseInt(i.value || 0);
    const harga = parseInt(i.dataset.harga || 0);
    total += qty * harga;
  });

  totalPreview.innerText = formatRupiah(total);
}

inputs.forEach(i => {
  i.addEventListener("input", function() {

    const max = parseInt(this.max);
    let val = parseInt(this.value || 0);

    if (val > max) {
      val = max;
      this.value = max;
    }

    if (val < 0) {
      this.value = 0;
    }

    hitungTotalRetur();
  });
});

formRetur.addEventListener("submit", function (e) {

  let adaRetur = false;

  inputs.forEach(i => {
    if (parseInt(i.value || 0) > 0) {
      adaRetur = true;
    }
  });

  if (!adaRetur) {
    e.preventDefault();
    alert("Isi minimal 1 Qty Retur sebelum menyimpan.");
    return;
  }

  document.getElementById("btnSimpanRetur").disabled = true;
});
</script>