<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Worksheet_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('worksheet_id', $params['id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('worksheet_majors_id', $params['majors_id']);
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
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
            $this->db->order_by('period_start', 'desc');
            $this->db->order_by('majors_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('worksheet_id, worksheet_majors_id, worksheet_period_id, worksheet_nama_kepsek, worksheet_nama_bendahara,
        worksheet_nama_komite, worksheet_nip_kepsek, worksheet_nip_bendahara, worksheet_email_komite, worksheet_nominal,
        worksheet_status, worksheet_created_at, worksheet_updated_at');
        $this->db->select('majors_name, majors_short_name, period_start, period_end');

        $this->db->join('majors', 'majors.majors_id = worksheet.worksheet_majors_id', 'left');
        $this->db->join('period', 'period.period_id = worksheet.worksheet_period_id', 'left');

        $res = $this->db->get('worksheet');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_review($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('worksheet_id', $params['id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('worksheet_majors_id', $params['majors_id']);
        }

        if ($this->session->userdata('umajorsid') != '0') {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
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
            $this->db->order_by('period_start', 'desc');
            $this->db->order_by('majors_id', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('worksheet_id, worksheet_majors_id, worksheet_period_id, worksheet_nama_kepsek, worksheet_nama_bendahara,
        worksheet_nama_komite, worksheet_nip_kepsek, worksheet_nip_bendahara, worksheet_email_komite, worksheet_nominal,
        worksheet_status, worksheet_created_at, worksheet_updated_at');
        $this->db->select('majors_name, majors_short_name, period_start, period_end');

        $this->db->join('majors', 'majors.majors_id = worksheet.worksheet_majors_id', 'left');
        $this->db->join('period', 'period.period_id = worksheet.worksheet_period_id', 'left');

        $res = $this->db->get('worksheet');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['worksheet_id'])) {
            $this->db->set('worksheet_id', $data['worksheet_id']);
        }

        if (isset($data['worksheet_majors_id'])) {
            $this->db->set('worksheet_majors_id', $data['worksheet_majors_id']);
        }

        if (isset($data['worksheet_period_id'])) {
            $this->db->set('worksheet_period_id', $data['worksheet_period_id']);
        }

        if (isset($data['worksheet_nama_kepsek'])) {
            $this->db->set('worksheet_nama_kepsek', $data['worksheet_nama_kepsek']);
        }

        if (isset($data['worksheet_nama_bendahara'])) {
            $this->db->set('worksheet_nama_bendahara', $data['worksheet_nama_bendahara']);
        }

        if (isset($data['worksheet_nama_komite'])) {
            $this->db->set('worksheet_nama_komite', $data['worksheet_nama_komite']);
        }

        if (isset($data['worksheet_nip_kepsek'])) {
            $this->db->set('worksheet_nip_kepsek', $data['worksheet_nip_kepsek']);
        }

        if (isset($data['worksheet_nip_bendahara'])) {
            $this->db->set('worksheet_nip_bendahara', $data['worksheet_nip_bendahara']);
        }

        if (isset($data['worksheet_email_komite'])) {
            $this->db->set('worksheet_email_komite', $data['worksheet_email_komite']);
        }

        if (isset($data['worksheet_nominal'])) {
            $this->db->set('worksheet_nominal', $data['worksheet_nominal']);
        }

        if (isset($data['worksheet_status'])) {
            $this->db->set('worksheet_status', $data['worksheet_status']);
        }

        if (isset($data['worksheet_id'])) {
            $this->db->where('worksheet_id', $data['worksheet_id']);
            $this->db->update('worksheet');
            $id = $data['worksheet_id'];
        } else {
            $this->db->insert('worksheet');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('worksheet_id', $id);
        $this->db->delete('worksheet');
    }

    public function is_exist($field, $value)
    {
        $this->db->where($field, $value);

        return $this->db->count_all_results('student') > 0 ? TRUE : FALSE;
    }

    public function get_anggaran()
    {

        $wherePeriod = "";
        if (!empty($_REQUEST['period'])) {
            $wherePeriod = " AND period_id = $_REQUEST[period]";
        }

        $whereMajors = "";
        if (!empty($_REQUEST['majors'])) {
            $whereMajors = " AND majors_id = $_REQUEST[majors]";
        }

        $rp_bulan       = $this->db->query("SELECT
                                    SUM(CASE WHEN bulan.bulan_status = 1 THEN bulan.bulan_bill ELSE 0 END) AS terbayar,
                                    SUM(CASE WHEN bulan.bulan_status = 0 THEN bulan.bulan_bill ELSE 0 END) AS belum
                                    FROM `bulan` LEFT JOIN `month` ON `month`.`month_id` = `bulan`.`month_month_id` 
                                    LEFT JOIN `student` ON `student`.`student_id` = `bulan`.`student_student_id` 
                                    LEFT JOIN `payment` ON `payment`.`payment_id` = `bulan`.`payment_payment_id` 
                                    LEFT JOIN `pos` ON `pos`.`pos_id` = `payment`.`pos_pos_id` 
                                    LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
                                    LEFT JOIN `majors` ON `majors`.`majors_id` = `student`.`majors_majors_id` 
                                    WHERE 1 $wherePeriod $whereMajors
                                    GROUP BY pos.pos_id")->result_array();

        $rp_bebas       = $this->db->query("SELECT
                                    SUM(bebas.bebas_total_pay) - SUM(bebas.bebas_diskon) AS terbayar,
                                    SUM(bebas.bebas_bill) - SUM(bebas.bebas_total_pay) - SUM(bebas.bebas_diskon) AS belum
                                    FROM pos 
                                    JOIN payment ON pos.pos_id = payment.pos_pos_id 
                                    LEFT JOIN `period` ON `period`.`period_id` = `payment`.`period_period_id` 
                                    JOIN bebas ON payment.payment_id = bebas.payment_payment_id 
                                    JOIN account ON account.account_id = pos.account_account_id 
                                    JOIN majors ON majors.majors_id = account.account_majors_id 
                                    JOIN student on bebas.student_student_id = student.student_id 
                                    WHERE 1 $wherePeriod $whereMajors
                                    GROUP BY pos.pos_id")->result_array();

        $data = array(
            'rp_bulan'  => $rp_bulan,
            'rp_bebas'  => $rp_bebas
        );

        return json_encode($data);
    }
}
