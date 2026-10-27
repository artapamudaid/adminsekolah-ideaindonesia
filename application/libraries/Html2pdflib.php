<?php defined('BASEPATH') or exit('No direct script access allowed');

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Html2pdflib
{

    public function generate($html, $filename = 'file_name', $download = TRUE, $paper = 'A4', $orientation = 'P')
    {
        ob_start();
        try {
            $pdf = new Html2Pdf($orientation, $paper, 'en', true, 'UTF-8', array(15, 15, 5, 5), false);
            $pdf->pdf->SetTitle($filename);
            $pdf->writeHTML($html);
            ob_end_clean();
            $pdf->output($filename . '.pdf', $download ? 'D' : 'I');
        } catch (Html2PdfException $e) {
            $pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }
}
