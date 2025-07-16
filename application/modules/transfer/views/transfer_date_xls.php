<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekapitulasi_Pembayaran_Kelas_".$kls."_TA_".$dataHead->period_start."-".$dataHead->period_end."_".date('d-m-Y').".xls");
header("Pragma: no-cache");
header("Expires: 0");;
?>
<h2><?php echo $setting_school['setting_value']; ?></h2>
<p></p>
<h4></span><?php echo $setting_address['setting_value']; ?>, Telp. 
        	<?php echo $setting_phone['setting_value']; ?></span></h2>
<hr>
<p></p>
<table id="dtable" class="table table-responsive table-bordered" style="white-space: nowrap;">
	<thead>
	<tr>
		<th>Tanggal</th> 
		<th>Id Transfer</th> 
		<th>Keterangan Unit Pengirim </th>
		<th>Keterangan Unit Penerima </th>
		<th>Kredit</th>
		<th>Debit</th>
	</tr>
	</thead>
	<?php if ($q AND !empty($transfer)) { ?>
	<tbody>
	<?php 
		$no = 1;
		$debit = 0;
		$kredit = 0;
		foreach ($transfer as $row) : 
	?>
		<tr>
			<td><?php echo pretty_date($row['log_tf_date'], 'd-m-Y', false) ?></td> 
			<td><?php echo $row['log_tf_balance_id'] ?></td> 
			<td>
				<?php 
					$nameAcc 		= $row['combane_name'];
					$account_name 	= explode(",", $nameAcc);
					$nameMaj 		= $row['combane_majors_name'];
					$majors_name 	= explode(",", $nameMaj);
					
					// penerima
					echo 'Unit : '.$majors_name[0];
					echo '<br> Akun : '.$account_name[0];
					echo '<br> No. Referensi : '.$row['deb_kas_noref'];

				?>
			</td> 
			<td>
				<?php 
					
					// Pengirim
					echo 'Unit : '.$majors_name[1];
					echo '<br> Akun : '.$account_name[1];
					echo '<br> No. Referensi : '.$row['kre_kas_noref'];  

				?>
			</td> 
			<td>Rp <?php echo number_format($row['kre_value'], '0', ',', '.') ?></td>
			<td>Rp <?php echo number_format($row['deb_value'], '0', ',', '.') ?></td>
		</tr>
	<?php 
		$debit += $row['deb_value'];
		$kredit += $row['kre_value'];
		endforeach;
	?>
	</tbody>
	<?php } ?>
	
	<tfoot>
	<tr>
		<th colspan="4">Total</th>
		<th>Rp <?php echo number_format($debit, '0', ',', '.') ?></th>
		<th>Rp <?php echo number_format($kredit, '0', ',', '.') ?></th>
	</tr>
	</tfoot>
</table>