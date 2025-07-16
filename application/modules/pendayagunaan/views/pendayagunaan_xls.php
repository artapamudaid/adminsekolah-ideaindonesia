<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Program_" . str_replace(' ', '_', ucwords($program['program_name'])) . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 250mm;'>
    <tr valign='top'>
        <td colspan="12" valign='middle'>
            <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value']; ?>
            </div>
            <span style='font-size: 10pt;'><?php echo $setting_address['setting_value']; ?>, Telp.
                <?php echo $setting_phone['setting_value']; ?></span>
        </td>
    </tr>
</table>
<br>
<table style='width: 250mm;'>
    <tr>
        <td colspan="6" style='width: 150mm; font-size: 10pt; text-align: center' valign='top' align='center'>
            <h3 style="font-weight: bold;"><?php echo 'Laporan Program ' . $program['program_name']; ?></h3>
        </td>
    </tr>
</table>
<br>
<br>
<table style='width: 250mm;'>
    <tr>
        <td colspan="1" style='width: 30mm; font-size: 10pt; text-align: left' valign='center'>Nama Program</td>
        <td colspan="1" style='width: 5mm; font-size: 10pt; text-align: left' valign='center'>:</td>
        <td colspan="4" style='width: 215mm; font-size: 10pt; text-align: left'><?= $program['program_name'] ?></td>
    </tr>
    <tr>
        <td colspan="1" style='width: 30mm; font-size: 10pt; text-align: left' valign='center'>Target</td>
        <td colspan="1" style='width: 5mm; font-size: 10pt; text-align: left' valign='center'>:</td>
        <td colspan="4" style='width: 215mm; font-size: 10pt; text-align: left'><?= 'Rp ' . number_format($program['program_target'], 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td colspan="1" style='width: 30mm; font-size: 10pt; text-align: left' valign='center'>Pencapain</td>
        <td colspan="1" style='width: 5mm; font-size: 10pt; text-align: left' valign='center'>:</td>
        <td colspan="4" style='width: 215mm; font-size: 10pt; text-align: left'><?= 'Rp ' . number_format($program['program_earn'], 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td colspan="1" style='width: 30mm; font-size: 10pt; text-align: left' valign='center'>Pendayagunaan</td>
        <td colspan="1" style='width: 5mm; font-size: 10pt; text-align: left' valign='center'>:</td>
        <td colspan="4" style='width: 215mm; font-size: 10pt; text-align: left'><?= 'Rp ' . number_format($program['program_realisasi'], 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td colspan="1" style='width: 30mm; font-size: 10pt; text-align: left' valign='center'>Sisa Dana</td>
        <td colspan="1" style='width: 5mm; font-size: 10pt; text-align: left' valign='center'>:</td>
        <td colspan="4" style='width: 215mm; font-size: 10pt; text-align: left'><?= 'Rp ' . number_format($program['program_earn'] - $program['program_realisasi'], 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td colspan="1" style='width: 30mm; font-size: 10pt; text-align: left' valign='center'>Aktif Sampai</td>
        <td colspan="1" style='width: 5mm; font-size: 10pt; text-align: left' valign='center'>:</td>
        <td colspan="4" style='width: 215mm; font-size: 10pt; text-align: left'><?= pretty_date($program['program_end'], 'd F Y', false) ?></td>
    </tr>
</table>
<br>
<br>
<h3>Data Donasi</h3>
<table cellpadding='0' cellspacing='0' style='width: 250mm;' border='1'>
    <tr>
        <th align='center' style='font-size: 10pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No.</th>
        <th align='center' style='font-size: 10pt; width: 55mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Tanggal</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nama</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Email</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No. HP/WA</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nominal</th>
    </tr>
    <?php
    $no = 1;
    $totalDonasi = 0;
    foreach ($donasi as $row) {
        $totalDonasi += $row['donasi_nominal'];
    ?>
        <tr valign='top'>
            <td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $no++ ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: left;'><?php echo pretty_date($row['donasi_datetime'], 'd F Y H:i:s', false) ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: left;'><?php echo $row['donasi_name'] ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: left;'><?php echo $row['donasi_email'] ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: left;'><?php echo str_replace('-', '', $row['donasi_hp']) ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($row['donasi_nominal'], "0", ",", ".") ?></td>
        </tr>
    <?php
    }
    ?>
    <tr>
        <th align='center' style='font-size: 10pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;' colspan="5">Total</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999; text-align:right;'>Rp <?php echo number_format($totalDonasi, "0", ",", ".") ?></th>
    </tr>
</table>
<br>
<br>
<h3>Data Pendayagunaan</h3>
<table cellpadding='0' cellspacing='0' style='width: 250mm;' border='1'>
    <tr>
        <th align='center' style='font-size: 10pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No.</th>
        <th align='center' style='font-size: 10pt; width: 55mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Tanggal</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Keterangan</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nominal</th>
    </tr>
    <?php
    $no = 1;
    $totalPendayagunaan = 0;
    foreach ($pendayagunaan as $row) {
        $totalPendayagunaan += $row['pendayagunaan_nominal'];
    ?>
        <tr valign='top'>
            <td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $no++ ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: left;'><?php echo pretty_date($row['pendayagunaan_date'], 'd F Y', false) ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: left;'><?php echo $row['pendayagunaan_note'] ?></td>
            <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($row['pendayagunaan_nominal'], "0", ",", ".") ?></td>
        </tr>
    <?php
    }
    ?>
    <tr>
        <th align='center' style='font-size: 10pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;' colspan="3">Total</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999; text-align:right;'>Rp <?php echo number_format($totalDonasi, "0", ",", ".") ?></th>
    </tr>
</table>
<br>
<br>
<table cellpadding='0' cellspacing='0' style='width: 250mm;' border='0'>
    <tr>
        <td colspan='3' align='right' style='width:150mm;font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
        <td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php echo $setting_city['setting_value']; ?>, <?php echo pretty_date(date('Y-m-d'), 'd F Y', false); ?></td>
    </tr>
    <tr>
        <td colspan='5' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
    </tr>
    <tr>
        <td colspan='5' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
    </tr>
    <tr>
        <td colspan='3' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
        <td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php
                                                                                                                                                echo ucfirst($setting_nama_bendahara['setting_value']) . '<p></p>NIP. ' . ucfirst($setting_nip_bendahara['setting_value']); ?></td>
    </tr>
</table>