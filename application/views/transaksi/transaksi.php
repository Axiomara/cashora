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

    <!-- ✅ FORM DIBUKA DI SINI (mencakup semua) -->
    <form id="formTransaksi" method="post" action="<?= base_url('transaksi/simpan') ?>">

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
                <label class="form-label">Cari Barang (Kode / Nama)</label>
                <input type="text"
                    class="form-control"
                    id="barangSearch"
                    placeholder="Ketik nama atau kode barang..."
                    autocomplete="off"
                    autocapitalize="off"
                    autocorrect="off"
                    spellcheck="false"
                  >
                <div id="resultProduk" class="list-group mt-2" style="display:none;"></div>

                <input type="hidden" id="idBarangSelected">
                <input type="hidden" id="stokSelected">
                <input type="hidden" id="hargaSelected">
                <input type="hidden" id="kodeSelected">
                <input type="hidden" id="namaSelected">
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
                <!-- type="button" biar gak submit form -->
                <button type="button" class="btn btn-primary" id="btnTambah">
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

              <!-- type="button" biar gak submit form -->
              <button type="button" class="btn btn-sm btn-light" id="btnReset">
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
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">
                        Keranjang masih kosong
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <hr class="my-3">

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
                  <!-- type="submit" baru submit transaksi -->
                  <button type="submit" class="btn btn-success btn-lg w-100" id="btnSimpan">
                    <i class="bi bi-check-circle me-1"></i> Simpan Transaksi
                  </button>
                </div>
              </div>

              <!-- ✅ Hidden input harus ADA di dalam form -->
              <input type="hidden" name="cart_json" id="cartJson">
              <input type="hidden" name="total" id="totalInput">
              <input type="hidden" name="bayar" id="bayarInput">
              <input type="hidden" name="kembalian" id="kembalianInput">

            </div>
          </div>
        </div>

      </div>

    </form>
    <!-- ✅ FORM DITUTUP DI SINI -->

  </div>
</div>


<script>
  // ======================
  // CONFIG & ELEMENT
  // ======================
  const BASE_URL = "<?= base_url() ?>";

  function formatRupiah(angka) {
    angka = parseInt(angka || 0);
    return "Rp" + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  let cart = [];

  const barangSearch = document.getElementById("barangSearch");
  const resultProduk = document.getElementById("resultProduk");

  const idBarangSelected = document.getElementById("idBarangSelected");
  const stokSelected = document.getElementById("stokSelected");
  const hargaSelected = document.getElementById("hargaSelected");
  const kodeSelected = document.getElementById("kodeSelected");
  const namaSelected = document.getElementById("namaSelected");

  const qtyInput = document.getElementById("qtyInput");
  const hargaInput = document.getElementById("hargaInput");

  const cartTable = document.getElementById("cartTable");
  const totalBelanja = document.getElementById("totalBelanja");
  const jumlahBayar = document.getElementById("jumlahBayar");
  const kembalian = document.getElementById("kembalian");

  const formTransaksi = document.getElementById("formTransaksi");
  const cartJson = document.getElementById("cartJson");
  const totalInput = document.getElementById("totalInput");
  const bayarInput = document.getElementById("bayarInput");
  const kembalianInput = document.getElementById("kembalianInput");

  // ======================
  // LOCAL STORAGE (ANTI INPUT ULANG)
  // ======================
  const LS_CART = "kasir_cart";
  const LS_BAYAR = "kasir_bayar";

  function saveLocal() {
    localStorage.setItem(LS_CART, JSON.stringify(cart));
    localStorage.setItem(LS_BAYAR, jumlahBayar.value || "0");
  }

  function loadLocal() {
    const savedCart = localStorage.getItem(LS_CART);
    const savedBayar = localStorage.getItem(LS_BAYAR);

    if (savedCart) {
      try {
        cart = JSON.parse(savedCart) || [];
      } catch (e) {
        cart = [];
      }
    }

    if (savedBayar !== null) {
      jumlahBayar.value = savedBayar;
    }
  }

  function clearLocal() {
    localStorage.removeItem(LS_CART);
    localStorage.removeItem(LS_BAYAR);
  }

  // ======================
  // SHOW / HIDE RESULT
  // ======================
  function hideResult() {
    resultProduk.style.display = "none";
    resultProduk.innerHTML = "";
  }

  function showResult() {
    resultProduk.style.display = "block";
  }

  // ======================
  // AJAX SEARCH PRODUK
  // ======================
  let typingTimer = null;

  barangSearch.addEventListener("input", function () {
    clearTimeout(typingTimer);
    const q = this.value.trim();

    // reset pilihan kalau user mengetik ulang
    idBarangSelected.value = "";
    stokSelected.value = "";
    hargaSelected.value = "";
    kodeSelected.value = "";
    namaSelected.value = "";
    hargaInput.value = "Rp0";

    if (q.length < 1) {
      hideResult();
      return;
    }

    typingTimer = setTimeout(async () => {
      try {
        const res = await fetch(BASE_URL + "api/cari-produk?q=" + encodeURIComponent(q));
        const json = await res.json();

        if (!json.status) {
          hideResult();
          return;
        }

        const data = json.data || [];

        if (data.length === 0) {
          resultProduk.innerHTML = `
            <div class="list-group-item text-muted">
              Produk tidak ditemukan
            </div>
          `;
          showResult();
          return;
        }

        let html = "";
        data.forEach(p => {
          html += `
            <button type="button"
              class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
              onclick="pilihProduk(${p.id_barang}, '${p.kode_barang}', '${p.nama_barang}', ${p.harga_jual}, ${p.stok})"
            >
              <div>
                <div class="fw-semibold">${p.kode_barang} - ${p.nama_barang}</div>
                <small class="text-muted">Stok: ${p.stok}</small>
              </div>
              <span class="badge bg-light text-dark">
                ${formatRupiah(p.harga_jual)}
              </span>
            </button>
          `;
        });

        resultProduk.innerHTML = html;
        showResult();
      } catch (err) {
        console.log(err);
        hideResult();
      }
    }, 250);
  });

  // ======================
  // PILIH PRODUK
  // ======================
  function pilihProduk(id, kode, nama, harga, stok) {
    idBarangSelected.value = id;
    stokSelected.value = stok;
    hargaSelected.value = harga;
    kodeSelected.value = kode;
    namaSelected.value = nama;

    barangSearch.value = nama;
    hargaInput.value = formatRupiah(harga);

    hideResult();
    qtyInput.focus();
  }
  window.pilihProduk = pilihProduk;

  document.addEventListener("click", function (e) {
    if (!resultProduk.contains(e.target) && e.target !== barangSearch) {
      hideResult();
    }
  });

  // ======================
  // RENDER CART (dengan + - dan input qty)
  // ======================
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

            <td class="text-center">
              <div class="d-flex justify-content-center align-items-center gap-1">
                <button type="button"
                  class="btn btn-sm btn-outline-secondary"
                  onclick="qtyMinus(${index})"
                  title="Kurangi"
                >
                  <i class="bi bi-dash"></i>
                </button>

                <input type="number"
                  class="form-control form-control-sm text-center qty-input"
                  style="width:70px;"
                  placeholder="0"
                  value="${item.qty ?? ''}"
                  oninput="qtyInputChange(${index}, this.value)"
                >

                <button type="button"
                  class="btn btn-sm btn-outline-secondary"
                  onclick="qtyPlus(${index})"
                  title="Tambah"
                >
                  <i class="bi bi-plus"></i>
                </button>
              </div>

              <small class="text-muted d-block mt-1">
                Stok: ${item.stok ?? 0}
              </small>
            </td>

             <td class="text-end fw-semibold" id="subtotal_${index}">
              ${formatRupiah(item.subtotal)}
            </td>

            <td class="text-end">
              <button type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="hapusItem(${index})"
              >
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        `;
      });
    }

    hitungTotal();
    saveLocal();
  }

  // ======================
  // HITUNG TOTAL + KEMBALIAN
  // ======================
  function hitungTotal() {
    const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
    totalBelanja.value = formatRupiah(total);

    const bayar = parseInt(jumlahBayar.value || 0);
    const kembali = bayar - total;

    kembalian.value = formatRupiah(kembali > 0 ? kembali : 0);

    cartJson.value = JSON.stringify(cart);
    totalInput.value = total;
    bayarInput.value = bayar;
    kembalianInput.value = kembali > 0 ? kembali : 0;
  }

  jumlahBayar.addEventListener("input", function () {
    hitungTotal();
    saveLocal();
  });

  // ======================
  // QTY CONTROL (+ - input manual)
  // ======================
  function qtyMinus(index) {
  if (!cart[index]) return;

  let qtyNow = parseInt(cart[index].qty || 0);
  qtyNow = qtyNow - 1;

  // ✅ boleh 0 (tidak dihapus)
  if (qtyNow < 0) qtyNow = 0;

  cart[index].qty = qtyNow;
  cart[index].subtotal = cart[index].qty * cart[index].harga;

  renderCart();
}


  function qtyPlus(index) {
    if (!cart[index]) return;

    const stok = parseInt(cart[index].stok || 0);
    const qtySekarang = parseInt(cart[index].qty || 0);

    if (qtySekarang + 1 > stok) {
      alert("Stok tidak cukup! Stok tersedia: " + stok);
      return;
    }

    cart[index].qty = qtySekarang + 1;
    cart[index].subtotal = cart[index].qty * cart[index].harga;

    renderCart();
  }

  function qtyInputChange(index, value) {
  if (!cart[index]) return;

  // kalau kosong -> subtotal 0
  if (value === "" || value === null) {
    cart[index].qty = "";
    cart[index].subtotal = 0;

    const elSub = document.getElementById("subtotal_" + index);
    if (elSub) elSub.innerText = formatRupiah(0);

    hitungTotal();
    saveLocal();
    return;
  }

  let qtyBaru = parseInt(value || 0);
  if (isNaN(qtyBaru) || qtyBaru < 0) qtyBaru = 0;

  const stok = parseInt(cart[index].stok || 0);

  if (qtyBaru > stok) {
    alert("Qty melebihi stok! Stok tersedia: " + stok);
    qtyBaru = stok;

    // balikin isi input ke stok maksimal
    const inputs = document.querySelectorAll(".qty-input");
    if (inputs[index]) inputs[index].value = qtyBaru;
  }

  cart[index].qty = qtyBaru;
  cart[index].subtotal = qtyBaru * cart[index].harga;

  // ✅ update SUBTOTAL yang benar
  const elSub = document.getElementById("subtotal_" + index);
  if (elSub) elSub.innerText = formatRupiah(cart[index].subtotal);

  hitungTotal();
  saveLocal();
}




  window.qtyMinus = qtyMinus;
  window.qtyPlus = qtyPlus;
  window.qtyInputChange = qtyInputChange;

  // ======================
  // TAMBAH KE CART
  // ======================
  document.getElementById("btnTambah").addEventListener("click", function (e) {
    e.preventDefault();

    const id_barang = idBarangSelected.value;
    if (!id_barang) {
      alert("Cari dan pilih barang dulu!");
      return;
    }

    const stok = parseInt(stokSelected.value || 0);
    const kode_barang = kodeSelected.value;
    const nama_barang = namaSelected.value;
    const harga = parseInt(hargaSelected.value || 0);
    const qty = parseInt(qtyInput.value || 1);

    if (qty < 1) {
      alert("Qty minimal 1");
      return;
    }

    if (qty > stok) {
      alert("Stok tidak cukup! Stok tersedia: " + stok);
      return;
    }

    const existingIndex = cart.findIndex(x => x.id_barang == id_barang);

    if (existingIndex !== -1) {
      const newQty = cart[existingIndex].qty + qty;

      if (newQty > stok) {
        alert("Qty melebihi stok! Stok tersedia: " + stok);
        return;
      }

      cart[existingIndex].stok = stok;
      cart[existingIndex].qty = newQty;
      cart[existingIndex].subtotal = cart[existingIndex].qty * cart[existingIndex].harga;
    } else {
      cart.push({
        id_barang: id_barang,
        kode_barang: kode_barang,
        nama_barang: nama_barang,
        harga: harga,
        stok: stok,
        qty: qty,
        subtotal: harga * qty
      });
    }

    renderCart();

    // reset input
    qtyInput.value = 1;
    barangSearch.value = "";
    idBarangSelected.value = "";
    stokSelected.value = "";
    hargaSelected.value = "";
    kodeSelected.value = "";
    namaSelected.value = "";
    hargaInput.value = "Rp0";

    barangSearch.focus();
  });

  // ======================
  // HAPUS ITEM
  // ======================
  function hapusItem(index) {
    cart.splice(index, 1);
    renderCart();
  }
  window.hapusItem = hapusItem;

  // ======================
  // RESET CART
  // ======================
  document.getElementById("btnReset").addEventListener("click", function (e) {
    e.preventDefault();

    if (confirm("Reset keranjang?")) {
      cart = [];
      jumlahBayar.value = 0;
      renderCart();
      clearLocal();
    }
  });

  // ======================
  // SUBMIT FORM (VALIDASI)
  // ======================
  formTransaksi.addEventListener("submit", function (e) {
  if (cart.length === 0) {
    e.preventDefault();
    alert("Keranjang masih kosong!");
    return;
  }

  // ✅ cek qty kosong / 0
  const invalid = cart.filter(item => {
    const q = item.qty;
    return q === "" || q === null || isNaN(parseInt(q)) || parseInt(q) <= 0;
  });

  if (invalid.length > 0) {
    e.preventDefault();
    alert("Ada barang yang Qty masih 0/kosong. Perbaiki dulu sebelum simpan!");
    return;
  }

  const total = parseInt(totalInput.value || 0);
  const bayar = parseInt(bayarInput.value || 0);

  if (bayar < total) {
    e.preventDefault();
    alert("Uang bayar kurang!");
    return;
  }
});


  // ======================
  // INIT
  // ======================
  document.addEventListener("DOMContentLoaded", function () {
    loadLocal();
    renderCart();
    barangSearch.focus();
  });
</script>

<style>
  /* Hilangkan panah input number (Chrome, Edge, Safari) */
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Hilangkan panah input number (Firefox) */
  input[type="number"] {
    -moz-appearance: textfield;
  }
</style>

    