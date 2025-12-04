<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Billing_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array('billing/Billing_model', 'student/Student_model', 'account/Account_model', 'pos/Pos_model', 'payment/Payment_model', 'period/Period_model', 'pos/Pos_model', 'bulan/Bulan_model', 'bebas/Bebas_model', 'bebas/Bebas_pay_model', 'setting/Setting_model', 'logs/Logs_model', 'report/Report_model', 'report/Detail_jurnal_model'));
		$this->load->library('upload');

		$this->load->helper(array('send_helper'));
	}

	// pos view in list
	public function index($offset = NULL)
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();
		$periodAktif 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status = 1")->row_array();
		$params['period_start'] = $periodAktif['period_start'];
		// $params['period_end'] 	= $periodAktif['period_end'];

		$tParams = 0;
		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
			if ($q['p'] != 'all') {
				$idPeriodSelected 		=  $q['p'];
				$periodSelected 		= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_id = $idPeriodSelected")->row_array();
				$params['period_start'] = $periodSelected['period_start'];
			}

			$tParams += 1;
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id']  = $q['c'];
			$tParams += 1;
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
			$tParams += 1;
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['month_start'] = $q['d'];
		}

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
			$params['month_end'] = $q['s'];
		}

		if ($tParams >= 2) {
			$data['tagihan_siswa']    = $this->Billing_model->get_tagihan_siswa($params);
		}

		$data['period']     = $this->Period_model->get();
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$data['title'] = 'Tagihan Santri';
		$data['main'] = 'billing/billing_list';
		$this->load->view('manage/layout', $data);
	}

	function rincian_tagihan()
	{
		$params = array();

		// ambil period aktif sebagai default period_start (tahun)
		$periodAktif = $this->db
			->select('period_id, period_start, period_end')
			->where('period_status', 1)
			->get('period')
			->row_array();

		$params['period_start'] = isset($periodAktif['period_start']) ? $periodAktif['period_start'] : null;

		// student id
		$params['student_id'] = (int) $this->input->post('student');

		// period filter: bisa 'all' atau id period
		$period_input = $this->input->post('period');
		$params['period_id'] = ($period_input === null) ? null : $period_input;

		if ($params['period_id'] !== null && $params['period_id'] !== 'all') {
			// gunakan nilai dari input period (casting ke int)
			$idPeriodSelected = (int) $params['period_id'];
			$periodSelected = $this->db
				->select('period_id, period_start, period_end')
				->where('period_id', $idPeriodSelected)
				->get('period')
				->row_array();

			if (!empty($periodSelected)) {
				$params['period_start'] = $periodSelected['period_start'];
			}
		}

		// bulan (safe cast)
		$params['month_start'] = (int) $this->input->post('month_start');
		$params['month_end']   = (int) $this->input->post('month_end');

		// fallback jika kosong
		if (empty($params['month_start'])) $params['month_start'] = 1;
		if (empty($params['month_end'])) $params['month_end'] = 12;

		$dataTagihan = $this->Billing_model->get_rincian_tagihan($params);

		echo json_encode($dataTagihan);
	}

	function tagihan_kelas()
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
		}

		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
			$params['majors_id'] = $q['m'];
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['month_start'] = $q['d'];
		}

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
			$params['month_end'] = $q['s'];
		}

		$data['period']     = $this->Period_model->get($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['posBayar'] = $this->Billing_model->getPosBayar($params);
		$data['kelas']    = $this->Billing_model->getKelas($params);
		$data['dataTagihan']  = $this->Billing_model->getTagihanPerKelas($params);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['title'] = 'Rekap Tagihan Kelas';
		$data['main'] = 'billing/tagihan_kelas';
		$this->load->view('manage/layout', $data);
	}

	function rekap_tagihan_kelas()
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();

		$bulan = ["Juli", "Agustus", "September", "Oktober", "November", "Desember", "Januari", "Februari", "Maret", "April", "Mei", "Juni"];

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
		}

		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
			$params['majors_id'] = $q['m'];
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['month_start'] = $q['d'];
			$data['bulanAwal'] = $bulan[$q['d'] - 1];
		}

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
			$params['month_end'] = $q['s'];
			$data['bulanAkhir'] = $bulan[$q['s'] - 1];
		}

		$data['posBayar'] = $this->Billing_model->getPosBayar($params);
		$data['kelas']    = $this->Billing_model->getKelas($params);
		$data['dataTagihan']  = $this->Billing_model->getTagihanPerKelas($params);

		if ($params['majors_id'] == "all") {
			$data['unit'] = "Semua Unit";
		} else {
			$idMajors = $params['majors_id'];
			$majors  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id='$idMajors'")->row_array();
			$data['unit'] = $majors['majors_short_name'];
		}

		$setting = $this->db->query("SELECT setting_name, setting_value FROM setting WHERE setting_name IN ('setting_school','setting_district','setting_city','setting_address','setting_phone','setting_nip_bendahara','setting_nama_bendahara')")->result();

		foreach ($setting as $value) {
			$data[$value->setting_name] = $value->setting_value;
		}

		if (!empty($params['period_id'])) {
			$idPeriod = $params['period_id'];
			$dataPeriod  = $this->db->query("SELECT period_start, period_end FROM period WHERE period_id='$idPeriod'")->row_array();
			$data['periode'] = $dataPeriod['period_start'] . '-' . $dataPeriod['period_end'];
		}

		$this->load->view('billing/tagihan_per_kelas', $data);
	}

	function class_searching()
	{
		$id_majors = $this->input->post('id_majors');
		$dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

		$semuaKelas = $this->input->post('allClass');
		$required = 'required=""';
		$firstOption = '-- Pilih Kelas --';

		if ($semuaKelas == 1) {
			$required = '';
			$firstOption = '-- SEMUA KELAS --';
		}

		echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" ' . $required . '>
                    <option value="">' . $firstOption . '</option>';
		foreach ($dataKelas as $row) {

			echo '<option value="' . $row['class_id'] . '">';

			echo $row['class_name'];

			echo '</option>';
		}
		echo '</select>
				</div>
			</div>';
	}

	function print_bill()
	{
		$this->load->library('html2pdflib');

		$id_periode             = $this->uri->segment('4');
		$month_start            = $this->uri->segment('5');
		$month_end              = $this->uri->segment('6');
		$id_siswa               = $this->uri->segment('7');

		$periodAktif = $this->db->query("SELECT period_id FROM period WHERE period_status = 1")->row_array();
		$idPeriodAktif = $periodAktif['period_id'];

		if ($idPeriodAktif != $id_periode) {
			$month_start            = 1;
			$month_end              = 12;
		}

		$student                = $this->Student_model->get(array('id' => $id_siswa));

		$params = array();

		$params['student_id']   = $id_siswa;
		$params['period_id']    = $id_periode;
		$params['majors_id']    = $student['majors_id'];
		$params['class_id']     = $student['class_id'];
		$params['month_start']  = $month_start;
		$params['month_end']    = $month_end;

		$data['period']         = $this->Period_model->get(array('id' => $id_periode));
		$data['from']           = $this->Bulan_model->get_month(array('id' => $month_start));
		$data['to']             = $this->Bulan_model->get_month(array('id' => $month_end));
		$data['student']        = $this->Student_model->get(array('id' => $id_siswa));

		$data['bulan']          = $this->Billing_model->get_tagihan_bulan($params);
		$data['bebas']          = $this->Billing_model->get_tagihan_bebas($params);

		$periodSekarang = $this->db->query("SELECT period_start FROM period WHERE period_id = '$id_periode'")->row_array();

		$periodLama = $this->db->query("SELECT period_id FROM period WHERE period_start < '" . $periodSekarang['period_start'] . "'")->result_array();

		$periode    = array();
		foreach ($periodLama as $row) {
			$periode[] = $row['period_id'];
		}

		if (count($periode) > 0) {

			$param['student_id']    = $id_siswa;
			$param['period_id']     = $periode;
			$param['majors_id']     = $student['majors_id'];
			$param['class_id']      = $student['class_id'];
			//$param['month_start']   = $month_start;
			//$param['month_end']     = $month_end;

		} else {

			$param['student_id']    = "";
			$param['period_id']     = "";
			$param['majors_id']     = "";
			$param['class_id']      = "";
		}

		$data['bulanLama']      = $this->Billing_model->get_tagihan_bulan_lama($param);
		$data['bebasLama']      = $this->Billing_model->get_tagihan_bebas_lama($param);


		$data['setting_nip_kepsek']     = $this->Setting_model->get(array('id' => 11));
		$data['setting_nama_kepsek']    = $this->Setting_model->get(array('id' => 12));
		$data['setting_nip_katu']       = $this->Setting_model->get(array('id' => 13));
		$data['setting_nama_katu']      = $this->Setting_model->get(array('id' => 14));
		$data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
		$data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

		$filename = 'Tagihan-Pembayaran-' . $student['student_full_name'] . '-Kelas-' . $student['class_name'] . '.pdf';

		$html = $this->load->view('billing/billing_print', $data, TRUE);

		$paper = 'A4';
		$orientation = 'P';
		$download = FALSE;

		$this->html2pdflib->generate($html, $filename, $download, $paper, $orientation);
	}

	public function billing_excel()
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id']  = $q['c'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['month_start'] = $q['d'];
		}

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
			$params['month_end'] = $q['s'];
		}

		$data['period']     = $this->Period_model->get($params);
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['student']    = $this->Student_model->get($params);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$this->load->view('billing/billing_xls', $data);
	}

	public function billing_excel_rekap()
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id']  = $q['c'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['month_start'] = $q['d'];
		}

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
			$params['month_end'] = $q['s'];
		}

		$data['period']     = $this->Period_model->get($params);
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['student']    = $this->Student_model->get($params);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$this->load->view('billing/billing_xls_rekap', $data);
	}

	// pos view in list
	public function send_billing($offset = NULL)
	{
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
		}

		if (isset($q['m']) && !empty($q['m']) && $q['m'] != '') {
			$params['madin_id']  = $q['m'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id']  = $q['c'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
		}

		if (isset($q['d']) && !empty($q['d']) && $q['d'] != '') {
			$params['month_start'] = $q['d'];
		}

		if (isset($q['s']) && !empty($q['s']) && $q['s'] != '') {
			$params['month_end'] = $q['s'];
		}

		$data['period']     = $this->Period_model->get($params);
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['student']    = $this->Student_model->get($params);
		$data['madin']      = $this->Student_model->get_madin(array());

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['title'] = 'Kirim Tagihan Santri';
		$data['main'] = 'billing/billing_send_list';
		$this->load->view('manage/layout', $data);
	}

	function get_form()
	{

		for ($count = 0; $count < count($_POST["student_id"]); $count++) {
			$student = $this->db->query("SELECT student_id
                                        FROM student
                                        WHERE student_id IN (" . $_POST['student_id'][$count] . ")")->result_array();

			foreach ($student as $row) {
				echo '<input type="hidden" name="student_id[]" id="student_id" value="' . $row['student_id'] . '">';
			}
		}
	}

	public function send()
	{
		if ($_POST == TRUE) {

			$wa_center  = $this->Setting_model->get(array('id' => 17));

			$period_id      = $_POST['period_id'];
			$student_id     = $_POST['student_id'];
			$majors_id      = $_POST['majors_id'];
			$class_id       = $_POST['class_id'];

			$now = pretty_date(date('d-m-Y'), 'F', false);

			$month = $this->db->query("SELECT month_id FROM month WHERE month_name = '$now'")->row_array();

			$cpt = count($_POST['student_id']);
			for ($i = 0; $i < $cpt; $i++) {

				$params['student_id']   = $student_id[$i];
				$params['period_id']    = $period_id;
				$params['month_start']  = 1;
				$params['month_end']    = $month['month_id'];

				$bulan          = $this->Billing_model->get_tagihan_bulan($params);

				$periodNow = $this->db->query("SELECT period_start FROM period WHERE period_id = '$period_id'")->row_array();

				$periodLama = $this->db->query("SELECT period_id FROM period WHERE period_start < '" . $periodNow['period_start'] . "'")->result_array();

				$periode    = NULL;
				foreach ($periodLama as $row) {
					$periode = $row['period_id'];
				}

				$param['student_id']    = $student_id[$i];
				$param['period_id']     = $periode;

				$sumBulan       = 0;
				foreach ($bulan as $row) {
					$sumBulan += $row['bulan_bill'];
				}

				if (isset($periode)) {
					$bulanLama      = $this->Billing_model->get_tagihan_bulan_lama($param);
					$sumBulanLama   = 0;
					foreach ($bulanLama as $row) {
						$sumBulanLama += $row['bulan_bill'];
					}
				} else {
					$sumBulanLama   = 0;
				}

				$bulan = $sumBulan + $sumBulanLama;
				$bebas = $this->Bebas_model->get(array('student_id' => $student_id[$i]));

				$sumBulan = $sumBulan + $sumBulanLama;

				$total_bebas = 0;
				foreach ($bebas as $row) {
					$total_bebas += $row['bebas_bill'];
				}

				$total_diskon = 0;
				foreach ($bebas as $row) {
					$total_diskon += $row['bebas_diskon'];
				}

				$total_bebas_pay = 0;
				foreach ($bebas as $row) {
					$total_bebas_pay += $row['bebas_total_pay'];
				}

				$sumBebas = $total_bebas - $total_diskon - $total_bebas_pay;

				$total    = $sumBulan + $sumBebas;

				$dateM = date('m');

				if ($dateM == '01') {
					$till = '7';
				} else if ($dateM == '02') {
					$till = '8';
				} else if ($dateM == '03') {
					$till = '9';
				} else if ($dateM == '04') {
					$till = '10';
				} else if ($dateM == '05') {
					$till = '11';
				} else if ($dateM == '06') {
					$till = '12';
				} else if ($dateM == '07') {
					$till = '1';
				} else if ($dateM == '08') {
					$till = '2';
				} else if ($dateM == '09') {
					$till = '3';
				} else if ($dateM == '10') {
					$till = '4';
				} else if ($dateM == '11') {
					$till = '5';
				} else if ($dateM == '12') {
					$till = '6';
				}

				$santri = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_school_name, class_name, student_parent_phone, student_id_telegram FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_id = '$student_id[$i]'")->row_array();
				$wa_key             = $this->Setting_model->get(array('name' => 'setting_wa_key'));
				$setting_whatsapp   = $this->Setting_model->get(array('id' => 92));
				$set_key 			= $wa_key['setting_value'];
				$set_wa  			= $setting_whatsapp['setting_value'];

				if (isset($santri['student_parent_phone']) and $santri['student_parent_phone'] != '+62' and $total != 0) {

					//echo $student_id[$i];

					$no_wa = $santri['student_parent_phone'];
					//$no_wa='+6281335111174';
					$psn = 'Assalamualaikum, Bismillahirrahmanirrahim..' . "\n\n" . 'Diberitahukan kepada Ayah/Bunda Wali Siswa,  kami ingin mengingatkan kembali kewajiban pembayaran ananda A/N ' . $santri['student_full_name'] . ', Kelas ' . $santri['class_name'] . 'sejumlah Rp ' . number_format($total, 0, ",", ".") . '. sudah bisa ditunaikan.' . "\n\n" .
						'Kami mengharap kerjasama Ayah/Bunda agar bisa menunaikan kewajiban paling lambat tgl 10 tiap bulannya.' . "\n\n" .
						'Download Tagihan : ' . base_url() . 'billing/cetak?a=' . base64_encode($period_id) . '&b=' . base64_encode('1') . '&c=' . base64_encode($till) . '&d=' . base64_encode($santri['student_id']) . "\n\n" . '*) Jika tagihan tidak sesuai dengan kartu syahriah atau sudah melakukan pembayaran agar konfirmasi dengan mengirimkan bukti pembayaran / kwitansi yang sah.';
					send_whatsapp($no_wa, $psn, $set_key, $set_wa);
				}
			}


			$this->session->set_flashdata('success', ' Kirim Tagihan Santri Berhasil Dikirim');
			redirect('manage/billing/send_billing');
		}
	}
}
