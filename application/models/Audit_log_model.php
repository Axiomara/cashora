<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_log_model extends CI_Model {

    public function log($aksi, $tabel = null, $data_id = null, $keterangan = null)
    {
        $CI =& get_instance();

        $user_id = $CI->session->userdata('user_id');

        $data = [
            'user_id' => $user_id,
            'aksi' => $aksi,
            'tabel' => $tabel,
            'data_id' => $data_id,
            'keterangan' => $keterangan,
            'ip_address' => $CI->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('audit_log', $data);
    }

}