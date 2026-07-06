<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Laba_rugi_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array(
            'student/Student_model',
            'period/Period_model',
            'setting/Setting_model'
        ));
    }

    public function index($offset = NULL)
    {
        $this->load->library('pagination');
        $s = $this->input->get(NULL, TRUE);
        $data['s'] = $s;
        $params = array();
        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
        }
        $config['base_url'] = site_url('manage/laba_rugi/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $data['majors'] = $this->Student_model->get_majors();
        $data['title'] = 'Laba Rugi';
        $data['main'] = 'laba_rugi/laba_rugi_list';
        $this->load->view('manage/layout', $data);
    }


    public function list_laba_rugi()
    {
        $get = $this->input->get();
        $this->load->model('Laba_rugi_model');
        $tabelmodel = $this->Laba_rugi_model;
        $list = $tabelmodel->get_datatable('selectdata');
        $data = array();
        foreach ($list as $r) {
            $row = array();
            $row[] = $r->account_code;
            $row[] = $r->account_description;
            $row[] = $r->kredit;
            $row[] = $r->debet;
            $data[] = $row;
        }

        $result = array(
            "draw" => $get['draw'],
            "recordsTotal" => $tabelmodel->get_datatable('countall'),
            "recordsFiltered" => $tabelmodel->get_datatable('countfilter'),
            "data" => $data,
        );
        echo json_encode($result);
    }

    public function get_laba_rugi()
    {

        $this->load->model('Laba_rugi_model');

        $pendapatan = $this->Laba_rugi_model->get_data_simpanan('PENDAPATAN');
        $biaya = $this->Laba_rugi_model->get_data_simpanan('BIAYA');

        echo '<div class="box box-primary box-solid">
    		    <div class="box-header with-border">
    			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Labas Rugi' .
            ' Per ' . pretty_date($_REQUEST['end'], 'd F Y', false)
            . '</h3>
    			</div>
    			<div class="box-body table-responsive">
				<table class="table table-responsive" style="white-space: nowrap;">
                <tr><td colspan=3><strong>Penerimaan</strong></td></tr>';
        $sumPendapatan = 0;
        foreach ($pendapatan as $row) {
            echo '
			              <tr>
			                <td colspan="2">' . $row['jenisBayar'] . '</td>
			                <td>' . 'Rp ' . number_format($row['debet'], 0, ",", ".") . '</td>
                            <td></td>
					    </tr>';
            $sumPendapatan += $row['debet'];
        }
        echo '<tr style="background-color: #fcfdff;">
		                <td colspan = "2" align = "right"><strong>Total Penerimaan</strong></td>
		                <td>' . 'Rp ' . number_format($sumPendapatan, 0, ",", ".") . '</td>
    				    </tr>
                        <tr>
                        <tr><td colspan=3><strong>Pengeluaran</strong></td></tr>';
        $sumBiaya = 0;
        foreach ($biaya as $row) {
            echo '
                                  <tr>
                                    <td colspan="2">' . $row['jenisBayar'] . '</td>
                                    <td>' . 'Rp ' . number_format($row['kredit'], 0, ",", ".") . '</td>
                                    <td></td>
                                </tr>';
            $sumBiaya += $row['kredit'];
        }
        echo '<tr style="background-color: #fcfdff;">
                                <td colspan = "2" align = "right"><strong>Total Pengeluaran</strong></td>
                                <td>' . 'Rp ' . number_format($sumBiaya, 0, ",", ".") . '</td>
                                </tr>';
        echo '<tr style="background-color: #fcfdff;">
                                <td colspan = "2" align = "right"><strong>Laba/Rugi</strong></td>
                                <td>' . 'Rp ' . number_format($sumPendapatan - $sumBiaya, 0, ",", ".") . '</td>
                                </tr>
                                </table>';

        echo '</div>
			<div class="box-footer">
			<table class="table">
		        <tr>
				    <td>
				        <div class="pull-right">
    					    <a class="btn btn-success" target="_blank" href="' . base_url() . 'manage/laba_rugi/export_xls/?unit=' . $_REQUEST['majors_id'] . '&start=' . @$_REQUEST['start'] . '&end=' . $_REQUEST['end'] . '"><span class="fa fa-file-excel-o"></span> Cetak Excel
    					    </a>
    					</div>
				    </td>
			    </tr>
			</table>
			</div>
			</div>
		</div>';
    }

    public function export_xls()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $this->load->model('Laba_rugi_model');
        $this->load->model('student/Student_model');

        $majors_id = isset($_GET['unit']) ? $_GET['unit'] : '';
        $start = isset($_GET['start']) ? $_GET['start'] : '';
        $end = isset($_GET['end']) ? $_GET['end'] : '';

        // Override request parameters for model
        $_REQUEST['majors_id'] = $majors_id;
        $_REQUEST['start'] = $start;
        $_REQUEST['end'] = $end;

        $pendapatan = $this->Laba_rugi_model->get_data_simpanan('PENDAPATAN');
        $biaya = $this->Laba_rugi_model->get_data_simpanan('BIAYA');

        $unit_name = 'Semua Unit';
        if ($majors_id != 'all' && !empty($majors_id)) {
            $unit = $this->Student_model->get_majors(array('id' => $majors_id));
            if (!empty($unit)) {
                $unit_name = $unit['majors_name'];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Laba Rugi');
        $sheet->setShowGridlines(true);

        // Title Block
        $sheet->setCellValue('A1', 'LAPORAN LABA RUGI');
        $sheet->setCellValue('A2', 'Unit: ' . $unit_name);
        $sheet->setCellValue('A3', 'Periode: ' . (!empty($start) ? pretty_date($start, 'd F Y', false) : '-') . ' s/d ' . (!empty($end) ? pretty_date($end, 'd F Y', false) : '-'));

        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');

        $sheet->getStyle('A1:B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A2:A3')->getFont()->setSize(11);

        // Header Row
        $sheet->setCellValue('A5', 'Keterangan');
        $sheet->setCellValue('B5', 'Nominal');
        $sheet->getStyle('A5:B5')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(25);

        $row = 6;

        // Penerimaan Header
        $sheet->setCellValue('A' . $row, 'Penerimaan');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1'],
            ]
        ]);
        $row++;

        $start_pendapatan = $row;
        foreach ($pendapatan as $item) {
            $sheet->setCellValue('A' . $row, $item['jenisBayar']);
            $sheet->setCellValue('B' . $row, $item['debet']);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
            $row++;
        }
        $end_pendapatan = $row - 1;

        // Total Penerimaan
        $sheet->setCellValue('A' . $row, 'Total Penerimaan');
        if ($start_pendapatan <= $end_pendapatan) {
            $sheet->setCellValue('B' . $row, '=SUM(B' . $start_pendapatan . ':B' . $end_pendapatan . ')');
        } else {
            $sheet->setCellValue('B' . $row, 0);
        }
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_penerimaan_cell = 'B' . $row;
        $row += 2;

        // Pengeluaran Header
        $sheet->setCellValue('A' . $row, 'Pengeluaran');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1'],
            ]
        ]);
        $row++;

        $start_biaya = $row;
        foreach ($biaya as $item) {
            $sheet->setCellValue('A' . $row, $item['jenisBayar']);
            $sheet->setCellValue('B' . $row, $item['kredit']);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
            $row++;
        }
        $end_biaya = $row - 1;

        // Total Pengeluaran
        $sheet->setCellValue('A' . $row, 'Total Pengeluaran');
        if ($start_biaya <= $end_biaya) {
            $sheet->setCellValue('B' . $row, '=SUM(B' . $start_biaya . ':B' . $end_biaya . ')');
        } else {
            $sheet->setCellValue('B' . $row, 0);
        }
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_pengeluaran_cell = 'B' . $row;
        $row += 2;

        // Laba / Rugi
        $sheet->setCellValue('A' . $row, 'Laba / Rugi Bersih');
        $sheet->setCellValue('B' . $row, '=' . $total_penerimaan_cell . '-' . $total_pengeluaran_cell);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D4EFDF'], // Light green accent
            ]
        ]);

        // Borders around headers and rows
        $sheet->getStyle('A5:B' . $row)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'BDC3C7'],
                ],
            ],
        ]);

        // Column dimension auto width
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        $filename = 'Laporan_Laba_Rugi_' . str_replace(' ', '_', $unit_name) . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: public');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
