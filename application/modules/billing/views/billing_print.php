<!DOCTYPE html>
<html>

<head>
	<title><?php echo 'Tagihan Pembayaran ' . $student['student_full_name'] . ' Kelas ' . $student['class_name'] ?></title>
	<style>
		.keep-together {
			page-break-inside: avoid;
		}
	</style>
</head>

<body>
	<table style='border-bottom: 2px solid black; padding-bottom: 10px; width: 190mm;'>
		<tr valign='top'>
			<td style='width: 187mm;' valign='middle'>
				<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
				</div>
				<span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp.
					<?php echo $setting_phone['setting_value'] ?></span>
			</td>
		</tr>
	</table>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<td style='width: 35mm; font-size: 8pt;'></td>
			<td style='width: 5mm; font-size: 8pt;'></td>
			<td style='width: 150mm; font-size: 8pt;'></td>
		</tr>
		<tr>
			<td colspan='3' style='font-size: 8pt;'>Hal : Pemberitahuan Pembayaran Uang Pembelajaran<br><br></td>
		</tr>
		<tr>
			<td colspan='3' style='font-size: 8pt;'>Kepada Yth. <br> bapak/ibu Orang Tua/Wali Ananda : <br><br></td>
		</tr>
		<tr>
			<td style='font-size: 8pt;'>Nama</td>
			<td style='font-size: 8pt;'>:</td>
			<td style='font-size: 8pt;'><?php echo $student['student_full_name'] ?></td>
		</tr>
		<tr valign='top'>
			<td style='font-size: 8pt;'>NIS</td>
			<td style='font-size: 8pt;'>:</td>
			<td style='font-size: 8pt;'><?php echo $student['student_nis'] ?></td>
		</tr>
		<tr valign='top'>
			<td style='font-size: 8pt;'>Kelas</td>
			<td style='font-size: 8pt;'>:</td>
			<td style='font-size: 8pt;'><?php echo $student['class_name'] ?></td>
		</tr>
		<tr valign='top'>
			<td style='font-size: 8pt;'>Tahun Ajaran</td>
			<td style='font-size: 8pt;'>:</td>
			<td style='font-size: 8pt;'><?php echo $period['period_start'] . '/' . $period['period_end']; ?></td>
		</tr>
		<tr>
			<td colspan='3' style='font-size: 8pt;'><br> </td>
		</tr>
		<tr>
			<td colspan='3' style='font-size: 8pt;'>Dengan ini kami memberitahukan bahwa bapak/ibu dari ananda tersebut diatas masih memiliki tunggakan dengan rincian sebagai berikut : </td>
		</tr>
	</table>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<th align='center' style='font-size: 10pt; width: 20mm; padding: 1px 0px 1px 0px;'></th>
			<th align='center' style='font-size: 10pt; width: 110mm; padding: 1px 0px 1px 0px; border-top: 2px solid black; border-bottom: 2px solid black;'>Nama Tagihan</th>
			<th align='center' style='font-size: 10pt; width: 10mm; padding: 1px 0px 1px 0px; border-top: 2px solid black; border-bottom: 2px solid black;'></th>
			<th align='center' style='font-size: 10pt; width: 30mm; padding: 1px 0px 1px 0px; border-top: 2px solid black; border-bottom: 2px solid black;'>Nominal</th>
			<th align='center' style='font-size: 10pt; width: 20mm; padding: 1px 0px 1px 0px;'></th>
		</tr>
		<?php
		$billBulan = 0;
		$billBebas = 0;
		foreach ($bulan as $row) {
		?>
			<tr valign='top'>
				<td align='center' style='padding: 1px; font-size: 10pt; '></td>
				<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'] . '-' . $row['month_name'] ?>
				</td>
				<td align='right' style='padding: 1px; font-size: 10pt; '>Rp</td>
				<td align='right' style='padding: 1px; font-size: 10pt; '>
					<?php echo number_format($row['bulan_bill'], 0, ",", ".") ?>
				</td>
				<td align='right' style='padding: 1px; font-size: 10pt; '></td>
			</tr>
			<?php $billBulan += $row['bulan_bill']; ?>
		<?php
		};
		foreach ($bebas as $row) {
		?>
			<tr valign='top'>
				<td align='center' style='padding: 1px; font-size: 10pt; '></td>
				<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end'] ?>
				</td>
				<td align='right' style='padding: 1px; font-size: 10pt; '>Rp</td>
				<td align='right' style='padding: 1px; font-size: 10pt; '>
					<?php echo number_format($row['bebas_bill'] - $row['bebas_diskon'] - $row['bebas_total_pay'], 0, ",", ".") ?>
				</td>
				<td align='right' style='padding: 1px; font-size: 10pt; '></td>
			</tr>
			<?php $billBebas += $row['bebas_bill'] - $row['bebas_diskon'] - $row['bebas_total_pay']; ?>
		<?php } ?>
		<tr>
			<td align='right' style='padding: 1px; font-size: 10pt; '></td>
			<td align="left" style="border-top: 2px solid black; background-color: #F0F0F0; padding: 1px; font-size: 10pt;">
				<b>Total Tagihan Siswa</b>
			</td>
			<td align='right' style='border-top: 2px solid black; background-color: #F0F0F0; padding: 1px; font-size: 10pt; '>Rp</td>
			<td align="right" style="border-top: 2px solid black; background-color: #F0F0F0; padding: 1px; font-size: 10pt;">
				<b><?php echo number_format($billBulan + $billBebas, 0, ",", ".") ?></b>
			</td>
			<td align='right' style='padding: 1px; font-size: 10pt; '></td>
		</tr>
	</table>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<td style='width: 190mm; font-size: 10pt;'>Tagihan Tahun Ajaran Lama : </td>
		</tr>
	</table>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<th align='center' style='font-size: 10pt; width: 20mm;'></th>
			<th align='center' style='font-size: 10pt; width: 110mm; border-top: 2px solid black; border-bottom: 2px solid black;'>Nama Tagihan</th>
			<th align='center' style='font-size: 10pt; width: 10mm; border-top: 2px solid black; border-bottom: 2px solid black;'></th>
			<th align='center' style='font-size: 10pt; width: 30mm; border-top: 2px solid black; border-bottom: 2px solid black;'>Nominal</th>
			<th align='center' style='font-size: 10pt; width: 20mm;'></th>
		</tr>

		<?php
		$totalPeriodLama = 0;
		$grouped = [];

		// Gabungkan bulan dan bebas lama ke satu array agar bisa digroup
		foreach ($bulanLama as $row) {
			$ta = $row['period_start'] . '/' . $row['period_end'];
			$grouped[$ta][] = [
				'nama' => $row['pos_name'] . ' - ' . $row['month_name'],
				'nominal' => $row['bulan_bill']
			];
		}

		foreach ($bebasLama as $row) {
			$ta = $row['period_start'] . '/' . $row['period_end'];
			$grouped[$ta][] = [
				'nama' => $row['pos_name'],
				'nominal' => $row['bebas_bill'] - $row['bebas_total_pay']
			];
		}

		// Tampilkan hasil grouping
		foreach ($grouped as $ta => $rows) {
			$subTotal = 0;
		?>
			<tr>
				<td colspan="5" style="font-size:10pt; padding-top:4px;"><b>Tagihan Tahun Ajaran <?php echo $ta; ?></b></td>
			</tr>
			<?php foreach ($rows as $r) { ?>
				<tr valign='top'>
					<td></td>
					<td style='padding: 1px; font-size: 10pt;'><?php echo $r['nama']; ?></td>
					<td align='right' style='padding: 1px; font-size: 10pt;'>Rp</td>
					<td align='right' style='padding: 1px; font-size: 10pt;'><?php echo number_format($r['nominal'], 0, ",", "."); ?></td>
					<td></td>
				</tr>
				<?php $subTotal += $r['nominal']; ?>
			<?php } ?>
			<tr>
				<td></td>
				<td align="left" style="border-top: 1px solid black; background-color: #F9F9F9; padding: 1px; font-size: 10pt;"><b>Total T.A <?php echo $ta; ?></b></td>
				<td align='right' style='border-top: 1px solid black; background-color: #F9F9F9; font-size: 10pt;'>Rp</td>
				<td align="right" style="border-top: 1px solid black; background-color: #F9F9F9; font-size: 10pt;"><b><?php echo number_format($subTotal, 0, ",", "."); ?></b></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="5" style="height:4px;"></td>
			</tr>
		<?php
			$totalPeriodLama += $subTotal;
		}
		?>

	</table>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
		<tr>
			<td align='left' style='font-size: 10pt; padding: 1px;'>
				<b><?php echo 'Total Keseluruhan : Rp ' . number_format($billBulan + $billBebas + $totalPeriodLama, 0, ",", ".") ?></b>
			</td>
		</tr>
	</table>
	<br>
	<div class="keep-together">
		<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
			<tr>
				<td align='left' style='font-size: 8pt; padding: 1px;'>
					Demikian surat pemberitahuan ini kami sampaikan atas perhatian dan kerjasamanya kami ucapkan terima kasih.
					Apabila ada kekeliruan dengan surat pemberitahuan ini, mohon bapak/ibu melakukan konfirmasi ke bagian keuangan
					dengan membawa tanda bukti.<br><br>
				</td>
			</tr>
			<tr>
				<td align='left' style='font-size: 8pt; padding: 1px;'>
					Atas perhatian dan partisipasinya kami ucapkan terima kasih. <br><br>
				</td>
			</tr>
		</table>

		<br>

		<table cellpadding='0' cellspacing='0' style='width: 170mm;'>
			<tr>
				<td style='width: 60%;'></td>
				<td style='width: 40%; text-align: right; font-size: 8pt; padding: 1px;'>
					<?php echo $setting_city['setting_value'] ?>, <?php echo pretty_date(date('Y-m-d'), 'd F Y', false) ?>
				</td>
			</tr>

			<tr>
				<td colspan='2' style='height: 50px;'></td>
			</tr> <!-- jarak tanda tangan -->

			<tr>
				<td style='width: 60%;'></td>
				<td style='width: 40%; text-align: right; font-size: 8pt; padding: 1px;'>
					<b><?php echo ucfirst($setting_nama_bendahara['setting_value']); ?></b><br>
					NIP. <?php echo $setting_nip_bendahara['setting_value']; ?>
				</td>
			</tr>
		</table>
	</div>
</body>

</html>