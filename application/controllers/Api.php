<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
    }

    public function cari_produk() {
        $q = trim($this->input->get('q', TRUE));

        $data = $this->Barang_model->search_ajax($q);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => true,
            'data' => $data
        ]);
    }
}
