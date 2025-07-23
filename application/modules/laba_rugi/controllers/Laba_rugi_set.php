<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laba_rugi_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
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
    			  <h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Laba Rugi' .
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
    					    <a class="btn btn-success" target="_blank" href="' . base_url() . 'manage/laba_rugi/export_xls/?unit=' . $_REQUEST['majors_id'] . '&start=' . $_REQUEST['start'] . '&end=' . $_REQUEST['end'] . '"><span class="fa fa-file-excel-o"></span> Cetak Excel
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
