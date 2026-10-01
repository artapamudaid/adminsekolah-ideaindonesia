<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_realisasi()
    {
        $wherePeriod = "";
        if (!empty($_GET['period']) && $_GET['period'] != 'all') {
            $pr = $_GET['period'];
            $this->db->select('period_start,period_end,period_id');
            $this->db->where('period_id', $pr);
            $period = $this->db->get('period')->row_array();

            $wherePeriod = "AND payment.period_period_id = $pr";
        } else {
            $this->db->select('period_start,period_end,period_id');
            $this->db->where('period_status = 1');
            $period = $this->db->get('period')->row_array();
            $pr = $period['period_id'];

            $wherePeriod = " AND payment.period_period_id = $pr";
        }

        $umajors = $this->session->userdata('umajorsid');

        $whereMajors = "";
        $whereMajorsRealisasi = "";
        if (!empty($_GET['majors_id']) && $_GET['majors_id'] != 0) {
            $majorsFilter = (int) $_GET['majors_id'];
            $whereMajorsRealisasi = " AND student.majors_majors_id = '$majorsFilter'";
        }

        $whereMajorsBebas = "";
        if (!empty($_GET['majors_id']) && $_GET['majors_id'] != 0) {
            $majorsFilter = (int) $_GET['majors_id'];
            $whereMajorsBebas = " AND account.account_majors_id = '$majorsFilter'";
        }

        if ($umajors != 0) {
            $whereMajors = " AND majors_id = '$umajors'";
        }

        if (!empty($_GET['start']) && !empty($_GET['end'])) {
            $start  = $_GET['start'];
            $end    = $_GET['end'];
        } else {
            $start  = $this->get_month($period['period_start'] . '-' . '07-01');
            $end    = $this->get_month($period['period_end'] . '-' . '06-30');
        }

        $whereDateBulan = "AND bulan.month_month_id BETWEEN '$start' AND '$end'";

        $rp_bulan       = $this->db->query("SELECT
                                    majors.majors_id,
                                    majors.majors_short_name,
                                    CONCAT(pos.pos_name, ' ', majors.majors_short_name) unit,
                                    SUM(CASE WHEN bulan.bulan_status = 1 THEN bulan.bulan_bill ELSE 0 END) AS terbayar,
                                    SUM(CASE WHEN bulan.bulan_status = 0 THEN bulan.bulan_bill ELSE 0 END) AS belum
                                    FROM `bulan` LEFT JOIN `month` ON `month`.`month_id` = `bulan`.`month_month_id` 
                                    LEFT JOIN `student` ON `student`.`student_id` = `bulan`.`student_student_id` 
                                    LEFT JOIN `payment` ON `payment`.`payment_id` = `bulan`.`payment_payment_id` 
                                    LEFT JOIN `pos` ON `pos`.`pos_id` = `payment`.`pos_pos_id` 
                                    LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
                                    LEFT JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` 
                                    WHERE 1 $whereDateBulan $wherePeriod $whereMajors $whereMajorsRealisasi
                                    GROUP BY pos.pos_id, majors.majors_id, majors.majors_short_name, pos.pos_name ORDER BY pos.pos_name ASC")->result_array();

        $rp_bebas       = $this->db->query("SELECT
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
                                    JOIN student on bebas.student_student_id = student.student_id 
                                    WHERE 1 $wherePeriod $whereMajors $whereMajorsBebas
                                    GROUP BY pos.pos_id, majors.majors_id, majors.majors_short_name, pos.pos_name")->result_array();

        $data = array(
            'period'    => $period['period_start'] . '_' . $period['period_end'],
            'rp_bulan'  => $rp_bulan,
            'rp_bebas'  => $rp_bebas
        );

        return json_encode($data);
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


    private function get_month(String $date = null)
    {
        $month_name = pretty_date($date, 'F', false);

        $this->db->where('month_name', $month_name);
        $month = $this->db->get('month')->row_array();

        return $month['month_id'];
    }
}
