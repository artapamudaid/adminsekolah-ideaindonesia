<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Account_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'account/Account_model', 'pos/Pos_model', 'period/Period_model', 'payment/Payment_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    public function index($offset = NULL)
    {
        $this->load->library('pagination');

        $s = $this->input->get(NULL, TRUE);

        $data['s'] = $s;

        $majors_id = $this->session->userdata('umajorsid');
        $filter_majors = null;
        if ($majors_id == 0) {
            if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
                $filter_majors = $s['m'];
            }
        } else {
            $filter_majors = $majors_id;
        }

        // Ambil data hierarki COA dalam 1 batch query efisien (menghindari nested query N+1 di view)
        $coa_data = $this->Account_model->get_coa_tree_data($filter_majors);
        $data = array_merge($data, $coa_data);

        $config['base_url'] = site_url('manage/account/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $data['majors'] = $this->Student_model->get_majors();

        $data['title'] = 'Akun Biaya';

        $data['main'] = 'account/account_list';

        $this->load->view('manage/layout', $data);
    }

    public function add_glob()
    {
        if ($_POST == TRUE) {
            $accountCode = $_POST['account_code'];
            $accountKet = $_POST['account_description'];
            $accountNote = $_POST['account_note'];
            $accountCat = $_POST['account_category'];
            $accountMajorsId = $_POST['account_majors_id'];

            $majors   = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$accountMajorsId'")->row_array();
            $cekCode  = $this->db->query("SELECT COUNT(account_code) as numCode FROM account WHERE account_code = '$accountCode'")->row();

            if (substr($accountCode, 0, 3) == '5-5' && $accountCat != 0) {
                $akun = 'Biaya ' . $accountKet . ' ' . $majors['majors_short_name'];
            } else {
                $akun = $accountKet . ' ' . $majors['majors_short_name'];
            }

            if ($cekCode->numCode == '0') {
                $params['account_code']         = $accountCode;
                $params['account_description']  = $akun;
                $params['account_note']         = $accountNote;
                $params['account_category']     = $accountCat;
                $params['account_majors_id']    = $accountMajorsId;

                $this->Account_model->add($params);

                $account_id = $this->db->insert_id();

                $this->db->query("UPDATE account SET account_status = 0 WHERE account_id = '$accountNote'");

                if ($accountCat == '1') {

                    // Data POS & Jenis Bayar dari wizard di modal (jika ada)
                    if ($this->input->post('piutang_account_id')) {
                        $piutang_id     = $this->input->post('piutang_account_id');
                        $pos_name       = $this->input->post('pos_name');
                        $pos_desc       = $this->input->post('pos_description');
                        $period_id      = $this->input->post('period_id');
                        $payment_type   = $this->input->post('payment_type') ?: 'BULAN';
                        $payment_mode   = $this->input->post('paymentModes') ?: 'TETAP';
                        $is_batch       = $this->input->post('paymentIsBatchs') ?: '0';
                    } else {
                        // Fallback: perilaku lama (auto)
                        $piutang_id   = $this->akun_piutang($accountMajorsId);
                        $pos_name     = $accountKet;
                        $pos_desc     = $accountKet;
                        $period       = $this->db->query("SELECT period_id as id FROM period WHERE period_status = '1'")->row_array();
                        $period_id    = $period['id'];
                        $payment_type = 'BULAN';
                        $payment_mode = 'TETAP';
                        $is_batch     = '0';
                    }

                    if (isset($piutang_id)) {
                        $check_account = $this->db->get_where('pos', ['account_account_id' => $account_id])->num_rows();

                        if ($check_account == 0) {
                            $this->db->insert('pos', [
                                'pos_name'           => $pos_name,
                                'pos_description'    => $pos_desc,
                                'account_account_id' => $account_id,
                                'piutang_account_id' => $piutang_id,
                            ]);

                            $pos_id = $this->db->insert_id();

                            $this->db->insert('payment', [
                                'payment_type'     => $payment_type,
                                'payment_mode'     => $payment_mode,
                                'payment_is_batch' => $is_batch,
                                'period_period_id' => $period_id,
                                'pos_pos_id'       => $pos_id,
                            ]);
                        }
                    }
                }

                $this->session->set_flashdata('success', ' Tambah Akun Berhasil');
                redirect('manage/account/?m=' . $accountMajorsId);
            } else {
                $this->session->set_flashdata('failed', ' Kode Akun Sudah Ada. Silahkan Buat Akun Lain');
                redirect('manage/account/?m=' . $accountMajorsId);
            }
        }
    }

    public function add($id = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('account_code', 'Akun Biaya', 'trim|required|xss_clean');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST and $this->form_validation->run() == TRUE) {

            if ($this->input->post('account_id')) {
                $params['account_id'] = $this->input->post('account_id');
            }

            $params['account_code'] = $this->input->post('account_code');
            $params['account_description'] = $this->input->post('account_description');
            $params['account_note'] = $this->input->post('account_note');
            $params['account_category'] = $this->input->post('account_category');
            $params['account_majors_id'] = $this->input->post('account_majors_id');

            $accountMajorsId = $this->input->post('account_majors_id');

            $accountNote = $this->input->post('account_note');

            $status = $this->Account_model->add($params);

            $paramsupdate['account_id'] = $status;

            $this->Account_model->add($paramsupdate);

            $this->db->query("UPDATE account SET account_status = 0 WHERE account_id = '$accountNote'");

            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Akun Biaya',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:null;Title:' . $params['account_code']
                )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' Akun Biaya berhasil');
            redirect('manage/account/?m=' . $accountMajorsId);
        } else {
            if ($this->input->post('account_id')) {
                redirect('manage/account/edit/' . $this->input->post('account_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $data['account'] = $this->Account_model->get(array('id' => $id));
                $data['majors'] = $this->Student_model->get_majors();
            }

            $data['title'] = $data['operation'] . ' Akun Biaya';
            $data['main'] = 'account/account_add';
            $this->load->view('manage/layout', $data);
        }
    }

    private function akun_piutang($id = null)
    {
        $akun_piutang = $this->db->query("SELECT account_id FROM account
                                            WHERE account_code LIKE '1-1%'
                                            AND account_description LIKE '%PIUTANG SISWA%'
                                            AND account_majors_id ='$id'")->row_array();

        return $akun_piutang['account_id'];
    }

    public function delete_multi()
    {
        if ($this->session->userdata('uroleid') != 3) {
            redirect('manage');
        }

        if ($_POST) {
            $account_ids = $this->input->post('account_id');
            $majors_id = $this->input->post('majors_id');

            if (empty($account_ids) || !is_array($account_ids)) {
                $this->session->set_flashdata('failed', 'Tidak ada data yang dipilih untuk dihapus.');
                redirect('manage/account/?m=' . $majors_id);
            }

            // Filter master data (ID 1-7)
            $account_ids = array_filter($account_ids, function ($id) {
                return $id > 7;
            });

            if (empty($account_ids)) {
                $this->session->set_flashdata('failed', 'Data master tidak dapat dihapus.');
                redirect('manage/account/?m=' . $majors_id);
            }

            // Get descendants
            $descendant_ids = $this->Account_model->get_descendants_ids($account_ids);

            // Gabung dan jadikan unique
            $all_ids_to_delete = array_unique(array_merge($account_ids, $descendant_ids));

            // Cek relasi POS
            $this->db->select('account_account_id');
            $this->db->where_in('account_account_id', $all_ids_to_delete);
            $pos_relations = $this->db->get('pos')->result_array();

            $pos_account_ids = array_column($pos_relations, 'account_account_id');

            // Pisahkan yang bisa dihapus dan yang tidak
            $ids_to_delete = array_diff($all_ids_to_delete, $pos_account_ids);
            $skipped_count = count($all_ids_to_delete) - count($ids_to_delete);

            if (empty($ids_to_delete)) {
                $this->session->set_flashdata('failed', 'Semua akun yang dipilih terkait dengan POS sehingga tidak dapat dihapus.');
                redirect('manage/account/?m=' . $majors_id);
            }

            // Hapus data
            $this->db->where_in('account_id', $ids_to_delete);
            $this->db->delete('account');
            bump_cache_version('account');

            // Log activity
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'Akun Bayar',
                    'log_action' => 'Hapus Multi',
                    'log_info' => 'Hapus ' . count($ids_to_delete) . ' akun (ID: ' . implode(',', $ids_to_delete) . ')'
                )
            );

            $msg = 'Berhasil menghapus ' . count($ids_to_delete) . ' akun.';
            if ($skipped_count > 0) {
                $msg .= ' ' . $skipped_count . ' akun dilewati karena terhubung dengan POS.';
            }

            $this->session->set_flashdata('success', $msg);
            redirect('manage/account/?m=' . $majors_id);
        }
    }

    public function delete($id = NULL)
    {
        if ($this->session->userdata('uroleid') != SUPERUSER && $this->session->userdata('uroleid') != 3) {
            redirect('manage');
        }

        if ($_POST) {

            $pos = $this->Pos_model->get(array('account_id' => $id));

            $account = $this->db->query("SELECT account_majors_id FROM account WHERE account_id = '$id'")->row_array();

            if (count($pos) > 0) {
                $this->session->set_flashdata('failed', 'Data Akun tidak dapat dihapus');
                redirect('manage/account/?m=' . $account['account_majors_id']);
            }

            $this->Account_model->delete($id);
            // activity log
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'Akun Bayar',
                    'log_action' => 'Hapus',
                    'log_info' => 'ID:' . $id . ';Title:' . $this->input->post('delCode')
                )
            );

            $this->session->set_flashdata('success', 'Hapus akun biaya berhasil');
            redirect('manage/account/?m=' . $account['account_majors_id']);
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('manage/account/edit/' . $id);
        }
    }

    public function import_account()
    {
        $s = $this->input->get('m');
        $user_majors = $this->session->userdata('umajorsid');

        if (empty($s) && $user_majors != '0') {
            $s = $user_majors;
        }

        $redirectUrl = site_url("manage/account");
        if (!empty($s) && $s != 'all') {
            $redirectUrl .= "?m=$s";
        }

        $data['majors'] = $this->Student_model->get_majors();
        $data['s'] = (!empty($s) && $s != 'all') ? $s : '';
        $data['title'] = 'Import Akun Biaya';
        $data['main'] = 'account/account_import';
        $data['redirect_url'] = $redirectUrl;
        $this->load->view('manage/layout', $data);
    }

    public function download()
    {
        $s = $this->input->get('m');

        if (empty($s) || $s == 'all') {
            $this->session->set_flashdata('failed', 'Pilih Unit Sekolah terlebih dahulu.');
            redirect('manage/account/import_account?m=' . $s);
        }

        $majors_id = sanitizeStrict($s);
        $colWhere = ['majors_id' => $majors_id];
        if (!$this->General_model->checkDataExists('majors', $colWhere)) {
            $this->session->set_flashdata('failed', 'Unit Sekolah tidak ditemukan.');
            redirect('manage/account/import_account');
        }

        $majors   = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$majors_id'")->row_array();
        $unitName = $majors['majors_short_name'];

        $fileName = 'Template-Import-Akun-' . $unitName;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Indoweb")
            ->setLastModifiedBy("Indoweb")
            ->setTitle($fileName)
            ->setSubject($fileName)
            ->setDescription("Template Import Akun Biaya untuk " . $unitName . ", Dibuat dengan PhpSpreadsheet.")
            ->setCategory("file Excel");

        // Style definitions
        $styleHeaderBanner = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']]
        ];

        $styleSubBanner = [
            'font' => ['italic' => true, 'color' => ['rgb' => '334155'], 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']]
        ];

        $styleColHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0F172A']]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']]
        ];

        $styleColHint = [
            'font' => ['italic' => true, 'color' => ['rgb' => '854D0E'], 'size' => 9],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']]
        ];

        $styleExampleRow = [
            'font' => ['italic' => true, 'color' => ['rgb' => '475569'], 'size' => 10],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']]
        ];

        $styleDataRow = [
            'font' => ['size' => 10],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]]
        ];

        // ==========================================
        // SHEET 1: DATA_AKUN (Format Sederhana 3 Kolom)
        // ==========================================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('DATA_AKUN');

        // Banner
        $sheet1->mergeCells('A1:C1');
        $sheet1->setCellValue('A1', 'TEMPLATE IMPORT AKUN BIAYA / COA - UNIT ' . strtoupper($unitName));
        $sheet1->getStyle('A1:C1')->applyFromArray($styleHeaderBanner);
        $sheet1->getRowDimension(1)->setRowHeight(32);

        $sheet1->mergeCells('A2:C2');
        $sheet1->setCellValue('A2', 'Petunjuk: Isi Kode Induk (lihat sheet DAFTAR_KODE_INDUK), Nama Akun, dan Kategori. Mendukung banyak anak akun sekaligus.');
        $sheet1->getStyle('A2:C2')->applyFromArray($styleSubBanner);
        $sheet1->getRowDimension(2)->setRowHeight(22);

        // Header Kolom (Baris 3)
        $sheet1->setCellValue('A3', 'KODE_INDUK');
        $sheet1->setCellValue('B3', 'NAMA_AKUN');
        $sheet1->setCellValue('C3', 'KATEGORI');
        $sheet1->getStyle('A3:C3')->applyFromArray($styleColHeader);
        $sheet1->getRowDimension(3)->setRowHeight(28);

        // Subheader Hint (Baris 4)
        $sheet1->setCellValue('A4', 'Wajib diisi (Contoh: 5-50100)');
        $sheet1->setCellValue('B4', 'Wajib diisi (Nama / Keterangan Akun)');
        $sheet1->setCellValue('C4', 'Pilih: Keuangan / Pembayaran');
        $sheet1->getStyle('A4:C4')->applyFromArray($styleColHint);
        $sheet1->getRowDimension(4)->setRowHeight(22);

        // Baris Contoh (Baris 5: Pembayaran, Baris 6: Keuangan)
        $sheet1->setCellValue('A5', '4-40100');
        $sheet1->setCellValue('B5', 'Uang Gedung Siswa (Contoh)');
        $sheet1->setCellValue('C5', 'Pembayaran');
        $sheet1->getStyle('A5:C5')->applyFromArray($styleExampleRow);
        $sheet1->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->getRowDimension(5)->setRowHeight(22);

        $sheet1->setCellValue('A6', '5-50100');
        $sheet1->setCellValue('B6', 'Biaya Listrik & Air (Contoh)');
        $sheet1->setCellValue('C6', 'Keuangan');
        $sheet1->getStyle('A6:C6')->applyFromArray($styleExampleRow);
        $sheet1->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle('C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet1->getRowDimension(6)->setRowHeight(22);

        // Dropdown validation & border untuk baris 5 s/d 100
        for ($r = 5; $r <= 100; $r++) {
            if ($r > 6) {
                $sheet1->getStyle("A{$r}:C{$r}")->applyFromArray($styleDataRow);
                $sheet1->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet1->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet1->getRowDimension($r)->setRowHeight(22);
            }

            // Dropdown di Kolom C
            $validation = $sheet1->getCell("C$r")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Pilihan Kategori');
            $validation->setError('Pilih Keuangan atau Pembayaran');
            $validation->setPromptTitle('Pilih Kategori');
            $validation->setPrompt('Pilih Keuangan atau Pembayaran');
            $validation->setFormula1('"Keuangan,Pembayaran"');
        }

        $sheet1->getColumnDimension('A')->setWidth(24);
        $sheet1->getColumnDimension('B')->setWidth(50);
        $sheet1->getColumnDimension('C')->setWidth(24);
        $sheet1->freezePane('A5');

        // ==========================================
        // SHEET 2: DAFTAR_KODE_INDUK (Referensi Lengkap)
        // ==========================================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('DAFTAR_KODE_INDUK');

        $sheet2->mergeCells('A1:D1');
        $sheet2->setCellValue('A1', 'DAFTAR REFERENSI KODE AKUN INDUK - UNIT ' . strtoupper($unitName));
        $sheet2->getStyle('A1:D1')->applyFromArray($styleHeaderBanner);
        $sheet2->getRowDimension(1)->setRowHeight(30);

        $sheet2->mergeCells('A2:D2');
        $sheet2->setCellValue('A2', 'Salin (copy) Kode Akun di bawah ini lalu tempel (paste) ke kolom KODE_INDUK di sheet DATA_AKUN.');
        $sheet2->getStyle('A2:D2')->applyFromArray($styleSubBanner);
        $sheet2->getRowDimension(2)->setRowHeight(22);

        $sheet2->setCellValue('A3', 'KODE AKUN');
        $sheet2->setCellValue('B3', 'NAMA AKUN');
        $sheet2->setCellValue('C3', 'TINGKAT AKUN');
        $sheet2->setCellValue('D3', 'KATEGORI');
        $sheet2->getStyle('A3:D3')->applyFromArray($styleColHeader);
        $sheet2->getRowDimension(3)->setRowHeight(26);

        // Ambil hierarki akun
        $defaultAccount = $this->Account_model->getDefaultAccount();
        $majorsAccount = $this->Account_model->get(['account_majors_id' => $majors_id]);
        $allAccounts = array_merge($defaultAccount, $majorsAccount);
        $sorted = $this->sortAccountsByParentChild($allAccounts);

        $r2 = 4;
        $levelLabels = [
            'header' => 'Akun Utama (Header)',
            'sub1'   => 'Sub Akun 1',
            'sub2'   => 'Sub Akun 2',
            'sub3'   => 'Sub Akun 3',
            'sub4'   => 'Sub Akun 4 (Maksimal)'
        ];

        foreach ($sorted as $acc) {
            // Sub 4 tidak bisa jadi induk
            if ($acc['type'] === 'sub4') {
                continue;
            }

            $catLabel = ($acc['account_category'] == '1') ? 'Pembayaran' : (($acc['account_category'] == '2') ? 'Keuangan' : '-');
            $levelLabel = isset($levelLabels[$acc['type']]) ? $levelLabels[$acc['type']] : ucfirst($acc['type']);

            $indent = '';
            if (strpos($acc['type'], 'sub') === 0) {
                $lvlNum = (int) str_replace('sub', '', $acc['type']);
                $indent = str_repeat('   ', $lvlNum);
            }

            $sheet2->setCellValue('A' . $r2, $acc['account_code']);
            $sheet2->setCellValue('B' . $r2, $indent . $acc['account_description']);
            $sheet2->setCellValue('C' . $r2, $levelLabel);
            $sheet2->setCellValue('D' . $r2, $catLabel);

            $sheet2->getStyle("A{$r2}:D{$r2}")->applyFromArray($styleDataRow);
            $sheet2->getStyle('A' . $r2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('C' . $r2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('D' . $r2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            if ($acc['type'] === 'header') {
                $sheet2->getStyle("A{$r2}:D{$r2}")->getFont()->setBold(true);
                $sheet2->getStyle("A{$r2}:D{$r2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2E8F0');
            }

            $sheet2->getRowDimension($r2)->setRowHeight(20);
            $r2++;
        }

        $sheet2->getColumnDimension('A')->setWidth(20);
        $sheet2->getColumnDimension('B')->setWidth(50);
        $sheet2->getColumnDimension('C')->setWidth(26);
        $sheet2->getColumnDimension('D')->setWidth(18);
        $sheet2->freezePane('A4');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function do_import()
    {
        $s = $this->input->get('m');

        if (empty($s) || $s == 'all') {
            $this->session->set_flashdata('failed', 'Pilih Unit Sekolah terlebih dahulu.');
            redirect('manage/account/import_account?m=' . $s);
        }

        $majors_id = sanitizeStrict($s);

        $config['upload_path'] = FCPATH . 'uploads/xls_tmp/';
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = '10240';
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $this->session->set_flashdata('failed', $this->upload->display_errors('', ''));
            redirect('manage/account/import_account?m=' . $s);
        }

        $upload_data = $this->upload->data();
        $fullPath = $upload_data['full_path'];

        try {
            $reader = IOFactory::createReaderForFile($fullPath);
            $spreadsheet = $reader->load($fullPath);

            // Deteksi sheet: utamakan DATA_AKUN, lalu INPUT_DATA, lalu sheet index 0
            $sheet = $spreadsheet->getSheetByName('DATA_AKUN');
            if (!$sheet) {
                $sheet = $spreadsheet->getSheetByName('INPUT_DATA');
            }
            if (!$sheet) {
                $sheet = $spreadsheet->getSheet(0);
            }

            $rawRows = $sheet->toArray();

            // Kumpulkan baris yang valid
            $rowsToProcess = [];
            foreach ($rawRows as $rowIdx => $row) {
                $colA = isset($row[0]) ? trim((string)$row[0]) : '';
                $colB = isset($row[1]) ? trim((string)$row[1]) : '';
                $colC = isset($row[2]) ? trim((string)$row[2]) : '';

                // Dukungan jika file menggunakan format lama (8 kolom, di mana Account Description di kolom H / index 7)
                if (count($row) >= 8 && empty($colC) && !empty($row[7])) {
                    $colA = isset($row[2]) ? trim((string)$row[2]) : $colA; // PARENT_CODE
                    $colB = trim((string)$row[7]); // ACCOUNT_DESCRIPTION
                    $colC = isset($row[5]) ? trim((string)$row[5]) : 'Keuangan'; // CATEGORY_NAME
                }

                // Abaikan header, petunjuk, dan baris kosong
                if (empty($colA) || empty($colB)) {
                    continue;
                }
                if (stripos($colA, 'KODE') !== FALSE || stripos($colA, 'TEMPLATE') !== FALSE || stripos($colA, 'Petunjuk') !== FALSE || stripos($colA, 'Wajib') !== FALSE) {
                    continue;
                }
                // Abaikan baris contoh bawaan template
                if (stripos($colB, '(Contoh)') !== FALSE) {
                    continue;
                }

                $rowsToProcess[] = [
                    'row_num'             => $rowIdx + 1,
                    'parent_code'         => $colA,
                    'account_description' => $colB,
                    'category'            => $colC
                ];
            }

            if (empty($rowsToProcess)) {
                $this->session->set_flashdata('failed', 'Tidak ada data akun yang valid untuk diimport. Pastikan Kode Induk dan Nama Akun sudah terisi.');
                if (file_exists($fullPath)) unlink($fullPath);
                redirect('manage/account/import_account?m=' . $majors_id);
                return;
            }

            // Load existing accounts untuk unit ini + default accounts
            $defaultAccount = $this->Account_model->getDefaultAccount();
            $majorsAccount = $this->Account_model->get(['account_majors_id' => $majors_id]);
            $allAccounts = array_merge($defaultAccount, $majorsAccount);
            $sortedAccounts = $this->sortAccountsByParentChild($allAccounts);

            // Bangun peta memori untuk pencarian parent & penomoran cepat
            $accountMapByCode = [];
            $childrenByParentId = [];

            foreach ($sortedAccounts as $acc) {
                $c = trim($acc['account_code']);
                $accountMapByCode[$c] = [
                    'account_id'          => $acc['account_id'],
                    'account_code'        => $c,
                    'account_description' => $acc['account_description'],
                    'type'                => strtoupper($acc['type']),
                    'account_note'        => $acc['account_note'],
                    'account_category'    => $acc['account_category'],
                    'account_majors_id'   => $acc['account_majors_id']
                ];

                $pId = $acc['account_note'];
                if (!isset($childrenByParentId[$pId])) {
                    $childrenByParentId[$pId] = [];
                }
                $childrenByParentId[$pId][] = $c;
            }

            $majors = $this->db->get_where('majors', ['majors_id' => $majors_id])->row_array();
            $unitName = $majors ? $majors['majors_short_name'] : '';

            $this->db->trans_begin();

            $added = 0;
            $skipped = 0;

            foreach ($rowsToProcess as $item) {
                $parentCode  = $item['parent_code'];
                $accountDesc = $item['account_description'];
                $catInput    = $item['category'];

                // Normalisasi kategori
                if (stripos($catInput, 'bayar') !== FALSE || $catInput === '1') {
                    $categoryId = 1;
                } else {
                    $categoryId = 2; // Default Keuangan
                }

                // Cek keberadaan parent (baik yang ada dari awal, maupun baru dibuat di baris sebelumnya!)
                if (!isset($accountMapByCode[$parentCode])) {
                    $skipped++;
                    continue;
                }

                $parent = $accountMapByCode[$parentCode];
                $parentId = $parent['account_id'];
                $parentType = $parent['type'];

                if ($parentType === 'SUB4') {
                    $skipped++;
                    continue;
                }

                if (!isset($childrenByParentId[$parentId])) {
                    $childrenByParentId[$parentId] = [];
                }

                // Generate kode anak baru secara otomatis
                $newCode = $this->_generate_next_code($parentId, $parentCode, $parentType, $childrenByParentId[$parentId]);
                $childrenByParentId[$parentId][] = $newCode;

                // Format nama akun sesuai konvensi
                if (substr($newCode, 0, 3) == '5-5' && $categoryId != 0 && !empty($unitName)) {
                    $akun = 'Biaya ' . $accountDesc . ' ' . $unitName;
                } elseif (!empty($unitName)) {
                    $akun = $accountDesc . ' ' . $unitName;
                } else {
                    $akun = $accountDesc;
                }

                // Cek duplikasi
                $colWhere = [
                    'account_description' => $akun,
                    'account_note'        => $parentId,
                    'account_majors_id'   => $majors_id
                ];
                if ($this->General_model->checkDataExists('account', $colWhere)) {
                    $skipped++;
                    continue;
                }

                $insertData = [
                    'account_code'        => $newCode,
                    'account_description' => $akun,
                    'account_note'        => $parentId,
                    'account_category'    => $categoryId,
                    'account_majors_id'   => $majors_id,
                    'account_status'      => 1
                ];
                $this->db->insert('account', $insertData);
                $newAccountId = $this->db->insert_id();

                // Update parent status menjadi 0 (punya anak)
                $this->db->where('account_id', $parentId)->update('account', ['account_status' => 0]);

                // Integrasi POS & Payment jika Pembayaran
                if ($categoryId == 1) {
                    $piutangId = $this->akun_piutang($majors_id);
                    if ($piutangId) {
                        $this->db->insert('pos', [
                            'pos_name'           => $accountDesc,
                            'pos_description'    => $accountDesc,
                            'account_account_id' => $newAccountId,
                            'piutang_account_id' => $piutangId
                        ]);
                        $posId = $this->db->insert_id();

                        $period = $this->db->get_where('period', ['period_status' => '1'])->row_array();
                        $periodId = $period ? $period['period_id'] : 1;

                        $this->db->insert('payment', [
                            'payment_type'     => 'BULAN',
                            'payment_mode'     => 'TETAP',
                            'payment_is_batch' => '0',
                            'period_period_id' => $periodId,
                            'pos_pos_id'       => $posId
                        ]);
                    }
                }

                $this->Logs_model->add([
                    'log_date'   => date('Y-m-d H:i:s'),
                    'user_id'    => $this->session->userdata('uid'),
                    'log_module' => 'Akun Biaya',
                    'log_action' => "Import Akun Biaya $akun",
                    'log_info'   => "ID:$newAccountId;Code:$newCode;Title:$akun"
                ]);

                // Daftarkan akun baru ke peta memori agar baris berikutnya di Excel bisa langsung memakainya sebagai parent!
                $nextTypeMap = ['HEADER' => 'SUB1', 'SUB1' => 'SUB2', 'SUB2' => 'SUB3', 'SUB3' => 'SUB4'];
                $childType = isset($nextTypeMap[$parentType]) ? $nextTypeMap[$parentType] : 'SUB4';

                $accountMapByCode[$newCode] = [
                    'account_id'          => $newAccountId,
                    'account_code'        => $newCode,
                    'account_description' => $akun,
                    'type'                => $childType,
                    'account_note'        => $parentId,
                    'account_category'    => $categoryId,
                    'account_majors_id'   => $majors_id
                ];
                $childrenByParentId[$newAccountId] = [];

                $added++;
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failed', 'Terjadi kesalahan database saat mengimport akun.');
            } else {
                $this->db->trans_commit();
                bump_cache_version('account');

                $msg = "Import berhasil: <strong>$added</strong> akun berhasil ditambahkan.";
                if ($skipped > 0) {
                    $msg .= " ($skipped data dilewati karena sudah ada atau kode induk tidak ditemukan).";
                }
                $this->session->set_flashdata('success', $msg);
            }

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            redirect('manage/account/?m=' . $majors_id);
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Error saat membaca file: ' . $e->getMessage());
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            redirect('manage/account/import_account?m=' . $majors_id);
        }
    }

    public function add_multi_child()
    {
        if ($_POST) {
            $parentId        = $this->input->post('parent_id');
            $parentCode      = $this->input->post('parent_code');
            $parentLevel     = (int) $this->input->post('parent_level');
            $accountMajorsId = $this->input->post('account_majors_id');
            $descriptions    = $this->input->post('account_description');
            $categories      = $this->input->post('account_category');
            $customCodes     = $this->input->post('account_code');

            if (empty($parentId) || empty($descriptions) || !is_array($descriptions)) {
                $this->session->set_flashdata('failed', 'Tidak ada data sub akun yang dikirim.');
                redirect('manage/account/?m=' . $accountMajorsId);
                return;
            }

            // Validasi keberadaan parent
            $parent = $this->db->get_where('account', ['account_id' => $parentId])->row_array();
            if (!$parent) {
                $this->session->set_flashdata('failed', 'Akun induk tidak ditemukan.');
                redirect('manage/account/?m=' . $accountMajorsId);
                return;
            }

            $majors = $this->db->get_where('majors', ['majors_id' => $accountMajorsId])->row_array();
            $unitName = $majors ? $majors['majors_short_name'] : '';

            $this->db->trans_begin();

            $insertedCount = 0;
            $generatedCodes = [];

            // Ambil semua child yang sudah ada di bawah parent ini agar penomoran akurat
            $existingChildren = $this->db->get_where('account', ['account_note' => $parentId])->result_array();
            foreach ($existingChildren as $c) {
                $generatedCodes[] = $c['account_code'];
            }

            // Tentukan tipe parent untuk generator
            $parentTypeMap = [1 => 'HEADER', 2 => 'SUB1', 3 => 'SUB2', 4 => 'SUB3'];
            $parentType = isset($parentTypeMap[$parentLevel]) ? $parentTypeMap[$parentLevel] : 'SUB1';

            foreach ($descriptions as $idx => $desc) {
                $desc = trim($desc);
                if (empty($desc)) {
                    continue; // Skip baris kosong
                }

                $cat = isset($categories[$idx]) && !empty($categories[$idx]) ? $categories[$idx] : '2';
                $code = isset($customCodes[$idx]) ? trim($customCodes[$idx]) : '';

                // Jika kode kosong atau sudah dipakai di database/batch saat ini, generate otomatis
                if (empty($code) || in_array($code, $generatedCodes) || $this->db->get_where('account', ['account_code' => $code])->num_rows() > 0) {
                    $code = $this->_generate_next_code($parentId, $parentCode, $parentType, $generatedCodes);
                }

                $generatedCodes[] = $code;

                // Format nama akun sesuai konvensi unit
                if (substr($code, 0, 3) == '5-5' && $cat != 0 && !empty($unitName)) {
                    $akun = 'Biaya ' . $desc . ' ' . $unitName;
                } elseif (!empty($unitName)) {
                    $akun = $desc . ' ' . $unitName;
                } else {
                    $akun = $desc;
                }

                $insertData = [
                    'account_code'        => $code,
                    'account_description' => $akun,
                    'account_note'        => $parentId,
                    'account_category'    => $cat,
                    'account_majors_id'   => $accountMajorsId,
                    'account_status'      => 1
                ];

                $this->db->insert('account', $insertData);
                $newAccountId = $this->db->insert_id();

                // Jika kategori Pembayaran (1), buat POS & Payment
                if ($cat == '1') {
                    $piutangId = $this->akun_piutang($accountMajorsId);
                    if ($piutangId) {
                        $this->db->insert('pos', [
                            'pos_name'           => $desc,
                            'pos_description'    => $desc,
                            'account_account_id' => $newAccountId,
                            'piutang_account_id' => $piutangId
                        ]);
                        $posId = $this->db->insert_id();

                        $period = $this->db->get_where('period', ['period_status' => '1'])->row_array();
                        $periodId = $period ? $period['period_id'] : 1;

                        $this->db->insert('payment', [
                            'payment_type'     => 'BULAN',
                            'payment_mode'     => 'TETAP',
                            'payment_is_batch' => '0',
                            'period_period_id' => $periodId,
                            'pos_pos_id'       => $posId
                        ]);
                    }
                }

                $this->Logs_model->add([
                    'log_date'   => date('Y-m-d H:i:s'),
                    'user_id'    => $this->session->userdata('uid'),
                    'log_module' => 'Akun Biaya',
                    'log_action' => 'Tambah Multi Child',
                    'log_info'   => 'ID:' . $newAccountId . ';Code:' . $code . ';Title:' . $akun
                ]);

                $insertedCount++;
            }

            // Update parent status menjadi 0 (memiliki anak)
            $this->db->where('account_id', $parentId)->update('account', ['account_status' => 0]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failed', 'Gagal menyimpan data sub akun. Terjadi kesalahan database.');
            } else {
                $this->db->trans_commit();
                bump_cache_version('account');
                $this->session->set_flashdata('success', "Berhasil menambahkan <strong>$insertedCount</strong> sub akun baru di bawah {$parent['account_code']} ({$parent['account_description']}).");
            }

            redirect('manage/account/?m=' . $accountMajorsId);
        }
    }

    private function _generate_next_code($parentId, $parentCode, $parentType, &$generatedCodes)
    {
        $parts = explode('-', $parentCode, 2);
        $prefix = isset($parts[0]) ? $parts[0] : '';
        $parentSuffix = isset($parts[1]) ? $parts[1] : '';

        if ($parentType === 'SUB2' || $parentType === 'SUB3' || $parentType === 'SUB4') {
            $maxNum = 0;
            foreach ($generatedCodes as $code) {
                if (strpos($code, $parentCode . '.') !== 0) continue;
                $frag = explode('.', $code);
                $last = end($frag);
                $val = intval($last);
                if ($val > $maxNum) $maxNum = $val;
            }
            $next = $maxNum + 1;
            return $parentCode . '.' . sprintf('%02d', $next);
        }

        // HEADER (Level 1 -> Sub 1) or SUB1 (Level 2 -> Sub 2)
        $maxSuffix = 0;
        foreach ($generatedCodes as $code) {
            $p = explode('-', $code, 2);
            if (!isset($p[1])) continue;
            $val = intval($p[1]);
            if ($val > $maxSuffix) $maxSuffix = $val;
        }

        $step = ($parentType === 'HEADER') ? 100 : 1;
        $baseSuffix = intval($parentSuffix);
        $newSuffix = ($maxSuffix == 0) ? ($baseSuffix + $step) : ($maxSuffix + $step);

        return $prefix . '-' . $newSuffix;
    }

    private function sortAccountsByParentChild($accounts)
    {
        $accountsById = [];
        $accountsByParent = [];

        foreach ($accounts as $acct) {
            $accountsById[$acct['account_id']] = $acct;
            $parent = isset($acct['account_note']) ? $acct['account_note'] : 0;
            $accountsByParent[$parent][] = $acct['account_id'];
        }

        foreach ($accountsByParent as $p => &$children) {
            usort($children, function ($aId, $bId) use ($accountsById) {
                return strnatcmp($accountsById[$aId]['account_code'], $accountsById[$bId]['account_code']);
            });
        }
        unset($children);

        $sorted = [];
        $processed = [];

        $hasChildren = function ($accountId) use ($accountsByParent) {
            return !empty($accountsByParent[$accountId]);
        };

        $getAccountDepth = function ($accountId) use ($accountsById, &$getAccountDepth) {
            if (!isset($accountsById[$accountId])) return 0;
            $acct = $accountsById[$accountId];
            if (empty($acct['account_note']) || $acct['account_note'] == 0) return 0;
            return 1 + $getAccountDepth($acct['account_note']);
        };

        $processAccount = function ($accountId) use (&$sorted, &$processed, $accountsById, $accountsByParent, &$processAccount, $getAccountDepth, $hasChildren) {
            if (in_array($accountId, $processed)) return;
            if (!isset($accountsById[$accountId])) return;

            $acct = $accountsById[$accountId];
            $processed[] = $accountId;

            if (!empty($acct['account_note']) && $acct['account_note'] != 0) {
                $processAccount($acct['account_note']);
            }

            $depth = $getAccountDepth($accountId);
            $type = ($depth == 0) ? 'header' : 'sub' . $depth;
            $isParent = $hasChildren($accountId);
            $category = $isParent ? 'header' : 'detail';

            $acct['type'] = $type;
            $acct['has_child'] = $isParent;
            $acct['category'] = $category;

            $sorted[] = $acct;

            if (!empty($accountsByParent[$accountId])) {
                foreach ($accountsByParent[$accountId] as $childId) {
                    if (!in_array($childId, $processed)) {
                        $processAccount($childId);
                    }
                }
            }
        };

        $rootIds = isset($accountsByParent[0]) ? $accountsByParent[0] : [];
        usort($rootIds, function ($aId, $bId) use ($accountsById) {
            return strnatcmp($accountsById[$aId]['account_code'], $accountsById[$bId]['account_code']);
        });

        foreach ($rootIds as $rid) {
            $processAccount($rid);
        }

        $remaining = array_diff(array_keys($accountsById), $processed);
        usort($remaining, function ($aId, $bId) use ($accountsById) {
            return strnatcmp($accountsById[$aId]['account_code'], $accountsById[$bId]['account_code']);
        });
        foreach ($remaining as $id) {
            $processAccount($id);
        }

        return $sorted;
    }

    private function generateCoa($parentID, $parentCode, $parentType, &$generatedCodes)
    {
        // NOTE: handle HEADER, SUB1, SUB2 dan SUB3
        if (!in_array($parentType, ["HEADER", "SUB1", "SUB2", "SUB3"])) {
            return null;
        }

        // NOTE: format kode "X-YYYYY" or "X-YYYYY..."
        $parts = explode('-', $parentCode, 2);
        $prefix = isset($parts[0]) ? $parts[0] : '';
        $parentSuffix = isset($parts[1]) ? $parts[1] : '';

        // NOTE: ambil semua child langsung dari parent di database (child menyimpan parent account_id di kolom `account_note`)
        $this->db->where('account_note', $parentID);
        $query = $this->db->get('account');

        if (!isset($generatedCodes[$parentID])) {
            $generatedCodes[$parentID] = [];
        }

        if ($parentType === 'SUB2' || $parentType === 'SUB3') {
            // NOTE: format kode child (2 digit nomor urut AI. ex 01,02)
            $maxNum = 0;
            foreach ($query->result() as $row) {
                if (empty($row->account_code)) continue;
                $code = $row->account_code;
                // NOTE: tambahkan titik "." untuk child dari SUB2 dan SUB3.
                if (strpos($code, $parentCode . '.') !== 0) continue;
                $frag = explode('.', $code);
                $last = end($frag);
                $val = intval($last);
                if ($val > $maxNum) $maxNum = $val;
            }

            foreach ($generatedCodes[$parentID] as $code) {
                if (strpos($code, $parentCode . '.') !== 0) continue;
                $frag = explode('.', $code);
                $last = end($frag);
                $val = intval($last);
                if ($val > $maxNum) $maxNum = $val;
            }

            $next = $maxNum + 1;
            $suffix = sprintf('%02d', $next);
            $newCode = $parentCode . '.' . $suffix;
            $generatedCodes[$parentID][] = $newCode;
            return $newCode;
        }

        // NOTE: HEADER dan SUB1
        $maxSuffix = 0;
        foreach ($query->result() as $row) {
            if (empty($row->account_code)) continue;
            $p = explode('-', $row->account_code, 2);
            if (!isset($p[1])) continue;
            $val = intval($p[1]);
            if ($val > $maxSuffix) $maxSuffix = $val;
        }

        foreach ($generatedCodes[$parentID] as $code) {
            $p = explode('-', $code, 2);
            if (!isset($p[1])) continue;
            $val = intval($p[1]);
            if ($val > $maxSuffix) $maxSuffix = $val;
        }

        $step = ($parentType === 'HEADER') ? 100 : 1;
        $baseSuffix = intval($parentSuffix);
        if ($maxSuffix == 0) {
            $newSuffix = $baseSuffix + $step;
        } else {
            $newSuffix = $maxSuffix + $step;
        }

        $newCode = $prefix . '-' . $newSuffix;
        $generatedCodes[$parentID][] = $newCode;
        return $newCode;
    }

    public function assign($id)
    {
        if (!$id) {
            $this->session->set_flashdata("failed", "ID is required");
            redirect("manage/account");
        }

        $s = $this->input->get('m');
        $redirectUrl = site_url("manage/account");
        if (isset($s)) {
            $redirectUrl .= "?m=$s";
        }

        if (!$this->General_model->checkDataExists("account", ["account_id" => $id])) {
            $this->session->set_flashdata("failed", "Data tidak ditemukan.");
            redirect($redirectUrl);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        $this->form_validation->set_rules('piutang_account_id', 'Akun Piutang', 'required|xss_clean');
        $this->form_validation->set_rules('pos_name', 'Nama POS', 'required|xss_clean');
        $this->form_validation->set_rules('pos_description', 'Nama POS', 'required|xss_clean');
        $this->form_validation->set_rules('period_id', 'Tahun Ajaran', 'required|xss_clean');
        $this->form_validation->set_rules('payment_type', 'Tipe Pembayaran', 'required|xss_clean');

        if ($_POST && $this->form_validation->run() == FALSE) {
            $params['id'] = $id;
            $account = $this->Account_model->get($params);
            $data['account'] = $account;

            $majors_id = $account['account_majors_id'];

            if ($majors_id != '0') {
                $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' AND account_majors_id = '$majors_id'")->result();
            } else {
                $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2'")->result();
            }

            $data['period'] = $this->Period_model->get();
            $data['paket']  = $this->db->query("SELECT setting_value as val FROM setting WHERE setting_name = 'setting_package'")->row_array();
            $data['title'] = 'Assign Akun Biaya';
            $data['main'] = 'account/account_assign';
            $data['redirect_url'] = $redirectUrl;

            $this->load->view('manage/layout', $data);
            return;
        }

        if ($_POST && $this->form_validation->run() == TRUE) {
            $m = sanitizeStrict($this->input->post('m'));
            if (isset($m)) {
                $redirectUrl .= "?m=$m";
            }
            $piutang_account_id = sanitizeStrict($this->input->post('piutang_account_id'));
            $pos_name = sanitizeStrict($this->input->post('pos_name'));
            $pos_description = sanitizeStrict($this->input->post('pos_description'));
            $period_id = sanitizeStrict($this->input->post('period_id'));
            $payment_type = sanitizeStrict($this->input->post('payment_type'));
            $paymentModes = sanitizeStrict($this->input->post('paymentModes'));
            $paymentIsBatchs = sanitizeStrict($this->input->post('paymentIsBatchs'));

            $dataPos['pos_name'] = $pos_name;
            $dataPos['pos_description'] = $pos_description;
            $dataPos['account_account_id'] = $id;
            $dataPos['piutang_account_id'] = $piutang_account_id;

            $dataPayment['payment_type'] = $payment_type;
            if ($payment_type == 'BEBAS') {
                $dataPayment['payment_mode'] = 'TETAP';
                $dataPayment['payment_is_batch'] = '0';
            } else {
                $dataPayment['payment_mode'] = $paymentModes;
                $dataPayment['payment_is_batch'] = $paymentIsBatchs;
            }
            $dataPayment['period_id'] = $period_id;

            $this->db->trans_begin();
            $posId = $this->Pos_model->add($dataPos);
            if (!$posId) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failed', 'Gagal menyimpan POS');
                redirect($redirectUrl);
            }

            $dataPayment['pos_id'] = $posId;
            $paymentId = $this->Payment_model->add($dataPayment);
            if (!$paymentId) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failed', 'Gagal menyimpan Payment');
                redirect($redirectUrl);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('failed', 'Transaksi gagal, data dibatalkan');
                redirect($redirectUrl);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Data berhasil disimpan');
                redirect($redirectUrl);
            }
        } else {
            $params['id'] = $id;
            $account = $this->Account_model->get($params);
            $account['account_pos_name'] = $account['account_description'];
            $account['account_pos_description'] = 'POS Pembayaran ' . $account['account_description'];
            $data['account'] = $account;

            // $majors_id = $this->session->userdata('umajorsid');
            $majors_id = $account['account_majors_id'];

            if ($majors_id != '0') {
                $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2' AND account_majors_id = '$majors_id'")->result();
            } else {
                $data['piutang'] = $this->db->query("SELECT * FROM account WHERE account_code LIKE '1%' AND account_description LIKE '%Piutang%' AND account_category = '2'")->result();
            }
            $data['period'] = $this->Period_model->get();
            $data['paket']  = $this->db->query("SELECT setting_value as val FROM setting WHERE setting_name = 'setting_package'")->row_array();

            $data['title'] = 'Assign Akun Biaya';
            $data['main'] = 'account/account_assign';
            $data['redirect_url'] = $redirectUrl;
            $this->load->view('manage/layout', $data);
        }
    }
}
