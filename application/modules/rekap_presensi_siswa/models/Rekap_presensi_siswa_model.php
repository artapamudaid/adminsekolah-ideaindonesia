<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rekap_presensi_siswa_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {

        if (isset($params['id'])) {
            $this->db->where('presensi_student.id', $params['id']);
        }

        if (isset($params['majors_id']) && !empty($params['majors_id'])) {
            $this->db->where('class.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['class_id']) && !empty($params['class_id']) && $params['class_id'] != 'all') {
            $this->db->where('presensi_student.id_class', $params['class_id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('presensi_student.id_student', $params['student_id']);
        }

        if (isset($params['rekap_presensi_search'])) {
            $this->db->where('student.student_full_name', $params['rekap_presensi_search']);
        }

        if (isset($params['tgl_awal']) || isset($params['tgl_akhir'])) {
            $this->db->where('presensi_student.date >=', $params['tgl_awal']);
            $this->db->where('presensi_student.date <=', $params['tgl_akhir']);
        }

        if (isset($params['group'])) {
            $this->db->group_by($params['group']);
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
            $this->db->order_by('student_full_name', 'ASC');
        }

        $this->db->select('presensi_student.*, student.student_full_name as nama_siswa, area_absensi.nama_area as area_absen_nama, class.class_name as nama_kelas, majors.majors_short_name as nama_unit');
        $this->db->join('student', 'student.student_id = presensi_student.id_student', 'left');
        $this->db->join('class', 'class.class_id = presensi_student.id_class', 'left');
        $this->db->join('majors', 'majors.majors_id = class.majors_majors_id', 'left');
        $this->db->join('area_absensi', 'area_absensi.id_area = presensi_student.id_area_absensi', 'left');
        $res = $this->db->get('presensi_student');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    function get_rekap($params = array())
    {

        if (isset($params['id'])) {
            $this->db->where('presensi_student.id', $params['id']);
        }

        if (isset($params['majors_id']) && !empty($params['majors_id'])) {
            $this->db->where('class.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['class_id']) && !empty($params['class_id']) && $params['class_id'] != 'all') {
            $this->db->where('presensi_student.id_class', $params['class_id']);
        }

        if (isset($params['student_id'])) {
            $this->db->where('presensi_student.id_student', $params['student_id']);
        }

        if (isset($params['rekap_presensi_search'])) {
            $this->db->where('student.student_full_name', $params['rekap_presensi_search']);
        }

        if (isset($params['tgl_awal']) || isset($params['tgl_akhir'])) {
            $this->db->where('presensi_student.date >=', $params['tgl_awal']);
            $this->db->where('presensi_student.date <=', $params['tgl_akhir']);
        }

        if (isset($params['group'])) {
            $this->db->group_by($params['group']);
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
            $this->db->order_by('id', 'desc');
        }

        $this->db->select('presensi_student.*, student.student_full_name as nama_siswa, area_absensi.nama_area as area_absen_nama, class.class_name as nama_kelas, majors.majors_short_name as nama_unit');
        $this->db->join('student', 'student.student_id = presensi_student.id_student', 'left');
        $this->db->join('class', 'class.class_id = presensi_student.id_class', 'left');
        $this->db->join('majors', 'majors.majors_id = class.majors_majors_id', 'left');
        $this->db->join('area_absensi', 'area_absensi.id_area = presensi_student.id_area_absensi', 'left');
        $res = $this->db->get('presensi_student');

        if (isset($params['id'])) {
            return $res->result_array();
        } else {
            return $res->result_array();
        }
    }

    public function get_absensi_bulan($class_id, $tgl_awal, $tgl_akhir, $majors_id = null)
    {
        $this->db->select('presensi_student.id_student, presensi_student.date as tanggal, presensi_student.jenis_absen, presensi_student.time, presensi_student.status');
        $this->db->where('presensi_student.date >=', $tgl_awal);
        $this->db->where('presensi_student.date <=', $tgl_akhir);

        if (!empty($class_id) && $class_id != 'all') {
            $this->db->where('presensi_student.id_class', $class_id);
        }

        if (!empty($majors_id)) {
            $this->db->join('class', 'class.class_id = presensi_student.id_class', 'left');
            $this->db->where('class.majors_majors_id', $majors_id);
        }

        $res = $this->db->get('presensi_student')->result_array();

        $out = [];

        foreach ($res as $r) {
            $out[$r['id_student']][$r['tanggal']][$r['jenis_absen']] = $r;
        }

        return $out;
    }

    public function get_rekap_counter($class_id, $tgl_awal, $tgl_akhir, $majors_id = null)
    {
        $this->db->select("
        presensi_student.id_student,
        SUM(presensi_student.jenis_absen='DATANG') hadir,
        SUM(presensi_student.jenis_absen IN('SAKIT','IZIN','ALPHA')) tidak_hadir,
        SUM(presensi_student.status='Terlambat') terlambat
    ");
        $this->db->where('presensi_student.date >=', $tgl_awal);
        $this->db->where('presensi_student.date <=', $tgl_akhir);
        $this->db->group_by('presensi_student.id_student');

        if (!empty($class_id) && $class_id != 'all') {
            $this->db->where('presensi_student.id_class', $class_id);
        }

        if (!empty($majors_id)) {
            $this->db->join('class', 'class.class_id = presensi_student.id_class', 'left');
            $this->db->where('class.majors_majors_id', $majors_id);
        }

        $q = $this->db->get('presensi_student')->result_array();

        $out = [];
        foreach ($q as $r) {
            $out[$r['id_student']] = $r;
        }

        return $out;
    }

    // Get From Databases
    function rekap($params = array())
    {

        if (isset($params['id'])) {
            $this->db->where('student.student_id', $params['id']);
        }

        if (isset($params['class_id']) && !empty($params['class_id']) && $params['class_id'] != 'all') {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id']) && !empty($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('student_full_name', 'asc');
        }

        $this->db->where('student.student_status', 1);

        $this->db->select('student.*, class.class_name as nama_kelas, majors.majors_short_name as nama_unit');
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $res = $this->db->get('student');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }

    public function rekap_kehadiran($params = array())
    {
        if (isset($params['id'])) {
            $this->db->where('student.student_id', $params['id']);
        }

        if (isset($params['class_id']) && !empty($params['class_id']) && $params['class_id'] != 'all') {
            $this->db->where('student.class_class_id', $params['class_id']);
        }

        if (isset($params['majors_id']) && !empty($params['majors_id'])) {
            $this->db->where('student.majors_majors_id', $params['majors_id']);
        }

        if (isset($params['order_by'])) {
            $this->db->order_by($params['order_by'], 'desc');
        } else {
            $this->db->order_by('student_id', 'desc');
        }

        $this->db->where('student.student_status', 1);

        $this->db->select('student.*, class.class_name as nama_kelas, majors.majors_short_name as nama_unit');
        $this->db->join('class', 'class.class_id = student.class_class_id', 'left');
        $this->db->join('majors', 'majors.majors_id = student.majors_majors_id', 'left');
        $res = $this->db->get('student');

        if (isset($params['id'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }
    }
}
