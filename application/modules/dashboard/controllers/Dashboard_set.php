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
            $data['dummy_top_metrics'] = [
                $this->top_metrics_student($where_majors_student),
                $this->top_metrics_teacher($where_majors_employee),
                $this->top_metrics_class($where_majors_class),
                $this->top_metrics_kas($where_majors_v_laporan_jurnal),
                ['title' => 'COLLECTION RATE', 'icon' => 'fa-percent', 'value' => '92,45%', 'label' => 'Tagihan Lunas', 'subtext' => '↑ 3,8% dari bulan lalu', 'color' => '#17a2b8', 'subcolor' => 'text-green'],
                ['title' => 'TABUNGAN SISWA', 'icon' => 'fa-credit-card', 'value' => 'Rp 3.765.930', 'label' => 'Total Saldo', 'subtext' => '↑ 8,7% dari bulan lalu', 'color' => '#007bff', 'subcolor' => 'text-green']
            ];

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

            $data['dummy_ikhtisar'] = [
                'pemasukan' => 'Rp 150.000.000',
                'pengeluaran' => 'Rp 75.000.000',
                'saldo' => 'Rp 75.000.000'
            ];
            $data['dummy_ikhtisar_doughnut'] = [
                'total_display' => '245,75 JT',
                'pemasukan' => ['val' => 312450000, 'text' => '312.450.000', 'pct' => '56.2%'],
                'pengeluaran' => ['val' => 198230000, 'text' => '198.230.000', 'pct' => '35.6%'],
                'saldo_akhir' => ['val' => 245750000, 'text' => '245.750.000', 'pct' => '8.2%']
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
            'title' => 'TOTAL SISWA',
            'icon' => 'fa-users',
            'value' => number_format($current, 0, ',', '.'),
            'label' => 'Aktif',
            'subtext' => "{$trend_icon} {$diff_abs} dari tahun lalu",
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
            'title' => 'TOTAL GURU',
            'icon' => 'fa-graduation-cap',
            'value' => number_format($current, 0, ',', '.'),
            'label' => 'Aktif',
            'subtext' => "{$trend_icon} {$diff_abs} dari tahun lalu",
            'color' => '#007bff',
            'subcolor' => $trend_subcolor
        ];
    }

    private function top_metrics_class($where_majors_class = "")
    {
        $current = $this->db->query("SELECT COUNT(class_id) as total FROM class WHERE 1 {$where_majors_class}")->row()->total;

        return [
            'title' => 'TOTAL KELAS',
            'icon' => 'fa-building',
            'value' => number_format($current, 0, ',', '.'),
            'label' => 'Kelas Aktif',
            'subtext' => null,
            'color' => '#6f42c1',
            'subcolor' => null
        ];
    }

    private function top_metrics_kas($where_majors_v_laporan_jurnal = "")
    {
        $period                 = $this->db->query("SELECT period_id,period_start,period_end FROM period WHERE period_status = 1")->row_array();
        $periodActive           = $period['period_id'];
        $periodStart = $period['period_start'];
        $periodEnd   = $period['period_end'];

        $dateStart = "$periodStart-07-01";
        $dateEnd  = "$periodEnd-06-30";

        $current = $this->db->query(
            "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                    FROM v_laporan_jurnal 
                    WHERE tanggal BETWEEN  '$dateStart' AND '$dateEnd' $where_majors_v_laporan_jurnal
                    GROUP BY jenisTransaksi"
        )->result_array();

        return [
            'title' => 'SALDO KAS SEKOLAH',
            'icon' => 'fa-money',
            'value' => 'Rp 245.750.000',
            'label' => 'Total Kas',
            'subtext' => '↑ 12,5% dari bulan lalu',
            'color' => '#fd7e14',
            'subcolor' => 'text-green'
        ];
    }
}
