<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_realisasi_akun_dt($period_id = null, $start_month = null, $end_month = null, $majors_id = null)
    {
        if (empty($period_id)) {
            $this->db->select('period_start,period_end,period_id');
            $this->db->where('period_status = 1');
            $period = $this->db->get('period')->row_array();
            $pr = $period ? (int)$period['period_id'] : 1;
        } else {
            $pr = (int)$period_id;
        }

        $wherePeriod = " AND payment.period_period_id = $pr";

        $whereMajorsRealisasi = "";
        $whereMajorsBebas = "";
        $whereMajors = "";

        $umajors = $this->session->userdata('umajorsid');
        if ($umajors != 0) {
            $majors_id = $umajors;
        }

        if (!empty($majors_id) && $majors_id != 0) {
            $majorsFilter = (int)$majors_id;
            $whereMajorsRealisasi = " AND student.majors_majors_id = '$majorsFilter'";
            $whereMajorsBebas = " AND account.account_majors_id = '$majorsFilter'";
            $whereMajors = " AND majors.majors_id = '$majorsFilter'";
        }

        $start = !empty($start_month) ? (int)$start_month : 1;
        $end   = !empty($end_month) ? (int)$end_month : 12;

        if ($start > $end) {
            $tmp = $start;
            $start = $end;
            $end = $tmp;
        }

        $whereDateBulan = "AND bulan.month_month_id BETWEEN '$start' AND '$end'";

        $rp_bulan = $this->db->query("SELECT
                                    majors.majors_id,
                                    majors.majors_short_name,
                                    CONCAT(pos.pos_name, ' ', majors.majors_short_name) unit,
                                    SUM(CASE WHEN bulan.bulan_status = 1 THEN bulan.bulan_bill ELSE 0 END) AS terbayar,
                                    SUM(CASE WHEN bulan.bulan_status = 0 THEN bulan.bulan_bill ELSE 0 END) AS belum
                                    FROM `bulan` 
                                    LEFT JOIN `month` ON `month`.`month_id` = `bulan`.`month_month_id` 
                                    LEFT JOIN `student` ON `student`.`student_id` = `bulan`.`student_student_id` 
                                    LEFT JOIN `payment` ON `payment`.`payment_id` = `bulan`.`payment_payment_id` 
                                    LEFT JOIN `pos` ON `pos`.`pos_id` = `payment`.`pos_pos_id` 
                                    LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
                                    LEFT JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` 
                                    WHERE majors.majors_id IS NOT NULL $whereDateBulan $wherePeriod $whereMajors $whereMajorsRealisasi
                                    GROUP BY pos.pos_id, majors.majors_id, majors.majors_short_name, pos.pos_name 
                                    ORDER BY pos.pos_name ASC")->result_array();

        $rp_bebas = $this->db->query("SELECT
                                    majors.majors_id,
                                    majors.majors_short_name,
                                    CONCAT(pos.pos_name, ' ', majors.majors_short_name) unit,
                                    SUM(bebas.bebas_total_pay) - SUM(bebas.bebas_diskon) AS terbayar,
                                    SUM(bebas.bebas_bill) - SUM(bebas.bebas_total_pay) - SUM(bebas.bebas_diskon) AS belum
                                    FROM pos 
                                    JOIN payment ON pos.pos_id = payment.pos_pos_id 
                                    JOIN bebas ON payment.payment_id = bebas.payment_payment_id 
                                    JOIN account ON account.account_id = pos.account_account_id 
                                    JOIN majors ON majors.majors_id = account.account_majors_id 
                                    JOIN student ON bebas.student_student_id = student.student_id 
                                    WHERE 1 $wherePeriod $whereMajors $whereMajorsBebas
                                    GROUP BY pos.pos_id, majors.majors_id, majors.majors_short_name, pos.pos_name")->result_array();

        $all_data = array_merge($rp_bulan, $rp_bebas);
        $result = [];

        foreach ($all_data as $row) {
            $terbayar = (float)($row['terbayar'] ?? 0);
            $belum    = (float)($row['belum'] ?? 0);
            if ($terbayar < 0) $terbayar = 0;
            if ($belum < 0) $belum = 0;
            $tagihan  = $terbayar + $belum;

            $pencapaian = $tagihan > 0 ? ($terbayar / $tagihan) * 100 : 0;

            $badge_class = 'label-danger';
            if ($pencapaian >= 100) {
                $badge_class = 'label-success';
            } elseif ($pencapaian >= 75) {
                $badge_class = 'label-primary';
            } elseif ($pencapaian >= 50) {
                $badge_class = 'label-warning';
            }

            $result[] = [
                'majors_short_name' => $row['majors_short_name'] ?? '-',
                'unit'              => $row['unit'] ?? '-',
                'tagihan'           => 'Rp ' . number_format($tagihan, 0, ',', '.'),
                'terbayar'          => 'Rp ' . number_format($terbayar, 0, ',', '.'),
                'belum'             => 'Rp ' . number_format($belum, 0, ',', '.'),
                'pencapaian'        => '<span class="label ' . $badge_class . '">' . number_format($pencapaian, 1, ',', '.') . '%</span>',
                'tagihan_raw'       => $tagihan,
                'terbayar_raw'      => $terbayar,
                'belum_raw'         => $belum,
                'pencapaian_raw'    => round($pencapaian, 1)
            ];
        }

        return $result;
    }

    /**
     * Get data for Grafik Pembayaran SPP (Total akumulasi SPP tiap Unit / Kelas)
     */
    public function get_spp_chart_data($majors_id = null)
    {
        $umajors = $this->session->userdata('umajorsid');
        if ($umajors != 0) {
            $majors_id = $umajors;
        }

        $is_filtered = (!empty($majors_id) && $majors_id != 0);

        if ($is_filtered) {
            $sql = "SELECT 
                        c.class_name as label,
                        SUM(CASE WHEN b.bulan_status = 1 THEN 1 ELSE 0 END) as lunas,
                        SUM(CASE WHEN b.bulan_status = 0 THEN 1 ELSE 0 END) as belum_lunas
                    FROM bulan b
                    JOIN payment p ON p.payment_id = b.payment_payment_id
                    JOIN student s ON s.student_id = b.student_student_id
                    JOIN class c ON c.class_id = s.class_class_id
                    JOIN period per ON per.period_id = p.period_period_id AND per.period_status = 1
                    WHERE p.payment_type = 'BULAN' 
                      AND s.majors_majors_id = " . (int)$majors_id . " 
                      AND s.student_status = 1
                    GROUP BY c.class_id, c.class_name
                    ORDER BY c.class_name ASC";
        } else {
            $sql = "SELECT 
                        m.majors_short_name as label,
                        SUM(CASE WHEN b.bulan_status = 1 THEN 1 ELSE 0 END) as lunas,
                        SUM(CASE WHEN b.bulan_status = 0 THEN 1 ELSE 0 END) as belum_lunas
                    FROM bulan b
                    JOIN payment p ON p.payment_id = b.payment_payment_id
                    JOIN student s ON s.student_id = b.student_student_id
                    JOIN majors m ON m.majors_id = s.majors_majors_id
                    JOIN period per ON per.period_id = p.period_period_id AND per.period_status = 1
                    WHERE p.payment_type = 'BULAN' 
                      AND s.student_status = 1
                    GROUP BY m.majors_id, m.majors_short_name
                    ORDER BY m.majors_id ASC";
        }

        $query = $this->db->query($sql)->result_array();

        $data = [
            'labels' => [],
            'lunas' => [],
            'belum_lunas' => [],
            'collection_rate' => []
        ];

        foreach ($query as $row) {
            $label = $row['label'];
            $lunas = (int)$row['lunas'];
            $belum_lunas = (int)$row['belum_lunas'];

            $total = $lunas + $belum_lunas;
            $rate = ($total > 0) ? round(($lunas / $total) * 100, 1) : 0;

            $data['labels'][] = $label;
            $data['lunas'][] = $lunas;
            $data['belum_lunas'][] = $belum_lunas;
            $data['collection_rate'][] = $rate;
        }

        return $data;
    }



    /**
     * Get tagihan bulan ini dari tabel bulan.
     * @param int|null $majors_id  filter unit (null = semua)
     * @param int|null $limit      null = semua (untuk modal), angka = preview (untuk widget)
     * @return array
     */
    public function get_tagihan_bulan_ini($majors_id = null, $limit = null)
    {
        $period = $this->db->query("SELECT period_id FROM period WHERE period_status = 1 LIMIT 1")->row_array();
        $pr = $period ? (int)$period['period_id'] : 1;
        $month_id = (int)date('n');

        $umajors = $this->session->userdata('umajorsid');
        if ($umajors != 0) {
            $majors_id = $umajors;
        }

        $whereMajors = '';
        if (!empty($majors_id) && $majors_id != 0) {
            $m = (int)$majors_id;
            $whereMajors = "AND s.majors_majors_id = '$m'";
        }

        $sql = "SELECT
                    p.pos_name,
                    m.majors_short_name,
                    COUNT(b.bulan_id)                                                     AS total,
                    SUM(CASE WHEN b.bulan_status = 1 THEN 1 ELSE 0 END)                  AS terbayar
                FROM bulan b
                JOIN payment py  ON py.payment_id        = b.payment_payment_id
                JOIN pos p       ON p.pos_id             = py.pos_pos_id
                JOIN student s   ON s.student_id         = b.student_student_id
                JOIN majors m    ON m.majors_id          = s.majors_majors_id
                WHERE py.period_period_id = $pr
                  AND b.month_month_id   = $month_id
                  AND s.student_status   = 1
                  $whereMajors
                GROUP BY p.pos_id, m.majors_id
                ORDER BY p.pos_name ASC, m.majors_short_name ASC";

        $rows = $this->db->query($sql)->result_array();
        $show_unit = (empty($majors_id) || $majors_id == 0);

        $result = [];
        foreach ($rows as $r) {
            $total    = (int)$r['total'];
            $paid     = (int)$r['terbayar'];
            $pct      = $total > 0 ? round($paid / $total * 100, 1) : 0;

            if ($pct >= 75) {
                $bar = 'progress-bar-success';
            } elseif ($pct >= 50) {
                $bar = 'progress-bar-info';
            } elseif ($pct >= 25) {
                $bar = 'progress-bar-warning';
            } else {
                $bar = 'progress-bar-danger';
            }

            $name = $r['pos_name'];
            if ($show_unit) {
                $name .= ' - ' . $r['majors_short_name'];
            }

            $result[] = [
                'name'     => $name,
                'progress' => $pct,
                'text'     => $paid . '/' . $total,
                'bar'      => $bar,
                'total'    => $total,
                'terbayar' => $paid,
            ];
        }

        return $result;
    }


    /**
     * Get 5 siswa tabungan terbesar
     * @param int|null $majors_id
     * @return array
     */
    public function get_top_5_tabungan($majors_id = null)
    {
        $umajors = $this->session->userdata('umajorsid');
        if ($umajors != 0) {
            $majors_id = $umajors;
        }

        $whereMajors = '';
        if (!empty($majors_id) && $majors_id != 0) {
            $m = (int)$majors_id;
            $whereMajors = "AND s.majors_majors_id = '$m'";
        }

        $sql = "SELECT 
                    s.student_id,
                    s.student_full_name,
                    s.student_nis,
                    c.class_name,
                    m.majors_short_name,
                    m.majors_name,
                    SUM(b.banking_debit - b.banking_kredit) AS total_saldo
                FROM banking b
                JOIN student s ON s.student_id = b.banking_student_id
                LEFT JOIN class c ON c.class_id = s.class_class_id
                LEFT JOIN majors m ON m.majors_id = s.majors_majors_id
                WHERE s.student_status = 1 $whereMajors
                GROUP BY s.student_id
                HAVING total_saldo > 0
                ORDER BY total_saldo DESC
                LIMIT 5";

        return $this->db->query($sql)->result_array();
    }

    /**
     * Get detail transaksi tabungan siswa
     * @param int $student_id
     * @return array [student => [...], mutasi => [...], total_setoran => ..., total_penarikan => ..., saldo_akhir => ...]
     */
    public function get_detail_tabungan($student_id)
    {
        $student_id = (int)$student_id;

        $student = $this->db->query(
            "SELECT s.student_id, s.student_full_name, s.student_nis, c.class_name, m.majors_name, m.majors_short_name 
             FROM student s
             LEFT JOIN class c ON c.class_id = s.class_class_id
             LEFT JOIN majors m ON m.majors_id = s.majors_majors_id
             WHERE s.student_id = $student_id LIMIT 1"
        )->row_array();

        if (!$student) {
            return null;
        }

        $rows = $this->db->query(
            "SELECT banking_id, banking_date, banking_debit, banking_kredit, banking_note 
             FROM banking 
             WHERE banking_student_id = $student_id 
             ORDER BY banking_date ASC, banking_id ASC"
        )->result_array();

        $running_balance = 0;
        $total_debit = 0;
        $total_kredit = 0;
        $mutasi = [];

        foreach ($rows as $i => $r) {
            $debit = (float)$r['banking_debit'];
            $kredit = (float)$r['banking_kredit'];
            $running_balance += ($debit - $kredit);

            $total_debit += $debit;
            $total_kredit += $kredit;

            $mutasi[] = [
                'no'           => $i + 1,
                'date'         => pretty_date($r['banking_date'], 'd M Y', false),
                'debit'        => $debit > 0 ? 'Rp' . number_format($debit, 0, ',', '.') : '-',
                'kredit'       => $kredit > 0 ? 'Rp' . number_format($kredit, 0, ',', '.') : '-',
                'note'         => html_escape($r['banking_note']),
                'saldo'        => 'Rp' . number_format($running_balance, 0, ',', '.'),
                'debit_raw'    => $debit,
                'kredit_raw'   => $kredit,
                'saldo_raw'    => $running_balance
            ];
        }

        return [
            'student'         => $student,
            'mutasi'          => $mutasi,
            'total_setoran'   => 'Rp' . number_format($total_debit, 0, ',', '.'),
            'total_penarikan' => 'Rp' . number_format($total_kredit, 0, ',', '.'),
            'saldo_akhir'     => 'Rp' . number_format($running_balance, 0, ',', '.')
        ];
    }

    /**
     * Get 5 transaksi terbaru dari ltrx
     * @param int|null $majors_id
     * @param int $limit
     * @return array
     */
    public function get_recent_transactions($majors_id = null, $limit = 5)
    {
        $this->db->select('l.ltrx_id, l.ltrx_date, l.ltrx_module, l.ltrx_action, l.ltrx_info, u.user_majors_id');
        $this->db->from('ltrx l');
        $this->db->join('users u', 'u.user_id = l.user_id', 'left');
        $this->db->where_in('l.ltrx_module', ['Pembayaran', 'Penggajian', 'Kas Masuk', 'Kas Keluar']);

        if (!empty($majors_id) && $majors_id != 0) {
            $this->db->where('u.user_majors_id', (int)$majors_id);
        }

        $this->db->order_by('l.ltrx_id', 'DESC');
        $this->db->limit((int)$limit);
        $rows = $this->db->get()->result_array();

        $result = [];
        foreach ($rows as $r) {
            $info = $r['ltrx_info'];
            $desc = $info;
            $nominal = 0;

            if (strpos($info, 'Title:') !== false) {
                $parts = explode('Title:', $info, 2);
                $desc_part = trim($parts[1]);

                if (strpos($desc_part, 'nominal') !== false) {
                    $n_parts = explode('nominal', $desc_part, 2);
                    $desc = trim(rtrim(trim($n_parts[0]), '-'));
                    $nominal = (float) preg_replace('/[^0-9]/', '', $n_parts[1]);
                } else {
                    $desc = $desc_part;
                }
            } else if (strpos($info, 'nominal') !== false) {
                $n_parts = explode('nominal', $info, 2);
                $desc = trim(rtrim(trim($n_parts[0]), '-'));
                $nominal = (float) preg_replace('/[^0-9]/', '', $n_parts[1]);
            }

            if (empty($desc)) {
                $desc = $r['ltrx_action'] . ' - ' . $r['ltrx_module'];
            }

            $status = 'Selesai';
            $label = 'label-success';
            $action_lower = strtolower($r['ltrx_action']);

            if (strpos($action_lower, 'hapus') !== false || strpos($action_lower, 'batal') !== false) {
                $status = 'Dibatalkan';
                $label = 'label-danger';
            } else if (strpos($action_lower, 'edit') !== false || strpos($action_lower, 'ubah') !== false) {
                $status = 'Diubah';
                $label = 'label-warning';
            }

            $result[] = [
                'tgl'      => pretty_date($r['ltrx_date'], 'd M Y H:i', false),
                'desc'     => html_escape($desc),
                'kategori' => html_escape($r['ltrx_module']),
                'nominal'  => $nominal > 0 ? 'Rp ' . number_format($nominal, 0, ',', '.') : '-',
                'status'   => $status,
                'label'    => $label
            ];
        }

        return $result;
    }
}
