<style>
  .card-clean {
    border: 1px solid #e9ecef;
    border-radius: 14px;
  }

  .table {
    font-size: 14px;
  }

  .table thead th {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #495057;
    background-color: #f8f9fa;
    padding: 14px 16px;
  }

  .table td {
    padding: 14px 16px;
    vertical-align: middle;
  }

  .detail-row {
    background-color: #f8f9fa;
    display: none; /* 🔥 hidden default */
  }

  .total-amount {
    font-weight: 600;
    color: #198754;
    font-size: 15px;
  }

  .btn-detail {
    font-size: 13px;
    padding: 6px 12px;
  }

  .detail-table thead th {
    font-size: 13px;
    padding: 10px 14px;
  }

  .detail-table td {
    padding: 10px 14px;
  }
</style>

<div class="card card-clean shadow-sm">

  <div class="card-body p-4">

    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h6 class="fw-bold mb-1">Riwayat Pembelian</h6>
        <small class="text-muted">Data transaksi yang sudah disimpan</small>
      </div>
    </div>

    <div class="table-responsive mt-3">

      <table class="table align-middle">

        <thead>
          <tr>
            <th>Kode</th>
            <th>Supplier</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th width="100" class="text-center">Detail</th>
          </tr>
        </thead>

        <tbody>

        <?php if (!empty($pembelian)) : ?>
          <?php foreach ($pembelian as $p) : ?>

            <!-- HEADER -->
            <tr>
              <td class="fw-semibold"><?= $p->kode_pembelian ?></td>
              <td><?= $p->nama_supplier ?></td>
              <td><?= date('d M Y H:i', strtotime($p->tanggal)) ?></td>

              <td class="total-amount">
                Rp<?= number_format($p->total, 0, ',', '.') ?>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-secondary btn-detail"
                        onclick="toggleDetail('<?= $p->id_pembelian ?>')">
                  Lihat
                </button>
              </td>
            </tr>

            <!-- DETAIL -->
            <tr class="detail-row" id="detail<?= $p->id_pembelian ?>">
              <td colspan="5">

                <div class="p-3">

                  <table class="table detail-table mb-0">

                    <thead>
                      <tr>
                        <th>Barang</th>
                        <th width="100">Qty</th>
                        <th width="110">Satuan</th>
                        <th width="140">Harga</th>
                        <th width="160">Subtotal</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php foreach ($p->detail as $d) : ?>
                        <tr>
                          <td><?= $d->kode_barang ?> - <?= $d->nama_barang ?></td>
                          <td><?= $d->qty_input ?></td>
                          <td><?= strtoupper($d->satuan) ?></td>
                          <td>Rp<?= number_format($d->harga_input, 0, ',', '.') ?></td>
                          <td class="fw-semibold">
                            Rp<?= number_format($d->subtotal, 0, ',', '.') ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>

                    <tfoot>
                      <tr>
                        <td colspan="4" class="text-end fw-bold">
                          Total Pembelian
                        </td>
                        <td class="fw-bold text-success">
                          Rp<?= number_format($p->total, 0, ',', '.') ?>
                        </td>
                      </tr>
                    </tfoot>

                  </table>

                </div>

              </td>
            </tr>

          <?php endforeach; ?>
        <?php else : ?>

          <tr>
            <td colspan="5" class="text-center py-4 text-muted">
              Belum ada data pembelian
            </td>
          </tr>

        <?php endif; ?>

        </tbody>

      </table>

    </div>

  </div>

</div>

<!-- 🔥 JS TANPA DELAY -->
<script>
function toggleDetail(id) {
    const row = document.getElementById('detail' + id);

    if (row.style.display === 'table-row') {
        row.style.display = 'none';
    } else {
        row.style.display = 'table-row';
    }
}
</script>