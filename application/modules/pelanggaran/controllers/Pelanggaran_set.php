<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Pelanggaran_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('pelanggaran/Pelanggaran_model'));
    }

    public function index()
    {
        $data['pelanggaran']           = $this->Pelanggaran_model->listPelanggaran();
        $data['kategori_pelanggaran']  = $this->Pelanggaran_model->kategori_pelanggaran();

        $data['title']      = 'Pelanggaran';
        $data['main']       = 'pelanggaran/pelanggaran';
        $this->load->view('manage/layout', $data);
    }

    public function simpan_pelanggaran()
    {
        $id = sanitizeStrict($this->input->post('id'));
        $pelanggaran = sanitizeStrict($this->input->post('pelanggaran'));
        $poin = sanitizeStrict($this->input->post('poin'));
        $idKategori = sanitizeStrict($this->input->post('id_kategori'));

        $data = [
            'pelanggaran' => $pelanggaran,
            'poin' => $poin,
            'id_kategori' => $idKategori,
        ];
        $colWhere = $data;

        if ($id) {
            $colWhere['id <>'] = $id;
            if ($this->General_model->checkDataExists("pelanggaran", $colWhere)) {
                $this->session->set_flashdata('failed', 'Data Jenis Pelanggaran sudah ada.');
                redirect('manage/pelanggaran');
            }
            $this->db->where('id', $id)->update('pelanggaran', $data);
        } else {
            if ($this->General_model->checkDataExists("pelanggaran", $colWhere)) {
                $this->session->set_flashdata('failed', 'Data Jenis Pelanggaran sudah ada.');
                redirect('manage/pelanggaran');
            }
            $this->db->insert('pelanggaran', $data);
        }

        $this->session->set_flashdata('success', 'Pelanggaran berhasil disimpan.');
        redirect('manage/pelanggaran');
    }

    public function simpan_kategori_pelanggaran()
    {
        $id = sanitizeStrict($this->input->post('id'));
        $kategori = sanitizeStrict($this->input->post('kategori'));

        $data = ['kategori' => $kategori];
        $colWhere = $data;

        if ($id) {
            $colWhere['id <>'] = $id;
            if ($this->General_model->checkDataExists("kategori_pelanggaran", $colWhere)) {
                $this->session->set_flashdata('failed', 'Data Kategori Pelanggaran sudah ada.');
                redirect('manage/pelanggaran');
            }
            $this->db->where('id', $id)->update('kategori_pelanggaran', $data);
        } else {
            if ($this->General_model->checkDataExists("kategori_pelanggaran", $colWhere)) {
                $this->session->set_flashdata('failed', 'Data Kategori Pelanggaran sudah ada.');
                redirect('manage/pelanggaran');
            }
            $this->db->insert('kategori_pelanggaran', $data);
        }

        $this->session->set_flashdata('success', 'Kategori berhasil disimpan.');
        redirect('manage/pelanggaran');
    }

    public function delete_pelanggaran($id)
    {
        $in_use = $this->db->get_where('konseling', ['pelanggaran_id' => $id])->num_rows();
        if ($in_use == 0) {
            $this->db->delete('pelanggaran', ['id' => $id]);
            $this->session->set_flashdata('success', 'pelanggaran berhasil disimpan.');
        } else {
            $this->session->set_flashdata('failed', 'pelanggaran masih digunakan di konseling.');
        }
        redirect('manage/pelanggaran');
    }

    public function delete_kategori_pelanggaran($id)
    {
        $in_use = $this->db->get_where('pelanggaran', ['id_kategori' => $id])->num_rows();
        if ($in_use == 0) {
            $this->db->delete('kategori_pelanggaran', ['id' => $id]);
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('failed', 'Kategori sedang digunakan.');
        }
        redirect('manage/pelanggaran');
    }

    public function download_template_pelanggaran()
    {
        $data = file_get_contents("./media/template_excel/template_pelanggaran.xlsx");
        $name = 'template_pelanggaran.xlsx';

        $this->load->helper('download');
        force_download($name, $data);
    }

    public function import_pelanggaran()
    {
        $file = $_FILES['file_excel']['tmp_name'];

        if ($file) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            foreach ($sheet as $i => $row) {
                if ($i < 2) continue; // skip header

                $data = [
                    'pelanggaran' => $row['A'],
                    'poin' => (int)$row['B'],
                    'id_kategori' => (int)$row['C']
                ];

                if (!empty($data['pelanggaran'])) {
                    $this->db->insert('pelanggaran', $data);
                }
            }

            $this->session->set_flashdata('success', 'Data pelanggaran berhasil diimport.');
        }

        redirect('manage/pelanggaran');
    }
}
