<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Mutasi_H2H_BMI_".$tanggal_title."_Status_".$status_title.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 277mm;'>
        	<tr valign='top'>
        		<td colspan="12" valign='middle'>
        			<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value']; ?>
        			</div>
        			<span style='font-size: 10pt;'><?php echo $setting_address['setting_value']; ?>, Telp. 
        			<?php echo $setting_phone['setting_value']; ?></span>
        		</td>
        	</tr>
        </table>
		<br>
		<table style='width: 277mm;'>
			<tr>
			    <td colspan="1" style='width: 92mm; font-size: 10pt; text-align: center' valign='center'></td>
				<td colspan="8" style='width: 93mm; font-size: 10pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Mutasi H2H BMI '.$tanggal; ?></b></td>
				<td colspan="3" style='width: 92mm; font-size: 10pt;' align='right'></td>
			</tr>
		</table>
		<br>
		<br>
		<table cellpadding='0' cellspacing='0' style='width: 277mm;' border='1'>
			<tr>
				<th align='center' style='font-size: 10pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No.</th>
				<th align='center' style='font-size: 10pt; width: 25mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Tanggal</th>
				<th align='center' style='font-size: 10pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No. Ref</th>
				<th align='center' style='font-size: 10pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No. VA</th>
				<th align='center' style='font-size: 10pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nama</th>
				<th align='center' style='font-size: 10pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;' colspan="2">Nominal</th>
				<th align='center' style='font-size: 10pt; width: 40mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Status</th>
			</tr>
    		<?php
    			$i =1;
    			$sumMutasi = 0;
    			foreach ($mutasi as $row) {
    			 ?>
					<tr valign='top'>
						<td align='center' style='padding: 1px; font-size: 10pt; '><?php echo $i++ ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $row->DATEPAY ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $row->REFNO ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo "'".$row->VANO ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $row->CUSTNAME ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo 'Rp' ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; text-align: right;'><?php echo number_format($row->NOMINAL,"0",",",".") ?></td>
						<td align='left' style='padding: 1px; font-size: 10pt; '><?php echo $row->STATUS ?></td>
					</tr>
				<?php 
					 $sumMutasi += $row->NOMINAL;
					}
				?>
			<tr>
				<td colspan='6' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><b>Total</b></td>
				<td style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><b>Rp <?php echo number_format($sumMutasi,0,",",".") ?></b></td>
				<td align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
			</tr>
			<tr>
				<td colspan='6' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
				<td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php echo $setting_city['setting_value']; ?>, <?php echo pretty_date(date('Y-m-d'),'d F Y',false); ?></td>
			</tr>
			<tr>
				<td colspan='8' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
			</tr>
			<tr>
				<td colspan='8' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
			</tr>
			<tr>
				<td colspan='6' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
				<td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php 
				        echo ucfirst($setting_nama_bendahara['setting_value']).'<p></p>NIP. '.ucfirst($setting_nip_bendahara['setting_value']);?></td>
			</tr>
		</table>