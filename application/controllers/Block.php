<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Block extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$query = $this->db->get_where('setting', ['setting_name' => 'setting_app_status'])->row_array();
		if (isset($query['setting_value']) && $query['setting_value'] == 'Y') {
			redirect('manage');
		}
	}

	public function index()
	{
		$this->db->where('setting_name', 'setting_school');
		$data['school'] = $this->db->get('setting')->row_array();
		$this->load->view('block', $data);
	}
}
