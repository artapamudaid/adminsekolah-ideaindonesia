<html lang="en">

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

	<title>Data Penghapusan Aset</title>
</head>

<body>
	<div class="container">
		<div class="row pt-4">
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
		</div>
		<hr style="border: 1px solid black;">
		<div class="row">
			<div class="col text-center">
				<h5>BERITA ACARA PENGHAPUSAN ASET</h5>
			</div>
		</div>
		<div class="row">
			<div class="col text-left">
				<p>Penghapusan Aset <?= $lokasi['nama_lokasi'] ?> </p>
			</div>
		</div>
		<div class="row pt-3">
			<div class="col text-center">
				<table id="tabelAset" class="table table-bordered">
					<thead>
						<th>NO.</th>
						<th>NAMA</th>
						<th>VOLUME</th>
						<th>SATUAN</th>
						<th>HARGA (Rp.)</th>
						<th>JUMLAH (Rp.)</th>
					</thead>
					<tbody>
						<?php
						$sum = 0;
						$no = 1;
						foreach ($aset as $row):
							$sum += $row['jumlah'] * $row['harga'];
						?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $row['nama_barang'] ?></td>
								<td><?= $row['jumlah'] ?></td>
								<td><?= $row['satuan'] ?></td>
								<td><?= laporan($row['harga']) ?></td>
								<td><?= laporan($row['jumlah'] * $row['harga']) ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5"><b>Jumlah</b></td>
							<td><?= laporan($sum); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col text-left">
				<p>Aset tersebut telah diperiksa dan terdapat rusak/cacat produksi dan tidak memungkinkan untuk digunakan kembali</p>
			</div>
		</div>
		<div class="row">
			<div class="col text-left">
				<p>Demikian Berita Acara ini kami buat berdasarkan keadaan yang sebenarnya. Atas perhatian dan kerjasamanya kami mengucapkan terima kasih.</p>
			</div>
		</div>
		<div class="row pt-4">
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
	</div>

</body>

</html>