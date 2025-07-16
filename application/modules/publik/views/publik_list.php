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
						<a href="<?php echo site_url('manage/publik/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#printRekap"><i class="fa fa-file-pdf-o"></i> Cetak Rekap </button>
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#xlsRekap"><i class="fa fa-file-excel-o"></i> Export Rekap</button>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>No. publik</th>
								<th>Nama </th>
								<th>Alamat </th>
								<th>No. Hp/WA</th>
								<th>NIK</th>
								<th>Bidang Yang dituju</th>
								<th>Detail/Keperluan</th>
								<th>Status</th>
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($publik)) {
									$i = 1;
									foreach ($publik as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['publik_no']; ?></td>
											<td><?php echo $row['publik_nama']; ?></td>											
											<td><?php echo $row['publik_alamat']; ?></td>
											<td><?php echo $row['publik_telepon']; ?></td>										
											<td><?php echo $row['publik_nik']; ?></td>
											<td><?php echo $row['position_name']; ?></td>
											<td><?php echo $row['keperluan']; ?></td>
											<td>
												<?php
													if($row['publik_status']==0){
														?>
														<a href="<?php echo site_url('manage/publik/update_status?id=' . $row['publik_id'] .'&status='. $row['publik_status']) ?>" class="btn btn-danger">Permintaan</a>
														<?php
													}elseif($row['publik_status']==1){
														?>
														<a href="<?php echo site_url('manage/publik/update_status?id=' . $row['publik_id'] .'&status='. $row['publik_status']) ?>" class="btn btn-warning">Sedang Proses</a>
														<?php
													}else{
														?>
														<a href="<?php echo site_url('manage/publik/update_status?id=' . $row['publik_id'] .'&status='. $row['publik_status']) ?>" class="btn btn-success">Selesai</a>
														<?php
													}
												?>
											</td>
											<td>
												<!-- <a href="<?php echo site_url('manage/publik/printdata/' . $row['publik_id']) ?>" class="btn btn-xs btn-success" target="_blank" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></a> -->

												<a href="<?php echo site_url('manage/publik/view/' . $row['publik_id']) ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></a>
												
												<a href="<?php echo site_url('manage/publik/edit/' . $row['publik_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												
												<a href="#delModal<?php echo $row['publik_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>	
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['publik_id']; ?>">
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
															<?php echo form_open('manage/publik/delete/' . $row['publik_id']); ?>
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
					<form action="<?php echo base_url();?>manage/publik/print_rekap" method="post" target="_blank">
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
							<label>Status Pengadu Sebagai</label>
							<select required="" name="position" id="position" class="form-control position">
								<option value="all">-Semua Posisi-</option>
								<?php foreach($position as $row){?>
									<option value="<?php echo $row['position_id'] ?>"><?php echo $row['position_name'] ?></option>
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
					<form action="<?php echo base_url();?>manage/publik/excel_rekap" method="post" target="_blank">
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
							<label>Status Pengadu Sebagai</label>
							<select required="" name="position" id="position_b" class="form-control position_b">
								<option value="all">-Semua Posisi-</option>
								<?php foreach($position as $row){?>
									<option value="<?php echo $row['position_id'] ?>"><?php echo $row['position_name'] ?></option>
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
		</script>

		<script>
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
			$('.position').select2({
				width: '100%'
			});
		});
		</script>
		<script>
		$(document).ready(function() {
			$('.position_b').select2({
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