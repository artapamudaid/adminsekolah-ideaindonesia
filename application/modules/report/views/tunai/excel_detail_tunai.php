<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename = Laporan Kas Tunai Per Jenis Akun Tanggal " . pretty_date($date_start, 'd-m-Y', FALSE) . ' Sampai ' . pretty_date($date_end, 'd-m-Y', FALSE) . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>

<head>
    <title><?php echo 'Laporan Kas Tunai per Jenis Akun Tanggal ' . pretty_date($date_start, 'd-m-Y', FALSE) . ' Sampai ' . pretty_date($date_end, 'd-m-Y', FALSE) ?></title>
</head>

<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 190mm;'>
        <tr valign='top'>
            <td colspan="9" style='width: 187mm;' valign='middle'>
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
            <td colspan="2" style='width: 24mm; font-size: 8pt; text-align: left'>Unit Sekolah</td>
            <td colspan="7" style='width: 1mm; font-size: 8pt; text-align: left'>: <?php echo $unit ?></td>
        </tr>
    </table>
    <br>
    <table style='width: 190mm;'>
        <tr>
            <td colspan="9" style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Kas Tunai per Jenis Akun Tanggal ' . pretty_date($date_start, 'd F Y', FALSE) . ' Sampai ' . pretty_date($date_end, 'd F Y', FALSE) ?></b></td>
        </tr>
    </table>
    <br>
    <?php
    $grandTotalPenerimaan = 0;
    $grandTotalPengeluaran = 0;
    ?>
    <?php foreach ($data_jurnal as $jenis_bayar => $items) {
        echo "<table cellpadding='0' cellspacing='0' style='width: 190mm;'>";
        echo '<thead>';
        echo    '<tr>
					<th colspan="9" align="left" style="font-size: 9pt; width: 5mm; padding: 1px 0px 1px 0px;">' . $jenis_bayar . '</th>
				</tr>';
        echo    '<tr>
                    <th align="center" style="font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No.</th>
                	<th align="center" style="font-size: 8pt; width: 15mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tanggal</th>
                	<th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kas</th>
                	<th align="center" style="font-size: 8pt; width: 35mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Keterangan</th>
                	<th align="center" style="font-size: 8pt; width: 15mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">NIS</th>
                	<th align="center" style="font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Nama</th>
                	<th align="center" style="font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kelas</th>
                	<th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Debit</th>
                	<th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kredit</th>
                </tr>';
        echo '</thead>';
        echo "<tbody>";
        // echo "<tr>";
        // echo '<td colspan="6">' . count($item) . '</td>';
        // echo "</tr>";
        // Loop melalui item dalam kategori
        $no = 1;
        $totalPenerimaan  = 0;
        $totalPengeluaran = 0;
        foreach ($items as $item) {
            echo '<tr>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: center">' . $no . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: center">' . $item['tanggal'] . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: center">' . $item['namaKas'] . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: left">' . $item['keterangan'] . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: left">' . $item['nis'] . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: left">' . $item['nama'] . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: left">' . $item['kelas'] . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: right">Rp ' . number_format($item['penerimaan'], 0, ",", ".") . '</td>';
            echo '<td style="padding: 1px; font-size: 8pt; text-align: right">Rp ' . number_format($item['pengeluaran'], 0, ",", ".") . '</td>';
            echo '</tr>';
            $no++;
            $totalPenerimaan += $item['penerimaan'];
            $totalPengeluaran += $item['pengeluaran'];
        }
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr>";
        echo '<td colspan="7" style="padding: 1px; font-size: 8pt; text-align: right; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"><strong>Total</strong></td>';
        echo '<td style="padding: 1px; font-size: 8pt; text-align: right; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"><strong>Rp ' . number_format($totalPenerimaan, 0, ',', '.') . '</strong></td>';
        echo '<td style="padding: 1px; font-size: 8pt; text-align: right; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"><strong>Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</strong></td>';
        echo "</tr>";
        echo "</tfoot>";
        echo '</table>';

        echo '<br>';
        $grandTotalPenerimaan += $totalPenerimaan;
        $grandTotalPengeluaran += $totalPengeluaran;
    } ?>

    <?php
    $grandTotal = $saldoAwal + $grandTotalPenerimaan;
    $saldoAkhir = $grandTotal - $grandTotalPengeluaran;
    ?>

    <table cellpadding='0' cellspacing='0' style='width: 190mm;'>
        <tr>
            <td colspan="5" style='font-size: 8pt; border-bottom: 1px solid #999999; border-top: 1px solid #999999; text-align: center'></td>
            <td style='font-size: 8pt; border-bottom: 1px solid #999999; border-top: 1px solid #999999; text-align: center'></td>
            <td style='font-size: 8pt; border-bottom: 1px solid #999999; border-top: 1px solid #999999; text-align: center'><b>Debit</b></td>
            <td style='font-size: 8pt; border-bottom: 1px solid #999999; border-top: 1px solid #999999; text-align: center'></td>
            <td style='font-size: 8pt; border-bottom: 1px solid #999999; border-top: 1px solid #999999; text-align: center'><b>Kredit</b></td>
        </tr>
        <tr>
            <td colspan="4" style='font-size: 8pt;'><strong>Sub Total</strong></td>
            <td style='font-size: 8pt; text-align: center'>:</td>
            <td style='font-size: 8pt; text-align: center'>Rp </td>
            <td style='font-size: 8pt; text-align: center'><?php echo number_format($grandTotalPenerimaan, 0, ",", ".") ?></td>
            <td style='font-size: 8pt; text-align: center'>Rp </td>
            <td style='font-size: 8pt; text-align: center'><?php echo number_format($grandTotalPengeluaran, 0, ",", ".") ?></td>
        </tr>
        <tr>
            <td colspan="4" style='font-size: 8pt;'><strong>Saldo Awal</strong></td>
            <td style='font-size: 8pt; text-align: center'>:</td>
            <td style='font-size: 8pt; text-align: center'>Rp </td>
            <td style='font-size: 8pt; text-align: center'><?php echo number_format($saldoAwal, 0, ",", ".") ?></td>
            <td style='font-size: 8pt; text-align: center'> </td>
            <td style='font-size: 8pt; text-align: center'>-</td>
        </tr>
        <tr>
            <td colspan="4" style='font-size: 8pt;'><strong>Total</strong></td>
            <td style='font-size: 8pt; text-align: center'>:</td>
            <td style='font-size: 8pt; text-align: center'>Rp </td>
            <td style='font-size: 8pt; text-align: center'><?php echo number_format($grandTotal, 0, ",", ".") ?></td>
            <td style='font-size: 8pt; text-align: center'>Rp </td>
            <td style='font-size: 8pt; text-align: center'><?php echo number_format($grandTotalPengeluaran, 0, ",", ".") ?></td>
        </tr>
        <tr>
            <td colspan="4" style='font-size: 8pt;'><strong>Saldo Akhir</strong></td>
            <td style='font-size: 8pt; text-align: center'>:</td>
            <td style='font-size: 8pt; text-align: center'>Rp </td>
            <td style='font-size: 8pt; text-align: center'><?php echo number_format($saldoAkhir, 0, ",", ".") ?></td>
            <td style='font-size: 8pt; text-align: center'> </td>
            <td style='font-size: 8pt; text-align: center'>-</td>
        </tr>
    </table>

</body>

</html>