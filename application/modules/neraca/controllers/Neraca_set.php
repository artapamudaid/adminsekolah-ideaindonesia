<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neraca_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
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
		$majors = $_GET['unit'];
		$end 	= $_GET['end'];

		$data['aktiva'] 	= $this->Neraca_model->get_aktiva($end, $majors);
		$data['pasiva'] 	= $this->Neraca_model->get_pasiva($end, $majors);
		$data['modal'] 		= $this->Neraca_model->get_modal($end, $majors);
		$data['laba_rugi'] 	= $this->Neraca_model->get_laba_rugi($end, $majors);

		$data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
		$data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$data['title'] = 'Laporan Neraca';
		$this->load->view('neraca/neraca_xls', $data);
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
			                <td>' . 'Rp ' . number_format($row['saldo'], 0, ",", ".") . '</td>
					    </tr>';
			$sumAktiva += $row['saldo'];
		}
		echo '<tr style="background-color: #fcfdff;">
		                <td colspan = "2" align = "right"><strong>Total Aktiva</strong></td>
		                <td>' . 'Rp ' . number_format($sumAktiva, 0, ",", ".") . '</td>
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
										<td>' . 'Rp ' . number_format($row['saldo'], 0, ",", ".") . '</td>
									</tr>';
			$sumPasiva += $row['saldo'];
		}
		echo '<tr style="background-color: #fcfdff;">
									<td colspan = "2" align = "right"><strong>Total Hutang</strong></td>
									<td>' . 'Rp ' . number_format($sumPasiva, 0, ",", ".") . '</td>
									</tr>
									</table>
									<table class="table table-responsive" style="white-space: nowrap;">';

		$sumModal = 0;
		foreach ($modal as $row) {
			echo '
													  <tr>
														<td colspan="2">' . $row['account_description'] . '</td>
														<td>' . 'Rp ' . number_format($row['saldo'], 0, ",", ".") . '</td>
													</tr>';
			$sumModal += $row['saldo'];
		}
		echo '<tr style="background-color: #fcfdff;">
													<td colspan = "2" align = "right"><strong>Total Modal</strong></td>
													<td>' . 'Rp ' . number_format($sumModal, 0, ",", ".") . '</td>
													</tr>
													</table>';
		echo '<table class="table table-responsive" style="white-space: nowrap;">
		<tr style="background-color: #fcfdff;">
													<td colspan = "2" align = "right"><strong>Total Laba/Rugi</strong></td>
													<td>' . 'Rp ' . number_format($laba_rugi['nominal'], 0, ",", ".") . '</td>
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