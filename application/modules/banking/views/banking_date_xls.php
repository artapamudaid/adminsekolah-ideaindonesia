<?php

$unit   = $_GET['k'];
$kelas  = $_GET['c'];

if ($unit == 0) {
    $majors = 'Semua';    
} else {
    $qUnit = $this->db->query("SELECT majors_name, majors_short_name FROM majors WHERE majors_id = '$unit'")->row_array();
    $majors = $qUnit['majors_short_name'];
}

if ($kelas == 0) {
    $class = 'Semua';    
} else {
    $qKelas = $this->db->query("SELECT class_name FROM class WHERE class_id = '$kelas'")->row_array();
    $class = $qKelas['class_name'];
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Tabungan_Tanggal_".pretty_date($_GET['s'],'d-m-Y', false)."_sd_".pretty_date($_GET['e'],'d-m-Y', false)."_Unit_".$majors."_Kelas_".$class.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 277mm;'>
	<tr valign='top'>
		<td colspan="5" style='width: 55mm;' valign='middle'>
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
		<td colspan="2" style='width: 93mm; font-size: 10pt; text-align: center' valign='top' align='center'><b><?php echo 'Laporan Tabungan Per Tanggal ' . pretty_date($_GET['s'],'d-m-Y', false) . ' s/d ' . pretty_date($_GET['e'],'d-m-Y', false) ?></b></td>
		<td colspan="1" style='width: 92mm; font-size: 10pt;' align='right'></td>
	</tr>
</table>
<br>
<table cellpadding='0' cellspacing='0' style='width: 277mm;'>
	<tr valign='top'>
		<td style='font-size: 10pt;'>Unit</td>
		<td colspan="2" style='font-size: 10pt;'> : <?php echo $majors ?></td>
		<td colspan="2" style='font-size: 10pt;'> </td>
	</tr>
	<tr valign='top'>
		<td style='font-size: 10pt;'>Kelas</td>
		<td colspan="2" style='font-size: 10pt;'> : <?php echo $class ?></td>
		<td colspan="2" style='font-size: 10pt;'> </td>
	</tr>
</table>
<table border="1">
    <thead>
	    <tr>
		<th>Tanggal</th> 
		<th>NIS</th> 
		<th>Nama</th>
		<th>Unit</th>
		<th>Kelas</th>
		<th>Debit</th>
		<th>Kredit</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		    $no = 1;
		    $debit = 0;
		    $kredit = 0;
		    foreach ($banking as $row) : 
		?>
		<tr>
			<td><?php echo pretty_date($row['banking_date'], 'd-m-Y', false) ?></td> 
			<td><?php echo $row['student_nis']?></td> 
			<td><?php echo $row['student_full_name']?></td>
			<td><?php echo $row['majors_short_name']?></td>
			<td><?php echo $row['class_name']?></td>
			<td>Rp <?php echo number_format($row['banking_debit'], '0', ',', '.') ?></td>
			<td>Rp <?php echo number_format($row['banking_kredit'], '0', ',', '.') ?></td>
		</tr>
		<?php 
		    $debit += $row['banking_debit'];
		    $kredit += $row['banking_kredit'];
		    endforeach;
		?>
		
		<tr>
		    <th colspan="5" style="text-align: center;"> Total</th>
			<th>Rp <?php echo number_format($debit, '0', ',', '.') ?></th>
			<th>Rp <?php echo number_format($kredit, '0', ',', '.') ?></th>
		</tr>
    </tbody>
</table>
<br>
<table>
<tr>
	<td colspan='3' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
	<td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php echo $setting_city['setting_value']; ?>, <?php echo pretty_date(date('Y-m-d'),'d F Y',false); ?></td>
	<td align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
</tr>
<tr>
	<td colspan='3' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
	<td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='right'><br><br></td>
	<td align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
</tr>
<tr>
	<td colspan='3' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
	<td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='right'><br><br></td>
	<td align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'><br><br></td>
</tr>
<tr>
	<td colspan='3' align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
	<td colspan='2' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;' align='center'><?php 
	        echo ucfirst($setting_nama_bendahara['setting_value']).'<p></p>NIP. '.ucfirst($setting_nip_bendahara['setting_value']);?></td>
	<td align='right' style='font-size: 10pt; padding: 1px; border-top: 0px solid #999999; border-bottom: 0px solid #999999;'></td>
</tr>
</table>