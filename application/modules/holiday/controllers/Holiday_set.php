<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Holiday_set extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('logged') == NULL) {
			redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
		}

		$this->load->model('holiday/Holiday_model');
		$this->load->helper(array('form', 'url'));
	}

	public function index($offset = NULL)
	{
		$this->load->library('pagination');
		// Apply Filter
		// Get $_GET variable
		$f = $this->input->get(NULL, TRUE);

		$data['f'] = $f;

		$params = array();
		// Nip
		if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
			$params['class_name'] = $f['n'];
		}

		$paramsPage = $params;
		$params['limit'] = 10;
		$params['offset'] = $offset;
		$data['holiday'] = $this->Holiday_model->get($params);

		//   echo $this->db->last_query();
		//   exit;

		$config['per_page'] = 10;
		$config['uri_segment'] = 4;
		$config['base_url'] = site_url('manage/holiday/index');
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['total_rows'] = count($this->Holiday_model->get($paramsPage));
		$this->pagination->initialize($config);

		$data['offset'] = (int) $offset;
		$data['title'] = 'Hari Libur';
		$data['main'] = 'holiday/holiday_list';
		$this->load->view('manage/layout', $data);
	}

	public function add_glob()
	{
		if ($_POST == TRUE) {
			$input = $_POST['date'];
			$date = explode(',', $input);
			$cpt = count($date);

			$added = 0;
			$skipped = 0;
			for ($i = 0; $i < $cpt; $i++) {
				if (empty($date[$i])) {
					$skipped++;
					continue;
				}

				$colWhere = ['date' => $date[$i]];
				if ($this->General_model->checkDataExists('holiday', $colWhere)) {
					$skipped++;
					continue;
				}

				$params['year'] = pretty_date($date[$i], 'Y', false);
				$params['date'] = $date[$i];
				$params['info'] = 'Hari Libur';

				$insert = $this->Holiday_model->add($params);
				if (!$insert) {
					$skipped++;
					continue;
				}

				$added++;
			}

			if ($added > 1) {
				$msg = "$added data berhasil ditambahkan";
			} elseif ($added == 1) {
				$msg = "1 data berhasil ditambahkan";
			} else {
				$msg = "Tidak ada yang ditambahkan";
			}

			if ($skipped > 0) {
				$msg .= "<br>($skipped data dilewati)";
			}

			$this->session->set_flashdata('success', $msg);
		}
		redirect('manage/holiday');
	}

	// Add User_customer and Update
	public function add($id = NULL)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('holiday_name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
		$data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

		if ($_POST and $this->form_validation->run() == TRUE) {

			if ($this->input->post('holiday_id')) {
				$params['holiday_id'] = $this->input->post('holiday_id');
			}
			$params['holiday_name'] = $this->input->post('holiday_name');
			$status = $this->Holiday_model->add_holiday($params);


			$this->session->set_flashdata('success', $data['operation'] . ' Hari Libur');
			redirect('manage/holiday');

			if ($this->input->post('from_angular')) {
				echo $status;
			}
		} else {
			if ($this->input->post('holiday_id')) {
				redirect('manage/holiday/edit/' . $this->input->post('holiday_id'));
			}

			// Edit mode
			if (!is_null($id)) {
				$object = $this->Holiday_model->get(array('id' => $id));
				if ($object == NULL) {
					redirect('manage/holiday');
				} else {
					$data['holiday'] = $object;
				}
			}
			$data['title'] = $data['operation'] . ' Hari Libur';
			$data['main'] = 'holiday/holiday_add';
			$this->load->view('manage/layout', $data);
		}
	}

	public function delete($id = NULL)
	{

		if ($_POST) {

			$this->Holiday_model->delete($id);

			$this->load->model('logs/Logs_model');
			$this->Logs_model->add(
				array(
					'log_date' => date('Y-m-d H:i:s'),
					'user_id' => $this->session->userdata('uid'),
					'log_module' => 'user',
					'log_action' => 'Hapus',
					'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
				)
			);
			$this->session->set_flashdata('success', 'Hapus Hari Libur berhasil');
			redirect('manage/holiday');
		} elseif (!$_POST) {
			$this->session->set_flashdata('delete', 'Delete');
			redirect('manage/holiday');
		}
	}

	public function upload()
	{
		if ($_POST) {
			$rows = explode("\n", $this->input->post('rows'));
			$success = 0;
			$failled = 0;
			$exist = 0;
			$date = '';
			foreach ($rows as $row) {
				$exp = explode("\t", $row);
				if (count($exp) != 3) continue;
				$date = trim($exp[1]);
				$arr = [
					'year' => trim($exp[0]),
					'date' => trim($exp[1]),
					'info' => trim($exp[2]),
				];

				$check = $this->db
					->where('date', trim($exp[1]))
					->count_all_results('holiday');
				if ($check == 0) {
					if ($this->db->insert('holiday', $arr)) {
						$success++;
					} else {
						$failled++;
					}
				} else {
					$exist++;
				}
			}
			$msg = 'Sukses : ' . $success . ' baris, Gagal : ' . $failled . ', Duplikat : ' . $exist;
			$this->session->set_flashdata('success', $msg);
			redirect('manage/holiday/import');
		} else {
			$data['title'] = 'Import Data Hari Libur';
			$data['main'] = 'holiday/holiday_upload';
			$this->load->view('manage/layout', $data);
		}
	}

	public function download()
	{
		$data = file_get_contents("./media/template_excel/template_libur_nasional.xls");
		$name = 'template_libur_nasional.xls';
		$this->load->helper('download');
		force_download($name, $data);
	}
}

/* End of file Holiday_set.php */
/* Location: ./application/modules/holiday/controllers/Holiday_set.php */