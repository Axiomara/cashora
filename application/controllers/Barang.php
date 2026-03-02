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
    $keyword = $this->input->get('keyword', true);
    $filter  = $this->input->get('filter', true);
    $sort    = $this->input->get('sort', true);
    $order   = $this->input->get('order', true);

    // whitelist field supaya aman
    $allowedSort = ['kode_barang','nama_barang','stok','harga_jual'];

    if (!in_array($sort, $allowedSort)) {
        $sort = 'id_barang'; // default sorting
    }

    $order = ($order === 'desc') ? 'desc' : 'asc';

    $data['list_barang'] = $this->Barang_model
        ->get_filtered($keyword, $filter, $sort, $order);

    $data['keyword'] = $keyword;
    $data['filter']  = $filter;
    $data['sort']    = $sort;
    $data['order']   = $order;

    $this->load->view('dashboard/header');
    $this->load->view('dashboard/sidebar');
    $this->load->view('product/input-produk', $data);
    $this->load->view('dashboard/footer');
}
    public function simpan()
{
    $this->_rules_tambah();

    if ($this->form_validation->run() == FALSE) {

        $this->session->set_flashdata(
            'error_validation',
            validation_errors('<div class="mb-1">', '</div>')
        );

        redirect('barang');
        return;
    }

    $kode = strtoupper(trim($this->input->post('kode_barang', TRUE)));

    $data = [
        'kode_barang' => $kode,
        'nama_barang' => trim($this->input->post('nama_barang', TRUE)),
        'harga_jual'  => (int) $this->input->post('harga_jual', TRUE),
        'stok'        => (int) $this->input->post('stok', TRUE),
    ];

    // Safety guard tambahan
    $data['harga_jual'] = max(0, $data['harga_jual']);
    $data['stok']       = max(0, $data['stok']);

    // Cek ulang jika ternyata sudah ada (double protection)
    $cek = $this->Barang_model->get_by_kode($kode);
    if ($cek) {
        $this->session->set_flashdata(
            'error_validation',
            'Kode Barang sudah digunakan. Gunakan kode lain.'
        );
        redirect('barang');
        return;
    }

    $insert = $this->Barang_model->insert($data);

    if ($insert) {
        $this->session->set_flashdata(
            'success',
            'Barang berhasil ditambahkan.'
        );
    } else {
        $this->session->set_flashdata(
            'error_validation',
            'Terjadi kesalahan saat menyimpan data.'
        );
    }

    redirect('barang');
}

    private function _rules_tambah()
{
    $this->form_validation->set_rules(
        'kode_barang',
        'Kode Barang',
        'required|trim|is_unique[barang.kode_barang]',
        [
            'required'  => 'Kode Barang wajib diisi.',
            'is_unique' => 'Kode Barang sudah digunakan. Gunakan kode lain.'
        ]
    );

    $this->form_validation->set_rules(
        'nama_barang',
        'Nama Barang',
        'required|trim',
        [
            'required' => 'Nama Barang wajib diisi.'
        ]
    );

    $this->form_validation->set_rules(
        'harga_jual',
        'Harga Jual',
        'required|numeric|greater_than_equal_to[0]',
        [
            'required' => 'Harga Jual wajib diisi.',
            'numeric'  => 'Harga Jual harus berupa angka.',
            'greater_than_equal_to' => 'Harga tidak boleh kurang dari 0.'
        ]
    );

    $this->form_validation->set_rules(
        'stok',
        'Stok',
        'required|integer|greater_than_equal_to[0]',
        [
            'required' => 'Stok wajib diisi.',
            'integer'  => 'Stok harus berupa angka bulat.',
            'greater_than_equal_to' => 'Stok tidak boleh kurang dari 0.'
        ]
    );
}

    public function ajax_list() {
    $keyword = $this->input->get('keyword', TRUE);
    $filter  = $this->input->get('filter', TRUE);
    $sort    = $this->input->get('sort', TRUE);
    $order   = $this->input->get('order', TRUE);

    $data = $this->Barang_model
                 ->search_barang($keyword, $filter, $sort, $order);

    $this->load->view('product/table_produk', [
        'list_barang' => $data
    ]);
    
    }

    
}
