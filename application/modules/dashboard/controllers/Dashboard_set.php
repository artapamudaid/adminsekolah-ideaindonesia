<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }

        $this->load->model(array(
            'users/Users_model',
            'holiday/Holiday_model',
            'dashboard/Dashboard_model',
            'student/Student_model',
            'kredit/Kredit_model',
            'debit/Debit_model',
            'bulan/Bulan_model',
            'setting/Setting_model',
            'information/Information_model',
            'rekap_presensi/Rekap_presensi_model',
            'bebas/Bebas_model',
            'bebas/Bebas_pay_model',
            'period/Period_model'
        ));

        $this->load->library('user_agent');

        $siteLang = $this->session->userdata('site_lang');
        if ($siteLang) {
            $this->lang->load('dashboard', $siteLang);
        } else {
            $this->lang->load('dashboard', 'indonesia');
        }
    }

    public function index_old()
    {
        $year = date('Y');

        $id = $this->session->userdata('uid');
        $uRole = $this->session->userdata('uroleid');
        $umajorsid = $this->session->userdata('umajorsid');

        $selected_majors = $umajorsid;
        if ($umajorsid == 0) {
            $get_majors = $this->input->get('majors_id');
            if ($get_majors !== NULL && $get_majors !== '') {
                $selected_majors = (int)$get_majors;
            }
        }

        // NOTES: Custom condition by majors on specific logic
        $widget_jumlah_siswa_title = $this->lang->line('dash_student_per_level');
        $where_majors_v_laporan_jurnal = "";
        $where_majors_student = "";
        $where_majors_employee = "";
        $where_majors_banking = "";
        $whereMajors = "";
        if ($selected_majors != 0) {
            $widget_jumlah_siswa_title = 'Jumlah Siswa Unit ' . $this->mapping_singkatan_unit($selected_majors);
            $where_majors_v_laporan_jurnal = "AND idMajors = '$selected_majors'";
            $where_majors_student = "AND majors_majors_id = '$selected_majors'";
            $where_majors_employee = "AND employee_majors_id = '$selected_majors'";
            $where_majors_banking = "AND s.majors_majors_id = '$selected_majors'";
            $whereMajors = " AND majors_id = '$selected_majors'";
        }

        $hasDashboard = false;
        if ($uRole) {
            $cekAksesDashboard = $this->db->query("SELECT hak_akses_id FROM hak_akses WHERE menu_id = 1 AND role_id = $uRole LIMIT 1")->row();
            if ($cekAksesDashboard) {
                $hasDashboard = true;
            }
        }

        $data['user'] = count($this->Users_model->get());

        $data['majors'] = $this->db->query("SELECT majors_id, majors_short_name FROM majors WHERE majors_status = 1")->result_array();

        $period = $this->db->query("SELECT period_id,period_start,period_end FROM period WHERE period_status = 1")->row_array();
        if (!$period) {
            $periodActive = 1;
            $periodStart = date('Y');
            $periodEnd = date('Y') + 1;
        } else {
            $periodActive = $period['period_id'];
            $periodStart = $period['period_start'];
            $periodEnd = $period['period_end'];
        }

        $dateStart = "$periodStart-07-01";
        $dateEnd = "$periodEnd-06-30";

        $mon = pretty_date(date('Y-m-d'), 'F', false);
        $bulanSekarang = pretty_date(date('Y-m-d'), 'm', false);

        $month = $this->db->query("SELECT month_id FROM month WHERE month_name = '$mon'")->row_array();
        $data['monthActive'] = $month['month_id'];

        if ($hasDashboard) {
            // $data['kas']            = $this->db->query("SELECT kas_noref, kas_debit, kas_kredit, kas_month_id, kas_majors_id, kas_input_date FROM kas WHERE kas_period = '$periodActive'")->result_array();
            $data['kasTotal'] = $this->db->query(
                "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                    FROM v_laporan_jurnal 
                    WHERE tanggal BETWEEN  '$dateStart' AND '$dateEnd' $where_majors_v_laporan_jurnal
                    GROUP BY jenisTransaksi"
            )->result_array();

            $data['kasMonth'] = $this->db->query(
                "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                    FROM v_laporan_jurnal 
                    WHERE tanggal BETWEEN  '$dateStart' AND '$dateEnd' AND MONTH(tanggal) = MONTH(CURRENT_DATE()) $where_majors_v_laporan_jurnal
                    GROUP BY jenisTransaksi"
            )->result_array();

            $data['kasToday'] = $this->db->query(
                "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                    FROM v_laporan_jurnal 
                    WHERE tanggal = CURDATE() $where_majors_v_laporan_jurnal
                    GROUP BY jenisTransaksi"
            )->result_array();

            $data['student'] = $this->db->query("SELECT student_status, majors_majors_id FROM student WHERE 1=1 $where_majors_student")->result_array();

            $where_majors_employee_v = "";
            if ($selected_majors != 0) {
                $where_majors_employee_v = "AND employee_majors_id = '$selected_majors'";
            }
            $data['employee'] = $this->db->query("SELECT employee_category FROM employee WHERE 1=1 $where_majors_employee_v")->result_array();

            $banking = $this->db->query("SELECT 
            SUM(b.banking_debit) AS total_setor, 
            SUM(b.banking_kredit) AS total_tarik 
            FROM banking b
            JOIN student s ON s.student_id = b.banking_student_id
            WHERE 1=1 $where_majors_banking")->row_array();

            $data['total_setoran'] = (int)$banking['total_setor'] ?? 0;
            $data['total_penarikan'] = (int)$banking['total_tarik'] ?? 0;

            $data['information'] = $this->Information_model->get(array('information_publish' => 1));
            $data['majors_chart'] = $this->db->query("SELECT * FROM majors WHERE 1 $whereMajors")->result_array();

            $data['selected_majors'] = $selected_majors;
            $data['numStudents']    = $this->db->query(
                "SELECT majors.majors_short_name AS unit,COUNT(student.student_id) AS numbers
            FROM majors
            LEFT JOIN student ON student.majors_majors_id = majors.majors_id
            WHERE 1=1 $whereMajors $where_majors_student
            GROUP BY majors.majors_id
            ORDER BY majors.majors_id ASC"
            )->result_array();

            $data['lastPayments'] = $this->db->query("SELECT s.student_full_name, MAX(l.log_trx_input_date) AS last_payment
                                                            FROM log_trx l
                                                            JOIN student s ON s.student_id = l.student_student_id
                                                            WHERE DATE(l.log_trx_input_date) = CURRENT_DATE $where_majors_student
                                                            GROUP BY l.student_student_id, s.student_full_name
                                                            ORDER BY last_payment DESC
                                                            LIMIT 5")->result_array();

            $data['total_pay_period'] = 0;
            $data['total_debit_period'] = 0;
            $data['total_kredit_period'] = 0;

            foreach ($data['kasTotal'] as $row) {
                if ($row['jenisTransaksi'] == 'Bulan' || $row['jenisTransaksi'] == 'Bebas') {
                    $data['total_pay_period'] += $row['tDebit'];
                    $data['total_debit_period'] += $row['tDebit'];
                }
                if ($row['jenisTransaksi'] == 'Debit') {
                    $data['total_debit_period'] += $row['tDebit'];
                }
                if ($row['jenisTransaksi'] == 'Kredit') {
                    $data['total_kredit_period'] += $row['tKredit'];
                }
            }

            $data['total_pay_month'] = 0;
            $data['total_debit_month'] = 0;
            $data['total_kredit_month'] = 0;
            foreach ($data['kasMonth'] as $row) {
                if ($row['jenisTransaksi'] == 'Bulan' || $row['jenisTransaksi'] == 'Bebas') {
                    $data['total_pay_month'] += $row['tDebit'];
                    $data['total_debit_month'] += $row['tDebit'];
                }
                if ($row['jenisTransaksi'] == 'Debit') {
                    $data['total_debit_month'] += $row['tDebit'];
                }
                if ($row['jenisTransaksi'] == 'Kredit') {
                    $data['total_kredit_month'] += $row['tKredit'];
                }
            }

            $data['total_debit_today'] = 0;
            $data['total_pay_today'] = 0;
            $data['total_kredit_today'] = 0;
            foreach ($data['kasToday'] as $row) {
                if ($row['jenisTransaksi'] == 'Bulan' || $row['jenisTransaksi'] == 'Bebas') {
                    $data['total_pay_today'] += $row['tDebit'];
                    $data['total_debit_today'] += $row['tDebit'];
                }
                if ($row['jenisTransaksi'] == 'Debit') {
                    $data['total_debit_today'] += $row['tDebit'];
                }
                if ($row['jenisTransaksi'] == 'Kredit') {
                    $data['total_kredit_today'] += $row['tKredit'];
                }
            }


            $data['total_active'] = 0;
            foreach ($data['student'] as $row) {
                if ($row['student_status'] == 1) {
                    $data['total_active'] += 1;
                }
            }

            $data['total_alumni'] = 0;
            foreach ($data['student'] as $row) {
                if ($row['student_status'] == 0) {
                    $data['total_alumni'] += 1;
                }
            }

            $data['total_other'] = 0;
            foreach ($data['student'] as $row) {
                if ($row['student_status'] != 0 && $row['student_status'] != 1) {
                    $data['total_other'] += 1;
                }
            }

            $data['total_tetap'] = 0;
            foreach ($data['employee'] as $row) {
                if ($row['employee_category'] == 1) {
                    $data['total_tetap'] += 1;
                }
            }

            $data['total_non_tetap'] = 0;
            foreach ($data['employee'] as $row) {
                if ($row['employee_category'] != 1) {
                    $data['total_non_tetap'] += 1;
                }
            }

            $data['total_all'] = 0;
            foreach ($data['employee'] as $row) {
                $data['total_all'] += 1;
            }

            $this->load->library('form_validation');
            if ($this->input->post('add', TRUE)) {
                $this->form_validation->set_rules('date', 'Tanggal', 'required');
                $this->form_validation->set_rules('info', 'Info', 'required');
                $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

                if ($_POST and $this->form_validation->run() == TRUE) {
                    list($tahun, $bulan, $tanggal) = explode('-', $this->input->post('date', TRUE));

                    $params['year'] = $tahun;
                    $params['date'] = $this->input->post('date');
                    $params['info'] = $this->input->post('info');

                    $ret = $this->Holiday_model->add($params);

                    $this->session->set_flashdata('success', 'Tambah Agenda berhasil');
                    redirect('manage');
                }
            } elseif ($this->input->post('del', TRUE)) {
                $this->form_validation->set_rules('id', 'ID', 'required');
                $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

                if ($_POST and $this->form_validation->run() == TRUE) {
                    $id = $this->input->post('id', TRUE);
                    $this->Holiday_model->delete($id);

                    $this->session->set_flashdata('success', 'Hapus Agenda berhasil');
                    redirect('manage');
                }
            }

            $data['widget_jumlah_siswa_title'] = $widget_jumlah_siswa_title;

            $dates['tgl_awal'] = date('Y-m-d');
            $dates['tgl_akhir'] = date('Y-m-d');

            $data['package'] = $this->Setting_model->get(array('name' => 'setting_package'));

            $data['periodActive'] = $periodActive;
            $data['period'] = $this->Period_model->get();
            $data['month'] = $this->Bulan_model->get_month();
            $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
            $data['rekap_presensi'] = $this->Rekap_presensi_model->get_dashboard($dates);

            $data['holiday'] = $this->Holiday_model->get();
            $data['main'] = 'dashboard/dashboard';
        } else {
            $data['information'] = $this->Information_model->get(array('information_publish' => 1));

            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard_employees';
        }

        $data['title'] = 'Dashboard';
        $this->load->view('manage/layout', $data);
    }

    public function index()
    {

        $id = $this->session->userdata('uid');
        $uRole = $this->session->userdata('uroleid');
        $umajorsid = $this->session->userdata('umajorsid');

        $selected_majors = $umajorsid;
        if ($umajorsid == 0) {
            $get_majors = sanitizeStrict($this->input->get('m'));
            if ($get_majors !== NULL && $get_majors !== '') {
                $selected_majors = (int)$get_majors;
            }
        }
        /*
        Array
        (
            [__ci_last_regenerate] => 1790737293
            [logged] => 1
            [uid] => 2
            [uemail] => ts@indoweb.id
            [ufullname] => Technical Support Indoweb.id
            [uroleid] => 1
            [umajorsid] => 0
            [umajorsname] => 
            [urolename] => ADMIN
            [user_image] => 
            [suspend_date] => 2027-07-16
            [user_password_last_update] => 
            [user_input_date] => 2024-07-17 06:43:46
        )
        */

        // NOTES: Custom condition by majors on specific logic
        $widget_jumlah_siswa_title = $this->lang->line('dash_student_per_level');
        $where_majors_v_laporan_jurnal = "";
        $where_majors_student = "";
        $where_majors_employee = "";
        $where_majors_class = "";
        $where_majors_banking = "";
        $whereMajors = "";
        if ($selected_majors != 0) {
            $widget_jumlah_siswa_title = 'Jumlah Siswa Unit ' . $this->mapping_singkatan_unit($selected_majors);
            $where_majors_v_laporan_jurnal = "AND idMajors = '$selected_majors'";
            $where_majors_student = "AND majors_majors_id = '$selected_majors'";
            $where_majors_employee = "AND employee_majors_id = '$selected_majors'";
            $where_majors_class = "AND majors_majors_id = '$selected_majors'";
            $where_majors_banking = "AND s.majors_majors_id = '$selected_majors'";
            $whereMajors = " AND majors_id = '$selected_majors'";
        }

        $hasDashboard = false;
        if ($uRole) {
            $cekAksesDashboard = $this->db->query("SELECT hak_akses_id FROM hak_akses WHERE menu_id = 1 AND role_id = $uRole LIMIT 1")->row();
            if ($cekAksesDashboard) {
                $hasDashboard = true;
            }
        }

        $data['role_name'] = $this->session->userdata('urolename');
        $filter_majors = $this->db->query("SELECT majors_id, majors_name, majors_short_name FROM majors WHERE majors_status = 1")->result_array();
        $data['filter_majors'] = $filter_majors;
        $school_name = $this->Setting_model->get(array('id' => 1));
        $data['nama_unit'] = 'Semua Unit ' . $school_name['setting_value'];

        $data['months_list'] = $this->db->query("SELECT month_id, month_name FROM month ORDER BY month_id ASC")->result_array();
        $data['periods_list'] = $this->Period_model->get();

        if ($hasDashboard) {
            foreach ($filter_majors as $row) {
                if ($row['majors_id'] == $selected_majors) {
                    $data['nama_unit'] = $row['majors_short_name'] . ' - ' . $school_name['setting_value'];
                }
            }

            $data['selected_majors'] = $selected_majors;

            // Top Metrics
            $data['dummy_top_metrics'] = [
                $this->top_metrics_student($where_majors_student),
                $this->top_metrics_teacher($where_majors_employee),
                $this->top_metrics_class($where_majors_class),
                $this->top_metrics_kas($where_majors_v_laporan_jurnal),
                $this->top_metrics_collection_rate($selected_majors),
                $this->top_metrics_tabungan($where_majors_banking)
            ];

            // Ikhtisar Keuangan
            $ikhtisar_month = date('n');
            $ikhtisar_year  = date('Y');
            $period = $this->db->query("SELECT period_start, period_end FROM period WHERE period_status = 1 LIMIT 1")->row_array();
            $data['ikhtisar_period'] = $period;
            $data['ikhtisar_month']  = $ikhtisar_month;
            $data['ikhtisar_year']   = $ikhtisar_year;
            $data['dummy_ikhtisar_doughnut'] = $this->get_ikhtisar_keuangan($ikhtisar_month, $ikhtisar_year, $where_majors_v_laporan_jurnal);

            // arus kas
            $data['arus_kas'] = $this->get_arus_kas_data($where_majors_v_laporan_jurnal);

            // tagihan bulan ini
            $all_tagihan = $this->Dashboard_model->get_tagihan_bulan_ini($selected_majors);
            $data['tagihan_bulan_ini'] = array_slice($all_tagihan, 0, 4);
            $data['has_more_tagihan']  = count($all_tagihan) > 4;

            // transaksi terbaru dari ltrx
            $data['recent_transactions'] = $this->Dashboard_model->get_recent_transactions($selected_majors, 5);

            $data['dummy_aktivitas'] = [
                ['icon' => 'fa-users bg-green', 'title' => 'Presensi Siswa', 'desc' => '315 siswa hadir hari ini', 'time' => '07:30'],
                ['icon' => 'fa-book bg-blue', 'title' => 'Jurnal Mengajar', 'desc' => '14 guru mengisi jurnal', 'time' => '08:15'],
                ['icon' => 'fa-money bg-yellow', 'title' => 'Pembayaran Diterima', 'desc' => '12 transaksi pembayaran', 'time' => '09:20'],
                ['icon' => 'fa-bullhorn bg-red', 'title' => 'Pengumuman Baru', 'desc' => 'Libur akhir semester', 'time' => '10:00'],
                ['icon' => 'fa-file-text-o bg-blue', 'title' => 'Perizinan Siswa', 'desc' => '3 pengajuan izin', 'time' => '10:15'],
            ];

            // top 5 tabungan
            $data['top_5_tabungan'] = $this->Dashboard_model->get_top_5_tabungan($selected_majors);
            $data['show_unit_tabungan'] = (empty($selected_majors) || $selected_majors == 0);

            $data['dummy_pengumuman'] = [
                ['type' => 'Penting', 'label' => 'text-red', 'border' => 'border-color: red;', 'title' => 'Libur Akhir Semester', 'desc' => 'Kegiatan belajar mengajar diliburkan mulai tgl 1 - 7 Juni 2025.', 'date' => '23 Mei 2025', 'icon' => 'fa-bullhorn'],
                ['type' => 'Info', 'label' => 'text-blue', 'border' => 'border-color: blue;', 'title' => 'Pembayaran SPP Bulan Juni', 'desc' => 'Diharapkan seluruh wali siswa melakukan pembayaran sebelum tgl 10 Juni 2025.', 'date' => '22 Mei 2025', 'icon' => 'fa-info-circle'],
            ];

            $data['dummy_jadwal'] = [
                ['time' => '07:00 - 07:30', 'title' => 'Upacara Bendera', 'desc' => 'Lapangan Sekolah', 'color' => 'bg-green'],
                ['time' => '07:30 - 09:00', 'title' => 'Matematika - Kelas 7A', 'desc' => 'Ruang 1 | Bpk. Andi', 'color' => 'bg-blue'],
                ['time' => '09:15 - 10:45', 'title' => 'IPA - Kelas 8B', 'desc' => 'Ruang 2 | Ibu Rina', 'color' => 'bg-yellow'],
                ['time' => '11:00 - 12:30', 'title' => 'Bahasa Inggris - Kelas 9A', 'desc' => 'Ruang 3 | Bpk. Dedi', 'color' => 'bg-purple'],
            ];

            $data['dummy_spp_chart'] = [
                'labels' => ['Kelas 7A', 'Kelas 7B', 'Kelas 8A', 'Kelas 8B', 'Kelas 9A', 'Kelas 9B'],
                'lunas' => [28, 25, 30, 27, 32, 29],
                'belum_lunas' => [4, 5, 2, 5, 1, 3],
                'collection_rate' => [87.5, 83.3, 93.8, 84.4, 97.0, 90.6]
            ];

            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard_new';
        } else {
            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard_employees';
        }

        $this->load->view('manage/layout', $data);
    }

    public function realisasi_xls()
    {
        $getRealisasi = $this->Dashboard_model->get_realisasi();

        $dataRealisasi = json_decode($getRealisasi, true);
        $data['rBulan'] = $dataRealisasi['rp_bulan'];
        $data['rBebas'] = $dataRealisasi['rp_bebas'];
        $data['majors'] = $this->Student_model->get_majors();

        $start = $this->get_month_name($_GET['start']);
        $end = $this->get_month_name($_GET['end']);

        $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
        $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
        $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
        $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
        $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
        $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
        $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));

        $data['bulan'] = $start . '_' . $end;
        $data['period'] = $dataRealisasi['period'];

        $this->load->view('dashboard/realisasi_xls', $data);
    }

    private function get_month_name(int $month = null)
    {

        $this->db->where('month_id', $month);
        $month = $this->db->get('month')->row_array();

        return $month['month_name'];
    }

    public function get()
    {
        $events = $this->Holiday_model->get();
        foreach ($events as $i => $row) {
            $data[$i] = array(
                'id' => $row['id'],
                'title' => strip_tags($row['info']),
                'start' => $row['date'],
                'end' => $row['date'],
                'year' => $row['year'],
                //'url' => event_url($row)
            );
        }
        echo json_encode($data, TRUE);
    }

    private function pembulatan(float $number = null)
    {

        $round = ceil($number * 10) / 10;

        $round = number_format($round, 1);

        return $round;
    }

    private function mapping_singkatan_unit(String $unit = null)
    {
        $majors = $this->db->query("SELECT majors_id, majors_short_name FROM majors WHERE majors_status = 1")->result_array();

        $mapping = array_column($majors, 'majors_short_name', 'majors_id');

        return isset($mapping[$unit]) ? $mapping[$unit] : $unit;
    }

    private function top_metrics_student($where_majors_student = "")
    {
        $year = date('Y');
        $last_year = $year - 1;

        $current = $this->db->query("SELECT COUNT(student_id) as total FROM student WHERE student_status = 1 {$where_majors_student}")->row()->total;
        $this_year_count = $this->db->query("SELECT COUNT(student_id) as total FROM student WHERE student_status = 1 AND YEAR(student_input_date) = {$year} {$where_majors_student}")->row()->total;
        $last_year_count = $this->db->query("SELECT COUNT(student_id) as total FROM student WHERE student_status = 1 AND YEAR(student_input_date) = {$last_year} {$where_majors_student}")->row()->total;

        $diff = $this_year_count - $last_year_count;
        $trend_icon = $diff >= 0 ? '<i class="fa fa-arrow-up" aria-hidden="true"></i>' : '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
        $trend_color = $diff >= 0 ? '#28a745' : '#dc3545';
        $trend_subcolor = $diff >= 0 ? 'text-green' : 'text-red';
        $diff_abs = abs($diff);

        return [
            'title' => $this->lang->line('dash_total_students'),
            'icon' => 'fa-users',
            'value' => number_format($current, 0, ',', '.'),
            'label' => $this->lang->line('dash_active'),
            'subtext' => $diff != 0 ? "{$trend_icon} {$diff_abs} {$this->lang->line('dash_FLY')}" : '',
            'color' => $trend_color,
            'subcolor' => $trend_subcolor
        ];
    }

    private function top_metrics_teacher($where_majors_employee = "")
    {
        $year = date('Y');
        $last_year = $year - 1;

        $sql_base = "SELECT COUNT(DISTINCT employee_name) as total FROM employee 
                     JOIN position ON position.position_id = employee.employee_position_id 
                     WHERE employee_status = 1 AND LOWER(position_name) LIKE '%guru%' {$where_majors_employee}";

        $current = $this->db->query($sql_base)->row()->total;
        $this_year_count = $this->db->query($sql_base . " AND YEAR(employee_input_date) = {$year}")->row()->total;
        $last_year_count = $this->db->query($sql_base . " AND YEAR(employee_input_date) = {$last_year}")->row()->total;

        $diff = $this_year_count - $last_year_count;
        $trend_icon = $diff >= 0 ? '<i class="fa fa-arrow-up" aria-hidden="true"></i>' : '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
        $trend_subcolor = $diff >= 0 ? 'text-green' : 'text-red';
        $diff_abs = abs($diff);

        return [
            'title' => $this->lang->line('dash_total_teachers'),
            'icon' => 'fa-graduation-cap',
            'value' => number_format($current, 0, ',', '.'),
            'label' => $this->lang->line('dash_active'),
            'subtext' => $diff != 0 ? "{$trend_icon} {$diff_abs} {$this->lang->line('dash_FLY')}" : '',
            'color' => '#007bff',
            'subcolor' => $trend_subcolor
        ];
    }

    private function top_metrics_class($where_majors_class = "")
    {
        $current = $this->db->query("SELECT COUNT(class_id) as total FROM class WHERE 1 {$where_majors_class}")->row()->total;

        return [
            'title' => $this->lang->line('dash_total_classes'),
            'icon' => 'fa-building',
            'value' => number_format($current, 0, ',', '.'),
            'label' => $this->lang->line('dash_active'),
            'subtext' => null,
            'color' => '#6f42c1',
            'subcolor' => null
        ];
    }

    private function top_metrics_kas($where_majors_v_laporan_jurnal = "")
    {
        $period = $this->db->query("SELECT period_id,period_start,period_end FROM period WHERE period_status = 1")->row_array();
        if (!$period) {
            $periodStart = date('Y');
            $periodEnd = date('Y') + 1;
        } else {
            $periodStart = $period['period_start'];
            $periodEnd   = $period['period_end'];
        }

        $dateStart = "$periodStart-07-01";
        $dateEnd  = "$periodEnd-06-30";

        // Query current month vs last month logic
        $current_month = date('n');
        $current_year = date('Y');
        $last_month = $current_month - 1;
        $last_month_year = $current_year;

        if ($last_month == 0) {
            $last_month = 12;
            $last_month_year = $current_year - 1;
        }

        $query = $this->db->query(
            "SELECT 
                SUM(penerimaan - pengeluaran) AS total_kas,
                SUM(CASE WHEN MONTH(tanggal) = {$current_month} AND YEAR(tanggal) = {$current_year} THEN penerimaan - pengeluaran ELSE 0 END) AS saldo_bulan_ini,
                SUM(CASE WHEN MONTH(tanggal) = {$last_month} AND YEAR(tanggal) = {$last_month_year} THEN penerimaan - pengeluaran ELSE 0 END) AS saldo_bulan_lalu
            FROM v_laporan_jurnal 
            WHERE tanggal BETWEEN '$dateStart' AND '$dateEnd' {$where_majors_v_laporan_jurnal}"
        )->row_array();

        $total_kas = (float)($query['total_kas'] ?? 0);
        $bulan_ini = (float)($query['saldo_bulan_ini'] ?? 0);
        $bulan_lalu = (float)($query['saldo_bulan_lalu'] ?? 0);

        // Menghitung growth percentage
        if ($bulan_lalu == 0) {
            $growth_percentage = $bulan_ini > 0 ? 100 : 0;
            $diff = $bulan_ini;
        } else {
            $diff = $bulan_ini - $bulan_lalu;
            $growth_percentage = ($diff / abs($bulan_lalu)) * 100;
        }

        $trend_icon = $diff >= 0 ? '<i class="fa fa-arrow-up" aria-hidden="true"></i>' : '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
        $trend_color = $diff >= 0 ? '#fd7e14' : '#dc3545';
        $trend_subcolor = $diff >= 0 ? 'text-green' : 'text-red';
        $formatted_growth = number_format(abs($growth_percentage), 1, ',', '.');

        return [
            'title' => $this->lang->line('dash_school_cash_balance'),
            'icon' => 'fa-money',
            'value' => 'Rp ' . number_format($total_kas, 0, ',', '.'),
            'label' => $this->lang->line('dash_total_cash'),
            'subtext' => $diff != 0 ? "{$trend_icon} {$formatted_growth}% {$this->lang->line('dash_FLM')}" : '',
            'color' => '#fd7e14',
            'subcolor' => $trend_subcolor
        ];
    }

    private function top_metrics_collection_rate($selected_majors = 0)
    {
        $period = $this->db->query("SELECT period_id FROM period WHERE period_status = 1")->row_array();
        $period_id = $period ? $period['period_id'] : 1;
        $last_month_date = date('Y-m-d', strtotime('last day of previous month'));

        $where_student = $selected_majors != 0 ? "AND student.majors_majors_id = '{$selected_majors}'" : "";

        $sql_bulan = "SELECT 
            SUM(bulan.bulan_bill) as total_bill,
            SUM(CASE WHEN bulan.bulan_status = 1 THEN bulan.bulan_bill ELSE 0 END) as total_paid,
            SUM(CASE WHEN bulan.bulan_status = 1 AND bulan.bulan_date_pay <= '{$last_month_date}' THEN bulan.bulan_bill ELSE 0 END) as total_paid_last_month
        FROM bulan 
        JOIN payment ON payment.payment_id = bulan.payment_payment_id
        JOIN student ON student.student_id = bulan.student_student_id
        WHERE payment.period_period_id = '{$period_id}' {$where_student}";

        $bulan_data = $this->db->query($sql_bulan)->row_array();

        $where_bebas = $selected_majors != 0 ? "AND account.account_majors_id = '{$selected_majors}'" : "";

        $sql_bebas = "SELECT 
            SUM(bebas.bebas_bill - bebas.bebas_diskon) as total_bill,
            SUM(bebas.bebas_total_pay) as total_paid,
            (SELECT COALESCE(SUM(bp.bebas_pay_bill), 0) 
             FROM bebas_pay bp 
             JOIN bebas b ON b.bebas_id = bp.bebas_bebas_id 
             JOIN payment p ON p.payment_id = b.payment_payment_id 
             JOIN pos po ON po.pos_id = p.pos_pos_id
             JOIN account a ON a.account_id = po.account_account_id
             WHERE p.period_period_id = '{$period_id}' AND bp.bebas_pay_input_date <= '{$last_month_date}'
             " . ($selected_majors != 0 ? "AND a.account_majors_id = '{$selected_majors}'" : "") . ") as total_paid_last_month
        FROM bebas
        JOIN payment ON payment.payment_id = bebas.payment_payment_id
        JOIN pos ON pos.pos_id = payment.pos_pos_id
        JOIN account ON account.account_id = pos.account_account_id
        WHERE payment.period_period_id = '{$period_id}' {$where_bebas}";

        $bebas_data = $this->db->query($sql_bebas)->row_array();

        $total_bill = ($bulan_data['total_bill'] ?? 0) + ($bebas_data['total_bill'] ?? 0);
        $total_paid = ($bulan_data['total_paid'] ?? 0) + ($bebas_data['total_paid'] ?? 0);
        $total_paid_last = ($bulan_data['total_paid_last_month'] ?? 0) + ($bebas_data['total_paid_last_month'] ?? 0);

        $rate_current = $total_bill > 0 ? ($total_paid / $total_bill) * 100 : 0;
        $rate_last = $total_bill > 0 ? ($total_paid_last / $total_bill) * 100 : 0;
        $diff = $rate_current - $rate_last;

        $trend_icon = $diff >= 0 ? '<i class="fa fa-arrow-up" aria-hidden="true"></i>' : '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
        $trend_color = $diff >= 0 ? '#17a2b8' : '#dc3545';
        $trend_subcolor = $diff >= 0 ? 'text-green' : 'text-red';
        $formatted_diff = number_format(abs($diff), 2, ',', '.');
        $formatted_rate = number_format($rate_current, 2, ',', '.');

        return [
            'title' => $this->lang->line('dash_collection_rate'),
            'icon' => 'fa-calculator',
            'value' => "{$formatted_rate}%",
            'label' => $this->lang->line('dash_bill_paid'),
            'subtext' => $diff != 0 ? "{$trend_icon} {$formatted_diff}% {$this->lang->line('dash_FLM')}" : '',
            'color' => '#17a2b8',
            'subcolor' => $trend_subcolor
        ];
    }

    private function top_metrics_tabungan($where_majors_banking = "")
    {
        $last_month_end = date('Y-m-d', strtotime('last day of previous month'));

        $query = $this->db->query(
            "SELECT 
            SUM(b.banking_debit - b.banking_kredit) AS total_saldo,
            SUM(CASE WHEN b.banking_date <= '{$last_month_end}' THEN b.banking_debit - b.banking_kredit ELSE 0 END) AS saldo_bulan_lalu
            FROM banking b
            JOIN student s ON s.student_id = b.banking_student_id
            WHERE 1=1 {$where_majors_banking}"
        )->row_array();

        $total_saldo = (float)($query['total_saldo'] ?? 0);
        $saldo_bulan_lalu = (float)($query['saldo_bulan_lalu'] ?? 0);

        if ($saldo_bulan_lalu == 0) {
            $growth_percentage = $total_saldo > 0 ? 100 : 0;
            $diff = $total_saldo;
        } else {
            $diff = $total_saldo - $saldo_bulan_lalu;
            $growth_percentage = ($diff / abs($saldo_bulan_lalu)) * 100;
        }

        $trend_icon = $diff >= 0 ? '<i class="fa fa-arrow-up" aria-hidden="true"></i>' : '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
        $trend_color = $diff >= 0 ? '#007bff' : '#dc3545';
        $trend_subcolor = $diff >= 0 ? 'text-green' : 'text-red';
        $formatted_growth = number_format(abs($growth_percentage), 1, ',', '.');

        return [
            'title' => $this->lang->line('dash_student_savings'),
            'icon' => 'fa-credit-card',
            'value' => 'Rp ' . number_format($total_saldo, 0, ',', '.'),
            'label' => $this->lang->line('dash_total_savings'),
            'subtext' => $diff != 0 ? "{$trend_icon} {$formatted_growth}% {$this->lang->line('dash_FLM')}" : '',
            'color' => '#007bff',
            'subcolor' => $trend_subcolor
        ];
    }

    public function ajax_tagihan_bulan_ini()
    {
        $majors_id = sanitizeStrict($this->input->post('majors_id'));

        if (empty($majors_id)) {
            $majors_id = $this->session->userdata('umajorsid');
            if ($majors_id == 0 && $this->input->get('m')) {
                $majors_id = sanitizeStrict($this->input->get('m'));
            }
        }

        $result = $this->Dashboard_model->get_tagihan_bulan_ini($majors_id);

        header('Content-Type: application/json');
        echo json_encode(['data' => $result]);
        exit;
    }

    public function ajax_realisasi_akun()
    {
        $period_id   = sanitizeStrict($this->input->post('period_id'));
        $start_month = sanitizeStrict($this->input->post('start_month'));
        $end_month   = sanitizeStrict($this->input->post('end_month'));
        $majors_id   = sanitizeStrict($this->input->post('majors_id'));

        if (empty($majors_id)) {
            $majors_id = $this->session->userdata('umajorsid');
            if ($majors_id == 0 && $this->input->get('m')) {
                $majors_id = $this->input->get('m');
            }
        }

        $result = $this->Dashboard_model->get_realisasi_akun_dt($period_id, $start_month, $end_month, $majors_id);

        header('Content-Type: application/json');
        echo json_encode(['data' => $result]);
        exit;
    }

    public function ajax_detail_tabungan_siswa()
    {
        $student_id = sanitizeStrict($this->input->post('student_id'));
        if (empty($student_id)) {
            echo json_encode(['data' => [], 'student' => null]);
            exit;
        }

        $result = $this->Dashboard_model->get_detail_tabungan($student_id);

        if (!$result) {
            echo json_encode(['data' => [], 'student' => null]);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'data'            => $result['mutasi'],
            'student'         => $result['student'],
            'total_setoran'   => $result['total_setoran'],
            'total_penarikan' => $result['total_penarikan'],
            'saldo_akhir'     => $result['saldo_akhir']
        ]);
        exit;
    }

    public function export_realisasi_excel()
    {
        $period_id   = $this->input->get('period_id');
        $start_month = $this->input->get('start_month');
        $end_month   = $this->input->get('end_month');
        $majors_id   = $this->input->get('majors_id');

        if (empty($majors_id)) {
            $majors_id = $this->session->userdata('umajorsid');
        }

        $data = $this->Dashboard_model->get_realisasi_akun_dt($period_id, $start_month, $end_month, $majors_id);

        $filename = "Realisasi_Akun_Pembayaran_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");

        echo '<table border="1">';
        echo '<thead>';
        echo '<tr style="background-color: #f4f6f9;">';
        echo '<th>Unit</th>';
        echo '<th>Akun Pembayaran</th>';
        echo '<th>Tagihan</th>';
        echo '<th>Terbayar</th>';
        echo '<th>Belum Terbayar</th>';
        echo '<th>Pencapaian (%)</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if (!empty($data)) {
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['majors_short_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['unit']) . '</td>';
                echo '<td style="text-align: right;">' . number_format($row['tagihan_raw'], 0, ',', '.') . '</td>';
                echo '<td style="text-align: right;">' . number_format($row['terbayar_raw'], 0, ',', '.') . '</td>';
                echo '<td style="text-align: right;">' . number_format($row['belum_raw'], 0, ',', '.') . '</td>';
                echo '<td style="text-align: right;">' . number_format($row['pencapaian_raw'], 1, ',', '.') . '%</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6" style="text-align: center;">Tidak ada data untuk ditampilkan</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        exit;
    }

    public function ajax_ikhtisar_keuangan()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $majors = $this->input->post('majors');

        $where_majors = '';
        if (!empty($majors) && $majors != 0) {
            $where_majors = "AND idMajors = '$majors'";
        }

        $data = $this->get_ikhtisar_keuangan($month, $year, $where_majors);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function get_ikhtisar_keuangan($month, $year, $where_majors = "")
    {
        $period = $this->db->query("SELECT period_start, period_end FROM period WHERE period_status = 1 LIMIT 1")->row_array();
        if (!$period) {
            $period_date_start = date('Y') . '-07-01';
        } else {
            $period_date_start = $period['period_start'] . '-07-01';
        }

        $last_day = date('Y-m-t', mktime(0, 0, 0, (int)$month, 1, (int)$year));

        $query = $this->db->query("SELECT
            SUM(CASE WHEN MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year' THEN penerimaan ELSE 0 END) AS pemasukan_bulan,
            SUM(CASE WHEN MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year' THEN pengeluaran ELSE 0 END) AS pengeluaran_bulan,
            SUM(CASE WHEN tanggal <= '$last_day' THEN penerimaan - pengeluaran ELSE 0 END) AS saldo_kumulatif
            FROM v_laporan_jurnal
            WHERE tanggal >= '$period_date_start'
            {$where_majors}
        ")->row_array();

        $pemasukan   = (float)($query['pemasukan_bulan']  ?? 0);
        $pengeluaran = (float)($query['pengeluaran_bulan'] ?? 0);
        $saldo       = (float)($query['saldo_kumulatif']  ?? 0);

        // Doughnut: 2 slice (pemasukan vs pengeluaran bulan ini), center = saldo kumulatif
        $total_chart = $pemasukan + $pengeluaran;

        return [
            'total_display' => $this->format_short_amount($saldo),
            'pemasukan' => [
                'val'  => $pemasukan,
                'text' => number_format($pemasukan, 0, ',', '.'),
                'pct'  => ($total_chart > 0 ? number_format(($pemasukan / $total_chart) * 100, 1) : 0) . '%'
            ],
            'pengeluaran' => [
                'val'  => $pengeluaran,
                'text' => number_format($pengeluaran, 0, ',', '.'),
                'pct'  => ($total_chart > 0 ? number_format(($pengeluaran / $total_chart) * 100, 1) : 0) . '%'
            ],
            'saldo_akhir' => [
                'val'  => $saldo,
                'text' => number_format($saldo, 0, ',', '.'),
                'pct'  => '100%'
            ]
        ];
    }

    private function format_short_amount($amount)
    {
        if ($amount >= 1000000000000) {
            return rtrim(rtrim(number_format($amount / 1000000000000, 2, ',', '.'), '0'), ',') . ' ' . $this->lang->line('singkatan_triliun');
        } elseif ($amount >= 1000000000) {
            return rtrim(rtrim(number_format($amount / 1000000000, 2, ',', '.'), '0'), ',') . ' ' . $this->lang->line('singkatan_miliar');
        } elseif ($amount >= 1000000) {
            return rtrim(rtrim(number_format($amount / 1000000, 2, ',', '.'), '0'), ',') . ' ' . $this->lang->line('singkatan_juta');
        } elseif ($amount >= 1000) {
            return rtrim(rtrim(number_format($amount / 1000, 2, ',', '.'), '0'), ',') . ' ' . $this->lang->line('singkatan_ribu');
        }
        return number_format($amount, 0, ',', '.');
    }

    private function get_arus_kas_data($where_majors_v_laporan_jurnal = "")
    {
        $months = [];
        $month_names = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $current_first = date('Y-m-01');

        for ($i = 11; $i >= 0; $i--) {
            $time = strtotime("$current_first -$i month");
            $key = date('Y-m', $time);
            $m = (int)date('n', $time);
            $months[$key] = [
                'label' => $month_names[$m],
                'month' => $m,
                'pemasukan' => 0,
                'pengeluaran' => 0
            ];
        }

        $start_date = date('Y-m-01', strtotime("$current_first -11 month"));
        $end_date = date('Y-m-t');

        $sql = "SELECT 
                    DATE_FORMAT(tanggal, '%Y-%m') AS ym,
                    SUM(penerimaan) AS pemasukan,
                    SUM(pengeluaran) AS pengeluaran
                FROM v_laporan_jurnal
                WHERE tanggal >= '$start_date' AND tanggal <= '$end_date 23:59:59'
                $where_majors_v_laporan_jurnal
                GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
                ORDER BY ym ASC";

        $query = $this->db->query($sql)->result_array();
        foreach ($query as $row) {
            if (isset($months[$row['ym']])) {
                $months[$row['ym']]['pemasukan'] = (float)$row['pemasukan'];
                $months[$row['ym']]['pengeluaran'] = (float)$row['pengeluaran'];
            }
        }

        $all_months = array_values($months);
        $total_count = count($all_months);

        $result = [];
        foreach ([3, 6, 9, 12] as $k) {
            $slice = array_slice($all_months, max(0, $total_count - $k));
            $result[$k] = [
                'labels' => array_column($slice, 'label'),
                'pemasukan' => array_column($slice, 'pemasukan'),
                'pengeluaran' => array_column($slice, 'pengeluaran'),
            ];
        }

        return $result;
    }
}
