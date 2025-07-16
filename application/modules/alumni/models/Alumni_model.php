<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Alumni_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array()) {

        if (isset($params['id'])) {
            $this->db->where('alumni.alumni_id', $params['id']);
        }
        if (isset($params['alumni_id'])) {
            $this->db->where('alumni.alumni_id', $params['alumni_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('alumni.alumni_id', $params['multiple_id']);
        }

        if(isset($params['alumni_search']))
        {
            $this->db->where('alumni_nis', $params['alumni_search']);
            $this->db->or_like('alumni_full_name', $params['alumni_search']);
        }

        if (isset($params['alumni_nis'])) {
            $this->db->where('alumni.alumni_nis', $params['alumni_nis']);
        }

        if (isset($params['nis'])) {
            $this->db->like('alumni_nis', $params['nis']);
        }

        if (isset($params['alumni_full_name'])) {
            $this->db->where('alumni.alumni_full_name', $params['alumni_full_name']);
        }
        
        if (isset($params['date'])) {
            $this->db->where('alumni_input_date', $params['date']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('alumni_kelas', $params['class_id']);
        }

        if (isset($params['period_id'])) {
            $this->db->where('alumni_tahun_id', $params['period_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('alumni.alumni_unit', $params['majors_id']);
        }

        if (isset($params['class_name'])) {
            $this->db->like('class_name', $params['class_name']);
        }
        
        if($this->session->userdata('umajorsid') != '0')
        {
            $this->db->where('majors_id', $this->session->userdata('umajorsid'));
        }

        if (isset($params['name_like'])) {
            $this->db->like('alumni_full_name', $params['name_like']);
        }

        if(isset($params['group']))
        {

            $this->db->group_by('alumni.alumni_kelas'); 

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
            $this->db->order_by('majors_id', 'asc');
            $this->db->order_by('class_name', 'asc');
            $this->db->order_by('alumni_nis', 'asc');
        }

        $this->db->select('alumni.alumni_id, alumni_nis, alumni_nisn, alumni_gender, alumni_phone, 
                            alumni_hobby, alumni_address, alumni_parent_phone, alumni_full_name, alumni_born_place, 
                            alumni_born_date, alumni_img, alumni_name_of_mother, alumni_name_of_father,
                            alumni_tahun_id alumni_input_date, alumni_last_update');
        $this->db->select('class.class_id, alumni.alumni_kelas, class.class_name, class.majors_majors_id');
        $this->db->select('alumni.alumni_unit, majors_id, majors_name, majors_short_name, majors_status');
        $this->db->select('period.period_id, period_start, period_end');
        
        $this->db->join('class', 'class.class_id = alumni.alumni_kelas', 'left');
        $this->db->join('majors', 'majors.majors_id = alumni.alumni_unit', 'left');
        $this->db->join('period', 'period.period_id = alumni.alumni_tahun_id', 'left');

        $res = $this->db->get('alumni');

        if (isset($params['id'])) {
            return $res->row_array();
        } else if (isset($params['alumni_nis'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }

    }
    
    function get_alumni($params = array()) {

        if (isset($params['id'])) {
            $this->db->where('alumni.alumni_id', $params['id']);
        }
        if (isset($params['alumni_id'])) {
            $this->db->where('alumni.alumni_id', $params['alumni_id']);
        }

        if (isset($params['multiple_id'])) {
            $this->db->where_in('alumni.alumni_id', $params['multiple_id']);
        }

        if(isset($params['alumni_search']))
        {
            $this->db->where('alumni_nis', $params['alumni_search']);
            $this->db->or_like('alumni_full_name', $params['alumni_search']);
        }

        if (isset($params['alumni_nis'])) {
            $this->db->where('alumni.alumni_nis', $params['alumni_nis']);
        }

        if (isset($params['nis'])) {
            $this->db->where('alumni_nis', $params['nis']);
        }

        if (isset($params['alumni_full_name'])) {
            $this->db->where('alumni.alumni_full_name', $params['alumni_full_name']);
        }
        
        if (isset($params['date'])) {
            $this->db->where('alumni_input_date', $params['date']);
        }

        if (isset($params['class_id'])) {
            $this->db->where('alumni_kelas', $params['class_id']);
        }

        if (isset($params['majors_id'])) {
            $this->db->where('alumni.alumni_unit', $params['majors_id']);
        }

        if (isset($params['class_name'])) {
            $this->db->like('class_name', $params['class_name']);
        }

        if (isset($params['name_like'])) {
            $this->db->like('alumni_full_name', $params['name_like']);
        }

        if(isset($params['group']))
        {

            $this->db->group_by('alumni.alumni_kelas'); 

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
            $this->db->order_by('majors_id', 'asc');
            $this->db->order_by('class_name', 'asc');
            $this->db->order_by('alumni_nis', 'asc');
        }
        
        $this->db->where('majors_status', '1');

        $this->db->select('alumni.alumni_id, alumni_nis, alumni_nisn, alumni_gender, alumni_phone, 
                            alumni_hobby, alumni_address, alumni_parent_phone, alumni_full_name, alumni_born_place, 
                            alumni_born_date, alumni_img, alumni_name_of_mother, alumni_name_of_father, alumni_tahun_id, alumni_input_date, alumni_last_update');
        $this->db->select('class.class_id, alumni.alumni_kelas, class.class_name, class.majors_majors_id');
        $this->db->select('alumni.alumni_unit, majors_id, majors_name, majors_short_name, majors_status');
        $this->db->select('period.period_id, period_start, period_end');
        
        $this->db->join('class', 'class.class_id = alumni.alumni_kelas', 'left');
        $this->db->join('majors', 'majors.majors_id = alumni.alumni_unit', 'left');
        $this->db->join('period', 'period.period_id = alumni.alumni_tahun_id', 'left');

        $res = $this->db->get('alumni');

        if (isset($params['id'])) {
            return $res->row_array();
        } else if (isset($params['alumni_nis'])) {
            return $res->row_array();
        } else {
            return $res->result_array();
        }

    }

    function add($data = array()) {

        if (isset($data['alumni_id'])) {
            $this->db->set('alumni_id', $data['alumni_id']);
        }

        if (isset($data['alumni_nis'])) {
            $this->db->set('alumni_nis', $data['alumni_nis']);
        }

        if (isset($data['alumni_nisn'])) {
            $this->db->set('alumni_nisn', $data['alumni_nisn']);
        }

        if (isset($data['alumni_gender'])) {
            $this->db->set('alumni_gender', $data['alumni_gender']);
        }

        if (isset($data['alumni_phone'])) {
            $this->db->set('alumni_phone', $data['alumni_phone']);
        }

        if (isset($data['alumni_parent_phone'])) {
            $this->db->set('alumni_parent_phone', $data['alumni_parent_phone']);
        }

        if (isset($data['alumni_hobby'])) {
            $this->db->set('alumni_hobby', $data['alumni_hobby']);
        }

        if (isset($data['alumni_address'])) {
            $this->db->set('alumni_address', $data['alumni_address']);
        }

        if (isset($data['alumni_name_of_father'])) {
            $this->db->set('alumni_name_of_father', $data['alumni_name_of_father']);
        }

        if (isset($data['alumni_full_name'])) {
            $this->db->set('alumni_full_name', $data['alumni_full_name']);
        }

        if (isset($data['alumni_img'])) {
            $this->db->set('alumni_img', $data['alumni_img']);
        }

        if (isset($data['alumni_born_place'])) {
            $this->db->set('alumni_born_place', $data['alumni_born_place']);
        }

        if (isset($data['alumni_born_date'])) {
            $this->db->set('alumni_born_date', $data['alumni_born_date']);
        }

        if (isset($data['alumni_name_of_mother'])) {
            $this->db->set('alumni_name_of_mother', $data['alumni_name_of_mother']);
        }
        
        if (isset($data['alumni_tahun_id'])) {
            $this->db->set('alumni_tahun_id', $data['alumni_tahun_id']);
        }

        if (isset($data['alumni_kelas'])) {
            $this->db->set('alumni_kelas', $data['alumni_kelas']);
        }

        if (isset($data['alumni_unit'])) {
            $this->db->set('alumni_unit', $data['alumni_unit']);
        }

        if (isset($data['alumni_input_date'])) {
            $this->db->set('alumni_input_date', $data['alumni_input_date']);
        }

        if (isset($data['alumni_last_update'])) {
            $this->db->set('alumni_last_update', $data['alumni_last_update']);
        }

        if (isset($data['alumni_id'])) {
            $this->db->where('alumni_id', $data['alumni_id']);
            $this->db->update('alumni');
            $id = $data['alumni_id'];
        } else {
            $this->db->insert('alumni');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }

    function delete($id) {
        $this->db->where('alumni_id', $id);
        $this->db->delete('alumni');
    }

    public function is_exist($field, $value)
    {
        $this->db->where($field, $value);        

        return $this->db->count_all_results('alumni') > 0 ? TRUE : FALSE;
    }

}
