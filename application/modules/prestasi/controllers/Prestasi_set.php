<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Prestasi_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('prestasi/Prestasi_model'));
    }

    public function index()
    {
        $data['prestasi']           = $this->Prestasi_model->listPrestasi();
        $data['kategori_prestasi']  = $this->Prestasi_model->kategori_prestasi();

        $data['title']      = 'Prestasi';
        $data['main']       = 'prestasi/prestasi';
        $this->load->view('manage/layout', $data);
    }

    public function tambah_kategori()
    {
        $kategori = sanitizeStrict($this->input->post('kategori'));

        if ($kategori) {
            $colWhere = ["kategori" => $kategori];
            if ($this->General_model->checkDataExists("kategori_prestasi", $colWhere)) {
                $this->session->set_flashdata('failed', 'Kategori sudah ada.');
                redirect('manage/prestasi');
            }
            $this->db->insert('kategori_prestasi', [
                'kategori' => $kategori
            ]);
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('failed', 'Nama kategori tidak boleh kosong.');
        }

        redirect('manage/prestasi');
    }


    public function update_kategori()
    {
        $id = sanitizeStrict($this->input->post('edit_id_kategori'));
        $nama = sanitizeStrict($this->input->post('edit_nama_kategori'));

        if (empty($nama)) {
            $this->session->set_flashdata('failed', 'Kategori harus diisi.');
            redirect('manage/prestasi');
        }

        $colWhere = ["kategori" => $nama, "id <>" => $id];
        if ($this->General_model->checkDataExists("kategori_prestasi", $colWhere)) {
            $this->session->set_flashdata('failed', 'Kategori sudah ada.');
            redirect('manage/prestasi');
        }

        $this->db->where('id', $id);
        $this->db->update('kategori_prestasi', ['kategori' => $nama]);

        $this->session->set_flashdata('success', 'Kategori berhasil diperbarui');
        redirect('manage/prestasi');
    }

    public function delete_kategori()
    {
        $id = $this->input->post('delete_id_kategori');

        // Cek apakah kategori sedang digunakan
        $cek = $this->db->get_where('prestasi', ['id_kategori' => $id])->num_rows();
        if ($cek > 0) {
            $this->session->set_flashdata('failed', 'Kategori tidak bisa dihapus karena sedang digunakan.');
        } else {
            $this->db->delete('kategori_prestasi', ['id' => $id]);
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        }

        redirect('manage/prestasi');
    }


    function add_jenis_prestasi()
    {
        if ($_POST == TRUE) {
            $idKategori = sanitizeStrict($this->input->post('kategori_prestasi'));
            $prestasi = sanitizeStrict($this->input->post('nama_prestasi'));
            $poin = sanitizeStrict($this->input->post('poin_prestasi'));

            if (empty($prestasi) || empty($poin) || empty($idKategori)) {
                $this->session->set_flashdata('failed', 'Data tidak boleh kosong.');
                redirect('manage/prestasi');
            }

            $colWhere = ["prestasi" => $prestasi, "id_kategori" => $idKategori, "poin" => $poin];
            if ($this->General_model->checkDataExists("prestasi", $colWhere)) {
                $this->session->set_flashdata('failed', 'Data sudah ada.');
                redirect('manage/prestasi');
            }

            $data = array();
            $data['id_kategori']  = $idKategori;
            $data['prestasi']  = $prestasi;
            $data['poin']         = $poin;

            $this->db->insert('prestasi', $data);

            $period = $this->input->post('period');
            $nis = $this->input->post('nis');
            $this->session->set_flashdata('success', ' Tambah Jenis Prestasi Berhasil');

            $source = $this->input->post('source');
            if (!empty($source) and $source == 'master') {
                redirect('manage/prestasi');
            }
            redirect('manage/konseling?n=' . $period . '&r=' . $nis . '&m=3');
        }
    }

    public function update_prestasi()
    {
        $id         = sanitizeStrict($this->input->post('id'));
        $prestasi   = sanitizeStrict($this->input->post('prestasi'));
        $kategori   = sanitizeStrict($this->input->post('id_kategori'));
        $poin       = sanitizeStrict($this->input->post('poin'));

        if (empty($prestasi) || empty($poin) || empty($kategori)) {
            $this->session->set_flashdata('failed', 'Data tidak boleh kosong.');
            redirect('manage/prestasi');
        }

        $colWhere = ["prestasi" => $prestasi, "id_kategori" => $kategori, "poin" => $poin, "id <>" => $id];
        if ($this->General_model->checkDataExists("prestasi", $colWhere)) {
            $this->session->set_flashdata('failed', 'Data sudah ada.');
            redirect('manage/prestasi');
        }

        $data = [
            'prestasi' => $prestasi,
            'id_kategori' => $kategori,
            'poin' => $poin
        ];

        $this->db->where('id', $id);
        $this->db->update('prestasi', $data);

        $this->session->set_flashdata('success', 'Data prestasi berhasil diperbarui.');
        redirect('manage/prestasi');
    }

    public function delete_prestasi()
    {
        $id = $this->input->post('del_prestasi');

        if (!$id) {
            $this->session->set_flashdata('failed', 'ID Prestasi tidak valid.');
            redirect('manage/prestasi');
        }

        // Cek apakah ID digunakan di tabel konseling
        $this->db->where('prestasi_id', $id);
        $used = $this->db->get('konseling')->num_rows();

        if ($used > 0) {
            $this->session->set_flashdata('failed', 'Prestasi tidak dapat dihapus karena sedang digunakan dalam data konseling.');
            redirect('manage/prestasi');
        }

        // Jika tidak digunakan, lanjut hapus
        $this->db->where('id', $id);
        $this->db->delete('prestasi');

        $this->session->set_flashdata('success', 'Prestasi berhasil dihapus.');
        redirect('manage/prestasi');
    }

    public function download_template_prestasi()
    {
        $data = file_get_contents("./media/template_excel/template_prestasi.xlsx");
        $name = 'template_prestasi.xlsx';

        $this->load->helper('download');
        force_download($name, $data);
    }

    public function import_prestasi()
    {
        if (empty($_FILES['file_excel']['name'])) {
            $this->session->set_flashdata('failed', 'File belum dipilih.');
            redirect('manage/prestasi');
        }

        $file_mimes = array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if (in_array($_FILES['file_excel']['type'], $file_mimes)) {
            $filePath = $_FILES['file_excel']['tmp_name'];

            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $data_insert = [];
            // Lewati header (baris 1)
            for ($i = 3; $i <= count($sheetData); $i++) {
                $row = $sheetData[$i];
                // Misal kolom A=prestasi, B=id_kategori, C=poin
                if (!empty($row['A'])) {
                    $data_insert[] = [
                        'prestasi' => $row['A'],
                        'poin' => intval($row['B']),
                        'id_kategori' => intval($row['C']),
                    ];
                }
            }

            if (!empty($data_insert)) {
                $this->Prestasi_model->insert_batch_prestasi($data_insert);
                $this->session->set_flashdata('success', 'Data prestasi berhasil diimport.');
            } else {
                $this->session->set_flashdata('failed', 'Data tidak ditemukan dalam file.');
            }
        } else {
            $this->session->set_flashdata('failed', 'Format file tidak didukung.');
        }

        redirect('manage/prestasi');
    }
}
