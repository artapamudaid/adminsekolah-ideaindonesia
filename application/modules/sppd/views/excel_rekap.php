<?php

    $filename = 'Data_Rekap_Surat_Perjalanan_Dinas_(SPD)';    

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
			<th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
			<th align='center' style='font-size: 8pt; width: 25mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No. SPD</th>
			<th align='center' style='font-size: 8pt; width: 35mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama Pemberi Perintah</th>
			<th align='center' style='font-size: 8pt; width: 35mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Petugas Yang diperintahkan</th>
			<th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Nama Anggota</th>
			<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tempat Kegiatan</th>
			<th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Lama Perjalanan</th>
			<th align='center' style='font-size: 8pt; width: 30mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Maksud Kegiatan</th>
		</tr>
		    <?php
			    $i =1;
			    foreach ($sppd as $row) {
			?>
				<tr valign='top'>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['no_sppd'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['nama_perintah'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['nama_diperintah'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'>
						<?php
							if($row['anggota_id'] != ''){
								?>
								<ol>
									<?php  
									$pengikut 	= $row['anggota_id'];
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
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tmp_tujuan'] ?></td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['lama_perjalanan'] ?> Hari (<?php echo $row['tgl_berangkat'] ?> S/D <?php echo $row['tgl_kembali'] ?>)</td>
					<td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['deskripsi'] ?></td>
				</tr>
			<?php } ?>
	</table>