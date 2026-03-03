<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model {

    // ===============================
    // Ambil semua pembelian (header)
    // ===============================
    public function get_all()
    {
        return $this->db
            ->select('p.*, s.nama_supplier')
            ->from('pembelian p')
            ->join('supplier s', 's.id_supplier = p.id_supplier')
            ->order_by('p.id_pembelian', 'DESC')
            ->get()
            ->result();
    }

    // ===============================
    // Ambil pembelian by ID
    // ===============================
    public function get_by_id($id_pembelian)
    {
        return $this->db
            ->select('p.*, s.nama_supplier')
            ->from('pembelian p')
            ->join('supplier s', 's.id_supplier = p.id_supplier')
            ->where('p.id_pembelian', $id_pembelian)
            ->get()
            ->row();
    }

    // ===============================
    // Ambil detail pembelian
    // ===============================
    public function get_detail($id_pembelian)
    {
        return $this->db
            ->select('pd.*, b.nama_barang, b.kode_barang')
            ->from('pembelian_detail pd')
            ->join('barang b', 'b.id_barang = pd.id_barang')
            ->where('pd.id_pembelian', $id_pembelian)
            ->get()
            ->result();
    }

    // ===============================
    // Insert pembelian + detail + tambah stok
    // ===============================
    public function insert($header, $items)
    {
        $this->db->trans_begin();

        // Insert header
        $this->db->insert('pembelian', $header);
        $id_pembelian = $this->db->insert_id();

        $total = 0;

        foreach ($items as $item) {

            $id_barang = (int)$item['id_barang'];
            $qty       = (int)$item['qty'];
            $harga     = (int)$item['harga'];

            if ($id_barang <= 0 || $qty <= 0) continue;

            $subtotal = $qty * $harga;
            $total += $subtotal;

            // Insert detail
            $this->db->insert('pembelian_detail', [
                'id_pembelian' => $id_pembelian,
                'id_barang'    => $id_barang,
                'qty'          => $qty,
                'harga'        => $harga,
                'subtotal'     => $subtotal
            ]);

            // 🔥 Tambah stok
            $this->db->set('stok', 'stok + ' . $qty, FALSE)
                     ->where('id_barang', $id_barang)
                     ->update('barang');
        }

        // Update total
        $this->db->where('id_pembelian', $id_pembelian)
                 ->update('pembelian', ['total' => $total]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $id_pembelian;
    }

    // ===============================
    // Hapus pembelian (rollback stok)
    // ===============================
    public function delete($id_pembelian)
    {
        $this->db->trans_begin();

        $detail = $this->get_detail($id_pembelian);

        foreach ($detail as $d) {
            // Kurangi stok lagi
            $this->db->set('stok', 'stok - ' . $d->qty, FALSE)
                     ->where('id_barang', $d->id_barang)
                     ->update('barang');
        }

        $this->db->where('id_pembelian', $id_pembelian)
                 ->delete('pembelian_detail');

        $this->db->where('id_pembelian', $id_pembelian)
                 ->delete('pembelian');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

        public function get_all_with_detail() {
        $this->db->select('
            p.id_pembelian,
            p.kode_pembelian,
            p.tanggal,
            p.total,
            s.nama_supplier
        ');
        $this->db->from('pembelian p');
        $this->db->join('supplier s', 's.id_supplier = p.id_supplier');
        $this->db->order_by('p.id_pembelian', 'DESC');

        $pembelian = $this->db->get()->result();

        foreach ($pembelian as $p) {

            $this->db->select('
                d.*,
                b.kode_barang,
                b.nama_barang
            ');
            $this->db->from('pembelian_detail d');
            $this->db->join('barang b', 'b.id_barang = d.id_barang');
            $this->db->where('d.id_pembelian', $p->id_pembelian);

            $p->detail = $this->db->get()->result();
        }

        return $pembelian;
    }
}