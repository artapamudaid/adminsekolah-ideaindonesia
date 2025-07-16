<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Pdf{

function pdf_create($html, $filename='', $stream=TRUE, $paper = 'Letter', $orientation = 'portrait') 
{

    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new Pdf();
    $dompdf->loadHTML($html);
    $dompdf->setPaper($paper, $orientation);

    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.'.pdf', array("Attachment" => 0));
    } else {
        return $dompdf->output(); 
    }
}

function pdf_create_landscape($html, $filename='', $stream=TRUE) 
{

    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new Pdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');

    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.'.pdf', array("Attachment" => 0));
    } else {
        return $dompdf->output(); 
    }
}

}
/* End of file dompdf_helper.php */
/* Location: ./application/helpers/dompdf_helper.php */
