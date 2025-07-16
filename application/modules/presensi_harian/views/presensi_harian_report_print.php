<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekap Presensi Kelas " . $namaKelas['class_name'] . "Bulan " . $namaBulan . " " . $namaTahun . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 190mm;'>
	<tr valign='top'>
		<td colspan="8" valign='middle'>
			<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
			</div>
			<span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp. <?php echo $setting_phone['setting_value'] ?></span>
		</td>
	</tr>
</table>
<br>
<table border="1">
	<thead>
		<tr>
			<th width='5' rowspan='2'>
				<center>No.</center>
			</th>
			<th rowspan='2'>
				<center>NIS</center>
			</th>
			<th rowspan='2'>
				<center>Nama</center>
			</th>
			<th colspan='<?php echo $interval->format("%a") ?>'>
				<center><?php echo $namaBulan . ' ' . $namaTahun ?></center>
			</th>
		</tr>
		<tr>

			<?php
			foreach ($daterange as $dt) {
				echo '<th>' . $dt->format("d") . '</th>';
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		foreach ($student as $row) :
		?>
			<tr>
				<td><?php echo $no++ ?></td>
				<td><?php echo $row['student_nis'] ?></td>
				<td><?php echo $row['student_full_name'] ?></td>
				<?php
				$student_id = $row['student_id'];
				$absensiSiswa = $data_absen[$student_id];
				foreach ($daterange as $dt) {
					$day = $dt->format("d");
					$period_id = $q['p'];
					$month_id = $q['m'];
					$class_id = $q['c'];

					echo (isset($absensiSiswa[$day])) ? '<td>' . $absensiSiswa[$day] . '</td>' : '<td>-</td>';
				}
				?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>