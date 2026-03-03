<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Supplier_model');
        $this->load->library('form_validation');
    }

    // ===============================
    // HALAMAN LIST SUPPLIER
    // ===============================
   public function index()
{
    $keyword = $this->input->get('keyword', TRUE);

    if (!empty($keyword)) {
        $supplier = $this->Supplier_model->search($keyword);
    } else {
        $supplier = $this->Supplier_model->get_all();
    }

    $data = [
        'supplier' => $supplier
    ];

    $this->load->view('dashboard/header');
    $this->load->view('dashboard/sidebar');
    $this->load->view('supplier/index', $data);
    $this->load->view('dashboard/footer');
}

    // ===============================
    // HALAMAN FORM TAMBAH SUPPLIER
    // ===============================
    public function tambah()
    {
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('supplier/form');
        $this->load->view('dashboard/footer');
    }

    // ===============================
    // SIMPAN SUPPLIER
    // ===============================
    public function simpan()
{
    // ===============================
    // 1. VALIDASI
    // ===============================
    $this->form_validation->set_rules(
        'nama_supplier',
        'Nama Supplier',
        'required|trim'
    );

    $this->form_validation->set_rules(
        'no_hp',
        'No HP',
        'trim'
    );

    $this->form_validation->set_rules(
        'alamat',
        'Alamat',
        'trim'
    );

    $this->form_validation->set_rules(
        'keterangan',
        'Keterangan',
        'trim'
    );

    if ($this->form_validation->run() === FALSE) {
        $this->tambah();
        return;
    }

    // ===============================
    // 2. AMBIL & SANITASI DATA
    // ===============================
    $data = [
        'nama_supplier' => strtoupper(
            trim($this->input->post('nama_supplier', TRUE))
        ),
        'no_hp'      => trim($this->input->post('no_hp', TRUE)),
        'alamat'     => trim($this->input->post('alamat', TRUE)),
        'keterangan' => trim($this->input->post('keterangan', TRUE)),
    ];

    // ===============================
    // 3. SIMPAN KE MODEL
    // ===============================
    $insert_id = $this->Supplier_model->insert($data);

    // ===============================
    // 4. HANDLE RESPONSE
    // ===============================
    if ($insert_id) {

        $this->session->set_flashdata(
            'success',
            'Supplier berhasil ditambahkan.'
        );

        redirect('supplier');
        return;

    } else {

        log_message('error', 'Gagal insert supplier: ' . json_encode($data));

        $this->session->set_flashdata(
            'error',
            'Gagal menambahkan supplier. Silakan coba lagi.'
        );

        redirect('supplier/tambah');
        return;
    }
}
}