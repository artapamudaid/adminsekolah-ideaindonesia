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

		$whatsapp_cs = WHATSAPP_CS;
		$setting_end = $this->db->get_where('setting', ['setting_name' => 'setting_end'])->row();
		$suspend_date = $setting_end ? $setting_end->setting_value : null;
		$school_name = $data['school']['setting_value'] ?? 'Aplikasi Sekolah';

		$today = date("Y-m-d");

		$diff = (strtotime($suspend_date) - strtotime($today)) / 86400;
		$days_left = ceil($diff);

		$formatted_date = pretty_date($suspend_date, 'd F Y', false);

		if ($days_left <= 0) {
			$wa_message = "*Halo Admin,*\n\n" . "Masa berlangganan aplikasi *Admin Sekolah {$school_name}* " . "saya telah berakhir pada *{$formatted_date}*.\n\n" . "Saya ingin memperpanjang langganan aplikasi " . "*Admin Sekolah {$school_name}*.\n\n" . "Mohon informasi lebih lanjut.";
		} else {
			$wa_message = "*Halo Admin,*\n\n" . "Masa berlangganan aplikasi *Admin Sekolah {$school_name}* " . "saya akan segera berakhir pada *{$formatted_date}*.\n\n" . "Saya ingin memperpanjang langganan aplikasi " . "*Admin Sekolah {$school_name}*.\n\n" . "Mohon informasi lebih lanjut.";
		}
		$whatsappUrl = 'https://wa.me/' . $whatsapp_cs . '?text=' . urlencode($wa_message);
		$data['whatsappUrl'] = $whatsappUrl;

		$this->load->view('block', $data);
	}
}