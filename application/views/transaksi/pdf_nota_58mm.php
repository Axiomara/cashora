<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Nota Kasir</title>

  <style>
    /* ukuran kertas Photo L */
    @page {
      size: 89mm 127mm;
      margin: 0;
    }

    body {
      font-family: monospace;
      font-size: 10px;
      margin: 0;
      padding: 0;
      color: #000;
    }

    /* area nota di tengah kertas */
    .nota {
      width: 60mm;
      margin: auto;
      padding-top: 8mm;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .small {
      font-size: 9px;
    }

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


    /* PRINT MODE */

    @media print {

      body {
        width: 89mm;
        height: 127mm;
      }

      .nota {
        width: 60mm;
        padding-top: 8mm;
      }

    }
  </style>

</head>

<body>

  <div class="nota">

    <!-- HEADER TOKO -->

    <div class="center">

      <div class="title">KIOS DAMI</div>

      <div class="small">
        Jl. Contoh No.1
      </div>

      <div class="small">
        Telp: 08xxxxxxxxxx
      </div>

    </div>

    <div class="line"></div>


    <!-- INFO TRANSAKSI -->

    <table>

      <tr>
        <td class="small">Kode</td>
        <td class="small right">
          : <?= $transaksi->kode_transaksi ?>
        </td>
      </tr>

      <tr>
        <td class="small">Tanggal</td>
        <td class="small right">
          : <?= date('d-m-Y H:i', strtotime($transaksi->tanggal)) ?>
        </td>
      </tr>

    </table>

    <div class="line"></div>


    <!-- LIST BARANG -->

    <table>

      <?php foreach ($detail as $d) : ?>

        <tr>

          <td class="item-name">

            <?= $d->nama_barang ?><br>

            <span class="small">
              <?= $d->qty ?> x <?= format_rupiah($d->harga) ?>
            </span>

          </td>

          <td class="item-subtotal">
            <?= format_rupiah($d->subtotal) ?>
          </td>

        </tr>

      <?php endforeach; ?>

    </table>

    <div class="line"></div>


    <!-- RINGKASAN PEMBAYARAN -->

    <table>

      <tr>
        <td class="sum-label">Total</td>
        <td class="sum-value">
          <?= format_rupiah($transaksi->total) ?>
        </td>
      </tr>

      <tr>
        <td class="sum-label">Bayar</td>
        <td class="sum-value">
          <?= format_rupiah($transaksi->bayar) ?>
        </td>
      </tr>

      <tr>
        <td class="sum-label">Kembali</td>
        <td class="sum-value">
          <?= format_rupiah($transaksi->kembalian) ?>
        </td>
      </tr>

      <tr>
        <td class="sum-label">Metode</td>
        <td class="sum-value">
          <?= strtoupper($transaksi->metode_bayar ?? 'CASH') ?>
        </td>
      </tr>

    </table>

    <div class="line"></div>


    <!-- FOOTER -->

    <div class="center small">

      Terima kasih 🙏 <br>

      Barang yang sudah dibeli <br>

      tidak dapat dikembalikan

    </div>

  </div>


  <script>
    /* reset keranjang setelah transaksi */

    localStorage.removeItem("kasir_cart");
    localStorage.removeItem("kasir_bayar");


    /* auto print */

    window.onload = function() {
      window.print();
    }
  </script>

</body>

</html>