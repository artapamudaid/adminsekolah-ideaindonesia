<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->helper('send');


        $this->load->model(array('setting/Setting_model'));
    }

    public function index() {
        $key = $this->input->get('key');
        if (empty($key) || $key !== CRON_KEY) {
            show_error('Unauthorized access', 403);
            return;
        }
        exit;
    }

    public function email_register(){
        
        $key = $this->input->get('key');
        if (empty($key) || $key !== CRON_KEY) {
            show_error('Unauthorized access', 403);
            return;
        }

        $this->db->where('status', 'pending');
        $this->db->limit(10); // Limit to 10 emails at a time
        $this->db->order_by('created_at', 'ASC'); // Order by created
        $recepients = $this->db->get('scheduled_emails')->result();

        foreach ($recepients as $recipient) {
            $email = $recipient->recipient_email;
            

            $email_sent = $this->send_email($email, "Registrasi");

            $response = json_decode($email_sent, true);

            if ($response['status'] === 'success') {


                // Update status to sent
                $this->db->where('id', $recipient->id)->update('scheduled_emails', [
                    'status' => 'sent',
                    'send_at' => date('Y-m-d H:i:s')
                ]);

                $created_at = date('Y-m-d');

                $data = [
                    'email'         => $email,
                    'created_at'    => $created_at,
                    'follow_up_1'   => add_workdays($created_at, 3),
                    'follow_up_2'   => add_workdays(add_workdays($created_at, 3), 3),
                    'follow_up_3'   => add_workdays(add_workdays(add_workdays($created_at, 3), 3), 3),
                ];

                $this->db->insert('email_marketing', $data);
            }
        }
    }

    public function wa_register(){
        
        $key = $this->input->get('key');
        if (empty($key) || $key !== CRON_KEY) {
            show_error('Unauthorized access', 403);
            return;
        }

        $wa_key                 = $this->Setting_model->get(array('name' => 'setting_wa_key'));
        $setting_whatsapp       = $this->Setting_model->get(array('id' => 92));
        $secret_key                 = $wa_key['setting_value'];
        $sender             = $setting_whatsapp['setting_value'];

        $this->db->where('status', 'pending');
        $this->db->limit(10); // Limit to 10 numbers at a time
        $this->db->order_by('created_at', 'ASC'); // Order by created
        $recepients = $this->db->get('scheduled_wa')->result();

        foreach ($recepients as $recipient) {
            $receiver = $recipient->receiver;
            $msg = $recipient->msg;
            

            $wa_sent = send_whatsapp($receiver, $msg, $secret_key, $sender);

            $response = json_decode($wa_sent, true);

            $status = 'pending';

            if ($response['status'] === true) {
                $status = 'sent';
            } else {
                $status = 'failed';
            }

            $this->db->where('id', $recipient->id)->update('scheduled_wa', [
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        $email = DEVELOPER_EMAIL;

        $this->db->where('date', date('Y-m-d'));
        $failed_count = $this->db->count_all_results('wanotif_reports');
        if($failed_count >= 5){
            $this->send_email($email, "Warning Notif WA");
        }
    }

    public function follupf()
    {
        $key = $this->input->get('key');
        if (empty($key) || $key !== CRON_KEY) {
            show_error('Unauthorized access', 403);
            return;
        }

        $this->process_follow_up(1, '1');
    }

    public function follups()
    {
        $key = $this->input->get('key');
        if (empty($key) || $key !== CRON_KEY) {
            show_error('Unauthorized access', 403);
            return;
        }

        $this->process_follow_up(2, '2');
    }

    public function follupt()
    {
        $key = $this->input->get('key');
        if (empty($key) || $key !== CRON_KEY) {
            show_error('Unauthorized access', 403);
            return;
        }

        $this->process_follow_up(3, '3');
    }


    private function process_follow_up($n, $label)
    {
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');

        $follow_col = "follow_up_{$n}";
        $sent_col = "sent_{$n}";
        $status_col = "status_{$n}";

        // Kolom follow-up sebelumnya (jika bukan follow up 1)
        $prev_status_col = $n > 1 ? "status_" . ($n - 1) : null;

        // Build dynamic query untuk atomic "lock"
        $where = "{$follow_col} <= '{$today}' AND {$sent_col} IS NULL AND {$status_col} = '0'";
        if ($prev_status_col) {
            $where .= " AND {$prev_status_col} = '1'";
        }

        // Tandai max 10 data sebagai processing
        $this->db->query("
            UPDATE email_marketing 
            SET {$status_col} = 'processing', updated_at = ? 
            WHERE {$where}
            LIMIT 10
        ", [$now]);

        // Ambil data yang sedang processing
        $this->db->where($status_col, 'processing');
        $this->db->where("{$sent_col} IS NULL");
        if ($prev_status_col) {
            $this->db->where($prev_status_col, '1');
        }
        $query = $this->db->get('email_marketing')->result();
        log_message('info', "Found " . count($query) . " records for follow-up {$n} to process.");

        foreach ($query as $row) {
            log_message('info', "Processing follow-up {$n} for email: {$row->email}");
            
                log_message('info', "Today is a workday, proceeding with follow-up {$n} for email: {$row->email}");
               try {
                    $resEmail = $this->send_email($row->email, "Follow Up {$label}");
                    log_message('info', "Email sent for follow-up {$n} to: {$row->email}");

                    $message = json_decode($resEmail, true);
                    if ($message['status'] !== 'success') {
                        throw new Exception($message['message']);
                    }

                    // Update status berhasil
                    $this->db->where('id', $row->id)->update('email_marketing', [
                        $sent_col => $today,
                        $status_col => '1',
                        'updated_at' => $now,
                    ]);
                    log_message('info', "Follow-up {$n} for email: {$row->email} completed successfully.");

                    // Catat ke log (berhasil)
                    $this->db->insert('email_marketing_log', [
                        'email_marketing_id' => $row->id,
                        'follow_up_number' => $n,
                        'email' => $row->email,
                        'sent_at' => date('Y-m-d H:i:s'),
                        'status' => 'success',
                        'message' => null,
                    ]);
                    log_message('info', "Follow-up {$n} for email: {$row->email} completed successfully.");

                } catch (Exception $e) {
                    // Catat ke log (gagal)
                    $this->db->insert('email_marketing_log', [
                        'email_marketing_id' => $row->id,
                        'follow_up_number' => $n,
                        'email' => $row->email,
                        'sent_at' => date('Y-m-d H:i:s'),
                        'status' => 'fail',
                        'message' => $e->getMessage(),
                    ]);
                    log_message('error', "Follow-up {$n} for email: {$row->email} failed with error: " . $e->getMessage());

                    // Reset ke status_? = 0 agar bisa dicoba ulang nanti
                    $this->db->where('id', $row->id)->update('email_marketing', [
                        $status_col => '0',
                        'updated_at' => $now,
                    ]);
                    log_message('info', "Follow-up {$n} for email: {$row->email} reset to status 0 due to error.");
                }

            
        }
    }


    private function send_email($email, $title) {
        $this->load->helper('email');

        log_message('info', "Preparing to send email to: {$email} with title: {$title}");

        $to = $email;
        
        $app_url = 'https://demo.adminsekolah.net';
        $tutorial_url = 'https://tutorial.adminsekolah.net';

        $ios_link = 'http://s.id/AdminSekolahiOS';
        $play_store_link = 'http://s.id/AdminSekolahAndroid';
        $subject = $title;



        $customer = $this->db
            ->select('registrasi.*')
            ->from('email_marketing')
            ->join('registrasi', 'registrasi.registrasi_email = email_marketing.email')
            ->where('email_marketing.email', $email)
            ->order_by('registrasi.registrasi_id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if($title == 'Registrasi') {
            $customer = $this->db
                ->select('registrasi.*')
                ->from('scheduled_emails')
                ->join('registrasi', 'registrasi.registrasi_email = scheduled_emails.recipient_email')
                ->where('scheduled_emails.recipient_email', $email)
                ->where('scheduled_emails.status', 'pending')
                ->order_by('registrasi.registrasi_id', 'DESC')
                ->limit(1)
                ->get()
                ->row();
        }

        if($title == 'Registrasi') {
            $subject = 'Registrasi Admin Sekolah '. $customer->registrasi_school;
        } else if($title == 'Follow Up 1') {
            $subject = 'Pengenalan Adminsekolah kepada ' . $customer->registrasi_school;
        } else if($title == 'Follow Up 2') {
            $subject = 'Proposal Software Adminsekolah untuk ' . $customer->registrasi_school;
        } else if($title == 'Follow Up 3') {
            $subject = 'Undangan Meeting Implementasi Software Manajemen Sekolah AdminSekolah di ' . $customer->registrasi_school;
        } else if($title == 'Warning Notif WA') {
            $subject = 'Peringatan Notif WA Demo Telah Gagal Mengirim Pesan 5 Kali atau Lebih';
        }

        
        $body = email_template($title, array('name' => $customer->registrasi_name, 'school' => $customer->registrasi_school,
            'app_url' => $app_url, 'tutorial_url' => $tutorial_url));

        if($title=='Registrasi'){
            $body = email_template($title, array('name' => $customer->registrasi_name, 'school' => $customer->registrasi_school,
            'app_url' => $app_url, 'tutorial_url' => $tutorial_url, 'username' => $customer->registrasi_email, 'password' => $customer->registrasi_password,
        'ios_link' => $ios_link, 'play_store_link' => $play_store_link));
        } else if ($title == 'Warning Notif WA'){
            $wa_key                 = $this->Setting_model->get(array('name' => 'setting_wa_key'));
            $setting_whatsapp       = $this->Setting_model->get(array('id' => 92));
            $set_key                = $wa_key['setting_value'];
            $set_wa                 = $setting_whatsapp['setting_value'];

            $body = email_template($title, array('wa_sender' => $set_wa, 'wa_key' => $set_key));
        }


        $attachments = [];

        if($title=='Follow Up 2'){
            $attachments = [
                FCPATH . 'media/docs/Proposal-Adminsekolah.pdf'
            ];
        } else if($title=='Registrasi'){
                $attachments = [
                FCPATH . 'media/docs/Brosur-Adminsekolah.jpeg',
                FCPATH . 'media/docs/Presentasi-Produk-Adminsekolah.pdf'
            ];
        }


        if (send_email($to, $subject, $body, $attachments)) {
            return json_encode(array('status' => 'success', 'message' => 'Email sent successfully.'));
        } else {
            return json_encode(array('status' => 'error', 'message' => 'Failed to send email.'));
        }
    }
}
