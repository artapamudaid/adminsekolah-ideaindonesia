<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employees/Employees_model');
        $this->load->model('setting/Setting_model');
        // $this->load->model('information/Information_model');
        $this->load->library('form_validation');
        $this->load->helper('string');
    }

    function index()
    {
        redirect('guru/guru_auth/login');
    }

    function login()
    {
        if ($this->session->userdata('logged_guru')) {
            redirect('guru');
        }
        if ($this->input->post('location')) {
            $location = $this->input->post('location');
        } else {
            $location = NULL;
        }

        $url_pengumuman = 'https://api.epesantren.co.id/api/pengumuman-web-as?token=e6df5137d38410fbd27ab211d56a16c3';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_pengumuman);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $pengumuman = json_decode($response, true);
        $data['pengumuman'] = $pengumuman['data'] ?? [];

        $this->form_validation->set_rules('nip', 'NIP', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($_POST and $this->form_validation->run() == TRUE) {
            $nip = $this->input->post('nip', TRUE);
            $password = $this->input->post('password', TRUE);

            $employees = $this->Employees_model->get_employee(array('nip' => $nip, 'password' => md5($password)));
            // pre($employees);
            // die;
            // echo count($employees);
            // exit;
            if (count($employees) > 0) {
                $this->session->set_userdata('logged_guru', TRUE);
                $this->session->set_userdata('uid_guru', $employees[0]['employee_id']);
                $this->session->set_userdata('unip_guru', $employees[0]['employee_nip']);
                $this->session->set_userdata('ufullname_guru', $employees[0]['employee_name']);
                $this->session->set_userdata('uiswali_guru', $employees[0]['employee_is_wali']);
                $this->session->set_userdata('umajors_guru', $employees[0]['employee_majors_id']);
                if ($location != '') {
                    header("Location:" . htmlspecialchars($location));
                } else {
                    redirect('guru');
                }
            } else {
                if ($location != '') {
                    $this->session->set_flashdata('failed', 'Maaf, NIP dan password tidak cocok!');
                    header("Location:" . site_url('guru/auth/login') . "?location=" . urlencode($location));
                } else {
                    $this->session->set_flashdata('failed', 'Maaf, NIP dan password tidak cocok!');
                    redirect('guru/auth/login');
                }
            }
        } else {
            $data['setting_school'] = $this->Setting_model->get(array('id' => 1));
            $data['setting_logo'] = $this->Setting_model->get(array('id' => SCHOOL_LOGO));
            $this->load->view('guru/login', $data);
        }
    }

    // Logout Processing
    function logout()
    {
        $this->session->unset_userdata('logged_guru');
        $this->session->unset_userdata('uid_guru');
        $this->session->unset_userdata('unis_guru');
        $this->session->unset_userdata('ufullname_guru');

        $q = $this->input->get(NULL, TRUE);
        if ($q['location'] != NULL) {
            $location = $q['location'];
        } else {
            $location = NULL;
        }
        redirect('guru/auth/login');
    }
}
