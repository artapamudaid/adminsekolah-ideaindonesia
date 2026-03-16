<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Change_language_set extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function en()
    {
        $this->session->set_userdata('site_lang', 'english');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function id()
    {
        $this->session->set_userdata('site_lang', 'indonesia');
        redirect($_SERVER['HTTP_REFERER']);
    }
}
