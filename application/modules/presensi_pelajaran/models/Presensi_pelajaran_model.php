<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Presensi_pelajaran_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function check($param = array()) {
        
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
            $this->db->where('presensi_pelajaran_lesson_id', $param['lesson_id']);
        }
        
        $this->db->from('presensi_pelajaran');
        
        $res = $this->db->count_all_results();
        
        return $res;
    }
    
    function add($data = array()) {

        if (isset($data['presensi_pelajaran_id'])) {
            $this->db->set('presensi_pelajaran_id', $data['presensi_pelajaran_id']);
        }
        
        if (isset($data['presensi_pelajaran_period_id'])) {
            $this->db->set('presensi_pelajaran_period_id', $data['presensi_pelajaran_period_id']);
        }
            
        if (isset($data['presensi_pelajaran_month_id'])) {
            $this->db->set('presensi_pelajaran_month_id', $data['presensi_pelajaran_month_id']);
        }
            
        if (isset($data['presensi_teaching_id'])) {
            $this->db->set('presensi_teaching_id', $data['presensi_teaching_id']);
        }
        
        if (isset($data['presensi_pelajaran_date'])) {
            $this->db->set('presensi_pelajaran_date', $data['presensi_pelajaran_date']);
        }
        
        if (isset($data['presensi_pelajaran_class_id'])) {
            $this->db->set('presensi_pelajaran_class_id', $data['presensi_pelajaran_class_id']);
        }
        
        if (isset($data['presensi_pelajaran_lesson_id'])) {
            $this->db->set('presensi_pelajaran_lesson_id', $data['presensi_pelajaran_lesson_id']);
        }
        
        if (isset($data['presensi_pelajaran_student_id'])) {
            $this->db->set('presensi_pelajaran_student_id', $data['presensi_pelajaran_student_id']);
        }
        
        if (isset($data['presensi_pelajaran_status'])) {
            $this->db->set('presensi_pelajaran_status', $data['presensi_pelajaran_status']);
        }

        if (isset($data['presensi_pelajaran_id'])) {
            $this->db->where('presensi_pelajaran_id', $data['presensi_pelajaran_id']);
            $this->db->update('presensi_pelajaran');
            $id = $data['presensi_pelajaran_id'];
        } else {
            $this->db->insert('presensi_pelajaran');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function get_teaching($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('teaching_id', $params['id']);
        }

        if(isset($params['lesson_id']))
        {
            $this->db->where('teaching_lesson_id', $params['lesson_id']);
        }

        if(isset($params['employee_id']))
        {
            $this->db->where('teaching_employee_id', $params['employee_id']);
        }

        if(isset($params['majors_id']))
        {
            $this->db->where('class.majors_majors_id', $params['majors_id']);
        }

        if(isset($params['class_id']))
        {
            $this->db->where('teaching_class_id', $params['class_id']);
        }

        if(isset($params['semester_id']))
        {
            $this->db->where('teaching_semester_id', $params['semester_id']);
        }
		
		if(isset($params['date_start']) AND isset($params['date_end']))
		{
			$this->db->where('teaching_date_create >=', $params['date_start'] . ' 00:00:00');
			$this->db->where('teaching_date_create <=', $params['date_end'] . ' 23:59:59');
		}

        if(isset($params['month_id']))
        {
            $this->db->where('teaching_month_id', $params['month_id']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('teaching.teaching_id', 'asc');
        }
        
        $this->db->select('teaching_id, teaching_materi, teaching_date_create');
        $this->db->select('class_name');
        $this->db->select('lesson_id, lesson_name');
        $this->db->select('majors_name, majors_short_name, majors_id');
        $this->db->select('employee_name');
        
        
        $this->db->join('class', 'class.class_id = teaching.teaching_class_id');
        $this->db->join('lesson', 'lesson.lesson_id = teaching.teaching_lesson_id');
        // $this->db->join('schedule', 'schedule.schedule_lesson_id = teaching.teaching_lesson_id');
        $this->db->join('majors', 'majors.majors_id = class.majors_majors_id');
        $this->db->join('employee', 'employee.employee_id = teaching.teaching_employee_id');
        
        $res = $this->db->get('teaching');

        if(isset($params['id']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }

    function add_teaching($param = array()) {

        if (isset($param['teaching_id'])) {
            $this->db->set('teaching_id', $param['teaching_id']);
        }
        
        if (isset($param['teaching_employee_id'])) {
            $this->db->set('teaching_employee_id', $param['teaching_employee_id']);
        }
            
        if (isset($param['teaching_semester_id'])) {
            $this->db->set('teaching_semester_id', $param['teaching_semester_id']);
        }
            
        if (isset($param['teaching_lesson_id'])) {
            $this->db->set('teaching_lesson_id', $param['teaching_lesson_id']);
        }
        
        if (isset($param['teaching_class_id'])) {
            $this->db->set('teaching_class_id', $param['teaching_class_id']);
        }
        
        if (isset($param['teaching_month_id'])) {
            $this->db->set('teaching_month_id', $param['teaching_month_id']);
        }
        
        if (isset($param['teaching_materi'])) {
            $this->db->set('teaching_materi', $param['teaching_materi']);
        }

        if (isset($param['teaching_id'])) {
            $this->db->where('teaching_id', $param['teaching_id']);
            $this->db->update('teaching');
            $id = $param['teaching_id'];
        } else {
            $this->db->insert('teaching');
            $id = $this->db->insert_id(); 
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function get_jurnal_mengajar($params = array()){

        if(isset($params['id']))
        {
            $this->db->where('teaching_id', $params['id']);
        }

        if(isset($params['lesson_id']))
        {
            $this->db->where('teaching_lesson_id', $params['lesson_id']);
        }

        if(isset($params['class_id']))
        {
            $this->db->where('presensi_pelajaran_class_id', $params['class_id']);
        }

        if(isset($params['majors_id']))
        {
            $this->db->where('class.majors_majors_id', $params['majors_id']);
        }

        if(isset($params['month_id']))
        {
            $this->db->where('presensi_pelajaran_month_id', $params['month_id']);
        }

        if(isset($params['employee_id']))
        {
            $this->db->where('teaching_employee_id', $params['employee_id']);
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('teaching_id', 'asc');
        }
        
        $this->db->select('presensi_pelajaran_lesson_id AS les_id, presensi_pelajaran_status AS c_pre, presensi_teaching_id AS teach_id, student_full_name ');
                
        $this->db->join('student', 'presensi_pelajaran.presensi_pelajaran_student_id=student.student_id','left');
        $this->db->join('teaching', 'presensi_pelajaran.presensi_teaching_id=teaching.teaching_id','left');
        $this->db->join('class', 'teaching.teaching_class_id=class.class_id','left');
        
        $res = $this->db->get('presensi_pelajaran');

        if(isset($params['id']))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }
    

}
