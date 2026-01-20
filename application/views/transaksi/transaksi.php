<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <div class="page-heading mb-3">
    <h3 class="mb-1">Transaksi Kasir</h3>
    <p class="text-muted mb-0">Input transaksi penjualan kios</p>
  </div>

  <div class="page-content">
    <div class="row g-3">

      <!-- KIRI: INPUT BARANG -->
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="mb-0">Tambah Barang</h5>
            <small class="text-muted">Pilih barang lalu masukkan jumlah</small>
          </div>

          <div class="card-body px-4 pb-4 pt-2">
            <div class="mb-3">
              <label class="form-label">Pilih Barang</label>
              <select class="form-select" id="barangSelect">
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang as $b) : ?>
                  <option 
                    value="<?= $b->id_barang ?>"
                    data-kode="<?= $b->kode_barang ?>"
                    data-nama="<?= $b->nama_barang ?>"
                    data-harga="<?= $b->harga_jual ?>"
                    data-stok="<?= $b->stok ?>"
                  >
                    <?= $b->kode_barang ?> - <?= $b->nama_barang ?> (Rp<?= number_format($b->harga_jual, 0, ',', '.') ?> | Stok: <?= $b->stok ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label">Qty</label>
                <input type="number" class="form-control" id="qtyInput" value="1" min="1">
              </div>

              <div class="col-6">
                <label class="form-label">Harga</label>
                <input type="text" class="form-control" id="hargaInput" value="Rp0" readonly>
              </div>
            </div>

            <div class="d-grid mt-3">
              <button class="btn btn-primary" id="btnTambah">
                <i class="bi bi-plus-circle me-1"></i> Tambah ke Keranjang
              </button>
            </div>

            <div class="alert alert-light border mt-3 mb-0">
              <small class="text-muted">
                Tips: pilih barang → isi qty → klik tambah ✅
              </small>
            </div>
          </div>
        </div>
      </div>

      <!-- KANAN: KERANJANG + PEMBAYARAN -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-0 px-4 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0">Keranjang</h5>
              <small class="text-muted">Daftar barang yang dibeli</small>
            </div>
            <button class="btn btn-sm btn-light" id="btnReset">
              <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </button>
          </div>

          <div class="card-body px-4 pb-4 pt-2">
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr class="text-muted">
                    <th style="width: 35%;">Barang</th>
                    <th class="text-end">Harga</th>
                    <th class="text-center" style="width: 90px;">Qty</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end" style="width: 90px;">Aksi</th>
                  </tr>
                </thead>
                <tbody id="cartTable">
                  <tr id="cartEmpty">
                    <td colspan="5" class="text-center text-muted py-4">
                      Keranjang masih kosong
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <hr class="my-3">

            <!-- TOTAL -->
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Total Belanja</label>
                <input type="text" class="form-control form-control-lg fw-bold" id="totalBelanja" value="Rp0" readonly>
              </div>

              <div class="col-md-6">
                <label class="form-label">Jumlah Bayar</label>
                <input type="number" class="form-control form-control-lg" id="jumlahBayar" value="0" min="0">
              </div>

              <div class="col-md-6">
                <label class="form-label">Kembalian</label>
                <input type="text" class="form-control form-control-lg" id="kembalian" value="Rp0" readonly>
              </div>

              <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-success btn-lg w-100" id="btnSimpan">
                  <i class="bi bi-check-circle me-1"></i> Simpan Transaksi
                </button>
              </div>
            </div>

            <!-- DATA DIKIRIM KE SERVER -->
            <form id="formTransaksi" method="post" action="<?= base_url('transaksi/simpan') ?>">
              <input type="hidden" name="cart_json" id="cartJson">
              <input type="hidden" name="total" id="totalInput">
              <input type="hidden" name="bayar" id="bayarInput">
              <input type="hidden" name="kembalian" id="kembalianInput">
            </form>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // ===== Helpers =====
  function formatRupiah(angka) {
    angka = parseInt(angka || 0);
    return "Rp" + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // ===== Data Cart =====
  let cart = []; 
  // item: {id_barang, kode_barang, nama_barang, harga, qty, subtotal}

  const barangSelect = document.getElementById("barangSelect");
  const qtyInput = document.getElementById("qtyInput");
  const hargaInput = document.getElementById("hargaInput");

  const cartTable = document.getElementById("cartTable");
  const cartEmpty = document.getElementById("cartEmpty");

  const totalBelanja = document.getElementById("totalBelanja");
  const jumlahBayar = document.getElementById("jumlahBayar");
  const kembalian = document.getElementById("kembalian");

  const cartJson = document.getElementById("cartJson");
  const totalInput = document.getElementById("totalInput");
  const bayarInput = document.getElementById("bayarInput");
  const kembalianInput = document.getElementById("kembalianInput");

  // ===== Update Harga ketika pilih barang =====
  barangSelect.addEventListener("change", function() {
    const selected = barangSelect.options[barangSelect.selectedIndex];
    const harga = selected.getAttribute("data-harga") || 0;
    hargaInput.value = formatRupiah(harga);
  });

  // ===== Render Cart =====
  function renderCart() {
    cartTable.innerHTML = "";

    if (cart.length === 0) {
      cartTable.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            Keranjang masih kosong
          </td>
        </tr>
      `;
    } else {
      cart.forEach((item, index) => {
        cartTable.innerHTML += `
          <tr>
            <td>
              <div class="fw-semibold">${item.nama_barang}</div>
              <small class="text-muted">${item.kode_barang}</small>
            </td>
            <td class="text-end">${formatRupiah(item.harga)}</td>
            <td class="text-center">${item.qty}</td>
            <td class="text-end fw-semibold">${formatRupiah(item.subtotal)}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger" onclick="hapusItem(${index})">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        `;
      });
    }

    hitungTotal();
  }

  // ===== Hitung Total & Kembalian =====
  function hitungTotal() {
    const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
    totalBelanja.value = formatRupiah(total);

    const bayar = parseInt(jumlahBayar.value || 0);
    const kembali = bayar - total;

    kembalian.value = formatRupiah(kembali > 0 ? kembali : 0);

    // set hidden input
    cartJson.value = JSON.stringify(cart);
    totalInput.value = total;
    bayarInput.value = bayar;
    kembalianInput.value = kembali > 0 ? kembali : 0;
  }

  jumlahBayar.addEventListener("input", hitungTotal);

  // ===== Tambah ke Cart =====
  document.getElementById("btnTambah").addEventListener("click", function() {
    const selected = barangSelect.options[barangSelect.selectedIndex];
    const id_barang = barangSelect.value;

    if (!id_barang) {
      alert("Pilih barang dulu!");
      return;
    }

    const stok = parseInt(selected.getAttribute("data-stok"));
    const kode_barang = selected.getAttribute("data-kode");
    const nama_barang = selected.getAttribute("data-nama");
    const harga = parseInt(selected.getAttribute("data-harga"));
    const qty = parseInt(qtyInput.value || 1);

    if (qty < 1) {
      alert("Qty minimal 1");
      return;
    }

    if (qty > stok) {
      alert("Stok tidak cukup! Stok tersedia: " + stok);
      return;
    }

    // cek jika sudah ada di cart -> tambah qty
    const existingIndex = cart.findIndex(x => x.id_barang == id_barang);
    if (existingIndex !== -1) {
      const newQty = cart[existingIndex].qty + qty;
      if (newQty > stok) {
        alert("Qty melebihi stok! Stok tersedia: " + stok);
        return;
      }
      cart[existingIndex].qty = newQty;
      cart[existingIndex].subtotal = cart[existingIndex].qty * cart[existingIndex].harga;
    } else {
      cart.push({
        id_barang: id_barang,
        kode_barang: kode_barang,
        nama_barang: nama_barang,
        harga: harga,
        qty: qty,
        subtotal: harga * qty
      });
    }

    renderCart();

    // reset qty
    qtyInput.value = 1;
  });

  // ===== Hapus Item =====
  function hapusItem(index) {
    cart.splice(index, 1);
    renderCart();
  }

  // biar bisa dipanggil di onclick
  window.hapusItem = hapusItem;

  // ===== Reset Cart =====
  document.getElementById("btnReset").addEventListener("click", function() {
    if (confirm("Reset keranjang?")) {
      cart = [];
      jumlahBayar.value = 0;
      renderCart();
    }
  });

  // ===== Simpan Transaksi =====
  document.getElementById("btnSimpan").addEventListener("click", function() {
    if (cart.length === 0) {
      alert("Keranjang masih kosong!");
      return;
    }

    const total = parseInt(totalInput.value || 0);
    const bayar = parseInt(bayarInput.value || 0);

    if (bayar < total) {
      alert("Uang bayar kurang!");
      return;
    }

    document.getElementById("formTransaksi").submit();
  });

  // init
  renderCart();
</script>
