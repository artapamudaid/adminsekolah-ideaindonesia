<style>
	/* Header tabel tetap kelihatan saat scroll */
	#rincianTagihan thead th {
		position: sticky;
		top: 0;
		background: #fff;
		z-index: 10;
	}

	/* Biar footer tetap terlihat */
	#rincianTagihan .modal-footer {
		position: sticky;
		bottom: 0;
		background: #fff;
		z-index: 15;
		border-top: 1px solid #ddd;
	}

	/* Tabel lebih rapi */
	#rincianTagihan table th,
	#rincianTagihan table td {
		padding: 6px 10px;
		font-size: 13px;
		vertical-align: middle;
	}

	/* Perbaiki tampilan modal agar tidak memanjang */
	#rincianTagihan .modal-dialog {
		max-width: 600px;
	}

	#rincianTagihan #listRincian tr td:first-child {
		padding-left: 8px;
	}

	#rincianTagihan #listRincian tr td:last-child {
		padding-right: 8px;
	}

	#rincianTagihan #listRincian tr td {
		vertical-align: middle;
	}
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header">
						<div class="row">
							<form action="<?= current_url(); ?>" method="get">
								<div class="col-md-2">
									<div class="form-group">
										<label>Tahun Ajaran</label>
										<select class="form-control" name="p" id="periodeID" required="">
											<option value="">-- PILIH TAHUN AJARAN --</option>
											<?php foreach ($period as $row) : ?>
												<option <?php echo (isset($q['p']) and $q['p'] == $row['period_id']) ? 'selected' : '' ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Unit Sekolah</label>
										<select class="form-control" name="k" id="majors_id" onchange="get_kelas()" required>
											<option value="">-- PILIH UNIT --</option>
											<?php foreach ($majors as $row) : ?>
												<option <?php echo (isset($q['k']) and $q['k'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div id="td_kelas">
									<div class="col-md-2">
										<div class="form-group">
											<label>Kelas</label>
											<select class="form-control" name="c" id="class_id">
												<option value="">-- Semua Kelas --</option>
												<?php if (isset($q['k'])) { ?>
													<?php foreach ($class as $row) : ?>
														<option <?php echo (isset($q['c']) and $q['c'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
													<?php endforeach; ?>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label>Dari Bulan</label>
										<select class="form-control" name="d" id="dari_id">
											<option value="">-- Dari Bulan --</option>
											<?php foreach ($from as $row) : ?>
												<?php
												$selected = '';
												if (isset($q['d']) and $q['d'] == $row['month_id']) {
													$selected = 'selected';
												} elseif (!isset($q['d']) and $row['month_id'] == 1) {
													$selected = 'selected';
												}
												?>
												<option <?php echo $selected ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label>Sampai Bulan</label>
										<select class="form-control" name="s" id="sampai_id">
											<option value="">-- Sampai Bulan --</option>
											<?php foreach ($to as $row) : ?>
												<?php
												$selected = '';
												if (isset($q['s']) and $q['s'] == $row['month_id']) {
													$selected = 'selected';
												} elseif (!isset($q['s']) and $row['month_id'] == 12) {
													$selected = 'selected';
												}
												?>
												<option <?php echo $selected ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div style="margin-top:25px;">
										<button type="submit" class="btn btn-primary">Cari</button>
										<?php if ($q and !empty($tagihan_siswa)) { ?>
											<a class="btn btn-success" target="_blank" href="<?php echo site_url('manage/billing/billing_excel' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-excel-o"></i>Detail Excel</a>
											<a class="btn btn-success" target="_blank" href="<?php echo site_url('manage/billing/billing_excel_rekap' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-excel-o"></i> Rekap Excel</a>
										<?php } ?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<?php if ($q and !empty($tagihan_siswa)) { ?>
					<div class="box box-info">
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-responsive table-bordered" style="white-space: nowrap;">
								<thead>
									<tr>
										<!-- <th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th> -->
										<th>No. </th>
										<th>NIS</th>
										<th>Nama</th>
										<th>Kelas</th>
										<th>Total Tagihan</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$totalTagihan = 0;
									foreach ($tagihan_siswa as $row) : ?>
										<tr>
											<td><?php echo $no++ ?></td>
											<td class="nis_santri"><?php echo $row['student_nis'] ?></td>
											<td class="nama_santri"><?php echo $row['student_full_name'] ?></td>
											<td class="kelas_santri"><?php echo $row['class_name'] ?></td>
											<td style="text-align: right;"><?php echo 'Rp ' . number_format($row['total_tagihan'], 0, ",", "."); ?></td>
											<td><button data-student="<?php echo $row['student_id'] ?>" class="btnRincian btn btn-info btn-sm"><i class="fa fa-list"></i> Rician</button>
												<a href="<?php echo site_url('manage/billing/print_bill/' . $q['p'] . '/' . $q['d'] . '/' . $q['s'] . '/' . $row['student_id']) ?>" target="_blank"><button class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> Cetak</button></a>
											</td>
										</tr>

									<?php
										$totalTagihan += $row['total_tagihan'];
									endforeach;
									?>
								</tbody>
								<tfoot>
									<th colspan="4" style="text-align: center;">Total</th>
									<th style="text-align: right;"><?php echo 'Rp ' . number_format($totalTagihan, 0, ",", "."); ?></th>
									<th></th>
								</tfoot>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="rincianTagihan" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Rincian Tagihan Santri</h4>
			</div>

			<!-- Bagian identitas santri -->
			<div class="px-3 pt-2 pb-0">
				<table style="width: 100%; margin-bottom: 10px;">
					<tr>
						<th style="text-align:left; width:25%;">NIS</th>
						<th>:</th>
						<td id="nisSantri"></td>
					</tr>
					<tr>
						<th style="text-align:left;">Nama</th>
						<th>:</th>
						<td id="namaSantri"></td>
					</tr>
					<tr>
						<th style="text-align:left;">Kelas</th>
						<th>:</th>
						<td id="kelasSantri"></td>
					</tr>
				</table>
			</div>

			<!-- Bagian scrollable -->
			<div class="modal-body p-0">
				<div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
					<table id="dtable" class="table table-striped table-hover mb-0">
						<thead>
							<tr>
								<th>Rincian Tagihan</th>
								<th style="text-align: right;">Nominal</th>
							</tr>
						</thead>
						<tbody id="listRincian">
							<!-- Data tagihan dimasukkan lewat JS -->
						</tbody>
					</table>
				</div>
			</div>

			<!-- Sticky footer total -->
			<div class="modal-footer" style="justify-content: space-between;">
				<strong>Total Tagihan:</strong>
				<strong id="totalRincian" style="text-align: right;">Rp 0</strong>
			</div>

		</div>
	</div>
</div>


<script>
	$(".btnRincian").click(function() {
		const row = $(this).closest('tr');
		const nis = row.find('.nis_santri').text().trim();
		const nama = row.find('.nama_santri').text().trim();
		const kelas = row.find('.kelas_santri').text().trim();

		$("#nisSantri").text(nis);
		$("#namaSantri").text(nama);
		$("#kelasSantri").text(kelas);

		var student = $(this).attr("data-student");
		var period = $("#periodeID").val();
		var bulan_a = $("#dari_id").val();
		var bulan_b = $("#sampai_id").val();

		const dataTagihan = {
			student: student,
			period: period,
			month_start: bulan_a,
			month_end: bulan_b
		};

		console.log(dataTagihan);

		$.ajax({
			url: '<?php echo base_url(); ?>manage/billing/rincian_tagihan',
			type: 'POST',
			data: dataTagihan,
			success: function(res) {
				var dataTagihan = JSON.parse(res);
				const tbody = document.getElementById("listRincian");
				$("#listRincian").html('');

				if (dataTagihan.length > 0) {
					let grandTotal = 0;
					let currentTA = '';
					let subtotal = 0;

					for (let i = 0; i < dataTagihan.length; i++) {
						const item = dataTagihan[i];
						const ta = `T.A ${item.period_start}/${item.period_end}`;

						// Kalau masuk ke T.A baru dan sebelumnya sudah ada subtotal, tampilkan total per T.A
						if (currentTA !== '' && ta !== currentTA) {
							const trSubtotal = document.createElement("tr");
							trSubtotal.innerHTML = `
                    <td style="text-align:right; font-weight:bold;">Total ${currentTA}</td>
                    <td style="text-align:right; font-weight:bold;">Rp ${toRupiah(subtotal)}</td>
                `;
							trSubtotal.style.borderTop = "2px solid #ccc";
							tbody.appendChild(trSubtotal);

							// Reset subtotal untuk T.A berikutnya
							subtotal = 0;
						}

						// Jika T.A baru, tampilkan header-nya
						if (ta !== currentTA) {
							currentTA = ta;
							const trGroup = document.createElement("tr");
							const tdGroup = document.createElement("td");
							tdGroup.colSpan = 2;
							tdGroup.innerHTML = `<strong>${ta}</strong>`;
							tdGroup.style.background = "#f7f7f7";
							tdGroup.style.borderTop = "2px solid #ddd";
							tdGroup.style.paddingTop = "6px";
							tdGroup.style.paddingBottom = "6px";
							trGroup.appendChild(tdGroup);
							tbody.appendChild(trGroup);
						}

						// Baris rincian tagihan
						const tr = document.createElement("tr");
						const tdKeterangan = document.createElement("td");
						tdKeterangan.textContent = item.keterangan;

						const tdJumlah = document.createElement("td");
						tdJumlah.style.textAlign = "right";
						tdJumlah.textContent = toRupiah(item.total);

						tr.appendChild(tdKeterangan);
						tr.appendChild(tdJumlah);
						tbody.appendChild(tr);

						subtotal += parseInt(item.total);
						grandTotal += parseInt(item.total);
					}

					// Tambahkan total untuk T.A terakhir
					if (currentTA !== '') {
						const trSubtotal = document.createElement("tr");
						trSubtotal.innerHTML = `
                <td style="text-align:right; font-weight:bold;">Total ${currentTA}</td>
                <td style="text-align:right; font-weight:bold;">Rp ${toRupiah(subtotal)}</td>
            `;
						trSubtotal.style.borderTop = "2px solid #ccc";
						tbody.appendChild(trSubtotal);
					}

					// Update total keseluruhan
					$("#totalRincian").text('Rp ' + toRupiah(grandTotal));

				} else {
					$("#listRincian").html('<td style="text-align:center;" colspan="2">Tidak ada Tagihan</td>');
					$("#totalRincian").text('Rp 0');
				}
			},
			error: function(msg) {
				alert('msg');
			}
		});

		$("#rincianTagihan").modal("show");
	});

	function toRupiah(angka) {
		return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	}

	function get_kelas() {
		var id_majors = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/billing/class_searching',
			type: 'POST',
			data: {
				'id_majors': id_majors,
				'allClass': 1,
			},
			success: function(msg) {
				$("#td_kelas").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>
<script>
	$(document).ready(function() {
		$("#selectall").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
	$(document).ready(function() {
		$("#selectall2").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>