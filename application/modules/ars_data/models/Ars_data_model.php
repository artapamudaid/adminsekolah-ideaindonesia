<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ars_data_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('id_arsip', $params['id']);
		}

		if (isset($params['id_users'])) {
			$this->db->where('id_users', $params['id_users']);
		}

		if (isset($params['tanggal'])) {
			$this->db->where('tanggal', $params['tanggal']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('tahfidz_student_id', $params['student_id']);
		}
        
        if($this->session->userdata('umajorsid') != '0')
        {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('id_arsip', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('arsip.id_arsip, nama_arsip, file_arsip, arsip.tanggal, status, jumlah, arsip.id_jenis, arsip.id_users, arsip.id_satuan, lokasi, status, permision');
        $this->db->select('jenis_arsip, nama_lokasi, nama_satuan, keterangan, user_full_name');
		
		$this->db->join('ars_jenis', 'ars_jenis.id_jenis = arsip.id_jenis', 'left');
		
		$this->db->join('ars_lokasi', 'ars_lokasi.id_lokasi = arsip.lokasi', 'left');
		
        $this->db->join('ars_satuan', 'ars_satuan.id_satuan = arsip.id_satuan', 'left');
		
        $this->db->join('users', 'users.user_id = arsip.id_users', 'left');

		$res = $this->db->get('arsip');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}

    // Add and update to database
	function add($data = array()) {

		if(isset($data['id_arsip'])) {
			$this->db->set('id_arsip', $data['id_arsip']);
		}

		if(isset($data['id_jenis'])) {
			$this->db->set('id_jenis', $data['id_jenis']);
		}

		if(isset($data['id_users'])) {
			$this->db->set('id_users', $data['id_users']);
		}

		if(isset($data['nama_arsip'])) {
			$this->db->set('nama_arsip', $data['nama_arsip']);
		}

		if(isset($data['upl_arsip'])) {
			$this->db->set('file_arsip', $data['upl_arsip']);
		}

		if(isset($data['jumlah'])) {
			$this->db->set('jumlah', $data['jumlah']);
		}

		if(isset($data['id_satuan'])) {
			$this->db->set('id_satuan', $data['id_satuan']);
		}

		if(isset($data['lokasi'])) {
			$this->db->set('lokasi', $data['lokasi']);
		}

		if(isset($data['ket_isi'])) {
			$this->db->set('ket_isi', $data['ket_isi']);
		}

		if(isset($data['status'])) {
			$this->db->set('status', $data['status']);
		}

		if(isset($data['tanggal'])) {
			$this->db->set('tanggal', $data['tanggal']);
		}

		if(isset($data['permision'])) {
			$this->db->set('permision', $data['permision']);
		}

		if (isset($data['id_arsip'])) {
			$this->db->where('id_arsip', $data['id_arsip']);
			$this->db->update('arsip');
			$id = $data['id_arsip'];
		} else {
			$this->db->insert('arsip');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('id_arsip ', $id);
		$this->db->delete('arsip');
	}
	
	function get_sum($params = array()){
	    if(isset($params['id']))
		{
			$this->db->where('id_arsip ', $params['id']);
		}

		if (isset($params['period_id'])) {
			$this->db->where('tahfidz_period_id', $params['period_id']);
		}

		if (isset($params['student_id'])) {
			$this->db->where('tahfidz_student_id', $params['student_id']);
		}
        
        $this->db->group_by('tahfidz_student_id');
		
        $this->db->where('majors_status', '1');
        
        $this->db->select('student_id, student_full_name, student_nis');
        $this->db->select('class_id, class_name');
        $this->db->select('period_id');
        $this->db->select('majors_id, majors_short_name, majors_name');
		$this->db->select('SUM(tahfidz_new) AS tahfidzSum');
		
		$this->db->join('student', 'student.student_id = tahfidz.tahfidz_student_id', 'left');
		
		$this->db->join('period', 'period.period_id = tahfidz.tahfidz_period_id', 'left');
		
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
		
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');

		$res = $this->db->get('tahfidz');

		if(isset($params['id']))
		{
			return $res->row_array();
		}
		else
		{
			return $res->result_array();
		}
	}


    //========================================jenis model ===================

	function get_jenis($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id_jenis', $params['id']);
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
            $this->db->order_by('id_jenis', 'asc');
        }

        $this->db->select('id_jenis, jenis_arsip, majors_id, create_date');

        $res = $this->db->get('ars_jenis');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

	function add_jenis($data = array()) {

        if (isset($data['id_jenis'])) {
            $this->db->set('id_jenis', $data['id_jenis']);
        }

        if (isset($data['jenis_arsip'])) {
            $this->db->set('jenis_arsip', $data['jenis_arsip']);
        }

		if (isset($data['majors_id'])) {
            $this->db->set('majors_id', $data['majors_id']);
        }

        if (isset($data['create_date'])) {
            $this->db->set('create_date', $data['create_date']);
        }

        if (isset($data['id_jenis'])) {
            $this->db->where('id_jenis', $data['id_jenis']);
            $this->db->update('ars_jenis');
            $id = $data['id_jenis'];
        } else {
            $this->db->insert('ars_jenis');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_jenis($id) {
        $this->db->where('id_jenis', $id);
        $this->db->delete('ars_jenis');
    }

    //========================== satuan model====================

	function get_satuan($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id_satuan', $params['id']);
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
            $this->db->order_by('id_satuan', 'asc');
        }

        $this->db->select('id_satuan, nama_satuan, keterangan');

        $res = $this->db->get('ars_satuan');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

	function add_satuan($data = array()) {

        if (isset($data['id_satuan'])) {
            $this->db->set('id_satuan', $data['id_satuan']);
        }

        if (isset($data['nama_satuan'])) {
            $this->db->set('nama_satuan', $data['nama_satuan']);
        }

		if (isset($data['keterangan'])) {
            $this->db->set('keterangan', $data['keterangan']);
        }

        if (isset($data['id_satuan'])) {
            $this->db->where('id_satuan', $data['id_satuan']);
            $this->db->update('ars_satuan');
            $id = $data['id_satuan'];
        } else {
            $this->db->insert('ars_satuan');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_satuan($id) {
        $this->db->where('id_satuan', $id);
        $this->db->delete('ars_satuan');
    }


    //==================================== Lokasi Model ==================

    
	function get_lokasi($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('id_lokasi', $params['id']);
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
            $this->db->order_by('id_lokasi', 'asc');
        }

        $this->db->select('id_lokasi, nama_lokasi, tanggal');

        $res = $this->db->get('ars_lokasi');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

	function add_lokasi($data = array()) {

        if (isset($data['id_lokasi'])) {
            $this->db->set('id_lokasi', $data['id_lokasi']);
        }

        if (isset($data['nama_lokasi'])) {
            $this->db->set('nama_lokasi', $data['nama_lokasi']);
        }

		if (isset($data['tanggal'])) {
            $this->db->set('tanggal', $data['tanggal']);
        }

        if (isset($data['id_lokasi'])) {
            $this->db->where('id_lokasi', $data['id_lokasi']);
            $this->db->update('ars_lokasi');
            $id = $data['id_lokasi'];
        } else {
            $this->db->insert('ars_lokasi');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete_lokasi($id) {
        $this->db->where('id_lokasi', $id);
        $this->db->delete('ars_lokasi');
    }
}
