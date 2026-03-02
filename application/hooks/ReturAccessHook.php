<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReturAccessHook {

    public function check_retur_access()
    {
        $CI =& get_instance();

        if (!$CI) return;

        $class  = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        if ($class === 'retur' && $method === 'form') {

            $id = $CI->uri->segment(3);
            $allowed_id = $CI->session->userdata('allow_retur');

            if (!$id || $allowed_id != $id) {
                redirect('transaksi/riwayat');
                exit;
            }
        }
    }
}