<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

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
}