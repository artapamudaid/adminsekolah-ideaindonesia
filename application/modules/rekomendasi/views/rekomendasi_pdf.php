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
				<td style="width: 100%; text-align: center;"><h3><u>REKOMENDASI</u></h3></td>
			</tr>
			<tr>
				<td style="width: 100%; text-align: center;">Nomor : <?= $surat_rekomendasi['rekomendasi_nomor_surat_id']?></td>
			</tr>
		</table>
		<br><br><br><br>

		<table style="width: 100%;">
			<tr>
				<td style="margin-right:500px;">
						Kepala Madrasah Aliyah Negeri (MAN) 1 Kota Serang, Merekomendasikan siswa/i MAN 1 Kota Serang <br>
						dengan sebagai berikut : <br><br>
				</td>
			</tr>
			<tr>
				<td>
					<table border=1px; width="100%";>
						<tr>
							<th style="width: 50px; height: 30px; text-align: center; background-color:green;">No</th>
							<th style="width: 300px; height: 30px; text-align: center; background-color:green;">Nama Peserta Utusan</th>
							<th style="width: 300px; height: 30px; text-align: center; background-color:green;">Kelas</th>
						</tr>
							<?php  
							$pengikut 	= $surat_rekomendasi['rekomendasi_student_id'];
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
				<td><br><br>
					Untuk mengikuti Kegiatan <?= $surat_rekomendasi['rekomendasi_desc'] ?> yang akan diselenggarakan pada :
					<br><br><br>
					<table>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px; ">Hari/Tanggal </td>
							<td>:</td>
							<td>
								<?= pretty_date($surat_rekomendasi['rekomendasi_date_start'], 'l', false) .' S.d '. 
									pretty_date($surat_rekomendasi['rekomendasi_date_start'], 'l', false) .' / '.
									pretty_date($surat_rekomendasi['rekomendasi_date_start'], 'd', false) .'S.d'.
									pretty_date($surat_rekomendasi['rekomendasi_date_end'], 'd F Y', false)?></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px;">Waktu </td>
							<td>:</td>
							<td><?= $surat_rekomendasi['rekomendasi_time_start'] .' - '. $surat_rekomendasi['rekomendasi_time_end'] ?></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
							<td style="width: 200px; height:  20px;">Tempat</td>
							<td>:</td>
							<td><?= wordwrap($surat_rekomendasi['rekomendasi_lokasi'] ,50,"<br>\n")?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
				<br><br>
				Demikian Surat Rekomendasi ini dibuat untuk digunakan sebagaimana mestinya.
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