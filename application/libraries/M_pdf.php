<?php

use Mpdf\Mpdf;

require_once APPPATH . 'third_party/autoload.php';

class M_pdf
{

    public $param;
    public $pdf;

    public function __construct($param = 'en-GB-x,A4,,,10,10,10,10,6,3')
    {
        if (is_string($param)) {
            $constructor = explode(',', str_replace('"', '', $param));

            $param = [
                'mode' => $constructor[0] ?? '',
                'format' => $constructor[1] ?? 'A4',
                'default_font_size' => $constructor[2] ?? 0,
                'default_font' => $constructor[3] ?? '',
                'margin_left' => $constructor[4] ?? 15,
                'margin_right' => $constructor[5] ?? 15,
                'margin_top' => $constructor[6] ?? 16,
                'margin_bottom' => $constructor[7] ?? 16,
                'margin_header' => $constructor[8] ?? 9,
                'margin_footer' => $constructor[9] ?? 9,
                'orientation' => $constructor[10] ?? 'P',
            ];
        }

        $this->param = $param;
        $this->pdf = new Mpdf($this->param);
    }

}
