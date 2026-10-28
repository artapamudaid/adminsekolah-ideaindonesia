<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Bulan_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Databases 

    function get_cari_bulan($id_periode, $id_tagihan, $id_kelas)
    {
        if ($id_kelas != '0') {
            $query = "SELECT DISTINCT `bulan_id`, `bulan_bill`, `bulan_date_pay`, `bulan_number_pay`, `bulan_status`, `bulan_input_date`, `bulan_last_update`, `student_student_id`, `student_img`, `student_nis`, `student_full_name`, `student_name_of_mother`, `student_parent_phone`, `student`.`class_class_id`, `student`.`majors_majors_id`, `majors_name`, `majors_short_name`, `class_name`, `payment_payment_id`, `period_period_id`, `period_status`, `period_start`, `period_end`, `pos_name`, `payment_type`, `user_user_id`, `user_full_name`, `month_month_id`, `month_name`, `bulan`.`student_student_id`, `bulan`.`student_student_id` 
            FROM `bulan` 
            LEFT JOIN `month` ON `month`.`month_id` = `bulan`.`month_month_id` 
            LEFT JOIN `student` ON `student`.`student_id` = `bulan`.`student_student_id` 
            LEFT JOIN `payment` ON `payment`.`payment_id` = `bulan`.`payment_payment_id` 
            LEFT JOIN `pos` ON `pos`.`pos_id` = `payment`.`pos_pos_id` 
            LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
            LEFT JOIN `class` ON `class`.`class_id` = `student`.`class_class_id` 
            LEFT JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` 
            LEFT JOIN `users` ON `users`.`user_id` = `bulan`.`user_user_id` 
            WHERE 
            `student`.`class_class_id` = '$id_kelas' 
            AND `payment`.`period_period_id` = '$id_periode' 
            AND majors.majors_status = 1 
            AND `payment`.`payment_id` = '$id_tagihan' 
            -- AND student.student_status = 1
            GROUP BY `bulan`.`student_student_id` ORDER BY `payment_payment_id`, `month_month_id` ASC";
        } else {
            $query = "SELECT DISTINCT `bulan_id`, `bulan_bill`, `bulan_date_pay`, `bulan_number_pay`, `bulan_status`, `bulan_input_date`, `bulan_last_update`, `student_student_id`, `student_img`, `student_nis`, `student_full_name`, `student_name_of_mother`, `student_parent_phone`,`class_name`, `student`.`class_class_id`, `student`.`majors_majors_id`, `majors_name`, `majors_short_name`, `payment_payment_id`, `period_period_id`, `period_status`, `period_start`, `period_end`, `pos_name`, `payment_type`, `user_user_id`, `user_full_name`, `month_month_id`, `month_name`, `bulan`.`student_student_id`, `bulan`.`student_student_id` 
            FROM `bulan` 
            LEFT JOIN `month` ON `month`.`month_id` = `bulan`.`month_month_id` 
            LEFT JOIN `student` ON `student`.`student_id` = `bulan`.`student_student_id` 
            LEFT JOIN `class` ON `class`.`class_id` = `student`.`class_class_id` 
            LEFT JOIN `payment` ON `payment`.`payment_id` = `bulan`.`payment_payment_id` 
            LEFT JOIN `pos` ON `pos`.`pos_id` = `payment`.`pos_pos_id` 
            LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
            LEFT JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` 
            LEFT JOIN `users` ON `users`.`user_id` = `bulan`.`user_user_id` 
            WHERE 
            `payment`.`period_period_id` = '$id_periode' 
            AND majors.majors_status = 1 
            AND `payment`.`payment_id` = '$id_tagihan' 
            -- AND student.student_status = 1
            GROUP BY `bulan`.`student_student_id` ORDER BY `payment_payment_id`, `month_month_id` ASC";
        }
        $data = $this->db->query($query)->result();
        return $data;
    }

    function get_batch_pembayaran($tahun, $nis)
    {
        $query = "SELECT `t`.`student_id`, `t`.`student_nis`, `t`.`student_full_name`, 
        `bp`.`name`, `d`.`period_id`, `d`.`period_start`, `d`.`period_end`, 
        `p`.`payment_id`, `p`.`payment_mode`, bp.id,
        SUM(`b`.`bulan_bill`) as `total`,
        SUM(if(`b`.`bulan_status`='1',`b`.`bulan_bill`,0)) as `dibayar`,
        GROUP_CONCAT(if(`b`.`month_month_id`='1',`b`.`bulan_bill`,NULL)) as `bill_jul`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='2',`b`.`bulan_bill`,NULL)) as `bill_agu`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='3',`b`.`bulan_bill`,NULL)) as `bill_sep`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='4',`b`.`bulan_bill`,NULL)) as `bill_okt`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='5',`b`.`bulan_bill`,NULL)) as `bill_nov`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='6',`b`.`bulan_bill`,NULL)) as `bill_des`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='7',`b`.`bulan_bill`,NULL)) as `bill_jan`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='8',`b`.`bulan_bill`,NULL)) as `bill_feb`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='9',`b`.`bulan_bill`,NULL)) as `bill_mar`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='10',`b`.`bulan_bill`,NULL)) as `bill_apr`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='11',`b`.`bulan_bill`,NULL)) as `bill_mei`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='12',`b`.`bulan_bill`,NULL)) as `bill_jun`, 
        SUM(if(`b`.`month_month_id`='1',`b`.`bulan_status`,0)) as `status_jul`, 
        SUM(if(`b`.`month_month_id`='2',`b`.`bulan_status`,0)) as `status_agu`, 
        SUM(if(`b`.`month_month_id`='3',`b`.`bulan_status`,0)) as `status_sep`, 
        SUM(if(`b`.`month_month_id`='4',`b`.`bulan_status`,0)) as `status_okt`, 
        SUM(if(`b`.`month_month_id`='5',`b`.`bulan_status`,0)) as `status_nov`, 
        SUM(if(`b`.`month_month_id`='6',`b`.`bulan_status`,0)) as `status_des`, 
        SUM(if(`b`.`month_month_id`='7',`b`.`bulan_status`,0)) as `status_jan`, 
        SUM(if(`b`.`month_month_id`='8',`b`.`bulan_status`,0)) as `status_feb`, 
        SUM(if(`b`.`month_month_id`='9',`b`.`bulan_status`,0)) as `status_mar`, 
        SUM(if(`b`.`month_month_id`='10',`b`.`bulan_status`,0)) as `status_apr`, 
        SUM(if(`b`.`month_month_id`='11',`b`.`bulan_status`,0)) as `status_mei`, 
        SUM(if(`b`.`month_month_id`='12',`b`.`bulan_status`,0)) as `status_jun`,
        MAX(if(`b`.`month_month_id`='1',`b`.`bulan_date_pay`,0)) as `date_pay_jul`, 
        MAX(if(`b`.`month_month_id`='2',`b`.`bulan_date_pay`,0)) as `date_pay_agu`, 
        MAX(if(`b`.`month_month_id`='3',`b`.`bulan_date_pay`,0)) as `date_pay_sep`, 
        MAX(if(`b`.`month_month_id`='4',`b`.`bulan_date_pay`,0)) as `date_pay_okt`, 
        MAX(if(`b`.`month_month_id`='5',`b`.`bulan_date_pay`,0)) as `date_pay_nov`, 
        MAX(if(`b`.`month_month_id`='6',`b`.`bulan_date_pay`,0)) as `date_pay_des`, 
        MAX(if(`b`.`month_month_id`='7',`b`.`bulan_date_pay`,0)) as `date_pay_jan`, 
        MAX(if(`b`.`month_month_id`='8',`b`.`bulan_date_pay`,0)) as `date_pay_feb`, 
        MAX(if(`b`.`month_month_id`='9',`b`.`bulan_date_pay`,0)) as `date_pay_mar`, 
        MAX(if(`b`.`month_month_id`='10',`b`.`bulan_date_pay`,0)) as `date_pay_apr`, 
        MAX(if(`b`.`month_month_id`='11',`b`.`bulan_date_pay`,0)) as `date_pay_mei`, 
        MAX(if(`b`.`month_month_id`='12',`b`.`bulan_date_pay`,0)) as `date_pay_jun`, 
        MAX(if(`b`.`month_month_id`='1',`m`.`month_name`,'')) as `month_name_jul`, 
        MAX(if(`b`.`month_month_id`='2',`m`.`month_name`,'')) as `month_name_agu`, 
        MAX(if(`b`.`month_month_id`='3',`m`.`month_name`,'')) as `month_name_sep`, 
        MAX(if(`b`.`month_month_id`='4',`m`.`month_name`,'')) as `month_name_okt`, 
        MAX(if(`b`.`month_month_id`='5',`m`.`month_name`,'')) as `month_name_nov`, 
        MAX(if(`b`.`month_month_id`='6',`m`.`month_name`,'')) as `month_name_des`, 
        MAX(if(`b`.`month_month_id`='7',`m`.`month_name`,'')) as `month_name_jan`, 
        MAX(if(`b`.`month_month_id`='8',`m`.`month_name`,'')) as `month_name_feb`, 
        MAX(if(`b`.`month_month_id`='9',`m`.`month_name`,'')) as `month_name_mar`, 
        MAX(if(`b`.`month_month_id`='10',`m`.`month_name`,'')) as `month_name_apr`, 
        MAX(if(`b`.`month_month_id`='11',`m`.`month_name`,'')) as `month_name_mei`, 
        MAX(if(`b`.`month_month_id`='12',`m`.`month_name`,'')) as `month_name_jun`,
        MAX(if(`b`.`month_month_id`='1',`a`.`account_description`,'')) as `account_jul`, 
        MAX(if(`b`.`month_month_id`='2',`a`.`account_description`,'')) as `account_agu`, 
        MAX(if(`b`.`month_month_id`='3',`a`.`account_description`,'')) as `account_sep`, 
        MAX(if(`b`.`month_month_id`='4',`a`.`account_description`,'')) as `account_okt`, 
        MAX(if(`b`.`month_month_id`='5',`a`.`account_description`,'')) as `account_nov`, 
        MAX(if(`b`.`month_month_id`='6',`a`.`account_description`,'')) as `account_des`, 
        MAX(if(`b`.`month_month_id`='7',`a`.`account_description`,'')) as `account_jan`, 
        MAX(if(`b`.`month_month_id`='8',`a`.`account_description`,'')) as `account_feb`, 
        MAX(if(`b`.`month_month_id`='9',`a`.`account_description`,'')) as `account_mar`, 
        MAX(if(`b`.`month_month_id`='10',`a`.`account_description`,'')) as `account_apr`, 
        MAX(if(`b`.`month_month_id`='11',`a`.`account_description`,'')) as `account_mei`, 
        MAX(if(`b`.`month_month_id`='12',`a`.`account_description`,'')) as `account_jun`, 
        GROUP_CONCAT(if(`b`.`month_month_id`='1',`b`.`bulan_id`,NULL)) as `month_id_jul`,
        GROUP_CONCAT(if(`b`.`month_month_id`='2',`b`.`bulan_id`,NULL)) as `month_id_agu`,
        GROUP_CONCAT(if(`b`.`month_month_id`='3',`b`.`bulan_id`,NULL)) as `month_id_sep`,
        GROUP_CONCAT(if(`b`.`month_month_id`='4',`b`.`bulan_id`,NULL)) as `month_id_okt`,
        GROUP_CONCAT(if(`b`.`month_month_id`='5',`b`.`bulan_id`,NULL)) as `month_id_nov`,
        GROUP_CONCAT(if(`b`.`month_month_id`='6',`b`.`bulan_id`,NULL)) as `month_id_des`,
        GROUP_CONCAT(if(`b`.`month_month_id`='7',`b`.`bulan_id`,NULL)) as `month_id_jan`,
        GROUP_CONCAT(if(`b`.`month_month_id`='8',`b`.`bulan_id`,NULL)) as `month_id_feb`,
        GROUP_CONCAT(if(`b`.`month_month_id`='9',`b`.`bulan_id`,NULL)) as `month_id_mar`,
        GROUP_CONCAT(if(`b`.`month_month_id`='10',`b`.`bulan_id`,NULL)) as `month_id_apr`,
        GROUP_CONCAT(if(`b`.`month_month_id`='11',`b`.`bulan_id`,NULL)) as `month_id_mei`,
        GROUP_CONCAT(if(`b`.`month_month_id`='12',`b`.`bulan_id`,NULL)) as `month_id_jun`
        FROM `bulan` as `b` 
        JOIN `payment` as `p` ON `b`.`payment_payment_id` = `p`.`payment_id`
        JOIN `batch_item` as `bi` ON `bi`.`batch_item_payment_id` = `p`.`payment_id`
        JOIN `batchpayment` as `bp` ON `bp`.`id` = `bi`.`batch_item_batchpayment_id` 
        LEFT JOIN `account` as `a` ON `a`.`account_id` = `b`.`bulan_account_id` 
        JOIN `period` as `d` ON `d`.`period_id` = `p`.`period_period_id`
        JOIN `student` as `t` ON `t`.`student_id` = `b`.student_student_id
        JOIN `month` as `m` ON `m`.`month_id` = `b`.month_month_id
        WHERE `t`.`student_nis` = '$nis' AND `d`.`period_id` = '$tahun'
        AND p.payment_is_batch = '1'
        GROUP BY bp.id";

        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function get_pembayaran($tahun, $nis)
    {
        $query = "SELECT `t`.`student_id`, `t`.`student_nis`, `t`.`student_full_name`, 
            `s`.`pos_name`, `d`.`period_id`, `d`.`period_start`, `d`.`period_end`, 
            `p`.`payment_id`, `p`.`payment_mode`, 
            SUM(`b`.`bulan_bill`) as `total`,
            SUM(if(`b`.`bulan_status`='1',`b`.`bulan_bill`,0)) as `dibayar`,
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_bill`,0)) as `bill_jul`, 
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_bill`,0)) as `bill_agu`, 
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_bill`,0)) as `bill_sep`, 
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_bill`,0)) as `bill_okt`, 
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_bill`,0)) as `bill_nov`, 
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_bill`,0)) as `bill_des`, 
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_bill`,0)) as `bill_jan`, 
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_bill`,0)) as `bill_feb`, 
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_bill`,0)) as `bill_mar`, 
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_bill`,0)) as `bill_apr`, 
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_bill`,0)) as `bill_mei`, 
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_bill`,0)) as `bill_jun`, 
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_status`,0)) as `status_jul`, 
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_status`,0)) as `status_agu`, 
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_status`,0)) as `status_sep`, 
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_status`,0)) as `status_okt`, 
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_status`,0)) as `status_nov`, 
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_status`,0)) as `status_des`, 
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_status`,0)) as `status_jan`, 
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_status`,0)) as `status_feb`, 
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_status`,0)) as `status_mar`, 
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_status`,0)) as `status_apr`, 
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_status`,0)) as `status_mei`, 
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_status`,0)) as `status_jun`,
            MAX(if(`b`.`month_month_id`='1',`b`.`bulan_date_pay`,0)) as `date_pay_jul`, 
            MAX(if(`b`.`month_month_id`='2',`b`.`bulan_date_pay`,0)) as `date_pay_agu`, 
            MAX(if(`b`.`month_month_id`='3',`b`.`bulan_date_pay`,0)) as `date_pay_sep`, 
            MAX(if(`b`.`month_month_id`='4',`b`.`bulan_date_pay`,0)) as `date_pay_okt`, 
            MAX(if(`b`.`month_month_id`='5',`b`.`bulan_date_pay`,0)) as `date_pay_nov`, 
            MAX(if(`b`.`month_month_id`='6',`b`.`bulan_date_pay`,0)) as `date_pay_des`, 
            MAX(if(`b`.`month_month_id`='7',`b`.`bulan_date_pay`,0)) as `date_pay_jan`, 
            MAX(if(`b`.`month_month_id`='8',`b`.`bulan_date_pay`,0)) as `date_pay_feb`, 
            MAX(if(`b`.`month_month_id`='9',`b`.`bulan_date_pay`,0)) as `date_pay_mar`, 
            MAX(if(`b`.`month_month_id`='10',`b`.`bulan_date_pay`,0)) as `date_pay_apr`, 
            MAX(if(`b`.`month_month_id`='11',`b`.`bulan_date_pay`,0)) as `date_pay_mei`, 
            MAX(if(`b`.`month_month_id`='12',`b`.`bulan_date_pay`,0)) as `date_pay_jun`, 
            MAX(if(`b`.`month_month_id`='1',`m`.`month_name`,'')) as `month_name_jul`, 
            MAX(if(`b`.`month_month_id`='2',`m`.`month_name`,'')) as `month_name_agu`, 
            MAX(if(`b`.`month_month_id`='3',`m`.`month_name`,'')) as `month_name_sep`, 
            MAX(if(`b`.`month_month_id`='4',`m`.`month_name`,'')) as `month_name_okt`, 
            MAX(if(`b`.`month_month_id`='5',`m`.`month_name`,'')) as `month_name_nov`, 
            MAX(if(`b`.`month_month_id`='6',`m`.`month_name`,'')) as `month_name_des`, 
            MAX(if(`b`.`month_month_id`='7',`m`.`month_name`,'')) as `month_name_jan`, 
            MAX(if(`b`.`month_month_id`='8',`m`.`month_name`,'')) as `month_name_feb`, 
            MAX(if(`b`.`month_month_id`='9',`m`.`month_name`,'')) as `month_name_mar`, 
            MAX(if(`b`.`month_month_id`='10',`m`.`month_name`,'')) as `month_name_apr`, 
            MAX(if(`b`.`month_month_id`='11',`m`.`month_name`,'')) as `month_name_mei`, 
            MAX(if(`b`.`month_month_id`='12',`m`.`month_name`,'')) as `month_name_jun`,
            MAX(if(`b`.`month_month_id`='1',`a`.`account_description`,'')) as `account_jul`, 
            MAX(if(`b`.`month_month_id`='2',`a`.`account_description`,'')) as `account_agu`, 
            MAX(if(`b`.`month_month_id`='3',`a`.`account_description`,'')) as `account_sep`, 
            MAX(if(`b`.`month_month_id`='4',`a`.`account_description`,'')) as `account_okt`, 
            MAX(if(`b`.`month_month_id`='5',`a`.`account_description`,'')) as `account_nov`, 
            MAX(if(`b`.`month_month_id`='6',`a`.`account_description`,'')) as `account_des`, 
            MAX(if(`b`.`month_month_id`='7',`a`.`account_description`,'')) as `account_jan`, 
            MAX(if(`b`.`month_month_id`='8',`a`.`account_description`,'')) as `account_feb`, 
            MAX(if(`b`.`month_month_id`='9',`a`.`account_description`,'')) as `account_mar`, 
            MAX(if(`b`.`month_month_id`='10',`a`.`account_description`,'')) as `account_apr`, 
            MAX(if(`b`.`month_month_id`='11',`a`.`account_description`,'')) as `account_mei`, 
            MAX(if(`b`.`month_month_id`='12',`a`.`account_description`,'')) as `account_jun`, 
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_id`,0)) as `month_id_jul`,
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_id`,0)) as `month_id_agu`,
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_id`,0)) as `month_id_sep`,
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_id`,0)) as `month_id_okt`,
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_id`,0)) as `month_id_nov`,
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_id`,0)) as `month_id_des`,
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_id`,0)) as `month_id_jan`,
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_id`,0)) as `month_id_feb`,
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_id`,0)) as `month_id_mar`,
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_id`,0)) as `month_id_apr`,
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_id`,0)) as `month_id_mei`,
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_id`,0)) as `month_id_jun`
            FROM `bulan` as `b` 
            JOIN `payment` as `p` ON `b`.`payment_payment_id` = `p`.`payment_id` 
            JOIN `pos` as `s` ON `s`.`pos_id` = `p`.`pos_pos_id` 
            LEFT JOIN `account` as `a` ON `a`.`account_id` = `b`.`bulan_account_id` 
            JOIN `period` as `d` ON `d`.`period_id` = `p`.`period_period_id`
            JOIN `student` as `t` ON `t`.`student_id` = `b`.student_student_id
            JOIN `month` as `m` ON `m`.`month_id` = `b`.month_month_id
            WHERE `t`.`student_nis` = '$nis' AND `d`.`period_id` = '$tahun'
            AND p.payment_is_batch = '0'
            GROUP BY `p`.`payment_id`";

        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function cek_pembayaran($tahun, $nis)
    {
        $query = "SELECT `t`.`student_id`, `t`.`student_nis`, `t`.`student_full_name`, `s`.`pos_name`, `d`.`period_id`, `d`.`period_start`, `d`.`period_end`, `p`.`payment_id`, `p`.`payment_mode`, 
            SUM(`b`.`bulan_bill`) as `total`,
            SUM(if(`b`.`bulan_status`='1',`b`.`bulan_bill`,0)) as `dibayar`,
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_bill`,0)) as `bill_jul`, 
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_bill`,0)) as `bill_agu`, 
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_bill`,0)) as `bill_sep`, 
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_bill`,0)) as `bill_okt`, 
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_bill`,0)) as `bill_nov`, 
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_bill`,0)) as `bill_des`, 
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_bill`,0)) as `bill_jan`, 
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_bill`,0)) as `bill_feb`, 
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_bill`,0)) as `bill_mar`, 
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_bill`,0)) as `bill_apr`, 
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_bill`,0)) as `bill_mei`, 
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_bill`,0)) as `bill_jun`, 
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_status`,0)) as `status_jul`, 
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_status`,0)) as `status_agu`, 
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_status`,0)) as `status_sep`, 
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_status`,0)) as `status_okt`, 
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_status`,0)) as `status_nov`, 
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_status`,0)) as `status_des`, 
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_status`,0)) as `status_jan`, 
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_status`,0)) as `status_feb`, 
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_status`,0)) as `status_mar`, 
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_status`,0)) as `status_apr`, 
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_status`,0)) as `status_mei`, 
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_status`,0)) as `status_jun`,
            MAX(if(`b`.`month_month_id`='1',`b`.`bulan_date_pay`,0)) as `date_pay_jul`, 
            MAX(if(`b`.`month_month_id`='2',`b`.`bulan_date_pay`,0)) as `date_pay_agu`, 
            MAX(if(`b`.`month_month_id`='3',`b`.`bulan_date_pay`,0)) as `date_pay_sep`, 
            MAX(if(`b`.`month_month_id`='4',`b`.`bulan_date_pay`,0)) as `date_pay_okt`, 
            MAX(if(`b`.`month_month_id`='5',`b`.`bulan_date_pay`,0)) as `date_pay_nov`, 
            MAX(if(`b`.`month_month_id`='6',`b`.`bulan_date_pay`,0)) as `date_pay_des`, 
            MAX(if(`b`.`month_month_id`='7',`b`.`bulan_date_pay`,0)) as `date_pay_jan`, 
            MAX(if(`b`.`month_month_id`='8',`b`.`bulan_date_pay`,0)) as `date_pay_feb`, 
            MAX(if(`b`.`month_month_id`='9',`b`.`bulan_date_pay`,0)) as `date_pay_mar`, 
            MAX(if(`b`.`month_month_id`='10',`b`.`bulan_date_pay`,0)) as `date_pay_apr`, 
            MAX(if(`b`.`month_month_id`='11',`b`.`bulan_date_pay`,0)) as `date_pay_mei`, 
            MAX(if(`b`.`month_month_id`='12',`b`.`bulan_date_pay`,0)) as `date_pay_jun`, 
            MAX(if(`b`.`month_month_id`='1',`m`.`month_name`,'')) as `month_name_jul`, 
            MAX(if(`b`.`month_month_id`='2',`m`.`month_name`,'')) as `month_name_agu`, 
            MAX(if(`b`.`month_month_id`='3',`m`.`month_name`,'')) as `month_name_sep`, 
            MAX(if(`b`.`month_month_id`='4',`m`.`month_name`,'')) as `month_name_okt`, 
            MAX(if(`b`.`month_month_id`='5',`m`.`month_name`,'')) as `month_name_nov`, 
            MAX(if(`b`.`month_month_id`='6',`m`.`month_name`,'')) as `month_name_des`, 
            MAX(if(`b`.`month_month_id`='7',`m`.`month_name`,'')) as `month_name_jan`, 
            MAX(if(`b`.`month_month_id`='8',`m`.`month_name`,'')) as `month_name_feb`, 
            MAX(if(`b`.`month_month_id`='9',`m`.`month_name`,'')) as `month_name_mar`, 
            MAX(if(`b`.`month_month_id`='10',`m`.`month_name`,'')) as `month_name_apr`, 
            MAX(if(`b`.`month_month_id`='11',`m`.`month_name`,'')) as `month_name_mei`, 
            MAX(if(`b`.`month_month_id`='12',`m`.`month_name`,'')) as `month_name_jun`,
            MAX(if(`b`.`month_month_id`='1',`a`.`account_description`,'')) as `account_jul`, 
            MAX(if(`b`.`month_month_id`='2',`a`.`account_description`,'')) as `account_agu`, 
            MAX(if(`b`.`month_month_id`='3',`a`.`account_description`,'')) as `account_sep`, 
            MAX(if(`b`.`month_month_id`='4',`a`.`account_description`,'')) as `account_okt`, 
            MAX(if(`b`.`month_month_id`='5',`a`.`account_description`,'')) as `account_nov`, 
            MAX(if(`b`.`month_month_id`='6',`a`.`account_description`,'')) as `account_des`, 
            MAX(if(`b`.`month_month_id`='7',`a`.`account_description`,'')) as `account_jan`, 
            MAX(if(`b`.`month_month_id`='8',`a`.`account_description`,'')) as `account_feb`, 
            MAX(if(`b`.`month_month_id`='9',`a`.`account_description`,'')) as `account_mar`, 
            MAX(if(`b`.`month_month_id`='10',`a`.`account_description`,'')) as `account_apr`, 
            MAX(if(`b`.`month_month_id`='11',`a`.`account_description`,'')) as `account_mei`, 
            MAX(if(`b`.`month_month_id`='12',`a`.`account_description`,'')) as `account_jun`, 
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_id`,0)) as `month_id_jul`,
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_id`,0)) as `month_id_agu`,
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_id`,0)) as `month_id_sep`,
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_id`,0)) as `month_id_okt`,
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_id`,0)) as `month_id_nov`,
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_id`,0)) as `month_id_des`,
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_id`,0)) as `month_id_jan`,
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_id`,0)) as `month_id_feb`,
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_id`,0)) as `month_id_mar`,
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_id`,0)) as `month_id_apr`,
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_id`,0)) as `month_id_mei`,
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_id`,0)) as `month_id_jun`
            FROM `bulan` as `b` 
            JOIN `payment` as `p` ON `b`.`payment_payment_id` = `p`.`payment_id` 
            JOIN `pos` as `s` ON `s`.`pos_id` = `p`.`pos_pos_id` 
            LEFT JOIN `account` as `a` ON `a`.`account_id` = `b`.`bulan_account_id` 
            JOIN `period` as `d` ON `d`.`period_id` = `p`.`period_period_id`
            JOIN `student` as `t` ON `t`.`student_id` = `b`.student_student_id
            JOIN `month` as `m` ON `m`.`month_id` = `b`.month_month_id
            WHERE `t`.`student_nis` = '$nis' AND `d`.`period_id` = '$tahun'
            GROUP BY `p`.`payment_id`";

        $data = $this->db->query($query)->result_array();;
        return $data;
    }

    function get_jumlah($n)
    {
        $query = "SELECT COUNT(DISTINCT bulan.payment_payment_id) as jml FROM student JOIN class ON student.class_class_id=class.class_id JOIN bulan ON bulan.student_student_id = student.student_id WHERE class.class_id = '$n'";
        $data = $this->db->query($query)->row_array();
        return $data;
    }

    function get_bulanan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('bulan_date_pay <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('class_id', 'asc');
            $this->db->order_by('student_nis', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->group_by('student_nis');
        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, sum(bulan_bill) as bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_paket($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['noref'])) {
            $this->db->where('bulan.bulan_noref', $params['noref']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['is_batch'])) {
            $this->db->where('payment_is_batch', $params['is_batch']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('bulan_date_pay <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        } else {
            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('month_month_id', 'asc');
            $this->db->order_by('batchpayment.name', 'asc');
        }

        $this->db->select('SUM(bulan_bill) AS total');
        $this->db->select('period_start, period_end');
        $this->db->select('month_name, month_month_id');
        $this->db->select('name');

        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');
        $this->db->join('batch_item', 'batch_item.batch_item_payment_id = payment.payment_id', 'left');
        $this->db->join('batchpayment', 'batch_item.batch_item_batchpayment_id = batchpayment.id', 'left');

        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['noref'])) {
            $this->db->where('bulan.bulan_noref', $params['noref']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['is_batch'])) {
            $this->db->where('payment_is_batch', $params['is_batch']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('bulan_date_pay <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('account_id', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_account_id, bulan_noref, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account.account_id, account.account_code, acc.account_description');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_last($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }

        if (isset($param['student_id'])) {
            $this->db->where('bulan.student_student_id', $param['student_id']);
        }

        if (isset($param['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $param['multiple_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['student_nis'])) {
            $this->db->where('student_nis', $param['student_nis']);
        }

        if (isset($param['bulan_bill'])) {
            $this->db->where('bulan_bill', $param['bulan_bill']);
        }

        if (isset($param['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $param['payment_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('bulan.month_month_id', $param['month_id']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['account_majors_id'])) {
            $this->db->where('account_majors_id', $param['account_majors_id']);
        }

        if (isset($param['period_id'])) {
            $this->db->where('payment.period_period_id', $param['period_id']);
        }

        if (isset($param['status'])) {
            $this->db->where('bulan.bulan_status', $param['status']);
        }

        if (isset($param['period_status'])) {
            $this->db->where('period_status', $param['period_status']);
        }

        if (isset($param['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $param['bulan_input_date']);
        }

        if (isset($param['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $param['bulan_last_update']);
        }

        if (isset($param['date_start']) and isset($param['date_end'])) {
            $this->db->where('bulan_date_pay >=', $param['date_start'] . ' 00:00:00');
            $this->db->where('bulan_date_pay <=', $param['date_end'] . ' 23:59:59');
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($param['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($param['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($param['limit'])) {
            if (!isset($param['offset'])) {
                $param['offset'] = NULL;
            }

            $this->db->limit($param['limit'], $param['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    //Mutasi
    function get_mutasi($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['kas_account_id'])) {
            $this->db->where_in('bulan.bulan_account_id', $params['kas_account_id']);
        }

        // if ($params['metode_id']==1){

        //     if (isset($params['account_majors_id'])) {
        //         $this->db->where('acc.account_majors_id', $params['account_majors_id']);
        //         $this->db->like('acc.account_description', 'Tunai');
        //         $this->db->like('bulan_noref', 'SP', 'after');
        //     }

        //     if (isset($params['kas_noref'])) {
        //         $this->db->like('acc.account_description', 'Tunai');
        //         $this->db->like('bulan_noref', 'SP', 'after');
        //     }
        // }elseif ($params['metode_id']==2){
        //     if(isset($params['account_majors_id']))
        //     {
        //         $this->db->where('acc.account_majors_id', $params['account_majors_id']);             
        //         $this->db->like('acc.account_description', 'Bank');
        //         $this->db->like('bulan_noref', 'SP', 'after');
        //     }

        //     if(isset($params['kas_noref']))
        //     {            
        //         $this->db->like('acc.account_description', 'Bank');
        //         $this->db->like('bulan_noref', 'SP', 'after');
        //     }
        // }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start']);
            $this->db->where('bulan_date_pay <=', $params['date_end']);
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();
        $this->db->group_by('bulan_date_pay');
        $this->db->group_by('pos_name');

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('account_id', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, SUM(`bulan`.`bulan_bill`) AS `total_bulan_bill`, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');

        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');
        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');

        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');

        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_last_mutasi($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }

        if (isset($param['student_id'])) {
            $this->db->where('bulan.student_student_id', $param['student_id']);
        }

        if (isset($param['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $param['multiple_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['student_nis'])) {
            $this->db->where('student_nis', $param['student_nis']);
        }

        if (isset($param['bulan_bill'])) {
            $this->db->where('bulan_bill', $param['bulan_bill']);
        }

        if (isset($param['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $param['payment_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('bulan.month_month_id', $param['month_id']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['kas_account_id'])) {
            $this->db->where_in('bulan.bulan_account_id', $param['kas_account_id']);
        }

        if (isset($param['period_id'])) {
            $this->db->where('payment.period_period_id', $param['period_id']);
        }

        if (isset($param['status'])) {
            $this->db->where('bulan.bulan_status', $param['status']);
        }

        if (isset($param['period_status'])) {
            $this->db->where('period_status', $param['period_status']);
        }

        if (isset($param['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $param['bulan_input_date']);
        }

        if (isset($param['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $param['bulan_last_update']);
        }

        if (isset($param['date_start']) and isset($param['date_end'])) {
            // $this->db->where('bulan_date_pay >=', $param['date_start']);
            $this->db->where('bulan_date_pay <=', $param['date_end']);
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        if (isset($param['limit'])) {
            if (!isset($param['offset'])) {
                $param['offset'] = NULL;
            }

            $this->db->limit($param['limit'], $param['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');

        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    //Jurnal Umum
    function get_jurnal($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('acc.account_majors_id', $params['account_majors_id']);
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($params['kas_noref'])) {
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start']);
            $this->db->where('bulan_date_pay <=', $params['date_end']);
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('account.account_id', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account.account_id, account.account_code');
        $this->db->select('acc.account_id, acc.account_code, acc.account_description');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');

        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');
        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');

        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');

        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_last_jurnal($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }

        if (isset($param['student_id'])) {
            $this->db->where('bulan.student_student_id', $param['student_id']);
        }

        if (isset($param['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $param['multiple_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['student_nis'])) {
            $this->db->where('student_nis', $param['student_nis']);
        }

        if (isset($param['bulan_bill'])) {
            $this->db->where('bulan_bill', $param['bulan_bill']);
        }

        if (isset($param['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $param['payment_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('bulan.month_month_id', $param['month_id']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['account_majors_id'])) {
            $this->db->where('acc.account_majors_id', $param['account_majors_id']);
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($param['kas_noref'])) {
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($param['period_id'])) {
            $this->db->where('payment.period_period_id', $param['period_id']);
        }

        if (isset($param['status'])) {
            $this->db->where('bulan.bulan_status', $param['status']);
        }

        if (isset($param['period_status'])) {
            $this->db->where('period_status', $param['period_status']);
        }

        if (isset($param['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $param['bulan_input_date']);
        }

        if (isset($param['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $param['bulan_last_update']);
        }

        if (isset($param['date_start']) and isset($param['date_end'])) {
            $this->db->where('bulan_date_pay <', $param['date_start']);
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($param['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($param['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($param['limit'])) {
            if (!isset($param['offset'])) {
                $param['offset'] = NULL;
            }

            $this->db->limit($param['limit'], $param['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');

        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    //Kas Tunai
    function get_kas($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('acc.account_majors_id', $params['account_majors_id']);
            $this->db->like('acc.account_description', 'Tunai');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($params['kas_noref'])) {
            $this->db->like('acc.account_description', 'Tunai');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start']);
            $this->db->where('bulan_date_pay <=', $params['date_end']);
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('account_id', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');

        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');
        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');

        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');

        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_last_kas($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }

        if (isset($param['student_id'])) {
            $this->db->where('bulan.student_student_id', $param['student_id']);
        }

        if (isset($param['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $param['multiple_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['student_nis'])) {
            $this->db->where('student_nis', $param['student_nis']);
        }

        if (isset($param['bulan_bill'])) {
            $this->db->where('bulan_bill', $param['bulan_bill']);
        }

        if (isset($param['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $param['payment_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('bulan.month_month_id', $param['month_id']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['account_majors_id'])) {
            $this->db->where('acc.account_majors_id', $param['account_majors_id']);
            $this->db->like('acc.account_description', 'Tunai');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($param['kas_noref'])) {
            $this->db->like('acc.account_description', 'Tunai');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($param['period_id'])) {
            // $this->db->where('payment.period_period_id', $param['period_id']);
        }

        if (isset($param['status'])) {
            $this->db->where('bulan.bulan_status', $param['status']);
        }

        if (isset($param['period_status'])) {
            $this->db->where('period_status', $param['period_status']);
        }

        if (isset($param['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $param['bulan_input_date']);
        }

        if (isset($param['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $param['bulan_last_update']);
        }

        // if (isset($param['date_start']) and isset($param['date_end'])) {
        //     $this->db->where('bulan_date_pay >=', $param['date_start']);
        //     $this->db->where('bulan_date_pay <=', $param['date_end']);
        // }

        if (isset($param['date_start'])) {
            $this->db->where('bulan_date_pay <', $param['date_start'] . ' 00:00:00');
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($param['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($param['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($param['limit'])) {
            if (!isset($param['offset'])) {
                $param['offset'] = NULL;
            }

            $this->db->limit($param['limit'], $param['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');

        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    //Bank
    function get_bank($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('acc.account_majors_id', $params['account_majors_id']);
            $this->db->like('acc.account_description', 'Bank');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($params['kas_noref'])) {
            $this->db->like('acc.account_description', 'Bank');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start']);
            $this->db->where('bulan_date_pay <=', $params['date_end']);
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('account_id', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');

        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');

        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_last_bank($param = array())
    {
        if (isset($param['id'])) {
            $this->db->where('bulan.bulan_id', $param['id']);
        }

        if (isset($param['student_id'])) {
            $this->db->where('bulan.student_student_id', $param['student_id']);
        }

        if (isset($param['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $param['multiple_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['student_nis'])) {
            $this->db->where('student_nis', $param['student_nis']);
        }

        if (isset($param['bulan_bill'])) {
            $this->db->where('bulan_bill', $param['bulan_bill']);
        }

        if (isset($param['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $param['payment_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('bulan.month_month_id', $param['month_id']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('student.class_class_id', $param['class_id']);
        }

        if (isset($param['account_majors_id'])) {
            $this->db->where('acc.account_majors_id', $param['account_majors_id']);
            $this->db->like('acc.account_description', 'Bank');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($param['kas_noref'])) {
            $this->db->like('acc.account_description', 'Bank');
            $this->db->like('bulan_noref', 'SP', 'after');
        }

        if (isset($param['period_id'])) {
            $this->db->where('payment.period_period_id', $param['period_id']);
        }

        if (isset($param['status'])) {
            $this->db->where('bulan.bulan_status', $param['status']);
        }

        if (isset($param['period_status'])) {
            $this->db->where('period_status', $param['period_status']);
        }

        if (isset($param['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $param['bulan_input_date']);
        }

        if (isset($param['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $param['bulan_last_update']);
        }

        if (isset($param['date_start']) and isset($param['date_end'])) {
            $this->db->where('bulan_date_pay >=', $param['date_start']);
            $this->db->where('bulan_date_pay <=', $param['date_end']);
        }

        if (isset($param['month_start']) and isset($param['month_end'])) {
            $this->db->where('month_month_id >=', $param['month_start']);
            $this->db->where('month_month_id <=', $param['month_end']);
        }

        if (isset($param['date'])) {
            $this->db->where('bulan_date_pay', $param['date']);
        }

        if (isset($param['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($param['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($param['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($param['limit'])) {
            if (!isset($param['offset'])) {
                $param['offset'] = NULL;
            }

            $this->db->limit($param['limit'], $param['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');

        $this->db->select('account.account_id, account.account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('kas', 'kas.kas_noref = bulan.bulan_noref');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $this->db->join('account AS acc', 'acc.account_id = bulan.bulan_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($param['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // Get From Databases
    function get_total($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['is_batch'])) {
            $this->db->where('payment_is_batch', $params['is_batch']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['noref'])) {
            $this->db->where('bulan.bulan_noref', $params['noref']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student.student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->where('majors_status', '1');

        $this->db->group_by('payment_payment_id');

        $this->db->order_by('month_month_id', 'asc');

        $this->db->select('bulan.bulan_id, sum(`bulan_bill`) as `total`, bulan_bill, bulan_date_pay, bulan_status, bulan_input_date, bulan_last_update');
        // $this->db->select('sum(bulan_bill) AS total');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, payment_type, pos_name, payment.pos_pos_id');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_report_tagihan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('majors_id', $params['majors_id']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('bulan_date_pay <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        $this->db->distinct();

        $this->db->order_by('student_nis', 'asc');

        $this->db->order_by('month_month_id', 'asc');

        //$this->db->group_by('student_nis');

        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    // Add and update to database
    function add($data = array())
    {
        // mapping antara key array dan nama kolom di database
        $map = [
            'bulan_id'          => 'bulan_id',
            'student_id'        => 'student_student_id',
            'payment_id'        => 'payment_payment_id',
            'month_id'          => 'month_month_id',
            'bulan_bill'        => 'bulan_bill',
            'bulan_number_pay'  => 'bulan_number_pay',
            'bulan_status'      => 'bulan_status',
            'bulan_date_pay'    => 'bulan_date_pay',
            'bulan_account_id'  => 'bulan_account_id',
            'bulan_noref'       => 'bulan_noref',
            'user_user_id'      => 'user_user_id',
            'bulan_input_date'  => 'bulan_input_date',
            'bulan_last_update' => 'bulan_last_update'
        ];

        foreach ($map as $key => $column) {
            if (array_key_exists($key, $data)) { // <– ini beda dari isset()
                $value = $data[$key];
                if (is_null($value)) {
                    $this->db->set($column, 'NULL', FALSE);
                } else {
                    $this->db->set($column, $value);
                }
            }
        }

        if (!empty($data['bulan_id'])) {
            $this->db->where('bulan_id', $data['bulan_id']);
            $this->db->update('bulan');
            $id = $data['bulan_id'];
        } else {
            $this->db->insert('bulan');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    public function get_total_bulan($param = array())
    {
        $this->db->select('SUM(bulan_bill) AS total');
        $this->db->from('bulan');

        if (!empty($param['noref'])) {
            $this->db->where('bulan_noref', $param['noref']);
        }

        // Hanya ambil yang status = 1
        $this->db->where('bulan_status', 1);

        if (!empty($param['last_update'])) {
            $this->db->where('DATE(bulan_last_update)', $param['last_update']);
        }

        $this->db->group_by('bulan_noref');

        $query = $this->db->get();
        $row = $query->row();

        return $row ? $row->total : 0;
    }

    // Get month from database
    function get_month($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('month_id', $params['id']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('month_id', 'asc');
        }

        $this->db->select('month_id, month_name');

        $res = $this->db->get('month');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add_month($data = array())
    {

        if (isset($data['month_id'])) {
            $this->db->set('month_id', $data['month_id']);
        }

        if (isset($data['month_name'])) {
            $this->db->set('month_name', $data['month_name']);
        }

        if (isset($data['month_id'])) {
            $this->db->where('month_id', $data['month_id']);
            $this->db->update('month');
            $id = $data['month_id'];
        } else {
            $this->db->insert('month');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_month($id)
    {
        $this->db->where('month_id', $id);
        $this->db->delete('month');
    }

    function get_day($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('day_id', $params['id']);
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('day_id', 'asc');
        }

        $this->db->select('day_id, day_name');

        $res = $this->db->get('day');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add_day($data = array())
    {

        if (isset($data['day_id'])) {
            $this->db->set('day_id', $data['day_id']);
        }

        if (isset($data['day_name'])) {
            $this->db->set('day_name', $data['day_name']);
        }

        if (isset($data['day_id'])) {
            $this->db->where('day_id', $data['day_id']);
            $this->db->update('day');
            $id = $data['day_id'];
        } else {
            $this->db->insert('day');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_day($id)
    {
        $this->db->where('day_id', $id);
        $this->db->delete('day');
    }

    // Delete to database
    function delete($id)
    {
        $this->db->where('bulan_id', $id);
        $this->db->delete('bulan');
    }

    function update_batch($data = array())
    {

        if (isset($data['bulan_bill'])) {
            $this->db->set('bulan_bill', $data['bulan_bill']);
        }

        if (isset($data['user_user_id'])) {
            $this->db->set('user_user_id', $data['user_user_id']);
        }

        if (isset($data['bulan_last_update'])) {
            $this->db->set('bulan_last_update', $data['bulan_last_update']);
        }

        if (isset($data['student_id']) and isset($data['payment_id'])) {
            $this->db->where('month_month_id', $data['month_id']);
            $this->db->where('student_student_id', $data['student_id']);
            $this->db->where('payment_payment_id', $data['payment_id']);
            $this->db->where('bulan_bill', $data['tarif_lama']);
            $this->db->where('bulan_status', $data['bulan_status']);
            $this->db->update('bulan');
            //$id = $data['bulan_id'];
        } else {
            $this->db->insert('bulan');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : TRUE;
    }

    function update_batch_siswa($data = array())
    {

        if (isset($data['bulan_bill'])) {
            $this->db->set('bulan_bill', $data['bulan_bill']);
        }

        if (isset($data['user_user_id'])) {
            $this->db->set('user_user_id', $data['user_user_id']);
        }

        if (isset($data['bulan_last_update'])) {
            $this->db->set('bulan_last_update', $data['bulan_last_update']);
        }

        if (isset($data['student_id']) and isset($data['payment_id'])) {
            $this->db->where('month_month_id', $data['month_id']);
            $this->db->where('student_student_id', $data['student_id']);
            $this->db->where('payment_payment_id', $data['payment_id']);
            $this->db->where('bulan_status', $data['bulan_status']);
            $this->db->update('bulan');
            //$id = $data['bulan_id'];
        } else {
            $this->db->insert('bulan');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : TRUE;
    }

    function get_bulananWithCOA($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('bulan.bulan_id', $params['id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('bulan.student_student_id', $params['student_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('bulan.bulan_id', $params['multiple_id']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['student_nis'])) {
            $this->db->where('student_nis', $params['student_nis']);
        }

        if (isset($params['bulan_bill'])) {
            $this->db->where('bulan_bill', $params['bulan_bill']);
        }

        if (isset($params['payment_id'])) {
            $this->db->where('bulan.payment_payment_id', $params['payment_id']);
        }

        if (isset($params['month_id'])) {
            $this->db->where('bulan.month_month_id', $params['month_id']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['account_majors_id'])) {
            $this->db->where('account_majors_id', $params['account_majors_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('payment.period_period_id', $params['period_id']);
        }

        if (isset($params['status'])) {
            $this->db->where('bulan.bulan_status', $params['status']);
        }

        if (isset($params['period_status'])) {
            $this->db->where('period_status', $params['period_status']);
        }

        if (isset($params['bulan_input_date'])) {
            $this->db->where('bulan_input_date', $params['bulan_input_date']);
        }

        if (isset($params['bulan_last_update'])) {
            $this->db->where('bulan_last_update', $params['bulan_last_update']);
        }

        if (isset($params['date_start']) and isset($params['date_end'])) {
            $this->db->where('bulan_date_pay >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('bulan_date_pay <=', $params['date_end'] . ' 23:59:59');
        }

        if (isset($params['month_start']) and isset($params['month_end'])) {
            $this->db->where('month_month_id >=', $params['month_start']);
            $this->db->where('month_month_id <=', $params['month_end']);
        }

        if (isset($params['date'])) {
            $this->db->where('bulan_date_pay', $params['date']);
        }

        if (isset($params['group'])) {

            $this->db->group_by('bulan.student_student_id');
        }

        if (isset($params['grup'])) {

            $this->db->group_by('bulan.month_month_id');
        }

        if (isset($params['paymentt'])) {

            $this->db->group_by('bulan.payment_payment_id');
        }

        if (isset($params['limit'])) {
            if (!isset($params['offset'])) {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        $this->db->distinct();

        $this->db->order_by('payment_payment_id');

        if (isset($params['urutan'])) {

            $this->db->order_by('bulan_date_pay', 'asc');
        } else {
            $this->db->order_by('class_id', 'asc');
            $this->db->order_by('student_nis', 'asc');
            $this->db->order_by('month_month_id', 'asc');
        }

        $this->db->group_by('student_nis');
        $this->db->where('majors_status', '1');

        $this->db->select('bulan.bulan_id, sum(bulan_bill) as bulan_bill, bulan_date_pay, bulan_number_pay, bulan_status, status_tagihan, bulan_input_date, bulan_last_update');

        $this->db->select('student_student_id, student_img, student_nis, student_full_name, student_name_of_mother, student_parent_phone, student.class_class_id, student.majors_majors_id, majors_name, majors_short_name, class_name');
        $this->db->select('payment_payment_id, period_period_id, period_status, period_start, period_end, pos_name, account_account_id, payment_type');
        $this->db->select('user_user_id, user_full_name');
        $this->db->select('account_id, account_code');

        $this->db->select('month_month_id, month_name');
        $this->db->select('bulan.student_student_id, bulan.student_student_id');
        $this->db->join('month', 'month.month_id = bulan.month_month_id', 'left');

        $this->db->join('student', 'student.student_id = bulan.student_student_id', 'left');
        $this->db->join('payment', 'payment.payment_id = bulan.payment_payment_id', 'left');
        $this->db->join('pos', 'pos.pos_id = payment.pos_pos_id', 'left');
        $this->db->join('period', 'period.period_id = payment.period_period_id', 'left');

        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $this->db->join('users', 'users.user_id = bulan.user_user_id', 'left');
        $this->db->join('account', 'account.account_id = pos.account_account_id', 'left');
        $res = $this->db->get('bulan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }


    function get_pembayaranWithCOA($tahun, $nis)
    {
        $query = "SELECT `t`.`student_id`, `t`.`student_nis`, `t`.`student_full_name`, 
            `s`.`pos_name`, `d`.`period_id`, `d`.`period_start`, `d`.`period_end`, 
            `p`.`payment_id`, `p`.`payment_mode`, 
            SUM(`b`.`bulan_bill`) as `total`,
            SUM(if(`b`.`bulan_status`='1',`b`.`bulan_bill`,0)) as `dibayar`,
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_bill`,0)) as `bill_jul`, 
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_bill`,0)) as `bill_agu`, 
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_bill`,0)) as `bill_sep`, 
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_bill`,0)) as `bill_okt`, 
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_bill`,0)) as `bill_nov`, 
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_bill`,0)) as `bill_des`, 
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_bill`,0)) as `bill_jan`, 
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_bill`,0)) as `bill_feb`, 
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_bill`,0)) as `bill_mar`, 
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_bill`,0)) as `bill_apr`, 
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_bill`,0)) as `bill_mei`, 
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_bill`,0)) as `bill_jun`, 
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_status`,0)) as `status_jul`, 
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_status`,0)) as `status_agu`, 
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_status`,0)) as `status_sep`, 
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_status`,0)) as `status_okt`, 
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_status`,0)) as `status_nov`, 
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_status`,0)) as `status_des`, 
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_status`,0)) as `status_jan`, 
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_status`,0)) as `status_feb`, 
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_status`,0)) as `status_mar`, 
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_status`,0)) as `status_apr`, 
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_status`,0)) as `status_mei`, 
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_status`,0)) as `status_jun`,
            MAX(if(`b`.`month_month_id`='1',`b`.`status_tagihan`,'')) as `status_tagihan_jul`,
            MAX(if(`b`.`month_month_id`='2',`b`.`status_tagihan`,'')) as `status_tagihan_agu`,
            MAX(if(`b`.`month_month_id`='3',`b`.`status_tagihan`,'')) as `status_tagihan_sep`,
            MAX(if(`b`.`month_month_id`='4',`b`.`status_tagihan`,'')) as `status_tagihan_okt`,
            MAX(if(`b`.`month_month_id`='5',`b`.`status_tagihan`,'')) as `status_tagihan_nov`,
            MAX(if(`b`.`month_month_id`='6',`b`.`status_tagihan`,'')) as `status_tagihan_des`,
            MAX(if(`b`.`month_month_id`='7',`b`.`status_tagihan`,'')) as `status_tagihan_jan`,
            MAX(if(`b`.`month_month_id`='8',`b`.`status_tagihan`,'')) as `status_tagihan_feb`,
            MAX(if(`b`.`month_month_id`='9',`b`.`status_tagihan`,'')) as `status_tagihan_mar`,
            MAX(if(`b`.`month_month_id`='10',`b`.`status_tagihan`,'')) as `status_tagihan_apr`,
            MAX(if(`b`.`month_month_id`='11',`b`.`status_tagihan`,'')) as `status_tagihan_mei`,
            MAX(if(`b`.`month_month_id`='12',`b`.`status_tagihan`,'')) as `status_tagihan_jun`,
            MAX(if(`b`.`month_month_id`='1',`b`.`bulan_date_pay`,0)) as `date_pay_jul`, 
            MAX(if(`b`.`month_month_id`='2',`b`.`bulan_date_pay`,0)) as `date_pay_agu`, 
            MAX(if(`b`.`month_month_id`='3',`b`.`bulan_date_pay`,0)) as `date_pay_sep`, 
            MAX(if(`b`.`month_month_id`='4',`b`.`bulan_date_pay`,0)) as `date_pay_okt`, 
            MAX(if(`b`.`month_month_id`='5',`b`.`bulan_date_pay`,0)) as `date_pay_nov`, 
            MAX(if(`b`.`month_month_id`='6',`b`.`bulan_date_pay`,0)) as `date_pay_des`, 
            MAX(if(`b`.`month_month_id`='7',`b`.`bulan_date_pay`,0)) as `date_pay_jan`, 
            MAX(if(`b`.`month_month_id`='8',`b`.`bulan_date_pay`,0)) as `date_pay_feb`, 
            MAX(if(`b`.`month_month_id`='9',`b`.`bulan_date_pay`,0)) as `date_pay_mar`, 
            MAX(if(`b`.`month_month_id`='10',`b`.`bulan_date_pay`,0)) as `date_pay_apr`, 
            MAX(if(`b`.`month_month_id`='11',`b`.`bulan_date_pay`,0)) as `date_pay_mei`, 
            MAX(if(`b`.`month_month_id`='12',`b`.`bulan_date_pay`,0)) as `date_pay_jun`, 
            MAX(if(`b`.`month_month_id`='1',`m`.`month_name`,'')) as `month_name_jul`, 
            MAX(if(`b`.`month_month_id`='2',`m`.`month_name`,'')) as `month_name_agu`, 
            MAX(if(`b`.`month_month_id`='3',`m`.`month_name`,'')) as `month_name_sep`, 
            MAX(if(`b`.`month_month_id`='4',`m`.`month_name`,'')) as `month_name_okt`, 
            MAX(if(`b`.`month_month_id`='5',`m`.`month_name`,'')) as `month_name_nov`, 
            MAX(if(`b`.`month_month_id`='6',`m`.`month_name`,'')) as `month_name_des`, 
            MAX(if(`b`.`month_month_id`='7',`m`.`month_name`,'')) as `month_name_jan`, 
            MAX(if(`b`.`month_month_id`='8',`m`.`month_name`,'')) as `month_name_feb`, 
            MAX(if(`b`.`month_month_id`='9',`m`.`month_name`,'')) as `month_name_mar`, 
            MAX(if(`b`.`month_month_id`='10',`m`.`month_name`,'')) as `month_name_apr`, 
            MAX(if(`b`.`month_month_id`='11',`m`.`month_name`,'')) as `month_name_mei`, 
            MAX(if(`b`.`month_month_id`='12',`m`.`month_name`,'')) as `month_name_jun`,
            MAX(if(`b`.`month_month_id`='1',`a`.`coa_name`,'')) as `account_jul`, 
            MAX(if(`b`.`month_month_id`='2',`a`.`coa_name`,'')) as `account_agu`, 
            MAX(if(`b`.`month_month_id`='3',`a`.`coa_name`,'')) as `account_sep`, 
            MAX(if(`b`.`month_month_id`='4',`a`.`coa_name`,'')) as `account_okt`, 
            MAX(if(`b`.`month_month_id`='5',`a`.`coa_name`,'')) as `account_nov`, 
            MAX(if(`b`.`month_month_id`='6',`a`.`coa_name`,'')) as `account_des`, 
            MAX(if(`b`.`month_month_id`='7',`a`.`coa_name`,'')) as `account_jan`, 
            MAX(if(`b`.`month_month_id`='8',`a`.`coa_name`,'')) as `account_feb`, 
            MAX(if(`b`.`month_month_id`='9',`a`.`coa_name`,'')) as `account_mar`, 
            MAX(if(`b`.`month_month_id`='10',`a`.`coa_name`,'')) as `account_apr`, 
            MAX(if(`b`.`month_month_id`='11',`a`.`coa_name`,'')) as `account_mei`, 
            MAX(if(`b`.`month_month_id`='12',`a`.`coa_name`,'')) as `account_jun`, 
            SUM(if(`b`.`month_month_id`='1',`b`.`bulan_id`,0)) as `month_id_jul`,
            SUM(if(`b`.`month_month_id`='2',`b`.`bulan_id`,0)) as `month_id_agu`,
            SUM(if(`b`.`month_month_id`='3',`b`.`bulan_id`,0)) as `month_id_sep`,
            SUM(if(`b`.`month_month_id`='4',`b`.`bulan_id`,0)) as `month_id_okt`,
            SUM(if(`b`.`month_month_id`='5',`b`.`bulan_id`,0)) as `month_id_nov`,
            SUM(if(`b`.`month_month_id`='6',`b`.`bulan_id`,0)) as `month_id_des`,
            SUM(if(`b`.`month_month_id`='7',`b`.`bulan_id`,0)) as `month_id_jan`,
            SUM(if(`b`.`month_month_id`='8',`b`.`bulan_id`,0)) as `month_id_feb`,
            SUM(if(`b`.`month_month_id`='9',`b`.`bulan_id`,0)) as `month_id_mar`,
            SUM(if(`b`.`month_month_id`='10',`b`.`bulan_id`,0)) as `month_id_apr`,
            SUM(if(`b`.`month_month_id`='11',`b`.`bulan_id`,0)) as `month_id_mei`,
            SUM(if(`b`.`month_month_id`='12',`b`.`bulan_id`,0)) as `month_id_jun`
            FROM `bulan` as `b` 
            JOIN `payment` as `p` ON `b`.`payment_payment_id` = `p`.`payment_id` 
            JOIN `pos` as `s` ON `s`.`pos_id` = `p`.`pos_pos_id` 
            LEFT JOIN `coa` as `a` ON `a`.`coa_id` = `b`.`bulan_account_id` 
            JOIN `period` as `d` ON `d`.`period_id` = `p`.`period_period_id`
            JOIN `student` as `t` ON `t`.`student_id` = `b`.student_student_id
            JOIN `month` as `m` ON `m`.`month_id` = `b`.month_month_id
            WHERE `t`.`student_nis` = '$nis' AND `d`.`period_id` = '$tahun'
            AND p.payment_is_batch = '0'
            GROUP BY `p`.`payment_id`";

        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}
