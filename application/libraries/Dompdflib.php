<?php defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdflib extends Dompdf
{
    private $dompdf;

    public function __construct()
    {
        parent::__construct();
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $this->dompdf = new Dompdf($options);
    }

    public function generate($html, $filename = '', $paper = '', $orientation = '', $stream = TRUE)
    {
        try {
            $this->dompdf->loadHtml($html);
            $this->dompdf->setPaper($paper, $orientation);
            $this->dompdf->render();

            if ($stream) {
                $this->dompdf->stream($filename . ".pdf", array("Attachment" => 0));
            } else {
                return $this->dompdf->output();
            }
        } catch (\Exception $e) {
            // Handle Dompdf exceptions
            echo "Error generating PDF: " . $e->getMessage();
        }
    }
}
