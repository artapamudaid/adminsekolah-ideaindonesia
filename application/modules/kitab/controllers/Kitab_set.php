<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kitab_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model('kitab/Kitab_model');
        $this->load->helper(array('form', 'url'));
    }

    // User_customer view in list
    public function index($offset = NULL)
    {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();

        $paramsPage = $params;
        // $params['limit'] = 10;
        $params['offset'] = $offset;
        $data['kitab'] = $this->Kitab_model->get($params);

        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $config['base_url'] = site_url('manage/kitab/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['total_rows'] = count($this->Kitab_model->get($paramsPage));
        $this->pagination->initialize($config);

        $data['title'] = 'Kitab';
        $data['main'] = 'kitab/kitab_list';
        $this->load->view('manage/layout', $data);
    }

    // Add User_customer and Update
    public function add($id = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kitab_name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST and $this->form_validation->run() == TRUE) {

            if ($this->input->post('kitab_id')) {
                $id = $this->input->post('kitab_id');
                $params['kitab_id'] = $id;
            }

            $kitabName = sanitizeStrict($this->input->post('kitab_name'));
            $colWhere = [
                "kitab_name" => $kitabName,
            ];
            if (!empty($id)) {
                $colWhere["kitab_id != "] = $id;
            }
            if ($this->General_model->checkDataExists("kitab", $colWhere)) {
                $this->session->set_flashdata('failed', ' Data sudah ada');
                $redirectUrl = (!empty($id)) ? "manage/kitab/edit/$id" : "manage/kitab";
                redirect($redirectUrl);
            }

            $params['kitab_name'] = $kitabName;
            $status = $this->Kitab_model->add($params);

            $this->session->set_flashdata('success', $data['operation'] . ' Kitab');
            redirect('manage/kitab');

            if ($this->input->post('from_angular')) {
                echo $status;
            }
        } else {
            if ($this->input->post('kitab_id')) {
                redirect('manage/kitab/edit/' . $this->input->post('kitab_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $object = $this->Kitab_model->get(array('id' => $id));
                if ($object == NULL) {
                    redirect('manage/kitab');
                } else {
                    $data['kitab'] = $object;
                }
            }
            $data['title'] = $data['operation'] . ' Kitab';
            $data['main'] = 'kitab/kitab_add';
            $this->load->view('manage/layout', $data);
        }
    }



    public function add_glob()
    {
        if ($_POST == TRUE) {
            $kitabName = sanitizeStrict($_POST['kitab_name']);

            $cpt = count($kitabName);

            $added = 0;
            $skipped = 0;
            for ($i = 0; $i < $cpt; $i++) {
                if (empty($kitabName[$i])) {
                    $skipped++;
                    continue;
                }

                $colWhere = [
                    "kitab_name" => $kitabName[$i],
                ];
                if ($this->General_model->checkDataExists("kitab", $colWhere)) {
                    $skipped++;
                    continue;
                }

                $params['kitab_name'] = $kitabName[$i];

                $insert = $this->Kitab_model->add($params);
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
        redirect('manage/kitab');
    }

    public function delete($id = NULL)
    {

        if ($_POST) {

            $this->Kitab_model->delete($id);

            $this->session->set_flashdata('success', 'Hapus Kitab berhasil');
            redirect('manage/kitab');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('manage/kitab/edit/' . $id);
        }
    }
}
