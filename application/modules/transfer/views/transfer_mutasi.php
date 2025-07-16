<html>
<head>
  <?php foreach ($siswa as $row): ?>
    <title>Cetak Surat Tagihan - <?php echo $row['student_full_name'] ?></title>
  <?php endforeach ?>
  <style type="text/css">
  .upper { text-transform: uppercase; }
  .lower { text-transform: lowercase; }
  .cap   { text-transform: capitalize; }
  .small { font-variant:   small-caps; }
</style>
<style type="text/css">
@page {
  margin-top: 1cm;
  margin-bottom: 1cm;
  margin-left: 1cm;
  margin-right: 1cm;
  } .style12 {font-size: 10px}
  .style13 {
   font-size: 14pt;
   font-weight: bold;
 }
.name-school{
	font-size: 15pt;
	font-weight: bold;
	padding-bottom: -15px;
}

.unit{
	font-size: 8pt;
	font-weight: bold;
	margin-bottom: -10px;
}

.alamat{
	font-size: 8pt;
	margin-bottom: -10px;
}
 .title{
  font-size: 14pt;
  text-align: center;
  font-weight: bold;
  padding-bottom: -10px;
}
.tp{
  font-size: 12pt;
  text-align: center;
  font-weight: bold;
}
.unit-siswa{
  font-size: 10pt;
  text-align: center;
  font-weight: bold;
}
hr {
	border: none;
	height: 1px;
	/* Set the hr color */
	color: #333; /* old IE */
	background-color: #333; /* Modern Browsers */
}
body {
  font-family: sans-serif;
}
table {
  border-collapse: collapse;
  font-size: 9pt;
  width: 100%;
}
</style>
</head>
<body>
    
	<p class="name-school"><?php echo $setting_school['setting_value'] ?></p>
	<p class="alamat"><?php echo $setting_address['setting_value'] ?><br>
		<?php echo $setting_phone['setting_value'] ?></p>
		<hr>
  <p class="title">RINCIAN MUTASI</p>
  <table style="font-size: 10pt;" width="100%" border="0">
    <tr>
      <td>Unit</td>
      <td>:</td>
      <td><?php echo $unit ?></td>
    </tr>
    <tr>
      <td>Akun</td>
      <td>:</td>
      <td><?php echo $akun ?></td>
    </tr>
    </table><br>

      <table width="100%" border="1" style="white-space: nowrap;">
        <tr>
			<th>NO.</th>
			<th>NO. REF</th>
			<th>TANGGAL</th>
			<th>NOMINAL</th>
			<th>KETERANGAN</th>
        </tr>
        <?php
			$i =1;
			foreach ($history as $row):
		?>
			<tr>
				<td><?php echo $i ?></td>
				<td><?php echo ($row['log_tf_kredit_id'] != NULL) ? $row['kredit_kas_noref'] : $row['debit_kas_noref'] ?></td>
				<td><?php echo ($row['log_tf_kredit_id'] != NULL) ? pretty_date($row['kredit_date'], 'd F Y', false) : pretty_date($row['debit_date'], 'd F Y', false) ?></td>
				<td><?php echo ($row['log_tf_kredit_id'] != NULL) ? 'Rp '.number_format($row['kredit_value'], 0, ',', '.') : 'Rp '.number_format($row['debit_value'], 0, ',', '.') ?></td>
				<td><?php echo ($row['log_tf_kredit_id'] != NULL) ? $row['kredit_desc'] : $row['debit_desc'] ?></td>
			</tr>
		<?php 
				$i++;
			endforeach; 
		?>	
      </table>


    </body>
    </html>