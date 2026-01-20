<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }
    public function login()
    {

        if ($this->session->userdata('logged_in') === TRUE) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function process()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password wajib diisi');
            redirect('auth/login');
        }

        $user = $this->User_model->get_by_username($username);

        if ($user && password_verify($password, $user->password)) {

            $session_data = [
                'user_id'   => $user->id,
                'username'  => $user->username,
                'role'      => $user->role,
                'logged_in' => TRUE
            ];

            $this->session->set_userdata($session_data);
            redirect('dashboard');

        } else {
            $this->session->set_flashdata('error', 'Username atau password salah');
            redirect('auth/login');
        }
    }

   
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
