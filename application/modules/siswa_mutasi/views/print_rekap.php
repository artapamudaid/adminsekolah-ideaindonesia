<!DOCTYPE html>
<html>
<head>
	<title><?php echo 'Data Rekap Keterangan Siswa Mutasi' ?></title>
</head>
<body>
    <table style='border-bottom: 1px solid #000000; padding-bottom: 10px; width: 277mm;'>
        	<tr valign='top'>
        		<td style='width: 277mm;' valign='middle'>
        			<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value']; ?>
        			</div>
        			<span style='font-size: 8pt;'><?php echo $setting_address['setting_value']; ?>, Telp. 
        	<?php echo $setting_phone['setting_value']; ?></span>
        		</td>
        	</tr>
        </table>
		<br>
		<table style='width: 150mm;'>
			<tr>
			    <td style='width: 70mm; font-size: 8pt; text-align: center' valign='center'></td>
				<td style='width: 50mm; font-size: 8pt; text-align: center' valign='top' align='center'><b>Rekap Data Keterangan Siswa Mutasi</b></td>
				<td style='width: 92mm; font-size: 8pt;' align='right'></td>
			</tr>
		</table>
		<br>
		<table cellpadding='0' cellspacing='0' style='width: 100%;'>
			<tr>
				<th align='center' style='font-size: 8pt; width: 3%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
				<th align='center' style='font-size: 8pt; width: 12%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.Surat Keterangan</th>
				<th align='center' style='font-size: 8pt; width: 15%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama Siswa</th>
				<th align='center' style='font-size: 8pt; width: 12%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>NIS Siswa</th>
				<th align='center' style='font-size: 8pt; width: 8%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Kelas</th>
				<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Sekolah Tujuan</th>
				<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Alamat Sekolah Tujuan</th>
				<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Alasan Pindah</th>
				<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Catatan</th>
				<th align='center' style='font-size: 8pt; width: 5%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Input</th>
			</tr>
				<?php
					$i =1;
					foreach ($siswa_mutasi as $row) {
				?>
					<tr valign='top'>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['siswa_mutasi_no'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_full_name'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_nis'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['class_name'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['sekolah_tujuan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['alamat_sekolah_tujuan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['alasan_pindah'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['catatan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tanggal_input'] ?></td>
					</tr>
				<?php } ?>
		</table>
</body>
</html>