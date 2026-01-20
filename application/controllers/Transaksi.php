<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('dashboard/header' );
        $this->load->view('dashboard/sidebar');
        $this->load->view('transaksi/transaksi' );
        $this->load->view('dashboard/footer');
    }
}