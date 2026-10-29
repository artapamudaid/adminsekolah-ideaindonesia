<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Scheduler extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);

        $this->load->model(array('billing/Billing_model', 'student/Student_model', 'account/Account_model', 'pos/Pos_model', 'payment/Payment_model', 'period/Period_model', 'pos/Pos_model', 'bulan/Bulan_model', 'bebas/Bebas_model', 'bebas/Bebas_pay_model', 'setting/Setting_model', 'logs/Logs_model', 'report/Report_model', 'report/Detail_jurnal_model'));

        $this->load->helper('notif_helper');
        
    }

    public function run_scheduler()
    {
        // --- [LAYER 1] SECURITY ---
        if (!is_cli()) { show_404(); return; } 
        
        date_default_timezone_set('Asia/Jakarta');
        set_time_limit(0); // Unlimited execution time
        ini_set('memory_limit', '512M');

        echo "\n[" . date('Y-m-d H:i:s') . "] START SCHEDULER SINGLE TIME...\n";

        // --- [LAYER 2] CEK JADWAL SINGLE (UBAHAN UTAMA DISINI) ---
        
        // 1. Ambil Setting
        $scheduler_status = $this->Setting_model->get(['name' => 'scheduler_status'])['setting_value'] ?? 'N';
        $set_date = $this->Setting_model->get(['name' => 'scheduler_date'])['setting_value'] ?? '10';
        $set_time = $this->Setting_model->get(['name' => 'scheduler_time'])['setting_value'] ?? '08:00';
        $domain = $this->Setting_model->get(['name' => 'scheduler_domain'])['setting_value'] ?? 'https://demo.adminsekolah.net';

        if ($scheduler_status == 'N') {
            echo "Billing Scheduler Tidak/Belum Di Aktifkan";
            return;
        }

        $current_date = (int) date('j'); // 1-31
        $current_time = date('H:i');     // 00:00 - 23:59

        // 2. Validasi Tanggal
        // Jika HARI INI bukan TANGGAL YANG DISET, langsung berhenti.
        if ($current_date != (int)$set_date) {
            echo "SKIP: Hari ini tanggal $current_date. Jadwal diset hanya tanggal $set_date.\n";
            return; 
        }

        // 3. Validasi Jam
        // Kita pakai logic "Lebih Besar Dari" (>=).
        // Kenapa? Karena Cronjob mungkin telat 1-2 menit. 
        // Jika diset jam 08:00, tapi script jalan jam 08:05, dia HARUS tetap jalan.
        // Keamanan double-send ditangani oleh LOG DATABASE, bukan oleh jam.
        if ($current_time < $set_time) {
            echo "WAIT: Sekarang jam $current_time. Menunggu jadwal jam $set_time.\n";
            return;
        }

        echo "RUN: Waktu cocok (Tgl $current_date, Jam $current_time). Memulai proses...\n";

        // --- [LAYER 3] PROSES DATA ---
        
        // Ambil Tahun Ajaran & Bulan (Sama seperti sebelumnya)
        $ta = $this->db->get_where('period', ['period_status' => '1'])->row_array();
        if (!$ta) { echo "ERROR: Tidak ada TA aktif.\n"; return; }
        $period_id = $ta['period_id'];

        $bulan_nama = pretty_date(date('d-m-Y'), 'F', false);
        $bulan_db = $this->db->get_where('month', ['month_name' => $bulan_nama])->row();
        $current_month_id = $bulan_db->month_id;

        $limit_batch = 20;

        // Ambil Siswa Aktif
        // Ambil siswa yang AKTIF dan BELUM DIKIRIM bulan ini
        // Kita gunakan Subquery atau Logic Join agar efisien
        $students = $this->db->query("
            SELECT s.student_id 
            FROM student s
            WHERE s.student_status = '1'
            AND NOT EXISTS (
                SELECT 1 FROM billing_log_wa log 
                WHERE log.student_id = s.student_id 
                AND log.period_id = '$period_id' 
                AND log.month_id = '$current_month_id'
            )
            LIMIT $limit_batch
        ")->result_array();

        if (empty($students)) {
            echo "IDLE: Semua siswa bulan ini sudah diproses/dikirim.\n";
            return;
        }

        // Config WA
        $wa_key = $this->Setting_model->get(['name' => 'setting_wa_key'])['setting_value'];
        $wa_number = $this->Setting_model->get(['id' => 92])['setting_value'];

        $count_sent = 0;

        foreach ($students as $row) {
            $std_id = $row['student_id'];

            // --- [LAYER 4] CEK LOG (KUNCI KEAMANAN UTAMA) ---
            // Ini yang mencegah pengiriman ulang meskipun script jalan berkali-kali seharian di tanggal 10.
            $is_sent = $this->db->get_where('billing_log_wa', [
                'student_id' => $std_id,
                'period_id'  => $period_id,
                'month_id'   => $current_month_id
            ])->row();

            if ($is_sent) {
                // Sudah dikirim bulan ini -> SKIP
                continue; 
            }

            // --- HITUNG & KIRIM ---
            // Panggil logika hitung tagihan Anda
            $tagihan = $this->_hitung_total_tagihan($std_id, $period_id, $current_month_id); // Gunakan helper/function hitung

            if ($tagihan > 0) {
                // Ambil Data Siswa Lengkap
                $santri = $this->db->query("SELECT s.student_full_name, c.class_name, s.student_parent_phone 
                                            FROM student s JOIN class c ON c.class_id = s.class_class_id 
                                            WHERE s.student_id = '$std_id'")->row_array();

                if (!empty($santri['student_parent_phone']) && $santri['student_parent_phone'] != '+62') {

                    $no_wa = $santri['student_parent_phone'];
                    $nama  = $santri['student_full_name'];
                    $kelas = $santri['class_name'];
                    $nominal = number_format($tagihan, 0, ",", ".");
                    
                    // Pesan WA
                    $psn = "Diberitahukan kepada Ayah/Bunda Wali Siswa, kami ingin mengingatkan kembali kewajiban pembayaran ananda A/N *$nama*, Kelas $kelas sejumlah *Rp $nominal* sudah bisa ditunaikan.\n\n" .
                        "Kami mengharap kerjasama Ayah/Bunda agar bisa menunaikan kewajiban paling lambat tgl 10 tiap bulannya.\n\n" .
                        "Download Tagihan: " . $domain . '/billing/cetak?a=' . base64_encode($period_id) . '&b=' . base64_encode('1') . '&c=' . base64_encode($current_month_id) . '&d=' . base64_encode($std_id) . "\n\n" . 
                        "*) Harap simpan bukti pembayaran yang sah.";

                    // Kirim WA
                    send_whatsapp($no_wa, $psn, $wa_key, $wa_number);
                    
                    // CATAT LOG (Wajib!)
                    $this->db->insert('billing_log_wa', [
                        'student_id' => $std_id,
                        'period_id'  => $period_id,
                        'month_id'   => $current_month_id,
                        'sent_at'    => date('Y-m-d H:i:s'),
                        'status'     => 'success'
                    ]);

                    $jeda = rand(5, 15);

                    echo "SENT: " . $santri['student_full_name'] . "\n";
                    $count_sent++;

                    sleep($jeda);
                }
            }
        }

        echo "FINISH. Terkirim: $count_sent\n";
    }

    private function _hitung_total_tagihan($student_id, $period_id, $month_id_current)
    {
        // ==========================================
        // 1. SETUP PARAMETER QUERY TAHUN SEKARANG
        // ==========================================
        $params = array();
        $params['student_id']   = $student_id;
        $params['period_id']    = $period_id;
        $params['month_start']  = 1; // Asumsi mulai bulan pertama
        $params['month_end']    = $month_id_current;

        // ==========================================
        // 2. SETUP PARAMETER PERIODE LAMA (TUNGGAKAN)
        // ==========================================
        $periode_lama_ids = array();
        
        // Ambil data periode aktif saat ini
        $periodNow = $this->db->get_where('period', ['period_id' => $period_id])->row_array();
        
        // Cari periode yang start_date-nya lebih kecil dari periode sekarang
        if ($periodNow) {
            $periodLama = $this->db->select('period_id')
                                ->from('period')
                                ->where('period_start <', $periodNow['period_start'])
                                ->get()
                                ->result_array();

            foreach ($periodLama as $row) {
                $periode_lama_ids[] = $row['period_id'];
            }
        }

        // Siapkan parameter untuk query lama (jika ada tunggakan)
        $params_lama = array();
        $ada_tunggakan = false;

        if (!empty($periode_lama_ids)) {
            $params_lama['student_id'] = $student_id;
            $params_lama['period_id']  = $periode_lama_ids;
            $ada_tunggakan = true;
        }

        // ==========================================
        // 3. HITUNG TAGIHAN BULANAN (SPP, dll)
        // ==========================================
        $total_bulan = 0;

        // A. Bulanan Tahun Ini
        $bulan_now = $this->Billing_model->get_tagihan_bulan($params);
        if (!empty($bulan_now)) {
            foreach ($bulan_now as $row) {
                $total_bulan += $row['bulan_bill'];
            }
        }

        // B. Bulanan Tahun Lalu (Tunggakan)
        if ($ada_tunggakan) {
            $bulan_old = $this->Billing_model->get_tagihan_bulan_lama($params_lama);
            if (!empty($bulan_old)) {
                foreach ($bulan_old as $row) {
                    $total_bulan += $row['bulan_bill'];
                }
            }
        }

        // ==========================================
        // 4. HITUNG TAGIHAN BEBAS (Gedung, Seragam, dll)
        // Rumus: Tagihan - Diskon - Sudah Dibayar
        // ==========================================
        $total_bebas = 0;

        // A. Bebas Tahun Ini
        $bebas_now = $this->Billing_model->get_tagihan_bebas($params);
        if (!empty($bebas_now)) {
            foreach ($bebas_now as $row) {
                $tagihan = $row['bebas_bill'];
                $diskon  = $row['bebas_diskon'];
                $bayar   = $row['bebas_total_pay'];
                
                // Hitung sisa
                $sisa = $tagihan - $diskon - $bayar;
                $total_bebas += $sisa;
            }
        }

        // B. Bebas Tahun Lalu (Tunggakan)
        if ($ada_tunggakan) {
            $bebas_old = $this->Billing_model->get_tagihan_bebas_lama($params_lama);
            if (!empty($bebas_old)) {
                foreach ($bebas_old as $row) {
                    $tagihan = $row['bebas_bill'];
                    $diskon  = $row['bebas_diskon'];
                    $bayar   = $row['bebas_total_pay'];
                    
                    $sisa = $tagihan - $diskon - $bayar;
                    $total_bebas += $sisa;
                }
            }
        }

        // ==========================================
        // 5. TOTAL AKHIR
        // ==========================================
        
        // Return berupa angka murni (Integer/Float)
        // Formatting (Rp. xxx) dilakukan nanti saat kirim WA
        return $total_bulan + $total_bebas;
    }

}