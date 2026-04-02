<?php

class SuspendInfoHook
{
    public function check()
    {
        $CI = &get_instance();

        if (!$CI->session->userdata('logged')) {
            return;
        }

        // $suspend_date = "2026-03-03";
        $suspend_date = $CI->session->userdata('suspend_date');
        $today = date("Y-m-d");

        $diff = (strtotime($suspend_date) - strtotime($today)) / 86400;

        $bg_color = '';
        $title_text = '';
        if ($diff <= 7 && $diff > 0) {
            $bg_color = 'callout-danger';
            $title_text = 'Penting!';
        } elseif ($diff <= 14 && $diff > 7) {
            $bg_color = 'callout-warning';
            $title_text = 'Perhatian!';
        } elseif ($diff <= 30 && $diff > 14) {
            $bg_color = 'callout-info';
            $title_text = 'Informasi!';
        }

        // $show_info = true;
        // $show_info = ($diff <= 30 && $diff > 0);
        $show_info = ($diff == 30 ||
            $diff == 21 ||
            // $diff == 14 ||
            // ($diff <= 7 && $diff > 0)
            ($diff <= 15 && $diff > 0)
        );

        if ($diff < 0) {
            $CI->load->database();

            $CI->db->where('setting_name', 'setting_app_status');
            $CI->db->update('setting', ['setting_value' => 'N']);

            // redirect('block');
            // exit();
        }

        $CI->suspend_info = [
            'show'          => $show_info,
            'suspend_date'  => $suspend_date,
            'days_left'     => ceil($diff),
            'bg_color'      => $bg_color,
            'ui'            => $show_info
                ? $CI->load->view('suspendInfo', [
                    'suspend_date' => $suspend_date,
                    'days_left'    => ceil($diff),
                    'bg_color'     => $bg_color,
                    'title_text' => $title_text
                ], true)
                : ""
        ];
    }
}
