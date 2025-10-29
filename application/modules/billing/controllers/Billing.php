<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Billing extends CI_Controller
{

  public function __construct()
  {
    parent::__construct(TRUE);

    $this->load->model(array('billing/Billing_model', 'student/Student_model', 'account/Account_model', 'pos/Pos_model', 'payment/Payment_model', 'period/Period_model', 'pos/Pos_model', 'bulan/Bulan_model', 'bebas/Bebas_model', 'bebas/Bebas_pay_model', 'setting/Setting_model', 'logs/Logs_model', 'report/Report_model', 'report/Detail_jurnal_model'));
    $this->load->library('upload');
  }

  function cetak()
  {
    $this->load->library('html2pdflib');

    $f = $this->input->get(NULL, TRUE);

    $data['f'] = $f;

    // Tahun Ajaran
    if (isset($f['a']) && !empty($f['a']) && $f['a'] != '') {
      $id_periode   = base64_decode($f['a']);
    }

    // Siswa
    if (isset($f['b']) && !empty($f['b']) && $f['b'] != '') {
      $month_start  = base64_decode($f['b']);
    }

    if (isset($f['c']) && !empty($f['c']) && $f['c'] != '') {
      $month_end    = base64_decode($f['c']);
    }

    if (isset($f['d']) && !empty($f['d']) && $f['d'] != '') {
      $id_siswa    = base64_decode($f['d']);
    }

    $student                = $this->Student_model->get_student(array('id' => $id_siswa));

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
    $data['student']        = $this->Student_model->get_student(array('id' => $id_siswa));

    $data['bulan']          = $this->Billing_model->get_tagihan_bulan($params);
    $data['bebas']          = $this->Billing_model->get_tagihan_bebas($params);
    $data['paket']          = $this->Billing_model->get_tagihan_paket($params);

    $periodLama = $this->db->query("SELECT period_id FROM period WHERE period_id != '$id_periode'")->result_array();

    $periode    = NULL;
    foreach ($periodLama as $row) {
      $periode = $row['period_id'];
    }

    if (isset($periode)) {

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
    $data['paketLama']      = $this->Billing_model->get_tagihan_paket_lama($param);

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
}
