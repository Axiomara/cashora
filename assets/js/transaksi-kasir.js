/**
 * Logika Transaksi Kasir
 */

// Konfigurasi Dasar
// Jika BASE_URL tidak didefinisikan di HTML, pastikan path API sesuai
const BASE_URL = window.location.origin + "/"; 

let cart = [];
const LS_CART = "kasir_cart";
const LS_BAYAR = "kasir_bayar";

// Elements
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

// Helper: Format Rupiah
function formatRupiah(angka) {
    angka = parseInt(angka || 0);
    return "Rp" + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Local Storage Handlers
function saveLocal() {
    localStorage.setItem(LS_CART, JSON.stringify(cart));
    localStorage.setItem(LS_BAYAR, jumlahBayar.value || "0");
}

function loadLocal() {
    const savedCart = localStorage.getItem(LS_CART);
    const savedBayar = localStorage.getItem(LS_BAYAR);
    if (savedCart) {
        try { cart = JSON.parse(savedCart) || []; } catch (e) { cart = []; }
    }
    if (savedBayar !== null) { jumlahBayar.value = savedBayar; }
}

function clearLocal() {
    localStorage.removeItem(LS_CART);
    localStorage.removeItem(LS_BAYAR);
}

// UI Handlers
function hideResult() {
    resultProduk.style.display = "none";
    resultProduk.innerHTML = "";
}

function showResult() {
    resultProduk.style.display = "block";
}

// AJAX Search
let typingTimer = null;
barangSearch.addEventListener("input", function () {
    clearTimeout(typingTimer);
    const q = this.value.trim();

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

            if (!json.status) { hideResult(); return; }

            const data = json.data || [];
            if (data.length === 0) {
                resultProduk.innerHTML = `<div class="list-group-item text-muted">Produk tidak ditemukan</div>`;
                showResult();
                return;
            }

            let html = "";
            data.forEach(p => {
                html += `
                <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                  onclick="pilihProduk(${p.id_barang}, '${p.kode_barang}', '${p.nama_barang}', ${p.harga_jual}, ${p.stok})">
                  <div>
                    <div class="fw-semibold">${p.kode_barang} - ${p.nama_barang}</div>
                    <small class="text-muted">Stok: ${p.stok}</small>
                  </div>
                  <span class="badge bg-light text-dark">${formatRupiah(p.harga_jual)}</span>
                </button>`;
            });
            resultProduk.innerHTML = html;
            showResult();
        } catch (err) {
            console.error(err);
            hideResult();
        }
    }, 250);
});

// Pilih Produk
window.pilihProduk = function(id, kode, nama, harga, stok) {
    idBarangSelected.value = id;
    stokSelected.value = stok;
    hargaSelected.value = harga;
    kodeSelected.value = kode;
    namaSelected.value = nama;
    barangSearch.value = nama;
    hargaInput.value = formatRupiah(harga);
    hideResult();
    qtyInput.focus();
};

document.addEventListener("click", (e) => {
    if (!resultProduk.contains(e.target) && e.target !== barangSearch) hideResult();
});

// Cart Core Functions
function renderCart() {
    cartTable.innerHTML = "";
    if (cart.length === 0) {
        cartTable.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Keranjang masih kosong</td></tr>`;
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
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="qtyMinus(${index})"><i class="bi bi-dash"></i></button>
                        <input type="number" class="form-control form-control-sm text-center qty-input" style="width:70px;" value="${item.qty ?? ''}" oninput="qtyInputChange(${index}, this.value)">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="qtyPlus(${index})"><i class="bi bi-plus"></i></button>
                    </div>
                    <small class="text-muted d-block mt-1">Stok: ${item.stok ?? 0}</small>
                </td>
                <td class="text-end fw-semibold" id="subtotal_${index}">${formatRupiah(item.subtotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusItem(${index})"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`;
        });
    }
    hitungTotal();
    saveLocal();
}

function hitungTotal() {
    const total = cart.reduce((sum, item) => sum + (parseInt(item.subtotal) || 0), 0);
    totalBelanja.value = formatRupiah(total);
    const bayar = parseInt(jumlahBayar.value || 0);
    const kembali = bayar - total;
    kembalian.value = formatRupiah(kembali > 0 ? kembali : 0);

    cartJson.value = JSON.stringify(cart);
    totalInput.value = total;
    bayarInput.value = bayar;
    kembalianInput.value = kembali > 0 ? kembali : 0;
}

jumlahBayar.addEventListener("input", () => { hitungTotal(); saveLocal(); });

// Qty Controls
window.qtyMinus = function(index) {
    if (!cart[index]) return;
    let qtyNow = parseInt(cart[index].qty || 0);
    qtyNow = Math.max(0, qtyNow - 1);
    cart[index].qty = qtyNow;
    cart[index].subtotal = cart[index].qty * cart[index].harga;
    renderCart();
};

window.qtyPlus = function(index) {
    if (!cart[index]) return;
    const stok = parseInt(cart[index].stok || 0);
    const qtySekarang = parseInt(cart[index].qty || 0);
    if (qtySekarang + 1 > stok) { alert("Stok tidak cukup!"); return; }
    cart[index].qty = qtySekarang + 1;
    cart[index].subtotal = cart[index].qty * cart[index].harga;
    renderCart();
};

window.qtyInputChange = function(index, value) {
    if (!cart[index]) return;
    if (value === "" || value === null) {
        cart[index].qty = "";
        cart[index].subtotal = 0;
    } else {
        let qtyBaru = parseInt(value || 0);
        const stok = parseInt(cart[index].stok || 0);
        if (qtyBaru > stok) {
            alert("Qty melebihi stok!");
            qtyBaru = stok;
            const inputs = document.querySelectorAll(".qty-input");
            if (inputs[index]) inputs[index].value = qtyBaru;
        }
        cart[index].qty = qtyBaru;
        cart[index].subtotal = qtyBaru * cart[index].harga;
    }
    const elSub = document.getElementById("subtotal_" + index);
    if (elSub) elSub.innerText = formatRupiah(cart[index].subtotal);
    hitungTotal();
    saveLocal();
};

// Add to Cart Action
document.getElementById("btnTambah").addEventListener("click", function () {
    const id_barang = idBarangSelected.value;
    if (!id_barang) { alert("Cari dan pilih barang dulu!"); return; }

    const stok = parseInt(stokSelected.value || 0);
    const qty = parseInt(qtyInput.value || 1);

    if (qty < 1) { alert("Qty minimal 1"); return; }
    if (qty > stok) { alert("Stok tidak cukup!"); return; }

    const existingIndex = cart.findIndex(x => x.id_barang == id_barang);
    if (existingIndex !== -1) {
        const newQty = parseInt(cart[existingIndex].qty || 0) + qty;
        if (newQty > stok) { alert("Qty melebihi stok!"); return; }
        cart[existingIndex].qty = newQty;
        cart[existingIndex].subtotal = cart[existingIndex].qty * cart[existingIndex].harga;
    } else {
        cart.push({
            id_barang: id_barang,
            kode_barang: kodeSelected.value,
            nama_barang: namaSelected.value,
            harga: parseInt(hargaSelected.value || 0),
            stok: stok,
            qty: qty,
            subtotal: parseInt(hargaSelected.value || 0) * qty
        });
    }
    renderCart();
    // Reset Form
    qtyInput.value = 1;
    barangSearch.value = "";
    idBarangSelected.value = "";
    barangSearch.focus();
});

window.hapusItem = function(index) {
    cart.splice(index, 1);
    renderCart();
};

document.getElementById("btnReset").addEventListener("click", () => {
    if (confirm("Reset keranjang?")) {
        cart = [];
        jumlahBayar.value = 0;
        renderCart();
        clearLocal();
    }
});

// Form Submission
let isSubmitting = false;
formTransaksi.addEventListener("submit", function (e) {
    if (isSubmitting) { e.preventDefault(); return; }
    if (cart.length === 0) { e.preventDefault(); alert("Keranjang kosong!"); return; }
    
    const invalid = cart.filter(item => !item.qty || item.qty <= 0);
    if (invalid.length > 0) { e.preventDefault(); alert("Cek kembali Qty barang!"); return; }

    if (parseInt(bayarInput.value) < parseInt(totalInput.value)) {
        e.preventDefault(); alert("Uang bayar kurang!"); return;
    }

    isSubmitting = true;
    const btn = document.getElementById("btnSimpan");
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...`;
});

// Keyboard Shortcuts
document.addEventListener("keydown", function (e) {
    if (e.key === "F2") { e.preventDefault(); barangSearch.focus(); }
    if (e.key === "Enter") {
        if (document.activeElement.id === "barangSearch" || document.activeElement.id === "qtyInput") {
            e.preventDefault();
            document.getElementById("btnTambah").click();
        }
    }
    if (e.key === "F8") { e.preventDefault(); document.getElementById("btnSimpan").click(); }
    if (e.key === "Escape") { e.preventDefault(); document.getElementById("btnReset").click(); }
});

// Init
document.addEventListener("DOMContentLoaded", function () {

    loadLocal();
    renderCart();

    if (typeof barangSearch !== "undefined" && barangSearch) {

        barangSearch.focus();

        barangSearch.addEventListener("keydown", function (e) {

            if (e.key === "Enter") {

                e.preventDefault();

                const value = this.value.trim();

                if (value === "") return;

                // jika angka semua dianggap barcode
                if (/^\d+$/.test(value)) {

                    scanBarcode(value);

                }

            }

        });

    }

});

function scanBarcode(barcode) {

    fetch(BASE_URL + "transaksi/cari_barcode", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: "barcode=" + encodeURIComponent(barcode)

    })
    .then(res => res.json())
    .then(data => {

        if (data && data.status === "ok" && data.barang) {

            const barang = data.barang;

            if (idBarangSelected) idBarangSelected.value = barang.id_barang;
            if (stokSelected) stokSelected.value = barang.stok;
            if (hargaSelected) hargaSelected.value = barang.harga_jual;
            if (kodeSelected) kodeSelected.value = barang.kode_barang;
            if (namaSelected) namaSelected.value = barang.nama_barang;

            if (hargaInput) {
                hargaInput.value = formatRupiah(barang.harga_jual);
            }

            if (qtyInput) {
                qtyInput.value = 1;
            }

            const btnTambah = document.getElementById("btnTambah");

            if (btnTambah) {
                btnTambah.click();
            }

            // kosongkan input dan fokus lagi
            barangSearch.value = "";
            barangSearch.focus();

        } else {

            alert("Barcode tidak ditemukan");

            barangSearch.value = "";
            barangSearch.focus();

        }

    })
    .catch(err => {

        console.error("Error:", err);

        alert("Gagal mengambil data barang");

        barangSearch.focus();

    });

}