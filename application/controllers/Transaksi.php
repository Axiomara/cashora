<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Barang_model')  ;
    }

    public function index() {
        $this->load->view('dashboard/header' );
        $this->load->view('dashboard/sidebar');
        $this->load->view('transaksi/transaksi' );
        $this->load->view('dashboard/footer');
    }

    // ==========================================
    // POST: transaksi/simpan (checkout transaksi)
    // ==========================================
    public function simpan() {
        $cart_json = $this->input->post('cart_json', TRUE);
        $bayar     = (int)$this->input->post('bayar', TRUE);

        if (empty($cart_json)) {
            $this->session->set_flashdata('error', 'Keranjang masih kosong!');
            redirect('transaksi');
        }

        $cart = json_decode($cart_json, true);

        if (!is_array($cart) || count($cart) == 0) {
            $this->session->set_flashdata('error', 'Data keranjang tidak valid!');
            redirect('transaksi');
        }

        $total = 0;
        $detailItems = [];

        foreach ($cart as $item) {
            $id_barang = (int)($item['id_barang'] ?? 0);
            $qty       = (int)($item['qty'] ?? 0);

            if ($id_barang <= 0 || $qty <= 0) {
                $this->session->set_flashdata('error', 'Item keranjang tidak valid!');
                redirect('transaksi');
            }

            $barang = $this->Barang_model->get_by_id($id_barang);
            if (!$barang) {
                $this->session->set_flashdata('error', 'Barang tidak ditemukan!');
                redirect('transaksi');
            }

            if ($qty > $barang->stok) {
                $this->session->set_flashdata(
                    'error',
                    'Stok tidak cukup untuk ' . $barang->nama_barang . ' (Stok: ' . $barang->stok . ')'
                );
                redirect('transaksi');
            }

            $harga    = (int)$barang->harga_jual;
            $subtotal = $harga * $qty;

            $total += $subtotal;

            $detailItems[] = [
                'id_barang'   => $id_barang,
                'qty'         => $qty,
                'harga'       => $harga,
                'subtotal'    => $subtotal,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
            ];
        }

        if ($bayar < $total) {
            $this->session->set_flashdata('error', 'Uang bayar kurang!');
            redirect('transaksi');
        }

        $kembalian = $bayar - $total;

        // simpan transaksi pakai transaksi_model (pake transaksi DB)
        $result = $this->Transaksi_model->create_transaksi($detailItems, $total, $bayar, $kembalian);

        if ($result['status'] === true) {
           echo "berhasil";
        } else {
            $this->session->set_flashdata('error', $result['message']);
            echo "gagal";
    }

}
}


