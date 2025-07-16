<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ppdb_set extends CI_Controller {

    public function __construct() {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('setting/Setting_model', 'student/Student_model'));
    $this->load->helper(array('form', 'url', 'ppdb'));
  }

// User_customer view in list
    public function index($offset = NULL) {
    
    $tahun      = null;
    $gelombang  = null;
    
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;
    
    $data['student'] = array();
    
    if (isset($s['u']) && !empty($s['u'])) {
      $unit = $s['u'];
    }else {
      $unit = '';
    }
    
    if (isset($s['t']) && !empty($s['t'])) {
      $tahun = $s['t'];
      $data['gelombang']  = $this->get_gelombang($tahun);
    }
    
    if (isset($s['g']) && !empty($s['g'])) {
      $gelombang = $s['g'];
    }
    
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    
    $data['unit']       = $this->get_unit();
    $data['tahun']      = $this->get_tahun_ajaran();
    
    $data['majors']     = $this->Student_model->get_majors();
    $data['classes']    = $this->Student_model->get_class();
    
    if(!empty($tahun) && !empty($gelombang)) {
      
        $ppdb               = $this->env();
      
        $params             = array(
                                'key'           => $ppdb['key'],
                                'tahun_ajaran'  => $tahun,
                                'unit'          => $unit,
                                'gelombang'     => $gelombang
                            );
      
        $student               = ppdb_data($ppdb['link'], "get_siswa", $params);
        
        $datas              = json_decode($student, true);
        
        $data['student']    = $datas['data'];
        
    }
    
    $config['base_url'] = site_url('manage/ppdb/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    
    $data['title'] = 'Data PPDB';
    $data['main'] = 'ppdb/ppdb_list';
    $this->load->view('manage/layout', $data);
  }
  
    public function gelombang_pendaftaran(){
      $tahun        = $_POST['tahun'];
      
      $gelombang    = $this->get_gelombang($tahun);
      
      echo '<select style="width: 200px;" id="g" name="g" class="form-control" required>';
              foreach($gelombang as $row){ 

                echo '<option value="'.$row['id_gelombang'].'">';
                    
                echo $row['nama_gelombang'];
                    
                echo '</option>';
            
                }
        echo '</select>';
      
      
  }
  
    public function data_kelas(){
        $major        = $_POST['major'];
        
        $kelas    = $this->Student_model->get_class(array('major_id'=>$major));
        
        echo '<label>Kelas</label>
    		    <select id="class" name="class" class="form-control" required>';
              foreach($kelas as $row){ 
        
                echo '<option value="'.$row['class_id'].'">';
                    
                echo $row['class_name'];
                    
                echo '</option>';
            
                }
        echo '</select>';
    }
  
    public function get_form(){
        for($i = 0; $i < count($_POST["no_pendaftaran"]); $i++)
        {
    		    echo '<input type="hidden" name="no_pendaftaran[]" id="no_pendaftaran" value="'.$_POST['no_pendaftaran'][$i].'">';
        }
    }
    
    public function go_to() {
        
        if($_POST) {
            
            $major      = $_POST['major'];
            $class      = $_POST['class'];
            
            $data_array = $_POST['no_pendaftaran'];
            
            $no_pendaftaran = implode(', ', array_map(function($item) {
                return "'$item'";
            }, $data_array));
            
            $students   = $this->get_siswa_by_no_pendaftaran($no_pendaftaran);
            
            foreach($students as $student) {
                
                $params['student_nis']              = $this->generate_nis($major);
                $params['student_full_name']        = $student['nama_lengkap'];
                $params['student_nisn']             = $student['nisn'];
                $params['student_gender']           = $student['jk'];
                $params['class_class_id']           = $class;
                $params['majors_majors_id']         = $major;
                $params['student_born_place']       = $student['tempat_lahir']; 
                $params['student_born_date']        = $student['tgl_lahir']; 
                $params['student_address']          = trim($student['alamat_siswa']); 
                $params['student_name_of_mother']   = $student['nama_ibu']; 
                $params['student_name_of_father']   = $student['nama_ayah']; 
                $params['student_img']              = $this->get_photo($student['foto']);
                $params['student_parent_phone']     = $this->generate_phone($student['no_hp_siswa']); 
                $params['student_status']           = 1; 
                
                $this->Student_model->add($params);
                
            }
            
            $this->siswa_penempatan_kelas($no_pendaftaran);
            
            $this->session->set_flashdata('success', 'Penempatan santri Berhasil');
            redirect('manage/ppdb');
            
        } else {
            redirect(base_url());
        }

    }
    
    private function generate_nis($major = null) {
        $nis = $this->db->query("SELECT MAX(student_nis) + 1 AS new_nis FROM `student` WHERE majors_majors_id = 5")->row_array();
        
        return $nis['new_nis'];
    }
    
  
    private function env() {
      
      $link_ppdb = $this->Setting_model->get(array('id'=>'600'));
      $key_ppdb  = $this->Setting_model->get(array('id'=>'601'));
      
      return $data = array(
                'link' => $link_ppdb['setting_value'],
                'key'  => $key_ppdb['setting_value']
          );
  }
  
    private function get_tahun_ajaran() {
      
        $ppdb = $this->env();
      
        $params = array(
                'key' => $ppdb['key']
            );
      
        $data = ppdb_data($ppdb['link'], "get_period", $params);
        
        $datas = json_decode($data, true);
        
        return $datas['data'];
      
  }
  
    private function get_unit() {
      
        $ppdb = $this->env();
      
        $params = array(
                'key' => $ppdb['key']
            );
      
        $data = ppdb_data($ppdb['link'], "get_unit", $params);
        
        $datas = json_decode($data, true);
        
        return $datas['data'];
      
  }
  
    private function get_gelombang($tahun = null) {
      
        $ppdb = $this->env();
      
        $params = array(
                'key' => $ppdb['key'],
                'tahun_ajaran' => $tahun
            );
      
        $data = ppdb_data($ppdb['link'], "get_gelombang", $params);
        
        $datas = json_decode($data, true);
        
        return $datas['data'];
      
  }
  
    private function get_photo($photo = null) 
    {
        
        $ppdb = $this->env();
        
        $url = $ppdb['link'] . '/uploads/foto/' . $photo;
        $path = FCPATH . 'uploads/student/';
        $fileName = $photo;
        
        $imageData = file_get_contents($url);
        
        $result = file_put_contents($path . $fileName, $imageData);
        
        return $fileName;
        
    }
  
    private function generate_phone($phone = null) 
    {
        
        $student_parent_phone = $phone;
              		    
        $student_parent_phone = str_replace(" ","",$student_parent_phone);
        $student_parent_phone = str_replace("(","",$student_parent_phone);
        $student_parent_phone = str_replace(")","",$student_parent_phone);
        $student_parent_phone = str_replace(".","",$student_parent_phone);
        
        if(!preg_match('/[^+0-9]/',trim($student_parent_phone)))
        {
        	 if(substr(trim($student_parent_phone), 0, 1)=='+')
        	 {
        	 $hp = trim($student_parent_phone);
        	 }
        	 elseif(substr(trim($student_parent_phone), 0, 1)=='0')
        	 {
        	 $hp = '+62'.substr(trim($student_parent_phone), 1);
        	 }
        	 elseif(substr(trim($student_parent_phone), 0, 2)=='62')
        	 {
        	 $hp = '+'.trim($student_parent_phone);
        	 }
        	 elseif(substr(trim($student_parent_phone), 0, 1)=='8')
        	 {
        	 $hp = '+62'.trim($student_parent_phone);
        	 }
        	 else
        	 {
        	 $hp = '+'.trim($student_parent_phone);
        	 }		 
        }
        
        return $hp;
        
    }
  
    private function get_siswa_by_no_pendaftaran($no_pendaftaran = null) {
      
        $ppdb = $this->env();
      
        $params = array(
                'key' => $ppdb['key'],
                'no_pendaftaran' => $no_pendaftaran,
            );
      
        $data = ppdb_data($ppdb['link'], "get_siswa_by_no_pendaftaran", $params);
        
        $datas = json_decode($data, true);
        
        return $datas['data'];
      
    }
    
    private function siswa_penempatan_kelas($no_pendaftaran = null) {
      
        $ppdb = $this->env();
      
        $params = array(
                'key' => $ppdb['key'],
                'no_pendaftaran' => $no_pendaftaran,
            );
      
        $data = ppdb_data($ppdb['link'], "siswa_penempatan_kelas", $params);
        
        $datas = json_decode($data, true);
        
        return $datas['status'];
      
    }

}
