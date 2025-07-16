<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_keluar_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
      header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }

    $this->load->model(array('surat_keluar/Surat_keluar_model','surat_jenis/Surat_jenis_model', 'setting/Setting_model'));
    $this->load->helper(array('form', 'url'));
  }

// User_customer view in list
  public function index($offset = NULL) {
    $this->load->library('pagination');
    $s = $this->input->get(NULL, TRUE);
    $f = $this->input->get(NULL, TRUE);

    $data['s'] = $s;
    $data['f'] = $f;

    $params = array();

    $data['surat_keluar'] = $this->Surat_keluar_model->get($params);
    $data['setting_logo'] = $this->Setting_model->get(array('id' => 6));
    //$config['per_page'] = 10;
    //$config['uri_segment'] = 4;
    $config['base_url'] = site_url('manage/surat_keluar/index');
    $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    //$config['total_rows'] = count($this->Student_model->get_class($paramsPage));
    //$this->pagination->initialize($config);
    // $data['majors'] = $this->Student_model->get_majors();
    
    $data['title'] = 'Data Surat Keluar';
    $data['main'] = 'surat_keluar/surat_keluar_list';
    $this->load->view('manage/layout', $data);
  }


// Add User_customer and Update
  public function add($id = NULL) {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('no_surat', 'No. Surat', 'trim|required|xss_clean');
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button ket="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';
    
    $data['jenis'] = $this->Surat_jenis_model->get();
    if ($_POST AND $this->form_validation->run() == TRUE) {
      
      if ($this->input->post('id_surat')) {
        $params['id_surat'] = $this->input->post('id_surat');
      }
      $params['id_jenis_surat']       = $this->input->post('id_jenis');
      $params['tujuan']     = $this->input->post('tujuan');
      $params['no_surat']         = $this->input->post('no_surat');
      $params['isi']      = $this->input->post('isi');
      $params['kode']         = $this->input->post('kode');
      $params['tgl_surat']     = $this->input->post('tgl_surat');
      $params['tgl_catat']        = date('Y-m-d');
      $params['keterangan']      = $this->input->post('keterangan');
      $params['status']         = $this->input->post('status');
      $params['id_user']       = $this->input->post('id_users');
      $status                   = $this->Surat_keluar_model->add($params);

      if (!empty($_FILES['file']['name'])) {
        $paramsupdate['file'] = $this->do_upload($name = 'file', $fileName= $params['file']);
      } 

      $paramsupdate['id_surat'] = $status;
      $this->Surat_keluar_model->add($paramsupdate);

      $this->session->set_flashdata('success', $data['operation'] . ' Nama Satuan Arsip');
      redirect('manage/surat_keluar');

      if ($this->input->post('from_angular')) {
        echo $status;
      }
    } else {
      if ($this->input->post('id_surat')) {
        redirect('manage/surat_keluar/edit/' . $this->input->post('id_surat'));
      }

      // Edit mode
      if (!is_null($id)) {
        $object = $this->Surat_keluar_model->get(array('id' => $id));
        if ($object == NULL) {
          redirect('manage/surat_keluar');
        } else {
          $data['surat_keluar'] = $object;
        }
      }
      $data['title'] = $data['operation'] . ' Surat Keluar';
      $data['main'] = 'surat_keluar/surat_keluar_add';
      $this->load->view('manage/layout', $data);
    }
  }

  // Setting Upload File Requied
  function do_upload($name=NULL, $fileName=NULL) {
    $this->load->library('upload');

    $config['upload_path'] = FCPATH . 'uploads/surat_keluar/';

    /* create directory if not exist */
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $config['allowed_types'] = 'pdf';
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


// Delete to database
  public function delete($id = NULL) {
    if ($this->session->userdata('uroleid')!= SUPERUSER){
      redirect('manage');
    }
    // $satuan = $this->Surat_keluar_model->get(array('id_surat'=>$id));

    if ($_POST) {

      // if (count($satuan) > 0) {
      //   $this->session->set_flashdata('failed', 'Data Satuan tidak dapat dihapus');
      //   redirect('manage/surat_keluar');
      // }

      $this->Surat_keluar_model->delete($id);
      // activity log
      $this->load->model('logs/Logs_model');
      $this->Logs_model->add(
        array(
          'log_date' => date('Y-m-d H:i:s'),
          'user_id' => $this->session->userdata('uid'),
          'log_module' => 'user',
          'log_action' => 'Hapus',
          'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delName')
        )
      );
      $this->session->set_flashdata('success', 'Hapus Data Surat Keluar berhasil');
      redirect('manage/surat_keluar');
    } elseif (!$_POST) {
      $this->session->set_flashdata('delete', 'Delete');
      redirect('manage/surat_keluar/edit/' . $id);
    }  
  }

   // View data detail
   public function view($id = NULL) {
    $data['surat_keluar'] = $this->Surat_keluar_model->get(array('id' => $id));
    $data['title'] = 'Surat Keluar';
    $data['main'] = 'surat_keluar/surat_keluar_view';
    $this->load->view('manage/layout', $data);
  }


  

  //-----------------------------Download------------------------------------
  public function download_sk(){
    $id_surat   = $_POST['id_surat'];
    $file = $_POST['file'];
    if($file ==''){
      $this->session->set_flashdata('failed',' File Tidak Ada')== TRUE;
      redirect('manage/surat_keluar/surat_keluar_list');
    }else{
      $this->load->helper('download');
      $file = 'uploads/surat_keluar/'.$file;
      force_download($file, null);
    }
    
  }

  public function cari_nosur(){
      $id_jenis = $this->input->post('id_jenis');
      $jenisKD = $this->Surat_jenis_model->get(array('id'=>$id_jenis));
    
      $now = date('dmy');
          
      $query = $this->db->query("SELECT MAX(RIGHT(no_surat,2)) AS no_max FROM tbl_surat_keluar 
      WHERE SUBSTR(no_surat, -8, 6) = '$now'")->row();
      
      if (count(array($query))>0){
          $tmp = ((int)$query->no_max)+1;
          $norut = sprintf("%02s", $tmp);
      } else {
          $norut = "01";
      }

      $date =  date('Y-m');
      $nosur= $jenisKD['kode_surat'].'SK/'.$date.'/'.$now.$norut;
    
    echo '
        <label>Nomor Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
        <input name="no_surat"  id="no_surat" type="text" class="form-control" value="'.$nosur.'" readonly="" placeholder="Nomor Surat">
        
    ';
  }

  function printRekap(){
      ob_start();
        
      $this->load->library('pagination');
      $s = $this->input->get(NULL, TRUE);
      $f = $this->input->get(NULL, TRUE);

      $data['s'] = $s;
      $data['f'] = $f;

      $params = array();

      $data['surat_keluar'] = $this->Surat_keluar_model->get($params);
      
      $data['period'] 	= $this->db->query("SELECT period_id, period_start, period_end FROM period WHERE period_status='1' ")->row_array();

      $data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME)); 
      $data['setting_address'] = $this->Setting_model->get(array('id' => SCHOOL_ADRESS)); 
      $data['setting_phone'] = $this->Setting_model->get(array('id' => SCHOOL_PHONE)); 

      $filename = 'Laporan Data Surat Keluar.pdf';
      
      $this->load->view('surat_keluar/surat_keluar_print', $data);
      
      $html = ob_get_contents();
      ob_clean();
      
      require_once('./assets/html2pdf/html2pdf.class.php');
      $pdf = new HTML2PDF('L','A4','en', false, 'ISO-8859-15',array(10, 5, 10, 5));
      
      $pdf->setDefaultFont('arial');
      $pdf->setTestTdInOnePage(false);
      $pdf->pdf->SetDisplayMode('fullpage');
      $pdf->WriteHTML($html);
      $pdf->Output($filename, '');
  }
}
