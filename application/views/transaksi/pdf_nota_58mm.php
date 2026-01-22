<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Nota Kasir</title>
  <style>
    body {
      font-family: monospace;
      font-size: 10px;
      margin: 0;
      padding: 0;
      color: #000;
    }

    .nota {
      width: 58mm;
      padding: 6px;
    }

    .center { text-align: center; }
    .right { text-align: right; }
    .small { font-size: 9px; }

    .line {
      border-top: 1px dashed #000;
      margin: 6px 0;
    }

    .title {
      font-weight: bold;
      font-size: 12px;
      margin-bottom: 2px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 2px 0;
      vertical-align: top;
      word-break: break-word;
    }

    .item-name {
      width: 70%;
    }

    .item-subtotal {
      width: 30%;
      text-align: right;
      white-space: nowrap;
    }

    .sum-label {
      width: 50%;
    }

    .sum-value {
      width: 50%;
      text-align: right;
      white-space: nowrap;
    }
  </style>
</head>
<body>

<div class="nota">

  <div class="center">
    <div class="title">KIOS DAMI</div>
    <div class="small">Jl. Contoh No.1</div>
    <div class="small">Telp: 08xxxxxxxxxx</div>
  </div>

  <div class="line"></div>

  <table>
    <tr>
      <td class="small">Kode</td>
      <td class="small right">: <?= $transaksi->kode_transaksi ?></td>
    </tr>
    <tr>
      <td class="small">Tanggal</td>
      <td class="small right">: <?= date('d-m-Y H:i', strtotime($transaksi->tanggal)) ?></td>
    </tr>
  </table>

  <div class="line"></div>

  <!-- LIST BARANG -->
  <table>
    <?php foreach ($detail as $d) : ?>
      <tr>
        <td class="item-name">
          <?= $d->nama_barang ?><br>
          <span class="small"><?= $d->qty ?> x Rp<?= number_format($d->harga, 0, ',', '.') ?></span>
        </td>
        <td class="item-subtotal">
          Rp<?= number_format($d->subtotal, 0, ',', '.') ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="line"></div>

  <!-- TOTAL -->
  <table>
    <tr>
      <td class="sum-label">Total</td>
      <td class="sum-value">Rp<?= number_format($transaksi->total, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td class="sum-label">Bayar</td>
      <td class="sum-value">Rp<?= number_format($transaksi->bayar, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td class="sum-label">Kembali</td>
      <td class="sum-value">Rp<?= number_format($transaksi->kembalian, 0, ',', '.') ?></td>
    </tr>
  </table>

  <div class="line"></div>

  <div class="center small">
    Terima kasih 🙏<br>
    Barang yang sudah dibeli<br>
    tidak dapat dikembalikan
  </div>

</div>

</body>
</html>

<script>
  localStorage.removeItem("kasir_cart");
  localStorage.removeItem("kasir_bayar");
</script>
