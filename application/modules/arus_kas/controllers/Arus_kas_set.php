<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arus_kas_set extends CI_Controller
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
		$config['base_url'] = site_url('manage/arus_kas/index');
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");


		$data['majors'] = $this->Student_model->get_majors();

		$data['title'] = 'Arus Kas';
		$data['main'] = 'arus_kas/arus_kas_list';
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

		$this->load->model('arus_kas_model');
		$tabelmodel = $this->arus_kas_model;

		$saldoJurnal = $tabelmodel->get_sum($majors_id);

		$saldoSekarang = $saldoJurnal['total_debet'] - $saldoJurnal['total_kredit'];

		$debets = $tabelmodel->get_debet($majors_id);

		$kredits = $tabelmodel->get_kredit($majors_id);

		echo '<div class="box box-primary box-solid">
            		    <div class="box-header with-border">
            			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Arus Kas</h3>
            			</div>
            			<div class="box-body table-responsive">
            			<table class="table table-responsive table-hover table-bordered" style="white-space: nowrap;">
						<tr>
							<td>
								<font size="4"><strong>LAPORAN ARUS KAS</strong></font><br />
							<td align="right" valign="top">
							
							</td>
						</tr>
						</table>
						<table border="0" cellpadding="10" cellspacing="5" background="images/bttablelong2.png" align="center" width="70%">
						<tr height="30">
							<td colspan="4" align="left"><font size="2"><strong>Arus Kas dari Kegiatan Operasional</strong></font></td>
                        </tr>';
		$totalDebet = 0;
		foreach ($debets as $debet) :
			$totalDebet += $debet['nominal'];
			echo '</tr>
							<tr height="25">
							<td width="20">&nbsp;</td>
							<td width="420">' . $debet['account_description'] . '</td>
							<td width="120" align="right">Rp ' . number_format($debet['nominal'], 0, ',', '.') . '</td>
							<td width="120" align="right">&nbsp;</td>
						</tr>';
		endforeach;

		$totalKredit = 0;
		foreach ($kredits as $kredit) :
			$totalKredit += $kredit['nominal'];
			echo '</tr>
							<tr height="25">
							<td width="20">&nbsp;</td>
							<td width="420">' . $kredit['account_description'] . '</td>
							<td width="120" align="right">Rp ' . number_format($kredit['nominal'], 0, ',', '.') . '</td>
							<td width="120" align="right">&nbsp;</td>
						</tr>';
		endforeach;
		echo '<tr height="30">
							<td width="20">&nbsp;</td>
							<td width="420"><font size="2"><strong><em>Arus Kas Bersih Kegiatan Operasional</em></strong></font></td>
							<td width="120" align="right">&nbsp;</td>
							<td width="120" align="right"><font size="2"><strong>
							Rp ' . number_format($totalDebet - $totalKredit, 0, ',', '.') . '</strong></font></td>
						</tr>
						
						
						<tr height="5">
						<td colspan="4" align="left">&nbsp;</td>
						</tr>
						
						<tr height="30">
						<td colspan="4" align="left"><font size="2"><strong>Arus Kas dari Kegiatan Investasi</strong></font></td>
						</tr>
						
						<tr height="30">
							<td width="20">&nbsp;</td>
							<td width="420"><font size="2"><strong><em>Arus Kas Bersih Kegiatan Investasi</em></strong></font></td>
							<td width="120" align="right">&nbsp;</td>
							<td width="120" align="right"><font size="2"><strong>
						Rp 0.</strong></font></td>
						</tr>
						
						<tr height="5">
						<td colspan="4" align="left">&nbsp;</td>
						</tr>
						
                        <tr height="30">
						<td colspan="4" align="left"><font size="2"><strong>Arus Kas dari Kegiatan Keuangan/Pendanaan</strong></font></td>
						
						<tr height="30">
							<td width="20">&nbsp;</td>
							<td width="420"><font size="2"><strong><em>Arus Kas Bersih Kegiatan Keuangan/Pendanaan</em></strong></font></td>
							<td width="120" align="right">&nbsp;</td>
							<td width="120" align="right"><font size="2"><strong>
						Rp 0.</strong></font></td>
						</tr>
						
						<tr height="5">
						<td colspan="4" align="left">&nbsp;</td>
						</tr>
						
						<tr height="30">
							<td colspan="3"><font size="2"><strong><em>Perubahan Kas</em></strong></font></td>
							<td width="150" align="right"><font size="2"><strong>
						Rp  ' . number_format($saldoSekarang, 0, ',', '.') . '</strong></font></td>
						</tr>
						<tr height="30">
							<td colspan="3"><font size="2"><strong><em>Saldo Kas Sebelumnya</em></strong></font></td>
							<td width="120" align="right"><font size="2"><strong>
						Rp ' . number_format($saldoAwal['total'], 0, ',', '.') . '</strong></font></td>
						</tr>
						
						<tr height="30">
							<td colspan="3"><font size="2"><strong><em>Saldo Kas Sekarang</em></strong></font></td>
							<td width="150" align="right"><font size="2"><strong>
						Rp ' . number_format($saldoAwal['total'] + $saldoSekarang, 0, ',', '.') . '</strong></font></td>
						</tr>';
		echo '</table>
    					</div>
    					</div>
    				</div>';
	}
}
