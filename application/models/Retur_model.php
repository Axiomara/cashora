<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retur_model extends CI_Model
{

    public function generate_kode()
    {
        $last = $this->db->order_by('id_retur', 'DESC')->limit(1)->get('retur')->row();
        $next = $last ? $last->id_retur + 1 : 1;
        return 'RT' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    public function sudah_diretur($id_transaksi)
    {
        return $this->db
            ->where('id_transaksi', $id_transaksi)
            ->get('retur')
            ->row();
    }

    public function get_sisa_qty_by_transaksi($id_transaksi)
    {
        $query = $this->db
            ->select("
            td.id_barang,
            td.qty AS qty_beli,
            COALESCE(SUM(rd.qty),0) AS qty_retur
        ")
            ->from('transaksi_detail td')
            ->join('retur r', 'r.id_transaksi = td.id_transaksi', 'left')
            ->join(
                'retur_detail rd',
                'rd.id_retur = r.id_retur AND rd.id_barang = td.id_barang',
                'left'
            )
            ->where('td.id_transaksi', $id_transaksi)
            ->group_by(['td.id_barang', 'td.qty'])
            ->get();

        return $query->result();
    }

    public function get_by_transaksi($id_transaksi)
    {
        return $this->db
            ->select('r.*, rd.qty, rd.harga, rd.subtotal, b.nama_barang, b.kode_barang')
            ->from('retur r')
            ->join('retur_detail rd', 'rd.id_retur = r.id_retur')
            ->join('barang b', 'b.id_barang = rd.id_barang')
            ->where('r.id_transaksi', $id_transaksi)
            ->order_by('r.id_retur', 'DESC')
            ->get()
            ->result();
    }

    public function get_total_retur_by_transaksi($id_transaksi)
    {
        return $this->db
            ->select_sum('total_retur')
            ->where('id_transaksi', (int)$id_transaksi)
            ->get('retur')
            ->row()
            ->total_retur ?? 0;
    }

    public function get_status_retur_transaksi($id_transaksi)
    {

        $beli = $this->db
            ->select('id_barang, qty')
            ->from('transaksi_detail')
            ->where('id_transaksi', $id_transaksi)
            ->get()
            ->result_array();

        if (!$beli) return 'belum';

        $allFull  = true;
        $adaRetur = false;

        foreach ($beli as $item) {


            $returQty = $this->db
                ->select_sum('qty')
                ->from('retur_detail rd')
                ->join('retur r', 'r.id_retur = rd.id_retur')
                ->where('r.id_transaksi', $id_transaksi)
                ->where('rd.id_barang', $item['id_barang'])
                ->get()
                ->row()
                ->qty ?? 0;

            if ($returQty > 0) {
                $adaRetur = true;
            }

            if ($returQty < $item['qty']) {
                $allFull = false;
            }
        }

        if (!$adaRetur) return 'belum';
        if ($allFull)   return 'selesai';

        return 'sebagian';
    }


    public function is_transaksi_sudah_retur($id_transaksi)
    {
        return $this->db
            ->where('id_transaksi', (int)$id_transaksi)
            ->count_all_results('retur') > 0;
    }

    public function get_transaksi_detail($id_transaksi)
    {
        return $this->db
            ->select('td.*, b.nama_barang, b.kode_barang')
            ->from('transaksi_detail td')
            ->join('barang b', 'b.id_barang=td.id_barang')
            ->where('td.id_transaksi', $id_transaksi)
            ->get()
            ->result();
    }

    public function create_retur($id_transaksi, $items)
    {
        $this->db->trans_begin();

        $kode  = $this->generate_kode();
        $total = 0;

        // ===============================
        // INSERT HEADER RETUR
        // ===============================
        $this->db->insert('retur', [
            'kode_retur'   => $kode,
            'id_transaksi' => $id_transaksi,
            'tanggal'      => date('Y-m-d H:i:s'),
            'total_retur'  => 0
        ]);

        $id_retur = $this->db->insert_id();

        if (!$id_retur) {
            $this->db->trans_rollback();
            return [
                'status'  => false,
                'message' => 'Gagal membuat header retur'
            ];
        }

        // ===============================
        // LOOP ITEM RETUR
        // ===============================
        foreach ($items as $id_detail => $qty_input) {

            $id_detail = (int) $id_detail;
            $qty_input = (int) $qty_input;

            if ($qty_input <= 0) {
                continue;
            }

            // ===============================
            // AMBIL DETAIL TRANSAKSI
            // ===============================
            $detail = $this->db
                ->where('id_detail', $id_detail)
                ->where('id_transaksi', $id_transaksi)
                ->get('transaksi_detail')
                ->row();

            if (!$detail) {
                $this->db->trans_rollback();
                return [
                    'status'  => false,
                    'message' => 'Detail transaksi tidak ditemukan'
                ];
            }

            // ===============================
            // CEK SISA RETUR
            // ===============================
            $qty_beli  = (int) $detail->qty;
            $qty_retur = (int) $detail->qty_retur;
            $sisa      = $qty_beli - $qty_retur;

            if ($qty_input > $sisa) {
                $this->db->trans_rollback();
                return [
                    'status'  => false,
                    'message' => 'Qty retur melebihi sisa. Sisa hanya ' . $sisa
                ];
            }

            // ===============================
            // HITUNG SUBTOTAL
            // ===============================
            $subtotal = $qty_input * $detail->harga;
            $total   += $subtotal;

            // ===============================
            // INSERT DETAIL RETUR
            // ===============================
            $this->db->insert('retur_detail', [
                'id_retur'  => $id_retur,
                'id_detail' => $id_detail,
                'id_barang' => $detail->id_barang,
                'qty'       => $qty_input,
                'harga'     => $detail->harga,
                'subtotal'  => $subtotal
            ]);

            // ===============================
            // UPDATE QTY_RETUR
            // ===============================
            $this->db->set('qty_retur', 'qty_retur+' . $qty_input, false)
                ->where('id_detail', $id_detail)
                ->update('transaksi_detail');

            // ===============================
            // AMBIL STOK SEBELUM
            // ===============================
            $barang = $this->db
                ->where('id_barang', $detail->id_barang)
                ->get('barang')
                ->row();

            if (!$barang) {
                $this->db->trans_rollback();
                return [
                    'status'  => false,
                    'message' => 'Barang tidak ditemukan'
                ];
            }

            $stok_sebelum = (int)$barang->stok;
            $stok_sesudah = $stok_sebelum + $qty_input;

            // ===============================
            // UPDATE STOK BARANG
            // ===============================
            $this->db->set('stok', $stok_sesudah)
                ->where('id_barang', $detail->id_barang)
                ->update('barang');

            // ===============================
            // INSERT STOK LOG
            // ===============================
            $this->db->insert('stok_log', [
                'id_barang'     => $detail->id_barang,
                'tipe'          => 'retur',
                'qty'           => $qty_input,
                'stok_sebelum'  => $stok_sebelum,
                'stok_sesudah'  => $stok_sesudah,
                'referensi'     => $kode,
                'keterangan'    => 'Retur transaksi',
                'created_at'    => date('Y-m-d H:i:s')
            ]);
        }

        // ===============================
        // UPDATE TOTAL RETUR
        // ===============================
        $this->db->where('id_retur', $id_retur)
            ->update('retur', [
                'total_retur' => $total
            ]);

        // ===============================
        // CEK STATUS TRANSAKSI
        // ===============================
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return [
                'status'  => false,
                'message' => 'Gagal menyimpan retur (DB Error)'
            ];
        }

        $this->db->trans_commit();

        return [
            'status'   => true,
            'id_retur' => $id_retur
        ];
    }


    public function get_retur($id_retur)
    {
        return $this->db->where('id_retur', $id_retur)->get('retur')->row();
    }

    public function get_retur_detail($id_retur)
    {
        return $this->db
            ->select('rd.*, b.nama_barang, b.kode_barang')
            ->from('retur_detail rd')
            ->join('barang b', 'b.id_barang=rd.id_barang')
            ->where('rd.id_retur', $id_retur)
            ->get()
            ->result();
    }
}
