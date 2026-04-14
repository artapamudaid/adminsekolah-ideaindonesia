<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neraca_akrual_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array('student/Student_model', 'setting/Setting_model', 'neraca_akrual/Neraca_akrual_model'));
	}

	public function index()
	{

		$data['majors'] = $this->Student_model->get_majors();
		$data['tahun'] = $this->db->query("SELECT period_start FROM period")->result_array();

		$data['title'] = 'Laporan Posisi Keuangan';
		$data['main'] = 'neraca_akrual/neraca_list';
		$this->load->view('manage/layout', $data);
	}

	public function export_xls()
	{

		$data['aktiva'] 	= $this->Neraca_akrual_model->get_aktiva();
		$data['pasiva'] 	= $this->Neraca_akrual_model->get_pasiva();
		$data['modal'] 		= $this->Neraca_akrual_model->get_modal();
		$data['laba_rugi'] 	= $this->Neraca_akrual_model->get_laba_rugi();

		$data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
		$data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$data['title'] = 'Laporan Neraca';
		$this->load->view('neraca_akrual/neraca_xls', $data);
	}

	public function get_neraca_non_akrual()
	{

		$aktiva 	= $this->Neraca_akrual_model->get_aktiva();
		$pasiva 	= $this->Neraca_akrual_model->get_pasiva();
		$modal 		= $this->Neraca_akrual_model->get_modal();
		$laba_rugi 	= $this->Neraca_akrual_model->get_laba_rugi();

		echo '<div class="box box-primary box-solid">
    		    <div class="box-header with-border">
    			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Neraca Per '
			. pretty_date($_REQUEST['end'], 'd F Y', false)
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
													<td colspan = "2" align = "right"><strong>Aset Neto</strong></td>
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
    					    <a class="btn btn-success" target="_blank" href="' . base_url() . 'manage/neraca_akrual/export_xls/?unit=' . $_REQUEST['majors_id'] . '&start=' . $_REQUEST['start'] . '&end=' . $_REQUEST['end'] . '"><span class="fa fa-file-excel-o"></span> Cetak Excel
    					    </a>
    					</div>
				    </td>
			    </tr>
			</table>
			</div>
			</div>
		</div>';
	}

	public function get_neraca()
	{
		$bulan        = $_POST['end'];
		$majors_id    = $_POST['majors_id'];

		$end = date('Y-m-t', strtotime($bulan));

		$aktiva    = $this->Neraca_akrual_model->get_aktiva_akrual($end, $majors_id);
		$pasiva    = $this->Neraca_akrual_model->get_pasiva_akrual($end, $majors_id);
		$modal     = $this->Neraca_akrual_model->get_modal_akrual($end, $majors_id);
		$laba_rugi = $this->Neraca_akrual_model->get_laba_rugi_akrual($end, $majors_id);

		$sumAset = 0;
		$sumLiabilitas = 0;
		$sumAsetNeto = 0;
		$nominalLabaRugi = $laba_rugi['nominal'] ?? 0;

		?>
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Posisi Keuangan Per <?php echo pretty_date($end, 'F Y', false); ?></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<!-- ASET SECTION -->
					<div class="col-md-6 border-right">
						<h4 class="text-center"><b>ASET</b></h4>
						<table class="table table-hover table-condensed">
							<thead>
								<tr class="bg-gray">
									<th>Akun</th>
									<th class="text-right">Saldo</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aktiva as $row): ?>
									<tr>
										<td><?php echo $row['account_description']; ?></td>
										<td class="text-right">Rp <?php echo number_format($row['saldo'], 0, ",", "."); ?></td>
									</tr>
									<?php $sumAset += $row['saldo']; ?>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr class="bg-info" style="font-weight: bold;">
									<td class="text-right">TOTAL ASET</td>
									<td class="text-right">Rp <?php echo number_format($sumAset, 0, ",", "."); ?></td>
								</tr>
							</tfoot>
						</table>
					</div>

					<!-- LIABILITAS & ASET NETO SECTION -->
					<div class="col-md-6">
						<h4 class="text-center"><b>LIABILITAS DAN ASET NETO</b></h4>
						
						<!-- Liabilitas Table -->
						<table class="table table-hover table-condensed">
							<thead>
								<tr class="bg-gray">
									<th colspan="2">LIABILITAS</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pasiva as $row): ?>
									<tr>
										<td><?php echo $row['account_description']; ?></td>
										<td class="text-right">Rp <?php echo number_format($row['saldo'], 0, ",", "."); ?></td>
									</tr>
									<?php $sumLiabilitas += $row['saldo']; ?>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr class="bg-info" style="font-weight: bold;">
									<td class="text-right">TOTAL LIABILITAS</td>
									<td class="text-right">Rp <?php echo number_format($sumLiabilitas, 0, ",", "."); ?></td>
								</tr>
							</tfoot>
						</table>

						<!-- Aset Neto Table -->
						<table class="table table-hover table-condensed" style="margin-top: 20px;">
							<thead>
								<tr class="bg-gray">
									<th colspan="2">ASET NETO</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($modal as $row): ?>
									<tr>
										<td><?php echo $row['account_description']; ?></td>
										<td class="text-right">Rp <?php echo number_format($row['saldo'], 0, ",", "."); ?></td>
									</tr>
									<?php $sumAsetNeto += $row['saldo']; ?>
								<?php endforeach; ?>
								<tr>
									<td>Surplus / (Defisit) Periode Berjalan</td>
									<td class="text-right">Rp <?php echo number_format($nominalLabaRugi, 0, ",", "."); ?></td>
								</tr>
							</tbody>
							<tfoot>
								<tr class="bg-info" style="font-weight: bold;">
									<td class="text-right">TOTAL ASET NETO</td>
									<td class="text-right">Rp <?php echo number_format($sumAsetNeto + $nominalLabaRugi, 0, ",", "."); ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

				<!-- BALANCE SUMMARY -->
				<div class="row" style="margin-top: 30px; border-top: 2px solid #3c8dbc; padding-top: 15px;">
					<div class="col-md-6 text-center">
						<div class="well well-sm">
							<h4 style="margin:0;">TOTAL ASET: <b>Rp <?php echo number_format($sumAset, 0, ",", "."); ?></b></h4>
						</div>
					</div>
					<div class="col-md-6 text-center">
						<div class="well well-sm">
							<h4 style="margin:0;">TOTAL LIAB. & ASET NETO: <b>Rp <?php echo number_format($sumLiabilitas + $sumAsetNeto + $nominalLabaRugi, 0, ",", "."); ?></b></h4>
						</div>
					</div>
				</div>
			</div>
			
			<div class="box-footer">
				<div class="pull-right">
					<a class="btn btn-success" target="_blank" href="<?php echo base_url(); ?>manage/neraca_akrual/export_xls/?unit=<?php echo $_REQUEST['majors_id']; ?>&end=<?php echo $_REQUEST['end']; ?>">
						<span class="fa fa-file-excel-o"></span> Cetak Excel
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}

/* End of file Neraca_set.php */
/* Location: ./application/modules/neraca/controllers/Neraca_set.php */