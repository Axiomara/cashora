<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Session $session
 * @property CI_Pagination $pagination
 * @property CI_DB_query_builder $db
 * @property Transaksi_model $Transaksi_model
 * @property Barang_model $Barang_model
 * @property Retur_model $Retur_model
 * @property Pdf $pdf
 */
class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Barang_model');
        $this->load->model('Retur_model');
    }

    public function index() {

        $token = bin2hex(random_bytes(16));
        $this->session->set_userdata('trx_token', $token);

        $data = [
            'barang' => $this->Barang_model->get_all(),
            'trx_token' => $token
        ];

        $this->load->view('dashboard/header' );
        $this->load->view('dashboard/sidebar');
        $this->load->view('transaksi/transaksi', $data);
        $this->load->view('dashboard/footer');
    }

    public function simpan() {

    $tokenForm    = $this->input->post('trx_token', TRUE);
    $tokenSession = $this->session->userdata('trx_token');

    if (!$tokenForm || !$tokenSession || $tokenForm !== $tokenSession) {
        $this->session->set_flashdata('error', 'Transaksi sudah diproses / token tidak valid.');
        redirect('transaksi');
        return;
    }

    $this->session->unset_userdata('trx_token');

   
    $cart_json = $this->input->post('cart_json', TRUE);
    $bayar     = (int)$this->input->post('bayar', TRUE);

    if (empty($cart_json)) {
        $this->session->set_flashdata('error', 'Keranjang masih kosong!');
        redirect('transaksi');
        return;
    }

    $cart = json_decode($cart_json, true);

    if (!is_array($cart) || count($cart) === 0) {
        $this->session->set_flashdata('error', 'Data keranjang tidak valid!');
        redirect('transaksi');
        return;
    }

    $total       = 0;
    $detailItems = [];

    foreach ($cart as $item) {
        $id_barang = (int)($item['id_barang'] ?? 0);

        $qtyRaw = $item['qty'] ?? 0;
        $qty    = (int)$qtyRaw;

        if ($id_barang <= 0 || $qty <= 0) {
            $this->session->set_flashdata('error', 'Item keranjang tidak valid (Qty harus > 0).');
            redirect('transaksi');
            return;
        }

        $barang = $this->Barang_model->get_by_id($id_barang);

        if (!$barang) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan!');
            redirect('transaksi');
            return;
        }

        if ($qty > (int)$barang->stok) {
            $this->session->set_flashdata(
                'error',
                'Stok tidak cukup untuk ' . $barang->nama_barang . ' (Stok: ' . $barang->stok . ')'
            );
            redirect('transaksi');
            return;
        }

        $harga    = (int)$barang->harga_jual;
        $subtotal = $harga * $qty;

        $total += $subtotal;

        $detailItems[] = [
            'id_barang'   => (int)$barang->id_barang,
            'qty'         => $qty,
            'harga'       => $harga,
            'subtotal'    => $subtotal,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
        ];
    }

    if ($bayar < $total) {
        $this->session->set_flashdata('error', 'Uang bayar kurang!');
        redirect('transaksi');
        return;
    }

    $kembalian = $bayar - $total;

    $result = $this->Transaksi_model->create_transaksi($detailItems, $total, $bayar, $kembalian);

    if (!empty($result['status']) && $result['status'] === true) {
        redirect('transaksi/sukses/' . $result['id_transaksi']);
        return;
    }


    $msg = $result['message'] ?? 'Gagal menyimpan transaksi.';
    $this->session->set_flashdata('error', $msg);
    redirect('transaksi');
    return;
}


    public function nota_pdf($id_transaksi = null)
    {
        if (!$id_transaksi) redirect('transaksi/riwayat');

        $this->load->library('pdf');
        $this->load->model('Transaksi_model');

        $data['transaksi'] = $this->Transaksi_model->get_by_id($id_transaksi);
        $data['detail']    = $this->Transaksi_model->get_detail($id_transaksi);

        if (!$data['transaksi']) {
            show_404();
        }

        $html = $this->load->view('transaksi/pdf_nota_58mm', $data, true);
        $this->pdf->thermal($html, "NOTA_" . $data['transaksi']->kode_transaksi . ".pdf");
    }

    public function sukses($id_transaksi) {
    $data['id_transaksi'] = $id_transaksi;
    $this->load->view('transaksi/sukses', $data);
    }

        public function riwayat()
        {
            $this->load->library('pagination');

            $q        = trim((string)$this->input->get('q', TRUE));
            $from     = trim((string)$this->input->get('from', TRUE));
            $to       = trim((string)$this->input->get('to', TRUE));
            $pageParam = $this->input->get('page', TRUE);

            if ($q === '' && $from === '' && $to === '' && ($pageParam === null || $pageParam === '')) {
                if ($this->input->server('QUERY_STRING')) {
                    redirect('transaksi/riwayat');
                    return;
                }
            }

            $perPage = 10;

            $totalRows = $this->Transaksi_model->count_riwayat($q, $from, $to);

            $config['base_url'] = base_url('transaksi/riwayat');
            $config['total_rows'] = $totalRows;
            $config['per_page'] = $perPage;

            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'page';
            $config['reuse_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            $config['full_tag_open']  = '<nav><ul class="pagination justify-content-end mb-0">';
            $config['full_tag_close'] = '</ul></nav>';

            $config['num_tag_open']   = '<li class="page-item">';
            $config['num_tag_close']  = '</li>';

            $config['cur_tag_open']   = '<li class="page-item active"><a class="page-link" href="#">';
            $config['cur_tag_close']  = '</a></li>';

            $config['next_tag_open']  = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';

            $config['prev_tag_open']  = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';

            $config['first_tag_open']  = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';

            $config['last_tag_open']  = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';

            $config['attributes'] = ['class' => 'page-link'];

            $config['first_link'] = '«';
            $config['last_link']  = '»';
            $config['next_link']  = '›';
            $config['prev_link']  = '‹';

            $this->pagination->initialize($config);

            // ==========================
            // PAGE -> OFFSET
            // ==========================
            $page = (ctype_digit((string)$pageParam) && (int)$pageParam > 0) ? (int)$pageParam : 1;
            $offset = ($page - 1) * $perPage;

            $data['q']    = $q;
            $data['from'] = $from;
            $data['to']   = $to;

            $data['list'] = $this->Transaksi_model->get_riwayat_paging($q, $from, $to, $perPage, $offset);
            $data['pagination'] = $this->pagination->create_links();

            $data['totalRows'] = $totalRows;
            $data['perPage']   = $perPage;
            $data['offset']    = $offset;
            $data['page']      = $page;

            $this->load->view('dashboard/header' );
            $this->load->view('dashboard/sidebar');
            $this->load->view('transaksi/riwayat', $data);
            $this->load->view('dashboard/footer');
        }

 public function detail($id_transaksi)
{
    $this->load->model('Retur_model');

    $transaksi = $this->Transaksi_model->get_by_id($id_transaksi);
    $detail    = $this->Transaksi_model->get_detail($id_transaksi);

    if (!$transaksi) {
        show_404();
    }

    // ===============================
    // 1️⃣ Ambil total retur
    // ===============================
    $total_retur = $this->Retur_model
                        ->get_total_retur_by_transaksi($id_transaksi);

    // ===============================
    // 2️⃣ Ambil sisa qty per barang
    // ===============================
    $sisaData = $this->Retur_model
                     ->get_sisa_qty_by_transaksi($id_transaksi);

    $masih_ada_sisa = false;

    foreach ($sisaData as $row) {
        if ($row->qty_retur < $row->qty_beli) {
            $masih_ada_sisa = true;
            break;
        }
    }


    if ($total_retur == 0) {
        $status_retur = 'belum';
    } elseif (!$masih_ada_sisa) {
        $status_retur = 'selesai';
    } else {
        $status_retur = 'sebagian';
    }

   
    $retur = $this->Retur_model
                  ->get_by_transaksi($id_transaksi);

    $data = [
        'transaksi'       => $transaksi,
        'detail'          => $detail,
        'status_retur'    => $status_retur,
        'masih_ada_sisa'  => $masih_ada_sisa,
        'total_retur'     => $total_retur,   
        'retur'           => $retur         
    ];

    $this->load->view('dashboard/header');
    $this->load->view('dashboard/sidebar');
    $this->load->view('transaksi/detail', $data);
    $this->load->view('dashboard/footer');
}
   
   public function go_retur($id_transaksi = null)
{
    $this->load->model('Transaksi_model');
    $this->load->model('Retur_model');

    $id_transaksi = (int)$id_transaksi;
    if ($id_transaksi <= 0) {
        show_404();
        return;
    }

    $transaksi = $this->Transaksi_model->get_by_id($id_transaksi);
    if (!$transaksi) {
        show_404();
        return;
    }

    $sisaData = $this->Retur_model
                     ->get_sisa_qty_by_transaksi($id_transaksi);
    if (empty($sisaData)) {
        $this->session->set_flashdata(
            'error',
            'Data transaksi tidak memiliki detail barang.'
        );
        redirect('transaksi/detail/' . $id_transaksi);
        return;
    }

    $masih_ada_sisa = false;

    foreach ($sisaData as $row) {
        $qty_beli  = (int)$row->qty_beli;
        $qty_retur = (int)$row->qty_retur;

        if ($qty_retur < $qty_beli) {
            $masih_ada_sisa = true;
            break;
        }
    }

    if (!$masih_ada_sisa) {
        $this->session->set_flashdata(
            'error',
            'Semua barang dalam transaksi ini sudah diretur.'
        );
        redirect('transaksi/detail/' . $id_transaksi);
        return;
    }

    $this->session->set_userdata('allow_retur', $id_transaksi);

    redirect('retur/form/' . $id_transaksi);
}
}


