<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Audit_log_model');
    }
    public function index()
    {
        $list_stok_menipis = $this->Dashboard_model->get_stok_menipis();

        $dari   = date('Y-m-d', strtotime('-7 days'));
        $sampai = date('Y-m-d');

        // 🔥 ambil omzet (kotor, retur, bersih)
        $omzet = $this->Dashboard_model->get_omzet_harian();

        // ✅ gabungkan semua ke dalam 1 $data
        $data = [
        
            'omzet_kotor'      => $omzet['omzet_kotor'],
            'retur_hari_ini'   => $omzet['retur'],
            'omzet_bersih'     => $omzet['omzet_bersih'],

            // data lama
            'omzet_hari_ini'    => $this->Dashboard_model->get_omzet_hari_ini(),
            'jumlah_transaksi'  => $this->Dashboard_model->get_jumlah_transaksi_hari_ini(),
            'total_barang'      => $this->Dashboard_model->get_total_barang(),
            'stok_menipis'      => count($list_stok_menipis),
            'list_stok_menipis' => $list_stok_menipis,
            'transaksi_terbaru' => $this->Dashboard_model->get_transaksi_terbaru_hari_ini(),

            // grafik
            'penjualan_harian'  => $this->Dashboard_model->get_penjualan_per_hari($dari, $sampai),
            'transaksi_harian'  => $this->Dashboard_model->get_transaksi_per_hari($dari, $sampai)
        ];

        // load view
        $this->load->view('dashboard/header', $data);
        $this->load->view('dashboard/sidebar', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('dashboard/footer');
    }
}
