<!DOCTYPE html>
<html>

<head>
	<title>Buku Nadhoman - <?php foreach ($siswa as $row) : ?> <?php echo ($f['r'] == $row['student_nis']) ? $row['student_full_name'] : '' ?><?php endforeach; ?></title>
</head>

<style type="text/css">
	@page {
		margin-top: 0.5cm;
		/*margin-bottom: 0.1em;*/
		margin-left: 1cm;
		margin-right: 1cm;
		margin-bottom: 0.1cm;
	}

	.name-school {
		font-size: 15pt;
		font-weight: bold;
		padding-bottom: -15px;
	}

	.unit {
		font-weight: bold;
		font-size: 8pt;
		margin-bottom: -10px;
	}

	.alamat {
		font-size: 8pt;
		margin-bottom: -10px;
	}

	.detail {
		font-size: 10pt;
		font-weight: bold;
		padding-top: -15px;
		padding-bottom: -12px;
	}

	body {
		font-family: sans-serif;
	}

	table {
		font-family: verdana, arial, sans-serif;
		font-size: 11px;
		color: #333333;
		border-width: none;
		/*border-color: #666666;*/
		border-collapse: collapse;
		width: 100%;
	}

	th {
		padding-bottom: 8px;
		padding-top: 8px;
		border-color: #666666;
		background-color: #dedede;
		/*border-bottom: solid;*/
		text-align: left;
	}

	td {
		text-align: left;
		border-color: #666666;
		background-color: #ffffff;
	}

	hr {
		border: none;
		height: 1px;
		/* Set the hr color */
		color: #333;
		/* old IE */
		background-color: #333;
		/* Modern Browsers */
	}

	.container {
		position: relative;
	}

	.topright {
		position: absolute;
		top: 0;
		right: 0;
		font-size: 18px;
		border-width: thin;
		padding: 5px;
	}

	.topright2 {
		position: absolute;
		top: 30px;
		right: 50px;
		font-size: 18px;
		border: 1px solid;
		padding: 5px;
		color: red;
	}
</style>

<body>

	<div class="container">
		<div class="topright">
			Buku Nadhoman<br>
			<?php
			$num = count($unit);
			if ($num > 1) { ?>
				<font size=12px>
					Unit Pesantren :
					<?php foreach ($siswa as $row) : ?>
						<?php echo $row['majors_short_name'] . '<br>' ?>
					<?php endforeach; ?></font>
			<?php } ?>
		</div>
	</div>
	<table>
		<tr>
			<td width="8%">
				<?php
				$arrContextOptions = array(
					"ssl" => array(
						"verify_peer" => false,
						"verify_peer_name" => false,
					),
				);

				$path = upload_url('school/' . logo());
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path, false, stream_context_create($arrContextOptions));
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				?>

				<img src="<?php echo $base64 ?>" style="height: 50px;">
			</td>
			<td>
				<span class="name-school"><?php echo $setting_school['setting_value'] ?></span>
				<br>
				<?php
				$num = count($unit);
				if ($num > 1) {
					echo '<span class="unit">';
					foreach ($unit as $row) {
						$data  = $row['majors_short_name'];
						$Pecah = explode(" ", $data);
						for ($i = 0; $i < count($Pecah); $i++) {
							echo $Pecah[$i] . " ";
						}
					}
					echo '</span>';
				} ?>
				<br>
				<span class="alamat">
					<?php echo $setting_address['setting_value'] ?>
					<?php echo $num == 1 ? '<br>' : ''; ?>
					<?php echo ' Telp. ' . $setting_phone['setting_value'] ?>
				</span>
			</td>
		</tr>
	</table>
	<hr>
	<table style="padding-top: -5px; padding-bottom: 5px">
		<tbody>
			<tr>
				<td style="width: 100px;">NIS</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($siswa as $row) : ?>
					<td style="width: 150px;"><?php echo $row['student_nis'] ?></td>
				<?php endforeach ?>
				<td style="width: 100px;">Kelas</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($siswa as $row) : ?>
					<td style="width: 150px;"><?php echo $row['class_name'] ?></td>
				<?php endforeach ?>
			</tr>
			<tr>
				<td style="width: 100px;">Nama</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($siswa as $row) : ?>
					<td style="width: 150px;"><?php echo $row['student_full_name'] ?></td>
				<?php endforeach ?>
				<td style="width: 130px;">Tahun Ajaran</td>
				<td style="width: 5px;">:</td>
				<td style="width: 131px;"><?php foreach ($period as $row) : ?> <?php echo ($f['n'] == $row['period_id']) ? $row['period_start'] . '/' . $row['period_end'] : '' ?><?php endforeach; ?></td>
			</tr>
		</tbody>
	</table>
	<hr>

	<table style="border-style: solid;">
		<tr>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">No.</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">Kitab</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">Total Hafalan</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">Keterangan</th>
		</tr>
		<?php
		$i = 1;
		foreach ($nadhoman as $row) :
		?>
			<tr>
				<td style="border-bottom: 1px solid;padding-top: 10px; padding-bottom: 10px;"><?php echo $i ?></td>
				<td style="border-bottom: 1px solid"><?php echo $row['kitab_name'] ?></td>
				<td style="border-bottom: 1px solid;"><?php echo $row['nadhomanSum'] . ' Bait' ?></td>
				<td style="border-bottom: 1px solid;"><?php echo $row['nadhoman_note'] ?></td>
			</tr>
		<?php
			$i++;
		endforeach ?>
	</table>
	<br>





</body>

</html>