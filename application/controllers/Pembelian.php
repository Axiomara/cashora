<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pembelian_model');
        $this->load->model('Supplier_model');
        $this->load->model('Barang_model');
        $this->load->model('Audit_log_model');
    }

    // ===============================
    // FORM PEMBELIAN
    // ===============================
    public function index()
    {
        $this->load->model('Pembelian_model');
        $this->load->model('Supplier_model');
        $this->load->model('Barang_model');

        $data['supplier']  = $this->Supplier_model->get_all();
        $data['barang']    = $this->Barang_model->get_all();
        $data['pembelian'] = $this->Pembelian_model->get_all_with_detail();

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('pembelian/form', $data);
        $this->load->view('dashboard/footer');
    }

    public function simpan()
    {
        $id_supplier = (int) $this->input->post('id_supplier', TRUE);
        $tanggal     = $this->input->post('tanggal', TRUE);
        $items       = $this->input->post('items');

        if ($id_supplier <= 0 || empty($items) || !is_array($items)) {
            $this->session->set_flashdata('error', 'Data pembelian tidak valid.');
            redirect('pembelian');
            return;
        }

        $this->db->trans_begin();

        $total = 0;

        $kode_pembelian = $this->_generate_kode();


        $this->db->insert('pembelian', [
            'kode_pembelian' => $kode_pembelian,
            'id_supplier'    => $id_supplier,
            'tanggal'        => $tanggal,
            'total'          => 0
        ]);

        $id_pembelian = $this->db->insert_id();

        if (!$id_pembelian) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal membuat header pembelian.');
            redirect('pembelian');
            return;
        }

        foreach ($items as $item) {

            $id_barang   = isset($item['id_barang']) ? (int)$item['id_barang'] : 0;
            $qty_input   = isset($item['qty_input']) ? (int)$item['qty_input'] : 0;
            $satuan      = isset($item['satuan']) ? $item['satuan'] : 'pcs';
            $harga_input = isset($item['harga_input']) ? (float)$item['harga_input'] : 0;
            $isi_karton  = isset($item['isi_karton']) ? (int)$item['isi_karton'] : 1;

            if ($id_barang <= 0 || $qty_input <= 0 || $harga_input < 0) {
                continue;
            }

            if ($satuan === 'karton') {

                if ($isi_karton <= 0) {
                    $isi_karton = 1;
                }

                $qty = $qty_input * $isi_karton;
                $subtotal = $qty_input * $harga_input;

                $this->db->where('id_barang', $id_barang)
                    ->update('barang', [
                        'isi_karton' => $isi_karton
                    ]);
            } else {

                $qty = $qty_input;
                $isi_karton = 1;
                $subtotal = $qty_input * $harga_input;
            }

            $harga_beli = ($qty > 0) ? ($subtotal / $qty) : 0;

            $total += $subtotal;


            $insertDetail = $this->db->insert('pembelian_detail', [
                'id_pembelian' => $id_pembelian,
                'id_barang'    => $id_barang,
                'qty_input'    => $qty_input,
                'satuan'       => $satuan,
                'isi_karton'   => $isi_karton,
                'qty'          => $qty,
                'harga_input'  => $harga_input,
                'harga_beli'   => $harga_beli,
                'subtotal'     => $subtotal
            ]);

            if (!$insertDetail) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal menyimpan detail pembelian.');
                redirect('pembelian');
                return;
            }

            $this->db->set('stok', 'stok + ' . $qty, FALSE)
                ->where('id_barang', $id_barang)
                ->update('barang');

            $this->db->where('id_barang', $id_barang)
                ->update('barang', [
                    'harga_beli_terakhir' => $harga_beli,
                    'supplier_terakhir'   => $id_supplier,
                    'updated_at'          => date('Y-m-d H:i:s')
                ]);


            $this->Audit_log_model->log(
                'Pembelian Barang',
                'barang',
                $id_barang,
                'Stok bertambah ' . $qty . ' dari pembelian ' . $kode_pembelian
            );
        }

        $this->db->where('id_pembelian', $id_pembelian)
            ->update('pembelian', [
                'total' => $total
            ]);

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan pembelian.');
        } else {

            $this->db->trans_commit();


            $this->Audit_log_model->log(
                'Transaksi Pembelian',
                'pembelian',
                $id_pembelian,
                'Membuat pembelian ' . $kode_pembelian . ' total Rp ' . number_format($total)
            );

            $this->session->set_flashdata('success', 'Pembelian berhasil disimpan.');
        }

        redirect('pembelian');
    }

    private function _generate_kode()
    {
        $date = date('Ymd');
        $this->db->like('kode_pembelian', 'PB-' . $date, 'after');
        $this->db->from('pembelian');
        $count = $this->db->count_all_results() + 1;

        return 'PB-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
