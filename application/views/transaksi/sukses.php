<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Berhasil</title>

  <!-- Bootstrap CSS (kalau sudah ada di layout utama, ini boleh dihapus) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

  <!-- Modal -->
  <div class="modal fade" id="modalSukses" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title">Transaksi Berhasil ✅</h5>
        </div>

        <div class="modal-body pt-2">
          <p class="text-muted mb-0">
            Data transaksi sudah tersimpan dan keranjang otomatis dikosongkan.
          </p>
        </div>

        <div class="modal-footer border-0 d-flex gap-2">
          <a href="<?= base_url('transaksi/nota-pdf/' . $id_transaksi) ?>" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Download Nota PDF
          </a>

          <a href="<?= base_url('transaksi') ?>" class="btn btn-light">
            <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
          </a>
        </div>

      </div>
    </div>
  </div>

  <script>
    // ✅ Clear cart setelah sukses
    localStorage.removeItem("kasir_cart");
    localStorage.removeItem("kasir_bayar");
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // ✅ Auto show modal saat halaman dibuka
    document.addEventListener("DOMContentLoaded", function () {
      const modalEl = document.getElementById("modalSukses");
      const modal = new bootstrap.Modal(modalEl, {
        backdrop: "static",  // tidak bisa klik luar untuk close
        keyboard: false      // tidak bisa ESC untuk close
      });
      modal.show();
    });
  </script>

</body>
</html>
