<?php
$kls = str_replace(" ", "-", $dataKelas->class_name);
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekapitulasi_Pembayaran_Kelas_" . $kls . "_TA_" . $dataHead->period_start . "-" . $dataHead->period_end . "_" . date('d-m-Y') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");;
?>
<h2><?php echo $setting_school['setting_value']; ?></h2>
<p></p>
<h4></span><?php echo $setting_address['setting_value']; ?>, Telp.
	<?php echo $setting_phone['setting_value']; ?></span></h2>
	<hr>
	<h3><?php echo 'Rekapitulasi Pembayaran Kelas ' . $dataKelas->class_name . ' Tahun Ajaran ' . $dataHead->period_start . '/' . $dataHead->period_end ?></h3>
	<p></p>
	<h5>Dibuat pada Tanggal : <?php echo date('d-m-Y') ?></h3>
		<p></p>
		<table border="1">
			<tr>
				<th rowspan="2">NIS</th>
				<th rowspan="2">Nama</th>
				<th rowspan="2">Kelas</th>
				<?php foreach ($py as $row) : ?>
					<th colspan="<?php echo count($month) ?>">
						<center><?php echo $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end']; ?></center>
					</th>
				<?php endforeach ?>
				<?php foreach ($bebas as $key) : ?>
					<th rowspan="2"><?php echo $key['pos_name'] . ' - T.A ' . $key['period_start'] . '/' . $key['period_end']; ?></th>
				<?php endforeach ?>
			</tr>
			<tr>
				<?php
				for ($a = 1; $a <= $looping['jml']; $a++) {
					foreach ($month as $key) {
				?>
						<th><?php echo $key['month_name'] ?></th>
				<?php
					}
				}
				?>
			</tr>

			<?php foreach ($student as $row) : ?>
				<tr>
					<td><?php echo $row['student_nis'] ?></td>
					<td><?php echo $row['student_full_name'] ?></td>
					<td><?php echo $row['class_name'] ?></td>
					<!-- Bulan -->
					<?php foreach ($arrayPaymentBulan as $key) : ?>
						<?php $tPayBul = 12; ?>
						<?php foreach ($dataBulan[$key] as $paymentBulan) { ?>
							<?php if ($paymentBulan['student_student_id'] == $row['student_id']) { ?>
								<td <?php echo ($paymentBulan['month_month_id'] == 12) ? 'style="border-right: solid 1px;"' : ''  ?>> <span style="color:<?php echo ($paymentBulan['bulan_status'] == 1) ? '#00E640' : 'red'; ?>"> <?php echo ($paymentBulan['bulan_status'] == 1) ? 'Lunas' : number_format($paymentBulan['bulan_bill'], 0, ',', '.') ?> </span></td>
								<?php $tPayBul--; ?>
							<?php } ?>
						<?php } ?>
						<?php for ($i = 0; $i < $tPayBul; $i++) {
							if ($i == 11) {
								echo '<td style="border-right: solid 1px;">-</td>';
							} else {
								echo '<td>-</td>';
							}
						} ?>
					<?php endforeach ?>
					<!-- End Bulan -->

					<!-- Bebas -->
					<?php foreach ($arrayPaymentBebas as $key) : ?>
						<?php foreach ($dataFree[$key] as $paymentFree) : ?>
							<?php $isPayment = false; ?>
							<?php if ($paymentFree['student_student_id'] == $row['student_id']) { ?>
								<td style="text-align:center; border-left: solid 1px;border-right: solid 1px;"> <span style="text-align:center;color:<?php echo ($paymentFree['bebas_bill'] == $paymentFree['bebas_total_pay']) ? '#00E640' : 'red' ?> "> <?php echo ($paymentFree['bebas_bill'] == $paymentFree['bebas_total_pay']) ? 'Lunas' : number_format($paymentFree['bebas_bill'] - $paymentFree['bebas_total_pay'], 0, ',', '.') ?> </span></td>
								<?php
								$isPayment = true;
								break;
								?>
							<?php } ?>
						<?php endforeach ?>
						<?php if (!$isPayment) {
							echo '<td style="text-align:center; border-left: solid 1px;border-right: solid 1px;">-</td>';
						} ?>
					<?php endforeach ?>
					<!-- End Bebas -->
				</tr>
			<?php endforeach ?>

		</table>