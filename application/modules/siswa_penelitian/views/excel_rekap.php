<?php

    $filename = 'Data_Rekap_Penelitian_dan_Observasi_Siswa';    

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename = ".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

<table style='border-bottom: 1px solid #000000; padding-bottom: 10px; width: 277mm;'>
    	<tr valign='top'>
    		<td colspan='9' style='width: 277mm;' valign='middle'>
    			<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value']; ?>
    			</div>
    			<span style='font-size: 8pt;'><?php echo $setting_address['setting_value']; ?>, Telp. 
    	<?php echo $setting_phone['setting_value']; ?></span>
    		</td>
    	</tr>
    </table>
	<br>
	<table style='width: 277mm;'>
		<tr>
		    <td colspan='2' style='width: 92mm; font-size: 8pt; text-align: center' valign='center'></td>
			<td colspan='5' style='width: 93mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo str_replace('_',' ',$filename); ?></b></td>
			<td colspan='2' style='width: 92mm; font-size: 8pt;' align='right'></td>
		</tr>
	</table>
	<br>
	<table cellpadding='0' cellspacing='0' style='width: 277mm;'>
		<tr>
			<th align='center' style='font-size: 8pt; width: 3%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
			<th align='center' style='font-size: 8pt; width: 12%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.Surat Keterangan</th>
			<th align='center' style='font-size: 8pt; width: 15%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama Siswa PJ (NIS/Kelas)</th>
			<th align='center' style='font-size: 8pt; width: 12%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Anggota</th>
			<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Instansi Tujuan</th>
			<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Alamat Instansi</th>
			<th align='center' style='font-size: 8pt; width: 10%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Catatan</th>
			<th align='center' style='font-size: 8pt; width: 5%; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Input</th>
		</tr>
			<?php
				$i =1;
				foreach ($siswa_penelitian as $row) {
			?>
				<tr valign='top'>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:3%;'><?php echo $i++ ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:12%;'><?php echo $row['siswa_penelitian_no'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:25%;' ><?php echo $row['student_full_name'] ?> (<?php echo $row['student_nis'] ?>/<?php echo $row['class_name'] ?>)</td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:15%;'>
						<?php
							if($row['anggota_id'] != ''){
								?>
								<ol>
									<?php  
									$pengikut 	= $row['anggota_id'];
									$id 		= explode("," , $pengikut);
									$data 		= $this->db->query("SELECT student_full_name, student_nis FROM student WHERE student_id in ($pengikut)")->result_array();
									foreach ($data as $string) {
										echo '<li>'.$string['student_full_name'].' ( '.$string['student_nis']. ')</li>';
									}
									?>
								</ol>
								<?php
							}
						?>	
					</td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:10%;'><?php echo $row['nama_instansi'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:10%;'><?php echo $row['alamat_instansi'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000; width:15%;'><?php echo $row['catatan'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tanggal_input'] ?></td>
				</tr>
			<?php } ?>
	</table>