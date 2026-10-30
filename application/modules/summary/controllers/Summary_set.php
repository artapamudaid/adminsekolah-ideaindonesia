<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Summary_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array(
            'users/Users_model',
            'holiday/Holiday_model'
        ));
        $this->load->model(array(
            'student/Student_model',
            'kredit/Kredit_model',
            'debit/Debit_model',
            'bulan/Bulan_model',
            'setting/Setting_model',
            'information/Information_model',
            'bebas/Bebas_model',
            'bebas/Bebas_pay_model'
        ));

        $this->load->library('user_agent');
    }

    public function index()
    {

        $id = $this->session->userdata('uid');

        $data['user'] = count($this->Users_model->get());

        $data['majors'] = $this->db->query("SELECT majors_id, majors_short_name FROM majors WHERE majors_status = 1")->result_array();

        $period = $this->db->query("SELECT period_id,period_start,period_end FROM period WHERE period_status = 1")->row_array();
        if (!$period) {
            $period = $this->db->query("SELECT period_id,period_start,period_end FROM period ORDER BY period_id DESC LIMIT 1")->row_array();
        }
        $periodActive = $period ? $period['period_id'] : 1;

        $periodStart = $period ? $period['period_start'] : date('Y');
        $periodEnd   = $period ? $period['period_end'] : (date('Y') + 1);

        $dateStart = "$periodStart-07-01";
        $dateEnd  = "$periodEnd-06-30";

        $mon = pretty_date(date('Y-m-d'), 'F', false);

        $month = $this->db->query("SELECT month_id FROM month WHERE month_name = '$mon'")->row_array();
        if (!$month) {
            $month = ['month_id' => (int)date('n')];
        }
        $data['monthActive'] = $month['month_id'];

        $data['student']        = $this->db->query("SELECT student_status, majors_majors_id FROM student")->result_array();
        $data['employee']       = $this->db->query("SELECT employee_category FROM employee")->result_array();
        $data['banking']        = $this->db->query("SELECT banking_debit, banking_kredit, banking_date 
                                                    FROM banking WHERE banking_period_id = '$periodActive'")->result_array();
        $data['information']    = $this->Information_model->get(array('information_publish' => 1));

        $monDate    = date('Y-m-d');
        $monThree   = date('Y-m-d', strtotime('-3 months', strtotime($monDate)));
        $monDateThree = pretty_date($monThree, 'F', false);

        $monthThree = $this->db->query("SELECT month_id FROM month WHERE month_name = '$monDateThree'")->row_array();
        $data['monThreeOndate'] = $monthThree ? $monthThree['month_id'] : max(1, $month['month_id'] - 3);

        $bSekarang = (int)$month['month_id'];
        $bAwal = max(1, $bSekarang - 3);
        $bAkhir = max(1, $bSekarang - 1);

        $tagihanBebas = $this->db->query("SELECT SUM(bebas_bill - bebas_total_pay) AS tagihan
                                            FROM bebas
                                            JOIN payment py ON py.payment_id = bebas.payment_payment_id
                                            WHERE py.period_period_id = $periodActive")->row();

        $tahunIni = $this->db->query(
            "SELECT SUM(bulan_bill) AS tagihan
                                        FROM bulan
                                        JOIN payment py ON py.payment_id = bulan.payment_payment_id 
                                        WHERE bulan_status = 0
                                        AND py.period_period_id = $periodActive"
        )->row();

        $bulanIni = $this->db->query(
            "SELECT SUM(bulan_bill) AS tagihan
                                        FROM bulan
                                        JOIN payment py ON py.payment_id = bulan.payment_payment_id 
                                        WHERE bulan_status = 0 
                                        AND month_month_id = $bSekarang
                                        AND py.period_period_id = $periodActive"
        )->row();

        $groupedTagihan = [];
        foreach ($data['majors'] as $m) {
            $groupedTagihan[$m['majors_short_name']] = [
                'kategori_a' => 0,
                'kategori_b' => 0
            ];
        }

        $terlambat1 = $this->db->query(
            "SELECT m.majors_short_name, SUM(bulan_bill) AS terlambat_a
                                        FROM bulan 
                                        JOIN payment py ON py.payment_id = bulan.payment_payment_id
                                        JOIN student s ON s.student_id = bulan.student_student_id
  	                                    JOIN majors m ON m.majors_id = s.majors_majors_id
                                        WHERE bulan_status = 0 
                                        AND month_month_id BETWEEN $bAwal AND $bAkhir
                                        AND py.period_period_id = $periodActive
                                        GROUP BY m.majors_short_name"
        )->result_array();

        foreach ($terlambat1 as $row) {
            $majors_name = $row['majors_short_name'];
            if (!isset($groupedTagihan[$majors_name])) {
                $groupedTagihan[$majors_name] = ['kategori_a' => 0, 'kategori_b' => 0];
            }
            $groupedTagihan[$majors_name]['kategori_a'] += (float)$row['terlambat_a'];
        }

        $terlambat2 = $this->db->query(
            "SELECT m.majors_short_name, SUM(bulan_bill) AS terlambat_b
                                        FROM bulan 
                                        JOIN payment py ON py.payment_id = bulan.payment_payment_id
                                        JOIN student s ON s.student_id = bulan.student_student_id
  	                                    JOIN majors m ON m.majors_id = s.majors_majors_id
                                        WHERE bulan_status = 0 
                                        AND month_month_id < $bAwal
                                        AND py.period_period_id = $periodActive
                                        GROUP BY m.majors_short_name"
        )->result_array();

        foreach ($terlambat2 as $row) {
            $majors_name = $row['majors_short_name'];
            if (!isset($groupedTagihan[$majors_name])) {
                $groupedTagihan[$majors_name] = ['kategori_a' => 0, 'kategori_b' => 0];
            }
            $groupedTagihan[$majors_name]['kategori_b'] += (float)$row['terlambat_b'];
        }

        $data['tagihan_tahun_ini'] = (float)($tahunIni->tagihan ?? 0) + (float)($tagihanBebas->tagihan ?? 0);
        $data['tagihan_bulan_ini'] = (float)($bulanIni->tagihan ?? 0);
        $data['groupTagihan'] = $groupedTagihan;

        $data['bulan'] = $this->db->query("SELECT bulan_noref, bulan_status, bulan_bill, month_month_id, majors_majors_id
                                            FROM bulan a LEFT JOIN payment b ON a.payment_payment_id=b.payment_id  
                                            LEFT JOIN student c ON a.student_student_id=c.student_id
                                            WHERE b.period_period_id='$periodActive'")->result_array();

        $data['topfive'] =  $this->db->query(
            "SELECT vpj.jenisBayar AS account_description, mj.majors_short_name, SUM(vpj.penerimaan) AS total_deb
                FROM v_laporan_jurnal vpj
                JOIN majors mj ON mj.majors_id =  vpj.idMajors
                WHERE vpj.tanggal BETWEEN '$dateStart' AND '$dateEnd' AND vpj.jenisTransaksi != 'Kredit'
                GROUP BY vpj.jenisBayar ORDER BY total_deb DESC LIMIT 5"
        )->result_array();

        $data['total_banking_period'] = 0;
        foreach ($data['banking'] as $row) {
            $data['total_banking_period'] += ((float)($row['banking_debit'] ?? 0) - (float)($row['banking_kredit'] ?? 0));
        }

        $data['total_banking_month'] = 0;
        $currentMonthStr = date('F');
        foreach ($data['banking'] as $row) {
            $date = date_create($row['banking_date']);
            if ($date && date_format($date, "F") == $currentMonthStr) {
                $data['total_banking_month'] += ((float)($row['banking_debit'] ?? 0) - (float)($row['banking_kredit'] ?? 0));
            }
        }

        $data['total_banking_today'] = 0;
        $currentDateStr = date('Y-m-d');
        foreach ($data['banking'] as $row) {
            if ($row['banking_date'] == $currentDateStr) {
                $data['total_banking_today'] += ((float)($row['banking_debit'] ?? 0) - (float)($row['banking_kredit'] ?? 0));
            }
        }

        $groupedKas = [];
        foreach ($data['majors'] as $m) {
            $groupedKas[$m['majors_short_name']] = [
                'tahun' => 0,
                'bulan' => 0
            ];
        }

        $data['kasTotal']          = $this->db->query(
            "SELECT majors_short_name, jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                FROM v_laporan_jurnal
                JOIN majors ON majors.majors_id = v_laporan_jurnal.idMajors
                WHERE tanggal BETWEEN '$dateStart' AND '$dateEnd'
                GROUP BY majors_short_name, jenisTransaksi"
        )->result_array();

        $data['kasMonth']          = $this->db->query(
            "SELECT majors_short_name, jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                FROM v_laporan_jurnal 
                JOIN majors ON majors.majors_id = v_laporan_jurnal.idMajors
                WHERE tanggal BETWEEN '$dateStart' AND '$dateEnd' AND MONTH(tanggal) = MONTH(CURRENT_DATE())
                GROUP BY majors_short_name, jenisTransaksi"
        )->result_array();

        $data['kasToday']          = $this->db->query(
            "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                FROM v_laporan_jurnal 
                WHERE tanggal = CURDATE()
                GROUP BY jenisTransaksi"
        )->result_array();

        $data['total_debit_period']     = 0;
        $data['total_kredit_period']    = 0;
        $data['total_pay_period']       = 0;
        foreach ($data['kasTotal'] as $row) {
            $majors_name = $row['majors_short_name'];
            $tDebit = (float)($row['tDebit'] ?? 0);
            $tKredit = (float)($row['tKredit'] ?? 0);
            if ($row['jenisTransaksi'] == 'Bulan' || $row['jenisTransaksi'] == 'Bebas') {
                $data['total_pay_period'] += $tDebit;
                $data['total_debit_period'] += $tDebit;
            }
            if ($row['jenisTransaksi'] == 'Debit') {
                $data['total_debit_period'] += $tDebit;
            }
            if ($row['jenisTransaksi'] == 'Kredit') {
                $data['total_kredit_period'] += $tKredit;
            }

            if (!isset($groupedKas[$majors_name])) {
                $groupedKas[$majors_name] = ['tahun' => 0, 'bulan' => 0];
            }
            $groupedKas[$majors_name]['tahun'] += $tDebit;
        }

        $data['total_debit_month']      = 0;
        $data['total_kredit_month']     = 0;
        $data['total_pay_month']        = 0;
        foreach ($data['kasMonth'] as $row) {
            $majors_name = $row['majors_short_name'];
            $tDebit = (float)($row['tDebit'] ?? 0);
            $tKredit = (float)($row['tKredit'] ?? 0);
            if ($row['jenisTransaksi'] == 'Bulan' || $row['jenisTransaksi'] == 'Bebas') {
                $data['total_pay_month'] += $tDebit;
                $data['total_debit_month'] += $tDebit;
            }
            if ($row['jenisTransaksi'] == 'Debit') {
                $data['total_debit_month'] += $tDebit;
            }
            if ($row['jenisTransaksi'] == 'Kredit') {
                $data['total_kredit_month'] += $tKredit;
            }

            if (!isset($groupedKas[$majors_name])) {
                $groupedKas[$majors_name] = ['tahun' => 0, 'bulan' => 0];
            }
            $groupedKas[$majors_name]['bulan'] += $tDebit;
        }

        $data['kasUnit'] = $groupedKas;

        $data['total_pay_today']        = 0;
        $data['total_debit_today']      = 0;
        $data['total_kredit_today']     = 0;
        foreach ($data['kasToday'] as $row) {
            $tDebit = (float)($row['tDebit'] ?? 0);
            $tKredit = (float)($row['tKredit'] ?? 0);
            if ($row['jenisTransaksi'] == 'Bulan' || $row['jenisTransaksi'] == 'Bebas') {
                $data['total_pay_today'] += $tDebit;
                $data['total_debit_today'] += $tDebit;
            }
            if ($row['jenisTransaksi'] == 'Debit') {
                $data['total_debit_today'] += $tDebit;
            }
            if ($row['jenisTransaksi'] == 'Kredit') {
                $data['total_kredit_today'] += $tKredit;
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

        $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
        $data['holiday'] = $this->Holiday_model->get();
        $data['title'] = 'Analisa dan Performa';
        $data['main'] = 'summary/summary';
        $this->load->view('manage/layout', $data);
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
}
