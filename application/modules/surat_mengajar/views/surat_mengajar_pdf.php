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
</style>
<body>
	<div style="page-break-after: always;">
		<table style="width: 100%;">
			<tr>
				<td style="width: 60%; text-align: left; font-size: 6px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 6px;">
				LAMPIRAN 1 <br>
				<?= wordwrap("PERATURAN MENTERI KEUANGAN REPUBLIK INDONESIA NOMOR 113/PMK.05/2012",100,"<br>\n")?> <br>
				<?= wordwrap("TENTANG PERJALANAN DINAS JABATAN DALAM NEGERI BAGI PEJABAT NEGARA, PEGAWAI NEGERI, DAN PEGAWAI TIDAK TETAP",60,"<br>\n")?>
				</td>
			</tr>
		</table>
		<table style="width: 100%; text-align: center;">
			<tr>
				<td>
					<img src="media/template_kop/kop_surat.png" style="height: 110px;">
				</td>
			</tr>
		</table>

		<table style="padding-top: -5px; padding-bottom: 5px">
		    <tr>
				<td  style="width: 100%; text-align: center;">
					<p class="name-school"><?php echo "SURAT PERJALANAN DINAS <br> (SPD)" ?></p>
				</td>
		    </tr>
		</table>
		
		<table style="width: 100%;" border="1px">
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">1</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">Kuasa Pengguna Anggaran</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2"><?php echo $sppd['nama_perintah']?></td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">2</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">Nama/NIP yang melaksanakan perjalanan dinas</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2"><?php echo $sppd['nama_diperintah']?> / Peg.id <?php echo $sppd['employee_nip']?></td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">3</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<ol type='a'>
						<li>Pangkat dan Golongan</li>
						<li>Jabatan/Instansi</li>
						<li>Tingkat Biaya Perjalanan Dinas</li>
					</ol>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2"> 
					<ol type='a'>
						<li>-</li>
						<li><?php echo $sppd['position_name']?></li>
						<li>belum ada</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">4</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">Maksud Perjalanan Dinas</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2"><?php echo $sppd['deskripsi']?></td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">5</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">Alat angkutan yang dipergunakan</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2"><?php echo $sppd['transportasi']?></td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">6</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<ol type='a'>
						<li>Tempat berangkat</li>
						<li>Tempat tujuan</li>
					</ol>					
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2">
					<ol type='a'>
						<li><?php echo $sppd['tmp_berangkat']?></li>
						<li><?php echo $sppd['tmp_tujuan']?></li>
					</ol>
				</td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">7</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;"> 
					<ol type='a'>
						<li>Lamanya Perjalanan Dinas</li>
						<li>Tanggal berangkat</li>
						<li>Tanggal harus kembali/tiba di tempat baru*</li>
					</ol>	
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2">
					<ol type='a'>
						<li><?php echo $sppd['lama_perjalanan']?></li>
						<li><?php echo pretty_date($sppd['tgl_berangkat'], 'd F Y', false)?></li>
						<li><?php echo pretty_date($sppd['tgl_kembali'], 'd F Y', false) ?></li>
					</ol>
				</td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">8</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">Pengikut :	Nama</td>
				<td style="width: 25%; font-size: 11px;">Tanggal Lahir</td>
				<td style="width: 20%; font-size: 11px;">Keterangan</td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;"></td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<ol><?php  
						$pengikut = $sppd['anggota_id'];
						$id = explode("," , $pengikut);
						$employee = $this->db->query("SELECT employee_name, employee_nip FROM employee WHERE employee_id in ($pengikut)")->result_array();
						foreach ($employee as $string) {
							echo '<li>'.$string['employee_name'].'</li>';
						}
						?>
					</ol>
				</td>
				<td style="width: 25%; font-size: 11px;">
					<ol>
						<?php  
						$pengikut = $sppd['anggota_id'];
						$id = explode("," , $pengikut);
						$employee = $this->db->query("SELECT employee_born_date FROM employee WHERE employee_id in ($pengikut)")->result_array();
						foreach ($employee as $string) {
							echo '<li>'.pretty_date($string['employee_born_date'], 'd F Y', false).'</li>';
						}
						?>
					</ol>
				</td>
				<td style="width: 20%; font-size: 11px;">
					<ol>
						<?php  
						$pengikut = $sppd['anggota_id'];
						$id = explode("," , $pengikut);
						$employee = $this->db->query("SELECT employee_born_date FROM employee WHERE employee_id in ($pengikut)")->result_array();
						foreach ($employee as $string) {
							echo '<li>-</li>';
						}
						?>
					</ol>
				</td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">9</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					Pembebanan Anggaran
					<ol type='a'>
						<li>Instansi</li>
						<li>Akun</li>
					</ol>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2">
					<ol type='a'>
						<li>Instansi</li>
						<li>Akun</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td style="width: 3%; font-size: 11px; padding-bottom: 10px; padding-left:5px;">10</td>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">Keterangan lain-lain</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;" colspan="2"></td>
			</tr>
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					Dikeluarkan di Pada Tanggal ,
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
				<?php echo pretty_date(date('d-m-Y'), 'd F Y', false) ?>
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
					an , <?php echo $sppd['user_full_name'];?>
				</td>
			</tr>
			<tr>
				<td style="width: 60%; text-align: left; font-size: 11px;">

				</td>
				<td style="width: 40%; text-align: left; font-size: 11px;">
					<b><?php echo $setting_school['setting_value'] ?></b>
				</td>
			</tr>
		</table>
		
	</div>
	<div style="page-break-after: always;">
		<table style="padding-top: -5px; padding-bottom: 5px">
		    <tr>
				<td  style="width: 100%; text-align: center;">
					<p class="name-school"><br><?php echo "-2-" ?><br></p>
				</td>
		    </tr>
		</table>
		<table style="width: 100%;" border="1px">
			<tr>
				<td style="width: 50%; font-size: 11px; padding-left:5px;"></td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">I</td>
							<td style="font-size: 11px; padding-left:5px;">Beragkat dari(Tempat Kedudukan)</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Ke</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								Kepala, 
								<br><br><br><br>
								<?php echo $sppd['nama_perintah']?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">II</td>
							<td style="font-size: 11px; padding-left:5px;">Tiba Di</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Berangkat Dari</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Ke</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">III</td>
							<td style="font-size: 11px; padding-left:5px;">Tiba Di</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Berangkat Dari</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Ke</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">IV</td>
							<td style="font-size: 11px; padding-left:5px;">Tiba Di</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Berangkat Dari</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Ke</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">V</td>
							<td style="font-size: 11px; padding-left:5px;">Tiba Di</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Berangkat Dari</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Ke</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Pada Tanggal</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td style="font-size: 11px; padding-left:5px;"></td>
							<td style="font-size: 11px; padding-left:5px;">Kepala</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size: 11px; padding-left:5px;" colspan="3">
								<br><br><br><br>
								(......................................................) <br>
								NIP.								
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; font-size: 11px; padding-left:5px;">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">VI</td>
							<td style="font-size: 11px; padding-left:5px;">Catatan Lainnya</td>
							<td style="font-size: 11px; padding-left:5px;">:</td>
							<td style="font-size: 11px; padding-left:5px;"></td>
						</tr>
					</table>
				</td>
				<td style="width: 45%; font-size: 11px; padding-left:5px;">
				</td>
			</tr>
			<tr>
				<td style="width: 95%; font-size: 11px; padding-left:5px;" colspan="2">
					<table>
						<tr>
							<td style="font-size: 11px; padding-left:5px;">VII</td>
							<td style="font-size: 11px; padding-left:5px;">PERHATIAN : <br>
							PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, <br> serta bendahara pengeluaran bertanggungjawab berdasarkan peraturan-peraturan Keuangan Negara apabila Negara menderita rugi akibat <br> kesalahan, kelalaian, dan kealpaannya.
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>	
</body>
</html>