<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PasswordReminderHook
{
    public function check_reminder()
    {
        $CI = &get_instance();
        $CI->load->library('session');

        // Default: no reminder
        $CI->password_reminder_info = [
            'show' => false,
            'ui'   => ''
        ];

        if (!$CI->session->userdata('logged')) {
            return;
        }

        $user_id = $CI->session->userdata('uid');
        $user_email = $CI->session->userdata('uemail');

        // Exclude specific users
        $excluded_emails = ['ts@indoweb.id', 'super@indoweb.id'];
        if (in_array($user_email, $excluded_emails)) {
            return;
        }

        // Exclude specific routes
        $controller = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();
        $excluded_routes = ['auth/logout', 'profile/cpw', 'profile/dismiss_password_reminder', 'api'];
        $current_route = $controller . '/' . $method;
        foreach ($excluded_routes as $route) {
            if (strpos($current_route, $route) !== false) {
                return;
            }
        }

        // Check if dismissed in this session
        if ($CI->session->userdata('password_reminder_dismissed_' . $user_id)) {
            return;
        }

        // Determine date to check
        $last_update = $CI->session->userdata('user_password_last_update');
        $input_date = $CI->session->userdata('user_input_date');

        $date_to_check = $last_update;
        if (empty($date_to_check) || $date_to_check == '0000-00-00 00:00:00') {
            $date_to_check = $input_date;
        }

        if (empty($date_to_check) || $date_to_check == '0000-00-00 00:00:00') {
            return;
        }

        $last_update_time = strtotime($date_to_check);
        $four_months_ago = strtotime('-4 months');

        if ($last_update_time < $four_months_ago) {
            $CI->password_reminder_info = [
                'show' => true,
                'ui'   => $CI->load->view('passwordReminder', [], true)
            ];
        }
    }
}
