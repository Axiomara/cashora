<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model
{
    private $table = 'stok_log';

    public function log($id_barang, $tipe, $qty, $stok_sebelum, $stok_sesudah, $referensi = null, $keterangan = null)
    {
        $data = [
            'id_barang'    => $id_barang,
            'tipe'         => $tipe,
            'qty'          => $qty,
            'stok_sebelum' => $stok_sebelum,
            'stok_sesudah' => $stok_sesudah,
            'referensi'    => $referensi,
            'keterangan'   => $keterangan
        ];

        $this->db->insert($this->table, $data);
    }

    public function get_all()
    {
        return $this->db
            ->select('stok_log.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = stok_log.id_barang')
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }
}