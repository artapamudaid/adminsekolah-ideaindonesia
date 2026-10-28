<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class Presensi_harian_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function check($param = array())
    {

        if (isset($param['period_id'])) {
            $this->db->where('presensi_harian_period_id', $param['period_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('presensi_harian_month_id', $param['month_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('presensi_harian_date', $param['date']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('presensi_harian_class_id', $param['class_id']);
        }

        $this->db->from('presensi_harian');

        $res = $this->db->count_all_results();

        return $res;
    }

    function checkAbsensi($param = array())
    {

        if (isset($param['date'])) {
            $this->db->where('presensi_harian_date', $param['date']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('presensi_harian_class_id', $param['class_id']);
        }

        $this->db->from('presensi_student');

        $res = $this->db->count_all_results();

        return $res;
    }

    function addAbsensi($data = array())
    {
        if (isset($data['presensi_student_id'])) {
            $this->db->set('id_student', $data['presensi_student_id']);
        }

        if (isset($data['presensi_class_id'])) {
            $this->db->set('id_class', $data['presensi_class_id']);
        }

        if (isset($data['presensi_area'])) {
            $this->db->set('id_area_absensi', $data['presensi_area']);
        }

        if (isset($data['presensi_jenis'])) {
            $this->db->set('jenis_absen', $data['presensi_jenis']);
        }

        if (isset($data['presensi_date'])) {
            $this->db->set('date', $data['presensi_date']);
        }

        if (isset($data['presensi_time'])) {
            $this->db->set('time', $data['presensi_time']);
        }

        if (isset($data['presensi_longi'])) {
            $this->db->set('longi', $data['presensi_longi']);
        }

        if (isset($data['presensi_lati'])) {
            $this->db->set('lati', $data['presensi_lati']);
        }

        if (isset($data['presensi_note'])) {
            $this->db->set('note', $data['presensi_note']);
        }

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('presensi_student');
            $id = $data['id'];
        } else {
            $this->db->insert('presensi_student');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function cekAbsenByClass($class_id, $tanggal)
    {
        $this->db->select('s.student_id, s.student_nis, s.student_full_name, presensi.idAbsen');
        $this->db->from('student s');
        $this->db->join(
            "(SELECT id AS idAbsen, id_student AS idSiswa
                    FROM presensi_student
                    WHERE date = '$tanggal' AND id_class = $class_id
            ) presensi",
            'presensi.idSiswa = s.student_id',
            'left'
        );
        $this->db->where('s.class_class_id', $class_id);
        $this->db->where('presensi.idAbsen IS NULL');
        $this->db->where('s.student_status = 1');
        $query = $this->db->get();

        return $query->result_array();
    }

    function add($data = array())
    {

        if (isset($data['presensi_harian_id'])) {
            $this->db->set('presensi_harian_id', $data['presensi_harian_id']);
        }

        if (isset($data['presensi_harian_period_id'])) {
            $this->db->set('presensi_harian_period_id', $data['presensi_harian_period_id']);
        }

        if (isset($data['presensi_harian_month_id'])) {
            $this->db->set('presensi_harian_month_id', $data['presensi_harian_month_id']);
        }

        if (isset($data['presensi_harian_date'])) {
            $this->db->set('presensi_harian_date', $data['presensi_harian_date']);
        }

        if (isset($data['presensi_harian_class_id'])) {
            $this->db->set('presensi_harian_class_id', $data['presensi_harian_class_id']);
        }

        if (isset($data['presensi_harian_student_id'])) {
            $this->db->set('presensi_harian_student_id', $data['presensi_harian_student_id']);
        }

        if (isset($data['presensi_harian_status'])) {
            $this->db->set('presensi_harian_status', $data['presensi_harian_status']);
        }

        if (isset($data['presensi_harian_id'])) {
            $this->db->where('presensi_harian_id', $data['presensi_harian_id']);
            $this->db->update('presensi_harian');
            $id = $data['presensi_harian_id'];
        } else {
            $this->db->insert('presensi_harian');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
}
