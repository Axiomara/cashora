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
            <h4 class="fw-bold mb-1">Riwayat Pergerakan Stok</h4>
            <p class="text-muted small mb-0">
                Mencatat semua perubahan stok barang (pembelian, penjualan, retur, dll)
            </p>
        </div>

    </div>


    <!-- ALERT -->
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle me-2"></i>
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <!-- CARD -->
    <div class="bg-white border rounded-4 shadow-sm overflow-hidden">


        <!-- HEADER -->
        <div class="px-4 py-4 border-bottom bg-light bg-opacity-50">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h6 class="fw-semibold mb-1">Log Pergerakan Stok</h6>
                    <small class="text-muted">
                        Riwayat perubahan stok barang pada sistem
                    </small>
                </div>

                <span class="badge bg-light text-dark border px-3 py-2">
                    <?= count($stok_log) ?> Data
                </span>

            </div>

        </div>


        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="bg-light bg-opacity-25">

                    <tr class="text-uppercase small text-muted" style="letter-spacing:.05em;">
                        <th class="ps-4 py-3 border-0">Tanggal</th>
                        <th class="py-3 border-0">Barang</th>
                        <th class="py-3 border-0">Tipe</th>
                        <th class="py-3 border-0">Qty</th>
                        <th class="py-3 border-0">Stok Sebelum</th>
                        <th class="py-3 border-0">Stok Sesudah</th>
                        <th class="pe-4 py-3 border-0">Referensi</th>
                    </tr>

                </thead>


                <tbody>

                <?php if (!empty($stok_log)) : ?>

                    <?php foreach ($stok_log as $log) : ?>

                        <tr class="border-top">

                            <td class="ps-4 py-3 border-0 small text-muted">
                                <?= date('d-m-Y H:i', strtotime($log->created_at)) ?>
                            </td>


                            <td class="py-3 border-0 fw-semibold">
                                <?= htmlspecialchars($log->nama_barang, ENT_QUOTES, 'UTF-8') ?>
                            </td>


                            <td class="py-3 border-0">

                                <?php if ($log->tipe == 'penjualan'): ?>
                                    <span class="badge bg-danger">Penjualan</span>

                                <?php elseif ($log->tipe == 'pembelian'): ?>
                                    <span class="badge bg-success">Pembelian</span>

                                <?php elseif ($log->tipe == 'retur'): ?>
                                    <span class="badge bg-warning text-dark">Retur</span>

                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?= ucfirst($log->tipe) ?>
                                    </span>
                                <?php endif; ?>

                            </td>


                            <td class="py-3 border-0">

                                <?php if ($log->qty > 0): ?>
                                    <span class="text-success fw-semibold">
                                        +<?= $log->qty ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-danger fw-semibold">
                                        <?= $log->qty ?>
                                    </span>
                                <?php endif; ?>

                            </td>


                            <td class="py-3 border-0 small text-muted">
                                <?= $log->stok_sebelum ?>
                            </td>


                            <td class="py-3 border-0 small text-muted">
                                <?= $log->stok_sesudah ?>
                            </td>


                            <td class="py-3 pe-4 border-0 small text-muted">
                                <?= $log->referensi ?? '-' ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>


                <?php else : ?>

                    <tr>

                        <td colspan="7" class="text-center py-5 border-0">

                            <div class="fw-semibold mb-2">
                                Belum ada riwayat stok
                            </div>

                            <div class="text-muted small">
                                Pergerakan stok akan tercatat setelah terjadi transaksi
                            </div>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>