<html>

<head>
  <title>Cetak Buku Pembayaran Hutang - <?php echo $kreditur_name ?></title>
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
      padding-bottom: -10px;
    }

    .tp {
      font-size: 12pt;
      text-align: center;
      font-weight: bold;
    }

    .unit-siswa {
      font-size: 10pt;
      text-align: center;
      font-weight: bold;
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
  <p class="title">RINCIAN PEMBAYARAN HUTANG</p>
  <table style="font-size: 10pt;" width="100%" border="0">
    <tr>
      <td>Hutang No. Ref</td>
      <td width="5">:</td>
      <td><?php echo $hutang_noref ?></td>
      <td>Total Hutang</td>
      <td width="5">:</td>
      <td><?php echo 'Rp ' .  number_format($total_hutang, '0', ',', '.')  ?></td>
    </tr>
    <tr>
      <td>Tanggal Hutang</td>
      <td>:</td>
      <td><?php echo $hutang_date ?></td>
      <td>Hutang Terbayar</td>
      <td>:</td>
      <td><?php echo 'Rp ' .  number_format($total_terbayar, '0', ',', '.')  ?></td>
    </tr>
    <tr>
      <td>Kreditur</td>
      <td>:</td>
      <td><?php echo $kreditur_name ?></td>
      <td>Sisa Hutang</td>
      <td>:</td>
      <td><?php echo 'Rp ' .  number_format($total_hutang - $total_terbayar, '0', ',', '.')  ?></td>
    </tr>
  </table>
  <br>

  <table width="100%" border="1" style="white-space: nowrap;">
    <tr>
      <th>TANGGAL</th>
      <th>NOREF</th>
      <th>CATATAN</th>
      <th>NOMINAL</th>
    </tr>
    <?php
    foreach ($hutang_pay as $row) :
    ?>
      <tr>
        <td><?php echo pretty_date($row['hutang_pay_date'], 'd/m/Y', false) ?></td>
        <td><?php echo $row['hutang_pay_noref'] ?></td>
        <td><?php echo $row['hutang_pay_note'] ?></td>
        <td style="text-align: right;"><?php echo number_format($row['hutang_pay_bill'], '0', ',', '.') ?></td>
      </tr>
    <?php
    endforeach
    ?>
  </table>


</body>

</html>