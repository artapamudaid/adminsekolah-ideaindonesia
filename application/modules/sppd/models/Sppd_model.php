<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sppd_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('id_sppd', $params['id']);
		}

		if (isset($params['id_users'])) {
			$this->db->where('id_users', $params['id_users']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tanggal_input >=', $params['date_start']);
            $this->db->where('tanggal_input <=', $params['date_end']);
        }

		if (isset($params['employee_id'])) {
			$this->db->where('sppd.perintah_id', $params['employee_id']);
		}
        
        if($this->session->userdata('umajorsid') != '0')
        {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('id_sppd', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('sppd.id_sppd, no_sppd, deskripsi, perintah_id, transportasi, tmp_berangkat, tmp_tujuan, lama_perjalanan, tgl_berangkat, tgl_kembali, diperintah_id, anggota_id, instansi_anggaran, mata_anggaran, dasar_surat, keterangan, id_users, user_full_name, tanggal_input');
        $this->db->select('emp_a.employee_name AS nama_perintah, emp_a.employee_nip AS nip_perintah, emp_a.employee_position_id AS position_perintah, emp_a.employee_category, emp_a.employee_phone');
        $this->db->select('emp_b.employee_name AS nama_diperintah, emp_b.employee_nip AS nip_diperintah, position_name, emp_b.employee_position_id AS position_diperintah, emp_b.employee_category, emp_b.employee_phone');

		$this->db->join('employee AS emp_a', 'sppd.perintah_id = emp_a.employee_id', 'left');
		$this->db->join('employee AS emp_b', 'sppd.diperintah_id = emp_b.employee_id', 'left');
		$this->db->join('position', 'position.position_id = emp_b.employee_position_id', 'left');

		$this->db->join('users', 'sppd.id_users = users.user_id ', 'left');
		$this->db->join('majors', 'majors.majors_id = users.user_majors_id', 'left');

		$res = $this->db->get('sppd');

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

		if(isset($data['id_sppd'])) {
			$this->db->set('id_sppd', $data['id_sppd']);
		}

		if(isset($data['no_sppd'])) {
			$this->db->set('no_sppd', $data['no_sppd']);
		}

		if(isset($data['perintah_id'])) {
			$this->db->set('perintah_id', $data['perintah_id']);
		}

		if(isset($data['deskripsi'])) {
			$this->db->set('deskripsi', $data['deskripsi']);
		}

		if(isset($data['transportasi'])) {
			$this->db->set('transportasi', $data['transportasi']);
		}

		if(isset($data['tmp_berangkat'])) {
			$this->db->set('tmp_berangkat', $data['tmp_berangkat']);
		}

		if(isset($data['tmp_tujuan'])) {
			$this->db->set('tmp_tujuan', $data['tmp_tujuan']);
		}

		if(isset($data['tgl_berangkat'])) {
			$this->db->set('tgl_berangkat', $data['tgl_berangkat']);
		}

		if(isset($data['tgl_kembali'])) {
			$this->db->set('tgl_kembali', $data['tgl_kembali']);
		}

		if(isset($data['lama_perjalanan'])) {
			$this->db->set('lama_perjalanan', $data['lama_perjalanan']);
		}

		if(isset($data['diperintah_id'])) {
			$this->db->set('diperintah_id', $data['diperintah_id']);
		}

		if(isset($data['anggota_id'])) {
			$this->db->set('anggota_id', $data['anggota_id']);
		}

		if(isset($data['instansi_anggaran'])) {
			$this->db->set('instansi_anggaran', $data['instansi_anggaran']);
		}

		if(isset($data['mata_anggaran'])) {
			$this->db->set('mata_anggaran', $data['mata_anggaran']);
		}

		if(isset($data['dasar_surat'])) {
			$this->db->set('dasar_surat', $data['dasar_surat']);
		}

		if(isset($data['keterangan'])) {
			$this->db->set('keterangan', $data['keterangan']);
		}

		if(isset($data['id_users'])) {
			$this->db->set('id_users', $data['id_users']);
		}

		if (isset($data['id_sppd'])) {
			$this->db->where('id_sppd', $data['id_sppd']);
			$this->db->update('sppd');
			$id = $data['id_sppd'];
		} else {
			$this->db->insert('sppd');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('id_sppd', $id);
		$this->db->delete('sppd');
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
