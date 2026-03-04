<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
    }

    public function cari_produk()
{
    $q = trim($this->input->get('q', TRUE));

    if ($q == '') {
        echo json_encode(['status' => false]);
        return;
    }

    $this->db->group_start();
    $this->db->like('kode_barang', $q);
    $this->db->or_like('nama_barang', $q);
    $this->db->or_like('barcode', $q);
    $this->db->group_end();

    $result = $this->db->get('barang')->result();

    echo json_encode([
        'status' => true,
        'data'   => $result
    ]);
}
}
