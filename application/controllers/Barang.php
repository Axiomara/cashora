<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->library('form_validation');
        $this->load->model('Audit_log_model');
    }

    public function index()
    {
        $keyword = $this->input->get('keyword', true);
        $filter  = $this->input->get('filter', true);
        $sort    = $this->input->get('sort', true);
        $order   = $this->input->get('order', true);

        $allowedSort = ['kode_barang', 'nama_barang', 'stok', 'harga_jual'];

        if (!in_array($sort, $allowedSort)) {
            $sort = 'id_barang';
        }

        $order = ($order === 'desc') ? 'desc' : 'asc';

        $data['list_barang'] = $this->Barang_model
            ->get_filtered($keyword, $filter, $sort, $order);

        $data['keyword'] = $keyword;
        $data['filter']  = $filter;
        $data['sort']    = $sort;
        $data['order']   = $order;
        $data['kode_auto'] = $this->Barang_model->generate_kode();

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
