<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{

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
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword', TRUE);
        $page    = $this->uri->segment(2);
        $limit   = 10;

        if (!$page) {
            $page = 0;
        }

        // total data
        if (!empty($keyword)) {
            $total_rows = $this->Supplier_model->count_search($keyword);
        } else {
            $total_rows = $this->Supplier_model->count_all();
        }

        $config['base_url'] = base_url('supplier');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 2;

        $config['reuse_query_string'] = TRUE;

        // style bootstrap
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-end">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';

        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        if (!empty($keyword)) {
            $supplier = $this->Supplier_model->search_pagination($limit, $page, $keyword);
        } else {
            $supplier = $this->Supplier_model->get_pagination($limit, $page);
        }

        $data = [
            'supplier'   => $supplier,
            'pagination' => $this->pagination->create_links()
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
