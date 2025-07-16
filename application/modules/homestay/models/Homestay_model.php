<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homestay_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}


	// Get homestay from database
	function get($params = array())
	{
		if (isset($params['id'])) {
			$this->db->where('homestay_id', $params['id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('homestay_user_id', $params['user_id']);
		}

		if (isset($params['homestay_name'])) {
			$this->db->where('homestay_name', $params['homestay_name']);
		}

		if (isset($params['homestay_price'])) {
			$this->db->where('homestay_price', $params['homestay_price']);
		}

		if (isset($params['homestay_create_at'])) {
			$this->db->where('homestay_create_at', $params['homestay_create_at']);
		}

		if (isset($params['homestay_update_at'])) {
			$this->db->where('homestay_update_at', $params['homestay_update_at']);
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
			$this->db->order_by('homestay_id', 'desc');
		}

		$this->db->select('homestay_id, homestay_name, homestay_desc, homestay_latitude, homestay_longitude, homestay_price, homestay_create_at, homestay_update_at');
		$this->db->select('homestay_user_id, user_full_name');

		$this->db->join('users', 'users.user_id = homestay.homestay_user_id', 'left');

		$res = $this->db->get('homestay');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}

	function get_reservasi($params = array())
	{
		if (isset($params['homestay_id'])) {
			$this->db->where('reservasi_homestay_id', $params['homestay_id']);
		}

		if (isset($params['user_id'])) {
			$this->db->where('homestay_user_id', $params['user_id']);
		}

		if (isset($params['homestay_name'])) {
			$this->db->where('homestay_name', $params['homestay_name']);
		}

		if (isset($params['start']) and isset($params['end'])) {
			$this->db->where('reservasi_create_at >=', $params['start'] . ' 00:00:00');
			$this->db->where('reservasi_create_at <=', $params['end'] . ' 23:59:59');
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
			$this->db->order_by('reservasi_create_at', 'desc');
		}

		$this->db->select('reservasi_checkin, reservasi_checkout, reservasi_total, reservasi_status, reservasi_ref_id, reservasi_name, reservasi_hp, reservasi_create_at');
		$this->db->select('homestay_id, homestay_name');
		$this->db->select('homestay_user_id, user_full_name');

		$this->db->join('homestay', 'homestay.homestay_id = reservasi.reservasi_homestay_id', 'left');
		$this->db->join('users', 'users.user_id = homestay.homestay_user_id', 'left');

		$res = $this->db->get('reservasi');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}

	function get_gallery($params = array())
	{
		if (isset($params['id'])) {
			$this->db->where('gallery_id', $params['id']);
		}

		if (isset($params['homestay_id'])) {
			$this->db->where('gallery_homestay_id', $params['homestay_id']);
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
			$this->db->order_by('homestay_id', 'desc');
		}

		$this->db->select('homestay_id, homestay_name');
		$this->db->select('gallery_id, gallery_img, gallery_homestay_id');

		$this->db->join('homestay', 'homestay.homestay_id = gallery.gallery_homestay_id', 'left');

		$res = $this->db->get('gallery');

		if (isset($params['id'])) {
			return $res->row_array();
		} else {
			return $res->result_array();
		}
	}

	public function insert_gallery($params)
	{
		$data = array(
			'gallery_img' => $params['foto'],
			'gallery_homestay_id' => $params['homestay_id'],
		);

		$this->db->insert('gallery', $data);
	}

	// Add and update to database
	function add($data = array())
	{

		if (isset($data['homestay_id'])) {
			$this->db->set('homestay_id', $data['homestay_id']);
		}

		if (isset($data['homestay_name'])) {
			$this->db->set('homestay_name', $data['homestay_name']);
		}

		if (isset($data['homestay_desc'])) {
			$this->db->set('homestay_desc', $data['homestay_desc']);
		}

		if (isset($data['homestay_latitude'])) {
			$this->db->set('homestay_latitude', $data['homestay_latitude']);
		}

		if (isset($data['homestay_longitude'])) {
			$this->db->set('homestay_longitude', $data['homestay_longitude']);
		}

		if (isset($data['homestay_price'])) {
			$this->db->set('homestay_price', $data['homestay_price']);
		}

		if (isset($data['homestay_user_id'])) {
			$this->db->set('homestay_user_id', $data['homestay_user_id']);
		}

		if (isset($data['homestay_create_at'])) {
			$this->db->set('homestay_create_at', $data['homestay_create_at']);
		}

		if (isset($data['homestay_update_at'])) {
			$this->db->set('homestay_update_at', $data['homestay_update_at']);
		}

		if (isset($data['homestay_id'])) {
			$this->db->where('homestay_id', $data['homestay_id']);
			$this->db->update('homestay');
			$id = $data['homestay_id'];
		} else {
			$this->db->insert('homestay');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

	// Delete homestay to database
	function delete($id)
	{
		$this->db->where('homestay_id', $id);
		$this->db->delete('homestay');
	}

	function delete_gallery($id)
	{
		$this->db->where('gallery_id', $id);
		$this->db->delete('gallery');
	}
}

/* End of file homestay_model.php */
/* Location: ./application/modules/jurnal/models/homestay_model.php */