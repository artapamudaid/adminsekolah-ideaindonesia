<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_employees extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('employees/Employees_model');
        $this->load->model('setting/Setting_model');
        $this->load->library('form_validation');
        $this->load->helper('string');
    }

    function index() {
        redirect('employees/auth/login');
    }

    function login() {
        if ($this->session->userdata('logged_employees')) {
            redirect('employees');
        }
        if ($this->input->post('location')) {
            $location = $this->input->post('location');
        } else {
            $location = NULL;
        }
        
        $this->form_validation->set_rules('nip', 'NIP', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($_POST AND $this->form_validation->run() == TRUE) {
            $nip = $this->input->post('nip', TRUE);
            $password = $this->input->post('password', TRUE);

            $employees = $this->Employees_model->get_employee(array('nip' => $nip, 'password' => md5($password)));

            if (count($employees) > 0) {
                $this->session->set_userdata('logged_employees', TRUE);
                $this->session->set_userdata('uid_employees', $employees[0]['employee_id']);
                $this->session->set_userdata('unip_employees', $employees[0]['employee_nip']);
                $this->session->set_userdata('ufullname_employees', $employees[0]['employee_name']);
                $this->session->set_userdata('employees_img', $employees[0]['employees_photo']);
                if ($location != '') {
                    header("Location:" . htmlspecialchars($location));
                } else {
                    redirect('employees');
                }
            } else {
                if ($location != '') {
                    $this->session->set_flashdata('failed', 'Maaf, NIP dan password tidak cocok!');
                    header("Location:" . site_url('employees/auth/login') . "?location=" . urlencode($location));
                } else {
                    $this->session->set_flashdata('failed', 'Maaf, NIP dan password tidak cocok!');
                    redirect('employees/auth/login');
                }
            }
        } else {
            $data['setting_school'] = $this->Setting_model->get(array('id'=>1));
            $data['setting_logo'] = $this->Setting_model->get(array('id'=>SCHOOL_LOGO));
            $this->load->view('employees/login', $data);
        }
    }

    // Logout Processing
    function logout() {
        $this->session->unset_userdata('logged_employees');
        $this->session->unset_userdata('uid_employees');
        $this->session->unset_userdata('unis_employees');
        $this->session->unset_userdata('ufullname_employees');
        $this->session->unset_userdata('employees_img');

        $q = $this->input->get(NULL, TRUE);
        if ($q['location'] != NULL) {
            $location = $q['location'];
        } else {
            $location = NULL;
        }
        redirect('employees/auth/login');
    }

}
