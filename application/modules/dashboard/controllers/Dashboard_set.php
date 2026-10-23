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

        // NOTES: Custom condition by majors on specific logic
        $where_majors_v_laporan_jurnal = "";
        $where_majors_student = "";
        $where_majors_employee = "";
        $where_majors_class = "";
        $where_majors_banking = "";
        if ($selected_majors != 0) {
            $where_majors_v_laporan_jurnal = "AND idMajors = '$selected_majors'";
            $where_majors_student = "AND majors_majors_id = '$selected_majors'";
            $where_majors_employee = "AND employee_majors_id = '$selected_majors'";
            $where_majors_class = "AND majors_majors_id = '$selected_majors'";
            $where_majors_banking = "AND s.majors_majors_id = '$selected_majors'";
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

        $setting_website = $this->Setting_model->get(array('name' => 'setting_website'));
        $data['setting_website'] = $setting_website['setting_value'] ? $setting_website['setting_value'] : 'javascript:void(0);';

        if ($hasDashboard) {
            foreach ($filter_majors as $row) {
                if ($row['majors_id'] == $selected_majors) {
                    $data['nama_unit'] = $row['majors_short_name'] . ' - ' . $school_name['setting_value'];
                }
            }

            $data['selected_majors'] = $selected_majors;
            $data['umajorsid'] = $umajorsid;

            // Top Metrics
            $data['top_metrics'] = [
                $this->top_metrics_student($where_majors_student),
                $this->top_metrics_teacher($where_majors_employee),
                $this->top_metrics_class($where_majors_class),
                $this->top_metrics_kas($where_majors_v_laporan_jurnal),
                $this->top_metrics_collection_rate($selected_majors),
                $this->top_metrics_tabungan($where_majors_banking)
            ];

            $setting_package = $this->db->query("SELECT s.setting_value, p.package_name FROM setting s LEFT JOIN package p ON s.setting_value = p.package_id WHERE s.setting_name = 'setting_package'")->row_array();
            $setting_date = $this->Setting_model->get(array('name' => 'setting_date'));
            $setting_end = $this->Setting_model->get(array('name' => 'setting_end'));

            $packageName = !empty($setting_package['package_name']) ? $setting_package['package_name'] : '-';
            $startDate = !empty($setting_date['setting_value']) ? $setting_date['setting_value'] : date('Y-m-d');
            $endDate = !empty($setting_end['setting_value']) ? $setting_end['setting_value'] : date('Y-m-d', strtotime('+1 year'));

            $tsStart = strtotime($startDate);
            $tsEnd = strtotime($endDate);
            $monthsDiff = round(($tsEnd - $tsStart) / (30 * 24 * 60 * 60));
            if ($monthsDiff >= 12) {
                $years = round($monthsDiff / 12);
                $durationStr = $years . ' ' . ($this->lang->line('dash_year') ?: 'Tahun');
            } else {
                $durationStr = max(1, $monthsDiff) . ' ' . ($this->lang->line('dash_month') ?: 'Bulan');
            }

            $today = time();
            $daysLeft = ceil(($tsEnd - $today) / (24 * 60 * 60));
            $nearestBillDate = !empty($setting_end['setting_value']) ? pretty_date($setting_end['setting_value'], 'd M Y', false) : '-';

            $data['packageName'] = $packageName;
            $data['durationStr'] = $durationStr;
            $data['nearestBillDate'] = $nearestBillDate;
            $data['daysLeft'] = $daysLeft;
            $sch_name_wa = $school_name['setting_value'];

            $whatsapp_cs = WHATSAPP_CS;
            $formatted_date = pretty_date($setting_end['setting_value'], 'd F Y', false);
            $wa_message = "*Halo Admin,*\n\n" . "Masa berlangganan aplikasi *Admin Sekolah {$sch_name_wa}* " . "saya akan segera berakhir pada *{$formatted_date}*.\n\n" . "Saya ingin memperpanjang langganan aplikasi " . "*Admin Sekolah {$sch_name_wa}*.\n\n" . "Mohon informasi lebih lanjut.";

            $whatsappUrl = 'https://wa.me/' . $whatsapp_cs . '?text=' . urlencode($wa_message);
            $data['whatsappUrl'] = $whatsappUrl;

            $data['showTableSubsHistory'] = true;

            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard';
        } else {
            $data['information'] = $this->Information_model->get(array('information_publish' => 1));

            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard_employees';
            $data['umajorsid'] = $umajorsid;
        }

        $this->load->view('manage/layout', $data);
    }
    private function _get_subscription_duration($start, $end, $duration = null)
    {
        if (!empty($duration)) {
            return $duration;
        }
        if (empty($start) || empty($end)) {
            return '12 ' . ($this->lang->line('dash_month') ?: 'Bulan');
        }
        $tsStart = strtotime($start);
        $tsEnd = strtotime($end);
        $monthsDiff = round(($tsEnd - $tsStart) / (30 * 24 * 60 * 60));
        if ($monthsDiff >= 12) {
            $years = round($monthsDiff / 12);
            return $years . ' ' . ($this->lang->line('dash_year') ?: 'Tahun');
        }
        return max(1, $monthsDiff) . ' ' . ($this->lang->line('dash_month') ?: 'Bulan');
    }


    private function _get_selected_majors()
    {
        $selected_majors = sanitizeStrict($this->input->get('m')) ?: 0;
        $umajorsid = $this->session->userdata('umajorsid');
        if ($umajorsid != 0) {
            $selected_majors = $umajorsid;
        }
        return $selected_majors;
    }

    public function ajax_finance_ikhtisar()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();
        $where_majors_v_laporan_jurnal = $selected_majors != 0 ? "AND idMajors = '$selected_majors'" : "";
        $ikhtisar_month = date('n');
        $ikhtisar_year  = date('Y');
        $data = $this->get_ikhtisar_keuangan($ikhtisar_month, $ikhtisar_year, $where_majors_v_laporan_jurnal);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_finance_tagihan()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();
        $all_tagihan = $this->Dashboard_model->get_tagihan_bulan_ini($selected_majors);
        $data['tagihan_bulan_ini'] = array_slice($all_tagihan, 0, 4);
        $data['has_more_tagihan']  = count($all_tagihan) > 4;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_finance_arus_kas()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();
        $where_majors_v_laporan_jurnal = $selected_majors != 0 ? "AND idMajors = '$selected_majors'" : "";
        $data = $this->get_arus_kas_data($where_majors_v_laporan_jurnal);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_finance_tab()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();

        $where_majors_v_laporan_jurnal = $selected_majors != 0 ? "AND idMajors = '$selected_majors'" : "";
        $where_majors_banking = $selected_majors != 0 ? "AND s.majors_majors_id = '$selected_majors'" : "";

        $data['selected_majors'] = $selected_majors;
        $data['months_list'] = $this->db->query("SELECT month_id, month_name FROM month ORDER BY month_id ASC")->result_array();
        $data['periods_list'] = $this->Period_model->get();

        // Ikhtisar Keuangan
        $ikhtisar_month = date('n');
        $ikhtisar_year  = date('Y');
        $period = $this->db->query("SELECT period_start, period_end FROM period WHERE period_status = 1 LIMIT 1")->row_array();
        $data['ikhtisar_period'] = $period;
        $data['ikhtisar_month']  = $ikhtisar_month;
        $data['ikhtisar_year']   = $ikhtisar_year;
        $data['ikhtisar_doughnut'] = $this->get_ikhtisar_keuangan($ikhtisar_month, $ikhtisar_year, $where_majors_v_laporan_jurnal);

        // arus kas
        $data['arus_kas'] = $this->get_arus_kas_data($where_majors_v_laporan_jurnal);

        // tagihan bulan ini
        $all_tagihan = $this->Dashboard_model->get_tagihan_bulan_ini($selected_majors);
        $data['tagihan_bulan_ini'] = array_slice($all_tagihan, 0, 4);
        $data['has_more_tagihan']  = count($all_tagihan) > 4;

        // chart spp
        $data['spp_chart'] = $this->Dashboard_model->get_spp_chart_data($selected_majors);
        $spp_data_count = count($data['spp_chart']['labels'] ?? []);

        $data['spp_chart_col_class'] = ($spp_data_count > 6) ? 'col-lg-12 col-md-12 col-sm-12' : 'col-lg-4 col-md-12 col-sm-12';
        $data['other_widget_col_class'] = ($spp_data_count > 6) ? 'col-lg-6 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12';

        if ($selected_majors != 0) {
            $majors_short = $this->mapping_singkatan_unit($selected_majors, true);
            $data['spp_chart_title'] = $this->lang->line('dash_spp_chart_title') ? $this->lang->line('dash_spp_chart_title') . ' ' . $majors_short : 'Grafik Pembayaran (SPP) ' . $majors_short;
        } else {
            $data['spp_chart_title'] = $this->lang->line('dash_spp_chart_title') ? $this->lang->line('dash_spp_chart_title') . ' Per Unit' : 'Grafik Pembayaran (SPP) Per Unit';
        }

        // transaksi terbaru dari ltrx
        $data['recent_transactions'] = $this->Dashboard_model->get_recent_transactions($selected_majors, 5);

        // top 5 tabungan
        $data['top_5_tabungan'] = $this->Dashboard_model->get_top_5_tabungan($selected_majors);
        $data['show_unit_tabungan'] = (empty($selected_majors) || $selected_majors == 0);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_finance_spp()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();
        $data['spp_chart'] = $this->Dashboard_model->get_spp_chart_data($selected_majors);
        $spp_data_count = count($data['spp_chart']['labels'] ?? []);

        $data['spp_chart_col_class'] = ($spp_data_count > 6) ? 'col-lg-12 col-md-12 col-sm-12' : 'col-lg-4 col-md-12 col-sm-12';
        $data['other_widget_col_class'] = ($spp_data_count > 6) ? 'col-lg-6 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12';

        if ($selected_majors != 0) {
            $majors_short = $this->mapping_singkatan_unit($selected_majors, true);
            $data['spp_chart_title'] = $this->lang->line('dash_spp_chart_title') ? $this->lang->line('dash_spp_chart_title') . ' ' . $majors_short : 'Grafik Pembayaran (SPP) ' . $majors_short;
        } else {
            $data['spp_chart_title'] = $this->lang->line('dash_spp_chart_title') ? $this->lang->line('dash_spp_chart_title') . ' Per Unit' : 'Grafik Pembayaran (SPP) Per Unit';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_finance_recent_tr()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();
        $data = $this->Dashboard_model->get_recent_transactions($selected_majors, 5);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_finance_top_tabungan()
    {
        session_write_close();
        $selected_majors = $this->_get_selected_majors();
        $data = $this->Dashboard_model->get_top_5_tabungan($selected_majors);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_student_tab()
    {
        session_write_close();
        $data = [];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_student_jumlah_siswa()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));

        $labels = [];
        $data_l = [];
        $data_p = [];
        $data_total = [];

        if (empty($majors_id)) {
            $title = $this->lang->line('dash_student_per_unit') ?: 'Jumlah Siswa Per Unit';

            $sql = "SELECT 
                        m.majors_id, 
                        m.majors_short_name, 
                        SUM(CASE WHEN s.student_gender = 'L' THEN 1 ELSE 0 END) AS count_l, 
                        SUM(CASE WHEN s.student_gender = 'P' THEN 1 ELSE 0 END) AS count_p, 
                        COUNT(s.student_id) AS count_total 
                    FROM majors m 
                    LEFT JOIN student s ON s.majors_majors_id = m.majors_id AND s.student_status = '1' 
                    WHERE m.majors_status = '1' 
                    GROUP BY m.majors_id, m.majors_short_name 
                    ORDER BY m.majors_id ASC";
            $query = $this->db->query($sql)->result_array();

            foreach ($query as $row) {
                $labels[] = $row['majors_short_name'];
                $data_l[] = (int)$row['count_l'];
                $data_p[] = (int)$row['count_p'];
                $data_total[] = (int)$row['count_total'];
            }
        } else {
            $title = $this->lang->line('dash_student_per_class') ?: 'Jumlah Siswa Per Kelas';
            $mid = (int)$majors_id;

            $sql = "SELECT 
                        c.class_id, 
                        c.class_name, 
                        SUM(CASE WHEN s.student_gender = 'L' THEN 1 ELSE 0 END) AS count_l, 
                        SUM(CASE WHEN s.student_gender = 'P' THEN 1 ELSE 0 END) AS count_p, 
                        COUNT(s.student_id) AS count_total 
                    FROM class c 
                    LEFT JOIN student s ON s.class_class_id = c.class_id AND s.student_status = '1' AND s.majors_majors_id = c.majors_majors_id
                    WHERE c.majors_majors_id = '$mid' 
                    GROUP BY c.class_id, c.class_name 
                    ORDER BY c.class_name ASC";
            $query = $this->db->query($sql)->result_array();

            foreach ($query as $row) {
                $labels[] = $row['class_name'];
                $data_l[] = (int)$row['count_l'];
                $data_p[] = (int)$row['count_p'];
                $data_total[] = (int)$row['count_total'];
            }
        }

        $data = [
            'title'      => $title,
            'labels'     => $labels,
            'data_l'     => $data_l,
            'data_p'     => $data_p,
            'data_total' => $data_total,
            'lang_l'     => $this->lang->line('dash_male') ?: 'Laki-laki',
            'lang_p'     => $this->lang->line('dash_female') ?: 'Perempuan',
            'lang_total' => $this->lang->line('dash_total') ?: 'Jumlah'
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_student_status()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));

        $where = "";
        if (!empty($majors_id)) {
            $where = "WHERE s.majors_majors_id = " . (int)$majors_id;
        }

        $sql = "SELECT 
                    s.student_status,
                    COUNT(s.student_id) as total
                FROM student s
                {$where}
                GROUP BY s.student_status";

        $query = $this->db->query($sql)->result_array();

        $status_map = [
            1 => ['label' => $this->lang->line('dash_status_active') ?: 'Aktif', 'color' => '#00a65a'],
            2 => ['label' => $this->lang->line('dash_status_passed') ?: 'Lulus/Alumni', 'color' => '#3c8dbc'],
            3 => ['label' => $this->lang->line('dash_status_moved') ?: 'Pindah/Mutasi', 'color' => '#f39c12'],
            0 => ['label' => $this->lang->line('dash_status_inactive') ?: 'Tidak Aktif', 'color' => '#d2d6de'],
        ];

        $counts = [];
        foreach ($query as $row) {
            $counts[(int)$row['student_status']] = (int)$row['total'];
        }

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($status_map as $st => $info) {
            $val = isset($counts[$st]) ? $counts[$st] : 0;
            if ($val > 0) {
                $labels[] = $info['label'];
                $data[] = $val;
                $colors[] = $info['color'];
            }
        }

        $res = [
            'labels' => $labels,
            'data'   => $data,
            'colors' => $colors
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($res));
    }

    public function ajax_student_classes()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));
        $classes = [];
        if (!empty($majors_id)) {
            $mid = (int)$majors_id;
            $classes = $this->db->query("SELECT class_id, class_name FROM class WHERE majors_majors_id = '$mid' ORDER BY class_name ASC")->result_array();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($classes));
    }

    public function ajax_student_kehadiran()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));
        $class_id  = sanitizeStrict($this->input->post('class_id'));
        $today = date('Y-m-d');

        $base_title = $this->lang->line('dash_student_attendance') ?: 'Kehadiran Siswa Hari Ini';
        $unit_label = $this->lang->line('dash_all_units') ?: 'Semua Unit';
        $class_label = $this->lang->line('dash_all_classes') ?: 'Semua Kelas';

        if (!empty($majors_id)) {
            $mid = (int)$majors_id;
            $majors_row = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$mid'")->row_array();
            if ($majors_row && !empty($majors_row['majors_short_name'])) {
                $unit_label = $majors_row['majors_short_name'];
            }
        }

        if (!empty($class_id) && $class_id !== 'all') {
            $cid = (int)$class_id;
            $class_row = $this->db->query("SELECT class_name FROM class WHERE class_id = '$cid'")->row_array();
            if ($class_row && !empty($class_row['class_name'])) {
                $class_label = $class_row['class_name'];
            }
        }

        $title = "$base_title - $unit_label - $class_label";

        if ($this->db->table_exists('presensi_student')) {
            $where = "WHERE ps.date = '$today'";
            if (!empty($majors_id)) {
                $mid = (int)$majors_id;
                $where .= " AND c.majors_majors_id = '$mid'";
            }
            if (!empty($class_id) && $class_id !== 'all') {
                $cid = (int)$class_id;
                $where .= " AND ps.id_class = '$cid'";
            }

            $sql = "SELECT
                        COALESCE(SUM(CASE WHEN ps.jenis_absen = 'DATANG' THEN 1 ELSE 0 END), 0) AS hadir,
                        COALESCE(SUM(CASE WHEN ps.jenis_absen = 'IZIN' THEN 1 ELSE 0 END), 0) AS izin,
                        COALESCE(SUM(CASE WHEN ps.jenis_absen = 'SAKIT' THEN 1 ELSE 0 END), 0) AS sakit,
                        COALESCE(SUM(CASE WHEN ps.jenis_absen = 'ALPHA' THEN 1 ELSE 0 END), 0) AS alpa
                    FROM presensi_student ps
                    LEFT JOIN class c ON c.class_id = ps.id_class
                    {$where}";

            $row = $this->db->query($sql)->row_array();
        } else if ($this->db->table_exists('presensi_harian')) {
            $where = "WHERE ph.presensi_harian_date = '$today'";
            if (!empty($majors_id)) {
                $mid = (int)$majors_id;
                $where .= " AND c.majors_majors_id = '$mid'";
            }
            if (!empty($class_id) && $class_id !== 'all') {
                $cid = (int)$class_id;
                $where .= " AND ph.presensi_harian_class_id = '$cid'";
            }

            $sql = "SELECT
                        COALESCE(SUM(CASE WHEN ph.presensi_harian_status = 'H' THEN 1 ELSE 0 END), 0) AS hadir,
                        COALESCE(SUM(CASE WHEN ph.presensi_harian_status = 'I' THEN 1 ELSE 0 END), 0) AS izin,
                        COALESCE(SUM(CASE WHEN ph.presensi_harian_status = 'S' THEN 1 ELSE 0 END), 0) AS sakit,
                        COALESCE(SUM(CASE WHEN ph.presensi_harian_status = 'A' THEN 1 ELSE 0 END), 0) AS alpa
                    FROM presensi_harian ph
                    LEFT JOIN class c ON c.class_id = ph.presensi_harian_class_id
                    {$where}";

            $row = $this->db->query($sql)->row_array();
        } else {
            $row = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0];
        }

        $data = [
            'title' => $title,
            'labels' => [
                $this->lang->line('dash_attendance_present') ?: 'Hadir',
                $this->lang->line('dash_attendance_permit') ?: 'Izin',
                $this->lang->line('dash_attendance_sick') ?: 'Sakit',
                $this->lang->line('dash_attendance_absent') ?: 'Alpha'
            ],
            'data' => [
                (int)($row['hadir'] ?? 0),
                (int)($row['izin'] ?? 0),
                (int)($row['sakit'] ?? 0),
                (int)($row['alpa'] ?? 0)
            ],
            'colors' => ['#00a65a', '#3c8dbc', '#f39c12', '#f56954']
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_employee_jumlah()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));

        $labels      = [];
        $data_l      = [];
        $data_p      = [];
        $data_total  = [];

        if (empty($majors_id)) {
            $title = $this->lang->line('dash_employee_per_unit') ?: 'Jumlah Pegawai Per Unit';

            $sql = "SELECT
                        m.majors_id,
                        m.majors_short_name,
                        SUM(CASE WHEN e.employee_gender = 'L' THEN 1 ELSE 0 END) AS count_l,
                        SUM(CASE WHEN e.employee_gender = 'P' THEN 1 ELSE 0 END) AS count_p,
                        COUNT(e.employee_id) AS count_total
                    FROM majors m
                    LEFT JOIN employee e ON e.employee_majors_id = m.majors_id AND e.employee_status = 1
                    WHERE m.majors_status = 1
                    GROUP BY m.majors_id, m.majors_short_name
                    ORDER BY m.majors_id ASC";

            $query = $this->db->query($sql)->result_array();
            foreach ($query as $row) {
                $labels[]     = $row['majors_short_name'];
                $data_l[]     = (int)$row['count_l'];
                $data_p[]     = (int)$row['count_p'];
                $data_total[] = (int)$row['count_total'];
            }
        } else {
            $title = $this->lang->line('dash_employee_per_position') ?: 'Jumlah Pegawai Per Jabatan';
            $mid   = (int)$majors_id;

            $sql = "SELECT
                        p.position_id,
                        p.position_name,
                        SUM(CASE WHEN e.employee_gender = 'L' THEN 1 ELSE 0 END) AS count_l,
                        SUM(CASE WHEN e.employee_gender = 'P' THEN 1 ELSE 0 END) AS count_p,
                        COUNT(e.employee_id) AS count_total
                    FROM position p
                    JOIN employee e ON e.employee_position_id = p.position_id AND e.employee_status = 1 AND e.employee_majors_id = '$mid'
                    WHERE p.position_majors_id = '$mid'
                    GROUP BY p.position_id, p.position_name
                    ORDER BY count_total DESC, p.position_name ASC";

            $query = $this->db->query($sql)->result_array();
            foreach ($query as $row) {
                $labels[]     = $row['position_name'];
                $data_l[]     = (int)$row['count_l'];
                $data_p[]     = (int)$row['count_p'];
                $data_total[] = (int)$row['count_total'];
            }
        }

        $data = [
            'title'      => $title,
            'labels'     => $labels,
            'data_l'     => $data_l,
            'data_p'     => $data_p,
            'data_total' => $data_total,
            'lang_l'     => $this->lang->line('dash_male') ?: 'Laki-laki',
            'lang_p'     => $this->lang->line('dash_female') ?: 'Perempuan',
            'lang_total' => $this->lang->line('dash_total') ?: 'Jumlah',
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_employee_status()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));

        $where_majors = '';
        if (!empty($majors_id)) {
            $mid = (int)$majors_id;
            $where_majors = "AND e.employee_majors_id = '$mid'";
        }

        $sql = "SELECT
                    e.employee_category,
                    COUNT(e.employee_id) AS total
                FROM employee e
                WHERE e.employee_status = 1
                AND e.employee_category IN (1, 2)
                {$where_majors}
                GROUP BY e.employee_category";

        $query  = $this->db->query($sql)->result_array();
        $counts = [];
        foreach ($query as $row) {
            $counts[(int)$row['employee_category']] = (int)$row['total'];
        }

        $category_map = [
            1 => ['label' => $this->lang->line('dash_employee_permanent') ?: 'Pegawai Tetap',     'color' => '#00a65a'],
            2 => ['label' => $this->lang->line('dash_employee_non_permanent') ?: 'Pegawai Tidak Tetap', 'color' => '#ffec59'],
        ];

        $labels = [];
        $data   = [];
        $colors = [];
        foreach ($category_map as $cat => $info) {
            $val = isset($counts[$cat]) ? $counts[$cat] : 0;
            if ($val > 0) {
                $labels[] = $info['label'];
                $data[]   = $val;
                $colors[] = $info['color'];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode([
            'labels' => $labels,
            'data'   => $data,
            'colors' => $colors,
        ]));
    }

    public function ajax_employee_kehadiran()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));
        $today = date('Y-m-d');

        $where = "WHERE da.tanggal = " . $this->db->escape($today);
        if (!empty($majors_id)) {
            $where .= " AND e.employee_majors_id = " . (int)$majors_id;
        }

        $sql_metrics = "
            SELECT 
                COALESCE(SUM(CASE WHEN da.jenis_absen = 'DATANG' THEN 1 ELSE 0 END), 0) AS datang,
                COALESCE(SUM(CASE WHEN da.jenis_absen = 'IJIN' THEN 1 ELSE 0 END), 0) AS ijin,
                COALESCE(SUM(CASE WHEN da.jenis_absen = 'PULANG' THEN 1 ELSE 0 END), 0) AS pulang
            FROM data_absensi da
            JOIN employee e ON e.employee_id = da.id_pegawai
            {$where}
        ";
        $metrics = $this->db->query($sql_metrics)->row_array();

        $sql_list = "
            SELECT 
                da.id,
                e.employee_nip,
                e.employee_name,
                p.position_name,
                da.jenis_absen,
                da.statushadir,
                da.time,
                da.catatan_absen
            FROM data_absensi da
            JOIN employee e ON e.employee_id = da.id_pegawai
            LEFT JOIN position p ON p.position_id = e.employee_position_id
            {$where}
            ORDER BY da.id DESC
        ";
        $list = $this->db->query($sql_list)->result_array();

        $data = [
            'metrics' => [
                'datang' => (int)($metrics['datang'] ?? 0),
                'ijin'   => (int)($metrics['ijin'] ?? 0),
                'pulang' => (int)($metrics['pulang'] ?? 0)
            ],
            'list' => $list
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_employee_tab()
    {
        session_write_close();
        $data = [];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_academic_aktivitas()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));
        if (empty($majors_id)) {
            $majors_id = $this->session->userdata('umajorsid');
            if ($majors_id == 0 && $this->input->get('m')) {
                $majors_id = sanitizeStrict($this->input->get('m'));
            }
        }

        $majors_name = '';
        if (!empty($majors_id) && $majors_id != 0) {
            $majors_row = $this->db->query("SELECT majors_name, majors_short_name FROM majors WHERE majors_id = ? AND majors_status = 1", [(int)$majors_id])->row_array();
            if ($majors_row) {
                $majors_name = $majors_row['majors_name'] ? $majors_row['majors_name'] : $majors_row['majors_short_name'];
            }
        }

        $title = $this->lang->line('dash_today_activity') ?: 'Aktivitas Hari Ini';
        if (!empty($majors_name)) {
            $title .= ' - ' . $majors_name;
        } else {
            $title .= ' - ' . ($this->lang->line('dash_all_units') ?: 'Semua Unit');
        }

        $aktivitas = [];

        // 1. Presensi Siswa
        $where_student = "";
        if (!empty($majors_id) && $majors_id != 0) {
            $where_student = " AND s.majors_majors_id = " . (int)$majors_id;
        }
        $presensi_query = $this->db->query("
            SELECT COUNT(DISTINCT ps.id_student) as total, MAX(ps.time) as last_time 
            FROM presensi_student ps
            JOIN student s ON s.student_id = ps.id_student
            WHERE ps.date = CURRENT_DATE() AND ps.jenis_absen = 'DATANG' {$where_student}
        ")->row_array();

        $presensi_count = (int)($presensi_query['total'] ?? 0);
        $presensi_time = !empty($presensi_query['last_time']) ? date('H:i', strtotime($presensi_query['last_time'])) : '-';

        $aktivitas[] = [
            'icon' => 'fa-users bg-green',
            'title' => $this->lang->line('dash_act_presensi_siswa') ?: 'Presensi Siswa',
            'desc' => sprintf($this->lang->line('dash_act_presensi_siswa_desc') ?: '%s siswa hadir hari ini', $presensi_count),
            'time' => $presensi_time,
            'count' => $presensi_count
        ];

        // 2. Jurnal Mengajar
        $where_emp = "";
        if (!empty($majors_id) && $majors_id != 0) {
            $where_emp = " AND e.employee_majors_id = " . (int)$majors_id;
        }
        $jurnal_query = $this->db->query("
            SELECT COUNT(DISTINCT t.teaching_employee_id) as total, MAX(TIME(t.teaching_date_create)) as last_time
            FROM teaching t
            JOIN employee e ON e.employee_id = t.teaching_employee_id
            WHERE DATE(t.teaching_date_create) = CURRENT_DATE() {$where_emp}
        ")->row_array();

        $jurnal_count = (int)($jurnal_query['total'] ?? 0);
        $jurnal_time = !empty($jurnal_query['last_time']) ? date('H:i', strtotime($jurnal_query['last_time'])) : '-';

        $aktivitas[] = [
            'icon' => 'fa-book bg-blue',
            'title' => $this->lang->line('dash_act_jurnal_mengajar') ?: 'Jurnal Mengajar',
            'desc' => sprintf($this->lang->line('dash_act_jurnal_mengajar_desc') ?: '%s guru mengisi jurnal', $jurnal_count),
            'time' => $jurnal_time,
            'count' => $jurnal_count
        ];

        // 3. Pembayaran Diterima
        $bebas_pay_query = $this->db->query("
            SELECT COUNT(bp.bebas_pay_id) as total, MAX(bp.bebas_pay_last_update) as last_time
            FROM bebas_pay bp
            JOIN bebas b ON b.bebas_id = bp.bebas_bebas_id
            JOIN student s ON s.student_id = b.student_student_id
            WHERE bp.bebas_pay_input_date = CURRENT_DATE() {$where_student}
        ")->row_array();

        $bulan_pay_query = $this->db->query("
            SELECT COUNT(bl.bulan_id) as total, MAX(bl.bulan_last_update) as last_time
            FROM bulan bl
            JOIN student s ON s.student_id = bl.student_student_id
            WHERE bl.bulan_date_pay = CURRENT_DATE() AND bl.bulan_status = 1 {$where_student}
        ")->row_array();

        $pembayaran_count = (int)($bebas_pay_query['total'] ?? 0) + (int)($bulan_pay_query['total'] ?? 0);
        $max_time_pembayaran = max($bebas_pay_query['last_time'] ?? '', $bulan_pay_query['last_time'] ?? '');
        $pembayaran_time = !empty($max_time_pembayaran) ? date('H:i', strtotime($max_time_pembayaran)) : '-';

        $aktivitas[] = [
            'icon' => 'fa-money bg-yellow',
            'title' => $this->lang->line('dash_act_pembayaran_diterima') ?: 'Pembayaran Diterima',
            'desc' => sprintf($this->lang->line('dash_act_pembayaran_received_desc') ?: '%s transaksi pembayaran', $pembayaran_count),
            'time' => $pembayaran_time,
            'count' => $pembayaran_count
        ];


        // 4. Informasi Baru
        $info_query = $this->db->query("
            SELECT information_title, information_input_date
            FROM information
            WHERE information_publish = 1 AND DATE(information_input_date) = CURRENT_DATE()
            ORDER BY information_input_date DESC LIMIT 1
        ")->row_array();

        if (!$info_query) {
            $info_query = $this->db->query("
                SELECT information_title, information_input_date
                FROM information
                WHERE information_publish = 1
                ORDER BY information_input_date DESC LIMIT 1
            ")->row_array();
        }

        $info_title = $info_query['information_title'] ?? ($this->lang->line('dash_act_no_informasi') ?: 'Tidak ada informasi baru hari ini');
        $info_time = !empty($info_query['information_input_date']) ? date('H:i', strtotime($info_query['information_input_date'])) : '-';
        $info_count = $info_query ? 1 : 0;

        $aktivitas[] = [
            'icon' => 'fa-bullhorn bg-red',
            'title' => $this->lang->line('dash_act_informasi_baru') ?: 'Informasi Baru',
            'desc' => $info_title,
            'time' => $info_time,
            'count' => $info_count
        ];

        // 5. Perizinan Siswa
        $where_izin = "";
        if (!empty($majors_id) && $majors_id != 0) {
            $where_izin = " AND (pi.pengajuan_izin_majors_id = " . (int)$majors_id . " OR s.majors_majors_id = " . (int)$majors_id . ")";
        }
        $izin_query = $this->db->query("
            SELECT COUNT(pi.pengajuan_izin_id) as total, MAX(pi.pengajuan_izin_created_at) as last_time
            FROM pengajuan_izin pi
            JOIN student s ON s.student_id = pi.pengajuan_izin_student_id
            WHERE (pi.pengajuan_izin_date = CURRENT_DATE() OR DATE(pi.pengajuan_izin_created_at) = CURRENT_DATE()) {$where_izin}
        ")->row_array();

        $izin_count = (int)($izin_query['total'] ?? 0);
        $izin_time = !empty($izin_query['last_time']) ? date('H:i', strtotime($izin_query['last_time'])) : '-';

        $aktivitas[] = [
            'icon' => 'fa-file-text-o bg-blue',
            'title' => $this->lang->line('dash_act_perizinan_siswa') ?: 'Perizinan Siswa',
            'desc' => sprintf($this->lang->line('dash_act_perizinan_siswa_desc') ?: '%s pengajuan izin', $izin_count),
            'time' => $izin_time,
            'count' => $izin_count
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode([
            'title' => $title,
            'data' => $aktivitas
        ]));
    }

    public function ajax_academic_informasi()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));
        $title = $this->lang->line('dash_information_title') ?: 'Informasi';

        $where_unit = "";
        if (!empty($majors_id) && $majors_id != 0) {
            $majors = $this->db->query("SELECT majors_name, sekolah_id FROM majors WHERE majors_id = ?", array((int)$majors_id))->row_array();
            $majors_name = !empty($majors['majors_name']) ? $majors['majors_name'] : '';
            $sekolah_id = !empty($majors['sekolah_id']) ? $majors['sekolah_id'] : '';
            $title .= $majors_name ? ' - ' . $majors_name : '';
            if ($sekolah_id) {
                $where_unit = " AND (sekolah_id = " . $this->db->escape($sekolah_id) . " OR sekolah_id IS NULL OR sekolah_id = '' OR sekolah_id = '0')";
            }
        } else {
            $title .= ' - ' . ($this->lang->line('dash_all_units') ?: 'Semua Unit');
        }

        $query = $this->db->query("
            SELECT information_id, information_title, information_desc, information_img, information_publish, information_input_date
            FROM information
            WHERE information_publish = 1 {$where_unit}
            ORDER BY information_input_date DESC, information_id DESC
            LIMIT 9
        ")->result_array();

        $data = [];
        foreach ($query as $row) {
            $title_text = $row['information_title'];
            $desc_text = strip_tags($row['information_desc'] ?? '');
            $date_text = !empty($row['information_input_date']) ? date('d M Y', strtotime($row['information_input_date'])) : '-';

            $img_path = !empty($row['information_img']) ? FCPATH . 'uploads/information/' . $row['information_img'] : '';
            if (!empty($row['information_img']) && file_exists($img_path)) {
                $img_url = base_url('uploads/information/' . $row['information_img']);
            } else {
                $img_url = media_url('img/missing.png');
            }

            $is_important = (mb_stripos($title_text, 'penting') !== false || mb_stripos($title_text, 'libur') !== false || mb_stripos($desc_text, 'penting') !== false);
            if ($is_important) {
                $type = $this->lang->line('dash_info_type_important') ?: 'Penting';
                $label = 'text-red';
                $border = 'border-color: red;';
                $icon = 'fa-bullhorn';
            } else {
                $type = $this->lang->line('dash_info_type_info') ?: 'Info';
                $label = 'text-blue';
                $border = 'border-color: blue;';
                $icon = 'fa-info-circle';
            }

            $data[] = [
                'type' => $type,
                'label' => $label,
                'border' => $border,
                'title' => $title_text,
                'desc' => $desc_text,
                'date' => $date_text,
                'icon' => $icon,
                'img' => $img_url
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode([
            'title' => $title,
            'data' => $data
        ]));
    }


    public function ajax_academic_tab()
    {
        session_write_close();
        $data = [];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function ajax_academic_jadwal()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->post('majors_id'));
        $title_prefix = $this->lang->line('dash_today_schedule') ?: 'Jadwal Hari Ini';

        if (!empty($majors_id) && $majors_id != 0) {
            $majors = $this->db->query("SELECT majors_name FROM majors WHERE majors_id = ?", array((int)$majors_id))->row_array();
            $majors_name = !empty($majors['majors_name']) ? $majors['majors_name'] : '';
            $title = $title_prefix . ($majors_name ? ' - ' . $majors_name : '');
        } else {
            $title = $title_prefix . ' - ' . ($this->lang->line('dash_all_units') ?: 'Semua Unit');
        }

        $today_name = pretty_date(date('Y-m-d'), 'l', false);

        $where_unit = "";
        if (!empty($majors_id) && $majors_id != 0) {
            $where_unit = " AND c.majors_majors_id = " . (int)$majors_id;
        }

        $query = $this->db->query("
            SELECT s.*, l.lesson_name, c.class_name, jp.jam_pelajaran_start, jp.jam_pelajaran_end,
                   e.employee_name, m.majors_name, m.majors_short_name
            FROM schedule s
            JOIN lesson l ON l.lesson_id = s.schedule_lesson_id
            JOIN class c ON c.class_id = s.schedule_class_id
            JOIN jam_pelajaran jp ON jp.jam_pelajaran_id = s.schedule_jampel
            JOIN day d ON d.day_id = s.schedule_day
            JOIN semester sem ON sem.semester_id = s.schedule_semester_id
            JOIN period p ON p.period_id = sem.semester_period_id
            LEFT JOIN employee e ON e.employee_id = l.lesson_teacher
            LEFT JOIN majors m ON m.majors_id = c.majors_majors_id
            WHERE d.day_name = " . $this->db->escape($today_name) . "
              AND sem.semester_status = 1
              AND p.period_status = 1
              {$where_unit}
            ORDER BY jp.jam_pelajaran_start ASC, c.class_name ASC
            LIMIT 15
        ")->result_array();

        $colors = ['bg-green', 'bg-blue', 'bg-yellow', 'bg-purple', 'bg-red', 'bg-teal', 'bg-maroon'];
        $data = [];

        if (!empty($query)) {
            foreach ($query as $i => $row) {
                $start_time = !empty($row['jam_pelajaran_start']) ? date('H:i', strtotime($row['jam_pelajaran_start'])) : '';
                $end_time = !empty($row['jam_pelajaran_end']) ? date('H:i', strtotime($row['jam_pelajaran_end'])) : '';
                $time_str = ($start_time && $end_time) ? "{$start_time} - {$end_time}" : (!empty($row['schedule_time']) ? $row['schedule_time'] : '-');

                $lesson_title = $row['lesson_name'];
                if (empty($majors_id) || $majors_id == 0) {
                    $prefix = !empty($row['majors_short_name']) ? '[' . $row['majors_short_name'] . '] ' : '';
                    $item_title = $prefix . $lesson_title;
                } else {
                    $item_title = $lesson_title;
                }

                $desc = $row['class_name'] . (!empty($row['employee_name']) ? ' | ' . $row['employee_name'] : '');
                $color = $colors[$i % count($colors)];

                $data[] = [
                    'time' => $time_str,
                    'title' => $item_title,
                    'desc' => $desc,
                    'color' => $color
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode([
            'title' => $title,
            'data' => $data
        ]));
    }

    public function ajax_academic_calendar()
    {
        session_write_close();
        $majors_id = sanitizeStrict($this->input->get_post('majors_id'));
        $start = sanitizeStrict($this->input->get_post('start'));
        $end = sanitizeStrict($this->input->get_post('end'));

        if (empty($start)) {
            $start = date('Y-m-01');
        }
        if (empty($end)) {
            $end = date('Y-m-t');
        }

        $events = [];

        // 1. Libur / Holiday events
        $holiday_query = $this->db->query("
            SELECT * FROM holiday 
            WHERE date >= ? AND date <= ?
        ", array($start, $end))->result_array();

        if (!empty($holiday_query)) {
            foreach ($holiday_query as $h) {
                $events[] = [
                    'id' => 'hol_' . $h['id'],
                    'title' => '[Libur] ' . strip_tags($h['info']),
                    'start' => $h['date'],
                    'end' => $h['date'],
                    'allDay' => true,
                    'backgroundColor' => '#dd4b39',
                    'borderColor' => '#d73925',
                    'textColor' => '#ffffff'
                ];
            }
        }

        // 2. Schedule events (map active weekly schedules to dates between start & end)
        $where_unit = "";
        if (!empty($majors_id) && $majors_id != 0) {
            $where_unit = " AND c.majors_majors_id = " . (int)$majors_id;
        }

        $schedules = $this->db->query("
            SELECT s.*, l.lesson_name, c.class_name, jp.jam_pelajaran_start, jp.jam_pelajaran_end,
                   e.employee_name, m.majors_name, m.majors_short_name, d.day_name
            FROM schedule s
            JOIN lesson l ON l.lesson_id = s.schedule_lesson_id
            JOIN class c ON c.class_id = s.schedule_class_id
            JOIN jam_pelajaran jp ON jp.jam_pelajaran_id = s.schedule_jampel
            JOIN day d ON d.day_id = s.schedule_day
            JOIN semester sem ON sem.semester_id = s.schedule_semester_id
            JOIN period p ON p.period_id = sem.semester_period_id
            LEFT JOIN employee e ON e.employee_id = l.lesson_teacher
            LEFT JOIN majors m ON m.majors_id = c.majors_majors_id
            WHERE sem.semester_status = 1
              AND p.period_status = 1
              {$where_unit}
        ")->result_array();

        if (!empty($schedules)) {
            $day_map = [
                'senin' => 1,
                'monday' => 1,
                'selasa' => 2,
                'tuesday' => 2,
                'rabu' => 3,
                'wednesday' => 3,
                'kamis' => 4,
                'thursday' => 4,
                'jumat' => 5,
                'friday' => 5,
                'sabtu' => 6,
                'saturday' => 6,
                'minggu' => 7,
                'sunday' => 7
            ];

            $colors = ['#3c8dbc', '#00a65a', '#f39c12', '#605ca8', '#00c0ef', '#39ccd7', '#d81b60'];

            $start_time = strtotime($start);
            $end_time = strtotime($end);

            foreach ($schedules as $idx => $row) {
                $day_key = strtolower(trim($row['day_name']));
                $target_w = isset($day_map[$day_key]) ? $day_map[$day_key] : null;
                if (!$target_w) {
                    continue;
                }

                $prefix = (empty($majors_id) || $majors_id == 0) && !empty($row['majors_short_name']) ? '[' . $row['majors_short_name'] . '] ' : '';
                $item_title = $prefix . $row['lesson_name'] . ' - Kelas ' . $row['class_name'];
                $desc = !empty($row['employee_name']) ? 'Pengajar: ' . $row['employee_name'] : '';
                $color = $colors[$idx % count($colors)];

                for ($curr = $start_time; $curr <= $end_time; $curr = strtotime('+1 day', $curr)) {
                    $w = (int)date('N', $curr);
                    if ($w === $target_w) {
                        $date_str = date('Y-m-d', $curr);
                        $jp_start = !empty($row['jam_pelajaran_start']) ? $row['jam_pelajaran_start'] : '07:00:00';
                        $jp_end = !empty($row['jam_pelajaran_end']) ? $row['jam_pelajaran_end'] : '08:00:00';

                        $events[] = [
                            'id' => 'sched_' . $row['schedule_id'] . '_' . $date_str,
                            'title' => $item_title,
                            'start' => $date_str . 'T' . $jp_start,
                            'end' => $date_str . 'T' . $jp_end,
                            'allDay' => false,
                            'backgroundColor' => $color,
                            'borderColor' => $color,
                            'textColor' => '#ffffff',
                            'description' => $desc
                        ];
                    }
                }
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($events));
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

    private function mapping_singkatan_unit($unit = null, $full = false)
    {
        $majors = $this->db->query("SELECT majors_id, majors_short_name, majors_name FROM majors WHERE majors_status = 1")->result_array();

        $mapping = array_column($majors, $full ? 'majors_name' : 'majors_short_name', 'majors_id');

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
        $last_month_date = date('Y-m-d 23:59:59', strtotime('last day of previous month'));

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

        $where_bebas = $selected_majors != 0 ? "AND student.majors_majors_id = '{$selected_majors}'" : "";

        $sql_bebas = "SELECT 
            SUM(bebas.bebas_bill - bebas.bebas_diskon) as total_bill,
            SUM(bebas.bebas_total_pay) as total_paid,
            (SELECT COALESCE(SUM(bp.bebas_pay_bill), 0) 
             FROM bebas_pay bp 
             JOIN bebas b ON b.bebas_id = bp.bebas_bebas_id 
             JOIN payment p ON p.payment_id = b.payment_payment_id 
             JOIN student s ON s.student_id = b.student_student_id
             WHERE p.period_period_id = '{$period_id}' AND bp.bebas_pay_input_date <= '{$last_month_date}'
             " . ($selected_majors != 0 ? "AND s.majors_majors_id = '{$selected_majors}'" : "") . ") as total_paid_last_month
        FROM bebas
        JOIN payment ON payment.payment_id = bebas.payment_payment_id
        JOIN student ON student.student_id = bebas.student_student_id
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
        session_write_close();
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
        session_write_close();
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
        session_write_close();
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
        session_write_close();
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
