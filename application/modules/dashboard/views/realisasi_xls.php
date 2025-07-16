<?php
function pembulatan(Float $number = null)
{

    $round = ceil($number * 10) / 10;

    $round = number_format($round, 1);

    return $round;
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Realisasi_Pembayaran_Bulan_" . $bulan . "_TA_" . $period . ".xls");
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
        <td colspan="1" style='width: 100mm; font-size: 10pt; text-align: center' valign='center'></td>
        <td colspan="3" style='width: 150mm; font-size: 10pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Realisasai Pembayaran ' . $bulan; ?></b></td>
        <td colspan="1" style='width: 100mm; font-size: 10pt;' align='right'></td>
    </tr>
</table>
<br>
<br>
<table cellpadding='0' cellspacing='0' style='width: 250mm;' border='1'>
    <tr>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Unit</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Tagihan</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Terbayar</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Balum Terbayar</th>
        <th align='center' style='font-size: 10pt; width: 50mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Pencapaian</th>
    </tr>
    <?php

    $sumTagihanBulan = 0;
    $sumTerbayarBulan = 0;
    $sumBelumBulan = 0;
    $sumPresentaseBulan = 0;

    $sumTagihanBebas = 0;
    $sumTerbayarBebas = 0;
    $sumBelumBebas = 0;
    $sumPresentaseBebas = 0;

    foreach ($majors as $major) {
        foreach ($rBulan as $dBulan) {
            if ($major['majors_id'] == $dBulan['majors_id']) {

                $totalBulan = $dBulan['terbayar'] + $dBulan['belum'];
                $pembulatanBulan = pembulatan(($dBulan['terbayar'] / $totalBulan) * 100);
    ?>
                <tr valign='top'>
                    <td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $dBulan['unit'] ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($totalBulan, "0", ",", ".") ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($dBulan['terbayar'], "0", ",", ".") ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($dBulan['belum'], "0", ",", ".") ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'><?php echo $pembulatanBulan ?>%</td>
                </tr>
            <?php
                $sumTagihanBulan += $totalBulan;
                $sumTerbayarBulan += $dBulan['terbayar'];
                $sumBelumBulan += $dBulan['belum'];
                $sumPresentaseBulan += $pembulatanBulan;
            }
        }
        foreach ($rBebas as $dBebas) {
            if ($major['majors_id'] == $dBebas['majors_id']) {

                $totalBebas = $dBebas['terbayar'] + $dBebas['belum'];
                $pembulatanBebas = pembulatan(($dBebas['terbayar'] / $totalBebas) * 100);
            ?>

                <tr valign='top'>
                    <td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $dBebas['unit'] ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($totalBebas, "0", ",", ".") ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($dBebas['terbayar'], "0", ",", ".") ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($dBebas['belum'], "0", ",", ".") ?></td>
                    <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'><?php echo $pembulatanBebas ?>%</td>
                </tr>
    <?php
                $sumTagihanBebas += $totalBebas;
                $sumTerbayarBebas += $dBebas['terbayar'];
                $sumBelumBebas += $dBebas['belum'];
                $sumPresentaseBebas += $pembulatanBebas;
            }
        }
    }
    ?>
    <tr valign='top' style="font-weight: bold;">
        <td align='left' style='padding: 1px; font-size: 10pt; '></td>
        <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($sumTagihanBulan + $sumTagihanBebas, "0", ",", ".") ?></td>
        <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($sumTerbayarBulan + $sumTerbayarBebas, "0", ",", ".") ?></td>
        <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'>Rp <?php echo number_format($sumBelumBulan + $sumBelumBebas, "0", ",", ".") ?></td>
        <td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'><?php echo pembulatan(($sumTerbayarBulan + $sumTerbayarBebas) / ($sumTagihanBulan + $sumTagihanBebas) * 100) ?>%</td>
    </tr>
    <tr>
        <td colspan='3' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
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