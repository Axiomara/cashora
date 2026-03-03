<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
    protected $table = 'supplier'; // 🔥 WAJIB ADA

    public function __construct()
    {
        parent::__construct();
    }

    // =========================================
    // GET ALL
    // =========================================
    public function get_all()
    {
        return $this->db
            ->order_by('nama_supplier', 'ASC')
            ->get($this->table)
            ->result();
    }

    // =========================================
    // GET BY ID
    // =========================================
    public function get_by_id($id)
    {
        return $this->db
            ->where('id_supplier', (int)$id)
            ->get($this->table)
            ->row();
    }

    // =========================================
    // INSERT (SAFE & ENTERPRISE STYLE)
    // =========================================
    public function insert(array $data)
    {
        if (empty($data)) {
            return false;
        }

        // Sanitasi manual tambahan (opsional tapi lebih aman)
        $data = [
            'nama_supplier' => trim($data['nama_supplier'] ?? ''),
            'no_hp'         => trim($data['no_hp'] ?? ''),
            'alamat'        => trim($data['alamat'] ?? ''),
            'keterangan'    => trim($data['keterangan'] ?? ''),
        ];

        $this->db->trans_begin();

        $this->db->insert($this->table, $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('error', 'Insert supplier gagal: ' . json_encode($data));
            return false;
        }

        $insert_id = $this->db->insert_id();

        $this->db->trans_commit();

        return $insert_id;
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update($id, array $data)
    {
        return $this->db
            ->where('id_supplier', (int)$id)
            ->update($this->table, $data);
    }

    // =========================================
    // DELETE
    // =========================================
    public function delete($id)
    {
        return $this->db
            ->where('id_supplier', (int)$id)
            ->delete($this->table);
    }

    public function search($keyword) {
        // Validasi awal
        if (!is_string($keyword)) {
            return [];
        }

        $keyword = trim($keyword);

        // Batasi panjang keyword (anti abuse)
        if ($keyword === '' || strlen($keyword) > 100) {
            return [];
        }

        // Escape wildcard LIKE (% dan _)
        $keyword = $this->db->escape_like_str($keyword);

        return $this->db
            ->from('supplier')
            ->like('nama_supplier', $keyword, 'both')
            ->order_by('nama_supplier', 'ASC')
            ->limit(100) // batasi hasil
            ->get()
            ->result();
    }
}