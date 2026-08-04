<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Jurnal_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get($param = array())
    {

        if (isset($param['period_id'])) {
            $this->db->where('presensi_pelajaran_period_id', $param['period_id']);
        }

        if (isset($param['month_id'])) {
            $this->db->where('presensi_pelajaran_month_id', $param['month_id']);
        }

        if (isset($param['date'])) {
            $this->db->where('presensi_pelajaran_date', $param['date']);
        }

        if (isset($param['class_id'])) {
            $this->db->where('presensi_pelajaran_class_id', $param['class_id']);
        }

        if (isset($param['lesson_id'])) {
            $this->db->where('lesson_id', $param['lesson_id']);
        }

        if (isset($param['schedule'])) {
            $this->db->where('schedule_lesson_id', $param['schedule']);
        }

        if (isset($param['teacher_id'])) {
            $this->db->where('teaching_employee_id', $param['teacher_id']);
        }

        $this->db->from('presensi_pelajaran');
        $this->db->join('schedule', 'presensi_pelajaran.presensi_pelajaran_schedule_id = schedule.schedule_id');
        $this->db->join('lesson', 'lesson.lesson_id = schedule.schedule_lesson_id');
        $this->db->join('teaching', 'presensi_pelajaran.presensi_teaching_id = teaching.teaching_id');

        $res = $this->db->count_all_results();

        return $res;
    }
}
