<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Session $session
 * @property CI_Pagination $pagination
 * @property CI_DB_query_builder $db
 * @property CI_Form_validation $form_validation
 * @property Barang_model $Barang_model
 * @property Audit_log_model $Audit_log_model
 */

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->library('form_validation');
        $this->load->model('Audit_log_model');
        $this->load->helper('rupiah');
    }

    public function index()
    {
        $this->load->library('pagination');

        // ================= INPUT (AMAN PHP 8+) =================
        $keyword = trim((string) $this->input->get('keyword', TRUE));
        $filter  = $this->input->get('filter', TRUE);
        $sort    = $this->input->get('sort', TRUE);
        $order   = $this->input->get('order', TRUE);

        // ================= VALIDASI KEYWORD =================

        // kosong → null
        if ($keyword === '') {
            $keyword = null;
        }

        // 🔥 hanya huruf, angka, spasi (anti manipulasi URL)
        if ($keyword !== null) {
            if (!preg_match('/^[a-zA-Z0-9\s]+$/', $keyword)) {
                redirect('barang'); // tolak jika aneh
            }

            // 🔥 batasi panjang (anti spam)
            if (strlen($keyword) > 50) {
                $keyword = substr($keyword, 0, 50);
            }
        }

        // ================= VALIDASI SORT =================
        $allowedSort = ['kode_barang', 'nama_barang', 'stok', 'harga_jual'];

        if (!in_array($sort, $allowedSort)) {
            $sort = 'id_barang';
        }

        $order = ($order === 'desc') ? 'desc' : 'asc';

        // ================= TOTAL DATA =================
        $total_rows = $this->Barang_model->count_filtered($keyword, $filter);

        // ================= PAGINATION =================
        $config['base_url'] = base_url('barang/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 5;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // style pagination
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mt-3">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Awal';
        $config['last_link']  = 'Akhir';
        $config['next_link']  = '&raquo;';
        $config['prev_link']  = '&laquo;';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        // ================= OFFSET =================
        $offset = (int) ($this->input->get('per_page') ?? 0);

        // ================= DATA =================
        $data['list_barang'] = $this->Barang_model->get_paginated_filtered(
            $config['per_page'],
            $offset,
            $keyword,
            $filter,
            $sort,
            $order
        );

        // ================= DATA VIEW =================
        $data['pagination']    = $this->pagination->create_links();
        $data['keyword']       = $keyword;
        $data['filter']        = $filter;
        $data['currentSort']   = $sort;
        $data['currentOrder']  = $order;
        $data['kode_auto']     = $this->Barang_model->generate_kode();

        // ================= LOAD VIEW =================
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

        $kode = $this->Barang_model->generate_kode();
        $barcode  = trim($this->input->post('barcode', TRUE));
        $nama     = trim($this->input->post('nama_barang', TRUE));
        $harga    = (int) $this->input->post('harga_jual', TRUE);
        $stok_input = $this->input->post('stok', TRUE);
        $stok = ($stok_input === '' || $stok_input === NULL) ? 0 : (int)$stok_input;
        $isi      = (int) $this->input->post('isi_karton', TRUE);

        // ================= VALIDASI BARCODE WAJIB =================
        if ($barcode === '' || $barcode === NULL) {
            $this->session->set_flashdata(
                'error_validation',
                'Barcode wajib diisi dan tidak boleh kosong.'
            );
            redirect('barang');
            return;
        }

        // Safety guard angka
        $harga = max(0, $harga);
        $stok  = max(0, $stok);
        $isi   = $isi > 0 ? $isi : 1;

        // ================= CEK KODE UNIK =================
        if ($this->Barang_model->get_by_kode($kode)) {
            $this->session->set_flashdata(
                'error_validation',
                'Kode Barang sudah digunakan.'
            );
            redirect('barang');
            return;
        }

        // ================= CEK BARCODE UNIK =================
        if ($this->Barang_model->get_by_barcode($barcode)) {
            $this->session->set_flashdata(
                'error_validation',
                'Barcode sudah digunakan oleh produk lain.'
            );
            redirect('barang');
            return;
        }

        // ================= INSERT DATA =================
        $data = [
            'kode_barang'         => $kode,
            'barcode'             => $barcode,
            'nama_barang'         => $nama,
            'harga_jual'          => $harga,
            'stok'                => $stok,
            'isi_karton'          => $isi,
            'harga_beli_terakhir' => 0,
            'supplier_terakhir'   => NULL,
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s')
        ];

        $insert = $this->Barang_model->insert($data);

        if ($insert) {

            // ================= AUDIT LOG =================
            $this->Audit_log_model->log(
                'Tambah Barang',
                'barang',
                $insert,
                'Menambahkan barang: ' . $nama . ' (' . $kode . ')'
            );

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
            'barcode',
            'Barcode',
            'required|trim'
        );
    }

    public function ajax_list()
    {
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
