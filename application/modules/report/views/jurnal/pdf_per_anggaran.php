<html>

<head>
	<title><?php echo 'Laporan Jurnal per Jenis Anggaran Tanggal ' . pretty_date($ds, 'd-m-Y', FALSE) . ' Sampai ' . pretty_date($de, 'd-m-Y', FALSE) ?></title>
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
			<td style='width: 165mm; font-size: 8pt; text-align: left'><?php echo $unit ?></td>
		</tr>
	</table>
	<br>
	<table style='width: 190mm;'>
		<tr>
			<td style='width: 20mm; font-size: 8pt; text-align: center' valign='center'></td>
			<td style='width: 150mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Jurnal Jenis Anggaran Tanggal ' . pretty_date($ds, 'd F Y', FALSE) . ' Sampai ' . pretty_date($de, 'd F Y', FALSE) ?></b></td>
			<td style='width: 20mm; font-size: 8pt;' align='right'></td>
		</tr>
	</table>
	<br>

	<?php
	// Tampilkan data per tabel
	foreach ($dataPembayaran as $jenis_bayar => $rows) {

		echo "<table cellpadding='0' cellspacing='0' style='width: 190mm;'>";
		echo '<thead>';
		echo	'<tr>
					<th colspan="9" align="left" style="font-size: 9pt; width: 5mm; padding: 1px 0px 1px 0px;">' . $rows[0]['akunBayar'] . " - " . $jenis_bayar . '</th>
				</tr>';
		echo    '<tr>
					<th align="center" style="font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">No.</th>
					<th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Tanggal</th>
					<th align="center" style="font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kas</th>';

		if ($rows[0]['jenisTransaksi'] == 'Bulan' || $rows[0]['jenisTransaksi'] == 'Bebas') {
			if ($rows[0]['jenisTransaksi'] == 'Bulan') {
				echo   '<th align="center" style="font-size: 8pt; width: 10mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Bulan</th>';
			}
			echo	'<th align="center" style="font-size: 8pt; width: 15mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">NIS</th>
					 <th align="center" style="font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Nama</th>';
			if ($rows[0]['jenisTransaksi'] == 'Bebas') {
				echo   '<th align="center" style="font-size: 8pt; width: 10mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"></th>';
			}
			echo '<th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Kelas</th>';
		} else {
			echo 	'<th align="center" style="font-size: 8pt; width: 10mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Keterangan</th>
					<th align="center" style="font-size: 8pt; width: 15mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"></th>
					<th align="center" style="font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;"></th>';
		}

		echo		'<th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Penerimaan</th>
					 <th align="center" style="font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;">Pengeluaran</th>
				</tr>';
		echo "</thead><tbody>";
		$no = 1;
		foreach ($rows as $row) {
			echo '<tr>
				    <td style="padding: 1px; font-size: 8pt; text-align: center">' . $no++ . '</td>
				    <td style="padding: 1px; font-size: 8pt; text-align: center">' . pretty_date($row['tanggal'], 'd/m/Y', FALSE) . '</td>
				    <td style="padding: 1px; font-size: 8pt; text-align: left">' . $row['namaKas'] . '</td>';
			if ($row['jenisTransaksi'] == 'Bulan') {
				echo   ' <td style="padding: 1px; font-size: 8pt; text-align: center">' . $row['namaBulan'] . '</td>';
			}

			echo	'<td style="padding: 1px; font-size: 8pt; text-align: center">' . $row['nis'] . '</td>';


			if ($row['jenisTransaksi'] == 'Bebas') {
				echo		'<td colspan="2" style="padding: 1px; font-size: 8pt; text-align: left">' . $row['nama'] . '</td>';
			} else {
				echo		'<td style="padding: 1px; font-size: 8pt; text-align: left">' . $row['nama'] . '</td>';
			}
			// if ($row['jenisTransaksi'] == 'Bebas') {
			// 	echo		'<td style="padding: 1px; font-size: 8pt; text-align: center">b</td>';
			// }
			echo	'<td style="padding: 1px; font-size: 8pt; text-align: center">' . $row['kelas'] . '</td>
				    <td style="padding: 1px; font-size: 8pt; text-align: center">' . 'Rp ' . number_format($row['penerimaan'], 0, ",", ".") . '</td>
				    <td style="padding: 1px; font-size: 8pt; text-align: center">' . '-' . '</td>
				</tr>';
		}
		echo "</tbody>";
		echo "</table>";
		echo "<br>";
	}
	?>

</body>

</html>