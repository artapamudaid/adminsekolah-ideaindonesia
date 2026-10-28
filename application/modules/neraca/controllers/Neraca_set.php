<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Neraca_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array('student/Student_model', 'setting/Setting_model', 'neraca/Neraca_model'));
	}

	public function index()
	{

		$data['majors'] = $this->Student_model->get_majors();
		$data['tahun'] = $this->db->query("SELECT period_start FROM period")->result_array();
		// echo json_encode($data['tahun']);
		// exit;

		$data['title'] = 'Laporan Neraca';
		$data['main'] = 'neraca/neraca_list';
		$this->load->view('manage/layout', $data);
	}

	public function export_xls()
	{
        while (ob_get_level()) {
            ob_end_clean();
        }

		$majors = isset($_GET['unit']) ? $_GET['unit'] : '';
		$end 	= isset($_GET['end']) ? $_GET['end'] : '';

		$aktiva 	= $this->Neraca_model->get_aktiva($end, $majors);
		$pasiva 	= $this->Neraca_model->get_pasiva($end, $majors);
		$modal 		= $this->Neraca_model->get_modal($end, $majors);
		$laba_rugi 	= $this->Neraca_model->get_laba_rugi($end, $majors);

		$setting_school = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$setting_address = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$setting_phone = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Neraca');
        $sheet->setShowGridlines(true);

        // Title Block
        $sheet->setCellValue('A1', $setting_school['setting_value']);
        $sheet->setCellValue('A2', $setting_address['setting_value'] . ', Telp. ' . $setting_phone['setting_value']);
        $sheet->setCellValue('A3', 'Laporan Neraca Sampai Tanggal ' . (!empty($end) ? pretty_date($end, 'd F Y', false) : '-'));

        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');

        $sheet->getStyle('A1:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(9);
        $sheet->getStyle('A3')->getFont()->setSize(11)->setBold(true);

        // Thin border at row 4 as divider
        $sheet->getStyle('A4:E4')->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Headers
        $sheet->setCellValue('A5', 'AKTIVA');
        $sheet->mergeCells('A5:B5');
        $sheet->setCellValue('D5', 'HUTANG & MODAL');
        $sheet->mergeCells('D5:E5');

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

        $sheet->getStyle('D5:E5')->applyFromArray([
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

        // Column Titles
        $sheet->setCellValue('A6', 'Keterangan');
        $sheet->setCellValue('B6', 'Saldo');
        $sheet->setCellValue('D6', 'Keterangan');
        $sheet->setCellValue('E6', 'Saldo');

        $sheet->getStyle('A6:B6')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $sheet->getStyle('D6:E6')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Left Side (Aktiva)
        $left_row = 7;
        foreach ($aktiva as $row_data) {
            $sheet->setCellValue('A' . $left_row, $row_data['account_description']);
            $sheet->setCellValue('B' . $left_row, $row_data['saldo']);
            $sheet->getStyle('B' . $left_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
            $left_row++;
        }
        $last_aktiva_row = $left_row - 1;

        // Total Aktiva
        $sheet->setCellValue('A' . $left_row, 'Total Aktiva');
        if ($last_aktiva_row >= 7) {
            $sheet->setCellValue('B' . $left_row, '=SUM(B7:B' . $last_aktiva_row . ')');
        } else {
            $sheet->setCellValue('B' . $left_row, 0);
        }
        $sheet->getStyle('A' . $left_row . ':B' . $left_row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $left_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B' . $left_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('A' . $left_row . ':B' . $left_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_aktiva_cell = 'B' . $left_row;
        $left_row++;

        // Right Side (Hutang)
        $right_row = 7;
        foreach ($pasiva as $row_data) {
            $sheet->setCellValue('D' . $right_row, $row_data['account_description']);
            $sheet->setCellValue('E' . $right_row, $row_data['saldo']);
            $sheet->getStyle('E' . $right_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
            $right_row++;
        }
        $last_pasiva_row = $right_row - 1;

        // Total Pasiva / Hutang
        $sheet->setCellValue('D' . $right_row, 'Total Hutang');
        if ($last_pasiva_row >= 7) {
            $sheet->setCellValue('E' . $right_row, '=SUM(E7:E' . $last_pasiva_row . ')');
        } else {
            $sheet->setCellValue('E' . $right_row, 0);
        }
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $right_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E' . $right_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_pasiva_cell = 'E' . $right_row;
        $right_row++;

        // Spacer row on the right
        $right_row++;

        // Modal Header / Sub-Header
        $sheet->setCellValue('D' . $right_row, 'MODAL');
        $sheet->getStyle('D' . $right_row)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECF0F1'],
            ]
        ]);
        $right_row++;

        $start_modal_row = $right_row;
        foreach ($modal as $row_data) {
            $sheet->setCellValue('D' . $right_row, $row_data['account_description']);
            $sheet->setCellValue('E' . $right_row, $row_data['saldo']);
            $sheet->getStyle('E' . $right_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
            $right_row++;
        }
        $last_modal_row = $right_row - 1;

        // Total Modal
        $sheet->setCellValue('D' . $right_row, 'Total Modal');
        if ($last_modal_row >= $start_modal_row) {
            $sheet->setCellValue('E' . $right_row, '=SUM(E' . $start_modal_row . ':E' . $last_modal_row . ')');
        } else {
            $sheet->setCellValue('E' . $right_row, 0);
        }
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $right_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E' . $right_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_modal_cell = 'E' . $right_row;
        $right_row++;

        // Spacer row on the right
        $right_row++;

        // Total Laba / Rugi
        $sheet->setCellValue('D' . $right_row, 'Total Laba/Rugi');
        $sheet->setCellValue('E' . $right_row, isset($laba_rugi['nominal']) ? $laba_rugi['nominal'] : 0);
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $right_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E' . $right_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_laba_rugi_cell = 'E' . $right_row;
        $right_row++;

        // Spacer row on the right
        $right_row++;

        // Total Pasiva + Modal + Laba/Rugi
        $sheet->setCellValue('D' . $right_row, 'Total Hutang & Modal');
        $sheet->setCellValue('E' . $right_row, '=' . $total_pasiva_cell . '+' . $total_modal_cell . '+' . $total_laba_rugi_cell);
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $right_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E' . $right_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('D' . $right_row . ':E' . $right_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAEDED'],
            ]
        ]);
        $total_pasiva_modal_cell = 'E' . $right_row;
        $right_row++;

        // Final balanced bottom row
        $max_row = max($left_row, $right_row);
        $grand_row = $max_row + 1;

        $sheet->setCellValue('A' . $grand_row, 'GRAND TOTAL AKTIVA');
        $sheet->setCellValue('B' . $grand_row, '=' . $total_aktiva_cell);
        
        $sheet->setCellValue('D' . $grand_row, 'GRAND TOTAL PASIVA');
        $sheet->setCellValue('E' . $grand_row, '=' . $total_pasiva_modal_cell);

        $sheet->getStyle('A' . $grand_row . ':B' . $grand_row)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('D' . $grand_row . ':E' . $grand_row)->getFont()->setBold(true)->setSize(12);

        $sheet->getStyle('A' . $grand_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D' . $grand_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle('B' . $grand_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');
        $sheet->getStyle('E' . $grand_row)->getNumberFormat()->setFormatCode('"Rp"#,##0');

        $sheet->getStyle('A' . $grand_row . ':B' . $grand_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D4EFDF'], // Balanced Green
            ]
        ]);

        $sheet->getStyle('D' . $grand_row . ':E' . $grand_row)->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D4EFDF'], // Balanced Green
            ]
        ]);

        // Borders left side
        $sheet->getStyle('A5:B' . $grand_row)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'BDC3C7'],
                ],
            ],
        ]);

        // Borders right side
        $sheet->getStyle('D5:E' . $grand_row)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'BDC3C7'],
                ],
            ],
        ]);

        // Columns width formatting
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $filename = 'Laporan_Neraca_' . date('Ymd_His') . '.xlsx';

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

	public function get_neraca()
	{
		$majors = $_REQUEST['majors_id'];
		$end 	= $_REQUEST['end'];

		$aktiva 	= $this->Neraca_model->get_aktiva($end, $majors);
		$pasiva 	= $this->Neraca_model->get_pasiva($end, $majors);
		$modal 		= $this->Neraca_model->get_modal($end, $majors);
		$laba_rugi 	= $this->Neraca_model->get_laba_rugi($end, $majors);

		echo '<div class="box box-primary box-solid">
    		    <div class="box-header with-border">
    			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Neraca Sampai Tanggal ' . pretty_date($_REQUEST['end'], 'd F Y', false)
			. '</h3>
    			</div>
    			<div class="box-body table-responsive">
				<table class="table table-responsive" style="white-space: nowrap;">
					<tr>
						<td>
    			<table class="table table-responsive" style="white-space: nowrap;">
    			    <tr>
    			        <td>
    			        <div class="md-6">
    			            <b>AKTIVA</b>
    			        </div>
    			        </td>
    			        <td>
    			        <div class="md-6">
    			            <b>HUTANG & MODAL</b>
    			        </div>
    			        </td>
    			    </tr>
        			<tr>
        			    <td>
        			    <div class="md-6">
        			        <table class="table table-responsive" style="white-space: nowrap;">';

		$sumAktiva = 0;
		foreach ($aktiva as $row) {
			echo '
			              <tr>
			                <td colspan="2">' . $row['account_description'] . '</td>
			                <td>' . 'Rp ' . number_format((float)$row['saldo'], 0, ",", ".") . '</td>
					    </tr>';
			$sumAktiva += $row['saldo'];
		}
		echo '<tr style="background-color: #fcfdff;">
		                <td colspan = "2" align = "right"><strong>Total Aktiva</strong></td>
		                <td>' . 'Rp ' . number_format((float)$sumAktiva, 0, ",", ".") . '</td>
    				    </tr>
    			        </table>
    			    </div>
        			</td>
        			<td>
        			<div class="md-6">
					<table class="table table-responsive" style="white-space: nowrap;">';

		$sumPasiva = 0;
		foreach ($pasiva as $row) {
			echo '
									  <tr>
										<td colspan="2">' . $row['account_description'] . '</td>
										<td>' . 'Rp ' . number_format((float)$row['saldo'], 0, ",", ".") . '</td>
									</tr>';
			$sumPasiva += $row['saldo'];
		}
		echo '<tr style="background-color: #fcfdff;">
									<td colspan = "2" align = "right"><strong>Total Hutang</strong></td>
									<td>' . 'Rp ' . number_format((float)$sumPasiva, 0, ",", ".") . '</td>
									</tr>
									</table>
									<table class="table table-responsive" style="white-space: nowrap;">';

		$sumModal = 0;
		foreach ($modal as $row) {
			echo '
													  <tr>
														<td colspan="2">' . $row['account_description'] . '</td>
														<td>' . 'Rp ' . number_format((float)$row['saldo'], 0, ",", ".") . '</td>
													</tr>';
			$sumModal += $row['saldo'];
		}
		echo '<tr style="background-color: #fcfdff;">
													<td colspan = "2" align = "right"><strong>Total Modal</strong></td>
													<td>' . 'Rp ' . number_format((float)$sumModal, 0, ",", ".") . '</td>
													</tr>
													</table>';
		echo '<table class="table table-responsive" style="white-space: nowrap;">
		<tr style="background-color: #fcfdff;">
													<td colspan = "2" align = "right"><strong>Total Laba/Rugi</strong></td>
													<td>' . 'Rp ' . number_format((float)$laba_rugi['nominal'], 0, ",", ".") . '</td>
													</tr>
													</table>';

		echo '</div>
        			</td>
    			</tr>
			</table>
			
			</td>
			</tr>
			<tr>
				<td>
				<table class="table table-responsive">
				<tr>
				<td>
				<div class="md-6 pull-right" style="font-weight: bold">
				<font size="4">' . 'Rp ' . number_format($sumAktiva, 0, ",", ".") . '</font>
				</div>
				</td>
				<td>
				<div class="md-6 pull-right" style="font-weight: bold">
				<font size="4">' . 'Rp ' . number_format($sumPasiva + $sumModal + $laba_rugi['nominal'], 0, ",", ".") . '</font>
				</div>
				</td>
				</tr>
				</table>
				</td>

			</tr>
		</table>';

		echo '</div>
			<div class="box-footer">
			<table class="table">
		        <tr>
				    <td>
				        <div class="pull-right">
    					    <a class="btn btn-success" target="_blank" href="' . base_url() . 'manage/neraca/export_xls/?unit=' . $_REQUEST['majors_id'] . '&end=' . $_REQUEST['end'] . '"><span class="fa fa-file-excel-o"></span> Cetak Excel
    					    </a>
    					</div>
				    </td>
			    </tr>
			</table>
			</div>
			</div>
		</div>';
	}
}

/* End of file Neraca_set.php */
/* Location: ./application/modules/neraca/controllers/Neraca_set.php */