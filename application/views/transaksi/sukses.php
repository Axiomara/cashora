<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Transaksi Berhasil</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">

<div class="modal fade" id="modalSukses" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-success">
          <i class="bi bi-check-circle-fill me-1"></i>
          Transaksi Berhasil
        </h5>
      </div>

      <div class="modal-body pt-2">

        <p class="text-muted mb-2">
          Transaksi berhasil disimpan ke database.
        </p>

        <p class="text-muted small mb-0">
          Keranjang otomatis dikosongkan.
        </p>

      </div>

      <div class="modal-footer border-0 d-flex gap-2">

        <button onclick="printNota()" class="btn btn-primary">
          <i class="bi bi-printer me-1"></i>
          Print Nota
        </button>

        <a href="<?= base_url('transaksi/nota_pdf/' . $id_transaksi) ?>" target="_blank" class="btn btn-danger">
          <i class="bi bi-file-earmark-pdf me-1"></i>
          Download PDF
        </a>

        <a href="<?= base_url('transaksi') ?>" class="btn btn-light">
          <i class="bi bi-plus-circle me-1"></i>
          Transaksi Baru
        </a>

      </div>

    </div>
  </div>
</div>


<script>

function printNota()
{
    const url = "<?= base_url('transaksi/nota_pdf/' . $id_transaksi) ?>";

    const win = window.open(url, "_blank");

    if(win){
        setTimeout(function(){
            win.print();
        },700);
    }
}


// hapus keranjang setelah transaksi
localStorage.removeItem("kasir_cart");
localStorage.removeItem("kasir_bayar");


document.addEventListener("DOMContentLoaded", function () {

    const modalEl = document.getElementById("modalSukses");

    const modal = new bootstrap.Modal(modalEl, {
        backdrop: "static",
        keyboard: false
    });

    modal.show();

    // auto print setelah 1 detik
    setTimeout(function(){
        printNota();
    },1000);

});

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>