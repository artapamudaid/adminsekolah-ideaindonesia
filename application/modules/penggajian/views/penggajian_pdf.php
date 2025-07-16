<!DOCTYPE html>
<html>
<head>
	<title>
	<?php	
		if($month['month_id']<7){
            $th = $period['period_start'];   
	    } else {
	        $th = $period['period_end'];
	    }
	
	echo 'Laporan-Gaji-Guru-dan-Karyawan-'.$setting_school['setting_value'].'-TA-'.$period['period_start'].'-'.$period['period_end'].'-Bulan-'.$month['month_name'].'-'.$th;
	?>
	</title>
</head>
<body>
    <table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 277mm;'>
	<tr valign='top'>
		<td colspan="7" style='width: 274mm;' valign='middle'>
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
	    <td colspan="1" style='width: 87mm; font-size: 8pt; text-align: center' valign='center'></td>
		<td colspan="5" style='width: 113mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Gaji Guru dan Karyawan-'.$setting_school['setting_value'].' T.A '.$period['period_start'].'/'.$period['period_end']; ?></b></td>
		<td colspan="1" style='width: 87mm; font-size: 8pt;' align='right'></td>
	</tr>
	<tr>
	    <td colspan="1" style='font-size: 8pt; text-align: center' valign='center'></td>
		<td colspan="5" style='font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Bulan : '.$month['month_name'].' '.$th; ?></b></td>
		<td colspan="1" style='font-size: 8pt;' align='right'></td>
	</tr>
</table>
<br>
<table cellpadding='0' cellspacing='0' style='width: 277mm;'>
	<tr>
		<th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>No.</th>
		<th align='center' style='font-size: 8pt; width: 26mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Nama Siswa</th>
		<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Jabatan</th>
		<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Bulan</th>
		<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Gaji</th>
		<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Jumlah Potongan</th>
		<th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border-top: 1px solid #999999; border-bottom: 1px solid #999999;'>Gaji Diterima</th>
	</tr>
	<?php
		$i =1;
		$sumGaji        = 0;
		$sumPotongan    = 0;
		$sumDiterima    = 0;
	    foreach ($report as $row) {
	        $gaji = $row['gaji_pokok'];
	        
		    if($row['gaji_month_id']<7){
                $tahun = $row['period_start'];   
		    } else {
		        $tahun = $row['period_end'];
		    }
		 ?>
			<tr valign='top'>
				<td style='padding: 1px; font-size: 8pt; '><?php echo $i++ ?></td>
				<td style='padding: 1px; font-size: 8pt; '><?php echo $row['employee_name'] ?></td>
                <td style='padding: 1px; font-size: 8pt; '><?php echo $row['position_name'] ?></td>
                <td style='padding: 1px; font-size: 8pt; '><?php echo $row['month_name'].' '.$tahun ?></td>
                <td style='padding: 1px; font-size: 8pt; '>Rp <?php echo number_format($gaji, 0, ",", ".") ?></td>
                <td style='padding: 1px; font-size: 8pt; '>Rp <?php echo number_format($row['gaji_potongan'], 0, ",", ".") ?></td>
                <td style='padding: 1px; font-size: 8pt; '>Rp <?php echo number_format($row['gaji_jumlah'], 0, ",", ".") ?></td>
			</tr>
    <?php 
            $sumGaji        += $gaji;
			$sumPotongan    += $row['gaji_potongan'];
			$sumDiterima    += $row['gaji_jumlah'];
        } 
    ?>
    <tr style="background-color: #E2F7FF;">
        <td style='padding: 1px; font-size: 8pt;' align="center" colspan="3"><b>Total</b></td>
        <td style='padding: 1px; font-size: 8pt;' ></td>
        <td style='padding: 1px; font-size: 8pt;'>Rp <?php echo number_format($sumGaji, 0, ",", ".") ?></td>
        <td style='padding: 1px; font-size: 8pt;'>Rp <?php echo number_format($sumPotongan, 0, ",", ".") ?></td>
        <td style='padding: 1px; font-size: 8pt;'>Rp <?php echo number_format($sumDiterima, 0, ",", ".") ?></td>
    </tr>
    <tr>
		<td colspan='4' align='right' style='font-size: 8pt; padding: 1px; border-top: 1px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
		<td colspan='2' style='font-size: 8pt; padding: 1px; border-top: 1px solid #999999; border-bottom: 0px solid #999999;' align='right'><br><br></td>
		<td align='right' style='font-size: 8pt; padding: 1px; border-top: 1px solid #999999; border-bottom: 0px solid #999999;'> <br><br></td>
	</tr>
	<tr>
		<td colspan='4' align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
		<td colspan='2' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php echo $setting_city['setting_value']; ?>, <?php echo pretty_date(date('Y-m-d'),'d F Y',false); ?></td>
		<td align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
	</tr>
	<tr>
		<td colspan='4' align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
		<td colspan='2' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='right'><br><br></td>
		<td align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
	</tr>
	<tr>
		<td colspan='4' align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
		<td colspan='2' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='right'><br><br></td>
		<td align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
	</tr>
	<tr>
		<td colspan='4' align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
		<td colspan='2' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php 
		        echo ucfirst($setting_nama_bendahara['setting_value']);?></td>
		<td align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
	</tr>
	<tr>
		<td colspan='4' align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
		<td colspan='2' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php 
		        echo 'NIP. '.ucfirst($setting_nip_bendahara['setting_value']);?></td>
		<td align='right' style='font-size: 8pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
	</tr>
</table>
</body>
</html>