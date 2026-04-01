<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Laporan_model $Laporan_model
 */

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_model');
    }

    public function index()
    {
        $dari   = $this->input->get('dari') ?: date('Y-m-01');
        $sampai = $this->input->get('sampai') ?: date('Y-m-d');

        // ================= CORE =================
        $data['pembelian'] = $this->Laporan_model->get_pembelian($dari, $sampai);
        $data['penjualan'] = $this->Laporan_model->get_penjualan($dari, $sampai);
        $data['total']     = $this->Laporan_model->get_total($dari, $sampai);

        // ================= LABA =================
        $data['laba_real'] = $this->Laporan_model->get_laba_real($dari, $sampai);

        // ================= ANALISIS =================
        $data['barang_terlaris'] = $this->Laporan_model->get_barang_terlaris($dari, $sampai);
        $data['laba_per_barang'] = $this->Laporan_model->get_laba_per_barang($dari, $sampai);

        // ================= TRANSAKSI =================
        $data['jumlah_transaksi'] = $this->Laporan_model->count_transaksi($dari, $sampai);

        // ================= SUPPLIER =================
        $data['supplier'] = $this->Laporan_model->get_pembelian_per_supplier($dari, $sampai);

        // ================= STOK =================
        $data['stok'] = $this->Laporan_model->get_stok_barang();
        $data['stok_menipis'] = $this->Laporan_model->get_stok_menipis();

        // ================= GRAFIK =================
        $data['penjualan_harian'] = $this->Laporan_model->get_penjualan_per_hari($dari, $sampai);
        $data['pembelian_harian'] = $this->Laporan_model->get_pembelian_per_hari($dari, $sampai);

        // ================= FILTER =================
        $data['dari']   = $dari;
        $data['sampai'] = $sampai;

        // ================= VIEW =================
        $this->load->view('dashboard/header');
          $this->load->view('laporan/index', $data);
        $this->load->view('dashboard/sidebar');
        $this->load->view('dashboard/footer');
    }
}
