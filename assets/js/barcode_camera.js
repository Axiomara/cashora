document.addEventListener("DOMContentLoaded", function () {

    const btnScan = document.getElementById("btnScanCamera");
    const reader = document.getElementById("reader");

    if (!btnScan || !reader) return;

    let scanner = null;

    btnScan.addEventListener("click", async function () {

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Browser tidak mendukung kamera");
            return;
        }

        reader.style.display = "block";

        if (!scanner) {
            scanner = new Html5Qrcode("reader");
        }

        try {

            await scanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (barcode) => {

                    stopScanner();

                    prosesBarcode(barcode);

                },
                (error) => {
                    console.log("Scan error:", error);
                }
            );

        } catch (err) {

            console.error("Camera error:", err);

            alert("Kamera tidak bisa digunakan");

            reader.style.display = "none";

        }

    });

    async function stopScanner() {

        if (scanner) {
            try {
                await scanner.stop();
                await scanner.clear();
            } catch (e) {
                console.log("Stop scanner error:", e);
            }
        }

        reader.style.display = "none";

    }

});


function prosesBarcode(barcode) {

    fetch(BASE_URL + "transaksi/cari_barcode", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: "barcode=" + encodeURIComponent(barcode)

    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "ok" && data.barang) {

            const barang = data.barang;

            const idBarang = document.getElementById("idBarangSelected");
            const stok = document.getElementById("stokSelected");
            const harga = document.getElementById("hargaSelected");
            const kode = document.getElementById("kodeSelected");
            const nama = document.getElementById("namaSelected");

            if (idBarang) idBarang.value = barang.id_barang;
            if (stok) stok.value = barang.stok;
            if (harga) harga.value = barang.harga_jual;
            if (kode) kode.value = barang.kode_barang;
            if (nama) nama.value = barang.nama_barang;

            const hargaInput = document.getElementById("hargaInput");
            const qtyInput = document.getElementById("qtyInput");

            if (hargaInput) hargaInput.value = formatRupiah(barang.harga_jual);
            if (qtyInput) qtyInput.value = 1;

            const btnTambah = document.getElementById("btnTambah");

            if (btnTambah) {
                btnTambah.click();
            }

        } else {

            alert("Barang tidak ditemukan");

        }

    })
    .catch(err => {

        console.error("Fetch error:", err);

        alert("Gagal mengambil data barang");

    });

}