<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_easet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata("logged") <> 1) {
			redirect(site_url('login'));
		}

		$this->load->model('ModelMonitoring', 'mm');
		$this->load->model('ModelAset', 'ma');

		$this->load->library('upload');
		$this->load->helper('terbilang');
	}

	public function index()
	{
		$data = array(
			'title' => 'Monitoring Aset',
			'active_menu_mt' => 'active',
			'mt' => $this->mm->getMonitoring()
		);

		// $this->load->view('layouts/header', $data);
		// $this->load->view('monitoring/v_monitoring', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Monitoring';
		$data['main']           = 'easet/monitoring/v_monitoring';
		$this->load->view('manage/layout', $data);
	}

	public function tambahMonitoring()
	{
		$data = array(
			'title' => 'Monitoring Aset',
			'active_menu_mt' => 'active',
			'aset' => $this->ma->getAsetWujud()
		);

		// $this->load->view('layouts/header', $data);
		// $this->load->view('monitoring/c_monitoring', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Tambah Monitoring';
		$data['main']           = 'easet/monitoring/c_monitoring';
		$this->load->view('manage/layout', $data);
	}

	public function simpanMonitoring()
	{
		$config['upload_path'] = 'easet/src/img/aset/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = TRUE;
		$config['file_ext_tolower'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$config['detect_mime'] = TRUE;
		$config['mod_mime_fix'] = TRUE;

		$this->upload->initialize($config);
		if (!empty($_FILES['foto']['name'])) {

			if ($this->upload->do_upload('foto')) {
				$gbr = $this->upload->data();
				//Compress Image
				$config['image_library'] = 'gd2';
				$config['source_image'] = 'easet/src/img/aset/' . $gbr['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '60%';
				$config['width'] = 500;
				$config['height'] = 500;
				$config['new_image'] = 'easet/src/img/aset/' . $gbr['file_name'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();

				$data = array(
					'id_aset' => $this->input->post('id_aset'),
					'kerusakan' => $this->input->post('kerusakan'),
					'akibat' => $this->input->post('akibat'),
					'faktor' => $this->input->post('faktor'),
					'monitoring' => $this->input->post('monitoring'),
					'pemeliharaan' => $this->input->post('pemeliharaan'),
					'jml_rusak' => $this->input->post('jml_rusak'),
					'foto' => $gbr['file_name'],
					'updated_at' => date('Y-m-d H:i:s')
				);
				$this->mm->storeMonitoring($data);

				$this->session->set_flashdata('sukses', 'Disimpan');
				redirect('manage/easet/monitoring');
			}
		} else {
			$this->session->set_flashdata('gagal', 'Disimpan');
			redirect('manage/easet/monitoring/tambahMonitoring');
		}
	}

	public function detailMonitoring($id_monitoring)
	{
		$id_monitoring = $this->uri->segment(5);

		$data = array(
			'title' => 'Monitoring Aset',
			'active_menu_mt' => 'active',
			'mt' => $this->mm->getDetailMonitoring($id_monitoring),
			'aset' => $this->mm->getDetailAset($id_monitoring)
		);

		// $this->load->view('layouts/header', $data);
		// $this->load->view('monitoring/d_monitoring', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Detail Monitoring';
		$data['main']           = 'easet/monitoring/d_monitoring';
		$this->load->view('manage/layout', $data);
	}

	public function editMonitoring($id_monitoring)
	{
		$id_monitoring = $this->uri->segment(5);

		$data = array(
			'title' => 'Monitoring Aset',
			'active_menu_mt' => 'active',
			'aset' => $this->ma->getAsetWujud(),
			'mt' => $this->mm->getDetailMonitoring($id_monitoring)
		);

		// $this->load->view('layouts/header', $data);
		// $this->load->view('monitoring/u_monitoring', $data);
		// $this->load->view('layouts/footer');

		$data['title']          = 'Edit Monitoring';
		$data['main']           = 'easet/monitoring/u_monitoring';
		$this->load->view('manage/layout', $data);
	}

	public function ubahMonitoring()
	{
		$id_monitoring = $this->input->post('id_monitoring');
		if ($_FILES['foto']['name']) {

			$config['upload_path'] = 'easet/src/img/aset/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['encrypt_name'] = TRUE;
			$config['file_ext_tolower'] = TRUE;
			$config['remove_spaces'] = TRUE;
			$config['detect_mime'] = TRUE;
			$config['mod_mime_fix'] = TRUE;

			$this->upload->initialize($config);
			if (! $this->upload->do_upload('foto')) {
				$this->session->set_flashdata('gagal', 'Diupload');
				redirect('manage/easet/monitoring/editMonitoring/' . $id_monitoring);
			} else {
				$ambildata = $this->mm->getDetailAset($id_monitoring);
				foreach ($ambildata as $foto) {
					unlink('easet/src/img/aset/' . $foto['foto']);
				}

				$gbr = $this->upload->data();
				//Compress Image
				$config['image_library'] = 'gd2';
				$config['source_image'] = 'easet/src/img/aset/' . $gbr['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '60%';
				$config['width'] = 500;
				$config['height'] = 500;
				$config['new_image'] = 'easet/src/img/aset/' . $gbr['file_name'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();

				$data = array(
					'id_aset' => $this->input->post('id_aset'),
					'kerusakan' => $this->input->post('kerusakan'),
					'akibat' => $this->input->post('akibat'),
					'faktor' => $this->input->post('faktor'),
					'monitoring' => $this->input->post('monitoring'),
					'pemeliharaan' => $this->input->post('pemeliharaan'),
					'jml_rusak' => $this->input->post('jml_rusak'),
					'foto' => $gbr['file_name'],
					'updated_at' => date('Y-m-d H:i:s')
				);
				unset($data['id_monitoring']);
				$this->mm->updateMonitoring($id_monitoring, $data);

				$this->session->set_flashdata('sukses', 'Diubah');
				redirect('manage/easet/monitoring');
			}
		} else {
			$data = array(
				'id_aset' => $this->input->post('id_aset'),
				'kerusakan' => $this->input->post('kerusakan'),
				'akibat' => $this->input->post('akibat'),
				'faktor' => $this->input->post('faktor'),
				'monitoring' => $this->input->post('monitoring'),
				'pemeliharaan' => $this->input->post('pemeliharaan'),
				'jml_rusak' => $this->input->post('jml_rusak'),
				'updated_at' => date('Y-m-d H:i:s')
			);
			unset($data['id_monitoring']);
			$res = $this->mm->updateMonitoring($id_monitoring, $data);
			if ($res >= 1) {
				$this->session->set_flashdata('sukses', 'Diubah');
				redirect('manage/easet/monitoring');
			} else {
				$this->session->set_flashdata('gagal', 'Diupload');
				redirect('manage/easet/monitoring/editMonitoring/' . $id_monitoring);
			}
		}
	}

	public function hapusMonitoring($id_monitoring)
	{
		$id_monitoring = $this->uri->segment(5);
		$this->db->where('id_monitoring', $id_monitoring);
		$get_image_file = $this->db->get('monitoring_aset')->row();
		@unlink('easet/src/img/aset/' . $get_image_file->foto);
		$this->db->where('id_monitoring', $id_monitoring);
		$this->db->delete('monitoring_aset');
		$this->session->set_flashdata('sukses', 'Dihapus');
		redirect('manage/easet/monitoring');
	}
}

/* End of file Monitoring.php */
/* Location: ./application/controllers/Monitoring.php */
