<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // wajib login
        // if ($this->session->userdata('logged_in') !== TRUE) {
        //     redirect('auth/login');
        // }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Kasir'
        ];

        $this->load->view('dashboard/header', $data);
        $this->load->view('dashboard/sidebar', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('dashboard/footer');
    }
}
