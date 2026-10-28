<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('send_email')) {

    function send_email($to, $subject, $body, $attachments = array()) {

        // Nonaktifkan error reporting untuk warning agar tidak mengganggu proses pengiriman email
        error_reporting(E_ALL & ~E_WARNING);

        $from_email = MAIL_FROM_ADDRESS ?? 'tester@example.com';
        $from_name = MAIL_FROM_NAME ?? 'Tester';

        $CI =& get_instance();
        $CI->load->library('email');

        // Konfigurasi email langsung
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => MAIL_HOST ?? 'smtp.gmail.com', // Ganti sesuai host SMTP yang digunakan
            'smtp_port' => MAIL_PORT ?? 465,
            'smtp_user' => MAIL_USERNAME ?? 'emailpengirim@gmail.com',
            'smtp_pass' => MAIL_PASSWORD ?? 'passwordemail',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        );

        $CI->email->initialize($config);

        $message = '
    <div style="font-family: Arial, sans-serif; background: #f9fafb; padding: 40px 0;">
      <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
        <div style="text-align: center; padding: 30px 0;">
          <img src="https://adminsekolah.net/wp-content/uploads/2023/07/logo-adminsekolah-baru.png" alt="AdminSekolah" height="40">
        </div>';
        $message .= $body;
      $message .= '</div>
    </div>';


        $CI->email->from($from_email, $from_name);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        if ($attachments) {
            if (is_array($attachments)) {
                foreach ($attachments as $file) {
                    if (file_exists($file)) {
                        $CI->email->attach($file);
                    }
                }
            } elseif (is_string($attachments) && file_exists($attachments)) {
                $CI->email->attach($attachments);
            }
        }

        if ($CI->email->send()) {
            return true;
        } else {
            log_message('error', 'Email gagal dikirim: ' . $CI->email->print_debugger());
            return false;
        }
    }

    function email_template($title, $content=array()) {

        $greetings = 'Selamat Pagi';
        $current_hour = date('H');
        if ($current_hour >= 11 && $current_hour < 15) {
            $greetings = 'Selamat Siang';
        } else if ($current_hour >= 15 && $current_hour < 18) {
            $greetings = 'Selamat Sore';
        } else if ($current_hour >= 18 || $current_hour < 3) {
            $greetings = 'Selamat Malam';
        }

        if ($title == 'Registrasi') {
            $template = '
        <div style="padding: 0 40px 30px 40px;">
          <p style="font-size: 16px;"><strong>Yth. Bapak/Ibu ' . htmlspecialchars($content['name']) . ' dari '.htmlspecialchars($content['school']).' ,</strong></p>
          <p>Terimakasih sudah mendaftar trial aplikasi <strong>AdminSekolah.net</strong> 📑 </p>
          <p>Perkenalkan saya Kiki, yang akan membantu Anda dalam penggunaan aplikasi AdminSekolah:</p>

          <ul style="list-style: none; padding-left: 0;">
            <li>Silahkan login menggunakan akses berikut:</li>
            <li><strong>Login:</strong> <a href="' . $content['app_url'] . '" style="color: #3081bd;">' . $content['app_url'] . '</a></li>
            <li><strong>Username:</strong> ' . htmlspecialchars($content['username']) . '</li>
            <li><strong>Password:</strong> ' . htmlspecialchars($content['password']) . '</li>
          </ul>

          <ul style="list-style: none; padding-left: 0;">
            <li> Berikut panduan untuk mencoba fitur-fiturnya :</li>
            <li><strong>Tutorial Web:</strong> <a href="' . $content['tutorial_url'] . '" style="color: #3081bd;">' . $content['tutorial_url'] . '</a></li>
            <li><strong>Tutorial Video:</strong> <a href="https://s.id/YoutubeTutorialAdminSekolah" style="color: #3081bd;">https://s.id/YoutubeTutorialAdminSekolah</a></li>
          </ul>

          <hr style="margin: 40px 0; border: none; border-top: 1px solid #e5e7eb;">

          <ul style="list-style: none; padding-left: 0;">
            <li><strong>Apabila Bapak/Ibu berkenan silahkan coba juga demo aplikasi Mobile AdminSekolah secara GRATIS melalui akses berikut:</strong></li>
            <li><a href="' . $content['ios_link'] . '" style="color: #3081bd;">'.$content['ios_link'].'</a></li>
            <li><a href="' . $content['play_store_link'] . '" style="color: #3081bd;">'.$content['play_store_link'].'</a></li>
          </ul>

          <br>

            <ul style="list-style: none; padding-left: 0;">
                <li><strong>AKSES PEGAWAI</strong></li>
                <li>Kode Sekolah : 2020123</li>
                <li>NIP : 123</li>
                <li>Password : 123456</li>
            </ul>
            <br>
            <ul style="list-style: none; padding-left: 0;">
                <li><strong>AKSES SISWA & WALIMURID</strong></li>
                <li>Kode Sekolah : 2020123</li>
                <li>NIS : 20250201</li>
                <li>Password : 123456</li>
            </ul>
            
            <br>

            Apabila terdapat kendala atau ada yang ingin ditanyakan jangan ragu untuk menghubungi nomor customer servis di : 
            <br>
            <a href="https://wa.me/6281233640003" style="color: #3081bd;">https://wa.me/6281233640003</a>
            <br>

          <p>Salam,<br>
          Kiki CS AdminSekolah</p>
        </div>';
        }

        else if($title == 'Follow Up 1') {
                $template = '
                <div style="padding: 0 40px 40px 40px;">
                    <p style="font-size: 16px; line-height: 1.6;">'.$greetings.' Bapak/Ibu <strong>'.$content['name'].'</strong>!</p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Salam kenal, saya <strong>Kiki</strong>, Customer Service dari Software Manajemen Sekolah <strong>AdminSekolah</strong>.
                        Terima kasih karena bapak/ibu sudah tertarik untuk mencoba layanan kami.
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Melalui email ini, kami ingin menanyakan terkait kebutuhan Manajemen Sekolah di <strong>'.$content['school'].'</strong> seperti apa ya Bapak/Ibu?
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Bila bapak/ibu membutuhkan konsultasi lebih lanjut, bisa langsung menghubungi kami melalui WhatsApp:
                        <br><a href="https://wa.me/6281233640003" style="color: #3081bd;">https://wa.me/6281233640003</a>
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Semoga sukses selalu untuk karir dan lembaganya ya Bapak/Ibu 😊
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Salam hangat,<br><br>
                        <strong>Kiki Hasanah</strong><br>
                        Customer Service<br>
                        <span style="color: #3081bd;">AdminSekolah</span>
                    </p>
                </div>';     
        } else if($title == 'Follow Up 2') {

            $template = '
                <div style="padding: 0 40px 40px 40px;">
                    <p style="font-size: 16px; line-height: 1.6;">'.$greetings.' Bapak/Ibu '.$content['name'].'</p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Salam kenal saya Kiki, Customer Service dari AdminSekolah.
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Udah beberapa hari nih sejak Bapak/Ibu mencoba AdminSekolah. Gimana kesannya Bapak/Ibu? Semoga semuanya berjalan lancar dan Bapak/Ibu nyaman ya dengan aplikasi kami. 😊
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Pekan ini atau pekan depan kapan bapak/ibu ada waktu untuk zoom meeting dengan saya? Nanti saya bantu jelaskan bagaimana AdminSekolah bisa diterapkan di '.$content['school'].'. Zoom meeting one on one ini sekitar 30 - 60 menit, dan gratis tanpa ada biaya.
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Jika bapak/ibu berkenan, silahkan hubungi kontak dibawah ini di bawah ya Bapak/Ibu.
                        <br><a href="https://wa.me/6281233640003" style="color: #3081bd;">Hubungi AdminSekolah</a>
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Terakhir, salam kenal dari saya, Kiki, dan sukses terus untuk lembaga Bapak/Ibu 😊✨
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Salam hangat,<br><br>
                        <strong>Kiki Hasanah</strong><br>
                        Customer Service<br>
                        <span style="color: #3081bd;">AdminSekolah</span>
                    </p>
                </div>';
        } else if($title == 'Follow Up 3') {
            $template = '
                <div style="padding: 0 40px 40px 40px;">
                    <p style="font-size: 16px; line-height: 1.6;">'.$greetings.' Bapak/Ibu '.$content['name'].'</p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Perkenalkan saya Kiki, Customer Service dari AdminSekolah. Sebelumnya terima kasih sudah tertarik untuk mencoba menggunakan Software Manajemen Sekolah AdminSekolah.
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Dalam email ini saya lampirkan proposal dan penawaran Software Manajemen Sekolah Adminsekolah.
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Beberapa hari kedepan kapan bapak/ibu ada waktu untuk zoom meeting dengan saya? Nanti saya bantu jelaskan bagaimana Adminsekolah bisa diterapkan di '.$content['school'].'. Zoom meeting one on one ini sekitar 30 - 60 menit dan gratis.</a>
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Jika bapak/ibu berkenan, silahkan hubungi kontak dibawah ini di bawah ya Bapak/Ibu.
                        <br><a href="https://wa.me/6281233640003" style="color: #3081bd;">Hubungi AdminSekolah</a>
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Semoga sukses selalu untuk karir dan lembaganya ya Bapak/Ibu 😊
                    </p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Salam hangat,<br><br>
                        <strong>Kiki Hasanah</strong><br>
                        Customer Service<br>
                        <span style="color: #3081bd;">AdminSekolah</span>
                    </p>
                </div>';
        } else if($title == 'Warning Notif WA') {
            $template = '
                <div style="padding: 0 40px 40px 40px;">
                    <p style="font-size: 16px; line-height: 1.6;">Halo Developer Indoweb!</p>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Memberitahukan jika notif whatsapp dengan credentials berikut sedang tidak aktif
                    </p>

                     <ul style="list-style: none; padding-left: 0;">
                        <li><strong>Nomor Pengirim:</strong> ' . $content['wa_sender'] . '</li>
                        <li><strong>API Secret Key:</strong> ' . $content['wa_key'] . '</li>
                    </ul>

                    <p style="font-size: 16px; line-height: 1.6;">
                        Silahkan Periksa di <a href="https://indoweb.notifwa.com/login" style="color: #020202;">Notif Center Indoweb</a>
                    </p>


                    <p style="font-size: 16px; line-height: 1.6;">
                        Salam ,<br><br>
                        Pesan Otomatis<br>
                    </p>
                </div>';
        } else {
            $template = '';
        }

        return $template;
    }
}
