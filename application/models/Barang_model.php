<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 */

class Barang_model extends CI_Model {

    private $table = 'barang';
    private $select_default = 'id_barang, kode_barang, nama_barang, harga_jual, stok, created_at, updated_at';


    public function get_all($order = 'DESC')
    {
        return $this->db
            ->select($this->select_default)
            ->from($this->table)
            ->order_by('id_barang', $order)
            ->get()
            ->result();
    }

    public function search_ajax($keyword = '') {
    $keyword = trim($keyword);

    $this->db->select('id_barang, kode_barang, nama_barang, harga_jual, stok');
    $this->db->from($this->table);

    if ($keyword !== '') {
        $this->db->group_start();
        $this->db->like('kode_barang', $keyword);
        $this->db->or_like('nama_barang', $keyword);
        $this->db->group_end();
    }

    $this->db->order_by('nama_barang', 'ASC');
    $this->db->limit(10);

    return $this->db->get()->result_array();
    }


    public function insert($data)
    {
        if (empty($data)) return false;

        $insertData = [
            'kode_barang' => strtoupper(trim($data['kode_barang'] ?? '')),
            'nama_barang' => trim($data['nama_barang'] ?? ''),
            'harga_jual'  => (int)($data['harga_jual'] ?? 0),
            'stok'        => (int)($data['stok'] ?? 0),
        ];

        if ($insertData['kode_barang'] === '' || $insertData['nama_barang'] === '') {
            return false;
        }

        if ($insertData['harga_jual'] < 0) $insertData['harga_jual'] = 0;
        if ($insertData['stok'] < 0) $insertData['stok'] = 0;

        $insertData['created_at'] = date('Y-m-d H:i:s');
        $insertData['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->insert($this->table, $insertData);
    }

    public function get_by_id($id_barang) {
    return $this->db
        ->select('id_barang, kode_barang, nama_barang, harga_jual, stok')
        ->from('barang')
        ->where('id_barang', (int)$id_barang)
        ->limit(1)
        ->get()
        ->row();
    }

        public function get_low_stock($limit = 5) {
        return $this->db
            ->where('stok >', 0)
            ->where('stok <', 5)
            ->order_by('stok', 'ASC')
            ->limit($limit)
            ->get('barang')
            ->result();
    }

    public function count_low_stock() {
        return $this->db
            ->where('stok >', 0)
            ->where('stok <', 5)
            ->count_all_results('barang');
    }

        public function get_by_kode($kode) {
        return $this->db->get_where('barang', [
            'kode_barang' => $kode
        ])->row();
    }

    public function get_filtered($keyword = null, $filter = null, $sort = 'id_barang', $order = 'asc') {
        $this->db->from('barang');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('kode_barang', $keyword);
            $this->db->or_like('nama_barang', $keyword);
            $this->db->group_end();
        }

        if ($filter === 'low') {
            $this->db->where('stok <=', 5);
        }

        if ($filter === 'safe') {
            $this->db->where('stok >', 5);
        }

        $this->db->order_by($sort, $order);

        return $this->db->get()->result();
    }

    public function search_barang($keyword = null, $filter = null, $sort = null, $order = null) {
        $this->db->from('barang');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('kode_barang', $keyword);
            $this->db->or_like('nama_barang', $keyword);
            $this->db->group_end();
        }

        if ($filter === 'low') {
            $this->db->where('stok <=', 5);
        }

        if ($filter === 'safe') {
            $this->db->where('stok >', 5);
        }

        $allowed_sort = ['kode_barang', 'nama_barang', 'stok', 'harga_jual'];

        if (in_array($sort, $allowed_sort)) {
            $this->db->order_by($sort, $order === 'desc' ? 'DESC' : 'ASC');
        } else {
            $this->db->order_by('id_barang', 'DESC');
        }

        return $this->db->get()->result();
    }

}
