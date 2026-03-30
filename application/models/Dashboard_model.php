<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    // =========================
    // OMZET HARI INI
    // =========================
    public function get_omzet_hari_ini()
    {
        return $this->db
            ->select_sum('total')
            ->where('DATE(tanggal) = CURDATE()', null, false)
            ->get('transaksi')
            ->row()
            ->total ?? 0;
    }

    public function get_jumlah_transaksi_hari_ini()
    {
        return $this->db
            ->where('DATE(tanggal) = CURDATE()', null, false)
            ->count_all_results('transaksi');
    }

    // =========================
    // TOTAL BARANG
    // =========================
    public function get_total_barang()
    {
        return $this->db->count_all('barang');
    }

    // =========================
    // STOK MENIPIS (< 5)
    // =========================
    public function get_stok_menipis($limit = null)
    {
        $this->db->where('stok <', 5)
            ->order_by('stok', 'ASC');

        if ($limit) {
            $this->db->limit($limit);
        }

        return $this->db->get('barang')->result();
    }

    // =========================
    // TRANSAKSI TERBARU (HARI INI SAJA)
    // =========================
    public function get_transaksi_terbaru_hari_ini($limit = 5)
    {
        return $this->db
            ->where('DATE(tanggal) = CURDATE()', null, false)
            ->order_by('tanggal', 'DESC')
            ->limit($limit)
            ->get('transaksi')
            ->result();
    }

    public function get_penjualan_per_hari($dari, $sampai)
    {
        return $this->db
            ->select('DATE(tanggal) as tanggal, SUM(total) as total')
            ->from('transaksi')
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->group_by('DATE(tanggal)')
            ->order_by('tanggal', 'ASC')
            ->get()
            ->result();
    }

    public function get_transaksi_per_hari($dari, $sampai)
    {
        return $this->db
            ->select('DATE(tanggal) as tanggal, COUNT(*) as total')
            ->from('transaksi')
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->group_by('DATE(tanggal)')
            ->order_by('tanggal', 'ASC')
            ->get()
            ->result();
    }

    public function get_omzet_harian()
    {
        $today = date('Y-m-d');

        // omzet kotor
        $omzet_kotor = $this->db
            ->select_sum('total')
            ->where('DATE(tanggal)', $today)
            ->get('transaksi')
            ->row()->total ?? 0;

        // retur
        $retur = $this->db
            ->select('SUM(rd.qty * td.harga) as total')
            ->from('retur_detail rd')
            ->join('transaksi_detail td', 'td.id_detail = rd.id_detail')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->where('DATE(t.tanggal)', $today)
            ->get()
            ->row()->total ?? 0;

        return [
            'omzet_kotor'  => $omzet_kotor,
            'retur'        => $retur,
            'omzet_bersih' => $omzet_kotor - $retur
        ];
    }
}
