<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buku_besar_set extends CI_Controller
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
		$config['base_url'] = site_url('manage/buku_besar/index');
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");


		$data['majors'] = $this->Student_model->get_majors();

		$data['title'] = 'Buku Besar';
		$data['main'] = 'buku_besar/buku_besar_list';
		$this->load->view('manage/layout', $data);
	}

	public function search_data()
	{

		$majors_id  = $this->input->post('majors_id');

		$this->load->model('Buku_besar_model');
		$tabelmodel = $this->Buku_besar_model;

		$grps = $tabelmodel->group_jurnal($majors_id);

		$groups = array();
		foreach ($grps as $grp) :
			$groups[] = $grp;
		endforeach;

		$accs 	= $tabelmodel->get_account($majors_id);

		$accounts = array();
		foreach ($accs as $acc) :
			$accounts[] = $acc;
		endforeach;

		$jurs 	= $tabelmodel->get_jurnal($majors_id);

		$jurnals = array();
		foreach ($jurs as $jur) :
			$jurnals[] = $jur;
		endforeach;

		echo '<div class="box box-primary box-solid">
            		    <div class="box-header with-border">
            			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Buku Besar</h3>
            			</div>
            			<div class="box-body table-responsive">
            			<table class="table table-responsive table-hover table-bordered" style="white-space: nowrap;">
						<tr>
							<td>
								<font size="4"><strong>LAPORAN BUKU BESAR</strong></font><br />
							</td>
						</tr>
						</table>
						<table border="0" background="images/bttablelong2.png" align="center" width="100%">';
		foreach ($accounts as $account) :
			foreach ($groups as $group) :
				if ($account['account_code'] == $group['account_code']) {
					echo '<tr height="25">
					<td colspans="5"><b>' . $account['account_code'] . ' - ' . $account['account_description'] . '</b></td>
				</tr>';
					echo '<tr bgcolor="#bcbcbc" height="25">
					<th width="200px" style="border:1pt solid black;">Tanggal</th>
					<th style="border:1pt solid black;">Keterangan</th>
					<th style="border:1pt solid black;">Debet</th>
					<th style="border:1pt solid black;">Kredit</th>
					<th style="border:1pt solid black;">Saldo</th>
				</tr>';
					$saldo = 0;
					foreach ($jurnals as $jurnal) :
						if ($jurnal['account_code'] == $account['account_code']) {
							$saldo += ($jurnal['debet'] - $jurnal['kredit']);
							echo '<tr height="25">
					<td width="150px" style="border:1pt solid black;">' . pretty_date($jurnal['tanggal'], 'd/m/Y', false) . '</td>
					<td style="border:1pt solid black;">' . $jurnal['keterangan'] . '</td>
					<td style="border:1pt solid black;">Rp ' . number_format($jurnal['debet'], 0, ',', '.') . '</td>
					<td style="border:1pt solid black;">Rp ' . number_format($jurnal['kredit'], 0, ',', '.') . '</td>
					<td style="border:1pt solid black;">Rp ' . number_format(abs($saldo), 0, ',', '.') . '</td>
				</tr>';
						}
					endforeach;
				}
			endforeach;
		endforeach;
		echo '</table>
    					</div>
    					</div>
    				</div>';
	}
}
