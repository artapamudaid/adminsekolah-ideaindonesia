<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa_set extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }

        $this->load->model(array('coa/Coa_model', 'logs/Logs_model', 'setting/Setting_model'));
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $f = $this->input->get(NULL, TRUE);
        $s = $this->input->get(NULL, TRUE);

        $data['f'] = $f;
        $data['s'] = $s;

        $params = array();
        
        // Filter by Majors (Unit)
        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['coa_majors'] = $s['m'];
        }

        $data['coa'] = $this->Coa_model->get($params);
        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
        $data['majors'] = $this->Coa_model->get_majors();
        $data['groups'] = $this->Coa_model->get_groups();

        $data['title'] = 'Chart of Accounts (COA)';
        $data['main'] = 'coa/coa_list';
        $this->load->view('manage/layout', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('group_id', 'Group', 'trim|required|xss_clean');
        $this->form_validation->set_rules('coa_majors', 'Unit (Majors)', 'trim|required|xss_clean');
        $this->form_validation->set_rules('coa_name', 'Nama Akun', 'trim|required|xss_clean');
        $this->form_validation->set_rules('coa_type', 'Tipe Akun', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');

        if ($_POST && $this->form_validation->run() == TRUE) {
            $params = array();
            
            if ($this->input->post('coa_id')) {
                $params['coa_id'] = $this->input->post('coa_id');
                $params['is_active'] = $this->input->post('is_active') ? 1 : 0;
                $action = 'Sunting';
            } else {
                $params['is_active'] = 1; 
                $params['coa_category'] = 'DETAIL'; // Default child/new is DETAIL
                $action = 'Tambah';
            }

            $params['group_id'] = $this->input->post('group_id');
            $params['coa_majors'] = $this->input->post('coa_majors');
            $params['parent_id'] = $this->input->post('parent_id') ?: 0;
            
            // Re-generate code
            if (!$this->input->post('coa_id')) {
                $params['coa_code'] = $this->Coa_model->generate_code($params['group_id'], $params['coa_majors'], $params['parent_id']);
            }

            $params['coa_name'] = $this->input->post('coa_name');
            $params['coa_type'] = $this->input->post('coa_type');

            $status = $this->Coa_model->add($params);

            // Activity Log
            $log_info = 'Title:' . $params['coa_name'] . ';Code:' . (isset($params['coa_code']) ? $params['coa_code'] : $this->input->post('coa_code_readonly'));
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Chart of Accounts',
                    'log_action' => $action,
                    'log_info' => 'ID:' . ($this->input->post('coa_id') ?: $status) . ';' . $log_info
                )
            );

            $this->session->set_flashdata('success', $action . ' COA berhasil');
            redirect('manage/coa' . (isset($_POST['m_redirect']) ? '?m=' . $_POST['m_redirect'] : ''));
        } else {
            // Validation errors
            $this->session->set_flashdata('failed', validation_errors());
            redirect('manage/coa');
        }
    }

    public function delete($id = NULL)
    {
        if ($this->session->userdata('uroleid') != SUPERUSER) {
            redirect('manage');
        }

        if ($_POST && $id != NULL) {
            
            $del = $this->Coa_model->delete($id);
            if (!$del) {
                $this->session->set_flashdata('failed', 'COA tidak dapat dihapus karena memiliki sub-akun (child).');
                redirect('manage/coa' . (isset($_POST['m_redirect']) ? '?m=' . $_POST['m_redirect'] : ''));
            }
            
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'Chart of Accounts',
                    'log_action' => 'Hapus',
                    'log_info' => 'ID:' . $id
                )
            );

            $this->session->set_flashdata('success', 'Hapus Akun COA berhasil');
            redirect('manage/coa' . (isset($_POST['m_redirect']) ? '?m=' . $_POST['m_redirect'] : ''));
        } else {
            redirect('manage/coa');
        }
    }

    // AJAX Endpoint
    public function get_proposed_code()
    {
        $group_id = $this->input->post('group_id');
        $majors_id = $this->input->post('majors_id');
        $parent_id = $this->input->post('parent_id') ?: 0;

        if ($group_id && $majors_id) {
            $next_code = $this->Coa_model->generate_code($group_id, $majors_id, $parent_id);
            echo json_encode(['status' => 'success', 'code' => $next_code]);
        } else {
            echo json_encode(['status' => 'error', 'code' => '']);
        }
        exit;
    }

}
