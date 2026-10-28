<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_akuntansi_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model('setting_akuntansi/Setting_akuntansi_model', 'setting_akuntansi_model');
    }

    public function index()
    {
        $data['akrual_setting'] = $this->setting_akuntansi_model->get_akrual_setting();
        $data['title'] = 'Pengaturan Akuntansi';
        $data['main'] = 'setting_akuntansi/setting_akuntansi_list';
        $this->load->view('manage/layout', $data);
    }

    public function save()
    {
        if ($_POST) {
            $mode = $this->input->post('mode', TRUE);
            $frequency = $this->input->post('frequency', TRUE);
            $run_time = $this->input->post('run_time', TRUE);

            $data = array(
                'mode' => $mode
            );

            if ($mode == 'auto') {
                $data['frequency'] = $frequency;
                $data['run_time'] = $run_time;

                if ($frequency == 'weekly') {
                    $data['run_day'] = $this->input->post('run_day', TRUE);
                    $data['run_date'] = NULL;
                } else if ($frequency == 'monthly') {
                    $data['run_day'] = NULL;
                    $data['run_date'] = $this->input->post('run_date', TRUE);
                } else { // daily
                    $data['run_day'] = NULL;
                    $data['run_date'] = NULL;
                }
            } else { // manual
                $data['frequency'] = 'daily';
                $data['run_time'] = '00:00:00';
                $data['run_day'] = NULL;
                $data['run_date'] = NULL;
            }

            $this->setting_akuntansi_model->update_akrual_setting($data);
            $this->session->set_flashdata('success', 'Pengaturan berhasil disimpan');
            redirect('manage/setting_akuntansi');
        } else {
            redirect('manage/setting_akuntansi');
        }
    }
}
