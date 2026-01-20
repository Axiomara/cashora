<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

    public function insert($data)
    {
        if (empty($data)) return false;

        $insertData = [
            'kode_barang' => strtoupper(trim($data['kode_barang'] ?? '')),
            'nama_barang' => trim($data['nama_barang'] ?? ''),
            'harga_jual'  => (int)($data['harga_jual'] ?? 0),
            'stok'        => (int)($data['stok'] ?? 0),
        ];

        // minimal validation (biar aman walau controller lupa validasi)
        if ($insertData['kode_barang'] === '' || $insertData['nama_barang'] === '') {
            return false;
        }

        if ($insertData['harga_jual'] < 0) $insertData['harga_jual'] = 0;
        if ($insertData['stok'] < 0) $insertData['stok'] = 0;

        // timestamps manual kalau DB gak otomatis
        $insertData['created_at'] = date('Y-m-d H:i:s');
        $insertData['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->insert($this->table, $insertData);
    }

}
