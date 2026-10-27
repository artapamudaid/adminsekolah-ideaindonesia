<!DOCTYPE html>
<?php
$ds         = isset($ds) ? $ds : $this->uri->segment('4');
$de         = isset($de) ? $de : $this->uri->segment('5');
$id_periode = isset($id_periode) ? $id_periode : $this->uri->segment('6');
$id_majors  = isset($id_majors) ? $id_majors : $this->uri->segment('7');
$id_kelas   = isset($id_kelas) ? $id_kelas : $this->uri->segment('8');

if (!isset($kelas_name)) {
	if ($id_kelas != '0' && $id_kelas != 'all' && !empty($dataClass['class_name'])) {
		$kelas_name = $dataClass['class_name'];
	} else {
		$kelas_name = 'Semua Kelas';
	}
}

// Fallback jika pemanggilan data belum di-grouping di controller
if (!isset($bulPayData)) {
	$bulPayData = array();
	$bulPayment = $this->Report_model->get_period_bulan($id_periode, $id_kelas, $ds, $de);
	foreach ($bulPayment as $result) {
		$bulPayData[$result['payment_id']][] = $result;
	}
}

if (!isset($bebPayData)) {
	$bebPayData = array();
	$bebPayment = $this->Report_model->get_period_bebas($id_periode, $id_kelas, $ds, $de);
	foreach ($bebPayment as $result) {
		$bebPayData[$result['payment_id']][] = $result;
	}
}

$period_title = !empty($dataPeriod['period_start']) ? $dataPeriod['period_start'] . '/' . $dataPeriod['period_end'] : '-';
?>
<html>

<head>
	<meta charset="utf-8">
	<title><?php echo 'Laporan Pembayaran Kelas ' . $kelas_name . ' T.A.' . $period_title . ' Tanggal ' . pretty_date($ds, "d/m/Y", FALSE) . ' Sampai ' . pretty_date($de, "d/m/Y", FALSE); ?></title>
	<style>
		@page {
			size: A4 portrait;
			margin: 12mm 15mm 15mm 15mm;
		}

		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 8pt;
			line-height: 1.3;
			color: #222;
		}

		.footer {
			position: fixed;
			bottom: -10mm;
			left: 0;
			right: 0;
			height: 8mm;
			font-size: 7.5pt;
			color: #777;
			border-top: 0.5px solid #ccc;
			padding-top: 3px;
		}

		.footer table {
			width: 100%;
			border-collapse: collapse;
		}

		.footer .page-number:after {
			content: counter(page);
		}

		.kop {
			width: 100%;
			border-bottom: 2px solid #333;
			padding-bottom: 6px;
			margin-bottom: 10px;
			border-collapse: collapse;
		}

		.kop td {
			vertical-align: middle;
		}

		.kop .school-name {
			font-size: 13pt;
			font-weight: bold;
			text-transform: uppercase;
			color: #111;
			margin-bottom: 2px;
		}

		.kop .school-desc {
			font-size: 8pt;
			color: #444;
		}

		.title-area {
			text-align: center;
			margin-bottom: 12px;
		}

		.title-area h3 {
			font-size: 11pt;
			margin: 0 0 4px 0;
			text-transform: uppercase;
			font-weight: bold;
			color: #111;
		}

		.title-area .date-subtitle {
			font-size: 8.5pt;
			color: #333;
		}

		.info-table {
			width: 100%;
			margin-bottom: 12px;
			font-size: 8.5pt;
			border-collapse: collapse;
		}

		.info-table td {
			padding: 2px 0;
			vertical-align: top;
		}

		.pos-section {
			margin-bottom: 14px;
			page-break-inside: auto;
		}

		table.data-table {
			width: 100%;
			border-collapse: collapse;
			page-break-inside: auto;
			margin-bottom: 6px;
		}

		table.data-table thead {
			display: table-header-group;
		}

		table.data-table tfoot {
			display: table-footer-group;
		}

		table.data-table tr {
			page-break-inside: avoid;
		}

		table.data-table th.pos-header-cell {
			background-color: #dfdfdf;
			border-top: 1.5px solid #555;
			border-bottom: 1px solid #777;
			padding: 5px 6px;
			font-size: 8.5pt;
			font-weight: bold;
			text-align: left;
			text-transform: uppercase;
			color: #111;
		}

		table.data-table th.col-header {
			background-color: #f0f0f0;
			border-top: 1px solid #777;
			border-bottom: 1px solid #777;
			padding: 4px 3px;
			font-size: 8pt;
			font-weight: bold;
			text-align: center;
		}

		table.data-table td {
			padding: 3px 4px;
			font-size: 7.5pt;
			border-bottom: 0.5px solid #ddd;
		}

		.row-total td {
			background-color: #f2f2f2;
			border-top: 1px solid #888;
			border-bottom: 1px solid #888;
			font-weight: bold;
			font-size: 8pt;
		}

		.grand-total-box {
			width: 100%;
			border-collapse: collapse;
			margin-top: 8px;
			margin-bottom: 14px;
			page-break-inside: avoid;
		}

		.grand-total-box td {
			padding: 5px 6px;
			font-size: 8.5pt;
			font-weight: bold;
			background-color: #e8e8e8;
			border: 1px solid #bbb;
		}

		.signature-container {
			width: 100%;
			border-collapse: collapse;
			page-break-inside: avoid;
			margin-top: 15px;
		}

		.signature-container td {
			vertical-align: top;
		}

		.text-center {
			text-align: center;
		}

		.text-left {
			text-align: left;
		}

		.text-right {
			text-align: right;
		}
	</style>
</head>

<body>

	<div class="footer">
		<table>
			<tr>
				<td style="width: 70%; text-align: left;">Dicetak pada: <?php echo pretty_date(date('Y-m-d H:i:s'), 'd F Y H:i', false); ?></td>
				<td style="width: 30%; text-align: right;">Halaman <span class="page-number"></span></td>
			</tr>
		</table>
	</div>

	<table class="kop">
		<tr>
			<td style="width: 100%; vertical-align: middle;">
				<div class="school-name"><?php echo $setting_school['setting_value']; ?></div>
				<div class="school-desc">
					<?php echo $setting_address['setting_value']; ?>, Telp. <?php echo $setting_phone['setting_value']; ?>
				</div>
			</td>
		</tr>
	</table>

	<div class="title-area">
		<h3>Laporan Pembayaran</h3>
		<div class="date-subtitle">Tanggal: <?php echo pretty_date($ds, "d F Y", FALSE); ?> Sampai <?php echo pretty_date($de, "d F Y", FALSE); ?></div>
	</div>

	<table class="info-table">
		<tr>
			<td style="width: 14%;">Tahun Ajaran</td>
			<td style="width: 2%;">:</td>
			<td style="width: 38%;"><b><?php echo $period_title; ?></b></td>
			<td style="width: 8%;">Kelas</td>
			<td style="width: 2%;">:</td>
			<td style="width: 36%;"><b><?php echo $kelas_name; ?></b></td>
		</tr>
	</table>

	<?php
	$grandTotal   = 0;
	$adaTransaksi = false;
	if (!empty($dataPayment)) {
		foreach ($dataPayment as $payment) {
			$payId   = $payment['payment_id'];
			$isBulan = ($payment['payment_type'] == 'BULAN');
			$rows    = $isBulan ? ($bulPayData[$payId] ?? array()) : ($bebPayData[$payId] ?? array());

			if (empty($rows)) {
				continue;
			}
			$adaTransaksi = true;
	?>
			<div class="pos-section">
				<table class="data-table">
					<thead>
						<tr>
							<th colspan="6" class="pos-header-cell">
								<?php echo $payment['pos_name']; ?> - T.A. <?php echo $payment['period_start'] . '/' . $payment['period_end']; ?>
							</th>
						</tr>
						<tr>
							<th class="col-header" style="width: 5%;">No.</th>
							<th class="col-header" style="width: 14%;">Tanggal</th>
							<th class="col-header" style="width: 13%;">NIS</th>
							<th class="col-header" style="width: 33%; text-align: left;">Nama Siswa</th>
							<th class="col-header" style="width: 18%; text-align: right;">Nominal</th>
							<th class="col-header" style="width: 17%;">Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no       = 1;
						$subTotal = 0;
						foreach ($rows as $row) {
							$tgl = $isBulan ? $row['bulan_date_pay'] : $row['bebas_pay_input_date'];
							$nom = $isBulan ? $row['bulan_bill'] : $row['bebas_pay_bill'];
							$ket = $isBulan ? $row['month_name'] : $row['bebas_pay_desc'];
							$subTotal += $nom;
						?>
							<tr>
								<td class="text-center"><?php echo $no++; ?></td>
								<td class="text-center"><?php echo pretty_date($tgl, "d/m/Y", FALSE); ?></td>
								<td class="text-center"><?php echo $row['student_nis']; ?></td>
								<td><?php echo $row['student_full_name']; ?></td>
								<td class="text-right">Rp <?php echo number_format($nom, 0, ",", "."); ?></td>
								<td class="text-center"><?php echo $ket; ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr class="row-total">
							<td colspan="4" class="text-left">Total Pembayaran <?php echo $payment['pos_name']; ?></td>
							<td class="text-right">Rp <?php echo number_format($subTotal, 0, ",", "."); ?></td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
	<?php
			$grandTotal += $subTotal;
		}
	}

	if (!$adaTransaksi) {
		echo '<div style="text-align: center; padding: 25px; font-style: italic; color: #888;">Tidak ada data transaksi pembayaran pada rentang tanggal yang dipilih.</div>';
	}
	?>

	<table class="grand-total-box">
		<tr>
			<td style="width: 65%; text-align: left;">TOTAL KESELURUHAN PEMBAYARAN</td>
			<td style="width: 35%; text-align: right;">Rp <?php echo number_format($grandTotal, 0, ",", "."); ?></td>
		</tr>
	</table>

	<table class="signature-container">
		<tr>
			<td style="width: 60%;"></td>
			<td style="width: 40%; text-align: center;">
				<?php echo $setting_city['setting_value']; ?>, <?php echo pretty_date(date('Y-m-d'), 'd F Y', false); ?><br>
				Bendahara Sekolah<br><br><br><br><br>
				<b><u><?php echo ucwords($setting_nama_bendahara['setting_value']); ?></u></b><br>
				<?php if (!empty($setting_nip_bendahara['setting_value'])) { ?>
					NIP. <?php echo $setting_nip_bendahara['setting_value']; ?>
				<?php } ?>
			</td>
		</tr>
	</table>

</body>

</html>