<!DOCTYPE html>
<html>

<head>
	<?php foreach ($kesehatan as $row): ?>
		<title><?php echo 'Surat_keterangan_sakit_' . $row['student_full_name'] ?></title>
	<?php endforeach ?>
</head>

<style type="text/css">
	@page {
		margin-top: 1cm;
		/*margin-bottom: 0.1em;*/
		margin-left: 2cm;
		margin-right: 2cm;
		margin-bottom: 1cm;
	}

	.name-school {
		font-size: 15pt;
		font-weight: bold;
		padding-bottom: -15px;
	}

	.unit {
		font-weight: bold;
		font-size: 10pt;
		margin-bottom: -10px;
	}

	.alamat {
		font-size: 10pt;
		margin-bottom: -10px;
	}

	.detail {
		font-size: 14pt;
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
		color: #000000;
		border-width: none;
		/*border-color: #666666;*/
		border-collapse: collapse;
		width: 100%;
	}

	th {
		padding-bottom: 8px;
		padding-top: 8px;
		border-color: #ffffff;
		background-color: #ffffff;
		/*border-bottom: solid;*/
		text-align: left;
	}

	td {
		text-align: left;
		border-color: #ffffff;
		background-color: #ffffff;
	}

	hr {
		border: none;
		height: 3px;
		/* Set the hr color */
		color: #000;
		/* old IE */
		background-color: #000;
		/* Modern Browsers */
		margin-bottom: 10px;
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
	<table>
		<tr>
			<td width="11%">
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

				<img src="<?php echo $base64 ?>" style="height: 60px;">
			</td>
			<td>
				<span class="name-school">
					<?php echo strtoupper($setting_school['setting_value']) ?>
				</span>
				<br>
				<span class="alamat2">
					<?php echo $setting_address['setting_value'] ?>
					<?php echo '<br> Telp: ' . $setting_phone['setting_value'] ?>
					<?php echo ' | Email: ' . $setting_email['setting_value'] ?>
					<?php echo ' | Website: ' . $setting_website['setting_value'] ?>
				</span>
			</td>

		</tr>
	</table>
	<br>
	<hr>
	<table style="padding-top: -5px; padding-bottom: 5px">
		<tbody>
			<tr style="width: 100%;">
				<td style="width: 100px;"><br><br></td>
				<td style="width: 5px;"><br><br></td>
				<td><br><br></td>
				<td><br><br></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><b><u>
							<center>
								<font size="18px">SURAT KETERANGAN SAKIT</font>
						</u></b></center>
				</td>
			</tr>
			<tr>
				<td style="width: 100px;"><br><br></td>
				<td style="width: 5px;"><br><br></td>
				<td><br><br></td>
				<td><br><br></td>
			</tr>
			<tr>
				<td colspan="4">
					<p>Yang bertanda tangan dibawah ini, Bidan / Dokter / Petugas kesehatan, <?php echo strtoupper($setting_school['setting_value']) ?> , menerangkan bahwa :</p>
				</td>
			</tr>
			<tr>
				<td style="width: 100px;"><br><br></td>
				<td style="width: 5px;"><br><br></td>
				<td style="width: 100px;"><br><br></td>
				<td><br><br></td>
			</tr>
			<tr>
				<td style="width: 100px;">No. Induk Siswa</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($kesehatan as $row): ?>
					<td colspan="2"><?php echo $row['student_nis'] ?></td>
				<?php endforeach ?>
			</tr>
			<tr>
				<td style="width: 100px;">Nama</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($kesehatan as $row): ?>
					<td colspan="2"><?php echo $row['student_full_name'] ?></td>
				<?php endforeach ?>
			</tr>
			<tr>

				<td style="width: 100px;">Kelas</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($kesehatan as $row): ?>
					<td colspan="2"><?php echo $row['class_name'] ?></td>
				<?php endforeach ?>
			</tr>
			<tr>

				<td style="width: 100px;">Keluhan</td>
				<td style="width: 5px;">:</td>
				<?php foreach ($kesehatan as $row): ?>
					<td colspan="2"><?php echo $row['kesehatan_ill'] ?></td>
				<?php endforeach ?>
			</tr>
			<tr>
				<td style="width: 100px;"><br></td>
				<td style="width: 5px;"><br></td>
				<td><br></td>
				<td><br></td>
			</tr>
			<tr>
				<td colspan="4">
					<p>
						<?php foreach ($kesehatan as $row): ?>
							<?php echo $row['kesehatan_note'] ?>
						<?php endforeach ?>
					</p>
				</td>
			</tr>

			<?php
			$user = $this->db->query("SELECT users.user_full_name AS petugas FROM users WHERE users.user_id = " . $this->session->userdata('uid') . "")->row_array();
			?>

			<tr>
				<td colspan="3" style="text-align: center;"></td>
				<td style="text-align: center;"></td>
			</tr>
			<tr>
				<td colspan="3" style="text-align: center;"></td>
				<td style="text-align: center;">Petugas Kesehatan</td>
			</tr>
			<tr>
				<td colsapn="4">&nbsp;</td>
			</tr>
			<tr>
				<td colsapn="4">&nbsp;</td>
			</tr>
			<tr>
				<td colsapn="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align: center;"></td>
				<td style="text-align: center;"><b><?php echo ucfirst($user['petugas']); ?></b></td>
			</tr>
		</tbody>
	</table>
	<br>
</body>

</html>