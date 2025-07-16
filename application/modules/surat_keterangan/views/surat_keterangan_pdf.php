<!DOCTYPE html>
<html>
<head>
	<title><?php foreach ($siswa as $row):?> <?php echo ($f['r'] == $row['student_nis']) ? $row['student_full_name'] : '' ?><?php endforeach; ?></title>
</head>

<style type="text/css">
@page {
	margin-top: 0.5cm;
	/*margin-bottom: 0.1em;*/
	margin-left: 1cm;
	margin-right: 1cm;
	margin-bottom: 0.1cm;
}
.name-school{
	text-align: center;
	font-size: 11pt;
	font-weight: bold;
	padding-bottom: -15px;
}

.unit{
    font-weight: bold;
	font-size: 8pt;
	margin-bottom: -10px;
}

.alamat{
	text-align: center;
	font-size: 8pt;
	margin-bottom: -10px;
}

.alamat2{
	text-align: center;
	font-size: 10pt;
	margin-bottom: -10px;
}
.detail{
	font-size: 10pt;
	padding-top: -15px;
	padding-bottom: -12px;
}
body {
	font-family: sans-serif;
}
table {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: none;
	/*border-color: #666666;*/
	border-collapse: collapse;
	width: 100%;
}

th {
	padding-bottom: 10px;
	padding-top: 10px;
	/* font-weight: bold;
	border-color: #666666;
	background-color: #dedede; */
	/*border-bottom: solid;*/
	text-align: left;
}

td {
	text-align: left;
	font-size: 11pt;
	/* border-color: #666666; */
	/* background-color: #ffffff; */
	padding-top: 10px;
}

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
p{
	font-size: 11px;
}
</style>
<body>
	<div style="page-break-after: always;">
		<table style="width: 100%; text-align: center;">
			<tr>
				<td>
					<img src="media/template_kop/<?= ($keterangan['keterangan_kop'] != NULL ? $keterangan['keterangan_kop'] : 'kop_surat.png') ?>" style="height: 110px;">
				</td>
			</tr>
		</table>

		<table style="padding-top: -5px; padding-bottom: 5px">
		    <tr>
				<td  style="width: 100%; text-align: center;">
					<p style="text-align: center; font-size: 11pt; padding-bottom: -15px;" >
						<b><u><?php echo strtoupper($keterangan['keterangan_nama']) ?></u></b> 
						<br>
						Nomor : <?= $keterangan['nomor_surat'] ?>
					</p><br>					
				</td>
		    </tr>
		</table>

		<?php
		//untuk menampilkan data tahun ajaran yang sedang aktif
		$ta   = $this->db->query("SELECT period_start, period_end FROM period WHERE period_status = '1'")->row_array();
		$ta_aktif = $ta[period_start] . '/' . $ta[period_end];
		//ubah pesan agar bisa enter dan merubah pesan untuk mereplace kata kata pada pesan notif
        $surat      = $keterangan['keterangan_isi'];
        $before     = ["*nama_siswa*", "*tempat_lahir*", "*tanggal_lahir*", "*nis*", "*nisn*", 
						"*kelas*", "*alamat*", "*ortu_ibu*", "*ortu_ayah*", "*tahun_ajaran*",
						"*sekolah*","*nama_pegawai*", "*nip*", "*jabatan*", "*unit*"];
        $after      = ["$student[student_full_name]", "$student[student_born_place]", "$student[student_born_date]", "$student[student_nis]","$student[student_nisn]",
						"$student[class_name]", "$student[student_address]","$student[student_name_of_mother]","$student[student_name_of_father]", "$ta_aktif",
						"$setting_school[setting_value]","$employee[employee_name]","$employee[employee_nip]","$employee[position_name]","$employee[majors_name]"];
        $newPhrase  = str_replace($before, $after, $surat);
		
		?>
		<?php echo $newPhrase ?>
		
		
		
		<!-- <table style="width: 100%;">
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					Dikeluarkan di <?= $setting_city['setting_value'] ?> <br>
					Pada Tanggal ,<?php echo pretty_date(date('d-m-Y'), 'd F Y', false) ?>
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">
				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					Kepala Sekolah <br> <?php echo $setting_nama_kepsek['setting_value'];?>
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					<b><?php echo $setting_school['setting_value'] ?></b>
				</td>
			</tr>
		</table> -->
	</div>	
</body>
</html>

<html>
<head>
	<title><?php foreach ($siswa as $row):?> <?php echo ($f['r'] == $row['student_nis']) ? $row['student_full_name'] : '' ?><?php endforeach; ?></title>
</head>

<style type="text/css">
@page {
	margin-top: 0.5cm;
	/*margin-bottom: 0.1em;*/
	margin-left: 1cm;
	margin-right: 1cm;
	margin-bottom: 0.1cm;
}
.name-school{
	text-align: center;
	font-size: 11pt;
	font-weight: bold;
	padding-bottom: -15px;
}

.unit{
    font-weight: bold;
	font-size: 8pt;
	margin-bottom: -10px;
}

.alamat{
	text-align: center;
	font-size: 8pt;
	margin-bottom: -10px;
}

.alamat2{
	text-align: center;
	font-size: 10pt;
	margin-bottom: -10px;
}
.detail{
	font-size: 10pt;
	padding-top: -15px;
	padding-bottom: -12px;
}
body {
	font-family: sans-serif;
}
table {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: none;
	/*border-color: #666666;*/
	border-collapse: collapse;
	width: 100%;
}

th {
	padding-bottom: 10px;
	padding-top: 10px;
	/* font-weight: bold;
	border-color: #666666;
	background-color: #dedede; */
	/*border-bottom: solid;*/
	text-align: left;
}

td {
	text-align: left;
	font-size: 11pt;
	/* border-color: #666666; */
	/* background-color: #ffffff; */
	padding-top: 10px;
}

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
p{
	font-size: 11px;
}
</style>
<body>
	<div style="page-break-after: always;">
		<table style="width: 100%; text-align: center;">
			<tr>
				<td>
					<img src="media/template_kop/<?= ($keterangan['keterangan_kop'] != NULL ? $keterangan['keterangan_kop'] : 'kop_surat.png') ?>" style="height: 110px;">
				</td>
			</tr>
		</table>

		<table style="padding-top: -5px; padding-bottom: 5px">
		    <tr>
				<td  style="width: 100%; text-align: center;">
					<p style="text-align: center; font-size: 11pt; padding-bottom: -15px;" >
						<b><u>Lampiran</u></b> 
						<br>
						<!-- Nomor : <?= $keterangan['nomor_surat'] ?> -->
					</p><br><br><br><br>				
				</td>
		    </tr>
		</table>

		
		<?php echo $keterangan['lampiran_isi'] ?>
		
		
		
		<!-- <table style="width: 100%;">
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					Dikeluarkan di <?= $setting_city['setting_value'] ?> <br>
					Pada Tanggal ,<?php echo pretty_date(date('d-m-Y'), 'd F Y', false) ?>
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">
				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					Kepala Sekolah <br> <?php echo $setting_nama_kepsek['setting_value'];?>
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					<b><?php echo $setting_school['setting_value'] ?></b>
				</td>
			</tr>
		</table> -->
	</div>	
</body>
</html>