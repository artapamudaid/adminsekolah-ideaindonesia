
<html>
<head>
	<title><?php echo 'Laporan per Jenis Anggaran (Kas Tunai) per Tanggal '.pretty_date($date_start, 'd/m/Y', FALSE).' Sampai '.pretty_date($date_start, 'd/m/Y', FALSE) ?></title>
</head>
<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 475mm;'>
        <tr valign='top'>
            <td style='width: 260mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
                </div>
                <span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp. 
        <?php echo $setting_phone['setting_value'] ?></span>
            </td>
        </tr>
    </table>
    <br>
    <table style='width: 375mm;'>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 235mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Data SPPD' ?></b></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
        <tr>
            <td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'>Tahun Ajaran <?php echo $period['period_start'].'/'.$period['period_end'] ?></td>
            <td style='width: 20mm; font-size: 8pt;' align='right'></td>
        </tr>
    </table>
    <br>
    <table cellpadding="0" cellspacing="0" style="width: 300px;">
            <tr valign='top'>
                <th align="center" style="font-size: 10pt; width: 30px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No.</th> 
                <th align="center" style="font-size: 10pt; width: 200px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No SSPD</th> 
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tanggal</th>
                <th align="center" style="font-size: 10pt; width: 150px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Maksud</th> 
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Pemberi Perintah</th>
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">yang di perintah</th> 
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tujuan</th> 
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Berangkat</th>
                <th align="center" style="font-size: 10pt; width: 100px; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kembali</th>
            </tr>
            <?php 
                $no = 1;
                foreach($sppd as $row){
            ?>
            <tr>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $no++; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['no_sppd'] ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['tanggal_input']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['deskripsi']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['nama_perintah']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['nama_diperintah']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['tmp_tujuan']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['tgl_berangkat']; ?></td>
                <td style="padding: 1px; font-size: 9pt; text-align: center; solid #999999;"><?= $row['tgl_kembali']; ?></td>
            </tr>
            <?php } ?>
    </table>
</body>