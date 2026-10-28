<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">

						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
						<div class="box-body table-responsive">
							<table>
								<tr>
									<td>
										<select style="width: 200px;" id="p" name="p" class="form-control" required onchange="getSemesterSearch(this.value)">
											<option value="">- Pilih Tahun Ajaran -</option>
											<option value="all" <?php echo (isset($s['p']) && $s['p'] == 'all') ? 'selected' : '' ?>>Semua Tahun Ajaran</option>
											<?php foreach ($period as $row) { ?>
												<option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['p']) && $s['p'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
											<?php } ?>
										</select>
									</td>
									<td>
										&nbsp&nbsp
									</td>
									<td>
										<select style="width: 200px;" id="s" name="s" class="form-control" required>
											<option value="">- Pilih Semester -</option>
											<?php if (isset($s['p'])) : ?>
												<option value="all" <?php echo (isset($s['s']) && $s['s'] == 'all') ? 'selected' : '' ?>>Semua Semester</option>
												<?php foreach ($semester as $row) { ?>
													<option value="<?php echo $row['semester_id']; ?>" <?php echo (isset($s['s']) && $s['s'] == $row['semester_id']) ? 'selected' : '' ?>><?php echo $row['semester_name'] ?></option>
												<?php } ?>
											<?php endif; ?>
										</select>
									</td>
									<td>
										&nbsp&nbsp
									</td>
									<td>
										<select style="width: 200px;" id="m" name="m" class="form-control" required>
											<option value="">- Pilih Unit -</option>
											<?php if ($this->session->userdata('umajorsid') == '0') { ?>
												<option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
											<?php } ?>
											<?php foreach ($majors as $row) { ?>
												<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_name'] ?></option>
											<?php } ?>
										</select>
									</td>
									<td>
										&nbsp&nbsp
									</td>
									<td>
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
									</td>
								</tr>
							</table>
						</div>
						<?php echo form_close(); ?>
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addClass"><i class="fa fa-plus"></i> Tambah</button>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">


						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Tahun Ajaran</th>
									<th>Semester</th>
									<th>Kelas</th>
									<th>Wali</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($wali_kelas)) {
									$i = 1;
									foreach ($wali_kelas as $row) :
								?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['period_start'] . '/' . $row['period_end']; ?></td>
											<td><?php echo $row['semester_name']; ?></td>
											<td><?php echo $row['class_name']; ?></td>
											<td><?php echo $row['employee_name']; ?></td>
											<td>
												<a href="<?php echo site_url('manage/wali_kelas/edit/' . $row['wali_kelas_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												<a href="#delModal<?php echo $row['wali_kelas_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['wali_kelas_id']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
													</div>
													<div class="modal-body">
														<p>Apakah anda yakin akan menghapus data ini?</p>
													</div>
													<div class="modal-footer">
														<?php echo form_open('manage/wali_kelas/delete/' . $row['wali_kelas_id']); ?>
														<input type="hidden" name="delName" value="<?php echo $row['employee_name']; ?>">
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
										<td colspan="4" align="center">Data Kosong</td>
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
<div class="modal fade" id="addClass" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">

				<h4 class="modal-title">Tambah Wali Kelas</h4>
			</div>
			<?php echo form_open('manage/wali_kelas/create', array('method' => 'post')); ?>
			<div class="modal-body">

				<label>Tahun Ajaran</label>
				<select required="" name="wali_kelas_period_id" class="form-control" onchange="getSemester(this.value)">
					<option value="">-Pilih Tahun Ajaran-</option>
					<?php foreach ($period as $p) { ?>
						<option value="<?php echo $p['period_id'] ?>"><?php echo $p['period_start'] . '/' . $p['period_end'] ?></option>
					<?php } ?>
				</select>
				<label>Semester</label>
				<select required="" name="wali_kelas_semester_id" class="form-control">
					<option value="">-Pilih Semester-</option>
				</select>
				<label>Unit</label>
				<select required="" name="wali_kelas_majors_id" class="form-control" onchange="getClass(this.value)">
					<option value="">-Pilih Unit-</option>
					<?php foreach ($majors as $row) { ?>
						<option value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
					<?php } ?>
				</select>
				<label>Kelas</label>
				<select required="" name="wali_kelas_class_id" class="form-control">
					<option value="">-Pilih Kelas-</option>
				</select>
				<label>Nama Wali Kelas</label>
				<select required="" name="wali_kelas_employee_id" class="form-control">
					<option value="">-Pilih Guru-</option>
					<?php foreach ($employees as $row) { ?>
						<option value="<?php echo $row['employee_id'] ?>"><?php echo $row['employee_nip'] . ' - ' . $row['employee_name'] ?></option>
					<?php } ?>
				</select>

			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
</div>

<script>
	getSemesterSearch = function(period_id) {
		$.ajax({
			url: "<?php echo site_url('manage/wali_kelas/get_semester') ?>",
			type: "POST",
			data: {
				period_id: period_id
			},
			dataType: "json",
			success: function(data) {
				var options = '<option value="">-Pilih Semester-</option>';
				options += '<option value="all">Semua Semester</option>';
				$.each(data, function(index, semester) {
					options += '<option value="' + semester.semester_id + '">' + semester.semester_name + '</option>';
				});
				$('select[name="s"]').html(options);
			}
		});
	};

	getSemester = function(period_id) {
		$.ajax({
			url: "<?php echo site_url('manage/wali_kelas/get_semester') ?>",
			type: "POST",
			data: {
				period_id: period_id
			},
			dataType: "json",
			success: function(data) {
				var options = '<option value="">-Pilih Semester-</option>';
				$.each(data, function(index, semester) {
					options += '<option value="' + semester.semester_id + '">' + semester.semester_name + '</option>';
				});
				$('select[name="wali_kelas_semester_id"]').html(options);
			}
		});
	};

	getClass = function(majors_id) {
		$.ajax({
			url: "<?php echo site_url('manage/wali_kelas/get_class') ?>",
			type: "POST",
			data: {
				majors_id: majors_id
			},
			dataType: "json",
			success: function(data) {
				var options = '<option value="">-Pilih Kelas-</option>';
				$.each(data, function(index, kelas) {
					options += '<option value="' + kelas.class_id + '">' + kelas.class_name + '</option>';
				});
				$('select[name="wali_kelas_class_id"]').html(options);
			}
		});
	};
</script>