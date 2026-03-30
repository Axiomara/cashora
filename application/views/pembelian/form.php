<div id="main" class="p-3 p-md-4">

  <!-- MOBILE SIDEBAR BUTTON -->
  <header class="mb-3 d-xl-none">
    <a href="#" class="burger-btn d-block text-dark">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <style>
    .table td, .table th {
      vertical-align: middle;
    }

    .subtotal {
      font-weight: 600;
      background: #f8f9fa;
    }

    .isi_karton:disabled {
      background-color: #f1f3f5;
      opacity: .6;
    }

    .section-gap {
      padding-top: 20px;
    }
  </style>

  <!-- PAGE HEADING -->
  <div class="row align-items-center justify-content-between mb-4">
    <div class="col">
      <h3 class="fw-bold mb-1">Pembelian Barang</h3>
      <p class="text-muted small mb-0">Input pembelian stok dari supplier</p>
    </div>
    <div class="col-auto">
      <a href="<?= base_url('pembelian') ?>" class="btn btn-light border">
        Kembali
      </a>
    </div>
  </div>

  <form method="post" action="<?= base_url('pembelian/simpan') ?>" id="formPembelian">

    <div class="row g-4">

      <!-- LEFT SIDE -->
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">


          <?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


            <!-- Supplier & Tanggal -->
            <div class="row mb-4">

              <div class="col-md-6">
                <label class="form-label fw-semibold">Supplier</label>
                <select name="id_supplier" class="form-select" required>
                  <option value="">-- Pilih Supplier --</option>
                  <?php foreach ($supplier as $s): ?>
                    <option value="<?= $s->id_supplier ?>">
                      <?= $s->nama_supplier ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="datetime-local"
                       name="tanggal"
                       class="form-control"
                       value="<?= date('Y-m-d\TH:i') ?>"
                       required>
              </div>

            </div>

            <hr>

            <h6 class="fw-bold mb-3">Daftar Barang</h6>

            <div class="table-responsive">
              <table class="table table-bordered align-middle">

                <thead class="table-light">
                  <tr>
                    <th>Barang</th>
                    <th width="90">Qty</th>
                    <th width="110">Satuan</th>
                    <th width="120">Isi/Karton</th>
                    <th width="150">Harga Input</th>
                    <th width="150">Subtotal</th>
                    <th width="60"></th>
                  </tr>
                </thead>

                <tbody id="tablePembelian">

                  <tr>

                    <td>
                      <select name="items[0][id_barang]" class="form-select barangSelect" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang as $b): ?>
                          <option value="<?= $b->id_barang ?>"
                                  data-isi="<?= $b->isi_karton ?? 1 ?>">
                            <?= $b->kode_barang ?> - <?= $b->nama_barang ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </td>

                    <td>
                      <input type="number"
                             name="items[0][qty_input]"
                             class="form-control qty text-center"
                             min="1"
                             value="1">
                    </td>

                    <td>
                      <select name="items[0][satuan]" class="form-select satuan">
                        <option value="pcs">PCS</option>
                        <option value="karton">KARTON</option>
                      </select>
                    </td>

                    <td>
                      <input type="number"
                             name="items[0][isi_karton]"
                             class="form-control isi_karton text-center"
                             min="1"
                             value="1"
                             disabled>
                    </td>

                    <td>
                      <input type="number"
                             name="items[0][harga_input]"
                             class="form-control harga text-end"
                             min="0"
                             value="0">
                    </td>

                    <td>
                      <input type="text"
                             class="form-control subtotal text-end"
                             readonly
                             value="Rp0">
                    </td>

                    <td class="text-center">
                      <button type="button"
                              class="btn btn-sm btn-danger"
                              onclick="hapusRow(this)">
                        ×
                      </button>
                    </td>

                  </tr>

                </tbody>

              </table>
            </div>

            <button type="button"
                    class="btn btn-outline-primary btn-sm mt-2"
                    onclick="tambahRow()">
              Tambah Barang
            </button>

          </div>
        </div>
      </div>


      <!-- RIGHT SIDE -->
      <div class="col-lg-4">

        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

            <h6 class="fw-bold mb-4">Ringkasan Pembelian</h6>

            <div class="mb-3">
              <label class="form-label">Total Pembelian</label>

              <input type="text"
                     id="totalPembelian"
                     class="form-control form-control-lg fw-bold text-end"
                     readonly
                     value="Rp0">
            </div>

            <input type="hidden" name="total" id="totalInput">

            <button type="submit"
                    class="btn btn-success w-100 fw-bold mt-3">
              Simpan Pembelian
            </button>

          </div>
        </div>

      </div>

    </div>

  </form>


  <!-- RIWAYAT PEMBELIAN -->
  <div class="section-gap">
    <?php $this->load->view('pembelian/index'); ?>
  </div>

</div>


<script>

let rowIndex = 1;

function formatRupiah(number){
  number = parseInt(number) || 0;
  return "Rp" + number.toLocaleString("id-ID");
}

function hitungRow(row){

  const qty = parseInt(row.querySelector(".qty")?.value) || 0;
  const harga = parseInt(row.querySelector(".harga")?.value) || 0;

  const subtotal = qty * harga;

  row.querySelector(".subtotal").value = formatRupiah(subtotal);

  return subtotal;
}

function hitungSemua(){

  let total = 0;

  document.querySelectorAll("#tablePembelian tr").forEach(row=>{
      total += hitungRow(row);
  });

  document.getElementById("totalPembelian").value = formatRupiah(total);
  document.getElementById("totalInput").value = total;
}

function tambahRow(){

  const table = document.getElementById("tablePembelian");

  const row = document.createElement("tr");

  row.innerHTML = `
  <td>
    <select name="items[${rowIndex}][id_barang]" class="form-select barangSelect" required>
      <option value="">-- Pilih Barang --</option>
      <?php foreach ($barang as $b): ?>
        <option value="<?= $b->id_barang ?>" data-isi="<?= $b->isi_karton ?? 1 ?>">
          <?= $b->kode_barang ?> - <?= $b->nama_barang ?>
        </option>
      <?php endforeach; ?>
    </select>
  </td>

  <td>
    <input type="number"
           name="items[${rowIndex}][qty_input]"
           class="form-control qty text-center"
           min="1"
           value="1">
  </td>

  <td>
    <select name="items[${rowIndex}][satuan]" class="form-select satuan">
      <option value="pcs">PCS</option>
      <option value="karton">KARTON</option>
    </select>
  </td>

  <td>
    <input type="number"
           name="items[${rowIndex}][isi_karton]"
           class="form-control isi_karton text-center"
           min="1"
           value="1"
           disabled>
  </td>

  <td>
    <input type="number"
           name="items[${rowIndex}][harga_input]"
           class="form-control harga text-end"
           min="0"
           value="0">
  </td>

  <td>
    <input type="text"
           class="form-control subtotal text-end"
           readonly
           value="Rp0">
  </td>

  <td class="text-center">
    <button type="button"
            class="btn btn-sm btn-danger"
            onclick="hapusRow(this)">
      ×
    </button>
  </td>
  `;

  table.appendChild(row);
  rowIndex++;

  hitungSemua();
}

function hapusRow(btn){
  btn.closest("tr").remove();
  hitungSemua();
}

document.getElementById("tablePembelian").addEventListener("input",function(e){

  if(e.target.classList.contains("qty") || e.target.classList.contains("harga")){
    hitungSemua();
  }

});

document.getElementById("tablePembelian").addEventListener("change",function(e){

  const row = e.target.closest("tr");

  if(e.target.classList.contains("satuan")){

    const isiInput = row.querySelector(".isi_karton");

    if(e.target.value === "karton"){
      isiInput.disabled = false;
    }else{
      isiInput.disabled = true;
      isiInput.value = 1;
    }

  }

  if(e.target.classList.contains("barangSelect")){

    const selected = e.target.options[e.target.selectedIndex];
    const isi = selected.getAttribute("data-isi") || 1;

    const isiInput = row.querySelector(".isi_karton");
    isiInput.value = isi;
  }

});

document.addEventListener("DOMContentLoaded",function(){
  hitungSemua();
});

</script>