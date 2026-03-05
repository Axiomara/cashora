<div id="main" class="p-3 p-md-4">

    <!-- MOBILE SIDEBAR BUTTON -->
    <header class="mb-3 d-xl-none">
        <a href="#" class="burger-btn d-block text-dark">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Data Supplier</h4>
            <p class="text-muted small mb-0">
                Kelola daftar supplier untuk pembelian barang
            </p>
        </div>

        <a href="<?= base_url('supplier/tambah') ?>" 
           class="btn btn-dark px-4 rounded-pill">
            Tambah Supplier
        </a>
    </div>


    <!-- CARD CONTAINER -->
    <div class="bg-white border rounded-4 shadow-sm overflow-hidden">

        <!-- HEADER + FILTER -->
        <div class="px-4 py-4 border-bottom bg-light bg-opacity-50">

            <div class="row g-3 align-items-center">

                <!-- LEFT INFO -->
                <div class="col-12 col-md">
                    <h6 class="fw-semibold mb-2">Daftar Supplier</h6>

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <span class="badge bg-light text-dark border px-3 py-2">
                            <?= isset($supplier) ? count($supplier) : 0 ?> Supplier
                        </span>

                        <?php if ($this->input->get('keyword')) : ?>
                            <span class="badge bg-dark bg-opacity-10 text-dark border px-3 py-2">
                                Pencarian:
                                <?= htmlspecialchars($this->input->get('keyword'), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- FILTER + SEARCH -->
                <div class="col-12 col-md-auto">

                    <form method="get" action="<?= base_url('supplier') ?>" 
                          class="d-flex flex-column flex-md-row gap-2">

                        <select name="filter" class="form-select rounded-pill">

                            <option value="">Semua</option>

                            <option value="ada_hp"
                                <?= $this->input->get('filter') == 'ada_hp' ? 'selected' : '' ?>>
                                Ada No HP
                            </option>

                            <option value="tanpa_hp"
                                <?= $this->input->get('filter') == 'tanpa_hp' ? 'selected' : '' ?>>
                                Tanpa No HP
                            </option>

                        </select>

                        <input type="text"
                               name="keyword"
                               value="<?= htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               class="form-control rounded-pill"
                               placeholder="Cari supplier..."
                               style="min-width:200px;">

                        <button type="submit" class="btn btn-dark rounded-pill px-4">
                            Cari
                        </button>

                        <a href="<?= base_url('supplier') ?>" 
                           class="btn btn-light border rounded-pill px-4">
                            Reset
                        </a>

                    </form>

                </div>

            </div>

        </div>


        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="bg-light bg-opacity-25">

                    <tr class="text-uppercase small text-muted" style="letter-spacing:.05em;">
                        <th class="ps-4 py-3 border-0">Supplier</th>
                        <th class="py-3 border-0">Kontak</th>
                        <th class="py-3 border-0">Alamat</th>
                        <th class="py-3 border-0">Keterangan</th>
                        <th class="text-end pe-4 py-3 border-0">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (!empty($supplier)) : ?>

                    <?php foreach ($supplier as $s) : ?>

                        <tr class="border-top">

                            <td class="ps-4 py-4 border-0">

                                <div class="fw-semibold text-dark">
                                    <?= htmlspecialchars($s->nama_supplier ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </div>

                                <small class="text-muted">
                                    ID-<?= str_pad($s->id_supplier, 4, '0', STR_PAD_LEFT) ?>
                                </small>

                            </td>


                            <td class="py-4 border-0">

                                <?php if (!empty($s->no_hp)) : ?>

                                    <span class="px-3 py-2 rounded-pill bg-light border small">
                                        <?= htmlspecialchars($s->no_hp, ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                <?php else : ?>

                                    <span class="text-muted small">
                                        Tidak tersedia
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td class="py-4 border-0 text-muted small" style="max-width:220px;">
                                <div class="text-truncate">
                                    <?= htmlspecialchars($s->alamat ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>


                            <td class="py-4 border-0 text-muted small" style="max-width:200px;">
                                <div class="text-truncate">
                                    <?= htmlspecialchars($s->keterangan ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>


                            <td class="py-4 pe-4 border-0 text-end">

                                <div class="d-inline-flex gap-3">

                                    <a href="<?= base_url('supplier/edit/'.$s->id_supplier) ?>"
                                       class="text-decoration-none fw-semibold text-primary small">
                                        Edit
                                    </a>

                                    <a href="<?= base_url('supplier/hapus/'.$s->id_supplier) ?>"
                                       onclick="return confirm('Yakin hapus supplier ini?')"
                                       class="text-decoration-none fw-semibold text-danger small">
                                        Hapus
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else : ?>

                    <tr>

                        <td colspan="5" class="text-center py-5 border-0">

                            <div class="fw-semibold mb-2">
                                Belum ada supplier
                            </div>

                            <div class="text-muted small mb-4">
                                Tambahkan supplier untuk mulai mencatat pembelian
                            </div>

                            <a href="<?= base_url('supplier/tambah') ?>"
                               class="btn btn-dark rounded-pill px-4">
                                Tambah Supplier
                            </a>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>