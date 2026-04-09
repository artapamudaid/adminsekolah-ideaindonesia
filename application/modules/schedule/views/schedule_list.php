<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
						<h3 class="box-title">Import Jadwal Pelajaran</h3>
					</div>
					<div class="box-body">
						<a href="<?php echo site_url('manage/schedule/import_schedule') ?>" class="btn btn-sm btn-info"><i class="fa fa-upload"></i> Import Jadwal Pelajaran</a>
						<?php
						$majorsShortName = '';
						$className = '';
						$semesterName = '';
						$TAName = '';
						$periodId = '';
						$semesterId = '';

						if (
							!empty($f['n']) && !empty($f['r'])
						):

							foreach ($class as $row) {
								if (isset($f['n']) and $f['r'] == $row['class_id']) {
									$majorsShortName = $row['majors_short_name'];
									$className = $row['class_name'];
								}
							}

							foreach ($semester as $row) {
								$semesterName = $row['semester_name'];
								$semesterId = $row['semester_id'];
								$periodId = $row['semester_period_id'];
								$TAName = $row['period_start'] . '/' . $row['period_end'];
							}

						?>
							<button
								class="btn btn-sm btn-info"
								id="btnCopySchedule"
								data-unit="<?= $f['n'] ?>"
								data-period="<?= $periodId ?>"
								data-semester="<?= $semesterId ?>"
								data-class="<?= $f['r'] ?>"
								data-majors-short-name="<?= $majorsShortName ?>"
								data-class-name="<?= $className ?>"
								data-semester-name="<?= $semesterName ?>"
								data-ta-name="<?= $TAName ?>">
								<i class="fa fa-copy"></i>&nbsp;
								Salin Jadwal Pelajaran
							</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Filter Data Kelas</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
						<div class="form-group">

							<label class="col-sm-2 control-label">Unit</label>
							<div class="col-sm-2">
								<select class="form-control" name="n" id="majors_id" onchange="find_class()">
									<option <?php echo (isset($f['n']) and $f['n'] == '0') ? 'selected' : '' ?> value="0">Pilih Unit</option>
									<?php foreach ($majors as $row) : ?>
										<option <?php echo (isset($f['n']) and $f['n'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div id="div_class">
								<?php if (isset($f['n'])) { ?>
									<label class="col-sm-2 control-label">Kelas</label>
									<div class="col-sm-2">
										<select class="form-control" name="r" id="class_id">
											<option value="">-- Pilih Kelas --</option>
											<?php foreach ($class as $row) : ?>
												<option <?php echo (isset($f['r']) and $f['r'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<button class="btn btn-success" type="submit">Cari</button>
								<?php } ?>
							</div>

						</div>

						</form>
					</div><!-- /.box-body -->
				</div><!-- /.box -->

				<?php if ($f) { ?>
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Jadwal Pelajaran</h3>
							<?php if (isset($f['n']) and isset($f['r'])) { ?>
								<a href="<?php echo site_url('manage/schedule/cetak' . '/?' . http_build_query($f)) ?>" target="_blank" class="btn btn-danger btn-xs pull-right">Cetak Jadwal Pelajaran</a>
							<?php } ?>
						</div>
						<!-- /.box-header -->
						<div class="box-body">

							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addSchedule"><i class="fa fa-pencil"></i> Buat Jadwal</button>

							<br>
							<br>

							<div class="col-md-9">
								<table class="table table-striped">
									<tbody>
										<tr>
											<td>Unit</td>
											<td>:</td>
											<?php foreach ($class as $row) : ?>
												<?php echo (isset($f['n']) and $f['r'] == $row['class_id']) ?
													'<td>' . $row['majors_name'] . '</td>' : '' ?>
											<?php endforeach; ?>
										</tr>
										<tr>
											<td>Kelas</td>
											<td>:</td>
											<?php foreach ($class as $row) : ?>
												<?php echo (isset($f['n']) and $f['r'] == $row['class_id']) ?
													'<td>' . $row['class_name'] . '</td>' : '' ?>
											<?php endforeach; ?>
										</tr>
										<tr>
											<td>Semester</td>
											<td>:</td>
											<?php foreach ($semester as $row) : ?>
												<td><?php echo $row['semester_name'] . ' ( ' . $row['period_start'] . '/' . $row['period_end'] . ' )' ?></td>
											<?php endforeach; ?>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<!-- List Tagihan Bulanan -->

					<div class="row">
						<?php


						foreach ($day as $row) {
							if ($row['day_name'] != 'Ahad') {
								$hari[] = $row;
							}
						}
						foreach ($hari as $row) {
							$day_id = $row['day_id'];
							$id_kelas = $f['r'];
							$schedule = $this->db->query("SELECT schedule.*, lesson.*, employee.employee_name as teacher FROM schedule
							JOIN lesson ON lesson.lesson_id = schedule.schedule_lesson_id 
													JOIN semester ON schedule.schedule_semester_id = semester.semester_id
													JOIN employee ON lesson.lesson_teacher = employee.employee_id
													JOIN jam_pelajaran ON jam_pelajaran.jam_pelajaran_id = schedule.schedule_jampel
													JOIN period ON period.period_id = semester.semester_period_id
													WHERE schedule_day = '$day_id' AND schedule_class_id = '$id_kelas' AND semester_status = '1' AND period_status = '1'
													ORDER BY jam_pelajaran_start ASC")->result_array();
						?>
							<div class="col-md-4">

								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title"><?php echo $row['day_name'] ?></h3>
									</div>

									<div class="box-body table-responsive">
										<table class="table table-hover table-striped">
											<thead>
												<tr>
													<th>No</th>
													<th>Mata Pelajaran</th>
													<th>Guru</th>
													<th>Waktu</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;
												foreach ($schedule as $jadwal) {
												?>
													<tr>
														<td><?php echo $no++ ?></td>
														<td><?php echo $jadwal['lesson_name'] . '(' . $jadwal['lesson_code'] . ')' ?></td>
														<td><?php echo $jadwal['teacher'] ?></td>
														<?php
														$id_time = $jadwal['schedule_jampel'];
														$time = $this->db->query("SELECT * FROM jam_pelajaran WHERE jam_pelajaran_id = '$id_time' ")->row_array();
														?>
														<td><?php echo $time['jam_pelajaran_start'] . ' s/d ' . $time['jam_pelajaran_end'] ?></td>
														<td>
															<a href="<?= base_url() . 'manage/schedule/add/' . $jadwal['schedule_id'] ?>" data-toggle="modal" class="btn btn-xs btn-warning"><i class="fa fa-edit" data-toggle="tooltip" title="Edit"></i></a>
															<a href="#delModal<?php echo $jadwal['schedule_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
														</td>
													</tr>

													<div class="modal modal-default fade" id="delModal<?php echo $jadwal['schedule_id']; ?>">
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
																	<?php echo form_open('manage/schedule/delete/' . $jadwal['schedule_id']); ?>
																	<input type="hidden" name="majors_id" value="<?php echo $f['n']; ?>">
																	<input type="hidden" name="class_id" value="<?php echo $f['r']; ?>">
																	<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
																	<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
																	<?php echo form_close(); ?>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>
												<?php } ?>
											</tbody>
										</table>
									</div>

								</div>

							</div>
						<?php } ?>
					</div>

				<?php } ?>
			</div>
		</div>
	</section>
</div>


<div class="modal fade" id="addSchedule" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Jadwal Pelajaran</h4>
			</div>
			<div class="modal-body">

				<?php echo form_open('manage/schedule/add_glob', array('method' => 'post')); ?>

				<?php foreach ($majors as $row) : ?>
					<?php echo (isset($f['n']) and $f['n'] == $row['majors_id']) ?
						'<input type="hidden" name="schedule_majors_id" value="' . $row['majors_id'] . '">' : '' ?>
				<?php endforeach; ?>

				<?php foreach ($class as $row) : ?>
					<?php echo (isset($f['n']) and $f['r'] == $row['class_id']) ?
						'<input type="hidden" name="schedule_class_id" value="' . $row['class_id'] . '">' : '' ?>
				<?php endforeach; ?>

				<?php foreach ($semester as $row) : ?>
					<input type="hidden" name="schedule_semester_id" value="<?php echo $row['semester_id'] ?>">
				<?php endforeach; ?>

				<div class="form-group">
					<label>Hari</label>
					<select class="form-control" name="schedule_day" id="schedule_day">
						<option value="">-- Pilih Hari --</option>
						<?php foreach ($day as $row) { ?>
							<?php
							echo '<option value="' . $row['day_id'] . '">' . $row['day_name'] . '</option>'; ?>
						<?php } ?>
					</select>
				</div>

				<div id="p_scents_lesson">
					<div class="row" id="id_row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Pelajaran</label>
								<select class="form-control" name="schedule_lesson[]">
									<option value="">-- Pilih Mata Pelajaran --</option>
									<?php foreach ($lesson as $row) : ?>
										<option value="<?php echo $row['lesson_id'] ?>"><?php echo $row['lesson_code'] . ' - ' . str_replace("'", "", $row['employee_name']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pilih Jam Pelajaran</label>
								<select class="form-control" name="schedule_jampel[]">
									<option value="">-- Pilih Jam Pelajaran --</option>
									<?php foreach ($jam_pelajaran as $row) : ?>
										<option value="<?php echo $row['jam_pelajaran_id'] ?>"><?php echo $row['jam_pelajaran_name'] . ' (' . $row['jam_pelajaran_start'] . ' S/d ' . $row['jam_pelajaran_end'] . ' )' ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<h6><a href="#" class="btn btn-xs btn-success" id="addScnt_lesson"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>

			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-info">Buat Jadwal</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="copyScheduleModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Copy Jadwal Pelajaran</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">
					<strong>
						Salin Jadwal Pelajaran hanya dapat dilakukan dengan ketentuan sebagai berikut:
					</strong>
					<ol>
						<li>Hanya dapat dilakukan pada Unit Sekolah yang sama</li>
						<li>Hanya dapat dilakukan pada Kelas yang sama</li>
						<li>Dengan semester yang berbeda</li>
					</ol>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-info">
							<div class="box-body">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th></th>
												<th></th>
												<th class="text-left">Sumber Data</th>
												<th class="text-left">Data Tujuan</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Unit Sekolah</td>
												<td>:</td>
												<td>
													<span id="txtUnit"></span>
												</td>
												<td>
													<span id="txtUnit"></span>
												</td>
											</tr>
											<tr>
												<td>Kelas</td>
												<td>:</td>
												<td>
													<span id="txtClass"></span>
												</td>
												<td>
													<span id="txtClass"></span>
												</td>
											</tr>
											<tr>
												<td>Semester</td>
												<td>:</td>
												<td>
													<span id="txtSemesterSrc"></span>
												</td>
												<td>
													<span id="txtSemesterObj"></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12">
						<div id="copyProgressContainer" style="display: none;">
							<div class="progress">
								<div id="copyProgressBar" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									<span id="progressText">0%</span>
								</div>
							</div>
						</div>

						<input type="hidden" class="form-control" placeholder="unitId" id="unitId" readonly />
						<input type="hidden" class="form-control" placeholder="classId" id="classId" readonly />
						<input type="hidden" class="form-control" placeholder="periodId" id="periodId" readonly />
						<input type="hidden" class="form-control" placeholder="semesterId" id="semesterId" readonly />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="pasteSchedule" class="btn btn-info">Copy Jadwal</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>

<script>
	function get_kelas() {
		var id_majors = $("#majors_id").val();

		$.ajax({
			url: '<?php echo base_url(); ?>manage/banking/get_kelas',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#div_kelas").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}

	function get_student() {
		var id_majors = $("#majors_id").val();
		var id_kelas = $("#class_id").val();
		var student_name = $("#student_name").val();

		$.ajax({
			url: '<?php echo base_url(); ?>manage/banking/get_student',
			type: 'POST',
			data: {
				'id_majors': id_majors,
				'id_kelas': id_kelas,
				'student_name': student_name,
			},
			success: function(msg) {
				$("#div_data").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>

<script>
	$(function() {
		var scntDiv = $('#p_scents_lesson');
		var i = $('#p_scents_lesson .row').length;

		$("#addScnt_lesson").click(function() {
			$('<div class="row" id="id_row">' +
				'<div class="col-md-6">' +
				'<div class="form-group">' +
				'<label>Pelajaran</label>' +
				'<select class="form-control" name="schedule_lesson[]">' +
				'<option value="">-- Pilih Mata Pelajaran --</option>' +
				'<?php foreach ($lesson as $row) : ?>' +
				'<option value="<?php echo $row['lesson_id'] ?>">' +
				'<?php echo htmlspecialchars($row['lesson_code'] . " - " . $row["employee_name"], ENT_QUOTES); ?>' +
				'</option>' +
				'<?php endforeach; ?>' +
				'</select>' +
				'<a href="#" class="btn btn-xs btn-danger remScnt_lesson">Hapus Baris</a>' +
				'</div>' +
				'</div>' +
				'<div class="col-md-6">' +
				'<div class="form-group">' +
				'<label>Pilih Jam Pelajaran</label>' +
				'<select class="form-control" name="schedule_jampel[]">' +
				'<option value="">-- Pilih Jam Pelajaran --</option>' +
				'<?php foreach ($jam_pelajaran as $row) : ?>' +
				'<option value="<?php echo $row['jam_pelajaran_id'] ?>">' +
				'<?php echo htmlspecialchars($row['jam_pelajaran_name'] . " (" . $row['jam_pelajaran_start'] . " S/d " . $row['jam_pelajaran_end'] . ")", ENT_QUOTES); ?>' +
				'</option>' +
				'<?php endforeach; ?>' +
				'</select>' +
				'</div>' +
				'</div>' +
				'</div>').appendTo(scntDiv);
			i++;
			return false;
		});

		$(document).on("click", ".remScnt_lesson", function() {
			if (i > 1) {
				$(this).closest('.row').remove();
				i--;
			}
			return false;
		});
	});
</script>
<script>
	function find_class() {
		var id_majors = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/schedule/find_class',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#div_class").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>

<style>
	div.over {
		width: 700px;
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
	$(function() {
		let btnCopySchedule = $('#btnCopySchedule');
		btnCopySchedule.on('click', function() {
			let unitId = $(this).data('unit');
			let classId = $(this).data('class');
			let periodId = $(this).data('period');
			let semesterId = $(this).data('semester');
			let majorsShortName = $(this).data('majors-short-name');
			let className = $(this).data('class-name');
			let semesterName = $(this).data('semester-name');
			let taName = $(this).data('ta-name');

			$('#copyScheduleModal #txtUnit').html(majorsShortName);
			$('#copyScheduleModal #txtClass').html(className);
			$('#txtSemesterSrc').html(`${semesterName} (${taName})`);
			getSemesterObj(periodId, semesterId);

			$('#unitId').val(unitId);
			$('#classId').val(classId);
			$('#periodId').val(periodId);
			$('#semesterId').val(semesterId);

			$('#copyScheduleModal').modal('show');
		});

		let btnPasteSchedule = $('#pasteSchedule');
		btnPasteSchedule.on('click', function() {
			btnPasteSchedule.prop("disabled", true);
			btnPasteSchedule.html('Processing...');

			let unitId = $('#unitId').val();
			let classId = $('#classId').val();
			let periodId = $('#periodId').val();
			let semesterId = $('#semesterId').val();

			$.ajax({
				url: "<?= site_url('manage/schedule/copySchedule') ?>",
				type: 'POST',
				dataType: 'json',
				data: {
					unitId,
					classId,
					periodId,
					semesterId
				},
				xhr: function() {
					var xhr = new window.XMLHttpRequest();

					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = (evt.loaded / evt.total) * 100;
							if (!$('#copyProgressContainer').is(':visible')) {
								$('#copyProgressContainer').show();
								$('#copyProgressBar').removeClass('active');
							}
							updateProgressBar(percentComplete);
						}
					}, false);

					return xhr;
				},
				success: function(response) {
					if (response.status == 'success') {
						if (!$('#copyProgressContainer').is(':visible')) {
							$('#copyProgressContainer').show();
							$('#copyProgressBar').removeClass('active');
						}
						updateProgressBar(100);
						let title = 'Berhasil!';
						toastNotifikasi(response.status, response.message, title);
						setTimeout(function() {
							$('#copyScheduleModal').modal('hide');
							resetProgressBar();
						}, 1000);
					} else {
						resetProgressBar();
						let title = 'Gagal!';
						toastNotifikasi(response.status, response.message, title);
					}
				},
				error: function(jqXHR, textStatus) {
					handleAjaxError(jqXHR, textStatus);
					resetProgressBar();
				},
				complete: function() {
					btnPasteSchedule.prop("disabled", false);
					btnPasteSchedule.html('Copy Jadwal');
				}
			});
		});

		function getSemesterObj(periodId, semesterId) {
			$.ajax({
				url: '<?php echo base_url(); ?>manage/schedule/getSemseterObj',
				type: 'POST',
				dataType: 'json',
				data: {
					'periodId': periodId,
					'semesterId': semesterId,
				},
				success: function(response) {
					let semesterName = response.semester_name;
					let taName = `${response.period_start}/${response.period_end}`;

					$('#txtSemesterObj').html(`${semesterName} (${taName})`);
				},
				error: function(jqXHR, textStatus) {
					handleAjaxError(jqXHR, textStatus)
				}
			});
		}

		function updateProgressBar(percentage) {
			let roundedPercent = Math.round(percentage);
			$('#copyProgressBar').css('width', roundedPercent + '%');
			$('#copyProgressBar').attr('aria-valuenow', roundedPercent);
			$('#progressText').html(roundedPercent + '%');
		}

		function resetProgressBar() {
			$('#copyProgressContainer').hide();
			$('#copyProgressBar').addClass('active');
			updateProgressBar(0);
		}
	});
</script>