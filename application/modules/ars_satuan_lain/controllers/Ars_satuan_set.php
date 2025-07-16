<?php

/*developed by ismarianto putra
  you can visit my site in ismarianto.com
  for more complain anda more information.  
*/

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ars_satuan_set extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // login_access();
        // hak_akses();
        $this->load->model('ars_satuan/M_satuan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper(array('form', 'url'));
    }

    public function index($offset = NULL) 
    {
        // echo 'coba';
        // exit;
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);
        // catat_log($this->session->id_user, $_SERVER['REQUEST_URI'], 'Akses data satuan.', $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
        // if ($this->session->level != 'admin' and $this->session->level != 'staff') {
        //     redirect(base_url('404'));
        //     exit();
        // }
        $x['judul'] = 'Satuan Arsip';
        $this->template->load('template', 'ars_satuan/m_satuan_list', $x);
        $data['title'] = 'Data Jenis Arsip';
        $data['main'] = 'ars_satuan/m_satuan_list';
        $this->load->view('manage/layout', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->M_satuan_model->json();
    }

    public function detail($id)
    {
        if ($this->session->level != 'admin' and $this->session->level != 'staff') {
            redirect(base_url('404'));
            exit();
        }

        $row = $this->M_satuan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_satuan' => $row->id_satuan,
                'nama_satuan' => $row->nama_satuan,
                'keterangan' => $row->keterangan,

                'judul' => 'Detail :  M_SATUAN',
            );
            $this->template->load('template', 'm_satuan/m_satuan_read', $data);
        } else {
            $this->session->set_flashdata('message', '<div class="callout callout-warniing fade-in">Data Tidak Di Temukan.</div>');
            redirect(site_url('m_satuan'));
        }
    }

    public function tambah()
    {
        // catat_log($this->session->id_user, $_SERVER['REQUEST_URI'], 'menambahkan satuan arsip.', $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
        $data = array(
            'judul' => 'Tambah M satuan',
            'button' => 'Create',
            'action' => site_url('m_satuan/tambah_data'),
            'id_satuan' => set_value('id_satuan'),
            'nama_satuan' => set_value('nama_satuan'),
            'keterangan' => set_value('keterangan'),
        );
        $this->load->view('m_satuan/m_satuan_form', $data);
    }

    public function tambah_data()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $respon = array(
                'ket' => 2,
                'respon' => validation_errors()
            );
            echo json_encode($respon);
        } else {
            $data = array(
                'nama_satuan' => $this->input->post('nama_satuan', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->M_satuan_model->insert($data);
            $respon = array(
                'ket' => 1,
                'respon' => 'data berhasil di tambah'
            );
            echo json_encode($respon);
        }
    }

    public function edit($id)
    {
        $row = $this->M_satuan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'judul' => 'Data M_SATUAN',
                'button' => 'Update',
                'action' => site_url('m_satuan/edit_data'),
                'id_satuan' => set_value('id_satuan', $row->id_satuan),
                'nama_satuan' => set_value('nama_satuan', $row->nama_satuan),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('m_satuan/m_satuan_form', $data);
        } else {
            $this->session->set_flashdata('message', '<div class="callout callout-info fade-in">Data Tidak Di Temukan.</div>');
            redirect(site_url('m_satuan'));
        }
    }

    public function edit_data()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $respon = array(
                'ket' => 2,
                'respon' => validation_errors()
            );
            echo json_encode($respon);
        } else {
            $data = array(
                'nama_satuan' => $this->input->post('nama_satuan', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );
            $this->M_satuan_model->update($this->input->post('id_satuan', TRUE), $data);
            $respon = array(
                'ket' => 1,
                'respon' => 'Edit data berhasil'
            );
            echo json_encode($respon);
        }
    }

    public function hapus($id)
    {

        if ($this->session->level != 'admin' and $this->session->level != 'staff') {
            redirect(base_url('404'));
            exit();
        }

        $row = $this->M_satuan_model->get_by_id($id);

        if ($row) {
            // catat_log($this->session->id_user, $_SERVER['REQUEST_URI'], 'menghapus satuan arsip.', $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
            $this->M_satuan_model->delete($id);
            $this->session->set_flashdata('message', '<div class="callout callout-danger fade-in"><i class="fa fa-check"></i>Data Berhasil Di Hapus</div>');
            redirect(site_url('m_satuan'));
        } else {
            $this->session->set_flashdata('message', '<div class="callout callout-warniing fade-in">Ops Something Went Wrong Please Contact Administrator.</div>');
            redirect(site_url('m_satuan'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama_satuan', 'nama satuan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id_satuan', 'id_satuan', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "m_satuan.xls";
        $judul = "m_satuan";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Satuan");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->M_satuan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_satuan);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=m_satuan.doc");

        $data = array(
            'm_satuan_data' => $this->M_satuan_model->get_all(),
            'start' => 0
        );
        $this->load->view('m_satuan/m_satuan_doc', $data);
    }
}
