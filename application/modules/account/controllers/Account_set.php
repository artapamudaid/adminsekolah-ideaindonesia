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
            header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('student/Student_model', 'account/Account_model', 'pos/Pos_model', 'period/Period_model', 'payment/Payment_model', 'logs/Logs_model'));
        $this->load->library('upload');
    }

    public function index($offset = NULL)
    {
        $this->load->library('pagination');

        $s = $this->input->get(NULL, TRUE);

        $data['s'] = $s;

        $params = array();

        if (isset($s['m']) && !empty($s['m']) && $s['m'] != 'all') {
            $params['account_majors_id'] = $s['m'];
        } else if (isset($s['m']) && !empty($s['m']) && $s['m'] == 'all') {
            //
        }

        $data['account'] = $this->Account_model->get($params);

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

                    $akun_piutang = $this->akun_piutang($accountMajorsId);

                    if (isset($akun_piutang)) {

                        $check_account = $this->db->get_where('pos', array('account_account_id', $account_id))->num_rows();

                        if ($check_account == 0) {
                            $insert_pos = $this->db->query("INSERT INTO pos(pos_name, pos_description,
                                                            account_account_id, piutang_account_id)
                                                            VALUES
                                                            ('$accountKet','$accountKet','$account_id','$akun_piutang')");

                            if ($insert_pos) {
                                $pos_id = $this->db->insert_id();

                                $period = $this->db->query("SELECT period_id as id FROM period
                                                            WHERE period_status = '1'")->row_array();

                                $payment = array(
                                    'payment_type' => 'BULAN',
                                    'payment_mode' => 'TETAP',
                                    'payment_is_batch' => '0',
                                    'period_period_id' => $period['id'],
                                    'pos_pos_id' => $pos_id
                                );

                                $this->db->insert('payment', $payment);
                            }
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
                                            AND account_description LIKE '%PIUTANG SANTRI%'
                                            AND account_majors_id ='$id'")->row_array();

        return $akun_piutang['account_id'];
    }

    public function delete($id = NULL)
    {
        if ($this->session->userdata('uroleid') != SUPERUSER) {
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

        if (empty($s) || $s == 'all') {
            $this->session->set_flashdata('failed', 'Majors ID is required');
            redirect('manage/account');
        }

        $redirectUrl = site_url("manage/account");
        if (isset($s)) {
            $redirectUrl .= "?m=$s";
        }

        $data['s'] = $s;

        $data['title'] = 'Import Akun Biaya';
        $data['main'] = 'account/account_import';
        $data['redirect_url'] = $redirectUrl;
        $this->load->view('manage/layout', $data);
    }

    public function download()
    {
        $s = $this->input->get('m');

        if (empty($s) || $s == 'all') {
            $this->session->set_flashdata('failed', 'Majors ID is required');
            redirect('manage/account/import_account?m=' . $s);
        }

        $majors_id = sanitizeStrict($s);
        $colWhere = ['majors_id' => $majors_id];
        if (!$this->General_model->checkDataExists('majors', $colWhere)) {
            $this->session->set_flashdata('failed', 'Majors ID not found');
            redirect('manage/account/import_account');
        }

        $majors   = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$majors_id'")->row_array();

        $fileName = 'Template-Import-Akun-Biaya';
        $fileName .= '-' . $majors['majors_short_name'];
        // $fileName .= '-'.date('Ymd-His');
        $heightColumnHeader = 25;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Indoweb")
            ->setLastModifiedBy("Indoweb")
            ->setTitle($fileName)
            ->setSubject($fileName)
            ->setDescription(
                $fileName . ", Dibuat dengan PhpSpreadsheet oleh Indoweb."
            )
            ->setKeywords("office 2007 openxml php codeigniter indoweb")
            ->setCategory("file Excel");

        // SECTION: Style Definitions
        $bold_center_black = [
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => Color::COLOR_BLACK
                ]
            ],
            'aligment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $style_center = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $style_center_v = [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $style_col = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => true
            ],
            'borders' => [
                'top' => ['borderStyle'  => Border::BORDER_THIN],
                'right' => ['borderStyle'  => Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => Border::BORDER_THIN],
                'left' => ['borderStyle'  => Border::BORDER_THIN]
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => Border::BORDER_THIN],
                'right' => ['borderStyle'  => Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => Border::BORDER_THIN],
                'left' => ['borderStyle'  => Border::BORDER_THIN]
            ]
        ];
        $bg_yellow = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00'
                ]
            ],
        ];
        $bg_green = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '00B050'
                ]
            ],
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => Color::COLOR_WHITE
                ]
            ],
        ];
        $bg_red = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FF0000'
                ]
            ],
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => Color::COLOR_WHITE
                ]
            ],
        ];
        $bg_white_dark = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D9D9D9'
                ]
            ],
        ];
        // !SECTION: end Style Definitions

        // SECTION: Sheet INPUT_DATA
        $startRow = 3;
        $endRow   = 53;

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('INPUT_DATA');
        $sheetHeader = [
            'REF_COA',
            'PARENT_ID',
            'PARENT_CODE',
            'PARENT_DESCRIPTION',
            'PARENT_TYPE',
            'CATEGORY_NAME',
            'CATEGORY_ID',
            'ACCOUNT_DESCRIPTION'
        ];
        $sheetSubHeader = [
            'Pilih data dari MASTER_COA',
            'Jangan diubah',
            'Jangan diubah',
            'Jangan diubah',
            'Jangan diubah',
            'Pilih data dari MASTER_CATEGORY',
            'Jangan diubah',
            'Masukkan deskripsi akun biaya'
        ];
        $colSheet = 'A';
        $subHeaderBgGreen = [0, 5, 7];
        $subHeaderBgRed = [1, 2, 3, 4, 6];

        foreach ($sheetHeader as $key => $value) {
            $sheet->setCellValue($colSheet . '1', $value);
            $sheet->getStyle($colSheet . '1')
                ->applyFromArray($style_col)
                ->applyFromArray($bg_yellow);
            $sheet->getColumnDimension($colSheet)->setAutoSize(true);

            if (in_array($key, $subHeaderBgGreen)) {
                $sheet->setCellValue($colSheet . '2', $sheetSubHeader[$key]);
                $sheet->getStyle($colSheet . '2')
                    ->applyFromArray($style_center)
                    ->applyFromArray($style_row)
                    ->applyFromArray($bg_green);
            }

            if (in_array($key, $subHeaderBgRed)) {
                $sheet->setCellValue($colSheet . '2', $sheetSubHeader[$key]);
                $sheet->getStyle($colSheet . '2')
                    ->applyFromArray($style_center)
                    ->applyFromArray($style_row)
                    ->applyFromArray($bg_red);
            }

            $colSheet++;
        }

        $sheet->getRowDimension(1)->setRowHeight($heightColumnHeader);
        $sheet->freezePane('B3');
        foreach (range('B', 'E') as $col) {
            $sheet->getColumnDimension($col)->setVisible(false);
        }
        $sheet->getColumnDimension('G')->setVisible(false);

        for ($i = $startRow; $i <= $endRow; $i++) {
            $sheet->getStyle("A{$i}:H{$i}")->applyFromArray($style_row);
        }
        // !SECTION: end Sheet INPUT_DATA

        // SECTION: Sheet MASTER_COA
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('MASTER_COA');

        $sheet2Header = [
            'ACCOUNT_ID',
            'ACCOUNT_CODE',
            'ACCOUNT_DESCRIPTION',
            'ACCOUNT_DETAIL',
            'ACCOUNT_TYPE'
        ];
        $colSheet2 = 'A';
        foreach ($sheet2Header as $key => $value) {
            $sheet2->setCellValue($colSheet2 . '1', $value);
            $sheet2->getStyle($colSheet2 . '1')
                ->applyFromArray($style_col)
                ->applyFromArray($bg_yellow);
            $sheet2->getColumnDimension($colSheet2)->setAutoSize(true);

            $colSheet2++;
        }

        $sheet2->getRowDimension(1)->setRowHeight($heightColumnHeader);
        $sheet2->freezePane('C2');

        $accounts = [];
        $defaultAccount = $this->Account_model->getDefaultAccount();
        $majorsAccount = $this->Account_model->get([
            'account_majors_id' => $majors_id,
        ]);
        $accounts = array_merge($defaultAccount, $majorsAccount);

        $accounts = $this->sortAccountsByParentChild($accounts);

        // NOTE: Bypass data dengan Sub Akun 4
        $accounts = array_filter($accounts, function ($account) {
            if ($account['type'] !== 'sub4') {
                return true;
            }
            return false;
        });

        $rowSheet2 = 2;
        $maxRowSheet2 = count($accounts) + 1;

        foreach ($accounts as $key => $value) {
            $sheet2->setCellValue('A' . $rowSheet2, $value['account_id']);
            $sheet2->setCellValue('B' . $rowSheet2, $value['account_code']);
            $sheet2->setCellValue('C' . $rowSheet2, $value['account_description']);
            $detailAccount = $value['account_code'] . ' - ' . $value['account_description'];
            $sheet2->setCellValue('D' . $rowSheet2, $detailAccount);
            $typeAccount = strtoupper($value['type']);
            $sheet2->setCellValue('E' . $rowSheet2, $typeAccount);

            $sheet2->getStyle('A' . $rowSheet2)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);
            $sheet2->getStyle('B' . $rowSheet2)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);
            $sheet2->getStyle('C' . $rowSheet2)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);
            $sheet2->getStyle('D' . $rowSheet2)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);
            $sheet2->getStyle('E' . $rowSheet2)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);

            if ($value['type'] == 'header') {
                $sheet2->getStyle('A' . $rowSheet2 . ':E' . $rowSheet2)
                    ->applyFromArray($bold_center_black)
                    ->applyFromArray($bg_white_dark);
            }

            if (strpos($value['type'], 'sub') === 0) {
                $level = (int) str_replace('sub', '', $value['type']);
                $indentation = str_repeat('    ', $level); // 4 spasi per level
                $sheet2->setCellValue('B' . $rowSheet2, $indentation . $value['account_code']);
                $sheet2->setCellValue('C' . $rowSheet2, $indentation . $value['account_description']);
                $sheet2->setCellValue('D' . $rowSheet2, $indentation . $detailAccount);

                // if ($value['category'] == 'header') {
                //     $sheet2->getStyle('B' . $rowSheet2 . ':D' . $rowSheet2)
                //         ->applyFromArray($bold_center_black);
                // }
            }

            $rowSheet2++;
        }
        // !SECTION: end Sheet MASTER_COA

        // SECTION: Sheet MASTER_CATEGORY
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('MASTER_CATEGORY');
        $sheet3Header = [
            'ID',
            'NAME',
        ];
        $colSheet3 = 'A';
        foreach ($sheet3Header as $key => $value) {
            $sheet3->setCellValue($colSheet3 . '1', $value);
            $sheet3->getStyle($colSheet3 . '1')
                ->applyFromArray($style_col)
                ->applyFromArray($bg_yellow);
            $sheet3->getColumnDimension($colSheet3)->setAutoSize(true);

            $colSheet3++;
        }
        $sheet3->getRowDimension(1)->setRowHeight($heightColumnHeader);

        $sheet3Data = [
            ['id' => 1, 'name' => 'PEMBAYARAN'],
            ['id' => 2, 'name' => 'KEUANGAN'],
        ];

        $rowSheet3 = 2;
        foreach ($sheet3Data as $key => $value) {
            $sheet3->setCellValue('A' . $rowSheet3, $value['id']);
            $sheet3->setCellValue('B' . $rowSheet3, $value['name']);

            $sheet3->getStyle('A' . $rowSheet3)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);
            $sheet3->getStyle('B' . $rowSheet3)
                ->applyFromArray($style_center_v)
                ->applyFromArray($style_row);

            $rowSheet3++;
        }
        $maxRowSheet3 = count($sheet3Data) + 1;
        // !SECTION: end Sheet MASTER_CATEGORY

        // SECTION: Input Rumus
        $coaRangeDetail = "'MASTER_COA'!\$D\$2:\$D\$$maxRowSheet2";
        $catName = "'MASTER_CATEGORY'!\$B\$2:\$B\$$maxRowSheet3";
        for ($row = $startRow; $row <= $endRow; $row++) {
            $dv1 = $sheet->getCell("A$row")->getDataValidation();
            $dv1->setType(DataValidation::TYPE_LIST);
            $dv1->setErrorStyle(DataValidation::STYLE_STOP);
            $dv1->setAllowBlank(false);
            $dv1->setShowInputMessage(true);
            $dv1->setShowErrorMessage(true);
            $dv1->setShowDropDown(true);

            $dv1->setErrorTitle("Input Tidak Valid");
            $dv1->setError("Silakan pilih data dari MASTER_COA");
            $dv1->setPromptTitle("Pilih REF_COA");
            $dv1->setPrompt("Pilih Account Code dari MASTER_COA");
            $dv1->setFormula1($coaRangeDetail);

            $dv2 = $sheet->getCell("F$row")->getDataValidation();
            $dv2->setType(DataValidation::TYPE_LIST);
            $dv2->setErrorStyle(DataValidation::STYLE_STOP);
            $dv2->setAllowBlank(false);
            $dv2->setShowInputMessage(true);
            $dv2->setShowErrorMessage(true);
            $dv2->setShowDropDown(true);

            $dv2->setErrorTitle("Input Tidak Valid");
            $dv2->setError("Silakan pilih data dari MASTER_CATEGORY");
            $dv2->setPromptTitle("Pilih CATEGORY_NAME");
            $dv2->setPrompt("Pilih Name dari MASTER_CATEGORY");
            $dv2->setFormula1($catName);

            // NOTE: INDEX+MATCH
            $sheet->setCellValue(
                "B$row",
                '=IFERROR(INDEX(' .
                    "'MASTER_COA'!\$A\$2:\$A\$" . $maxRowSheet2 . ',' .
                    'MATCH(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',0)),"")'
            );

            $sheet->setCellValue(
                "C$row",
                '=IFERROR(INDEX(' .
                    "'MASTER_COA'!\$B\$2:\$B\$" . $maxRowSheet2 . ',' .
                    'MATCH(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',0)),"")'
            );

            $sheet->setCellValue(
                "D$row",
                '=IFERROR(INDEX(' .
                    "'MASTER_COA'!\$C\$2:\$C\$" . $maxRowSheet2 . ',' .
                    'MATCH(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',0)),"")'
            );

            $sheet->setCellValue(
                "E$row",
                '=IFERROR(INDEX(' .
                    "'MASTER_COA'!\$E\$2:\$E\$" . $maxRowSheet2 . ',' .
                    'MATCH(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',0)),"")'
            );

            $sheet->setCellValue(
                "G$row",
                '=IFERROR(INDEX(' .
                    "'MASTER_CATEGORY'!\$A\$2:\$A\$" . $maxRowSheet3 . ',' .
                    'MATCH(F' . $row . ',' .
                    "'MASTER_CATEGORY'!\$B\$2:\$B\$" . $maxRowSheet3 . ',0)),"")'
            );

            // NOTE: XLOOKUP
            /*
            $sheet->setCellValue(
                "B$row",
                '=XLOOKUP(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',' .
                    "'MASTER_COA'!\$A\$2:\$A\$" . $maxRowSheet2 . ',"")'
            );

            $sheet->setCellValue(
                "C$row",
                '=XLOOKUP(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',' .
                    "'MASTER_COA'!\$B\$2:\$B\$" . $maxRowSheet2 . ',"")'
            );

            $sheet->setCellValue(
                "D$row",
                '=XLOOKUP(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',' .
                    "'MASTER_COA'!\$C\$2:\$C\$" . $maxRowSheet2 . ',"")'
            );

            $sheet->setCellValue(
                "E$row",
                '=XLOOKUP(A' . $row . ',' .
                    "'MASTER_COA'!\$D\$2:\$D\$" . $maxRowSheet2 . ',' .
                    "'MASTER_COA'!\$E\$2:\$E\$" . $maxRowSheet2 . ',"")'
            );

            $sheet->setCellValue(
                "G$row",
                '=XLOOKUP(F' . $row . ',' .
                    "'MASTER_CATEGORY'!\$B\$2:\$B\$" . $maxRowSheet3 . ',' .
                    "'MASTER_CATEGORY'!\$A\$2:\$A\$" . $maxRowSheet3 . ',"")'
            );
            */
        }
        // !SECTION: end Input Rumus

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function do_import()
    {

        $s = $this->input->get('m');

        if (empty($s) || $s == 'all') {
            $this->session->set_flashdata('failed', 'Majors ID is required');
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
        $fileName = $upload_data['file_name'];
        $fullPath = $upload_data['full_path'];

        try {
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($fullPath);
            $sheetData = $spreadsheet
                ->getSheetByName('INPUT_DATA')
                ->toArray();

            // SECTION: Filter data - ambil hanya baris yang punya isi (tidak kosong)
            $dataRows = [];

            for ($i = 2; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];

                // NOTE: Cek apakah baris ini kosong (semua cell kosong)
                $isEmpty = true;
                foreach ($row as $cell) {
                    if (!empty(trim((string) $cell))) {
                        $isEmpty = false;
                        break;
                    }
                }

                // NOTE: Jika baris tidak kosong, tambahkan ke dataRows
                if (!$isEmpty) {
                    $dataRows[] = $row;
                }
            }
            // !SECTION: end Filter data

            // SECTION: Process data
            if (count($dataRows) === 0) {
                $this->session->set_flashdata('failed', 'Tidak ada data untuk diimport');
                unlink($fullPath);
                redirect('manage/account/import_account?m=' . $majors_id);
            }

            $dataInsert = [];
            $generatedCodes = [];

            foreach ($dataRows as $key => $row) {
                $parentID = trim($row[1]);
                $parentCode = trim($row[2]);
                $parentType = trim($row[4]);
                $categoryID = trim($row[6]);
                $accountDesc = trim($row[7]);

                if (empty($parentID) || empty($categoryID) || empty($accountDesc)) {
                    $this->session->set_flashdata('failed', 'Data tidak lengkap pada baris ' . ($key + 1));
                    unlink($fullPath);
                    redirect('manage/account/import_account?m=' . $majors_id);
                }

                $newCode = $this->generateCoa($parentID, $parentCode, $parentType, $generatedCodes);

                $dataInsert[] = [
                    "parent_code" => $parentCode,
                    "parent_type" => $parentType,
                    "account_code" => $newCode,
                    "account_description" => $accountDesc,
                    "account_note" => $parentID,
                    "account_category" => $categoryID,
                    "account_majors_id" => $majors_id,
                ];

                if (!isset($generatedCodes[$parentID])) {
                    $generatedCodes[$parentID] = [];
                }
                $generatedCodes[$parentID][] = $newCode;
            }

            // !SECTION: end Process data
            $added = 0;
            $skipped = 0;

            $testParams = [];
            foreach ($dataInsert as $row) {
                $accountCode = $row['account_code'];
                $accountKet = $row['account_description'];
                $accountNote = $row['account_note'];
                $accountCat = $row['account_category'];
                $accountMajorsId = $row['account_majors_id'];

                $colWhere = [
                    "account_description" => $accountKet,
                    "account_note" => $accountNote,
                    "account_category" => $accountCat,
                    "account_majors_id" => $accountMajorsId,
                ];

                if ($this->General_model->checkDataExists('account', $colWhere)) {
                    $skipped++;
                    continue;
                }

                $majors   = $this->db->query("SELECT majors_short_name FROM majors WHERE majors_id = '$accountMajorsId'")->row_array();

                if (substr($accountCode, 0, 3) == '5-5' && $accountCat != 0) {
                    $akun = 'Biaya ' . $accountKet . ' ' . $majors['majors_short_name'];
                } else {
                    $akun = $accountKet . ' ' . $majors['majors_short_name'];
                }

                $params['account_code'] = $accountCode;
                $params['account_description'] = $akun;
                $params['account_note'] = $accountNote;
                $params['account_category'] = $accountCat;
                $params['account_majors_id'] = $accountMajorsId;

                $testParams[] = $params;

                $account_id = $this->Account_model->add($params);
                $this->db->query("UPDATE account SET account_status = 0 WHERE account_id = '$accountNote'");

                if ($accountCat == '1') {
                    $akun_piutang = $this->akun_piutang($accountMajorsId);

                    if (isset($akun_piutang)) {
                        $check_account = $this->db->get_where('pos', array('account_account_id', $account_id))->num_rows();

                        if ($check_account == 0) {
                            $insert_pos = $this->db->query("INSERT INTO pos(pos_name, pos_description, account_account_id, piutang_account_id) VALUES ('$accountKet','$accountKet','$account_id','$akun_piutang')");

                            if ($insert_pos) {
                                $pos_id = $this->db->insert_id();
                                $period = $this->db->query("SELECT period_id as id FROM period WHERE period_status = '1'")->row_array();
                                $payment = array(
                                    'payment_type' => 'BULAN',
                                    'payment_mode' => 'TETAP',
                                    'payment_is_batch' => '0',
                                    'period_period_id' => $period['id'],
                                    'pos_pos_id' => $pos_id
                                );
                                $this->db->insert('payment', $payment);
                            }
                        }
                    }
                }

                $this->Logs_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Akun Biaya',
                        'log_action' => "Import Akun Biaya $akun",
                        'log_info' => 'ID:null;Title:' . $params['account_code'] . " " . $akun
                    )
                );

                $added++;
            }

            $message = 'Import berhasil: ' . $added . ' data ditambahkan';
            if ($skipped > 0) {
                $message .= ', ' . $skipped . ' data dilewati.';
            }
            $this->session->set_flashdata('success', $message);

            unlink($fullPath);
            redirect('manage/account/?m=' . $majors_id);
        } catch (Exception $e) {
            $this->session->set_flashdata('failed', 'Error: ' . $e->getMessage());
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            redirect('manage/account/import_account?m=' . $majors_id);
        }
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
