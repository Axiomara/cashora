<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
:root {
    --neutral-50: #f8fafc;
    --neutral-200: #e2e8f0;
    --neutral-500: #64748b;
    --neutral-900: #0f172a;
    --primary-color: #435ebe;
}

/* Card */
.modern-card {
    border: 1px solid var(--neutral-200);
    background: #ffffff;
}

/* Header */
.table-header-area {
    padding: 1.5rem;
    border-bottom: 1px solid var(--neutral-200);
}

/* Table */
.table-modern {
    margin-bottom: 0;
}

.table-modern thead th {
    background: var(--neutral-50);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--neutral-500);
    border-bottom: 1px solid var(--neutral-200);
    padding: 0.85rem 1rem;
    white-space: nowrap;
}

.table-modern tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--neutral-200);
    font-size: 0.9rem;
}

.table-modern tbody tr:hover {
    background: #fcfdff;
}

/* SKU */
.sku-text {
    font-family: monospace;
    font-size: 0.8rem;
    background: #eef2ff;
    color: var(--primary-color);
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 600;
}

/* Price */
.price-text {
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--neutral-900);
}

/* Stock Badge */
.stock-badge {
    padding: 0.45rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.stock-danger { background: #fff1f2; color: #e11d48; }
.stock-warning { background: #fffbeb; color: #d97706; }
.stock-success { background: #f0fdf4; color: #16a34a; }

.stock-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

/* Search */
.search-input {
    border-radius: 0.6rem;
}

/* Sort Link */
.sort-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: 0.2s;
}

.sort-link:hover {
    color: var(--neutral-900) !important;
}

/* Mobile Adjust */
@media (max-width: 768px) {
    .table-header-area {
        padding: 1rem;
    }

    .table-modern thead {
        display: none;
    }

    .table-modern tbody tr {
        display: block;
        padding: 1rem;
        border-bottom: 1px solid var(--neutral-200);
    }

    .table-modern tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border: none;
        font-size: 0.85rem;
    }

    .table-modern tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--neutral-500);
    }
}
</style>

<?php
$currentSort  = $sort ?? '';
$currentOrder = $order ?? 'asc';

function sort_link($field, $label, $currentSort, $currentOrder) {
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

    return '<a href="'.$url.'" class="text-decoration-none text-muted sort-link">
                '.$label.' '.$icon.'
            </a>';
}
?>

<div class="card modern-card shadow-sm rounded-4">

    <!-- HEADER -->
    <div class="table-header-area">
        <div class="row align-items-center g-3">

            <div class="col-md-4">
                <h5 class="fw-bold mb-1">Daftar Produk</h5>
                <p class="text-muted small mb-0">Kelola inventaris dan stok barang</p>
            </div>

            <div class="col-md-8">
                <form method="get" action="<?= base_url('barang') ?>"
                      class="d-flex flex-wrap gap-2 justify-content-md-end">

                    <input type="text"
                           name="keyword"
                           value="<?= htmlspecialchars($keyword ?? '') ?>"
                           class="form-control form-control-sm search-input"
                           placeholder="Cari SKU / Nama..."
                           style="max-width:220px;">

                    <select name="filter"
                            class="form-select form-select-sm search-input"
                            style="max-width:160px;">
                        <option value="">Semua Stok</option>
                        <option value="low" <?= ($filter ?? '') === 'low' ? 'selected' : '' ?>>Stok Menipis</option>
                        <option value="safe" <?= ($filter ?? '') === 'safe' ? 'selected' : '' ?>>Stok Aman</option>
                    </select>

                    <button class="btn btn-sm btn-dark px-3">
                        <i class="bi bi-search"></i>
                    </button>

                    <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>

                    <span class="badge bg-light text-secondary border px-3 d-flex align-items-center">
                        <?= count($list_barang ?? []) ?> Item
                    </span>

                </form>
            </div>

        </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-modern align-middle">

            <thead>
                <tr>
                    <th><?= sort_link('kode_barang','Kode SKU',$currentSort,$currentOrder) ?></th>
                    <th><?= sort_link('nama_barang','Nama Produk',$currentSort,$currentOrder) ?></th>
                    <th class="text-center"><?= sort_link('stok','Stok',$currentSort,$currentOrder) ?></th>
                    <th class="text-end"><?= sort_link('harga_jual','Harga',$currentSort,$currentOrder) ?></th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($list_barang)) : ?>
                    <?php foreach ($list_barang as $b) : ?>
                        <tr>

                            <td data-label="Kode">
                                <span class="sku-text"><?= htmlspecialchars($b->kode_barang) ?></span>
                            </td>

                            <td data-label="Nama">
                                <div class="fw-semibold"><?= htmlspecialchars($b->nama_barang) ?></div>
                                <small class="text-muted">Update <?= date('d/m/Y') ?></small>
                            </td>

                            <td data-label="Stok" class="text-center">
                                <?php if ($b->stok <= 3): ?>
                                    <span class="stock-badge stock-danger">
                                        <span class="stock-dot bg-danger"></span>
                                        <?= $b->stok ?> Kritis
                                    </span>
                                <?php elseif ($b->stok <= 5): ?>
                                    <span class="stock-badge stock-warning">
                                        <span class="stock-dot bg-warning"></span>
                                        <?= $b->stok ?> Terbatas
                                    </span>
                                <?php else: ?>
                                    <span class="stock-badge stock-success">
                                        <span class="stock-dot bg-success"></span>
                                        <?= $b->stok ?> Tersedia
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td data-label="Harga" class="text-end">
                                <span class="price-text">
                                    Rp<?= number_format($b->harga_jual,0,',','.') ?>
                                </span>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-25"></i>
                            Tidak ada data produk ditemukan
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>