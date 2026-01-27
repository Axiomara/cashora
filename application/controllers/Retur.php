<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Retur_model');
        $this->load->model('Transaksi_model');
    }

   public function form($id_transaksi)
{
    $id_transaksi = (int)$id_transaksi;

    // 🔐 CEK IZIN
    $allow = $this->session->userdata('allow_retur');

    if ($allow != $id_transaksi) {
        // akses ilegal (ketik URL manual)
        $this->session->set_flashdata('error', 'Akses retur tidak valid.');
        redirect('transaksi/riwayat');
        return;
    }

    // ambil transaksi
    $transaksi = $this->Transaksi_model->get_by_id($id_transaksi);
    if (!$transaksi) {
        show_404();
    }

    $detail = $this->Transaksi_model->get_detail($id_transaksi);

    $data = [
        'transaksi' => $transaksi,
        'detail'    => $detail
    ];

    $this->load->view('dashboard/header');
    $this->load->view('dashboard/sidebar');
    $this->load->view('retur/form', $data);
    $this->load->view('dashboard/footer');
}


    public function simpan()
{
    $id_transaksi = (int)$this->input->post('id_transaksi', TRUE);
    $items        = $this->input->post('items');

    if (!$id_transaksi || empty($items)) {
        $this->session->set_flashdata('error', 'Data retur tidak valid');
        redirect('transaksi');
        return;
    }

    // proses retur di model
    $result = $this->Retur_model->create_retur($id_transaksi, $items);

    if ($result['status'] === true) {

        // ✅ INI POSISI YANG BENAR
        // HAPUS SESSION IZIN RETUR (ONE-TIME ACCESS)
        $this->session->unset_userdata('allow_retur');

        $this->session->set_flashdata('success', 'Retur berhasil disimpan');
        redirect('transaksi/detail/' . $id_transaksi);
        return;

    } else {
        $this->session->set_flashdata('error', $result['message']);
        redirect('retur/form/' . $id_transaksi);
        return;
    }
}


    public function detail($id_retur)
    {
        $data = [
            'retur' => $this->Retur_model->get_retur($id_retur),
            'detail'=> $this->Retur_model->get_retur_detail($id_retur)
        ];

        if (!$data['retur']) show_404();

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('retur/detail',$data);
        $this->load->view('dashboard/footer');
    }
}
