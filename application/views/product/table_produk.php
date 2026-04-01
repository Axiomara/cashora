<!-- Dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --neutral-50: #f8fafc;
        --neutral-200: #e2e8f0;
        --neutral-500: #64748b;
        --neutral-900: #0f172a;
        --primary-color: #435ebe;
        --primary-light: #eef2ff;
    }

    /* Card Styling */
    .modern-card {
        border: 1px solid var(--neutral-200);
        background: #ffffff;
        overflow: hidden;
    }

    .table-header-area {
        padding: 1.5rem;
        border-bottom: 1px solid var(--neutral-200);
    }

    /* Table Design */
    .table-modern {
        margin-bottom: 0;
        width: 100%;
    }

    .table-modern thead th {
        background: var(--neutral-50);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--neutral-500);
        border-bottom: 1px solid var(--neutral-200);
        padding: 1rem;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--neutral-200);
        font-size: 0.9rem;
        color: var(--neutral-900);
    }

    .table-modern tbody tr:hover {
        background: #fcfdff;
    }

    /* Elements */
    .sku-text {
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 0.75rem;
        background: var(--primary-light);
        color: var(--primary-color);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .price-text {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--neutral-900);
    }

    .stock-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .stock-danger {
        background: #fff1f2;
        color: #e11d48;
    }

    .stock-warning {
        background: #fffbeb;
        color: #d97706;
    }

    .stock-success {
        background: #f0fdf4;
        color: #16a34a;
    }

    .stock-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .sort-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
        color: var(--neutral-500) !important;
    }

    .sort-link:hover {
        color: var(--primary-color) !important;
    }

    /* Mobile Responsive Engine */
    @media (max-width: 768px) {
        .table-header-area {
            padding: 1.25rem;
        }

        .table-modern thead {
            display: none;
        }

        .table-modern tbody tr {
            display: block;
            padding: 1rem;
            border-bottom: 8px solid var(--neutral-50);
        }

        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border: none;
            text-align: right !important;
        }

        .table-modern tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--neutral-500);
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-right: 1rem;
        }

        .table-modern tbody td.text-center,
        .table-modern tbody td.text-end {
            text-align: right !important;
        }

        .detail-row td {
            display: block;
            text-align: left !important;
        }

        .detail-row td::before {
            display: none;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<?php
$currentSort  = $sort ?? '';
$currentOrder = $order ?? 'asc';

if (!function_exists('sort_link')) {
    function sort_link($field, $label, $currentSort, $currentOrder)
    {
        $order = ($currentSort === $field && $currentOrder === 'asc') ? 'desc' : 'asc';
        $icon = '<i class="bi bi-arrow-down-up opacity-25"></i>';

        if ($currentSort === $field) {
            $icon = $currentOrder === 'asc'
                ? '<i class="bi bi-sort-up-alt text-primary"></i>'
                : '<i class="bi bi-sort-down text-primary"></i>';
        }

        $query = $_GET;
        $query['sort']  = $field;
        $query['order'] = $order;
        $url = base_url('barang?' . http_build_query($query));

        return '<a href="' . $url . '" class="text-decoration-none sort-link">
                    ' . $label . ' ' . $icon . '
                </a>';
    }
}
?>

<div class="card modern-card shadow-sm rounded-4">
    <!-- HEADER -->
    <div class="table-header-area">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Daftar Produk</h5>
                <small class="text-muted">Kelola inventaris barang secara real-time</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    <i class="bi bi-box-seam me-1 text-primary"></i>
                    <?= count($list_barang ?? []) ?> Item
                </span>
            </div>
        </div>

        <!-- SEARCH AREA -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-semibold mb-0 text-dark">Pencarian Cepat</h6>
                <small class="text-muted">Cari nama atau kode produk</small>
            </div>
            <form method="get" class="d-flex gap-2" style="max-width:300px; width:100%;">
                <input type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword ?? '') ?>"
                    class="form-control form-control-sm border-2 shadow-sm"
                    placeholder="Cari produk...">
                <button type="submit" class="btn btn-dark btn-sm px-3">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- TABLE AREA -->
    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th><?= sort_link('kode_barang', 'Kode', $currentSort, $currentOrder) ?></th>
                    <th><?= sort_link('nama_barang', 'Produk', $currentSort, $currentOrder) ?></th>
                    <th class="text-center"><?= sort_link('stok', 'Stok', $currentSort, $currentOrder) ?></th>
                    <th class="text-end"><?= sort_link('harga_jual', 'Harga', $currentSort, $currentOrder) ?></th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($list_barang)) : ?>
                    <?php foreach ($list_barang as $b) : ?>
                        <tr>
                            <td data-label="Kode">
                                <span class="sku-text"><?= htmlspecialchars($b->kode_barang) ?></span>
                            </td>
                            <td data-label="Produk">
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($b->nama_barang) ?></div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Updated: <?= isset($b->updated_at) ? date('d M Y', strtotime($b->updated_at)) : '-' ?>
                                </small>
                            </td>
                            <td data-label="Stok" class="text-center">
                                <?php if ($b->stok <= 3): ?>
                                    <span class="stock-badge stock-danger">
                                        <span class="stock-dot bg-danger"></span><?= $b->stok ?>
                                    </span>
                                <?php elseif ($b->stok <= 5): ?>
                                    <span class="stock-badge stock-warning">
                                        <span class="stock-dot bg-warning"></span><?= $b->stok ?>
                                    </span>
                                <?php else: ?>
                                    <span class="stock-badge stock-success">
                                        <span class="stock-dot bg-success"></span><?= $b->stok ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Harga" class="text-end">
                                <span class="price-text"><?= rupiah($b->harga_jual) ?></span>
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-light border" onclick="toggleDetail('<?= $b->id_barang ?>')" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="<?= base_url('barang/edit/' . $b->id_barang) ?>" class="btn btn-sm btn-light border" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('barang/delete/' . $b->id_barang) ?>"
                                        onclick="return confirm('Hapus data produk ini?')"
                                        class="btn btn-sm btn-light border text-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- DETAIL ROW -->
                        <tr class="detail-row" id="detail<?= $b->id_barang ?>" style="display:none; background-color: var(--neutral-50);">
                            <td colspan="5">
                                <div class="p-4 border-top">
                                    <div class="row g-4 text-start">
                                        <div class="col-6 col-md-3">
                                            <div class="text-muted small mb-1">Harga Beli Terakhir</div>
                                            <div class="fw-bold text-dark"><?= rupiah($b->harga_beli_terakhir ?? 0) ?></div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-muted small mb-1">Isi per Karton</div>
                                            <div class="fw-bold text-dark">
                                                <?php if (!empty($b->isi_karton) && $b->isi_karton > 1): ?>
                                                    <?= $b->isi_karton ?> pcs / karton
                                                <?php else: ?>
                                                    Eceran (<?= isset($b->stok) ? $b->stok : 0 ?> pcs)
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-muted small mb-1">Supplier</div>
                                            <div class="fw-bold text-dark"><?= $b->nama_supplier ?? '-' ?></div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-muted small mb-1">Waktu Update</div>
                                            <div class="fw-bold text-dark">
                                                <?= isset($b->updated_at) ? date('d/m/Y H:i', strtotime($b->updated_at)) : '-' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada data produk ditemukan
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <?php if (isset($pagination)): ?>
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light-subtle">
                <div class="small text-muted d-none d-md-block">Menampilkan data inventaris</div>
                <div><?= $pagination ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function toggleDetail(id) {
        const targetRow = document.getElementById('detail' + id);
        const isVisible = targetRow.style.display === 'table-row';

        // Sembunyikan detail lainnya yang mungkin sedang terbuka
        document.querySelectorAll('.detail-row').forEach(row => {
            row.style.display = 'none';
        });

        // Toggle tampilan row yang diklik
        if (!isVisible) {
            targetRow.style.display = 'table-row';
            targetRow.style.animation = 'fadeIn 0.3s ease';
        }
    }
</script>