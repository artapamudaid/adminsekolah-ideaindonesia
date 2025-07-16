<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homestay_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array('homestay/Homestay_model'));
		$this->load->library('upload');
	}

	// homestay view in list
	public function index($offset = NULL)
	{
		$f = $this->input->get(NULL, TRUE);

		$data['f'] = $f;

		$params = array();
		if ($this->session->userdata('uroleid') == 9) {
			$params['user_id'] = $this->session->userdata('uid');
		}

		$data['homestay'] = $this->Homestay_model->get($params);

		$config['base_url'] = site_url('manage/homestay/index');
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['title'] = 'Penginapan';
		$data['main'] = 'homestay/homestay_list';
		$this->load->view('manage/layout', $data);
	}


	// Add homestay and Update
	public function add($id = NULL)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('homestay_name', 'Judul', 'trim|required|xss_clean');
		$this->form_validation->set_rules('homestay_desc', 'Keterangan', 'trim|required|xss_clean');

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
		$data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

		if ($_POST and $this->form_validation->run() == TRUE) {

			if ($this->input->post('homestay_id')) {
				$params['homestay_id'] = $this->input->post('homestay_id');
			} else {
				$params['homestay_create_at'] = date('Y-m-d H:i:s');
			}

			$params['homestay_name'] = $this->input->post('homestay_name');
			$params['homestay_desc'] = $this->input->post('homestay_desc');
			$params['homestay_price'] = $this->input->post('homestay_price');
			$params['homestay_longitude'] = $this->input->post('homestay_longitude');
			$params['homestay_latitude'] = $this->input->post('homestay_latitude');
			$params['homestay_user_id'] = $this->input->post('homestay_user_id');
			$params['homestay_update_at'] = date('Y-m-d H:i:s');

			$status = $this->Homestay_model->add($params);

			if ($status) {
				$this->session->set_flashdata('success', $data['operation'] . ' Pengeluaran berhasil');
				redirect('manage/homestay');
			}
		} else {
			if ($this->input->post('homestay_id')) {
				redirect('manage/homestay/edit/' . $this->input->post('homestay_id'));
			}

			if ($this->session->userdata('uroleid') != 9) {
				$data['users'] = $this->db->query("SELECT user_id, user_full_name
												FROM users
												WHERE user_role_role_id = '9'")->result_array();
			}

			// Edit mode
			if (!is_null($id)) {
				$data['homestay'] = $this->Homestay_model->get(array('id' => $id));
			}
			$data['title'] = $data['operation'] . ' Penginapan';
			$data['main'] = 'homestay/homestay_add';
			$this->load->view('manage/layout', $data);
		}
	}


	// Delete to database
	public function delete($id = NULL)
	{
		if ($this->session->userdata('uroleid') != 1) {
			redirect('manage');
		}
		if ($_POST) {
			$this->Homestay_model->delete($id);

			$this->session->set_flashdata('success', 'Hapus Penginapan berhasil');
			redirect('manage/homestay');
		} elseif (!$_POST) {
			$this->session->set_flashdata('delete', 'Delete');
			redirect('manage/homestay/edit/' . $id);
		}
	}

	public function gallery($id = NULL)
	{
		if (empty($id)) {
			redirect('manage/homestay');
		}

		$f = $this->input->get(NULL, TRUE);

		$data['f'] = $f;

		$params = array();

		$homestay = $this->db->get_where(
			'homestay',
			array(
				'homestay_id' => $id
			)
		)->row_array();

		$params['homestay_id'] = $id;

		$data['gallery'] = $this->Homestay_model->get_gallery($params);
		$data['homestay_id'] = $id;
		$data['title'] = 'Galeri Penginapan ' . $homestay['homestay_name'];
		$data['main'] = 'homestay/homestay_gallery';
		$this->load->view('manage/layout', $data);
	}


	public function upload_gallery()
	{
		$config['upload_path'] = FCPATH . 'uploads/gallery/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = 1024;
		$this->upload->initialize($config);

		foreach ($_FILES['gallery_img']['name'] as $key => $value) {
			$_FILES['img']['name'] = $_FILES['gallery_img']['name'][$key];
			$_FILES['img']['type'] = $_FILES['gallery_img']['type'][$key];
			$_FILES['img']['tmp_name'] = $_FILES['gallery_img']['tmp_name'][$key];
			$_FILES['img']['error'] = $_FILES['gallery_img']['error'][$key];
			$_FILES['img']['size'] = $_FILES['gallery_img']['size'][$key];

			if ($this->upload->do_upload('img')) {
				$data = $this->upload->data();
				$params = array(
					'foto' => $data['file_name'],
					'homestay_id' => $this->input->post('homestay_id')
				);

				$this->Homestay_model->insert_gallery($params);
				$this->session->set_flashdata('success', 'Tambah Gallery/Foto Berhasil');
			} else {
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('failed', 'Tambah Gallery/Foto Gagal');
			}
		}
		redirect('manage/homestay/gallery/' . $this->input->post('homestay_id'));
	}

	public function delete_gallery($id = NULL)
	{
		if ($this->session->userdata('uroleid') != 1) {
			redirect('manage');
		}
		if ($_POST) {
			$this->Homestay_model->delete_gallery($id);
			$this->session->set_flashdata('success', 'Hapus Gambar/Foto Penginapan berhasil');
		} elseif (!$_POST) {
			$this->session->set_flashdata('delete', 'Delete');
		}
		redirect('manage/homestay/gallery/' . $this->input->post('homestay_id'));
	}

	public function report()
	{


		$params = array();

		if (isset($_GET['start']) && !empty($_GET['start']) && $_GET['start'] != '') {
			$params['start'] = $_GET['start'];
		}

		if (isset($_GET['end']) && !empty($_GET['end']) && $_GET['end'] != '') {
			$params['end'] = $_GET['end'];
		}

		if ($this->session->userdata('uroleid') == 9) {
			$params['user_id'] = $this->session->userdata('uid');
		}

		$data['reservasi'] = $this->Homestay_model->get_reservasi($params);

		$data['title'] = 'Laporan Penginapan';
		$data['main'] = 'homestay/homestay_report';
		$this->load->view('manage/layout', $data);
	}
}

/* End of file Jurnal_set.php */
/* Location: ./application/modules/jurnal/controllers/Jurnal_set.php */