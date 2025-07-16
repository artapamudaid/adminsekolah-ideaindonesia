<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Calon_Pelanggan_AdminSekolahmu_Tgl_" . $dari_tanggal . "_Sampai_" . $sampai_tanggal . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
		<table cellpadding='0' cellspacing='0' style='width: 277mm;'>
			<tr>
				<td align='center' colsepan='9' style='width: 25mm; font-size: 8pt;'>List Calon Pelanggan Admin Sekolahmu</td>
			</tr>
			<tr>
				<td align='center' colsepan='9' style='width: 25mm; font-size: 8pt;'>Tanggal : <?php echo $dari_tanggal . 's/d' . $sampai_tanggal; ?></td>
			</tr>
		</table>
		<br>
		<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
			<tr>
				<th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No.</th>
				<th align='center' style='font-size: 8pt; width: 25mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nama</th>
				<th align='center' style='font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nama Sekolah</th>
				<th align='center' style='font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Alamat</th>
				<th align='center' style='font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Kota</th>
				<th align='center' style='font-size: 8pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Provinsi</th>
				<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No. HP/Whatsapp</th>
				<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Email</th>
				<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Tgl Daftar</th>
			</tr>
    		<?php
    			$i =1;
    			foreach ($registrasi as $row) {
    			 ?>
					<tr valign='top'>
						<td align='center' style='padding: 1px; font-size: 8pt; '><?php echo $i++ ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo $row['registrasi_name'] ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo $row['registrasi_school'] ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo $row['registrasi_address'] ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo $row['city_name'] ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo $row['province_name'] ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo str_replace("+62", "'0", $row['registrasi_phone']) ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo $row['registrasi_email'] ?></td>
						<td align='left' style='padding: 1px; font-size: 8pt; '><?php echo pretty_date($row['registrasi_date'], 'd-m-Y', false); ?></td>
					</tr>
		    <?php } ?>
		</table>