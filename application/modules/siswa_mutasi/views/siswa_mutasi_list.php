<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<a href="<?php echo site_url('manage/siswa_mutasi/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#printRekap"><i class="fa fa-file-pdf-o"></i> Cetak Rekap </button>
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#xlsRekap"><i class="fa fa-file-excel-o"></i> Export Rekap</button>

					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>No. Surat</th>
								<th>NIS</th>
								<th>Nama Siswa</th>
								<th>Catatan</th>
								<th>Tanggal Input</th>
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($siswa_mutasi)) {
									$i = 1;
									foreach ($siswa_mutasi as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['siswa_mutasi_no']; ?></td>
											<td><?php echo $row['student_nis']; ?></td>
											<td><?php echo $row['student_full_name']; ?></td>
											<td><?php echo $row['catatan']; ?></td>
											<td><?php echo $row['tanggal_input']; ?></td>
											<td>
												<!-- <a href="<?php echo site_url('manage/siswa_mutasi/printdata/' . $row['siswa_mutasi_id']) ?>" class="btn btn-xs btn-success" target="_blank" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></a> -->
												
												<a href="<?php echo site_url('manage/siswa_mutasi/view/' . $row['siswa_mutasi_id']) ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a>
												
												<a href="<?php echo site_url('manage/siswa_mutasi/edit/' . $row['siswa_mutasi_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												
												<a href="#delModal<?php echo $row['siswa_mutasi_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>	
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['siswa_mutasi_id']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span></button>
															<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
														</div>
														<div class="modal-body">
															<p>Apakah anda yakin akan menghapus data ini?</p>
														</div>
														<div class="modal-footer">
															<?php echo form_open('manage/siswa_mutasi/delete/' . $row['siswa_mutasi_id']); ?>
															<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
															<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
															<?php echo form_close(); ?>
														</div>
													</div>
													<!-- /.modal-content -->
												</div>
												<!-- /.modal-dialog -->
											</div>
											<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="10" align="center">Data Kosong</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<!-- /.box-body -->
						</div>
						<div>
							<?php echo $this->pagination->create_links(); ?>
						</div>
						<!-- /.box -->
					</div>
				</div>
			</section>
			<!-- /.content -->
		</div>

		<!-- Modal -->
		<div class="modal fade" id="printRekap" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Cetak Rekap Data</h4>
					</div>
					<form action="<?php echo base_url();?>manage/siswa_mutasi/print_rekap" method="post" target="_blank">
						<div class="modal-body">
							<label>Pilih Tipe Rekap</label>
							<select name="rekap_type" id="rekap_type" onchange="toggleDateFields()" class="form-control">
								<option value="1">Semua Data</option>
								<option value="2">Per Tanggal Input</option>
							</select>
							<div id="date_fields" style="display: none;">
								<label>Dari Tanggal</label>
								<input type="date" name="tanggal_awal" class="form-control">
								<label>Sampai Tanggal</label>
								<input type="date" name="tanggal_akhir" class="form-control">
							</div>
							<label>Nama Siswa</label>
							<select required="" name="student_id" id="student_id_a" class="form-control student_id_a">
								<option value="all">-Semua Siswa-</option>
								<?php foreach($student as $row){?>
									<option value="<?php echo $row['student_id'] ?>"><?php echo $row['student_full_name'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-danger"><i class="fa fa-excel-o"></i> Cetak</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	
		<div class="modal fade" id="xlsRekap" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Ekspor Rekap Data</h4>
					</div>
					<form action="<?php echo base_url();?>manage/siswa_mutasi/excel_rekap" method="post" target="_blank">
						<div class="modal-body">
							<label>Pilih Tipe Rekap</label>
							<select name="rekap_type" id="rekap_type_b" onchange="toggleDateFields_b()" class="form-control">
								<option value="1">Semua Data</option>
								<option value="2">Per Tanggal Input</option>
							</select>
							<div id="date_fields_b" style="display: none;">
								<label>Dari Tanggal</label>
								<input type="date" name="tanggal_awal" class="form-control">
								<label>Sampai Tanggal</label>
								<input type="date" name="tanggal_akhir" class="form-control">
							</div>
							<label>Nama Siswa</label>
							<select required="" name="student_id" id="student_id_b" class="form-control student_id_b">
								<option value="all">-Semua Siswa-</option>
								<?php foreach($student as $row){?>
									<option value="<?php echo $row['student_id'] ?>"><?php echo $row['student_full_name'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success"><i class="fa fa-excel-o"></i> Export</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script>
		function toggleDateFields() {
			var rekapType = document.getElementById("rekap_type").value;
			var dateFields = document.getElementById("date_fields");
			if (rekapType == "2") {
				dateFields.style.display = "block";
			} else {
				dateFields.style.display = "none";
			}
		}
		function toggleDateFields_b() {
			var rekapType = document.getElementById("rekap_type_b").value;
			var dateFields = document.getElementById("date_fields_b");
			if (rekapType == "2") {
				dateFields.style.display = "block";
			} else {
				dateFields.style.display = "none";
			}
		}
		</script>
		<script>
		$(document).ready(function() {
			$('.student_id_a').select2({
				width: '100%'
			});
		});
		</script>
		<script>
		$(document).ready(function() {
			$('.student_id_b').select2({
				width: '100%'
			});
		});
		</script>

		<style>
		/* Ensure that all form-control elements have the same width */
		.form-control {
			width: 100% !important;
		}
		</style>

	</div>