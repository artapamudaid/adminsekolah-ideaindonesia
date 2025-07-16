<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumni_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('alumni/Alumni_model', 'student/Student_model', 'setting/Setting_model', 'bulan/Bulan_model', 'period/Period_model'));
    $this->load->helper(array('form', 'url'));
  }

    // User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
    // Apply Filter
    // Get $_GET variable
    $f = $this->input->get(NULL, TRUE);
    $s = $this->input->get(NULL, TRUE);

    $data['f'] = $f;
    $data['s'] = $s;

    $params = array();
    $param = array();
    // Nip
    if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
      $params['alumni_search'] = $f['n'];
    }
    
    if (isset($s['c']) && !empty($s['c']) && $s['c'] != 'all') {
      $params['class_id'] = $s['c'];
    } else if (isset($s['c']) && !empty($s['c']) && $s['c'] == 'all') {
      
    }
    
    if (isset($s['d']) && !empty($s['d']) && $s['d'] != 'all') {
      $params['madin_id'] = $s['d'];
    } else if (isset($s['d']) && !empty($s['d']) && $s['d'] == 'all') {
      
    }
    
    if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
      $params['majors_id'] = $s['m'];
    } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
      
    }
    
    if (isset($s['s']) && !empty($s['s']) && $s['s'] != 'all') {
      $params['tahun_id'] = $s['s'];
    } else if (isset($s['s']) && !empty($s['s']) && $s['s'] == 'all') {
      
    }
    

    $data['alumni']     = $this->Alumni_model->get($params);
    $data['majors']     = $this->Student_model->get_majors();
    $data['class']      = $this->Student_model->get_class($params);
    // $data['madin']      = $this->Student_model->get_madin($params);
    $data['period']     = $this->Period_model->get($params);

    $config['base_url'] = site_url('manage/alumni/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    

    $data['title'] = 'Alumni';
    $data['main'] = 'alumni/alumni_list';
    $this->load->view('manage/layout', $data);
  }
  
  function class_searching(){
	    $id_majors = $this->input->post('id_majors');
        $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();
    
        echo '<select style="width: 200px;" id="c" name="c" class="form-control" required>
    			<option value="">--- Pilih Kelas ---</option>
    			<option value="all">Semua Kelas</option>';
              foreach($dataKamar as $row){ 

                echo '<option value="'.$row['class_id'].'">';
                    
                echo $row['class_name'];
                    
                echo '</option>';
            
                }
        echo '</select>';
	}
	
	function madin_searching(){
	    $id_majors = $this->input->post('id_majors');
        $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();
    
        echo '<select style="width: 200px;" id="d" name="d" class="form-control" required>
    			<option value="">--- Pilih Kamar ---</option>
    			<option value="all">Semua Kamar</option>';
              foreach($dataMadin as $row){ 

                echo '<option value="'.$row['madin_id'].'">';
                    
                echo $row['madin_name'];
                    
                echo '</option>';
            
                }
        echo '</select>';
	}
	
  function cari_kelas(){
       $id_majors   = $this->input->post('id_majors');
        $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();
    
        echo '<select class="form-control" name="pr" id="pr" onchange="this.form.submit()">
				<option value="">-- Pilih Kelas  --</option>';
                      foreach($dataKamar as $row){ 
        
                        echo '<option value="'.$row['class_id'].'">';
                            
                        echo $row['class_name'];
                            
                        echo '</option>';
                    
                        }
                                        
        echo '</select>';
  }
	
  function cari_ke_kelas(){
       $id_majors   = $this->input->post('id_majors');
        $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();
    
        echo '<select class="form-control" name="c_id" id="c_id" onchange="ambil()">
    			<option value="">-- Ke Kelas  --</option>';
                      foreach($dataKamar as $row){ 
        
                        echo '<option value="'.$row['class_id'].'">';
                            
                        echo $row['class_name'];
                            
                        echo '</option>';
                    
                        }
                                        
        echo '</select>';
        
        echo '
            <script>
                function ambil(){
                    var kls = $("#c_id").val();
                    
                    $("#class_id").val(kls);
                }
            </script>
        ';
  }
	
  function cari_ke_kamar(){
       $id_majors   = $this->input->post('id_majors');
        $dataKamar  = $this->db->query("SELECT * FROM madin WHERE madin_majors_id = '$id_majors' ORDER BY madin_name ASC")->result_array();
    
        echo '<select class="form-control" name="c_id" id="c_id" onchange="ambil()">
    			<option value="">-- Ke Kamar  --</option>';
                      foreach($dataKamar as $row){ 
        
                        echo '<option value="'.$row['madin_id'].'">';
                            
                        echo $row['madin_name'];
                            
                        echo '</option>';
                    
                        }
                                        
        echo '</select>';
        
        echo '
            <script>
                function ambil(){
                    var kls = $("#c_id").val();
                    
                    $("#class_id").val(kls);
                }
            </script>
        ';
  }
  
  function get_kelas(){
        $id_majors   = $this->input->post('id_majors');
        $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();
    
        echo '<div class="form-group"> 
			<label >Kelas *</label>
            <select name="alumni_kelas" id="alumni_kelas" class="form-control">
				<option value=""> -- Pilih Kelas -- </option>';
                      foreach($dataKamar as $row){ 
        
                        echo '<option value="'.$row['class_id'].'">';
                            
                        echo $row['class_name'];
                            
                        echo '</option>';
                    
                        }
                                        
        echo '</select>
            </div>';
    }
    
    // function get_madin(){
    //     $id_majors   = $this->input->post('id_majors');
    //     $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();
    
    //     echo '<div class="form-group"> 
		// 	<label>Kamar </label>
    //         <select name="alumni_madin" id="alumni_madin" class="form-control">
		// 		<option value=""> -- Pilih Kamar-- </option>';
    //                   foreach($dataMadin as $row){ 
        
    //                     echo '<option value="'.$row['madin_id'].'">';
                            
    //                     echo $row['madin_name'];
                            
    //                     echo '</option>';
                    
    //                     }
                                        
    //     echo '</select>
    //         </div>';
    // }

    // Add User and Update
  public function add($id = NULL) {

    $this->load->library('form_validation');

    if (!$this->input->post('alumni_id')) {
      $this->form_validation->set_rules('alumni_nis', 'NIS', 'trim|required|xss_clean|is_unique[alumni.alumni_nis]');
    }
    $this->form_validation->set_rules('alumni_kelas', 'Kelas', 'trim|required|xss_clean');
    $this->form_validation->set_rules('alumni_full_name', 'Nama lengkap', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button position="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

    if ($_POST AND $this->form_validation->run() == TRUE) {

      if ($this->input->post('alumni_id')) {
        $params['alumni_id'] = $id;
      } else {
        $params['alumni_input_date'] = date('Y-m-d H:i:s');
        $default = "123456";
        $pass = $this->input->post('alumni_password');
        if($pass !=''){
            $params['alumni_password'] = md5($pass);
        } else {
            $params['alumni_password'] = md5($default);
        }
      }
      		    
        $alumni_parent_phone = $this->input->post('alumni_parent_phone');
              		    
        $alumni_parent_phone = str_replace(" ","",$alumni_parent_phone);
        $alumni_parent_phone = str_replace("(","",$alumni_parent_phone);
        $alumni_parent_phone = str_replace(")","",$alumni_parent_phone);
        $alumni_parent_phone = str_replace(".","",$alumni_parent_phone);
        
        if(!preg_match('/[^+0-9]/',trim($alumni_parent_phone)))
        {
        	 if(substr(trim($alumni_parent_phone), 0, 1)=='+')
        	 {
        	 $hp = trim($alumni_parent_phone);
        	 }
        	 elseif(substr(trim($alumni_parent_phone), 0, 1)=='0')
        	 {
        	 $hp = '+62'.substr(trim($alumni_parent_phone), 1);
        	 }
        	 elseif(substr(trim($alumni_parent_phone), 0, 2)=='62')
        	 {
        	 $hp = '+'.trim($alumni_parent_phone);
        	 }
        	 elseif(substr(trim($alumni_parent_phone), 0, 1)=='8')
        	 {
        	 $hp = '+62'.trim($alumni_parent_phone);
        	 }
        	 else
        	 {
        	 $hp = '+'.trim($alumni_parent_phone);
        	 }		 
        }
      
      $params['alumni_nis'] = $this->input->post('alumni_nis');
      $params['alumni_nisn'] = $this->input->post('alumni_nisn');
      $params['alumni_gender'] = $this->input->post('alumni_gender');
      $params['alumni_kelas'] = $this->input->post('alumni_kelas');
      $params['alumni_unit'] = $this->input->post('alumni_unit');
      $params['alumni_tahun_id'] = $this->input->post('alumni_tahun_id');
      $params['alumni_last_update'] = date('Y-m-d H:i:s');
      $params['alumni_full_name'] = $this->input->post('alumni_full_name');
      $params['alumni_born_place'] = $this->input->post('alumni_born_place'); 
      $params['alumni_born_date'] = $this->input->post('alumni_born_date'); 
      $params['alumni_address'] = $this->input->post('alumni_address'); 
      $params['alumni_name_of_mother'] = $this->input->post('alumni_name_of_mother'); 
      $params['alumni_name_of_father'] = $this->input->post('alumni_name_of_father'); 
      $params['alumni_parent_phone'] = $hp; 
      $status = $this->Alumni_model->add($params);

      if (!empty($_FILES['alumni_img']['name'])) {
        $paramsupdate['alumni_img'] = $this->do_upload($name = 'alumni_img', $fileName= base64_encode($params['alumni_full_name']));
      } 

      $paramsupdate['alumni_id'] = $status;
      $this->Alumni_model->add($paramsupdate);

    // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'Alumni',
          'log_action' => $data['operation'],
          'log_info' => 'ID:' . $status . ';Name:' . $this->input->post('alumni_full_name')
        )
      );

      $this->session->set_flashdata('success', $data['operation'] . ' Alumni Berhasil');
      redirect('manage/alumni');
    } else {
      if ($this->input->post('alumni_id')) {
        redirect('manage/alumni/edit/' . $this->input->post('alumni_id'));
      }

    // Edit mode
      if (!is_null($id)) {
        $object = $this->Alumni_model->get_alumni(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/alumni');
        } else {
          $data['alumni'] = $object;
        }
        $id_unit = $object['alumni_unit'];
        $data['class'] = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_unit'")->result_array();
      }
      $data['setting_level'] = $this->Setting_model->get(array('id' => 7));
      $data['majors'] = $this->Student_model->get_majors();
      $data['period'] = $this->Period_model->get();
      $data['title'] = $data['operation'] . ' Alumni';
      $data['main'] = 'alumni/alumni_add';
      $this->load->view('manage/layout', $data);
    }
  }

    // View data detail
  public function view($id = NULL) {
    $data['alumni'] = $this->Alumni_model->get(array('id' => $id));
    $data['title'] = 'Alumni';
    // $data['mahram'] = $this->db->get('mahram')->result_array();
    // $data['guest'] = $this->db->query("SELECT guest_id, guest_name, guest_student_id, mahram_note FROM guest JOIN mahram ON mahram.mahram_id = guest.guest_mahram_id WHERE guest_student_id = '$id'")->result_array();
    $data['main'] = 'alumni/alumni_view';
    $this->load->view('manage/layout', $data);
  }
  
  public function add_mahram(){
		if ($_POST == TRUE) {
		    $alumniID   = $_POST['alumni_id'];
		    $guestName   = $_POST['guest_name'];
			$guestDesc   = $_POST['guest_mahram_id'];
			$cpt = count($_POST['guest_name']);
			for ($i = 0; $i < $cpt; $i++) {
				$params['guest_name']        = $guestName[$i];
				$params['guest_mahram_id']   = $guestDesc[$i];
				$params['guest_alumni_id']  = $alumniID;

				$this->Alumni_model->add_mahram($params);
			}
		}
		$this->session->set_flashdata('success',' Tambah Data Mahram Berhasil');
		redirect('manage/alumni/view/'.$alumniID);
	}

	public function delete_mahram($id = NULL) {
		if ($_POST) {
		    $x = $this->input->post('alumni_id');
			$this->Alumni_model->delete_mahram($id);
			$this->session->set_flashdata('success', 'Hapus Data Mahram berhasil');
			redirect('manage/alumni/view/'.$x);
		} elseif (!$_POST) {
		    $x = $this->input->post('alumni_id');
			$this->session->set_flashdata('delete', 'Delete');
			redirect('manage/alumni/view/'.$x);
		}
	}

    // Class view in list
  public function clasess($offset = NULL) {
    $this->load->library('pagination');

    $data['class'] = $this->Student_model->get_class(array('limit' => 10, 'offset' => $offset));
    $data['title'] = 'Daftar Kelas';
    $data['main'] = 'alumni/class_list';
    $config['total_rows'] = count($this->Student_model->get_class());
    $this->pagination->initialize($config);

    $this->load->view('manage/layout', $data);
  }

    // Setting Upload File Requied
  function do_upload($name=NULL, $fileName=NULL) {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/alumni/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size'] = '1024';
    $config['file_name'] = $fileName;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload($name)) {
      $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
      redirect(uri_string());
    }

    $upload_data = $this->upload->data();

    return $upload_data['file_name'];
  }
  
  public function import_alumni(){
        //redirect('manage/alumni/import');
        $data['title'] = 'Import Data Alumni';
        $data['main'] = 'alumni/alumni_import';
        $data['action'] = site_url(uri_string());
        $this->load->view('manage/layout', $data);
  }
  
  public function do_import(){
    $default = "123456";
    $this->load->library('excel');
    if(isset($_FILES["file"]["name"]))
	{      
        $path = $_FILES["file"]["tmp_name"];
		$object = PHPExcel_IOFactory::load($path);
		foreach($object->getWorksheetIterator() as $worksheet)
		{
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			for($row=3; $row<=$highestRow; $row++)
			{
				$alumni_nis             = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
				$alumni_full_name       = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
				$alumni_kelas           = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
				$alumni_unit            = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
				$alumni_tahun_id        = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
				$alumni_name_of_father  = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
				$alumni_name_of_mother  = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
				$alumni_parent_phone    = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
				$alumni_address         = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
				
				$q = $this->db->query("SELECT COUNT(alumni_nis) as nisNum FROM alumni WHERE alumni_nis = '$alumni_nis'")->row_array();
				$nisNum = $q['nisNum'];
				
				if(empty($alumni_nis) || $nisNum != 0){
				    $this->session->set_flashdata('failed', 'Duplikasi No. Induk Alumni');
				} else {
				    
				    $alumni_parent_phone = str_replace(" ","",$alumni_parent_phone);
                    $alumni_parent_phone = str_replace("(","",$alumni_parent_phone);
                    $alumni_parent_phone = str_replace(")","",$alumni_parent_phone);
                    $alumni_parent_phone = str_replace(".","",$alumni_parent_phone);
                    
                    if(!preg_match('/[^+0-9]/',trim($alumni_parent_phone)))
                    {
                    	 if(substr(trim($alumni_parent_phone), 0, 1)=='+')
                    	 {
                    	 $hp = trim($alumni_parent_phone);
                    	 }
                    	 elseif(substr(trim($alumni_parent_phone), 0, 1)=='0')
                    	 {
                    	 $hp = '+62'.substr(trim($alumni_parent_phone), 1);
                    	 }
                    	 elseif(substr(trim($alumni_parent_phone), 0, 2)=='62')
                    	 {
                    	 $hp = '+'.trim($alumni_parent_phone);
                    	 }
                    	 elseif(substr(trim($alumni_parent_phone), 0, 1)=='8')
                    	 {
                    	 $hp = '+62'.trim($alumni_parent_phone);
                    	 }
                    	 else
                    	 {
                    	 $hp = '+'.trim($alumni_parent_phone);
                    	 }		 
                    }
				    
				    $data[] = array(
    					'alumni_nis'		        =>	$alumni_nis,
    					'alumni_full_name'	        =>	$alumni_full_name,
    					'alumni_password'	        =>	md5($default),
    					'alumni_kelas'	            =>	$alumni_kelas,
    					'alumni_unit'	            =>	$alumni_unit,
    					'alumni_tahun_id'	        =>	$alumni_tahun_id,
    					'alumni_name_of_father'	    =>	$alumni_name_of_father,
    					'alumni_name_of_mother'	    =>	$alumni_name_of_mother,
    					'alumni_parent_phone'	    =>	$hp,
    					'alumni_address'	        =>	$alumni_address
				    );    
				}
			}
		}
          
        $this->db->insert_batch('alumni', $data);
        $this->session->set_flashdata('success', 'Import Data Alumni Berhasil');
		redirect('manage/alumni');
	}
  }
  
  public function do_update(){
    $this->load->library('excel');
    if(isset($_FILES["file"]["name"]))
	{      
        $path = $_FILES["file"]["tmp_name"];
		$object = PHPExcel_IOFactory::load($path);
		foreach($object->getWorksheetIterator() as $worksheet)
		{
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			for($row=3; $row<=$highestRow; $row++)
			{
				$alumni_nis = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
				$alumni_full_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
				$alumni_madin = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
				    $data[] = array(
				    'alumni_nis'		=>	$alumni_nis,
					'alumni_madin'		=>	$alumni_madin,
				    );    
			}
		}
          
        $this->db->update_batch('alumni', $data, 'alumni_nis');
        $this->session->set_flashdata('success', 'Update Data Alumni Berhasil');
		redirect('manage/alumni');
	}
  }

  public function import() {
    if ($_POST) {
      $rows= explode("\n", $this->input->post('rows'));
      $success = 0;
      $failled = 0;
      $exist = 0;
      $nis = '';
      foreach($rows as $row) {
        $exp = explode("\t", $row);
        $count = (majors()=='senior') ? 14 : 13;
        if (count($exp) != $count) continue;
        $nis = trim($exp[0]);
        $ttl = trim($exp[5]);
        $date = str_replace('-', '',$ttl); 
        $arr = [
          'alumni_nis' => trim($exp[0]),
          'alumni_nisn' => trim($exp[1]),
          'alumni_password' => md5(date('dmY', strtotime($date))),
          'alumni_full_name' => trim($exp[2]),
          'alumni_gender' => trim($exp[3]),
          'alumni_born_place' => trim($exp[4]),
          'alumni_born_date' => trim($exp[5]),
          'alumni_hobby' => trim($exp[6]),
          'alumni_phone' => trim($exp[7]),
          'alumni_address' => trim($exp[8]),
          'alumni_name_of_mother' => trim($exp[9]),
          'alumni_name_of_father' => trim($exp[10]),
          'alumni_parent_phone' => trim($exp[11]),
          'alumni_kelas' => trim($exp[12]),
          'alumni_unit' => (majors()=='senior') ? trim($exp[13]) : NULL,
          'alumni_input_date' => date('Y-m-d H:i:s'),
          'alumni_last_update' => date('Y-m-d H:i:s')
        ];
        $class = $this->Student_model->get_class(array('id'=>trim($exp[12])));
        $majors = $this->Student_model->get_majors(array('id'=>trim($exp[13])));
        $check = $this->db
        ->where('alumni_nis', trim($exp[0]))
        ->count_all_results('alumni');
        if ($check == 0) {
          if (trim($exp[12]) == NULL OR is_null($class)) {
            $this->session->set_flashdata('failed', 'ID Kamar tidak ada');
            redirect('manage/alumni/import');
           
        } else if ($this->db->insert('alumni', $arr)) {
          $success++;
        } else {
          $failled++;
        }
      } else {
        $exist++;
      }
    }
    $msg = 'Sukses : ' . $success. ' baris, Gagal : '. $failled .', Duplikat : ' . $exist;
    $this->session->set_flashdata('success', $msg);
    redirect('manage/alumni/import');
  } else {
    $data['title'] = 'Import Data Alumni';
    $data['main'] = 'alumni/alumni_upload';
    $data['action'] = site_url(uri_string());
    $this->load->view('manage/layout', $data);
  }
}

  public function update_whatsapp(){
        //redirect('manage/alumni/import');
        
        $data['majors']     = $this->Student_model->get_majors();
        $data['title']      = 'Update No. Whatsapp Ortu Alumni';
        $data['main']       = 'alumni/update_whatsapp';
        $data['action']     = site_url(uri_string());
        $this->load->view('manage/layout', $data);
  }
  
  public function download_wa(){
    $params = array();
    
    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');
    
    $params['majors_id'] = $id_majors;
    
    if($id_kelas != 'all'){
        $params['class_id']  = $id_kelas;
        $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
        $data['kelas']   = array('class_name'=>'Semua Kelas');
    }
    
    $data['alumni'] = $this->Alumni_model->get($params);
    $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
    
    $this->load->view('alumni/whatsapp_alumni',$data);
}

  public function update_phone(){
    $this->load->library('excel');
    if(isset($_FILES["file"]["name"]))
	{      
        $path = $_FILES["file"]["tmp_name"];
		$object = PHPExcel_IOFactory::load($path);
		foreach($object->getWorksheetIterator() as $worksheet)
		{
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			for($row=3; $row<=$highestRow; $row++)
			{
				$alumni_nis            = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
				$alumni_majors         = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
				$alumni_class          = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
				$alumni_address        = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
				$alumni_name_of_father = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
				$alumni_name_of_mother = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
				$alumni_parent_phone   = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
				
				$alumni_nis            = str_replace("'", "", $alumni_nis);
				$alumni_parent_phone   = str_replace("'", "", $alumni_parent_phone);
                $alumni_parent_phone = str_replace(" ","",$alumni_parent_phone);
                $alumni_parent_phone = str_replace("(","",$alumni_parent_phone);
                $alumni_parent_phone = str_replace(")","",$alumni_parent_phone);
                $alumni_parent_phone = str_replace(".","",$alumni_parent_phone);
                
                if(!preg_match('/[^+0-9]/',trim($alumni_parent_phone)))
                {
                	 if(substr(trim($alumni_parent_phone), 0, 1)=='+')
                	 {
                	 $hp = trim($alumni_parent_phone);
                	 }
                	 elseif(substr(trim($alumni_parent_phone), 0, 1)=='0')
                	 {
                	 $hp = '+62'.substr(trim($alumni_parent_phone), 1);
                	 }
                	 elseif(substr(trim($alumni_parent_phone), 0, 2)=='62')
                	 {
                	 $hp = '+'.trim($alumni_parent_phone);
                	 }
                	 elseif(substr(trim($alumni_parent_phone), 0, 1)=='8')
                	 {
                	 $hp = '+62'.trim($alumni_parent_phone);
                	 }
                	 else
                	 {
                	 $hp = '+'.trim($alumni_parent_phone);
                	 }		 
                }
				
			    $data[] = array(
					'alumni_nis'		        =>	$alumni_nis,
					//'alumni_unit'	    =>	$alumni_majors,
					//'alumni_kelas'	        =>	$alumni_class,
					//'alumni_madin'	        =>	$alumni_madin,
					'alumni_address'	        =>	$alumni_address,
					'alumni_name_of_father'	=>	$alumni_name_of_father,
					'alumni_name_of_mother'	=>	$alumni_name_of_mother,
					'alumni_parent_phone'	    =>	$hp,
			    );    
			}
		}
          
        $this->db->update_batch('alumni', $data, 'alumni_nis');
        $this->session->set_flashdata('success', 'Update No. Whatsapp Ortu Alumni Berhasil');
		redirect('manage/alumni');
	}
  }

public function download() {
    $data = file_get_contents("./media/template_excel/Template_Data_Alumni.xls");
    $name = 'Template_Data_Alumni.xls';

  $this->load->helper('download');
  force_download($name, $data);
}


      // satuan
function printPdf($id = NULL) {
  $this->load->helper(array('dompdf'));
  $this->load->helper(array('tanggal'));
  $this->load->model('alumni/Alumni_model');
  $this->load->model('setting/Setting_model');
  if ($id == NULL)
    redirect('manage/alumni');

  $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
  $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS));
  $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));
  $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT));
  $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY)); 
  $data['alumni'] = $this->Alumni_model->get(array('id' => $id));
  $this->barcode2($data['alumni']['alumni_nis'], '');
  $html = $this->load->view('alumni/alumni_pdf', $data, true);
  $data = pdf_create($html, $data['alumni']['alumni_full_name'], TRUE, 'B6', 'potrait');
}



private function barcode2($sparepart_code, $barcode_type=39, $scale=6, $fontsize=1, $thickness=30,$dpi=72) {

  $this->load->library('upload');
  $config['upload_path'] = FCPATH . 'media/barcode_alumni/';

  /* create directory if not exist */
  if (!is_dir($config['upload_path'])) {
    mkdir($config['upload_path'], 0777, TRUE);
  }
  $this->upload->initialize($config);

    // CREATE BARCODE GENERATOR
    // Including all required classes
  require_once( APPPATH . 'libraries/barcodegen/BCGFontFile.php');
  require_once( APPPATH . 'libraries/barcodegen/BCGColor.php');
  require_once( APPPATH . 'libraries/barcodegen/BCGDrawing.php');

    // Including the barcode technology
    // Ini bisa diganti-ganti mau yang 39, ato 128, dll, liat di folder barcodegen
  require_once( APPPATH . 'libraries/barcodegen/BCGcode39.barcode.php');

    // Loading Font
    // kalo mau ganti font, jangan lupa tambahin dulu ke folder font, baru loadnya di sini
  $font = new BCGFontFile(APPPATH . 'libraries/font/Arial.ttf', $fontsize);

    // Text apa yang mau dijadiin barcode, biasanya kode produk
  $text = $sparepart_code;

    // The arguments are R, G, B for color.
  $color_black = new BCGColor(0, 0, 0);
  $color_white = new BCGColor(255, 255, 255);

  $drawException = null;
  try {
        $code = new BCGcode39(); // kalo pake yg code39, klo yg lain mesti disesuaikan
        $code->setScale($scale); // Resolution
        $code->setThickness($thickness); // Thickness
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont($font); // Font (or 0)
        $code->parse($text); // Text
      } catch(Exception $exception) {
        $drawException = $exception;
      }

    /* Here is the list of the arguments
    1 - Filename (empty : display on screen)
    2 - Background color */
    $drawing = new BCGDrawing('', $color_white);
    if($drawException) {
      $drawing->drawException($drawException);
    } else {
      $drawing->setDPI($dpi);
      $drawing->setBarcode($code);
      $drawing->draw();
    }
    // ini cuma labeling dari sisi aplikasi saya, penamaan file menjadi png barcode.
    $filename_img_barcode = $sparepart_code .'_'.$barcode_type.'.png';
    // folder untuk menyimpan barcode
    $drawing->setFilename( FCPATH .'media/barcode_alumni/'. $sparepart_code.'.png');
    // proses penyimpanan barcode hasil generate
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

    return $filename_img_barcode;

  }

public function get_mclass(){
    $id_majors = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();
    
    echo '<select required="" name="modal_class" id="modal_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>';
    foreach($dataKamar as $row){            
        echo '<option value="'.$row['class_id'].'">'.$row['class_name'].'</option>';
    }
    echo '</select>';
}

public function get_mmadin(){
    $id_majors = $this->input->post('id_majors');
    $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();
    
    echo '<select required="" name="modal_madin" id="modal_madin" class="form-control">';
    echo '<option value="">-Pilih Kamar-</option>
		  <option value="all">Semua Kamar</option>';
    foreach($dataMadin as $row){            
        echo '<option value="'.$row['madin_id'].'">'.$row['madin_name'].'</option>';
    }
    echo '</select>';
}

public function get_xls_class(){
    
    $id_majors  = $this->input->post('id_majors');
    $dataKamar  = $this->db->query("SELECT * FROM class WHERE majors_majors_id = '$id_majors' ORDER BY class_name ASC")->result_array();
    
    echo '<select required="" name="xls_class" id="xls_class" class="form-control">';
    echo '<option value="">-Pilih Kelas-</option>
		  <option value="all">Semua Kelas</option>';
    foreach($dataKamar as $row){            
        echo '<option value="'.$row['class_id'].'">'.$row['class_name'].'</option>';
    }
    echo '</select>';
}

public function get_xls_madin(){
    
    $id_majors  = $this->input->post('id_majors');
    $dataMadin  = $this->db->query("SELECT * FROM madin ORDER BY madin_id ASC")->result_array();
    
    echo '<select required="" name="xls_madin" id="xls_madin" class="form-control">';
    echo '<option value="">-Pilih Kamar-</option>
		  <option value="all">Semua Kamar</option>';
    foreach($dataMadin as $row){            
        echo '<option value="'.$row['madin_id'].'">'.$row['madin_name'].'</option>';
    }
    echo '</select>';
}

public function print_alumni(){
    ob_start();
    
    $params = array();
    
    $id_majors = $this->input->post('modal_majors');
    $id_kelas  = $this->input->post('modal_class');
    $id_madin  = $this->input->post('modal_madin');
    $id_period = $this->input->post('modal_period');
    
    if($id_majors != 'all'){
        $params['majors_id']  = $id_majors;
        $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
        $majors          = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
    } else {
        $data['majors']  = array('majors_short_name'=>'Semua Unit');
        $majors           = array('majors_short_name'=>'Semua Unit');
    }
    
    if($id_kelas != 'all'){
        $params['class_id']  = $id_kelas;
        $data['kelas']       = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
        $kelas               = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
        $data['kelas']   = array('class_name'=>'Semua Kelas');
        $kelas           = array('class_name'=>'Semua Kelas');
    }
    
    if($id_madin != 'all'){
        $params['madin_id']  = $id_madin;
        $data['madin']       = $this->db->query("SELECT madin_name FROM madin WHERE madin_id = '$id_madin'")->row_array();
        $madin               = $this->db->query("SELECT madin_name FROM madin WHERE madin_id = '$id_madin'")->row_array();
    } else {
        $data['madin']   = array('madin_name'=>'Semua Kamar');
        $madin           = array('madin_name'=>'Semua Kamar');
    }
    
    if($id_period != 'all'){
        $params['period_id']  = $id_period;
        $data['period']       = $this->db->query("SELECT period_end FROM period WHERE period_id = '$id_period'")->row_array();
        $period               = $this->db->query("SELECT period_end FROM period WHERE period_id = '$id_period'")->row_array();
    } else {
        $data['period']         = array('period_end'=>'Semua Tahun');
        $period                 = array('period_end'=>'Semua Tahun');
    }
    
    $data['alumni'] = $this->Alumni_model->get($params);
    
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));  

    $filename = 'Data_Alumni_'.$majors['majors_name'].'_Kelas_'.$kelas['class_name'].'_Kamar_'.$madin['madin_name'].'_Tahun_'.$madin['period_end'].'.pdf';
    
    $this->load->view('alumni/print_alumni',$data);
    
    $html = ob_get_contents();
    ob_end_clean();
    
    require_once('./assets/html2pdf/html2pdf.class.php');
    $pdf = new HTML2PDF('l','A4','en');
    $pdf->WriteHTML($html);
    $pdf->Output($filename);    
}

public function excel_alumni(){
    $params = array();
    
    $id_majors = $this->input->post('xls_majors');
    $id_kelas  = $this->input->post('xls_class');
    $id_madin  = $this->input->post('xls_madin');
    $id_period = $this->input->post('xls_period');
    
    if($id_majors != 'all'){
        $params['majors_id']  = $id_majors;
        $data['majors']  = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
        $majors          = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$id_majors'")->row_array();
    } else {
        $data['majors']  = array('majors_short_name'=>'Semua Unit');
        $majors          = array('majors_short_name'=>'Semua Unit');
    }
    
    if($id_kelas != 'all'){
        $params['class_id']  = $id_kelas;
        $data['kelas']   = $this->db->query("SELECT class_name FROM class WHERE class_id = '$id_kelas'")->row_array();
    } else {
        $data['kelas']   = array('class_name'=>'Semua Kelas');
    }
    
    if($id_madin != 'all'){
        $params['madin_id']  = $id_madin;
        $data['madin']   = $this->db->query("SELECT madin_name FROM madin WHERE madin_id = '$id_madin'")->row_array();
    } else {
        $data['madin']   = array('madin_name'=>'Semua Kamar');
    }
    
    if($id_period != 'all'){
        $params['period_id']  = $id_period;
        $data['period']       = $this->db->query("SELECT period_end FROM period WHERE period_id = '$id_period'")->row_array();
        $period               = $this->db->query("SELECT period_end FROM period WHERE period_id = '$id_period'")->row_array();
    } else {
        $data['period']         = array('period_end'=>'Semua Tahun');
        $period                 = array('period_end'=>'Semua Tahun');
    }
    
    $data['alumni'] = $this->Alumni_model->get($params);
    
    $data['setting_nip_bendahara'] = $this->Setting_model->get(array('id' => 15));
    $data['setting_nama_bendahara'] = $this->Setting_model->get(array('id' => 16));
    $data['setting_city'] = $this->Setting_model->get(array('id' => SCHOOL_CITY));
    $data['setting_district'] = $this->Setting_model->get(array('id' => SCHOOL_DISTRICT)); 
    $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
    $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
    $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE));  
    
    $this->load->view('alumni/excel_alumni',$data);
}

}