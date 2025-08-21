<!DOCTYPE html>
<html>

<head>
	<title><?= 'Cetak_Bukti_Bayar_Hutang_' . $kreditur['hutang_kreditur'] . '_' . $kreditur['hutang_pay_noref'] . '_' . date('Y-m-d') ?></title>
</head>

<style type="text/css">
	@page {
		margin-top: 0.5cm;
		/*margin-bottom: 0.1em;*/
		margin-left: 1cm;
		margin-right: 1cm;
		margin-bottom: 0.1cm;
	}

	.name-school {
		font-size: 15pt;
		font-weight: bold;
		padding-bottom: -15px;
	}

	.unit {
		font-weight: bold;
		font-size: 8pt;
		margin-bottom: -10px;
	}

	.alamat {
		font-size: 8pt;
		margin-bottom: -10px;
	}

	.detail {
		font-size: 10pt;
		font-weight: bold;
		padding-top: -15px;
		padding-bottom: -12px;
	}

	body {
		font-family: sans-serif;
	}

	table {
		font-family: verdana, arial, sans-serif;
		font-size: 11px;
		color: #333333;
		border-width: none;
		/*border-color: #666666;*/
		border-collapse: collapse;
		width: 100%;
	}

	th {
		padding-bottom: 8px;
		padding-top: 8px;
		border-color: #666666;
		background-color: #dedede;
		/*border-bottom: solid;*/
		text-align: left;
	}

	td {
		text-align: left;
		border-color: #666666;
		background-color: #ffffff;
	}

	hr {
		border: none;
		height: 1px;
		/* Set the hr color */
		color: #333;
		/* old IE */
		background-color: #333;
		/* Modern Browsers */
	}

	.container {
		position: relative;
	}

	.topright {
		position: absolute;
		top: 0;
		right: 0;
		font-size: 18px;
		border-width: thin;
		padding: 5px;
	}

	.topright2 {
		position: absolute;
		top: 30px;
		right: 50px;
		font-size: 18px;
		border: 1px solid;
		padding: 5px;
		color: red;
	}
</style>

<body>

	<div class="container">
		<div class="topright">
			Bukti Bayar<br>
			<img style="width:142.56pt;height:18pt;z-index:6;" src="<?php echo media_url() . 'barcode_hutang/' . $kreditur['hutang_pay_noref'] . '.png' ?>" alt="Image_4_0" /><br>
			<font size="12px"><?php echo $kreditur['hutang_pay_noref']; ?></font>
		</div>
	</div>
	<p class="name-school"><?php echo $setting_school['setting_value'] ?></p>
	<p class="unit">
		<?php
		$num = count($unit);
		if ($num > 1) {
			foreach ($unit as $row) {
				$data  = $row['majors_short_name'];
				$pecah = explode(" ", $data);
				for ($i = 0; $i < count($pecah); $i++) {
					echo $pecah[$i] . " ";
				}
			}
		} ?>
	</p>
	<p class="alamat"><?php echo $setting_address['setting_value'] ?><br>
		<?php echo $setting_phone['setting_value'] ?></p>
	<hr>
	<table style="padding-top: -5px; padding-bottom: 5px">
		<tbody>
			<tr>
				<td style="width: 50px;">Hutang No. Ref</td>
				<td style="width: 5px;">:</td>
				<td style="width: 150px;"><?php echo $kreditur['hutang_noref'] ?></td>

				<td style="width: 50px;">Total Hutang</td>
				<td style="width: 5px;">:</td>
				<td style="width: 150px;">Rp <?php echo number_format($kreditur['hutang_bill'], '0', ',', '.') ?></td>
			</tr>
			<tr>
				<td style="width: 50px;">Tanggal Hutang</td>
				<td style="width: 5px;">:</td>
				<td style="width: 150px;"><?php echo pretty_date($kreditur['hutang_date'], 'd F Y', false) ?></td>
				<td style="width: 50px;">Hutang Terbayar</td>
				<td style="width: 5px;">:</td>
				<?php
				$totalPaid = 0;
				foreach ($hutang_paid as $paid) :
					$totalPaid += $paid['hutang_pay_bill'];
				endforeach;
				?>
				<td style="width: 150px;">Rp <?php echo number_format($totalPaid, '0', ',', '.') ?></td>

			</tr>
			<tr>
				<td style="width: 50px;">Kreditur</td>
				<td style="width: 5px;">:</td>
				<td style="width: 150px;"><?php echo $kreditur['hutang_kreditur'] ?></td>
				<td style="width: 50px;">Sisa Hutang</td>
				<td style="width: 5px;">:</td>
				<td style="width: 150px;">Rp <?php echo number_format($kreditur['hutang_bill'] - $totalPaid, '0', ',', '.') ?></td>
			</tr>
		</tbody>
	</table>
	<hr>

	<table style="border-style: solid;">
		<tr>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">Tanggal Bayar</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">No Ref</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;">Akun Bayar</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;" align="center">Catatan</th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;"></th>
			<th style="border-top: 1px solid; border-bottom: 1px solid;" align="center">Nominal</th>
		</tr>
		<?php
		$i = 1;
		$payBill = 0;
		foreach ($hutang_pay as $row) :
		?>
			<tr>
				<td style="border-bottom: 1px solid;padding-top: 10px; padding-bottom: 10px;"><?php echo pretty_date($row['hutang_pay_date'], 'd F Y', false) ?></td>
				<td style="border-bottom: 1px solid"><?php echo $row['hutang_pay_noref'] ?></td>
				<td style="border-bottom: 1px solid"><?php echo $row['account_code'] . ' ' . $row['account_description'] ?></td>
				<td style="border-bottom: 1px solid; text-align: left;"><?php echo $row['hutang_pay_note'] ?></td>
				<td style="border-bottom: 1px solid;">Rp. </td>
				<td style="border-bottom: 1px solid; text-align: right;"><?php echo number_format($row['hutang_pay_bill'], 0, ',', '.') ?></td>
			</tr>
		<?php
			$i++;
			$payBill += $row['hutang_pay_bill'];
		endforeach ?>
		<tr>
			<td colspan="4" style="background-color: #dedede; font-weight:bold; border-bottom: 1px solid;">Total</td>
			<td style="background-color: #dedede;font-weight:bold;border-bottom: 1px solid;">Rp. </td>
			<td style="background-color: #dedede; font-weight:bold;border-bottom: 1px solid; text-align: right;"><?php echo number_format($payBill, 0, ',', '.') ?></td>
		</tr>
		<tr>
			<td style="background-color: #dedede; font-weight:bold; border-bottom: 1px solid;">Terbilang</td>
			<td colspan="5" style="background-color: #dedede; font-weight:bold;border-bottom: 1px solid; text-align: right;">
				<?= $huruf ?>
			</td>
		</tr>
	</table>
	<br><br>
	<table>
		<tr>
			<td style="width: 450px;"></td>
			<td style="text-align: center;"></td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td style="text-align: center;"><?php echo $setting_city['setting_value'] ?>, <?php echo pretty_date(date('Y-m-d'), 'd F Y', false) ?></td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td style="text-align: center">Penanggung Jawab</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td style="text-align: center"><b><?php echo ucfirst($kreditur['petugas']); ?></b></td>
		</tr>
	</table>
	<br>





</body>

</html>