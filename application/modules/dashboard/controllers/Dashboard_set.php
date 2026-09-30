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

            $data['dummy_tagihan_bulan'] = [
                ['name' => 'SPP Kelas 7', 'progress' => 90, 'text' => '72/80', 'bar' => 'progress-bar-success'],
                ['name' => 'SPP Kelas 8', 'progress' => 87, 'text' => '68/78', 'bar' => 'progress-bar-success'],
                ['name' => 'SPP Kelas 9', 'progress' => 93, 'text' => '70/75', 'bar' => 'progress-bar-success'],
                ['name' => 'Uang Pangkal', 'progress' => 50, 'text' => '5/10', 'bar' => 'progress-bar-warning'],
                ['name' => 'Seragam', 'progress' => 67, 'text' => '40/60', 'bar' => 'progress-bar-info'],
            ];

            $data['dummy_transaksi'] = [
                ['tgl' => '23 Mei 2025', 'desc' => 'Pembayaran SPP - Mei', 'kategori' => 'Pemasukan', 'nominal' => 'Rp 2.450.000', 'status' => 'Lunas', 'label' => 'label-success'],
                ['tgl' => '23 Mei 2025', 'desc' => 'Pembelian ATK', 'kategori' => 'Pengeluaran', 'nominal' => 'Rp 850.000', 'status' => 'Selesai', 'label' => 'label-default'],
                ['tgl' => '22 Mei 2025', 'desc' => 'Setoran Tabungan', 'kategori' => 'Pemasukan', 'nominal' => 'Rp 1.250.000', 'status' => 'Lunas', 'label' => 'label-success'],
                ['tgl' => '21 Mei 2025', 'desc' => 'Pembayaran Kegiatan', 'kategori' => 'Pemasukan', 'nominal' => 'Rp 750.000', 'status' => 'Lunas', 'label' => 'label-success'],
                ['tgl' => '21 Mei 2025', 'desc' => 'Pembelian Buku', 'kategori' => 'Pengeluaran', 'nominal' => 'Rp 450.000', 'status' => 'Selesai', 'label' => 'label-primary'],
            ];

            $data['dummy_aktivitas'] = [
                ['icon' => 'fa-users bg-green', 'title' => 'Presensi Siswa', 'desc' => '315 siswa hadir hari ini', 'time' => '07:30'],
                ['icon' => 'fa-book bg-blue', 'title' => 'Jurnal Mengajar', 'desc' => '14 guru mengisi jurnal', 'time' => '08:15'],
                ['icon' => 'fa-money bg-yellow', 'title' => 'Pembayaran Diterima', 'desc' => '12 transaksi pembayaran', 'time' => '09:20'],
                ['icon' => 'fa-bullhorn bg-red', 'title' => 'Pengumuman Baru', 'desc' => 'Libur akhir semester', 'time' => '10:00'],
                ['icon' => 'fa-file-text-o bg-blue', 'title' => 'Perizinan Siswa', 'desc' => '3 pengajuan izin', 'time' => '10:15'],
            ];

            $data['dummy_tabungan'] = [
                ['no' => 1, 'nama' => 'Ahmad Rizky', 'kelas' => 'Kelas 9A', 'nominal' => 'Rp 1.250.000'],
                ['no' => 2, 'nama' => 'Naila Putri', 'kelas' => 'Kelas 8B', 'nominal' => 'Rp 1.100.000'],
                ['no' => 3, 'nama' => 'Fauzan Alfarizi', 'kelas' => 'Kelas 9B', 'nominal' => 'Rp 950.000'],
                ['no' => 4, 'nama' => 'Siti Aisyah', 'kelas' => 'Kelas 7A', 'nominal' => 'Rp 900.000'],
                ['no' => 5, 'nama' => 'Muhammad Fikri', 'kelas' => 'Kelas 8A', 'nominal' => 'Rp 850.000'],
            ];

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
            $data['dummy_arus_kas'] = [
                3 => [
                    'labels' => ['Maret', 'April', 'Mei'],
                    'pemasukan' => [45000000, 52000000, 60000000],
                    'pengeluaran' => [28000000, 31000000, 35000000],
                ],
                6 => [
                    'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
                    'pemasukan' => [38000000, 42000000, 45000000, 52000000, 60000000, 58000000],
                    'pengeluaran' => [25000000, 27000000, 28000000, 31000000, 35000000, 33000000],
                ],
                9 => [
                    'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September'],
                    'pemasukan' => [38000000, 42000000, 45000000, 52000000, 60000000, 58000000, 65000000, 70000000, 68000000],
                    'pengeluaran' => [25000000, 27000000, 28000000, 31000000, 35000000, 33000000, 40000000, 42000000, 39000000],
                ],
                12 => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    'pemasukan' => [38000000, 42000000, 45000000, 52000000, 60000000, 58000000, 65000000, 70000000, 68000000, 72000000, 75000000, 80000000],
                    'pengeluaran' => [25000000, 27000000, 28000000, 31000000, 35000000, 33000000, 40000000, 42000000, 39000000, 45000000, 48000000, 50000000],
                ]
            ];



            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard_new';
        } else {
            $data['title'] = 'Dashboard';
            $data['main'] = 'dashboard/dashboard_employees';
        }

        $this->load->view('manage/layout', $data);
    }

    public function realisasi()
    {
        $umajors = $this->session->userdata('umajorsid');

        $whereMajors = "";

        if ($umajors != 0) {
            $whereMajors = " AND majors_id = '$umajors'";
        }

        $majors = $this->db->query("SELECT * FROM majors WHERE 1  $whereMajors")->result_array();

        $getRealisasi = $this->Dashboard_model->get_realisasi();

        $dataRealisasi = json_decode($getRealisasi, true);
        $rBulan = $dataRealisasi['rp_bulan'];
        $rBebas = $dataRealisasi['rp_bebas'];

        $html = '<thead>
                <tr>
                <th>' . $this->lang->line('dash_unit') . '</th>
                <th>' . $this->lang->line('dash_payment_account') . '</th>
                <th>' . $this->lang->line('dash_bill') . '</th>
                <th>' . $this->lang->line('dash_paid') . '</th>
                <th>' . $this->lang->line('dash_unpaid') . '</th>
                <th>' . $this->lang->line('dash_achievement') . '</th>
                </tr>
                </thead>';

        $html .= '<tbody>';


        $sumTagihanBulan = 0;
        $sumTerbayarBulan = 0;
        $sumBelumBulan = 0;
        $sumPresentaseBulan = 0;

        $sumTagihanBebas = 0;
        $sumTerbayarBebas = 0;
        $sumBelumBebas = 0;
        $sumPresentaseBebas = 0;

        foreach ($majors as $major) {
            foreach ($rBulan as $dBulan) {
                if ($major['majors_id'] == $dBulan['majors_id']) {

                    $terbayar = (int)$dBulan['terbayar'];
                    $belum = (int)$dBulan['belum'];
                    $totalBulan = $terbayar + $belum;

                    $pembulatanBulan = $totalBulan > 0
                        ? $this->pembulatan(($terbayar / $totalBulan) * 100)
                        : 0;

                    $html .= '<tr style="text-align: right;">
                    <td style="text-align: left;">' . $dBulan['majors_short_name'] . '</td>
                    <td style="text-align: left;">' . $dBulan['unit'] . '</td>
                    <td>Rp ' . number_format($totalBulan, '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBulan['terbayar'], '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBulan['belum'], '0', ',', '.') . '</td>
                    <td>' . $pembulatanBulan . '% </td>
                    </tr>';

                    $sumTagihanBulan += $totalBulan;
                    $sumTerbayarBulan += $dBulan['terbayar'];
                    $sumBelumBulan += $dBulan['belum'];
                    $sumPresentaseBulan += $pembulatanBulan;
                }
            }
            foreach ($rBebas as $dBebas) {
                if ($major['majors_id'] == $dBebas['majors_id']) {

                    $terbayar = (int)$dBebas['terbayar'];
                    $belum = (int)$dBebas['belum'];
                    $totalBebas = $terbayar + $belum;

                    $pembulatanBebas = $totalBebas > 0
                        ? $this->pembulatan(($terbayar / $totalBebas) * 100)
                        : 0;

                    $html .= '<tr style="text-align: right;">
                    <td style="text-align: left;">' . $dBebas['majors_short_name'] . '</td>
                    <td style="text-align: left;">' . $dBebas['unit'] . '</td>
                    <td>Rp ' . number_format($totalBebas, '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBebas['terbayar'], '0', ',', '.') . '</td>
                    <td>Rp ' . number_format($dBebas['belum'], '0', ',', '.') . '</td>
                    <td>' . $pembulatanBebas . '% </td></tr>';

                    $sumTagihanBebas += $totalBebas;
                    $sumTerbayarBebas += $dBebas['terbayar'];
                    $sumBelumBebas += $dBebas['belum'];
                    $sumPresentaseBebas += $pembulatanBebas;
                }
            }
        }

        $sumTagihanTotal = $sumTagihanBulan + $sumTagihanBebas;
        $sumTerbayarTotal = $sumTerbayarBulan + $sumTerbayarBebas;

        // Cek apakah penyebut nol sebelum pembagian
        $persentase = ($sumTagihanTotal != 0) ? ($sumTerbayarTotal / $sumTagihanTotal) * 100 : 0;

        // Panggil fungsi pembulatan
        $hasilPresentase = $this->pembulatan($persentase);

        $html .= '</tbody>';

        $html .= '<tfoot>
                <tr style="font-weight: bold; text-align: right;">
                <td></td>
                <td></td>
                <td>Rp ' . number_format($sumTagihanBulan + $sumTagihanBebas, '0', ',', '.') . '</td>
                <td>Rp ' . number_format($sumTerbayarBulan + $sumTerbayarBebas, '0', ',', '.') . '</td>
                <td>Rp ' . number_format($sumBelumBulan + $sumBelumBebas, '0', ',', '.') . '</td>
                <td>' . $hasilPresentase . '%</td></tr>
                </tfoot>';

        echo $html;
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
        $query = $this->db->query("SELECT 
            SUM(penerimaan) AS total_pemasukan,
            SUM(pengeluaran) AS total_pengeluaran,
            SUM(penerimaan - pengeluaran) AS saldo_akhir
            FROM v_laporan_jurnal
            WHERE MONTH(tanggal) = '$month' 
            AND YEAR(tanggal) = '$year'
            {$where_majors}
        ")->row_array();

        $pemasukan = (float)($query['total_pemasukan'] ?? 0);
        $pengeluaran = (float)($query['total_pengeluaran'] ?? 0);
        $saldo = (float)($query['saldo_akhir'] ?? 0);
        $total_all = $pemasukan + $pengeluaran + abs($saldo);

        return [
            'total_display' => $this->format_short_amount($total_all),
            'pemasukan' => [
                'val'  => $pemasukan,
                'text' => number_format($pemasukan, 0, ',', '.'),
                'pct'  => ($total_all > 0 ? number_format(($pemasukan / $total_all) * 100, 1) : 0) . '%'
            ],
            'pengeluaran' => [
                'val'  => $pengeluaran,
                'text' => number_format($pengeluaran, 0, ',', '.'),
                'pct'  => ($total_all > 0 ? number_format(($pengeluaran / $total_all) * 100, 1) : 0) . '%'
            ],
            'saldo_akhir' => [
                'val'  => $saldo,
                'text' => number_format($saldo, 0, ',', '.'),
                'pct'  => ($total_all > 0 ? number_format((abs($saldo) / $total_all) * 100, 1) : 0) . '%'
            ]
        ];
    }

    private function format_short_amount($amount)
    {
        if ($amount >= 1000000000000) {
            return rtrim(rtrim(number_format($amount / 1000000000000, 2, ',', '.'), '0'), ',') . ' T';
        } elseif ($amount >= 1000000000) {
            return rtrim(rtrim(number_format($amount / 1000000000, 2, ',', '.'), '0'), ',') . ' M';
        } elseif ($amount >= 1000000) {
            return rtrim(rtrim(number_format($amount / 1000000, 2, ',', '.'), '0'), ',') . ' JT';
        } elseif ($amount >= 1000) {
            return rtrim(rtrim(number_format($amount / 1000, 2, ',', '.'), '0'), ',') . ' RB';
        }
        return number_format($amount, 0, ',', '.');
    }
}
