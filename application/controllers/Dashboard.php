<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
           $this->load->model('Audit_log_model');
    }
    public function index()
    {
        $list_stok_menipis = $this->Dashboard_model->get_stok_menipis();

        $data = [
            'omzet_hari_ini'    => $this->Dashboard_model->get_omzet_hari_ini(),
            'jumlah_transaksi'  => $this->Dashboard_model->get_jumlah_transaksi_hari_ini(),
            'total_barang'      => $this->Dashboard_model->get_total_barang(),
            'stok_menipis'      => count($list_stok_menipis),
            'list_stok_menipis' => $list_stok_menipis,
            'transaksi_terbaru' => $this->Dashboard_model->get_transaksi_terbaru_hari_ini()
        ];

        $this->load->view('dashboard/header', $data);
        $this->load->view('dashboard/sidebar', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('dashboard/footer');
    }
}