<html>
<title>Laporan Data Aset</title>

<head>
	<style>
		#tabelAset {
			width: 100%;
			/* Tabel memenuhi lebar kontainer */
			border-collapse: collapse;
			/* Menghilangkan jarak antar border */
			font-size: 10pt;
		}

		#tabelAset th,
		#tabelAset td {
			border: 1px solid black;
			/* Memberikan border pada setiap sel */
			padding: 8px;
			/* Jarak dalam sel */
			text-align: left;
			/* Teks rata kiri */
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row pt-4">
			<div class="col-md-12">
				<table style="width: 100%;">
					<td width="6%">
						<?php
						$arrContextOptions = array(
							"ssl" => array(
								"verify_peer" => false,
								"verify_peer_name" => false,
							),
						);

						$path = upload_url('school/' . logo());
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path, false, stream_context_create($arrContextOptions));
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						?>

						<img src="<?php echo $base64 ?>" style="height: 80px;" class="kiri" alt="logo">
					</td>
					<td>
						<h3><?php echo $setting_school['setting_value'] ?></h3>
						<span class="alamat"><?php echo $setting_address['setting_value'] ?><?php echo ' Telp. ' . $setting_phone['setting_value'] ?></span>
					</td>
				</table>
				<hr style="border: 1px solid black;">

			</div>
		</div>

		<div class="row">
			<div style="text-align: center;">
				<strong>Aset <?= $lokasi['nama_lokasi'] ?></strong>
			</div>
		</div>
		<div style="margin-top: 5px;">
			<table id="tabelAset">
				<thead>
					<th>NO.</th>
					<th>NAMA</th>
					<th>VOLUME</th>
					<th>SATUAN</th>
					<th>HARGA (Rp.)</th>
					<th>JUMLAH (Rp.)</th>
				</thead>
				<tbody>
					<?php if (isset($aset)) { ?>
						<?php
						$no = 1;
						$sum = 0;
						foreach ($aset as $row):
							$sum += ($row['harga'] * $row['volume']);
						?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $row['nama_barang'] ?></td>
								<td><?= $row['volume'] ?></td>
								<td><?= $row['satuan'] ?></td>
								<td><?= laporan($row['harga']) ?></td>
								<td><?= laporan($row['harga'] * $row['volume']) ?></td>
							</tr>
						<?php endforeach ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"><b>Jumlah</b></td>
						<td><?= laporan($sum); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<br>
		<table style="width: 100%;">
			<tr>
				<td style="width: 40%;">
					<span>Mengetahui, </span>
					<br>
					<span>Kepala Sekolah</span>
					<br>
					<br>
					<br>
					<br>
					<br>
					<?= $nama_kepsek['setting_value'] ?? '' ?></br>
					NIP. <?= $nip_kepsek['setting_value'] ?? '' ?>
				</td>
				<td style="width: 30%;"></td>
				<td style="width: 30%;">
					<span>Tempat, <?= tgl_indo(date('Y-m-d')) ?></span>
					<br>
					<span>Kepala Sekolah</span>
					<br>
					<br>
					<br>
					<br>
					<br>
					<?= $nama_bendahara['setting_value'] ?? '' ?></br>
					NIP. <?= $nip_bendahara['setting_value'] ?? '' ?>
				</td>
			</tr>
		</table>
	</div>


</body>

</html>