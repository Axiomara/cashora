/**
 * Barcode Scanner Handler
 * File terpisah dari script utama kasir
 */

(function () {

    const barangSearch = document.getElementById("barangSearch");
    const idBarangSelected = document.getElementById("idBarangSelected");

    if (!barangSearch) return;

    let barcodeBuffer = "";
    let barcodeTimer = null;

    document.addEventListener("keydown", function (e) {

        // Abaikan jika sedang mengetik di input lain selain search
        if (document.activeElement !== barangSearch) return;

        // Jika tombol Enter ditekan
        if (e.key === "Enter") {

            if (barcodeBuffer.length >= 5) {

                e.preventDefault();

                // Isi ke search field
                barangSearch.value = barcodeBuffer;

                // Trigger event input agar search jalan
                barangSearch.dispatchEvent(new Event("input"));

                // Jika barang sudah terpilih → otomatis tambah ke keranjang
                setTimeout(() => {
                    if (idBarangSelected.value) {
                        const btnTambah = document.getElementById("btnTambah");
                        if (btnTambah) btnTambah.click();
                    }
                }, 200);

            }

            barcodeBuffer = "";
            return;
        }

        // Hanya tangkap karakter biasa (angka/huruf)
        if (e.key.length === 1) {
            barcodeBuffer += e.key;

            clearTimeout(barcodeTimer);
            barcodeTimer = setTimeout(() => {
                barcodeBuffer = "";
            }, 300);
        }

    });

})();