<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Billing_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
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

		if (isset($q['x']) && !empty($q['x']) && $q['x'] != '') {
			$params['status'] = $q['x'];
		}

		$data['period']     = $this->Period_model->get();
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['student']    = $this->Billing_model->get_student($params);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['title'] = 'Tagihan Siswa';
		$data['main'] = 'billing/billing_list';
		$this->load->view('manage/layout', $data);
	}

	function class_searching()
	{
		$id_majors = $this->input->post('id_majors');
		$dataKelas  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();

		echo '<div class="col-md-2">  
				<div class="form-group">
				    <label>Kelas</label>
		            <select name="c" id="class_id" class="form-control" required="">
                    <option value="">-- Pilih Kelas --</option>';
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
		$id_santri              = $this->uri->segment('7');

		$student                = $this->Student_model->get(array('id' => $id_santri));
		$periodSekarang = $this->db->query("SELECT period_id AS id, period_start FROM period WHERE period_id = '$id_periode'")->row_array();
		$periodLama = $this->db->query("SELECT period_id FROM period WHERE period_start < '" . $periodSekarang['period_start'] . "'")->result_array();

		$params = array();

		$periodActive = $this->db->query("SELECT period_id AS id FROM period WHERE period_status = 1")->row_array();
		if ($periodActive['id'] != $periodSekarang['id']) {
			$month_end       = 12;
		}

		$params['student_id']   = $id_santri;
		$params['period_id']    = $id_periode;
		$params['majors_id']    = $student['majors_id'];
		$params['class_id']     = $student['class_id'];
		$params['month_start']  = $month_start;
		$params['month_end']    = $month_end;

		$data['period']         = $this->Period_model->get(array('id' => $id_periode));
		$data['from']           = $this->Bulan_model->get_month(array('id' => $month_start));
		$data['to']             = $this->Bulan_model->get_month(array('id' => $month_end));
		$data['student']        = $this->Student_model->get(array('id' => $id_santri));

		$data['bulan'] = $this->Billing_model->get_tagihan_bulan($params);
		$data['bebas'] = $this->Billing_model->get_tagihan_bebas($params);
		$data['paket'] = $this->Billing_model->get_tagihan_paket($params);

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

		$data['setting_nip_bendahara']  = $this->Setting_model->get(array('id' => 15));
		$data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
		$data['setting_city']           = $this->Setting_model->get(array('id' => SCHOOL_CITY));
		$data['setting_district']       = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
		$data['setting_school']         = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$data['setting_address']        = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
		$data['setting_phone']          = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
		$data['setting_email']          = $this->Setting_model->get(array('id' => 90));
		$data['setting_website']        = $this->Setting_model->get(array('id' => 91));

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

		if (isset($q['x']) && !empty($q['x']) && $q['x'] != '') {
			$params['status'] = $q['x'];
		}

		$data['period']     = $this->Period_model->get();
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['student']    = $this->Billing_model->get_student($params);

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


		if (isset($q['x']) && !empty($q['x']) && $q['x'] != '') {
			$params['status'] = $q['x'];
		}

		$data['period']     = $this->Period_model->get();
		$data['class']      = $this->Student_model->get_class($params);
		$data['majors']     = $this->Student_model->get_majors($params);
		$data['from']       = $this->Bulan_model->get_month($params);
		$data['to']         = $this->Bulan_model->get_month($params);
		$data['student']    = $this->Billing_model->get_student($params);

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

		$data['title'] = 'Kirim Tagihan Siswa';
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

				$periodNow = $this->db->query("SELECT period_start FROM period WHERE period_id = '$period_id'")->row_array();
				$periodLama = $this->db->query("SELECT period_id FROM period WHERE period_start < '" . $periodNow['period_start'] . "'")->result_array();

				$periode    = array();
				foreach ($periodLama as $row) {
					$periode[] = $row['period_id'];
				}

				if (count($periode) > 0) {
					$param['student_id']    = $student_id[$i];
					$param['period_id']     = $periode;
				} else {
					$param['student_id']    = "";
					$param['period_id']     = "";
				}

				//kondisi pembayaran Bulan
				//BUlan Sekarang
				$bulan          = $this->Billing_model->get_tagihan_bulan($params);
				$sumBulan       = 0;
				foreach ($bulan as $row) {
					$sumBulan += $row['bulan_bill'];
				}
				//BUlan lama
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
				$sumBulan = $sumBulan + $sumBulanLama;
				// ====================================================================================================
				//Kondisi pembayaran bebas
				//Bebas Sekarang
				$bebas = $this->Billing_model->get_tagihan_bebas($params);
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

				$sumBebasnow = $total_bebas - $total_diskon - $total_bebas_pay;

				//Bebas Lama
				if (isset($periode)) {
					$bebasLama      = $this->Billing_model->get_tagihan_bebas_lama($param);
					$bebas_lama = 0;
					foreach ($bebasLama as $row) {
						$bebas_lama += $row['bebas_bill'];
					}

					$diskon_lama = 0;
					foreach ($bebasLama as $row) {
						$diskon_lama += $row['bebas_diskon'];
					}

					$bebas_pay_lama = 0;
					foreach ($bebasLama as $row) {
						$bebas_pay_lama += $row['bebas_total_pay'];
					}
				} else {
					$bebas_lama   = 0;
					$diskon_lama   = 0;
					$bebas_pay_lama   = 0;
				}

				$sumBebaslama = $bebas_lama - $diskon_lama - $bebas_pay_lama;
				$sumBebas = $sumBebasnow + $sumBebaslama;
				//==============================================================================
				//Total Semua tagihan yng dikirim ke notif
				$total    = $sumBulan + $sumBebas;
				$nominal_total = number_format($total, 0, ",", ".");
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

				//untuk menampilkan data tahun ajaran yang sedang aktif
				$ta   = $this->db->query("SELECT period_start, period_end FROM period WHERE period_status = '1'")->row_array();
				$ta_aktif = $ta['period_start'] . '/' . $ta['period_end'];

				//menampilkan nama bulan yang sedang aktif
				$dateM = date('m');

				if ($dateM == '01') {
					$mon = '7';
				} else if ($dateM == '02') {
					$mon = '8';
				} else if ($dateM == '03') {
					$mon = '9';
				} else if ($dateM == '04') {
					$mon = '10';
				} else if ($dateM == '05') {
					$mon = '11';
				} else if ($dateM == '06') {
					$mon = '12';
				} else if ($dateM == '07') {
					$mon = '1';
				} else if ($dateM == '08') {
					$mon = '2';
				} else if ($dateM == '09') {
					$mon = '3';
				} else if ($dateM == '10') {
					$mon = '4';
				} else if ($dateM == '11') {
					$mon = '5';
				} else if ($dateM == '12') {
					$mon = '6';
				}

				$santri = $this->db->query("SELECT student_id, student_full_name, majors_short_name, majors_school_name, class_name, student_parent_phone, student_id_telegram FROM student JOIN class ON class.class_id = student.class_class_id JOIN majors ON majors.majors_id = student.majors_majors_id WHERE student.student_id = '$student_id[$i]'")->row_array();
				$wa_key             = $this->Setting_model->get(array('name' => 'setting_wa_key'));
				$setting_whatsapp   = $this->Setting_model->get(array('id' => 92));
				$set_key 			= $wa_key['setting_value'];
				$set_wa  			= $setting_whatsapp['setting_value'];

				if (isset($santri['student_parent_phone']) and $santri['student_parent_phone'] != '+62' and $total != 0) {

					//echo $student_id[$i];

					$no_wa = $santri['student_parent_phone'];

					$psn = 'Diberitahukan kepada Ayah/Bunda Wali Siswa,  kami ingin mengingatkan kembali kewajiban pembayaran ananda A/N ' . $santri['student_full_name'] . ', Kelas ' . $santri['class_name'] . 'sejumlah Rp ' . number_format($total, 0, ",", ".") . '. sudah bisa ditunaikan.' . "\n\n" .
						'Kami mengharap kerjasama Ayah/Bunda agar bisa menunaikan kewajiban paling lambat tgl 10 tiap bulannya.' . "\n\n" .
						'Download Tagihan : ' . base_url() . 'billing/cetak?a=' . base64_encode($period_id) . '&b=' . base64_encode('1') . '&c=' . base64_encode($till) . '&d=' . base64_encode($santri['student_id']) . "\n\n" . '*) Jika tagihan tidak sesuai dengan kartu syahriah atau sudah melakukan pembayaran agar konfirmasi dengan mengirimkan bukti pembayaran / kwitansi yang sah.';
					send_whatsapp($no_wa, $psn, $set_key, $set_wa);
				}
			}


			$this->session->set_flashdata('success', ' Kirim Tagihan Siswa Berhasil Dikirim');
			redirect('manage/billing/send_billing');
		}
	}
}
