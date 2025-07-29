<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <title><?php echo $student['student_full_name'] ?></title>

  <style type="text/css">
    body {
      font-family: sans-serif;
    }

    @page {
      margin-top: 0.5cm;
      margin-bottom: 0.5cm;
      margin-left: 0.5cm;
      margin-right: 0.5cm;
    }

    .school {
      font-size: 7pt;
      font-weight: bold;
      text-align: left;
      padding-bottom: -5px;
      /*width: 50%;*/
    }

    .address {
      font-size: 4pt;
      text-align: left;
      padding-bottom: 1px;
      /*width: 50%;*/
    }

    .phone {
      font-size: 5pt;
      text-align: center;
      font-style: italic;
      padding-bottom: -10px;
      /*width: 50%;*/
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

    br {
      margin: 10px;
    }

    .container {
      position: relative;
    }

    .topright {
      margin-left: 10px;
      position: absolute;
    }

    .name {
      margin-left: 80px;
      font-size: 6pt;
    }

    .fieldset-auto-width {
      display: inline-block;
      width: 70%;
    }

    .depan {
      background-image: url('media/background/kartu-depan-mini.jpg');
    }

    .depan_source {
      background-image: url(<?php echo upload_url('kartu/' . $setting_kartu_depan['setting_value']) ?>);
      height: 200px;
    }

    .belakang {
      background-image: url('media/background/kartu-belakang-mini.jpg');
    }
  </style>
</head>

<body>
  <fieldset class="fieldset-auto-width">
    <?php
    if ($setting_kartu_depan['setting_value'] != NULL) {
    ?>
      <div class="depan_source">
        <table>
          <tr>
            <td width="15%" valign="top">
              <br><br>
            </td>
            <td valign="top">
              <br><br>
            </td>
          </tr>
        </table>
        <div class="container">
          <div class="topright">
            <?php if (!empty($student['student_img'])) { ?>
              <img src="<?php echo upload_url('student/' . $student['student_img']) ?>" style="height: 100px; width: 70px; border:0px solid">
            <?php } else { ?>
              <img src="<?php echo media_url('img/missing.png') ?>" style="height: 100px; width: 70px; border:0px solid">
            <?php } ?>
          </div>

          <table class="name">
            <tr>
              <td widht='30%'>NAMA</td>
              <td widht='5%'>:</td>
              <td widht='65%'><?php echo strtoupper($student['student_full_name']) ?></td>
            </tr>
            <tr>
              <td>NIS</td>
              <td>:</td>
              <td><?php echo $student['student_nis'] ?></td>
            </tr>
            <tr>
              <td>TTL</td>
              <td>:</td>
              <td><?php echo strtoupper($student['student_born_place']) . ', ' . pretty_date($student['student_born_date'], 'd m Y', false) ?></td>
            </tr>
            <tr>
              <td>ALAMAT</td>
              <td>:</td>
              <td><?php echo wordwrap($res['pos_name'] . ' - T.A ' . strtoupper($student['student_address']), 35, "<br>\n") ?></td>
            </tr>
            <tr>
              <td colspan="3">
                <br>
                <img style="width:45pt;height:25pt;z-index:6;" src="<?php echo 'media/barcode_student/' . $student['student_nis'] . '.png' ?>" alt="Image_4_0" />
                <img style="width:35pt;height:auto;margin-left:5px;" src="<?php echo 'media/qrcode/' . $student['student_nis'] . '.png' ?>" alt="Image_4_0" />
              </td>
            </tr>
          </table>
          <br>
        </div>
      <?php
    } else {
      ?>
        <div class="depan">
          <table>
            <tr>
              <td width="15%" valign="top">
                <?php
                $arrContextOptions = array(
                  "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                  ),
                );

                $path = upload_url('school/' . logo());
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path, false, stream_context_create($arrContextOptions));
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>

                <img src="<?php echo $base64 ?>" style="height: 50px;">
              </td>
              <td valign="top">
                <span class="school">KARTU TANDA SISWA</span>
                <br><span class="school"><?php echo strtoupper($setting_school['setting_value']) ?></span>
                <p class="address"><?php echo strtoupper($setting_address['setting_value']) ?></p>
              </td>
            </tr>
          </table>
          <div class="container">
            <div class="topright">
              <?php if (!empty($student['student_img'])) { ?>
                <img src="<?php echo upload_url('student/' . $student['student_img']) ?>" style="height: 70px; width: 70px; border:0px solid">
              <?php } else { ?>
                <img src="<?php echo media_url('img/missing.png') ?>" style="height: 100px; width: 70px; border:0px solid">
              <?php } ?>
            </div>

            <table class="name">
              <tr>
                <td widht='30%'>NAMA</td>
                <td widht='5%'>:</td>
                <td widht='65%'><?php echo strtoupper($student['student_full_name']) ?></td>
              </tr>
              <tr>
                <td>NIS</td>
                <td>:</td>
                <td><?php echo $student['student_nis'] ?></td>
              </tr>
              <tr>
                <td>TTL</td>
                <td>:</td>
                <td><?php echo strtoupper($student['student_born_place']) . ', ' . pretty_date($student['student_born_date'], 'd m Y', false) ?></td>
              </tr>
              <tr>
                <td>ALAMAT</td>
                <td>:</td>
                <td><?php echo strtoupper($student['student_address']) ?></td>
              </tr>
              <tr>
                <td colspan="3">
                  <br>
                  <?php
                  $arrContextOptions = array(
                    "ssl" => array(
                      "verify_peer" => false,
                      "verify_peer_name" => false,
                    ),
                  );

                  $path = media_url('barcode_student/' . $barcode_nis . '.png');
                  $type = pathinfo($path, PATHINFO_EXTENSION);
                  $data = file_get_contents($path, false, stream_context_create($arrContextOptions));
                  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                  ?>

                  <img style="width:65pt;height:25pt;z-index:6;" src="<?php echo $base64 ?>" alt="Image_4_0" />
                </td>
              </tr>
            </table>
            <br>
          </div>
        </div>
      <?php
    }
      ?>
  </fieldset>
  <br>
  <fieldset class="fieldset-auto-width">
    <?php
    if ($setting_kartu_belakang['setting_value'] != NULL) {
    ?>
      <div>
        <img src="<?php echo upload_url('kartu/' . $setting_kartu_belakang['setting_value']) ?>" style="width: 302.52px; height:204.01px; border:0px solid">
      </div>
    <?php
    } else {
    ?>
      <div class="belakang">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
      </div>
    <?php
    }
    ?>

  </fieldset>

</body>

</html>