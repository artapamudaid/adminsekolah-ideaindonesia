<html>

<head>
  <?php foreach ($siswa as $row): ?>
    <title>Cetak Surat Tagihan - <?php echo $row['student_full_name'] ?></title>
  <?php endforeach ?>
  <style type="text/css">
    .upper {
      text-transform: uppercase;
    }

    .lower {
      text-transform: lowercase;
    }

    .cap {
      text-transform: capitalize;
    }

    .small {
      font-variant: small-caps;
    }
  </style>
  <style type="text/css">
    @page {
      margin-top: 1cm;
      margin-bottom: 1cm;
      margin-left: 1cm;
      margin-right: 1cm;
    }

    .style12 {
      font-size: 10px
    }

    .style13 {
      font-size: 14pt;
      font-weight: bold;
    }

    .name-school {
      font-size: 15pt;
      font-weight: bold;
      padding-bottom: -15px;
    }

    .unit {
      font-size: 8pt;
      font-weight: bold;
      margin-bottom: -10px;
    }

    .alamat {
      font-size: 8pt;
      margin-bottom: -10px;
    }

    .title {
      font-size: 14pt;
      text-align: center;
      font-weight: bold;
      margin: 0 0 2px 0;
      line-height: 1.05;
    }

    .tp {
      font-size: 12pt;
      text-align: center;
      font-weight: bold;
      margin: 0 0 2px 0;
      line-height: 1.05;
    }

    .unit-siswa {
      font-size: 10pt;
      text-align: center;
      font-weight: bold;
      margin: 0;
      line-height: 1.05;
    }

    hr {
      border: none;
      height: 1px;
      /* Set the hr color */
      color: #333;
      /* old IE */
      background-color: #333;
      /* Modern Browsers */
    }

    body {
      font-family: sans-serif;
    }

    table {
      border-collapse: collapse;
      font-size: 9pt;
      width: 100%;
    }

    .table-bulanan td {
      padding: 3px;
    }

    .table-bebas td {
      padding: 3px;
    }
  </style>
</head>

<body>

  <p class="name-school"><?php echo $setting_school['setting_value'] ?></p>
  <p class="unit">
    <?php
    $num = count($unit);
    if ($num > 1) {
      foreach ($unit as $row) {
        $data  = $row['majors_short_name'];
        $Pecah = explode(" ", $data);
        for ($i = 0; $i < count($Pecah); $i++) {
          echo $Pecah[$i] . " ";
        }
      }
    } ?></p>
  <p class="alamat"><?php echo $setting_address['setting_value'] ?><br>
    <?php echo $setting_phone['setting_value'] ?></p>
  <hr>
  <p class="title">RINCIAN PEMBAYARAN ADMINISTRASI</p>
  <p class="tp"> TAHUN PELAJARAN <?php foreach ($period as $row): ?> <?php echo ($f['n'] == $row['period_id']) ? $row['period_start'] . '/' . $row['period_end'] : '' ?><?php endforeach; ?></p>
  <?php
  $num = count($unit);
  if ($num > 1) { ?>
    <p class="unit-siswa">
      Unit Sekolah :
      <?php foreach ($siswa as $row): ?>
        <?php echo $row['majors_short_name'] ?>
      <?php endforeach; ?>
    </p>
  <?php } ?>
  <br>
  <table class="table-siswa" style="font-size: 10pt;" width="100%" border="0">
    <tr>
      <td width="100">NIS</td>
      <td width="5">:</td>
      <?php foreach ($siswa as $row): ?>
        <td width=""><?php echo $row['student_nis'] ?></td>
      <?php endforeach; ?>
    </tr>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <?php foreach ($siswa as $row): ?>
        <td><?php echo $row['student_full_name'] ?></td>
      <?php endforeach; ?>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>:</td>
      <?php foreach ($siswa as $row): ?>
        <td><?php echo $row['class_name'] ?></td>
      <?php endforeach; ?>
    </tr>
  </table>
  <?php if ($f['n'] and $f['r'] != NULL) { ?>

    <h4>Pembayaran Bulanan</h4>
    <table width="100%" border="1" style="white-space: nowrap;" class="table-bulanan">
      <tr>
        <th style="height: 30px;">NO</th>
        <th>NAMA PEMBAYARAN</th>
        <th>TANGGAL</th>
        <th>NOMINAL</th>
        <th>KETERANGAN</th>
        <th>DIBAYAR VIA</th>
      </tr>
      <?php
      $i = 1;
      foreach ($bulan as $row) :
        $namePay = $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'];
        $mont = ($row['month_month_id'] <= 6) ? $row['period_start'] : $row['period_end'];

        $align_center = ($row['bulan_status'] == 1) ? 'center' : 'center';

        $style_tgl_bayar = '';
        $tgl_bayar_fmt = '';
        if ($row['bulan_status'] == 1) {
          $style_tgl_bayar = 'text-align: right;';
          $tgl_bayar_fmt = pretty_date($row['bulan_date_pay'], 'd F Y', false);
        } else {
          $style_tgl_bayar = 'text-align: center;';
          $tgl_bayar_fmt = '-';
        }

        $style_nominal = 'text-align: right;';

        $style_keterangan = '';
        $keterangan_fmt = '';
        if (isset($row['account_description']) && !empty($row['account_description'])) {
          $style_keterangan = 'text-align: left;';
          $keterangan_fmt = $row['account_description'];
        } else {
          $style_keterangan = 'text-align: center;';
          $keterangan_fmt = '-';
        }

      ?>
        <tr>
          <td style="text-align: center;"><?php echo $i ?></td>
          <td><?php echo $namePay . ' - (' . $row['month_name'] . ' ' . $mont . ')' ?></td>
          <td style="<?php echo $style_tgl_bayar ?>"><?php echo $tgl_bayar_fmt ?></td>
          <td style="<?php echo $style_nominal ?>"><?php echo 'Rp. ' . number_format($row['bulan_bill'], 0, ',', '.')  ?></td>
          <td><?php echo ($row['bulan_status'] == 1) ? 'Lunas' : 'Belum Lunas' ?></td>
          <td style="<?php echo $style_keterangan ?>">
            <?php echo $keterangan_fmt ?>
          </td>
        </tr>
      <?php
        $i++;
      endforeach
      ?>
    </table>

    <?php if (isset($bebas) && !empty($bebas)): ?>
      <h4>Pembayaran Bebas</h4>
      <table width="100%" border="1" style="white-space: nowrap;" class="table-bebas">
        <tr>
          <th style="height: 30px;">NO</th>
          <th>NAMA PEMBAYARAN</th>
          <th>TAGIHAN</th>
          <th>DISKON</th>
          <th>TOTAL TAGIHAN</th>
          <th>DIBAYAR</th>
          <th>SISA TAGIHAN</th>
        </tr>
        <?php
        $j = 1;
        foreach ($bebas as $row) :
          $namePayFree = $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'];
          $sisaTagihan = $row['bebas_bill'] - $row['bebas_diskon'] -  $row['bebas_total_pay'];
        ?>
          <tr>
            <td style="text-align: center;"><?php echo $j ?></td>
            <td><?php echo $namePayFree ?></td>
            <td style="text-align: right;"> <?php echo 'Rp. ' . number_format($row['bebas_bill'], 0, ',', '.') ?></td>
            <td style="text-align: right;"> <?php echo 'Rp. ' . number_format($row['bebas_diskon'], 0, ',', '.') ?></td>
            <td style="text-align: right;"> <?php echo 'Rp. ' . number_format($row['bebas_bill'] - $row['bebas_diskon'], 0, ',', '.') ?></td>
            <td style="text-align: right;"> <?php echo 'Rp. ' . number_format($row['bebas_total_pay'], 0, ',', '.') ?></td>
            <td style="text-align: right;"><?php echo 'Rp. ' . number_format($sisaTagihan, 0, ',', '.') ?></td>
          </tr>
          <?php if (isset($rincian_bebas[$row['pos_name']])) { ?>
            <tr>
              <td colspan="7"><b>Rincian</b></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="6">
                <table>
                  <thead>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Dibayar Via</th>
                    <th>Nominal</th>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($rincian_bebas[$row['pos_name']] as $item) {
                      echo '<tr>';
                      echo '<td>' . $no . '</td>';
                      echo '<td>' . $item['tanggal'] . '</td>';
                      echo '<td>' . $item['namaKas'] . '</td>';
                      echo '<td>Rp ' . number_format($item['penerimaan'], 0, ",", ".") . '</td>';
                      echo '</tr>';
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </td>
            </tr>
          <?php } ?>
        <?php
          $j++;
        endforeach
        ?>

      </table>
    <?php endif; ?>
  <?php } else redirect('manage/payout?n=' . $f['n'] . '&r=' . $f['r'])  ?>


  <table style="width:100%; margin-top: 50px; font-size: 10pt; ">
    <tr>
      <td>
      <td>
      <td><span class="cap"><?php echo $setting_city['setting_value'] ?></span>, <?php echo pretty_date(date('Y-m-d'), 'd F Y', false) ?></td>
    </tr>
    <tr>
      <td>
      <td>
      <td>Bendahara</td>
    </tr>

  </table>
  <br><br>
  <br><br>
  <table width="100%" style="font-size: 10pt;">
    <tr>
      <td>
      <td>
      <td><strong><span class="upper"> <?php echo ucfirst($setting_nama_bendahara['setting_value']); ?> </span></strong></td>
    </tr>
    <tr>
      <td>
      <td>
      <td><span class="upper">NIP. <?php echo ucfirst($setting_nip_bendahara['setting_value']); ?> </span></td>
    </tr>
  </table>


</body>

</html>