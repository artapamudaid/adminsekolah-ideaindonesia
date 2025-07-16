<html>

<head>
    <title>Data Tagihan</title>
</head>

<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 190mm;'>
        <tr valign='top'>
            <td style='width: 187mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
                </div>
                <span style='font-size: 10pt;'><?php echo $setting_address['setting_value'] ?>, Telp.
                    <?php echo $setting_phone['setting_value'] ?></span>
            </td>
        </tr>
    </table>
    <br>
    <table cellpadding='0' cellspacing='0' style='width: 190mm;'>
        <tr>
            <td style='width: 24mm; font-size: 10pt; text-align: left'>Unit Sekolah</td>
            <td style='width: 1mm; font-size: 10pt; text-align: center'>: </td>
            <td style='width: 165mm; font-size: 10pt; text-align: left'><?php echo $unit ?? ' Semua Unit' ?></td>
        </tr>
        <tr>
            <td style='width: 24mm; font-size: 10pt; text-align: left'>Periode</td>
            <td style='width: 1mm; font-size: 10pt; text-align: center'>: </td>
            <td style='width: 165mm; font-size: 10pt; text-align: left'><?php echo $period['period_start'] . '/' . $period['period_end'] ?></td>
        </tr>
    </table>
    <br>
    <table style='width: 190mm;'>
        <tr>
            <td style='width: 20mm; font-size: 10pt; text-align: center' valign='center'></td>
            <td style='width: 150mm; font-size: 10pt; text-align: center' valign='top' align='center'><b><?php echo 'Data Tagihan' ?></b></td>
            <td style='width: 20mm; font-size: 10pt;' align='right'></td>
        </tr>
    </table>
    <br>

    <table id="dtable" style="width: 100%;font-size: 10pt;">
        <thead>
            <tr>
                <th style="padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No</th>
                <th style="padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kelas</th>
                <th style="padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tagihan Pembayaran Bulanan</th>
                <th style="padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tagihan Pembayaran Bebas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (COUNT($tagihan) > 0) {
                $i = 1;
                $tBulan = 0;
                $tBebas = 0;
                foreach ($tagihan as $row):
                    $tagihanBebas = $row['total_tagihan_bebas'] - $row['total_bebas_terbayar'];
                    $tBulan += $row['total_tagihan_bulan'];
                    $tBebas += $tagihanBebas;
            ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i; ?></td>
                        <td><?php echo $row['class_name']; ?></td>
                        <td style="text-align: right;">Rp <?php echo number_format($row['total_tagihan_bulan'], 0, ",", "."); ?></td>
                        <td style="text-align: right;">Rp <?php echo number_format($tagihanBebas, 0, ",", "."); ?></td>
                    </tr>
                <?php
                    $i++;
                endforeach;
            } else {
                ?>
                <tr id="row">
                    <td colspan="8" align="center">Data Kosong</td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th style="padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"></th>
            <th style="padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Total</th>
            <th style="text-align: right;padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Rp <?php echo number_format($tBulan, 0, ",", "."); ?></th>
            <th style="text-align: right;padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Rp <?php echo number_format($tBebas, 0, ",", "."); ?></th>
        </tfoot>
    </table>


</body>

</html>