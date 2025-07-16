<!DOCTYPE html>
<html>
<head>
	<title>Slip Gaji</title>
	<?php
	
    $this->load->helper(array('terbilang'));
    
	    if($print['month_id']>0 && $print['month_id']<7){
	        $tahun = $print['period_start'];
	    }
	    else if($print['month_id']>6 && $print['month_id']<13){
	        $tahun = $print['period_end'];
	    } else {
	        $tahun = '?';
	    }
	    
	    if($print['employee_start']!='0000-00-00'){
	        $start = date_create($print['employee_start']);
	    }
	    else{
	        $start = date_create();
	    }
	    
	    if($print['employee_end']!='0000-00-00'){
	        $end = date_create($print['employee_end']);
	    }
	    else{
	        $end = date_create();
	    }
        $interval = date_diff($start, $end);
        if($interval->y == '0'){
            $masa = '-';     
        } else {
            $masa = $interval->y.' tahun'; 
        }   
	?>
</head>
<body>
    <table style='border-bottom: 1px solid black; padding-bottom: 10px; width: 175mm;'>
        	<tr valign='middle'>
        		<td style='width: 82mm;' valign='middle'>
        			<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
        			</div>
        			<span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp. 
        	<?php echo $setting_phone['setting_value'] ?></span>
        		</td>
        		<td style='width: 35mm;' valign='middle'>
        		</td>
        		<td style='width: 43mm;' valign='middle'>
        			<div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt; text-align:center'><b>SLIP GAJI</b>
        			</div>
        			<span><div style='font-size: 8pt; text-align:center'>
                  <?php
                    $tanggal = date_create($print['gaji_tanggal']);
                    $dformat = date_format($tanggal,'dmYHis');
                  ?>
                   <img style="width:142.56pt;height:18pt;z-index:6;" src="<?php echo 'media/barcode_fee/'.$print['kredit_kas_noref'].'.png' ?>" alt="Image_4_0" /><br>
                   <font size="12px"><?php echo $dformat.$print['employee_nip']; ?></font>
        			</div></span>
        		</td>
        	</tr>
        </table>
		<br>
		<table style='border-bottom: 1px solid black; width: 175mm;'>
		    <tr>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'>Unit</td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='width: 40mm; font-size: 8pt; text-align: left;' valign='middle'><?php echo $print['majors_short_name']; ?></td>
			    <td style='width: 5mm; font-size: 8pt; text-align: left;' valign='middle'><?php echo ''; ?></td>
				<td style='width: 7mm; font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='width: 25mm; font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'>Bulan</td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='width: 33mm; font-size: 8pt; text-align: left;' valign='middle'><?php echo strtoupper($print['month_name']).' '.$tahun; ?></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'>Nama</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><?php echo $print['employee_name']; ?></td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><?php echo ''; ?></td>
				<td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'>Status</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><?php echo ($print['employee_category']=='1')?'Tetap':'Tidak Tetap'; ?></td>
			</tr>
			<tr>
			    
			    <td style='font-size: 8pt; text-align: left;' valign='middle'>Jabatan</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><?php echo $print['position_name']; ?></td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><?php echo ''; ?></td>
				<td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: left;' valign='centeer'>Masa Kerja</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><?php echo $masa; ?></td>
			</tr>
		</table>
		<table cellpadding='0' cellspacing='0' style='width: 175mm;'>
			<tr>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 10mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 5mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 17mm; font-size: 8pt; text-align: right; ' valign='middle'><br></td>
				<td style='width: 8mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 10mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?> </td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: right;' valign='middle'></td>
				<td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td colspan='3' style='font-size: 8pt; text-align: left; ' valign='middle'><b>Total Gaji (Kotor)</b> </td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><b>Rp</b></td>
			    <td style='font-size: 8pt; text-align: right;' valign='middle'><b><?php echo number_format($print['gaji_pokok'],0,',','.'); ?></b></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'>Rincian Gaji</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'>:</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: right; ' valign='middle'></td>
				<td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: left; ' valign='middle'>Rincian Potongan : </td>
			    <td colspan='3' style='font-size: 8pt; text-align: left;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: right;' valign='middle'></td>
			</tr>
		</table>
		<table>
			<tr>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 10mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 5mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 17mm; font-size: 8pt; text-align: right;' valign='middle'><br></td>
				<td style='width: 8mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 10mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 5mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			</tr>
			<tr>
				<td colspan='6' style='width: 87.5mm;'>
					<table cellpadding='0' cellspacing='0' style='width: 87.5mm;'>
						<?php
							foreach($print_gaji AS $row){
						?>
							<tr>
							<td style='font-size: 8pt; text-align: left;' valign='middle'><?= $row['gaji_slip_name'] ?></td>
							<td style='font-size: 8pt; text-align: center; width: 30mm;'>:</td>
							<td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
							<td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
							<td style='font-size: 8pt; text-align: center;' valign='middle'>Rp</td>
							<td style='font-size: 8pt; text-align: right; width: 10mm;' valign='middle'><?= $row['gaji_slip_nominal'] ?></td>
							</tr>
						<?php
							}
						?>
					</table>
				</td>
				<td colspan='7'>
					<table>
						<?php
							foreach($print_potongan AS $rows){
						?>
							<tr>
							<td style='font-size: 8pt; text-align: left; width: 45mm; ' valign='middle'><?= $rows['potongan_slip_name'] ?></td>
							<td style='font-size: 8pt; text-align: center; width: 10mm;'>:</td>
							<td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
							<td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo ''; ?></td>
							<td style='font-size: 8pt; text-align: center;' valign='middle'>Rp</td>
							<td style='font-size: 8pt; text-align: right; width: 15mm;' valign='middle'><?= $rows['potongan_slip_nominal'] ?></td>
							</tr>
						<?php
							}
						?>
					</table>
				</td>
			</tr>
		</table>		
		<table style='border-bottom: 1px solid black; width: 175mm;'>
			<tr>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 10mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 5mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 17mm; font-size: 8pt; text-align: right;' valign='middle'><br></td>
				<td style='width: 8mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 20mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 10mm; font-size: 8pt; text-align: left;' valign='middle'><br></td>
			    <td style='width: 4mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 5mm; font-size: 8pt; text-align: center;' valign='middle'><br></td>
			    <td style='width: 17mm; font-size: 8pt; text-align: right;' valign='middle'><br></td>
			</tr>
			<tr valign='middle'>
			    <td style='font-size: 8pt; text-align: left; border-bottom: 1px solid black;' valign='middle'><b>Sub Total 1</b></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'><?php echo '' ?></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'><?php echo '' ?></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'>Rp</td>
			    <td style='font-size: 8pt; text-align: right; border-bottom: 1px solid black;' valign='middle'><?php echo number_format($print['gaji_pokok'],0,',','.'); ?></td>
				<td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td colspan='3' style='font-size: 8pt; text-align: left; border-bottom: 1px solid black;' valign='middle'><b>Jumlah Potongan</b></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'><b>Rp</b></td>
			    <td style='font-size: 8pt; text-align: right; border-bottom: 1px solid black;' valign='middle'><b><?php echo number_format($print['gaji_potongan'],0,',','.'); ?></b></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: left; border-bottom: 1px solid black;' valign='middle'><b>TOTAL</b></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'><?php echo '' ?></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'><?php echo '' ?></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'>Rp</td>
			    <td style='font-size: 8pt; text-align: right; border-bottom: 1px solid black;' valign='middle'><?php echo number_format($print['gaji_pokok'],0,',','.'); ?></td>
				<td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'></td>
			    <td colspan='3' style='font-size: 8pt; text-align: left; border-bottom: 1px solid black;' valign='middle'><b>GAJI DITERIMA</b></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center; border-bottom: 1px solid black;' valign='middle'><b>Rp</b></td>
			    <td style='font-size: 8pt; text-align: right; border-bottom: 1px solid black;' valign='middle'><b><?php echo number_format($print['gaji_jumlah'],0,',','.'); ?></b></td>
			</tr>
			<tr>
			    <td>
			        <br>
			    </td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><b>TERBILANG : </b></td>
			    <td colspan='12' style='font-size: 8pt; text-align: left;' valign='middle'><i><?php echo number_to_words($print['gaji_jumlah']); ?></i></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: left;' valign='middle'><b>CATATAN : </b></td>
			    <td colspan='12' style='font-size: 8pt; text-align: left;' valign='middle'><i><?php echo $print['gaji_catatan'] ?></i></td>
			</tr>
		</table>
		<br>
		<table style='width: 175mm;'>
			<tr>
			    <td style='width: 10mm; font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='width: 30mm; font-size: 8pt; text-align: center;' valign='middle'>Bendahara</td>
			    <td style='width: 65mm; font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='width: 30mm; font-size: 8pt; text-align: center;' valign='middle'><?php echo $setting_city['setting_value'].', '.$print['month_name'].' '.$tahun; ?></td>
			    <td style='width: 15mm; font-size: 8pt; text-align: center;' valign='middle'></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'>diterima oleh</td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><br><br><br><br></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><br><br><br><br></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><br><br><br><br></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><br><br><br><br></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><br><br><br><br></td>
			</tr>
			<tr>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo $setting_nama_bendahara['setting_value']; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'><?php echo $print['employee_name']; ?></td>
			    <td style='font-size: 8pt; text-align: center;' valign='middle'></td>
			</tr>
			<tr>
			</tr>
		</table>
</body>
</html>