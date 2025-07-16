<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modal_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array(
			'student/Student_model', 'period/Period_model', 'setting/Setting_model'
		));
	}

	public function index()
	{
		$config['base_url'] = site_url('manage/modal/index');
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");


		$data['majors'] = $this->Student_model->get_majors();

		$data['title'] = 'Perubahan Modal';
		$data['main'] = 'modal/modal_list';
		$this->load->view('manage/layout', $data);
	}

	public function search_data()
	{

		$majors_id  = $this->input->post('majors_id');

		$where = "";

		if ($majors_id != 'all') {
			$where = "AND account.account_majors_id = '$majors_id'";
		}

		$saldoAwal = $this->db->query("SELECT SUM(saldo_awal_debit) as total
                        FROM `saldo_awal` 
                        JOIN account ON account.account_id = saldo_awal_account 
                        WHERE 1 $where")->row_array();

		$this->load->model('modal_model');
		$tabelmodel = $this->modal_model;

		$saldoJurnal = $tabelmodel->get_sum($majors_id);

		$saldoSekarang = $saldoJurnal['total_debet'] - $saldoJurnal['total_kredit'];

		echo '<div class="box box-primary box-solid">
            		    <div class="box-header with-border">
            			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Perubahan Modal</h3>
            			</div>
            			<div class="box-body table-responsive">
            			<table class="table table-responsive table-hover table-bordered" style="white-space: nowrap;">
						<tr>
							<td>
								<font size="4"><strong>LAPORAN PERUBAHAN MODAL</strong></font><br />
						</tr>
						</table>
						<table border="0" cellpadding="10" cellspacing="5" background="images/bttablelong2.png" align="left" width="100%">
						
						<tr height="30">
							<td><font size="2"><strong><em>Modal Awal</em></strong></font></td>
							<td width="120" align="right"><font size="2"><strong></td>
							<td width="120" align="right"><font size="2"><strong>
						Rp ' . number_format($saldoAwal['total'], 0, ',', '.') . '</strong></font></td>
						</tr>
						<tr height="30">
							<td><font size="2"><strong><em>Laba/Rugi</em></strong></font></td>
							<td width="120" align="right"><font size="2"><strong>
						Rp  ' . number_format($saldoSekarang, 0, ',', '.') . '</strong></font></td>
							<td width="120" align="right"><font size="2"><strong></td>
						</tr>
						
						<tr height="30">
							<td><font size="2"><strong><em>Modal Sekarang</em></strong></font></td>
							<td width="120" align="right"><font size="2"><strong></td>
							<td width="120" align="right"><font size="2"><strong>
						Rp ' . number_format($saldoAwal['total'] + $saldoSekarang, 0, ',', '.') . '</strong></font></td>
						</tr>';
		echo '</table>
    					</div>
    					</div>
    				</div>';
	}
}
