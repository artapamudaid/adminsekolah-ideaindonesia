<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users/Users_model');
        $this->load->model('setting/Setting_model');
        $this->load->library('form_validation');
        $this->load->helper('string');
    }

    function index()
    {
        redirect('manage/auth/login');
    }

    function login()
    {
        if ($this->session->userdata('logged')) {
            redirect('manage');
        }
        if ($this->input->post('location')) {
            $location = $this->input->post('location');
        } else {
            $location = NULL;
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($_POST and $this->form_validation->run() == TRUE) {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);

            $user = $this->Users_model->get_login(array('email' => $email, 'password' => md5($password)));

            $aktivasi = $this->Setting_model->get(array('name' => 'setting_aktivasi'));

            if ($aktivasi['setting_value'] == 'N' and $user[0]['user_role_role_id'] != '3') {
                redirect('suspended');
            }

            $setting_end = $this->Setting_model->get(array('name' => 'setting_end'));
            if ($setting_end) {
                $setting_end_value = $setting_end['setting_value'];
            } else {
                $setting_end_value = date('Y-m-d', strtotime('+1 day'));
            }

            $secret_key = $this->config->item('turnstile_secret_key');
            $token      = $_POST['cf-turnstile-response'] ?? '';
            $remoteip   = $_SERVER['HTTP_CF_CONNECTING_IP'] ??
                $_SERVER['HTTP_X_FORWARDED_FOR'] ??
                $_SERVER['REMOTE_ADDR'];

            $turnstile = $this->config->item('turnstile');
            $validation['success'] = FALSE;
            if ($turnstile) {
                $turnstileValidation = $this->validateTurnstile($token, $secret_key, $remoteip);
                $validation['success'] = $turnstileValidation['success'];
            } else {
                $validation['success'] = TRUE;
            }

            if ($validation['success']) {
                if (count($user) > 0) {
                    $this->session->set_userdata('logged', TRUE);
                    $this->session->set_userdata('uid', $user[0]['user_id']);
                    $this->session->set_userdata('uemail', $user[0]['user_email']);
                    $this->session->set_userdata('ufullname', $user[0]['user_full_name']);
                    $this->session->set_userdata('uroleid', $user[0]['user_role_role_id']);
                    $user_majors_id = (int)$user[0]['user_majors_id'];
                    $this->session->set_userdata('umajorsid', $user_majors_id);
                    $this->session->set_userdata('umajorsname', $user[0]['majors_name']);
                    $this->session->set_userdata('urolename', $user[0]['role_name']);
                    $this->session->set_userdata('user_image', $user[0]['user_image']);
                    $this->session->set_userdata('suspend_date', $setting_end_value);
                    $this->session->set_userdata('user_password_last_update', $user[0]['user_password_last_update']);
                    $this->session->set_userdata('user_input_date', $user[0]['user_input_date']);
                    if ($location != '') {
                        header("Location:" . htmlspecialchars($location));
                    } else {
                        redirect('manage');
                    }
                } else {
                    $this->session->set_flashdata('failed', 'Maaf, username atau password Anda salah');
                    if ($location != '') {
                        redirect(site_url('manage/auth/login?location=' . urlencode($location)));
                    } else {
                        redirect('manage/auth/login');
                    }
                }
            } else {
                $this->session->set_flashdata('failed', 'Captcha verification failed. Please try again.');
                redirect(site_url('manage/auth/login?location=' . urlencode($location)));
            }


            // NOTE: Change @22072026
            // $secret_key = $this->config->item('turnstile_secret_key');
            // $token = $_POST['cf-turnstile-response'] ?? '';
            // $remoteip = $_SERVER['HTTP_CF_CONNECTING_IP'] ??
            //     $_SERVER['HTTP_X_FORWARDED_FOR'] ??
            //     $_SERVER['REMOTE_ADDR'];

            // $validation = $this->validateTurnstile($token, $secret_key, $remoteip);

            // if ($validation['success']) {

            //     $email = $this->input->post('email', TRUE);
            //     $password = $this->input->post('password', TRUE);

            //     $user = $this->Users_model->get_login(array('email' => $email, 'password' => md5($password)));

            //     if (count($user) > 0) {
            //         $this->session->set_userdata('logged', TRUE);
            //         $this->session->set_userdata('uid', $user[0]['user_id']);
            //         $this->session->set_userdata('uemail', $user[0]['user_email']);
            //         $this->session->set_userdata('ufullname', $user[0]['user_full_name']);
            //         $this->session->set_userdata('uroleid', $user[0]['user_role_role_id']);
            //         $this->session->set_userdata('umajorsid', $user[0]['user_majors_id']);
            //         $this->session->set_userdata('umajorsname', $user[0]['majors_name']);
            //         $this->session->set_userdata('urolename', $user[0]['role_name']);
            //         $this->session->set_userdata('user_image', $user[0]['user_image']);
            //         if ($location != '') {
            //             header("Location:" . htmlspecialchars($location));
            //         } else {
            //             redirect('manage');
            //         }
            //     } else {

            //         $this->session->set_flashdata('failed', 'Maaf, username atau password Anda salah');
            //         if ($location != '') {
            //             header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($location));
            //         } else {
            //             redirect('manage/auth/login');
            //         }
            //     }
            // } else {
            //     $this->session->set_flashdata('failed', 'Captcha verification failed. Please try again.');
            //     header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode((string)$location));
            // }
        } else {

            $data['setting_school'] = $this->Setting_model->get(array('id' => 1));
            $data['setting_logo'] = $this->Setting_model->get(array('id' => SCHOOL_LOGO));

            // NOTE: Check RFID Subscription
            $data['is_rfid_subscription'] = table_exists_cached('cards');
            $data['is_rfid_subscription_employee'] = table_exists_cached('employee_cards');

            $url_pengumuman = 'https://api.epesantren.co.id/api/pengumuman-web-as?token=e6df5137d38410fbd27ab211d56a16c3';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_pengumuman);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            $pengumuman = json_decode($response, true);
            $data['pengumuman'] = $pengumuman['data'] ?? [];

            $data['turnstile_site_key'] = $this->config->item('turnstile_site_key');

            $this->load->view('manage/login', $data);
        }
    }

    private function validateTurnstile($token, $secret, $remoteip = null)
    {
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        $data = [
            'secret' => $secret,
            'response' => $token
        ];

        if ($remoteip) {
            $data['remoteip'] = $remoteip;
        }

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === FALSE) {
            return ['success' => false, 'error-codes' => ['internal-error']];
        }

        return json_decode($response, true);
    }

    // Logout Processing
    function logout()
    {
        // Reset password reminder dismiss flag so it appears again on next login
        $uid = $this->session->userdata('uid');
        if ($uid) {
            $this->session->unset_userdata('password_reminder_dismissed_' . $uid);
        }

        $this->session->unset_userdata('logged');
        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('uemail');
        $this->session->unset_userdata('ufullname');
        $this->session->unset_userdata('uroleid');
        $this->session->unset_userdata('umajorsid');
        $this->session->unset_userdata('umajorsname');
        $this->session->unset_userdata('urolename');
        $this->session->unset_userdata('user_image');
        $this->session->unset_userdata('suspend_date');
        $this->session->unset_userdata('user_password_last_update');
        $this->session->unset_userdata('user_input_date');

        $q = $this->input->get(NULL, TRUE);
        if ($q && isset($q['location']) && $q['location'] != NULL) {
            $location = $q['location'];
        } else {
            $location = NULL;
        }
        redirect('manage/auth/login');
    }
}
