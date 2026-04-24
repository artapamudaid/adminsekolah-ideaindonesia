<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
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
        $year = date('Y');

        $id = $this->session->userdata('uid');
        $uRole = $this->session->userdata('uroleid');

        $hasDashboard = false;
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
        $periodActive = $period['period_id'];
        $periodStart = $period['period_start'];
        $periodEnd = $period['period_end'];

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
                    WHERE tanggal BETWEEN  '$dateStart' AND '$dateEnd'
                    GROUP BY jenisTransaksi"
            )->result_array();

            $data['kasMonth'] = $this->db->query(
                "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                    FROM v_laporan_jurnal 
                    WHERE tanggal BETWEEN  '$dateStart' AND '$dateEnd' AND MONTH(tanggal) = MONTH(CURRENT_DATE())
                    GROUP BY jenisTransaksi"
            )->result_array();

            $data['kasToday'] = $this->db->query(
                "SELECT jenisTransaksi, SUM(penerimaan) AS tDebit, SUM(pengeluaran) AS tKredit
                    FROM v_laporan_jurnal 
                    WHERE tanggal = CURDATE()
                    GROUP BY jenisTransaksi"
            )->result_array();

            $data['student'] = $this->db->query("SELECT student_status, majors_majors_id FROM student")->result_array();
            $data['employee'] = $this->db->query("SELECT employee_category FROM employee")->result_array();

            $banking = $this->db->query("SELECT SUM(banking_debit) AS total_setor, SUM(banking_kredit) AS total_tarik FROM banking")->row_array();

            $data['total_setoran'] = (int)$banking['total_setor'] ?? 0;
            $data['total_penarikan'] = (int)$banking['total_tarik'] ?? 0;

            $data['information'] = $this->Information_model->get(array('information_publish' => 1));
            $data['majors'] = $this->Student_model->get_majors();
            $data['numStudents'] = $this->db->query("SELECT COUNT(student_id) as numbers
                                                    FROM student
                                                    GROUP BY majors_majors_id
                                                    ORDER BY majors_majors_id ASC")->result_array();

            $data['lastPayments'] = $this->db->query("SELECT s.student_full_name, MAX(l.log_trx_input_date) AS last_payment
                                                            FROM log_trx l
                                                            JOIN student s ON s.student_id = l.student_student_id
                                                            WHERE DATE(l.log_trx_input_date) = CURRENT_DATE
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
}
