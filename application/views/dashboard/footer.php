<footer></footer>

</div>
</div>

<!-- BASE_URL HARUS DIBUAT DULU -->
<script>
const BASE_URL = "<?= base_url() ?>";
console.log("BASE_URL dari PHP:", BASE_URL);
</script>

<!-- JS -->
<script src="<?= base_url('assets/assets/static/js/components/dark.js') ?>"></script>
<script src="<?= base_url('assets/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
<script src="<?= base_url('assets/assets/compiled/js/app.js') ?>"></script>

<!-- JS KAMU -->
<script src="<?= base_url('assets/js/transaksi-kasir.js?v=4') ?>"></script>
<script src="<?= base_url('assets/js/kasir-barcode.js') ?>"></script>

<!-- QR -->
<script src="https://unpkg.com/html5-qrcode"></script>

</body>
</html>