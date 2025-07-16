<!DOCTYPE html>
<html>
<head>
	<title><?php echo 'Surat_siswa_ket_aktif_keluar_' . $row['student_full_name'] ?></title>
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
	text-align:center;
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
	margin-bottom: 10px;
}
.detail{
	font-size: 8pt;
	font-weight: bold;
	padding-top: -15px;
	padding-bottom: -12px;
}
body {
	font-family: sans-serif;
	/*font-family: dotmatrx;*/
}
table {
	/*font-family: dotmatrx;*/
	font-family: verdana,arial,sans-serif;
	font-size:15px;
	color:#000000;
	border-width: none;
	/*border-color: #ffffff;*/
	border-collapse: collapse;
	width: 100%;
	/* text-align: center; */
}

th {
	/* text-align: center;
	padding-bottom: 2px;
	padding-top: 2px;
	border-color: #ffffff;
	background-color: #ffffff; */
	/*border-bottom: solid;*/
}

td {
	/* text-align: center;
	padding-bottom: 2px; */
	/* padding-top: 2px; */
	border-color: #ffffff;
	background-color: #ffffff;
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
	font-size: 8px;
	border-width: thin;
	padding: 5px;
}
.topright2 {
	position: absolute;
	top: 30px;
	right: 50px;
	font-size: 8px;
	border: 1px solid;
	padding: 5px;
	color: red;
}
</style>

<style>
img {
  filter: grayscale(100%);
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
	<table>
	    <tr>
	        <td><p class="name-school" style="margin-left: 190px;"><u> SURAT KETERANGAN SISWA AKTIF </u><br><?= $siswa_ket_aktif['siswa_ket_aktif_no'] ?></p></td>
	    </tr>
	</table>
		<br><br>
		<table>
			<tr>
				<td > Yang bertanda tangan dibawah ini kepala Sekolah <?= $setting_school['setting_value'] ?> dengan ini menerangkan bahwa : <br></td>
			</tr>
		</table>
		<br>
		<table >
				<tr>
					<th style="width: 10%;">NIS</th>
					<td style="width: 2%;">:</td>
					<td><?php echo $siswa_ket_aktif['student_nis'] ?></td>
				</tr>
				<tr>
					<th style="width: 10%;">Nama</th>
					<td style="width: 2%;">:</td>
					<td><?php echo $siswa_ket_aktif['student_full_name'] ?></td>
				</tr>
				<tr>
					<th style="width: 10%;">Kelas</th>
					<td style="width: 2%;">:</td>
					<td><?php echo $siswa_ket_aktif['class_name'] ?></td>
				</tr>
			
		</table>
		<br>		
		<table>
			<tr>
				<td > Adalah benar-benar siswa <?= $setting_school['setting_value'] ?> dan tercatat aktif sebagai siswa kelas <?= $siswa_ket_aktif['class_name'] ?></td>
			</tr>
		</table>	
		<table>
			<tr>
				<td > Demikian Surat Keterangan ini dibuat dengan sebenarnya, dan diberikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya ?></td>
			</tr>
		</table>
		<br><br>
		<br><br>
		<table>
			<?php 
			    // $user = $this->db->query("SELECT users.user_full_name AS petugas FROM users JOIN siswa_ket_aktif ON siswa_ket_aktif.siswa_ket_aktif_user_id = users.user_id WHERE siswa_ket_aktif_id = '" . $siswa_ket_aktif['siswa_ket_aktif_id'] . "'")->row_array();
			?>
			<tr>
				<td style="width: 60%; text-align: center">&nbsp;</td>
		        <td style="width: 40%; text-align: center">
		            <?php echo $setting_city['setting_value'] ?>, <?php echo pretty_date(date('Y-m-d'),'d F Y',false) ?>
		        </td>
		    </tr>
			<tr>
				<td style="width: 60%; text-align: center">&nbsp;</td>
				<td >&nbsp;</td>
			</tr>
		    
			<tr>
				<td style="width: 60%; text-align: center">&nbsp;</td>
				<td  style="width: 40%; text-align: center">Pemberi siswa_ket_aktif</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: center">&nbsp;</td>
				<td  >&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: center">&nbsp;</td>
				<td >&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: center">&nbsp;</td>
				<td  style="width: 40%; text-align: center"><b><?php echo ucfirst($setting_nama_kepsek['setting_value']); ?></b></td>
			</tr>
		</table>
		<br>
	</body>
	</html>