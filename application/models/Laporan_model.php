<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    // ================= PEMBELIAN =================
    public function get_pembelian($dari, $sampai)
    {
        return $this->db
            ->select('p.*, s.nama_supplier')
            ->from('pembelian p')
            ->join('supplier s', 's.id_supplier = p.id_supplier')
            ->where('DATE(p.tanggal) >=', $dari)
            ->where('DATE(p.tanggal) <=', $sampai)
            ->order_by('p.tanggal', 'DESC')
            ->get()
            ->result();
    }

    // ================= PENJUALAN =================
    public function get_penjualan($dari, $sampai)
    {
        return $this->db
            ->from('transaksi')
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->order_by('tanggal', 'DESC')
            ->get()
            ->result();
    }

    // ================= DETAIL PENJUALAN =================
    public function get_detail_penjualan($dari, $sampai)
    {
        return $this->db
            ->select('t.kode_transaksi, b.nama_barang, td.qty, td.subtotal')
            ->from('transaksi_detail td')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->join('barang b', 'b.id_barang = td.id_barang')
            ->where('DATE(t.tanggal) >=', $dari)
            ->where('DATE(t.tanggal) <=', $sampai)
            ->get()
            ->result();
    }

    // ================= TOTAL =================
    public function get_total($dari, $sampai)
    {
        $pembelian = $this->db
            ->select('COALESCE(SUM(total),0) as total')
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->get('pembelian')
            ->row()->total;

        $penjualan = $this->db
            ->select('COALESCE(SUM(total),0) as total')
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->get('transaksi')
            ->row()->total;

        return [
            'pembelian' => $pembelian,
            'penjualan' => $penjualan,
            'laba'      => $penjualan - $pembelian // tetap ada (cash flow)
        ];
    }

    // ================= LABA REAL =================
    public function get_laba_real($dari, $sampai)
    {
        $penjualan = $this->db
            ->select('SUM(td.subtotal) as total')
            ->from('transaksi_detail td')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->where('DATE(t.tanggal) >=', $dari)
            ->where('DATE(t.tanggal) <=', $sampai)
            ->get()->row()->total ?? 0;

        $hpp = $this->db
            ->select('SUM(td.qty * b.harga_beli_terakhir) as total')
            ->from('transaksi_detail td')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->join('barang b', 'b.id_barang = td.id_barang')
            ->where('DATE(t.tanggal) >=', $dari)
            ->where('DATE(t.tanggal) <=', $sampai)
            ->get()->row()->total ?? 0;

        // 🔥 TAMBAHAN RETUR
        $retur = $this->get_total_retur($dari, $sampai);

        return $penjualan - $hpp - $retur;
    }

    // ================= BARANG TERLARIS =================
    public function get_barang_terlaris($dari, $sampai)
    {
        return $this->db
            ->select('b.nama_barang, SUM(td.qty) as total_terjual')
            ->from('transaksi_detail td')
            ->join('barang b', 'b.id_barang = td.id_barang')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->where('DATE(t.tanggal) >=', $dari)
            ->where('DATE(t.tanggal) <=', $sampai)
            ->group_by('td.id_barang')
            ->order_by('total_terjual', 'DESC')
            ->limit(5)
            ->get()
            ->result();
    }

    // ================= LABA PER BARANG =================
    public function get_laba_per_barang($dari, $sampai)
    {
        return $this->db
            ->select('b.nama_barang,
                      SUM(td.qty) as total_terjual,
                      SUM(td.subtotal) as total_penjualan,
                      SUM(td.qty * b.harga_beli_terakhir) as total_hpp,
                      (SUM(td.subtotal) - SUM(td.qty * b.harga_beli_terakhir)) as laba')
            ->from('transaksi_detail td')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->join('barang b', 'b.id_barang = td.id_barang')
            ->where('DATE(t.tanggal) >=', $dari)
            ->where('DATE(t.tanggal) <=', $sampai)
            ->group_by('td.id_barang')
            ->order_by('laba', 'DESC')
            ->get()
            ->result();
    }

    // ================= JUMLAH TRANSAKSI =================
    public function count_transaksi($dari, $sampai)
    {
        return $this->db
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->count_all_results('transaksi');
    }

    // ================= SUPPLIER =================
    public function get_pembelian_per_supplier($dari, $sampai)
    {
        return $this->db
            ->select('s.nama_supplier, SUM(p.total) as total_pembelian')
            ->from('pembelian p')
            ->join('supplier s', 's.id_supplier = p.id_supplier')
            ->where('DATE(p.tanggal) >=', $dari)
            ->where('DATE(p.tanggal) <=', $sampai)
            ->group_by('p.id_supplier')
            ->order_by('total_pembelian', 'DESC')
            ->get()
            ->result();
    }

    // ================= STOK =================
    public function get_stok_barang()
    {
        return $this->db
            ->select('nama_barang, stok, harga_jual')
            ->from('barang')
            ->order_by('stok', 'ASC')
            ->get()
            ->result();
    }

    public function get_stok_menipis($limit = 5)
    {
        return $this->db
            ->select('nama_barang, stok')
            ->from('barang')
            ->where('stok <=', $limit)
            ->order_by('stok', 'ASC')
            ->get()
            ->result();
    }

    // ================= GRAFIK =================
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

    public function get_pembelian_per_hari($dari, $sampai)
    {
        return $this->db
            ->select('DATE(tanggal) as tanggal, SUM(total) as total')
            ->from('pembelian')
            ->where('DATE(tanggal) >=', $dari)
            ->where('DATE(tanggal) <=', $sampai)
            ->group_by('DATE(tanggal)')
            ->order_by('tanggal', 'ASC')
            ->get()
            ->result();
    }

    public function get_total_retur($dari, $sampai)
    {
        return $this->db
            ->select('SUM(rd.qty * td.harga) as total')
            ->from('retur_detail rd')
            ->join('transaksi_detail td', 'td.id_detail = rd.id_detail')
            ->join('transaksi t', 't.id_transaksi = td.id_transaksi')
            ->where('DATE(t.tanggal) >=', $dari)
            ->where('DATE(t.tanggal) <=', $sampai)
            ->get()
            ->row()->total ?? 0;
    }
}
