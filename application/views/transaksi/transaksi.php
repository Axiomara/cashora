<div id="main">

  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>

  <div class="page-heading mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

      <div>
        <h3 class="mb-1">Transaksi Kasir</h3>
        <p class="text-muted mb-0">Input transaksi penjualan kios</p>
      </div>

      <div class="text-end">
        <div class="small text-muted">Waktu</div>
        <div class="fw-semibold"><?= date('d-m-Y H:i') ?></div>
      </div>

    </div>
  </div>


  <div class="page-content">

    <form id="formTransaksi" method="post" action="<?= base_url('transaksi/simpan') ?>">

      <input type="hidden" name="trx_token" value="<?= $trx_token ?>">
      <input type="hidden" name="cart_json" id="cartJson">
      <input type="hidden" name="total" id="totalInput">
      <input type="hidden" name="bayar" id="bayarInput">
      <input type="hidden" name="kembalian" id="kembalianInput">

      <div class="row g-3">

        <!-- ================= KIRI ================= -->
        <div class="col-lg-4 col-12">

          <div class="card shadow-sm border-0">

            <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
              <h5 class="mb-0">
                Tambah Barang
                <span class="badge bg-light text-dark ms-2">INPUT</span>
              </h5>
              <small class="text-muted">
                Cari barang dengan kode, nama, atau barcode
              </small>
            </div>


            <div class="card-body px-4 pb-4 pt-2">

              <!-- SEARCH -->
              <div class="mb-3">

                <label class="form-label">
                  Cari Barang
                  <small class="text-muted">(Kode / Nama / Barcode)</small>
                </label>

                <input
                  type="text"
                  class="form-control"
                  id="barangSearch"
                  placeholder="Contoh: BRG001 atau Indomie"
                  autocomplete="off"
                  spellcheck="false">

                <div id="resultProduk" class="list-group mt-2" style="display:none;"></div>

                <div class="small text-muted mt-2">
                  💡 Tekan <b>F2</b> untuk fokus ke pencarian
                </div>

                <input type="hidden" id="idBarangSelected">
                <input type="hidden" id="stokSelected">
                <input type="hidden" id="hargaSelected">
                <input type="hidden" id="kodeSelected">
                <input type="hidden" id="namaSelected">

              </div>


              <!-- QTY + HARGA -->
              <div class="row g-2">

                <div class="col-6">
                  <label class="form-label">Qty</label>

                  <input
                    type="number"
                    class="form-control text-center"
                    id="qtyInput"
                    value="1"
                    min="1">

                  <small class="text-muted">
                    Minimal pembelian 1
                  </small>

                </div>


                <div class="col-6">
                  <label class="form-label">Harga Satuan</label>

                  <input
                    type="text"
                    class="form-control text-end"
                    id="hargaInput"
                    value="Rp0"
                    readonly>

                  <small class="text-muted">
                    Harga otomatis dari sistem
                  </small>

                </div>

              </div>


              <!-- BUTTON TAMBAH -->
              <div class="d-grid mt-3">

                <button
                  type="button"
                  class="btn btn-primary"
                  id="btnTambah">

                  <i class="bi bi-plus-circle me-1"></i>
                  Tambah ke Keranjang

                </button>

              </div>


              <div class="alert alert-light border mt-3 mb-0 small">

                <b>Shortcut Cepat:</b><br>

                Enter → Tambah barang<br>
                Esc → Reset keranjang<br>
                F8 → Simpan transaksi

              </div>

            </div>

          </div>

        </div>


        <!-- ================= KANAN ================= -->
        <div class="col-lg-8 col-12">

          <div class="card shadow-sm border-0">

            <div class="card-header bg-white border-0 px-4 pt-4 pb-2 d-flex justify-content-between align-items-center">

              <div>

                <h5 class="mb-0">
                  Keranjang
                  <span class="badge bg-light text-dark ms-2" id="totalItemBadge">
                    0 Item
                  </span>
                </h5>

                <small class="text-muted">
                  Daftar barang yang dibeli pelanggan
                </small>

              </div>


              <button
                type="button"
                class="btn btn-sm btn-light"
                id="btnReset">

                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Reset

              </button>

            </div>



            <div class="card-body px-4 pb-4 pt-2">

              <!-- TABLE CART -->
              <div class="table-responsive">

                <table class="table align-middle">

                  <thead>
                    <tr class="text-muted small">
                      <th style="width:35%">Barang</th>
                      <th class="text-end">Harga</th>
                      <th class="text-center">Qty</th>
                      <th class="text-end">Subtotal</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>

                  <tbody id="cartTable">

                    <tr>
                      <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-cart fs-3 d-block mb-2"></i>
                        Keranjang masih kosong
                      </td>
                    </tr>

                  </tbody>

                </table>

              </div>


              <hr class="my-3">


              <!-- PAYMENT -->
              <div class="row g-3">

                <div class="col-md-4">

                  <label class="form-label">Total Belanja</label>

                  <input
                    type="text"
                    class="form-control form-control-lg fw-bold text-end"
                    id="totalBelanja"
                    value="Rp0"
                    readonly>

                </div>


                <div class="col-md-4">

                  <label class="form-label">Jumlah Bayar</label>

                  <input
                    type="number"
                    class="form-control form-control-lg text-end"
                    id="jumlahBayar"
                    value="0"
                    min="0"
                    required>

                  <small class="text-muted">
                    Masukkan nominal pembayaran pelanggan
                  </small>

                </div>


                <div class="col-md-4">

                  <label class="form-label">Kembalian</label>

                  <input
                    type="text"
                    class="form-control form-control-lg text-end fw-bold"
                    id="kembalian"
                    value="Rp0"
                    readonly>

                </div>


                <div class="col-md-6">

                  <label class="form-label">Metode Pembayaran</label>

                  <select
                    name="metode_bayar"
                    id="metodeBayar"
                    class="form-select"
                    required>

                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer</option>

                  </select>

                </div>


                <div class="col-md-6 d-flex align-items-end">

                  <button
                    type="submit"
                    class="btn btn-success btn-lg w-100"
                    id="btnSimpan">

                    <i class="bi bi-check-circle me-1"></i>
                    Simpan Transaksi

                  </button>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </form>

  </div>

</div>