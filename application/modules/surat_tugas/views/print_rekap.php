<!DOCTYPE html>
<html>
<head>
	<title><?php echo 'Data Rekap Surat Tugas' ?></title>
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
				<td style='width: 50mm; font-size: 8pt; text-align: center' valign='top' align='center'><b>Rekap Data Surat Tugas</b></td>
				<td style='width: 92mm; font-size: 8pt;' align='right'></td>
			</tr>
		</table>
		<br>
		<table cellpadding='0' cellspacing='0' style='width: 70mm;'>
			<tr>
				<th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
				<th align='center' style='font-size: 8pt; width: 25mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.Surat Tugas</th>
				<th align='center' style='font-size: 8pt; width: 35mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama PJ</th>
				<th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama Anggota</th>
				<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tempat Kegiatan</th>
				<th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Alamat</th>
				<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Kegiatan</th>
				<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Maksud Kegiatan</th>
			</tr>
				<?php
					$i =1;
					foreach ($surat_tugas as $row) {
				?>
					<tr valign='top'>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['no_surat_tugas'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['employee_name'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'>
							<?php
								if($row['pengikut_id'] != ''){
									?>
									<ol>
										<?php  
										$pengikut 	= $row['pengikut_id'];
										$id 		= explode("," , $pengikut);
										$data 		= $this->db->query("SELECT employee_name, employee_nip FROM employee WHERE employee_id in ($pengikut)")->result_array();
										foreach ($data as $string) {
											echo '<li>'.$string['employee_name'].' ( '.$string['employee_nip']. ')</li>';
										}
										?>
									</ol>
									<?php
								}
							?>
						</td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tmp_kegiatan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['alamat_kegiatan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tgl_kegiatan'] ?></td>
						<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['deskripsi'] ?></td>
					</tr>
				<?php } ?>
		</table>
</body>
</html>