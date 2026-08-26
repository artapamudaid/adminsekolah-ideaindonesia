<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<?php if ($this->session->flashdata('success')): ?>
				<div class="col-xs-12">
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($this->session->flashdata('failed')): ?>
				<div class="col-xs-12">
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php echo $this->session->flashdata('failed'); ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="col-xs-12">
				<div class="box">
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<h4>Petunjuk Singkat</h4>
						<p>Penginputan data Siswa bisa dilakukan dengan mengcopy data dari file Ms. Excel. Format file excel harus sesuai kebutuhan aplikasi. Silahkan download formatnya <a href="<?php echo site_url('manage/student/download'); ?>"><span class="label label-success">Disini</span></a>
							<br><br>
							<strong>CATATAN :</strong>
						<ol>
							<li>Pengisian jenis data <strong>TANGGAL</strong> diisi dengan format <strong>YYYY-MM-DD</strong> Contoh <strong>2017-12-21</strong><br> Cara ubah : blok semua tanggal pilih format cell di excel ganti dengan format date pilih yang tahunnya di depan</li>
						</ol>
						</p>
						<hr>
						<div class="col-md-4">
							<?php echo form_open_multipart('manage/student/do_import', ['id' => 'formImport']) ?>

							<div class="box-body">
								<div class="form-group">
									<label>Masukkan File (.xlsx)</label>
									<input type="file" name="file" id="file_excel" required="" accept=".xls,.xlsx">
									<span class="help-block text-danger" id="file-error" style="display:none;"></span>
								</div>
								<div class="form-group">
									<button type="submit" id="btn-submit" class="btn btn-success btn-sm btn-flat" disabled>Import</button>
									<a href="<?php echo site_url('manage/student') ?>" class="btn btn-info btn-sm btn-flat">Kembali</a>
								</div>
							</div>
							<?php echo form_close() ?>
						</div>
					</div>
					<!-- /.box-body -->
				</div>

				<!-- /.box -->
			</div>

			<div class="col-xs-12" id="preview-section" style="display: none;">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Preview Data</h3>
					</div>
					<div class="box-body">
						<div id="preview-info" class="alert alert-info" style="display:none;"></div>
						<div class="table-responsive" id="preview-table-container">
						</div>
					</div>
				</div>
			</div>

			<?php $importErrors = $this->session->flashdata('import_error_rows'); ?>
			<?php if (!empty($importErrors)): ?>
				<div class="col-xs-12">
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title text-danger"><i class="fa fa-warning"></i> Detail Data Gagal Diimport</h3>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th width="10%" class="text-center">Baris Excel</th>
											<th width="15%">NIS</th>
											<th width="25%">Nama Lengkap</th>
											<th width="50%">Keterangan Error</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($importErrors as $err): ?>
											<tr>
												<td class="text-center"><?php echo isset($err['baris']) ? htmlspecialchars($err['baris']) : ''; ?></td>
												<td class="text-center"><?php echo isset($err['nis']) ? htmlspecialchars($err['nis']) : ''; ?></td>
												<td><?php echo isset($err['nama']) ? htmlspecialchars($err['nama']) : ''; ?></td>
												<td>
													<?php
													if (isset($err['keterangan'])) {
														$messages = preg_split('/[;,]/', $err['keterangan']);
														if (count($messages) > 1) {
															echo '<ul style="padding-left: 15px; margin-bottom: 0;">';
															foreach ($messages as $msg) {
																$msg = trim($msg);
																if ($msg !== '') {
																	echo '<li>' . htmlspecialchars($msg) . '</li>';
																}
															}
															echo '</ul>';
														} else {
															echo htmlspecialchars(trim($err['keterangan']));
														}
													} else {
														echo htmlspecialchars(is_string($err) ? $err : '');
													}
													?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<!-- /.content -->
</div>

<script src="<?= base_url('assets/xlsx/xlsx.full.min.js') ?>"></script>
<script>
	document.getElementById('file_excel').addEventListener('change', function(e) {
		loadingOverlay();
		var file = e.target.files[0];
		var submitBtn = document.getElementById('btn-submit');
		var errorSpan = document.getElementById('file-error');
		var previewSection = document.getElementById('preview-section');
		var previewTableContainer = document.getElementById('preview-table-container');
		var previewInfo = document.getElementById('preview-info');

		submitBtn.disabled = true;
		errorSpan.style.display = 'none';
		previewSection.style.display = 'none';
		previewTableContainer.innerHTML = '';
		previewInfo.style.display = 'none';
		previewInfo.innerHTML = '';

		if (!file) {
			$.LoadingOverlay("hide");
			return;
		}

		var allowedExtensions = ['xls', 'xlsx'];
		var fileExtension = file.name.split('.').pop().toLowerCase();

		if (allowedExtensions.indexOf(fileExtension) === -1) {
			errorSpan.textContent = "Format file tidak valid. Harap masukkan file .xls atau .xlsx";
			errorSpan.style.display = 'block';
			$.LoadingOverlay("hide");
			return;
		}

		setTimeout(() => {
			var reader = new FileReader();
			reader.onload = function(e) {
				try {
					var data = new Uint8Array(e.target.result);
					var workbook = XLSX.read(data, {
						type: 'array'
					});
					var firstSheetName = workbook.SheetNames[0];
					var worksheet = workbook.Sheets[firstSheetName];

					// Get options to read empty cells as well, specifying raw to avoid some date parsing issues if any
					var jsonData = XLSX.utils.sheet_to_json(worksheet, {
						header: "A",
						defval: ""
					});

					if (jsonData.length <= 2) {
						errorSpan.textContent = "File Excel kosong atau tidak sesuai template.";
						errorSpan.style.display = 'block';
						$.LoadingOverlay("hide"); // Hide loading overlay
						return;
					}

					var html = '<table class="table table-bordered table-striped">';
					html += '<thead><tr><th>BARIS</th><th>NIS</th><th>NISN</th><th>NAMA SISWA</th><th>ID KELAS</th><th>ID UNIT</th><th>KETERANGAN</th></tr></thead>';
					html += '<tbody>';

					var hasError = false;
					var nisList = {};
					var nisnList = {};
					var errorCount = 0;
					var validCount = 0;

					// Mulai dari baris ke-3 (index 2) sesuai dengan struktur template (row 1 & 2 biasanya header)
					for (var i = 2; i < jsonData.length; i++) {
						var row = jsonData[i];

						// Sesuai dengan pembacaan backend
						// A: NIS, B: NISN, C: Nama, D: Kelas ID, E: Unit ID
						var nis = row['A'] ? row['A'].toString().trim() : "";
						var nisn = row['B'] ? row['B'].toString().trim() : "";
						var nama = row['C'] ? row['C'].toString().trim() : "";
						var kelasId = row['D'] ? row['D'].toString().trim() : "";
						var unitId = row['E'] ? row['E'].toString().trim() : "";

						// Cek jika seluruh row kosong (bisa jadi baris kosong di akhir file)
						if (nis === "" && nisn === "" && nama === "" && kelasId === "" && unitId === "") {
							continue;
						}

						var rowClass = '';
						var info = [];
						var isRowError = false;

						// Validasi Mandatory
						if (nis === "" || nama === "" || kelasId === "" || unitId === "") {
							rowClass = 'bg-danger';
							info.push("Data Wajib (NIS/Nama/Kelas/Unit) belum lengkap");
							isRowError = true;
						}

						// Validasi Duplikat NIS di dalam file Excel
						if (nis !== "") {
							if (nisList[nis]) {
								rowClass = 'bg-danger';
								info.push("NIS duplikat dalam file (Sama dengan baris " + nisList[nis] + ")");
								isRowError = true;
							} else {
								nisList[nis] = (i + 1); // Simpan nomor baris Excel (index + 1)
							}
						}

						// Validasi Duplikat NISN di dalam file Excel (opsional tapi sesuai rancangan)
						if (nisn !== "") {
							if (nisnList[nisn]) {
								rowClass = 'bg-danger';
								info.push("NISN duplikat dalam file (Sama dengan baris " + nisnList[nisn] + ")");
								isRowError = true;
							} else {
								nisnList[nisn] = (i + 1);
							}
						}

						if (isRowError) {
							hasError = true;
							errorCount++;
						} else {
							validCount++;
							info.push("<span class='text-success'>Valid</span>");
						}

						html += '<tr class="' + rowClass + '">';
						html += '<td class="text-center">' + (i + 1) + '</td>'; // Nomor baris aslinya di excel
						html += '<td class="text-center">' + nis + '</td>';
						html += '<td class="text-center">' + nisn + '</td>';
						html += '<td>' + nama + '</td>';
						html += '<td class="text-center">' + kelasId + '</td>';
						html += '<td class="text-center">' + unitId + '</td>';
						html += '<td>' + info.join(', ') + '</td>';
						html += '</tr>';
					}

					html += '</tbody></table>';

					previewTableContainer.innerHTML = html;
					previewSection.style.display = 'block';

					if (hasError) {
						submitBtn.disabled = true;
						previewInfo.className = 'alert alert-danger';
						previewInfo.innerHTML = "<strong>Terdapat " + errorCount + " baris data yang bermasalah.</strong> Silakan perbaiki file Excel Anda dan upload kembali. Data tidak dapat disimpan sebelum semua error diperbaiki.";
					} else {
						if (validCount > 0) {
							submitBtn.disabled = false;
							previewInfo.className = 'alert alert-success';
							previewInfo.innerHTML = "<strong>Semua baris data (" + validCount + " baris) valid.</strong> Anda dapat melanjutkan proses import.";
						} else {
							previewInfo.className = 'alert alert-warning';
							previewInfo.innerHTML = "Tidak ada data yang diproses. Pastikan file diisi dengan benar.";
						}
					}
					previewInfo.style.display = 'block';
				} catch (e) {
					console.error(e);
					errorSpan.textContent = "Terjadi kesalahan saat memproses file.";
					errorSpan.style.display = 'block';
				} finally {
					$.LoadingOverlay("hide");
				}
			};
			reader.readAsArrayBuffer(file);
		}, 100);
	});

	document.getElementById('formImport').addEventListener('submit', function(e) {
		$.LoadingOverlay("show");
	});
</script>