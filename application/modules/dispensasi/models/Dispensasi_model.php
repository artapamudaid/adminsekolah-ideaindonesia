<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispensasi_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    //sampai disini
	function get($params = array())
	{
		if(isset($params['id']))
		{
			$this->db->where('dispensasi_id ', $params['id']);
		}

		if (isset($params['id_users'])) {
			$this->db->where('dispensasi_users', $params['id_users']);
		}

		if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('dispensasi_input_date >=', $params['date_start']);
            $this->db->where('dispensasi_input_date <=', $params['date_end']);
        }

		if (isset($params['student_id'])) {
			$this->db->where('dispensasi_student_pj', $params['student_id']);
		}
        
        if($this->session->userdata('umajorsid') != '0')
        {
            // $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }
		
		if(isset($params['order_by'])){
		    $this->db->order_by($params['order_by'], 'desc');
		}else{
		    $this->db->order_by('dispensasi_id ', 'desc');
		}
        // $this->db->where('majors_status', '1');

		$this->db->select('surat_dispensasi.dispensasi_id, dispensasi_nomor_surat_id, dispensasi_desc, dispensasi_student_pj, dispensasi_student_id, dispensasi_date, dispensasi_time_start, dispensasi_time_end, dispensasi_lokasi, dispensasi_users');
        $this->db->select('student_full_name, student_nis, student_nisn');
        $this->db->select('class_name');

        $this->db->join('surat_nomor', 'surat_nomor.nomor_surat = surat_dispensasi.dispensasi_nomor_surat_id', 'left');
		$this->db->join('student', 'student.student_id = surat_dispensasi.dispensasi_student_pj', 'left');		
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');		
        $this->db->join('users', 'users.user_id = surat_dispensasi.dispensasi_users', 'left');

		$res = $this->db->get('surat_dispensasi');

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

		if(isset($data['dispensasi_id '])) {
			$this->db->set('dispensasi_id ', $data['dispensasi_id ']);
		}

		if(isset($data['nomor_surat'])) {
			$this->db->set('dispensasi_nomor_surat_id', $data['nomor_surat']);
		}

		if(isset($data['dispensasi_desc'])) {
			$this->db->set('dispensasi_desc', $data['dispensasi_desc']);
		}

		if(isset($data['dispensasi_student_pj'])) {
			$this->db->set('dispensasi_student_pj', $data['dispensasi_student_pj']);
		}

		if(isset($data['dispensasi_student_id'])) {
			$this->db->set('dispensasi_student_id', $data['dispensasi_student_id']);
		}

		if(isset($data['dispensasi_date'])) {
			$this->db->set('dispensasi_date', $data['dispensasi_date']);
		}

		if(isset($data['dispensasi_time_start'])) {
			$this->db->set('dispensasi_time_start', $data['dispensasi_time_start']);
		}

		if(isset($data['dispensasi_time_end'])) {
			$this->db->set('dispensasi_time_end', $data['dispensasi_time_end']);
		}

		if(isset($data['dispensasi_lokasi'])) {
			$this->db->set('dispensasi_lokasi', $data['dispensasi_lokasi']);
		}

		if(isset($data['id_users'])) {
			$this->db->set('dispensasi_users', $data['id_users']);
		}

		if (isset($data['dispensasi_id'])) {
			$this->db->where('dispensasi_id', $data['dispensasi_id']);
			$this->db->update('surat_dispensasi');
			$id = $data['dispensasi_id'];
		} else {
			$this->db->insert('surat_dispensasi');
			$id = $this->db->insert_id();
		}

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id;
	}

	public function add_nomor($data = array())
	{
		if(isset($data['nomor_surat'])) {
			$this->db->set('nomor_surat', $data['nomor_surat']);
		}

		if(isset($data['tahun'])) {
			$this->db->set('tahun', $data['tahun']);
		}

		$this->db->insert('surat_nomor');
		$id = $this->db->insert_id();

		$status = $this->db->affected_rows();
		return ($status == 0) ? FALSE : $id_nomor;
	}

    // Delete arsip to database
	function delete($id) {
		$this->db->where('dispensasi_id ', $id);
		$this->db->delete('surat_dispensasi');
	}

    function delete_nomor($id) {
		$this->db->where('nomor_id ', $id);
		$this->db->delete('surat_nomor');
	}
	
	

    // //========================================jenis model ===================

	// function get_jenis($params = array())
    // {
    //     if (isset($params['id'])) {
    //         $this->db->where('id_jenis', $params['id']);
    //     }

    //     if (isset($params['limit'])) {
    //         if (!isset($params['offset'])) {
    //             $params['offset'] = NULL;
    //         }

    //         $this->db->limit($params['limit'], $params['offset']);
    //     }
    //     if (isset($params['order_by'])) {
    //         $this->db->order_by($params['order_by'], 'desc');
    //     } else {
    //         $this->db->order_by('id_jenis', 'asc');
    //     }

    //     $this->db->select('id_jenis, jenis_arsip, majors_id, create_date');

    //     $res = $this->db->get('ars_jenis');

    //     if (isset($params['id'])) {
    //         return $res->row_array();
    //     } else {
    //         return $res->result_array();
    //     }
    // }

	// function add_jenis($data = array()) {

    //     if (isset($data['id_jenis'])) {
    //         $this->db->set('id_jenis', $data['id_jenis']);
    //     }

    //     if (isset($data['jenis_arsip'])) {
    //         $this->db->set('jenis_arsip', $data['jenis_arsip']);
    //     }

	// 	if (isset($data['majors_id'])) {
    //         $this->db->set('majors_id', $data['majors_id']);
    //     }

    //     if (isset($data['create_date'])) {
    //         $this->db->set('create_date', $data['create_date']);
    //     }

    //     if (isset($data['id_jenis'])) {
    //         $this->db->where('id_jenis', $data['id_jenis']);
    //         $this->db->update('ars_jenis');
    //         $id = $data['id_jenis'];
    //     } else {
    //         $this->db->insert('ars_jenis');
    //         $id = $this->db->insert_id(); 
    //     }

    //     $status = $this->db->affected_rows();
    //     return ($status == 0) ? FALSE : $id;
    // }

    // function delete_jenis($id) {
    //     $this->db->where('id_jenis', $id);
    //     $this->db->delete('ars_jenis');
    // }

    // //========================== satuan model====================

	// function get_satuan($params = array())
    // {
    //     if (isset($params['id'])) {
    //         $this->db->where('id_satuan', $params['id']);
    //     }

    //     if (isset($params['limit'])) {
    //         if (!isset($params['offset'])) {
    //             $params['offset'] = NULL;
    //         }

    //         $this->db->limit($params['limit'], $params['offset']);
    //     }
    //     if (isset($params['order_by'])) {
    //         $this->db->order_by($params['order_by'], 'desc');
    //     } else {
    //         $this->db->order_by('id_satuan', 'asc');
    //     }

    //     $this->db->select('id_satuan, nama_satuan, keterangan');

    //     $res = $this->db->get('ars_satuan');

    //     if (isset($params['id'])) {
    //         return $res->row_array();
    //     } else {
    //         return $res->result_array();
    //     }
    // }

	// function add_satuan($data = array()) {

    //     if (isset($data['id_satuan'])) {
    //         $this->db->set('id_satuan', $data['id_satuan']);
    //     }

    //     if (isset($data['nama_satuan'])) {
    //         $this->db->set('nama_satuan', $data['nama_satuan']);
    //     }

	// 	if (isset($data['keterangan'])) {
    //         $this->db->set('keterangan', $data['keterangan']);
    //     }

    //     if (isset($data['id_satuan'])) {
    //         $this->db->where('id_satuan', $data['id_satuan']);
    //         $this->db->update('ars_satuan');
    //         $id = $data['id_satuan'];
    //     } else {
    //         $this->db->insert('ars_satuan');
    //         $id = $this->db->insert_id(); 
    //     }

    //     $status = $this->db->affected_rows();
    //     return ($status == 0) ? FALSE : $id;
    // }

    // function delete_satuan($id) {
    //     $this->db->where('id_satuan', $id);
    //     $this->db->delete('ars_satuan');
    // }


    // //==================================== Lokasi Model ==================

    
	// function get_lokasi($params = array())
    // {
    //     if (isset($params['id'])) {
    //         $this->db->where('id_lokasi', $params['id']);
    //     }

    //     if (isset($params['limit'])) {
    //         if (!isset($params['offset'])) {
    //             $params['offset'] = NULL;
    //         }

    //         $this->db->limit($params['limit'], $params['offset']);
    //     }
    //     if (isset($params['order_by'])) {
    //         $this->db->order_by($params['order_by'], 'desc');
    //     } else {
    //         $this->db->order_by('id_lokasi', 'asc');
    //     }

    //     $this->db->select('id_lokasi, nama_lokasi, tanggal');

    //     $res = $this->db->get('ars_lokasi');

    //     if (isset($params['id'])) {
    //         return $res->row_array();
    //     } else {
    //         return $res->result_array();
    //     }
    // }

	// function add_lokasi($data = array()) {

    //     if (isset($data['id_lokasi'])) {
    //         $this->db->set('id_lokasi', $data['id_lokasi']);
    //     }

    //     if (isset($data['nama_lokasi'])) {
    //         $this->db->set('nama_lokasi', $data['nama_lokasi']);
    //     }

	// 	if (isset($data['tanggal'])) {
    //         $this->db->set('tanggal', $data['tanggal']);
    //     }

    //     if (isset($data['id_lokasi'])) {
    //         $this->db->where('id_lokasi', $data['id_lokasi']);
    //         $this->db->update('ars_lokasi');
    //         $id = $data['id_lokasi'];
    //     } else {
    //         $this->db->insert('ars_lokasi');
    //         $id = $this->db->insert_id(); 
    //     }

    //     $status = $this->db->affected_rows();
    //     return ($status == 0) ? FALSE : $id;
    // }

    // function delete_lokasi($id) {
    //     $this->db->where('id_lokasi', $id);
    //     $this->db->delete('ars_lokasi');
    // }
}
