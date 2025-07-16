<!DOCTYPE html>
<html>
<head>
	<title><?php foreach ($siswa as $row):?> <?php echo ($f['r'] == $row['student_nis']) ? $row['student_full_name'] : '' ?><?php endforeach; ?></title>
</head>

<style type="text/css">
@page {
	margin-top: 0.5cm;
	/*margin-bottom: 0.1em;*/
	margin-left: 100px;
	margin-right: 1cm;
	margin-bottom: 0.1cm;
}
.name-school{
	font-size: 15pt;
	font-weight: bold;
	padding-bottom: -15px;
}

.unit{
    font-weight: bold;
	font-size: 8pt;
	margin-bottom: -10px;
}

.alamat{
	font-size: 8pt;
	margin-bottom: -10px;
}
.detail{
	font-size: 10pt;
	font-weight: bold;
	padding-top: -15px;
	padding-bottom: -12px;
}
body {
	font-family: sans-serif;
}
table {
	font-family: arial,sans-serif;
	font-size:15px;
	color:#333333;
	border-width: none;
	/*border-color: #666666;*/
	border-collapse: collapse;
	width: 100%;
	margin-left: 20px;
}

/* th {
	padding-bottom: 8px;
	padding-top: 8px;
	border-color: #666666;
	background-color: #dedede;
	border-bottom: solid;
	text-align: left;
}

td {
	text-align: left;
	border-color: #666666;
	background-color: #ffffff;
} */

hr {
	border: none;
	height: 1px;
	/* Set the hr color */
	color: #333; /* old IE */
	background-color: #333; /* Modern Browsers */
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
		<table style="width: 100%; text-align: center;">
			<tr>
				<td>
					<br><br>
					<img src="media/template_kop/kop_surat.png" style="height: 110px;">
				</td>
			</tr>
		</table>
		<br><br><br>
		<table>
			<tr>
				<td style="width: 10%; text-align: left;">No.</td>
				<td style="width: 40%; text-align: left;"> : <?= $surat_dispensasi['dispensasi_nomor_surat_id'] ?></td>
				<td style="width: 40%; text-align: right;"><?= pretty_date(date('l, d-m-Y'), 'd F Y', false)?></td>
			</tr>
			<tr>
				<td style="width: 10%; text-align: left;">Lamp.	</td>
				<td style="width: 40%; text-align: left;"> : - </td>
				<td style="width: 40%; text-align: right;"></td>
			</tr>
			<tr>
				<td style="width: 10%; text-align: left;">Hal	</td>
				<td style="width: 40%; text-align: left;"> : <b>Dispensasi KBM</b> </td>
				<td style="width: 40%; text-align: right;"></td>
			</tr>
		</table>
		<br><br>

		<table style="width: 100%;">
			<tr>
				<td style="">Kepada Yth. <br>
							Bapak/Ibu Guru/Wali Kelas <br>
							di - <br>
							&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;tempat <br><br>
							&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Sehubungan dengan <b><?= $surat_dispensasi['dispensasi_desc'] ?></b> yang dilaksanakan pada  : <br><br>
				</td>
			</tr>
			<tr>
				<td>
					<table>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px; ">Hari </td>
							<td>:</td>
							<td><?= pretty_date($surat_dispensasi['dispensasi_date'], 'l', false) ?></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px;">Tanggal </td>
							<td>:</td>
							<td><?= pretty_date($surat_dispensasi['dispensasi_date'], 'd F Y', false)?></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px;">Waktu </td>
							<td>:</td>
							<td><?= $surat_dispensasi['dispensasi_time_start'] .' - '. $surat_dispensasi['dispensasi_time_end'] ?></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px;">Tempat</td>
							<td>:</td>
							<td><?= $surat_dispensasi['dispensasi_lokasi']?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<br> Maka nama - nama dibawah ini :  <br><br>
					<table border=1px; width="100%";>
						<tr>
							<th style="width: 50px; height: 30px; text-align: center; background-color:green;">No</th>
							<th style="width: 300px; height: 30px; text-align: center; background-color:green;">Nama Peserta Utusan</th>
							<th style="width: 300px; height: 30px; text-align: center; background-color:green;">Kelas</th>
						</tr>
							<?php  
							$pengikut 	= $surat_dispensasi['dispensasi_student_id'];
							$id 		= explode("," , $pengikut);
							$data 		= $this->db->query("SELECT student_full_name, student_nis, class_name
															 FROM student 
															 LEFT JOIN class ON class.class_id = student.class_class_id
															 WHERE student_id in ($pengikut)")->result_array();
							$no = 1;
							foreach ($data as $string) {
								?>
								<tr>
									<td><?php echo $no++; ?></td>
									<td><?php echo $string['student_full_name'].'('.$string['student_nis'].')'; ?></td>
									<td><?php echo $string['class_name']; ?></td>
								</tr>
								<?php
							}
							?>
					</table>
				</td>
			</tr>
			<tr>
				<td>
				<br><br>
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Agar diberikan dispensasi (izin) pada kegiatan Kegiatan Belajar Mengajar (KBM) di kelas. Dikarenakan harus mengikuti kegiatan tersebut di atas. <br><br>
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Demikianlah surat dispensasi ini kami sampaikan atas perhatian dan kerjasamanya kami ucapkan terima kasih.
				</td>
			</tr>
		</table>
		<br><br><br><br><br>
		<table style="margin-left:250px;">
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align: center;">
					Serang, <?php echo pretty_date(date('Ymd'), 'd F Y', false) ?>
					<br>
					Kepala,
					<br><br><br><br><br><br><br>
					<?= $setting_nama_kepsek['setting_value']?>
				</td>
			</tr>
		</table>

		

	</body>
	</html>