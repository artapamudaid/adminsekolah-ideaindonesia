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
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Filter Data Siswa</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
						<div class="form-group">
							<label for="" class="col-sm-1 control-label">Unit</label>
							<div class="col-sm-2">
								<select class="form-control" name="m">
									<option>-- Pilih Unit --</option>
									<?php foreach ($majors as $row): ?>
										<option <?php if (isset($f['m']) and $f['m'] == $row['majors_id']) {
													echo 'selected';
												} ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<label for="" class="col-sm-1 control-label">Jenis Izin</label>
							<div class="col-sm-2">
								<select class="form-control" name="k">
									<option>-- Pilih Jenis Izin --</option>
									<option value="izin" <?php if (isset($f['k']) and $f['k'] == "izin") {
																echo 'selected';
															} ?>>Izin Keluar</option>
									<option value="pulang" <?php if (isset($f['k']) and $f['k'] == "pulang") {
																echo 'selected';
															} ?>>Izin Pulang</option>
								</select>
							</div>
							<label for="" class="col-sm-1 control-label">Status</label>
							<div class="col-sm-2">
								<div class="input-group">
									<select class="form-control" name="s">
										<option>-- Pilih Status --</option>
										<option value="Diajukan" <?php if (isset($f['s']) and $f['s'] == "Diajukan") {
																		echo 'selected';
																	} ?>>Diajukan</option>
										<option value="Disetujui" <?php if (isset($f['s']) and $f['s'] == "Disetujui") {
																		echo 'selected';
																	} ?>>Disetujui</option>
										<option value="Ditolak" <?php if (isset($f['s']) and $f['s'] == "Ditolak") {
																	echo 'selected';
																} ?>>Ditolak</option>
									</select>
								</div>
							</div>
							<div class="col-sm-2">
								<span class="input-group-btn">
									<button class="btn btn-success" type="submit">Cari</button>
								</span>
							</div>
						</div>
						</form>
					</div><!-- /.box-body -->
				</div><!-- /.box -->

				<?php if ($f) { ?>

					<?php if ($f['k'] == 'izin') { ?>

						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Izin Pulang Siswa</h3>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="box-body table-responsive">
									<table id="xtable" class="table table-bordered" style="white-space: nowrap;">
										<thead>
											<tr class="info">
												<th>No.</th>
												<th>Siswa</th>
												<th>Kelas</th>
												<th>Tanggal</th>
												<th>Jam</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 1;
											foreach ($izin as $row):
											?>
												<tr>
													<td><?php echo $i ?></td>
													<td><?php echo $row['student_full_name'] ?></td>
													<td><?php echo $row['class_name'] ?></td>
													<td><?php echo pretty_date($row['pengajuan_izin_date'], 'd F Y', false) ?></td>
													<td><?php echo $row['pengajuan_izin_time'] ?></td>
													<td><?php echo $row['pengajuan_izin_note'] ?></td>
													<td><?php echo $row['pengajuan_izin_status'] ?></td>
													<td>
														<?php if ($row['pengajuan_izin_status'] == "Diajukan") { ?>
															<a href="#izinDisetujui<?php echo $row['pengajuan_izin_id']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" title="Setujui"></i></a>
															<a href="#izinDitolak<?php echo $row['pengajuan_izin_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-remove" data-toggle="tooltip" title="Tolak"></i></a>
														<?php } else { ?>
															<a href="#izinBatal<?php echo $row['pengajuan_izin_id']; ?>" data-toggle="modal" class="btn btn-xs btn-default"><i class="fa fa-refresh" data-toggle="tooltip" title="Batalkan"></i></a>
														<?php } ?>
													</td>
												</tr>
												<?php if ($row['pengajuan_izin_status'] == "Diajukan") { ?>
													<div class="modal modal-default fade" id="izinDisetujui<?php echo $row['pengajuan_izin_id']; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span></button>
																	<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi</h3>
																</div>
																<div class="modal-body">
																	<p>Apakah anda yakin akan Mensetujui pengajuan ini?</p>
																</div>
																<div class="modal-footer">
																	<?php echo form_open('manage/pengajuan/status_izin/'); ?>

																	<input type="hidden" name="id" value="<?php echo $row['pengajuan_izin_id'] ?>">

																	<input type="hidden" name="date" value="<?php echo pretty_date($row['pengajuan_izin_date'], 'd F Y', false) ?>">
																	<input type="hidden" name="time" value="<?php echo $row['pengajuan_izin_time'] ?>">
																	<input type="hidden" name="izin" value="<?php echo $row['pengajuan_izin_note'] ?>">
																	<input type="hidden" name="nama" value="<?php echo $row['student_full_name'] ?>">
																	<input type="hidden" name="kelas" value="<?php echo $row['class_name'] ?>">
																	<input type="hidden" name="phone" value="<?php echo $row['student_parent_phone'] ?>">

																	<input type="hidden" name="status" value="Disetujui">

																	<input type="hidden" name="majors_id" value="<?php echo $f['m'] ?>">

																	<input type="hidden" name="kategori" value="<?php echo  $f['k'] ?> ">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Setujui</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>

													<div class="modal modal-default fade" id="izinDitolak<?php echo $row['pengajuan_izin_id']; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span></button>
																	<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi</h3>
																</div>
																<div class="modal-body">
																	<p>Apakah anda yakin akan Menolak pengajuan ini?</p>
																</div>
																<div class="modal-footer">
																	<?php echo form_open('manage/pengajuan/status_izin/'); ?>
																	<input type="hidden" name="id" value="<?php echo $row['pengajuan_izin_id'] ?>">

																	<input type="hidden" name="date" value="<?php echo pretty_date($row['pengajuan_izin_date'], 'd F Y', false) ?>">
																	<input type="hidden" name="time" value="<?php echo $row['pengajuan_izin_time'] ?>">
																	<input type="hidden" name="izin" value="<?php echo $row['pengajuan_izin_note'] ?>">
																	<input type="hidden" name="nama" value="<?php echo $row['student_full_name'] ?>">
																	<input type="hidden" name="kelas" value="<?php echo $row['class_name'] ?>">
																	<input type="hidden" name="phone" value="<?php echo $row['student_parent_phone'] ?>">

																	<input type="hidden" name="status" value="Ditolak">

																	<input type="hidden" name="majors_id" value="<?php echo $f['m'] ?>">

																	<input type="hidden" name="kategori" value="<?php echo  $f['k'] ?> ">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-danger"><span class="fa fa-remove"></span> Tolak</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>

												<?php } else { ?>

													<div class="modal modal-default fade" id="izinBatal<?php echo $row['pengajuan_izin_id']; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span></button>
																	<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi</h3>
																</div>
																<div class="modal-body">
																	<p>Apakah anda yakin akan Membatalkan pengajuan ini?</p>
																</div>
																<div class="modal-footer">
																	<?php echo form_open('manage/pengajuan/status_izin/'); ?>

																	<input type="hidden" name="id" value="<?php echo $row['pengajuan_izin_id'] ?>">

																	<input type="hidden" name="date" value="<?php echo pretty_date($row['pengajuan_izin_date'], 'd F Y', false) ?>">
																	<input type="hidden" name="time" value="<?php echo $row['pengajuan_izin_time'] ?>">
																	<input type="hidden" name="izin" value="<?php echo $row['pengajuan_izin_note'] ?>">
																	<input type="hidden" name="nama" value="<?php echo $row['student_full_name'] ?>">
																	<input type="hidden" name="kelas" value="<?php echo $row['class_name'] ?>">
																	<input type="hidden" name="phone" value="<?php echo $row['student_parent_phone'] ?>">

																	<input type="hidden" name="status" value="Diajukan">

																	<input type="hidden" name="majors_id" value="<?php echo $f['m'] ?>">

																	<input type="hidden" name="kategori" value="<?php echo  $f['k'] ?> ">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-danger"><span class="fa fa-default"></span> Reset Status</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>

												<?php } ?>
											<?php
												$i++;
											endforeach;
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

					<?php } else if ($f['k'] == 'pulang') { ?>

						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Pengajuan Izin Pulang Siswa</h3>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="box-body table-responsive">
									<table id="ytable" class="table table-bordered" style="white-space: nowrap;">
										<thead>
											<tr class="info">
												<th>No.</th>
												<th>Siswa</th>
												<th>Kelas</th>
												<th>Tanggal</th>
												<th>Jumlah Hari</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 1;
											foreach ($pulang as $row):
											?>
												<tr>
													<td><?php echo $i ?></td>
													<td><?php echo $row['student_full_name'] ?></td>
													<td><?php echo $row['class_name'] ?></td>
													<td><?php echo pretty_date($row['pengajuan_pulang_date'], 'd F Y', false) ?></td>
													<td><?php echo $row['pengajuan_pulang_days'] . ' Hari' ?></td>
													<td><?php echo $row['pengajuan_pulang_note'] ?></td>
													<td><?php echo $row['pengajuan_pulang_status'] ?></td>
													<td>
														<?php if ($row['pengajuan_pulang_status'] == "Diajukan") { ?>
															<a href="#pulangDisetujui<?php echo $row['pengajuan_pulang_id']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" title="Setujui"></i></a>
															<a href="#pulangDitolak<?php echo $row['pengajuan_pulang_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-remove" data-toggle="tooltip" title="Tolak"></i></a>
														<?php } else { ?>
															<a href="#pulangBatal<?php echo $row['pengajuan_pulang_id']; ?>" data-toggle="modal" class="btn btn-xs btn-default"><i class="fa fa-refresh" data-toggle="tooltip" title="Batalkan"></i></a>
														<?php } ?>
													</td>
												</tr>
												<?php if ($row['pengajuan_pulang_status'] == "Diajukan") { ?>
													<div class="modal modal-default fade" id="pulangDisetujui<?php echo $row['pengajuan_pulang_id']; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&days;</span></button>
																	<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi</h3>
																</div>
																<div class="modal-body">
																	<p>Apakah anda yakin akan Mensetujui pengajuan ini?</p>
																</div>
																<div class="modal-footer">
																	<?php echo form_open('manage/pengajuan/status_pulang/'); ?>

																	<input type="hidden" name="id" value="<?php echo $row['pengajuan_pulang_id'] ?>">

																	<input type="hidden" name="date" value="<?php echo pretty_date($row['pengajuan_pulang_date'], 'd F Y', false) ?>">
																	<input type="hidden" name="days" value="<?php echo $row['pengajuan_pulang_days'] ?>">
																	<input type="hidden" name="pulang" value="<?php echo $row['pengajuan_pulang_note'] ?>">
																	<input type="hidden" name="nama" value="<?php echo $row['student_full_name'] ?>">
																	<input type="hidden" name="kelas" value="<?php echo $row['class_name'] ?>">
																	<input type="hidden" name="phone" value="<?php echo $row['student_parent_phone'] ?>">

																	<input type="hidden" name="status" value="Disetujui">

																	<input type="hidden" name="majors_id" value="<?php echo $f['m'] ?>">

																	<input type="hidden" name="kategori" value="<?php echo  $f['k'] ?> ">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Setujui</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>

													<div class="modal modal-default fade" id="pulangDitolak<?php echo $row['pengajuan_pulang_id']; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&days;</span></button>
																	<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi</h3>
																</div>
																<div class="modal-body">
																	<p>Apakah anda yakin akan Menolak pengajuan ini?</p>
																</div>
																<div class="modal-footer">
																	<?php echo form_open('manage/pengajuan/status_pulang/'); ?>
																	<input type="hidden" name="id" value="<?php echo $row['pengajuan_pulang_id'] ?>">

																	<input type="hidden" name="date" value="<?php echo pretty_date($row['pengajuan_pulang_date'], 'd F Y', false) ?>">
																	<input type="hidden" name="days" value="<?php echo $row['pengajuan_pulang_days'] ?>">
																	<input type="hidden" name="pulang" value="<?php echo $row['pengajuan_pulang_note'] ?>">
																	<input type="hidden" name="nama" value="<?php echo $row['student_full_name'] ?>">
																	<input type="hidden" name="kelas" value="<?php echo $row['class_name'] ?>">
																	<input type="hidden" name="phone" value="<?php echo $row['student_parent_phone'] ?>">

																	<input type="hidden" name="status" value="Ditolak">

																	<input type="hidden" name="majors_id" value="<?php echo $f['m'] ?>">

																	<input type="hidden" name="kategori" value="<?php echo  $f['k'] ?> ">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-danger"><span class="fa fa-remove"></span> Tolak</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>

												<?php } else { ?>

													<div class="modal modal-default fade" id="pulangBatal<?php echo $row['pengajuan_pulang_id']; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&days;</span></button>
																	<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi</h3>
																</div>
																<div class="modal-body">
																	<p>Apakah anda yakin akan Membatalkan pengajuan ini?</p>
																</div>
																<div class="modal-footer">
																	<?php echo form_open('manage/pengajuan/status_pulang/'); ?>

																	<input type="hidden" name="id" value="<?php echo $row['pengajuan_pulang_id'] ?>">

																	<input type="hidden" name="date" value="<?php echo pretty_date($row['pengajuan_pulang_date'], 'd F Y', false) ?>">
																	<input type="hidden" name="days" value="<?php echo $row['pengajuan_pulang_days'] ?>">
																	<input type="hidden" name="pulang" value="<?php echo $row['pengajuan_pulang_note'] ?>">
																	<input type="hidden" name="nama" value="<?php echo $row['student_full_name'] ?>">
																	<input type="hidden" name="kelas" value="<?php echo $row['class_name'] ?>">
																	<input type="hidden" name="phone" value="<?php echo $row['student_parent_phone'] ?>">

																	<input type="hidden" name="status" value="Diajukan">

																	<input type="hidden" name="majors_id" value="<?php echo $f['m'] ?>">

																	<input type="hidden" name="kategori" value="<?php echo  $f['k'] ?> ">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-danger"><span class="fa fa-default"></span> Reset Status</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>

												<?php } ?>
											<?php
												$i++;
											endforeach;
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

					<?php } ?>

				<?php } ?>

			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	(function(a) {
		a.createModal = function(b) {
			defaults = {
				title: "",
				message: "Your Message Goes Here!",
				closeButton: true,
				scrollable: false
			};
			var b = a.extend({}, defaults, b);
			var c = (b.scrollable === true) ? 'style="max-height: 420px;overflow-y: auto;"' : "";
			html = '<div class="modal fade" id="myModal">';
			html += '<div class="modal-dialog">';
			html += '<div class="modal-content">';
			html += '<div class="modal-header">';
			html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
			if (b.title.length > 0) {
				html += '<h4 class="modal-title">' + b.title + "</h4>"
			}
			html += "</div>";
			html += '<div class="modal-body" ' + c + ">";
			html += b.message;
			html += "</div>";
			html += '<div class="modal-footer">';
			if (b.closeButton === true) {
				html += '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
			}
			html += "</div>";
			html += "</div>";
			html += "</div>";
			html += "</div>";
			a("body").prepend(html);
			a("#myModal").modal().on("hidden.bs.modal", function() {
				a(this).remove()
			})
		}
	})(jQuery);
</script>
<style>
	div.over {
		width: 425px;
		height: 165px;
		overflow: scroll;
	}

	div.extended {
		width: 900px;
		height: 200px;
		overflow: scroll;
	}
</style>

<script>
	$(document).ready(function() {
		$('#xtable').DataTable();
	});

	$(document).ready(function() {
		$('#ytable').DataTable();
	});
</script>