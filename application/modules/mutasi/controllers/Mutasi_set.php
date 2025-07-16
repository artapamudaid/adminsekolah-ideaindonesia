<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_set extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

  // User_customer view in list
  public function index($offset = NULL)
  {

	$params = array();

    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));

    $data['title'] = 'Laporan Mutasi H2H';
    $data['main'] = 'mutasi/mutasi_list';
    $this->load->view('manage/layout', $data);
  }

  function get_data()
  {
	  
	$between 	= '';
	$note 		= '';
	$start 		= $this->input->post('start');
	$end 		= $this->input->post('end');
	$status     = $this->input->post('status');

	if ($start && $end) {
		$between = "AND DATE(a.created_date) BETWEEN '$start' AND '$end'";
	}  
	  
	if ($status) {
		$note = "AND b.status = '$status'";
	}
	 
    $data = $this->db->query("SELECT  DATE_FORMAT(a.created_date, '%d-%m-%Y') AS DATEPAY,
								b.noref AS REFNO, a.VANO, a.CUSTNAME, CAST(a.PAYMENT AS UNSIGNED)/100 AS NOMINAL,
								b.status AS STATUS
								FROM bmi_payment a
								JOIN ipaymu_transaksi b ON a.VANO = b.va_no
								WHERE b.va_channel = 'h2h|bmi'
								AND a.CUSTNAME != 'ADMIN EP' 
								$between
								$note
								ORDER BY a.created_date DESC")->result();

    echo json_encode($data);
  }
	
  function export_data()
  {
	$tanggal    	  = 'Semua Tanggal';
	$tanggal_title    = 'Semua_Tanggal';
	$between 		  = '';
	$note 			  = '';
	$start 			  = $this->input->get('start');
	$end 		      = $this->input->get('end');
	$status     	  = $this->input->get('status');
	$status_title     = 'Semua_Status';

	if ($start && $end) {
		$between 		= "AND DATE(a.created_date) BETWEEN '$start' AND '$end'";
		$tanggal 		= pretty_date($start, 'd-m-Y', false) . ' s/d ' . pretty_date($end, 'd-m-Y', false);
		$tanggal_title 	= pretty_date($start, 'd-m-Y', false) . '_' . pretty_date($end, 'd-m-Y', false);
	}  
	  
	if ($status) {
		$note 			= "AND b.status = '$status'";
		$status_title 	= $status;
	}
	  
	  $data['setting_nip_bendahara'] 	= $this->Setting_model->get(array('id' => 15));
	  $data['setting_nama_bendahara'] 	= $this->Setting_model->get(array('id' => 16));
	  $data['setting_city'] 			= $this->Setting_model->get(array('id' => SCHOOL_CITY));
	  $data['setting_district'] 		= $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
	  $data['setting_school'] 			= $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
	  $data['setting_address'] 			= $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
	  $data['setting_phone'] 			= $this->Setting_model->get(array('id' => SCHOOL_PHONE));  
	 
    $data['mutasi'] 	= $this->db->query("SELECT  DATE_FORMAT(a.created_date, '%d-%m-%Y') AS DATEPAY,
								b.noref AS REFNO, a.VANO, a.CUSTNAME, CAST(a.PAYMENT AS UNSIGNED)/100 AS NOMINAL,
								b.status AS STATUS
								FROM bmi_payment a
								JOIN ipaymu_transaksi b ON a.VANO = b.va_no
								WHERE b.va_channel = 'h2h|bmi'
								AND a.CUSTNAME != 'ADMIN EP' 
								$between
								$note
								ORDER BY a.created_date DESC")->result();
	  
	$data['status'] 		= $status;
	$data['tanggal'] 		= $tanggal;
	$data['tanggal_title'] 	= $tanggal_title;
	$data['status_title'] 	= $status_title;

    $this->load->view('mutasi/mutasi_xls', $data);
  }
}
