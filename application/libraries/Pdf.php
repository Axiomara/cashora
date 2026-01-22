<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pdf {

    public function __construct()
    {
        require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
    }

    // PDF A4 biasa
    public function download($html, $filename = 'file.pdf')
    {
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => 1]);
    }

    // ✅ PDF thermal 58mm (nota kasir)
    public function thermal($html, $filename = 'nota.pdf')
    {
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        // custom paper 58mm
        // 1 inch = 72 pt
        // 58mm = 2.283 inch => 2.283 * 72 = 164.4 pt
        $width = 233.6;
        $height = 2000; 

        $customPaper = [0, 0, $width, $height];

        $dompdf->setPaper($customPaper);
        $dompdf->render();

        $dompdf->stream($filename, ["Attachment" => 0]);
    }
}
