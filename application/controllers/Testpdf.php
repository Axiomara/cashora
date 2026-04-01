<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Testpdf extends CI_Controller {

    public function index()
    {
        $this->load->library('pdf');

        $html = "<h2>PDF Dompdf Berhasil ✅</h2><p>Ini test PDF CI3</p>";
        $this->pdf->preview($html, "test.pdf");
    }
}
