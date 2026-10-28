<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transfer_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function get_noref($like)
	{
		$today = date('Y-m-d');

		$this->db->select('MAX(RIGHT(log_tf_balance_id, 4)) AS no_max');
		$this->db->from('log_tf');
		$this->db->where('DATE(log_tf_input_date)', $today);
		$this->db->like('log_tf_balance_id', $like, 'after');

		$query = $this->db->get();
		$result = $query->row();

		$nextNumber = 1;
		if (!empty($result) && !empty($result->no_max)) {
			$nextNumber = (int) $result->no_max + 1;
		}

		$noref = sprintf("%04d", $nextNumber); // selalu 4 digit
		return date('dmy') . $noref;
	}


	function get($params = array())
	{
		if (isset($params['id'])) {
			$this->db->where('log_tf_id', $params['id']);
		}

		if (isset($params['account_id'])) {
			$this->db->where('log_tf_account_id', $params['account_id']);
		}

		if (isset($params['start']) && isset($params['end'])) {
			$this->db->where('log_tf_date >=', $params['start']);
			$this->db->where('log_tf_date <=', $params['end']);
		}

		if ($this->session->userdata('umajorsid') != '0') {
			$this->db->where('majors_id', $this->session->userdata('umajorsid'));
		}

		$this->db->order_by('log_tf_id', 'desc');
		// $this->db->order_by('log_tf_date', 'desc');

		$this->db->where('majors_status', '1');

		$this->db->select('log_tf_id, log_tf_date, log_tf_kredit_id, log_tf_debit_id, log_tf_note, log_tf_balance_id');
		$this->db->select('majors_id, majors_short_name, majors_name');
		$this->db->select('kredit_id, kredit_desc, kredit_kas_noref, kredit_kas_account_id, kredit_value, kredit_date');
		$this->db->select('debit_id, debit_desc, debit_kas_noref, debit_kas_account_id, debit_value, debit_date');
		$this->db->select('account_description');
		$this->db->select('user_id, user_full_name');

		$this->db->join('kredit', 'kredit.kredit_id = log_tf.log_tf_kredit_id', 'left');
		$this->db->join('debit', 'debit.debit_id = log_tf.log_tf_debit_id', 'left');
		$this->db->join('majors', 'majors.majors_id = log_tf.log_tf_majors_id', 'left');
		$this->db->join('account', 'account.account_id = log_tf.log_tf_account_id', 'left');
		$this->db->join('users', 'users.user_id = log_tf.log_tf_user_id', 'left');

		$this->db->group_start();
		$this->db->where('kredit.kredit_kas_noref IS NOT NULL', null, false);
		$this->db->or_where('debit.debit_kas_noref IS NOT NULL', null, false);
		$this->db->group_end();

		$this->db->group_by('log_tf.log_tf_id');

		$res = $this->db->get('log_tf');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}

	//view_transfer rekap
	function get_transfer($params = array())
	{
		if (isset($params['id'])) {
			$this->db->where('log_tf_id', $params['id']);
		}

		if (isset($params['account_id'])) {
			$this->db->where('log_tf_account_id', $params['account_id']);
		}

		if (isset($params['start']) && isset($params['end'])) {
			$this->db->where('log_tf_date >=', $params['start']);
			$this->db->where('log_tf_date <=', $params['end']);
		}

		if ($this->session->userdata('umajorsid') != '0') {
			$this->db->where('majors_id', $this->session->userdata('umajorsid'));
		}

		$this->db->order_by('log_tf_id', 'desc');
		$this->db->group_by('log_tf_balance_id');

		$this->db->where('majors_status', '1');
		$this->db->select('`log_tf_id`, 
		`log_tf_date`, 
		`log_tf_kredit_id`, 
		`log_tf_debit_id`, 
		`log_tf_note`, 
		`log_tf_balance_id`, 
		`majors_id`,
		`debit_kas_noref`,
		`kredit_kas_noref`,
		`majors_short_name`,
		`kredit_kas_account_id`,
		`user_id`, 
		`user_full_name`, 
		`account_description`,
		GROUP_CONCAT(`debit_value`) AS `deb_value`,
		GROUP_CONCAT(`kredit_value`) AS `kre_value`, 
		GROUP_CONCAT(`debit_kas_noref`) AS `deb_kas_noref`,
		GROUP_CONCAT(`kredit_kas_noref`) AS `kre_kas_noref`, 
		GROUP_CONCAT(`log_tf_debit_id`) AS `combined_idsd`,
		GROUP_CONCAT(`log_tf_kredit_id`) AS `combined_idsk`, 
		GROUP_CONCAT(`account_description`) AS combane_name, 
		GROUP_CONCAT(`majors_short_name`) AS combane_majors_name');

		$res = $this->db->get('log_transfer_v');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}

	// Delete log_tf to database
	function delete($id)
	{
		$this->db->where('log_tf_id', $id);
		$this->db->delete('log_tf');
	}

	function save_kas($params)
	{

		$this->db->insert('kas', $params);
	}

	function save_log_tf($params)
	{

		$this->db->insert('log_tf', $params);
	}
}
