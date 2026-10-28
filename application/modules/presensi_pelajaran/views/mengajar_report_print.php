<?php
// header("Content-type: application/octet-stream");
// header("Content-Disposition: attachment; filename=Rekap Presensi Kelas " . $namaKelas['class_name'] . " Mata Pelajaran " . $namaPelajaran['lesson_name'] . " Bulan " . $namaBulan . " " . $namaTahun . ".xls");
// header("Pragma: no-cache");
// header("Expires: 0");
?>
<html>

<head>
    <title><?php echo 'Laporan Jurnal Mengajar per Tanggal ' . pretty_date($q['ds'], 'd-m-Y', FALSE) . ' Sampai ' . pretty_date($q['de'], 'd-m-Y', FALSE) ?></title>
</head>

<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 190mm;'>
        <tr valign='top'>
            <td style='width: 187mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
                </div>
                <span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp.
                    <?php echo $setting_phone['setting_value'] ?></span>
            </td>
        </tr>
    </table>
    <!-- <br>
    <table cellpadding='0' cellspacing='0' style='width: 190mm;'>
        <tr>
            <td style='width: 24mm; font-size: 8pt; text-align: left'>Unit Sekolah</td>
            <td style='width: 1mm; font-size: 8pt; text-align: center'>:</td>
            <td style='width: 165mm; font-size: 8pt; text-align: left'><?php echo $unit ?></td>
        </tr>
    </table> -->
    <br>
    <table style='width: 190mm;'>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Jurnal Mengajar' ?></b></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'><?php echo 'Tanggal ' . pretty_date($q['ds'], 'd/m/Y', FALSE) . ' Sampai ' . pretty_date($q['de'], 'd/m/Y', FALSE) ?></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'>Tahun Ajaran <?php echo $period['period_start'] . '/' . $period['period_end'] ?></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
    </table>
    <br>
    <table cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr valign='top'>
            <th rowspan='2' align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No.</th>
            <th rowspan="2" align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tanggal</th>
            <th rowspan="2" align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Jam Pelajaran</th>
            <th rowspan="2" align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Guru</th>
            <th rowspan="2" align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kelas</th>
            <th rowspan="2" align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Mapel</th>
            <th rowspan="2" align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Materi</th>
            <th colspan="5" align="center" style="font-size: 10pt;  padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kehadiran</th>
            <!-- <th rowspan="2" align="center" style="font-size: 10pt; width: 10px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Keterangan</th> -->
        </tr>
        <tr>
            <th align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">H</th>
            <th align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">S</th>
            <th align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">I</th>
            <th align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">A</th>
            <th align="center" style="font-size: 10pt; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">D</th>
        </tr>
        <?php
        $no = 1;
        $teaches = array();
        foreach ($teaching as $row) :
            $teaches[] = $row;
        endforeach;

        $presens = array();
        foreach ($presensi as $row) :
            $presens[] = $row;
        endforeach;

        foreach ($teaches as $teach) :
        ?>
            <tr valign='center' style="border:1px solid black;">
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= $no++; ?></td>
                <td style="padding: 1px; width:70px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= pretty_date($teach['teaching_date_create'], "d-m-Y", FALSE); ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= $teach['jam_pelajaran_start'] . " - " . $teach['jam_pelajaran_end'] ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= wordwrap($teach['employee_name'], 25, "<br>\n") ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= $teach['class_name']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= wordwrap($teach['lesson_name'], 30, "<br>\n") ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;"><?= wordwrap($teach['teaching_materi'], 30, "<br>\n") ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;">
                    <?php
                    $hadir = 0;
                    foreach ($presens as $presen) :
                        if ($presen['c_pre'] == 'H' and $presen['les_id'] == $teach['lesson_id'] and $presen['teach_id'] == $teach['teaching_id']) {
                            $hadir += 1;
                        }
                    endforeach;
                    echo $hadir;
                    ?>
                </td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;">
                    <?php
                    $sakit = 0;
                    foreach ($presens as $presen) :
                        if ($presen['c_pre'] == 'S' and $presen['les_id'] == $teach['lesson_id'] and $presen['teach_id'] == $teach['teaching_id']) {
                            $sakit += 1;
                        }
                    endforeach;
                    echo $sakit;
                    ?>
                </td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;">
                    <?php
                    $ijin = 0;
                    foreach ($presens as $presen) :
                        if ($presen['c_pre'] == 'I' and $presen['les_id'] == $teach['lesson_id'] and $presen['teach_id'] == $teach['teaching_id']) {
                            $ijin += 1;
                        }
                    endforeach;
                    echo $ijin;
                    ?>
                </td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;">
                    <?php
                    $alpha = 0;
                    foreach ($presens as $presen) :
                        if ($presen['c_pre'] == 'A' and $presen['les_id'] == $teach['lesson_id'] and $presen['teach_id'] == $teach['teaching_id']) {
                            $alpha += 1;
                        }
                    endforeach;
                    echo $alpha;
                    ?>
                </td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; border: 0.5px solid #999999;">
                    <?php
                    $dispen = 0;
                    foreach ($presens as $presen) :
                        if ($presen['c_pre'] == 'D' and $presen['les_id'] == $teach['lesson_id'] and $presen['teach_id'] == $teach['teaching_id']) {
                            $dispen += 1;
                        }
                    endforeach;
                    echo $dispen;
                    ?>
                </td>

            </tr>
        <?php endforeach ?>
    </table>
</body>