<style>
#main {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f6f8fb;
    min-height: 100vh;
}

.modern-card {
    background: #ffffff;
    border-radius: 1.25rem;
    border: 1px solid #eef2f7;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
}

.page-heading h4 {
    font-weight: 700;
    color: #0f172a;
}

.page-heading p {
    color: #64748b;
}

.form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: .4rem;
}

.form-control,
.form-select {
    border-radius: .75rem;
    border: 1px solid #e2e8f0;
    background: #f9fbfd;
    padding: .6rem .9rem;
    transition: all .2s ease;
}

.form-control:focus,
.form-select:focus {
    background: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
}

.btn-primary {
    background: #4f46e5;
    border: none;
    border-radius: .75rem;
    font-weight: 600;
    padding: .65rem 1.6rem;
}

.btn-primary:hover {
    background: #4338ca;
}

.btn-outline-secondary {
    border-radius: .75rem;
}

.badge-modern {
    background: #eef2ff;
    color: #4f46e5;
    font-size: .7rem;
    letter-spacing: .5px;
    border-radius: .5rem;
    font-weight: 600;
}

.alert {
    border-radius: .75rem;
    border: none;
    font-size: .85rem;
}

.divider {
    height: 1px;
    background: #f1f5f9;
    margin: 2.5rem 0;
}
</style>

<div id="main" class="p-3 p-md-4">

    <!-- MOBILE SIDEBAR BUTTON -->
    <header class="mb-3 d-xl-none">
        <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <!-- PAGE HEADING -->
    <div class="page-heading mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">Tambah Barang</h4>
                <p class="mb-0 small">
                    Tambahkan produk baru ke dalam sistem inventaris
                </p>
            </div>

            <span class="badge badge-modern px-3 py-2 text-uppercase">
                <i class="bi bi-upc-scan me-1"></i> Inventory
            </span>
        </div>
    </div>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-12">
            <div class="modern-card p-4 p-md-5">

                <div class="mb-4">
                    <h6 class="fw-bold mb-1 text-dark">Form Input Produk</h6>
                    <small class="text-muted">
                        Pastikan data yang dimasukkan sudah benar sebelum menyimpan
                    </small>
                </div>

                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4">
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error_validation')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4">
                        <?= $this->session->flashdata('error_validation') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('barang/simpan') ?>" method="post" autocomplete="off">

                    <div class="row g-4">

                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Kode Barang</label>
                            <input type="text"
                                   name="kode_barang"
                                   class="form-control <?= form_error('kode_barang') ? 'is-invalid' : '' ?>"
                                   placeholder="BRG001"
                                   value="<?= set_value('kode_barang') ?>">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Barcode</label>
                            <input type="text"
                                   name="barcode"
                                   class="form-control <?= form_error('barcode') ? 'is-invalid' : '' ?>"
                                   placeholder="Scan atau input barcode"
                                   value="<?= set_value('barcode') ?>">
                            <small class="text-muted">
                                Bisa diisi manual atau menggunakan scanner
                            </small>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label">Nama Barang</label>
                            <input type="text"
                                   name="nama_barang"
                                   class="form-control <?= form_error('nama_barang') ? 'is-invalid' : '' ?>"
                                   placeholder="Nama produk"
                                   value="<?= set_value('nama_barang') ?>">
                        </div>

                        <div class="col-6 col-lg-1">
                            <label class="form-label">Stok</label>
                            <input type="number"
                                   name="stok"
                                   min="0"
                                   class="form-control"
                                   value="<?= set_value('stok') ?>">
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number"
                                       name="harga_jual"
                                       min="0"
                                       class="form-control border-start-0"
                                       value="<?= set_value('harga_jual') ?>">
                            </div>
                        </div>

                    </div>

                    <div class="divider"></div>

                    <div class="d-flex justify-content-end gap-3 flex-wrap">
                        <button type="reset" class="btn btn-outline-secondary px-4">
                            Reset
                        </button>

                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save2 me-2"></i> Simpan Produk
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-12">
            <?php $this->load->view('product/table_produk', ['list_barang' => $list_barang]); ?>
        </div>

    </div>

</div>