<!DOCTYPE html>
<html>
<head>
	<title><?php echo 'Data Rekap Surat Lainnya Guru' ?></title>
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
				<td style='width: 50mm; font-size: 8pt; text-align: center' valign='top' align='center'><b>Rekap Data Lainnya Guru</b></td>
				<td style='width: 92mm; font-size: 8pt;' align='right'></td>
			</tr>
		</table>
		<br>
		<table cellpadding='0' cellspacing='0' style='width: 277mm;'>
			<tr>
				<th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
				<th align='center' style='font-size: 8pt; width: 25mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.Surat Mengajar</th>
				<th align='center' style='font-size: 8pt; width: 35mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>NIP Pegawai</th>
				<th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama Pegawai</th>
				<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Jabatan</th>
				<th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Catatan</th>
				<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Input</th>
			</tr>
				<?php
					$i =1;
					foreach ($lainnya_guru as $row) {
				?>
					<tr valign='top'>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['lainnya_guru_no'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['employee_nip'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['employee_name'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['position_name'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['catatan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tanggal_input'] ?></td>
					</tr>
				<?php } ?>
		</table>
</body>
</html>