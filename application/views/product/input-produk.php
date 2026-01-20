<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <div class="page-heading mb-3">
    <h3 class="mb-1">Tambah Barang</h3>
    <p class="text-muted mb-0">Input data barang untuk kios</p>
  </div>

  <div class="page-content">
    <div class="row">
      <!-- FULL WIDTH CARD -->
      <div class="col-12">

        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="mb-0">Form Input Barang</h5>
            <small class="text-muted">Isi data dengan benar agar transaksi lancar</small>
          </div>

          <div class="card-body px-4 pb-4 pt-3">

            <?php if ($this->session->flashdata('success')) : ?>
              <div class="alert alert-success">
                <?= $this->session->flashdata('success') ?>
              </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
              <div class="alert alert-danger">
                <?= $this->session->flashdata('error') ?>
              </div>
            <?php endif; ?>

            <form action="<?= base_url('barang/simpan') ?>" method="post" autocomplete="off">

              <div class="row g-3">

                <!-- KODE BARANG -->
                <div class="col-12 col-md-6 col-lg-4">
                  <label class="form-label">Kode Barang</label>
                  <input type="text" name="kode_barang"
                    class="form-control <?= form_error('kode_barang') ? 'is-invalid' : '' ?>"
                    placeholder="Contoh: BRG001"
                    value="<?= set_value('kode_barang') ?>">
                  <div class="invalid-feedback">
                    <?= form_error('kode_barang') ?>
                  </div>
                </div>

                <!-- NAMA BARANG -->
                <div class="col-12 col-md-6 col-lg-5">
                  <label class="form-label">Nama Barang</label>
                  <input type="text" name="nama_barang"
                    class="form-control <?= form_error('nama_barang') ? 'is-invalid' : '' ?>"
                    placeholder="Contoh: Indomie Goreng"
                    value="<?= set_value('nama_barang') ?>">
                  <div class="invalid-feedback">
                    <?= form_error('nama_barang') ?>
                  </div>
                </div>

                <!-- STOK -->
                <div class="col-12 col-md-6 col-lg-3">
                  <label class="form-label">Stok</label>
                  <input type="number" name="stok" min="0"
                    class="form-control <?= form_error('stok') ? 'is-invalid' : '' ?>"
                    placeholder="Contoh: 50"
                    value="<?= set_value('stok') ?>">
                  <div class="invalid-feedback">
                    <?= form_error('stok') ?>
                  </div>
                </div>

                <!-- HARGA JUAL -->
                <div class="col-12 col-md-6 col-lg-4">
                  <label class="form-label">Harga Jual (Rp)</label>
                  <input type="number" name="harga_jual" min="0"
                    class="form-control <?= form_error('harga_jual') ? 'is-invalid' : '' ?>"
                    placeholder="Contoh: 3500"
                    value="<?= set_value('harga_jual') ?>">
                  <div class="invalid-feedback">
                    <?= form_error('harga_jual') ?>
                  </div>
                </div>

              </div>

              <hr class="my-4">

              <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('barang') ?>" class="btn btn-light">
                  <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button type="reset" class="btn btn-outline-secondary">
                  Reset
                </button>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save me-1"></i> Simpan Barang
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
