<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_employees extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_employees') == NULL) {
            header("Location:" . site_url('employees/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('employees/employees_model', 'bulan/Bulan_model', 'setting/Setting_model','bebas/Bebas_model', 'information/Information_model', 'bebas/Bebas_pay_model'));
    }

    public function index() {
        $id = $this->session->userdata('uid'); 

        $data['information'] = $this->Information_model->get(array('information_publish'=>1));
        
        $data['title'] = 'Dashboard';
        $data['main'] = 'dashboard/dashboard_employees';
        $this->load->view('employees/layout', $data);
    }


}
