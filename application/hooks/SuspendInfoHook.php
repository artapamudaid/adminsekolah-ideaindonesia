<?php

class SuspendInfoHook
{
    public function check()
    {
        $CI = &get_instance();

        if (!$CI->session->userdata('logged')) {
            return;
        }

        // $suspend_date = "2026-10-30"; // hardcoded for testing purposes
        $suspend_date = $CI->session->userdata('suspend_date');
        $today = date("Y-m-d");

        $diff = (strtotime($suspend_date) - strtotime($today)) / 86400;
        $days_left = ceil($diff);

        $bg_color = 'bg-gray-active';
        $title_text = 'Salam!';
        $message = "Status berlangganan aplikasi Anda masih aktif dan akan berakhir pada <b>" . pretty_date($suspend_date, 'l, d F Y', false) . "</b> "
            . "(sekitar {$days_left} hari lagi). Tidak ada tindakan yang perlu dilakukan saat ini.";

        if ($diff <= 7 && $diff > 0) {
            $bg_color = 'callout-danger';
            $title_text = 'Penting!';
            $message = "Status berlangganan aplikasi Anda akan segera <strong>habis dalam {$days_left} hari</strong>. "
                . "Mohon segera lakukan pembayaran sebelum jatuh tempo pada <b>" . pretty_date($suspend_date, 'l, d F Y', false) . "</b> "
                . "agar layanan tidak terganggu.";
        } elseif ($diff <= 14 && $diff > 7) {
            $bg_color = 'callout-warning';
            $title_text = 'Perhatian!';
            $message = "Masa berlangganan aplikasi Anda tersisa <strong>{$days_left} hari</strong> lagi. "
                . "Silakan siapkan pembayaran sebelum tanggal jatuh tempo pada <b>" . pretty_date($suspend_date, 'l, d F Y', false) . "</b> "
                . "untuk menghindari keterlambatan.";
        } elseif ($diff <= 30 && $diff > 14) {
            $bg_color = 'callout-info';
            $title_text = 'Informasi!';
            $message = "Sebagai pengingat, masa berlangganan aplikasi Anda akan berakhir dalam <strong>{$days_left} hari</strong>, "
                . "tepatnya pada <b>" . pretty_date($suspend_date, 'l, d F Y', false) . "</b>. "
                . "Anda dapat melakukan pembayaran kapan saja sebelum tanggal tersebut.";
        }
        // elseif ($diff <= 365 && $diff > 30) {
        //     $bg_color = 'bg-gray-active';
        //     $title_text = 'Salam!';
        //     $message = "Status berlangganan aplikasi Anda masih aktif dan akan berakhir pada <b>" . pretty_date($suspend_date, 'l, d F Y', false) . "</b> "
        //         . "(sekitar {$days_left} hari lagi). Tidak ada tindakan yang perlu dilakukan saat ini.";
        // }

        $show_info = true;
        // $show_info = ($diff <= 30 && $diff > 0);
        // $show_info = ($diff == 30 ||
        //     $diff == 21 ||
        //     // $diff == 14 ||
        //     // ($diff <= 7 && $diff > 0)
        //     ($diff <= 15 && $diff > 0)
        // );

        if ($diff < 0) {
            $CI->load->database();

            $CI->db->where('setting_name', 'setting_app_status');
            $CI->db->update('setting', ['setting_value' => 'N']);

            // redirect('block');
            // exit();
        }

        $alert_class = str_replace('callout-', 'alert-', $bg_color);
        if (strpos($alert_class, 'alert-') === false && strpos($alert_class, 'bg-') === false) {
            $alert_class = 'alert alert-info';
        } elseif (strpos($alert_class, 'alert-') === false) {
            $alert_class = 'alert ' . $alert_class;
        } else {
            $alert_class = 'alert ' . $alert_class;
        }

        $icon = 'fa-info-circle';
        if (strpos($alert_class, 'danger') !== false) {
            $icon = 'fa-ban';
        } elseif (strpos($alert_class, 'warning') !== false) {
            $icon = 'fa-warning';
        } elseif (strpos($alert_class, 'success') !== false) {
            $icon = 'fa-check';
        }


        $CI->suspend_info = [
            'show'          => $show_info,
            'suspend_date'  => $suspend_date,
            'days_left'     => $days_left,
            'bg_color'      => $bg_color,
            'alert_class'   => $alert_class,
            'icon'          => $icon,
            'ui'            => $show_info
                ? $CI->load->view('suspendInfo', [
                    'suspend_date' => $suspend_date,
                    'days_left'    => $days_left,
                    'bg_color'     => $bg_color,
                    'alert_class'  => $alert_class,
                    'icon'         => $icon,
                    'title_text'   => $title_text,
                    'message'      => $message
                ], true)
                : ""
        ];
    }
}
