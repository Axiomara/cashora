<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

    private $table_transaksi = 'transaksi';
    private $table_detail    = 'transaksi_detail';

    // generate kode transaksi TRX000001
    private function generate_kode_transaksi()
    {
        $last = $this->db->select('id_transaksi')
            ->from($this->table_transaksi)
            ->order_by('id_transaksi', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $next = $last ? ((int)$last->id_transaksi + 1) : 1;
        return 'TRX' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    // create transaksi + detail + update stok (transaction-safe)
    public function create_transaksi($items, $total, $bayar, $kembalian)
    {
        $this->db->trans_begin();

        try {
            $kode_transaksi = $this->generate_kode_transaksi();

            $dataTransaksi = [
                'kode_transaksi' => $kode_transaksi,
                'tanggal'        => date('Y-m-d H:i:s'),
                'total'          => (int)$total,
                'bayar'          => (int)$bayar,
                'kembalian'      => (int)$kembalian,
            ];

            $this->db->insert($this->table_transaksi, $dataTransaksi);
            $id_transaksi = $this->db->insert_id();

            foreach ($items as $it) {
                // insert detail
                $dataDetail = [
                    'id_transaksi' => $id_transaksi,
                    'id_barang'    => (int)$it['id_barang'],
                    'qty'          => (int)$it['qty'],
                    'harga'        => (int)$it['harga'],
                    'subtotal'     => (int)$it['subtotal'],
                ];
                $this->db->insert($this->table_detail, $dataDetail);

                // update stok barang (kurangi)
                $this->db->set('stok', 'stok - ' . (int)$it['qty'], false);
                $this->db->where('id_barang', (int)$it['id_barang']);
                $this->db->update('barang');
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return ['status' => false, 'message' => 'Gagal menyimpan transaksi!'];
            }

            $this->db->trans_commit();
            return ['status' => true, 'id_transaksi' => $id_transaksi];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function get_all()
    {
        return $this->db->select('*')
            ->from($this->table_transaksi)
            ->order_by('id_transaksi', 'DESC')
            ->get()
            ->result();
    }

    public function get_by_id($id_transaksi)
    {
        return $this->db->select('*')
            ->from($this->table_transaksi)
            ->where('id_transaksi', (int)$id_transaksi)
            ->limit(1)
            ->get()
            ->row();
    }

    public function get_detail($id_transaksi)
    {
        return $this->db->select('td.*, b.kode_barang, b.nama_barang')
            ->from($this->table_detail . ' td')
            ->join('barang b', 'b.id_barang = td.id_barang', 'left')
            ->where('td.id_transaksi', (int)$id_transaksi)
            ->get()
            ->result();
    }
}
