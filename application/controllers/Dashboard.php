<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Dashboard_model $Dashboard_model
 * @property Audit_log_model $Audit_log_model
 */

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
        $omzet = $this->Dashboard_model->get_omzet_harian();
        $data = [

            'omzet_kotor'      => $omzet['omzet_kotor'],
            'retur_hari_ini'   => $omzet['retur'],
            'omzet_bersih'     => $omzet['omzet_bersih'],

            'omzet_hari_ini'    => $this->Dashboard_model->get_omzet_hari_ini(),
            'jumlah_transaksi'  => $this->Dashboard_model->get_jumlah_transaksi_hari_ini(),
            'total_barang'      => $this->Dashboard_model->get_total_barang(),
            'stok_menipis'      => count($list_stok_menipis),
            'list_stok_menipis' => $list_stok_menipis,
            'transaksi_terbaru' => $this->Dashboard_model->get_transaksi_terbaru_hari_ini(),

            'penjualan_harian'  => $this->Dashboard_model->get_penjualan_per_hari($dari, $sampai),
            'transaksi_harian'  => $this->Dashboard_model->get_transaksi_per_hari($dari, $sampai)
        ];

        $this->load->view('dashboard/header', $data);
        $this->load->view('dashboard/sidebar', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('dashboard/footer');
    }
}
