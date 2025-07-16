<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Batchpayment_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id', $params['id']);
        }

        if (isset($params['name'])) {
            $this->db->where('name', $params['name']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('batchpayment.bp_majors_id', $params['majors_id']);
        }

        if (isset($param['period_id'])) {
            $this->db->where('batchpayment.bp_period_id', $params['period_id']);
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
            $this->db->order_by('period.period_end', 'desc');
            $this->db->order_by('batchpayment.bp_majors_id', 'asc');
            $this->db->order_by('batchpayment.name', 'asc');
        }

        $this->db->where('majors_status', '1');

        $this->db->select('id, name, bp_majors_id, bp_period_id, majors_id, majors_short_name,
        period_id, period_start, period_end');
        $this->db->join('majors', 'majors.majors_id=batchpayment.bp_majors_id', 'left');
        $this->db->join('period', 'period.period_id=batchpayment.bp_period_id', 'left');

        $res = $this->db->get('batchpayment');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_item($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('item_id', $params['id']);
        }

        if (isset($params['batch_id'])) {
            $this->db->where('id', $params['batch_id']);
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
            $this->db->order_by('payment.payment_id', 'asc');
        }

        $this->db->select('batch_item_id, payment_id, pos_name, period_start, period_end');

        $this->db->join('payment', 'payment.payment_id=batch_item.batch_item_payment_id', 'left');
        $this->db->join('period', 'period.period_id=payment.period_period_id', 'left');
        $this->db->join('pos', 'pos.pos_id=payment.pos_pos_id', 'left');
        $this->db->join('batchpayment', 'batchpayment.id=batch_item.batch_item_batchpayment_id', 'left');
        $this->db->join('majors', 'majors.majors_id=batchpayment.bp_majors_id', 'left');

        $res = $this->db->get('batch_item');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function add($data = array())
    {

        if (isset($data['id'])) {
            $this->db->set('id', $data['id']);
        }

        if (isset($data['name'])) {
            $this->db->set('name', $data['name']);
        }

        if (isset($data['bp_majors_id'])) {
            $this->db->set('bp_majors_id', $data['bp_majors_id']);
        }

        if (isset($data['bp_period_id'])) {
            $this->db->set('bp_period_id', $data['bp_period_id']);
        }

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('batchpayment');
            $id = $data['id'];
        } else {
            $this->db->insert('batchpayment');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function add_item($data = array())
    {

        if (isset($data['batch_item_batchpayment_id'])) {
            $this->db->set('batch_item_batchpayment_id', $data['batch_item_batchpayment_id']);
        }

        if (isset($data['batch_item_payment_id'])) {
            $this->db->set('batch_item_payment_id', $data['batch_item_payment_id']);
        }

        if (isset($data['batch_item_id'])) {
            $this->db->where('batch_item_id', $data['batch_item_id']);
            $this->db->update('batch_item');
            $id = $data['batch_item_id'];
        } else {
            $this->db->insert('batch_item');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('batchpayment');
    }

    function delete_item($id)
    {
        $this->db->where('batch_item_id', $id);
        $this->db->delete('batch_item');
    }
}
