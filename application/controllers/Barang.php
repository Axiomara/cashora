<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Tambah Barang";
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('product/input-produk', $data);
        $this->load->view('dashboard/footer');
    }

    public function simpan() {
        $this->_rules_tambah();

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('barang');
            return;
        }

        $data = [
            'kode_barang' => strtoupper($this->input->post('kode_barang', TRUE)),
            'nama_barang' => $this->input->post('nama_barang', TRUE),
            'harga_jual'  => (int)$this->input->post('harga_jual', TRUE),
            'stok'        => (int)$this->input->post('stok', TRUE),
        ];

        if ($data['harga_jual'] < 0) $data['harga_jual'] = 0;
        if ($data['stok'] < 0) $data['stok'] = 0;

        $insert = $this->Barang_model->insert($data);

        if ($insert) {
        $this->session->set_flashdata('success', 'Barang berhasil ditambahkan ✅');
        redirect('barang');
        return;
    }

    $this->session->set_flashdata('error', 'Barang gagal ditambahkan ❌');
    redirect('barang');
    }

    private function _rules_tambah() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim|is_unique[barang.kode_barang]');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer|greater_than_equal_to[0]');

        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_message('is_unique', '{field} sudah dipakai, gunakan kode lain.');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka.');
        $this->form_validation->set_message('integer', '{field} harus berupa angka bulat.');
        $this->form_validation->set_message('greater_than_equal_to', '{field} tidak boleh kurang dari 0.');
    }

    
}
