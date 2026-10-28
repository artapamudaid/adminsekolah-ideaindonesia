<?php

// Include PhpSpreadsheet library
require 'vendor/autoload.php'; // Adjust the path according to your project structure

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


while (ob_get_level()) {
  ob_end_clean();
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Rekap Absensi Unit');

// Jumlah Kolom Statis
$jKolomStatis = 12;
// Hitung jumlah hari
$jumlahHari = (int)$interval->format('%a');

// ================== HEADER ==================
$startSekolahIndex = 1; // Kolom D = 4
$endSekolahIndex   = $startSekolahIndex + ($jumlahHari * 5) - 1 + $jKolomStatis;
$endSekolahCol = Coordinate::stringFromColumnIndex($endSekolahIndex);

$sheet->mergeCells('A1:' . $endSekolahCol . '1');
$sheet->setCellValue('A1', 'REKAPITULASI ABSENSI PERUNIT');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('A2:' . $endSekolahCol . '2');
$sheet->setCellValue('A2', 'TANGGAL ' . $tgl_awal . '  s/d  ' . $tgl_akhir);
$sheet->getStyle('A2')->getFont()->setSize(8);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


$rowHeader1 = 4;
$rowHeader2 = 5;
$rowHeader3 = 6;

$headerRange = "A$rowHeader1:$endSekolahCol$rowHeader3";

$sheet->getStyle($headerRange)->applyFromArray([
  'font' => [
    'bold' => true,
    'color' => [
      'rgb' => 'FFFFFF', // putih
    ],
  ],
  'fill' => [
    'fillType' => Fill::FILL_SOLID,
    'startColor' => [
      'rgb' => '3C8DBC',
    ],
  ],
  'alignment' => [
    'horizontal' => Alignment::HORIZONTAL_CENTER,
    'vertical'   => Alignment::VERTICAL_CENTER,
  ],
]);
$sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$sheet->mergeCells("A$rowHeader1:A$rowHeader3");
$sheet->setCellValue("A$rowHeader1", 'No');

$sheet->mergeCells("B$rowHeader1:H$rowHeader1");
$sheet->setCellValue("B$rowHeader1", 'Data');

$sheet->mergeCells("I$rowHeader1:K$rowHeader1");
$sheet->setCellValue("I$rowHeader1", 'Keterangan');

$sheet->mergeCells("L$rowHeader1:L$rowHeader3");
$sheet->setCellValue("L$rowHeader1", 'Score');

$sheet->mergeCells("B$rowHeader2:B$rowHeader3");
$sheet->setCellValue("B$rowHeader2", 'Nama Lengkap');

$sheet->mergeCells("C$rowHeader2:C$rowHeader3");
$sheet->setCellValue("C$rowHeader2", 'Tanggal Masuk');

$sheet->mergeCells("D$rowHeader2:F$rowHeader2");
$sheet->setCellValue("D$rowHeader2", 'Usia Kerja');

$sheet->mergeCells("G$rowHeader2:G$rowHeader3");
$sheet->setCellValue("G$rowHeader2", 'Jabatan');

$sheet->mergeCells("H$rowHeader2:H$rowHeader3");
$sheet->setCellValue("H$rowHeader2", 'Unit Kerja');

$sheet->setCellValue("D$rowHeader3", 'Tahun');
$sheet->setCellValue("E$rowHeader3", 'Bulan');
$sheet->setCellValue("F$rowHeader3", 'Hari');

$sheet->mergeCells("I$rowHeader2:K$rowHeader2");
$sheet->setCellValue("I$rowHeader2", 'Rekap Kehadiran');

$sheet->setCellValue("I$rowHeader3", 'Hadir');
$sheet->setCellValue("J$rowHeader3", 'Ijin');
$sheet->setCellValue("K$rowHeader3", 'Terlambat');


// ===== Lebar kolom utama =====
$sheet->getColumnDimension('A')->setWidth(6);   // No
$sheet->getColumnDimension('B')->setWidth(35);  // Nama
$sheet->getColumnDimension('C')->setWidth(14);  // Tanggal Masuk
$sheet->getColumnDimension('D')->setWidth(10);  // Tahun
$sheet->getColumnDimension('E')->setWidth(10);  // Bulan
$sheet->getColumnDimension('F')->setWidth(10);  // Hari
$sheet->getColumnDimension('G')->setWidth(25);  // Jabatan
$sheet->getColumnDimension('H')->setWidth(20);  // Unit
$sheet->getColumnDimension('I')->setWidth(10);  // Hadir
$sheet->getColumnDimension('J')->setWidth(10);  // Ijin
$sheet->getColumnDimension('K')->setWidth(12);  // Terlambat
$sheet->getColumnDimension('L')->setWidth(10);  // Score

// Merge judul bulan
$startCol = 'M';

$startColumnIndex = 13; // Kolom M = 13
$jumlahLabel = count($dt_arr);

$endColumnIndex   = $startColumnIndex + ($jumlahHari * $jumlahLabel) - 1;

$endCol = Coordinate::stringFromColumnIndex($endColumnIndex);

$sheet->mergeCells("$startCol$rowHeader1:$endCol$rowHeader1");
$sheet->setCellValue(
  "$startCol$rowHeader1",
  $namaBulan . ' ' . $namaTahun
);
// $sheet->getStyle("$startCol$rowHeader1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$lastColIndex = Coordinate::columnIndexFromString($endCol);

for ($i = 13; $i <= $lastColIndex; $i++) { // 13 = kolom M
  $col = Coordinate::stringFromColumnIndex($i);
  $sheet->getColumnDimension($col)->setWidth(12);
}


$col = 'M';
foreach ($daterange as $dr) {
  $endColMerge = $col;
  // i < 4 karena 0 samapai 4 itu 5 kolom
  $kolomLabel = 0;
  for ($i = $kolomLabel; $i < 4; $i++) {
    $sheet->setCellValue($endColMerge . $rowHeader3, $dt_arr[$kolomLabel]);
    $endColMerge++;
    $kolomLabel++;
  }
  $sheet->setCellValue($endColMerge . $rowHeader3, $dt_arr[$kolomLabel]);

  $sheet->mergeCells("$col$rowHeader2:$endColMerge$rowHeader2");
  $sheet->setCellValue($col . $rowHeader2, $dr->format('d'));
  // $sheet->getStyle($col . $rowHeader2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

  $col = $endColMerge;
  $col++;
}

$rowDataStart = 7;
$no = 1;
foreach ($rekap_absensi as $row) {
  $idp = $row['employee_id'];

  // usia kerja
  $mulai = new DateTime($row['employee_start']);
  $today = new DateTime();
  $umur  = $today->diff($mulai);

  $tahunKerja = $umur->y ? $umur->y . ' Tahun' : '-';
  $bulanKerja = $umur->m ? $umur->m . ' Bulan' : '-';
  $hariKerja = $umur->d ? $umur->d . ' Hari' : '-';

  // rekap dari controller
  $hadir     = $counter[$idp]['hadir'] ?? 0;
  $ijin      = $counter[$idp]['ijin'] ?? 0;
  $terlambat = $counter[$idp]['terlambat'] ?? 0;

  $score = $hadir ? round((($hadir - $terlambat) / $hadir) * 100, 2) : 0;
  $scoreHadir = $score > 0 ? $score . ' %' : '-';

  $sheet->setCellValue("A$rowDataStart", $no++);
  $sheet->setCellValue("B$rowDataStart", $row['employee_name']);
  $sheet->setCellValue("C$rowDataStart", $row['employee_start']);
  $sheet->setCellValue("D$rowDataStart", $tahunKerja);
  $sheet->setCellValue("E$rowDataStart", $bulanKerja);
  $sheet->setCellValue("F$rowDataStart", $hariKerja);
  $sheet->setCellValue("G$rowDataStart", $row['jabatan']);
  $sheet->setCellValue("H$rowDataStart", $row['nama_unit']);
  $sheet->setCellValue("I$rowDataStart", $hadir);
  $sheet->setCellValue("J$rowDataStart", $ijin);
  $sheet->setCellValue("K$rowDataStart", $terlambat);
  $sheet->setCellValue("L$rowDataStart", $scoreHadir);

  $colStatusStart = 'M';
  foreach ($daterange as $d) {
    $date = $d->format('Y-m-d');

    $hari = $absensi[$idp][$date] ?? [];

    $datang = $hari['DATANG'] ?? null;
    $pulang = $hari['PULANG'] ?? null;
    $lain   = $hari['IJIN'] ?? $hari['SAKIT'] ?? $hari['LAIN-LAIN'] ?? null;


    $sheet->setCellValue($colStatusStart . $rowDataStart, $datang['time'] ?? '-');
    $colStatusStart++;
    $sheet->setCellValue($colStatusStart . $rowDataStart, $datang['statushadir'] ?? '-');
    $colStatusStart++;
    $sheet->setCellValue($colStatusStart . $rowDataStart, $pulang['time'] ?? '-');
    $colStatusStart++;
    $sheet->setCellValue($colStatusStart . $rowDataStart, $pulang['statushadir'] ?? '-');
    $colStatusStart++;

    $statusLain = '';
    if ($lain) {
      $statusLain = $lain['jenis_absen'];
    } elseif (!$datang && !$pulang) {
      $statusLain = 'Alpha';
    } else {
      $statusLain = '-';
    }
    $sheet->setCellValue($colStatusStart . $rowDataStart, $statusLain);
    $colStatusStart++;
  }

  $rowDataStart++;
}

// Header download
$filename = "Rekap_Absensi_Periode_" . $tgl_awal . "_" . $tgl_akhir . "_download_at_" . date('YmdHis') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // cache lama
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
