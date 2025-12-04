<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename = Tagihan Per Kelas Bulan " . $bulanAwal . ' Sampai ' . $bulanAkhir . " " . $periode . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>

<head>
    <title><?php echo 'Tagihan Per Kelas Bulan ' . $bulanAwal . ' Sampai ' . $bulanAkhir ?></title>
</head>

<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 190mm;'>
        <tr valign='top'>
            <td style='width: 187mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school ?>
                </div>
                <span style='font-size: 8pt;'><?php echo $setting_address ?>, Telp.
                    <?php echo $setting_phone ?></span>
            </td>
        </tr>
    </table>
    <br>
    <table cellpadding='0' cellspacing='0' style='width: 190mm;'>
        <tr>
            <td style='width: 24mm; font-size: 8pt; text-align: left'>Unit Sekolah</td>
            <td style='width: 1mm; font-size: 8pt; text-align: center'>:</td>
            <td style='width: 165mm; font-size: 8pt; text-align: left'> <?php echo $unit ?></td>
        </tr>
        <tr>
            <td style='width: 24mm; font-size: 8pt; text-align: left'>Bulan</td>
            <td style='width: 1mm; font-size: 8pt; text-align: center'>:</td>
            <td style='width: 165mm; font-size: 8pt; text-align: left'> <?php echo $bulanAwal . ' Sampai ' . $bulanAkhir ?></td>
        </tr>
        <tr>
            <td style='width: 24mm; font-size: 8pt; text-align: left'>Periode</td>
            <td style='width: 1mm; font-size: 8pt; text-align: center'>:</td>
            <td style='width: 165mm; font-size: 8pt; text-align: left'> <?php echo $periode ?></td>
        </tr>
    </table>
    <br>

    <?php
    $jumlahPosBayar = COUNT($posBayar);
    echo "<table cellpadding='0' cellspacing='0'>";
    echo '<thead>';
    echo    '<tr>';
    echo        '<th rowspan="2" align="center" style="font-size: 8pt; padding: 1px 0px 1px 0px; border: 1px solid #999999; border-bottom: 1px solid #999999;">Kelas</th>';
    echo        '<th colspan="' . $jumlahPosBayar . '" align="center" style="font-size: 8pt; padding: 1px 0px 1px 0px; border: 1px solid #999999; border-bottom: 1px solid #999999;">Jenis Pembayaran</th>';
    echo        '<th rowspan="2" align="center" style="font-size: 8pt; padding: 1px 0px 1px 0px; border: 1px solid #999999; border-bottom: 1px solid #999999;">Total Tagihan Kelas</th>';
    echo    '</tr>';

    echo    '<tr>';
    foreach ($posBayar as $jenis_bayar) {
        echo '<th align="center" style="font-size: 8pt; padding: 1px 0px 1px 0px; border: 1px solid #999999; border-bottom: 1px solid #999999;">' . $jenis_bayar['pos_name'] . '</th>';
    }
    echo    '</tr>';

    echo '</thead>';

    echo "<tbody>";
    $totalTagihanPos = [];
    $grandTotalTagihan = 0;
    foreach ($kelas as $row) {
        $totalTagihanKelas = 0;
        echo '<tr>';
        echo '<td style="padding: 1px; font-size: 8pt; text-align: center">' . $row['class_name'] . '</td>';
        foreach ($posBayar as $jenisBayar) {
            $totalTagihan = 0;
            foreach ($dataTagihan as $tagihan) {
                if ($tagihan['class_id'] == $row['class_id']) {
                    if ($tagihan['pos_id'] == $jenisBayar['pos_id']) {
                        $totalTagihan = $tagihan['totalTagihan'] ?? 0;
                        $totalTagihanKelas += $tagihan['totalTagihan'];
                    }
                }
            }
            $totalTagihanPos[$jenisBayar['pos_id']] += $totalTagihan;
            echo '<td style="padding: 1px; font-size: 8pt; text-align: center">Rp ' . number_format($totalTagihan, 0, ",", ".") . '</td>';
        }
        echo '<td style="padding: 1px; font-size: 8pt; text-align: center">Rp ' . number_format($totalTagihanKelas, 0, ",", ".") . '</td>';
        echo '</tr>';
        $grandTotalTagihan += $totalTagihanKelas;
    }
    echo "</tbody>";
    echo "<tfoot>";
    echo "<tr>";
    echo '<td style="padding: 1px; font-size: 8pt; text-align: right; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"><strong>Total</strong></td>';
    foreach ($totalTagihanPos as $key => $totalPos) {
        echo '<td align="center" style="font-size: 8pt; padding: 1px 0px 1px 0px; border: 1px solid #999999; border-bottom: 1px solid #999999;">Rp ' . number_format($totalPos, 0, ",", ".") . '</td>';
    }
    echo '<td align="center" style="font-size: 8pt; padding: 1px 0px 1px 0px; border: 1px solid #999999; border-bottom: 1px solid #999999;">Rp ' . number_format($grandTotalTagihan, 0, ",", ".") . '</td>';
    echo "</tr>";
    echo "</tfoot>";

    echo '</table>';

    echo '<br>';
    ?>

</body>

</html>