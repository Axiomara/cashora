<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stok_model');
           $this->load->model('Audit_log_model');
    }

    public function index()
    {
        $data['stok_log'] = $this->Stok_model->get_all();

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('stok/index', $data);
        $this->load->view('dashboard/footer');
    }
}