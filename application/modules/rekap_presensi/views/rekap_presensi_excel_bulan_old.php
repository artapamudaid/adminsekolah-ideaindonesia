<?php
// header("Content-Type: application/vnd.ms-excel"); //IE and Opera    
// header("Content-Type: application/x-msexcel"); // Other browsers    
// header("Content-Disposition: attachment; filename=Rekap_Absensi_Periode_" . $tgl_awal . "_" . $tgl_akhir . "_download_at_" . date('YmdHis') . ".xls");
// header("Expires: 0");
// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>
<!DOCTYPE html>

<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>REKAPITULASI ABSENSI </title>
  <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
  <style>
    a {
      color: #000000;
      font-weight: bold;
      font-family: Verdana;
      font-size: 11px;
      text-decoration: none;
    }

    tr,
    td {
      font-family: Verdana;
      font-size: 11px;
      border-collapse: collapse;
    }

    .td {
      font-weight: bold;
      font-family: Verdana;
      font-size: 11px;
      text-decoration: none;
      border-collapse: collapse;
    }

    .table>tbody>tr.info>td,
    .table>tbody>tr.info>th,
    .table>tbody>tr>td.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr.info>td,
    .table>tfoot>tr.info>th,
    .table>tfoot>tr>td.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>thead>tr.info>th,
    .table>thead>tr>td.info,
    .table>thead>tr>th.info {
      background-color: #d9edf7;
    }

    .table-bordered>thead>tr>th,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td {
      border: 1px solid #f4f4f4;
    }

    .table-bordered>tbody>tr>td,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
      border: 1px solid #ddd;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
      padding: 8px;
      line-height: 1.42857143;
    }

    a:hover {
      color: red;
    }
  </style>
</head>

<body>

  <table border="0" cellpadding="10" cellpadding="5" width="100%" align="left">
    <tr>
      <td align="left" valign="top">
        <center>
          <font size="4"><strong>REKAPITULASI ABSENSI PERUNIT</strong></font><br />
          <br>
          TANGGAL : <?= $tgl_awal ?> s/d <?= $tgl_akhir ?>
        </center><br /><br />
        <table class="table table-bordered" border=1 style="border-collapse:collapse">

          <?php
          $jumlahHari = $interval->format('%a');
          $colspanTanggal = $jumlahHari * count($dt_arr);
          ?>

          <thead>
            <tr bgcolor="#d9edf7" height="30">
              <th rowspan="3">No</th>
              <th colspan="7">Data</th>
              <th colspan="3">Keterangan</th>
              <th rowspan="3">Score</th>
              <th colspan="<?= $colspanTanggal ?>">
                <?= $namaBulan . ' ' . $namaTahun ?>
              </th>
            </tr>

            <tr bgcolor="#d9edf7" height="30">
              <th rowspan="2">Nama Lengkap</th>
              <th rowspan="2">Tanggal Masuk</th>
              <th colspan="3">Usia Kerja</th>
              <th rowspan="2">Jabatan</th>
              <th rowspan="2">Unit Kerja</th>
              <th colspan="4">Rekap Kehadiran</th>

              <?php foreach ($daterange as $d): ?>
                <th colspan="<?= count($dt_arr) ?>"><?= $d->format('d') ?></th>
              <?php endforeach ?>
            </tr>

            <tr bgcolor="#d9edf7" height="30">
              <th>Tahun</th>
              <th>Bulan</th>
              <th>Hari</th>
              <th>Hadir</th>
              <th>Ijin</th>
              <th>Terlambat</th>

              <?php foreach ($daterange as $d): ?>
                <?php foreach ($dt_arr as $label): ?>
                  <th><?= $label ?></th>
                <?php endforeach ?>
              <?php endforeach ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;

            foreach ($rekap_absensi as $row):

              $idp = $row['employee_id'];

              // usia kerja
              $mulai = new DateTime($row['employee_start']);
              $today = new DateTime();
              $umur  = $today->diff($mulai);

              // rekap dari controller
              $hadir     = $counter[$idp]['hadir'] ?? 0;
              $ijin      = $counter[$idp]['ijin'] ?? 0;
              $terlambat = $counter[$idp]['terlambat'] ?? 0;

              $score = $hadir ? round((($hadir - $terlambat) / $hadir) * 100, 2) : 0;
            ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['employee_name'] ?></td>
                <td><?= $row['employee_start'] ?></td>

                <td><?= $umur->y ?></td>
                <td><?= $umur->m ?></td>
                <td><?= $umur->d ?></td>

                <td><?= $row['jabatan'] ?></td>
                <td><?= $row['nama_unit'] ?></td>

                <td><?= $hadir ?></td>
                <td><?= $ijin ?></td>
                <td><?= $terlambat ?></td>
                <td><?= $score ?>%</td>

                <?php foreach ($daterange as $d): ?>

                  <?php
                  $date = $d->format('Y-m-d');

                  $hari = $absensi[$idp][$date] ?? [];

                  $datang = $hari['DATANG'] ?? null;
                  $pulang = $hari['PULANG'] ?? null;
                  $lain   = $hari['IJIN'] ?? $hari['SAKIT'] ?? $hari['LAIN-LAIN'] ?? null;
                  ?>

                  <td><?= $datang['time'] ?? '-' ?></td>
                  <td><?= $datang['statushadir'] ?? '-' ?></td>

                  <td><?= $pulang['time'] ?? '-' ?></td>
                  <td><?= $pulang['statushadir'] ?? '-' ?></td>

                  <?php if ($lain): ?>
                    <td><?= $lain['jenis_absen'] ?></td>
                  <?php elseif (!$datang && !$pulang): ?>
                    <td>Alpha</td>
                  <?php else: ?>
                    <td>-</td>
                  <?php endif ?>

                <?php endforeach ?>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </td>
    </tr>
    <!-- END TABLE CENTER -->
  </table>

</body>

</html>