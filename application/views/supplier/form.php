<div id="main" class="p-3 p-md-5">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Tambah Supplier</h4>
            <p class="text-muted small mb-0">
                Masukkan data supplier baru ke dalam sistem
            </p>
        </div>

        <a href="<?= base_url('supplier') ?>" 
           class="btn btn-outline-secondary px-3">
            Kembali
        </a>
    </div>


    <!-- MAIN FORM CONTAINER -->
    <div class="bg-white border rounded-4 shadow-sm">

        <!-- TOP STRIP -->
        <div class="px-4 px-md-5 py-4 border-bottom">
            <h6 class="fw-semibold mb-1">Informasi Supplier</h6>
            <small class="text-muted">
                Data berikut akan digunakan untuk kebutuhan pembelian dan pencatatan stok
            </small>
        </div>


        <div class="p-4 p-md-5">

            <!-- SUCCESS MESSAGE -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- ERROR MESSAGE -->
            <?php if (validation_errors()) : ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= validation_errors() ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>


            <form method="post" action="<?= base_url('supplier/simpan') ?>">

                <div class="row g-4">

                    <!-- NAMA SUPPLIER -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Supplier
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nama_supplier"
                               class="form-control form-control-lg"
                               placeholder="Contoh: PT Sumber Jaya"
                               value="<?= set_value('nama_supplier') ?>"
                               required>
                        <small class="text-muted">
                            Nama perusahaan atau toko supplier
                        </small>
                    </div>

                    <!-- NO HP -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">
                            Nomor Telepon
                        </label>
                        <input type="text"
                               name="no_hp"
                               class="form-control form-control-lg"
                               placeholder="08xxxxxxxxxx"
                               value="<?= set_value('no_hp') ?>">
                        <small class="text-muted">
                            Nomor yang dapat dihubungi
                        </small>
                    </div>

                    <!-- ALAMAT -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Alamat
                        </label>
                        <textarea name="alamat"
                                  rows="3"
                                  class="form-control form-control-lg"
                                  placeholder="Masukkan alamat lengkap supplier"><?= set_value('alamat') ?></textarea>
                    </div>

                    <!-- KETERANGAN -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Keterangan Tambahan
                        </label>
                        <input type="text"
                               name="keterangan"
                               class="form-control form-control-lg"
                               placeholder="Informasi tambahan (opsional)"
                               value="<?= set_value('keterangan') ?>">
                    </div>

                </div>


                <!-- ACTION SECTION -->
                <div class="mt-5 pt-4 border-top">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                        <small class="text-muted text-center text-md-start">
                            Pastikan data sudah benar sebelum menyimpan.
                        </small>

                        <div class="d-flex flex-column flex-sm-row gap-2">

                            <a href="<?= base_url('supplier') ?>" 
                               class="btn btn-light border px-4">
                                Batal
                            </a>

                            <button type="submit"
                                    class="btn btn-primary px-4">
                                Simpan Supplier
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>