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
	
	<table>
	    <tr>
	        <td width="15%">
	            <img src="<?php echo 'uploads/school/' . logo() ?>" style="height: 100px;">
	        </td>
	        <td>
            	<p class="name-school"><?php echo strtoupper($setting_school['setting_value']) ?></p>
                <p class="alamat2"><?php echo $setting_address['setting_value'] ?><?php echo ' Telp. '.$setting_phone['setting_value'] ?></p>
        	</td>
	        <td width="15%">
	        </td>
	    </tr>
	</table>
		<hr>

		<table style="padding-top: -5px; padding-bottom: 5px">
		    <tr>
		    <td><p class="name-school"><?php echo "LEMBAR DISPOSISI" ?></p>
		    </td>
		    </tr>
		</table>
		<br>
		<hr>
		<strong>  
		<table style="padding-top: -5px; padding-bottom: 5px; ">
			<tbody>
				<tr>
					<td style="width: 250px;">1. Surat Dari</td>
					<td style="width: 25px;">:</td>
					<td><?php echo $surat_masuk['asal_surat']?></td>
				</tr>
				<tr>
					<td style="width: 250px;">2. Nomor Surat</td>
					<td style="width: 5px;">:</td>
					<td><?php echo $surat_masuk['no_surat']?></td>
				</tr>
				<tr>
					<td style="width: 250px;">3. Tanggal Surat</td>
					<td style="width: 5px;">:</td>
					<td><?php echo $surat_masuk['tgl_surat']?></td>
				</tr>
				<tr>
					<td style="width: 250px;">4. Perihal</td>
					<td style="width: 5px;">:</td>
					<td><?php echo $surat_masuk['isi']?></td>
				</tr>
			</tbody>
		</table>
		</strong>
		<br>
		<hr>
		<strong>  
		<table style="padding-top: -5px; padding-bottom: 5px; ">
			<tbody>
				<tr>
					<td style="width: 250px;">1. Tanggal Terima</td>
					<td style="width: 25px;">:</td>
					<td><?php echo $surat_masuk['tgl_diterima']?></td>
				</tr>
				<tr>
					<td style="width: 250px;">2. Sifat</td>
					<td style="width: 5px;">:</td>
					<td><?php echo $surat_masuk['sifat']?></td>
				</tr>
				<tr>
					<td style="width: 250px;">3. Disposisi Kepada</td>
					<td style="width: 5px;">:</td>
					<td>
						<ol>
							<?php  
							$tujuan = explode(",",$surat_masuk['tujuan']);
							foreach ($tujuan as $string) {
								echo '<li>'.$string.'</li>';
							}
							?>
						</ol>
					</td>
				</tr>
				<tr>
					<td style="width: 250px;">4. Catatan</td>
					<td style="width: 5px;">:</td>
					<td><?php echo $surat_masuk['catatan']?></td>
				</tr>
			</tbody>
		</table>
		</strong>
		<br>
		<hr>
		<strong>  
		<table style="padding-top: -5px; padding-bottom: 5px; ">
			<tbody>
				<tr>
					<td style="width: 250px;">Isi Disposisi</td>
					<td style="width: 25px;">:</td>
					<td></td>
				</tr>
				<tr>
					<td style="width: 250px;"></td>
					<td style="width: 5px;"></td>
					<td><?php echo $surat_masuk['isi_disposisi']?></td>
				</tr>
			</tbody>
		</table>
		</strong>
		<br>
		<br>
		<br>
		<table>
			
			<tr>
				<td colspan="2" style="width: 350px;">&nbsp;</td>
				<td style="text-align: center;">Dikeluarkan di Pada Tanggal ,</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td style="text-align: center"><?php echo pretty_date(date('d-m-Y'), 'd F Y', false) ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td style="text-align: center;">&nbsp; an , <?php echo $surat_masuk['user_full_name'];?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td style="text-align: center"><b><?php echo $setting_school['setting_value'] ?></b></td>
			</tr>
		</table>
		
	</body>
	</html>