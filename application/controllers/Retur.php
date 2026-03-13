<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Retur_model $Retur_model
 * @property Transaksi_model $Transaksi_model
 */

class Retur extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Retur_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Audit_log_model');
    }
public function form($id_transaksi)
{
    $id_transaksi = (int) $id_transaksi;

    // ===============================
    // CEK TRANSAKSI
    // ===============================
    $transaksi = $this->Transaksi_model->get_by_id($id_transaksi);
    if (!$transaksi) {
        show_404();
    }

    // ===============================
    // CEK AKSES ONE TIME SESSION
    // ===============================
    $allow = (int) $this->session->userdata('allow_retur');

    if ($allow !== $id_transaksi) {
        $this->session->set_flashdata('error', 'Akses retur tidak valid.');
        redirect('transaksi/detail/' . $id_transaksi);
        return;
    }

    // ===============================
    // AMBIL DETAIL TRANSAKSI
    // ===============================
    $detail = $this->db
        ->select('td.id_detail, td.id_barang, td.qty, td.harga, 
                  b.nama_barang, b.kode_barang')
        ->from('transaksi_detail td')
        ->join('barang b', 'b.id_barang = td.id_barang')
        ->where('td.id_transaksi', $id_transaksi)
        ->get()
        ->result();

    if (!$detail) {
        $this->session->set_flashdata('error', 'Detail transaksi tidak ditemukan.');
        redirect('transaksi/detail/' . $id_transaksi);
        return;
    }

    $detail_final   = [];
    $masih_ada_sisa = false;

    foreach ($detail as $d) {

        // ===============================
        // HITUNG TOTAL RETUR SEBELUMNYA
        // ===============================
        $retur = $this->db
            ->select_sum('qty')
            ->where('id_detail', $d->id_detail)
            ->get('retur_detail')
            ->row();

        $total_retur = (int) ($retur->qty ?? 0);

        $sisa = $d->qty - $total_retur;
        if ($sisa < 0) {
            $sisa = 0;
        }

        if ($sisa > 0) {
            $masih_ada_sisa = true;

            $d->qty_beli = $d->qty;
            $d->sisa_qty = $sisa;

            $detail_final[] = $d;
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

    $data = [
        'transaksi' => $transaksi,
        'detail'    => $detail_final
    ];

    $this->load->view('dashboard/header');
    $this->load->view('dashboard/sidebar');
    $this->load->view('retur/form', $data);
    $this->load->view('dashboard/footer');
}


public function simpan()
{
    $this->load->model('Retur_model');

    $id_transaksi = (int) $this->input->post('id_transaksi', TRUE);
    $items        = $this->input->post('items');

    // ================= VALIDASI AWAL =================
    if (!$id_transaksi || !is_array($items)) {
        $this->session->set_flashdata('error', 'Data retur tidak valid.');
        redirect('transaksi');
        return;
    }

    $ada_input = false;

    foreach ($items as $id_detail => $qty_input) {

        $id_detail = (int) $id_detail;
        $qty_input = (int) $qty_input;

        if ($qty_input <= 0) {
            continue;
        }

        $ada_input = true;

        // ================= AMBIL DETAIL TRANSAKSI =================
        $detail = $this->db
            ->where('id_detail', $id_detail)
            ->where('id_transaksi', $id_transaksi)
            ->get('transaksi_detail')
            ->row();

        if (!$detail) {
            $this->session->set_flashdata(
                'error',
                'Detail transaksi tidak ditemukan.'
            );
            redirect('retur/form/' . $id_transaksi);
            return;
        }

        // ================= HITUNG TOTAL RETUR SEBELUMNYA =================
        $retur_sebelumnya = $this->db
            ->select_sum('qty')
            ->where('id_detail', $id_detail)
            ->get('retur_detail')
            ->row();

        $total_retur = (int) ($retur_sebelumnya->qty ?? 0);
        $sisa        = $detail->qty - $total_retur;

        // ================= VALIDASI SISA =================
        if ($qty_input > $sisa) {
            $this->session->set_flashdata(
                'error',
                'Qty retur melebihi sisa. Sisa hanya ' . $sisa
            );
            redirect('retur/form/' . $id_transaksi);
            return;
        }
    }

    if (!$ada_input) {
        $this->session->set_flashdata(
            'error',
            'Tidak ada qty yang diretur.'
        );
        redirect('retur/form/' . $id_transaksi);
        return;
    }

    // ================= SIMPAN KE MODEL =================
    $result = $this->Retur_model->create_retur($id_transaksi, $items);

    // ================= HANDLE RESPONSE =================
    if (is_array($result) && isset($result['status']) && $result['status'] === true) {

        // hapus izin one-time akses
        $this->session->unset_userdata('allow_retur');

        $this->session->set_flashdata(
            'success',
            'Retur berhasil disimpan.'
        );

        redirect('transaksi/detail/' . $id_transaksi);
        return;

    } else {

        $message = is_array($result) && isset($result['message'])
            ? $result['message']
            : 'Gagal menyimpan retur.';

        $this->session->set_flashdata('error', $message);

        redirect('retur/form/' . $id_transaksi);
        return;
    }
}

    public function detail($id_transaksi)
{
    $retur = $this->Retur_model->get_by_transaksi($id_transaksi);

    if (!$retur) {
        show_404();
    }

    $detail = $this->Transaksi_model->get_detail($retur->id_retur);

    // ===============================
    // LOGIKA STATUS
    // ===============================
    $total_beli  = $this->Transaksi_model->get_by_id($id_transaksi)->total;
    $total_retur = $retur->total_retur;

    if ($total_retur <= 0) {
        $status_retur = 'belum';
    } elseif ($total_retur < $total_beli) {
        $status_retur = 'sebagian';
    } else {
        $status_retur = 'selesai';
    }

    $data = [
        'retur'        => $retur,
        'detail'       => $detail,
        'status_retur' => $status_retur
    ];

    $this->load->view('dashboard/header');
    $this->load->view('dashboard/sidebar');
    $this->load->view('retur/detail', $data);
    $this->load->view('dashboard/footer');
}
}
