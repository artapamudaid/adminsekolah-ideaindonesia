<style>
	/* Styling dasar tabel */
	.table-freeze {
		width: 100%;
		border-collapse: collapse;
	}

	.table-freeze th,
	.table-freeze td {
		border: 1px solid #ddd;
		padding: 8px;
		min-width: 40px;
		white-space: nowrap;
		background-color: #f9f9f9;
		/* z-index: 1; */
	}

	/* Freeze kolom pertama (No.) */
	.table-freeze thead th:nth-child(1),
	.table-freeze tbody td:nth-child(1) {
		position: sticky;
		left: 0px;
		z-index: 4;
		background-color: #fff;
	}

	/* Freeze kolom kedua (Nama) */
	.table-freeze thead th:nth-child(2),
	.table-freeze tbody td:nth-child(2) {
		position: sticky;
		left: 43px;
		/* Jarak left harus disesuaikan */
		z-index: 3;
		background-color: #fff;
	}
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label>Kelas</label>
									<select class="form-control" name="c" id="class_id" required="">
										<option value="">-- Pilih Kelas --</option>
										<?php foreach ($class as $row) : ?>
											<option <?php echo (isset($q['c']) and $q['c'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div id="td_lesson">
								<div class="col-md-12 col-sm-12">
									<div class="form-group">
										<label>Mapel</label>
										<select class="form-control" name="l" id="lesson_id" required="">
											<option value="">-- Pilih Mapel --</option>
											<?php if (isset($q['l'])) { ?>
												<?php foreach ($lesson as $row) : ?>
													<option <?php echo (isset($q['c']) and $q['l'] == $row['lesson_id']) ? 'selected' : '' ?> value="<?php echo $row['lesson_id'] ?>"><?php echo $row['lesson_code'] . ' - ' . $row['lesson_name'] ?></option>
												<?php endforeach; ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div style="margin-top:25px;">
									<button type="submit" class="btn btn-primary btn-block"> Pilih</button>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>

				<?php if ($q) { ?>

					<?php if ($check == 0) { ?>

						<?php if (!empty($student)) { ?>
							<div class="box box-success">
								<div class="box-body table-responsive">
									<?php echo form_open('guru/jurnal/save', array('method' => 'post')); ?>
									<input type="hidden" name="class_id" value="<?php echo $q['c'] ?>" required></td>
									<input type="hidden" name="semester_id" value="<?= $semester['semester_id'] ?>" required></td>
									<input type="hidden" name="lesson_id" value="<?php echo $q['l'] ?>" required></td>
									<input type="hidden" name="month_id" value="<?php echo $bulan['month_id'] ?>" required></td>
									<table width="100%" style="margin-bottom: 30px;">
										<tr>
											<td><label for="">Materi Terakhir</label></td>
										</tr>
										<tr>
											<td>
												<p> <?= (isset($materi_terakhir['teaching_materi'])) ? $materi_terakhir['teaching_materi'] : '-'; ?></p>
											</td>
										</tr>
										<tr>
											<td><label for="">Materi</label></td>

										</tr>
										<tr>
											<td colspan="2"><textarea class="form-control" rows="4" name="materi" style="width:100%" required></textarea></td>
										</tr>
									</table>
									<div class="table-responsive">
										<?php
										$jam_pelajaran = array();
										foreach ($schedule as $row) :
											$jam_pelajaran[] = $row;
										endforeach;
										$last_presensi = array();
										foreach ($latest as $row) {
											$last_presensi[] = $row;
										}
										?>

										<table class="table table-bordered table-freeze" style="white-space: nowrap;">
											<thead>
												<tr>
													<th width='5' style="vertical-align: middle;">
														<center>No.</center>
													</th>
													<th style="vertical-align: middle;">
														<center>Nama</center>
													</th>
													<th colspan='<?= count($jam_pelajaran) ?>' style="vertical-align: middle;">
														<center>Presensi</center>
													</th>
													<th colspan='<?= count($jam_pelajaran) + 1 ?>' style="vertical-align: middle;">
														<center>Presensi Terakhir</center>
													</th>
												</tr>
												<tr>
													<th></th>
													<th></th>
													<?php foreach ($jam_pelajaran as $jam) : ?>

														<th>
															<?= $jam['jam_pelajaran_name'] ?>
														</th>
													<?php endforeach; ?>
													<?php foreach ($jam_pelajaran as $jam) : ?>

														<th>
															<?= $jam['jam_pelajaran_name'] ?>
														</th>
													<?php endforeach; ?>
												</tr>
												<tr>
													<th></th>
													<th></th>
													<?php foreach ($jam_pelajaran as $jam) : ?>
														<input type="hidden" name="schedule[]" value="<?= $jam['schedule_id'] ?>">
														<input type="hidden" name="jam_pelajaran[]" value="<?= $jam['jam_pelajaran_id'] ?>">
														<th>
															<label for="">
																<center>Presensi Massal</center>
															</label>
															<select id="schedule_id<?= $jam['schedule_id'] ?>" class="form-control">
																<?php foreach ($note as $n) : ?>
																	<option value="<?= $n['id'] ?>"><?= $n['name'] ?></option>
																<?php endforeach ?>
															</select>
														</th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;
												foreach ($student as $row) :
												?>
													<tr>
														<td><?php echo $no++ ?><input type="hidden" name="student_id[]" value="<?php echo $row['student_id'] ?>"></td>
														<td><?php echo wordwrap($row['student_full_name'], 10, "<br>\n") ?></td>
														<?php foreach ($jam_pelajaran as $jam) : ?>
															<th>
																<select id="schedule_id<?= $jam['schedule_id'] . $row['student_id'] ?>" name="schedule_id<?= $jam['schedule_id'] . $row['student_id'] ?>[]" class="form-control">
																	<?php foreach ($note as $n) : ?>
																		<option value="<?= $n['id'] ?>"><?= $n['name'] ?></option>
																	<?php endforeach ?>
																</select>
															</th>
														<?php endforeach; ?>
														<?php foreach ($jam_pelajaran as $jam) :
															foreach ($last_presensi as $lp) :
																if ($lp['presensi_pelajaran_student_id'] == $row['student_id'] && $jam['schedule_id'] == $lp['schedule_id']) {
														?>

																	<th>
																		<?= $lp['presensi_pelajaran_status'] ?>
																	</th>
														<?php
																}
															endforeach;
														endforeach; ?>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
										<div class="col-md-12 col-sm-12 mt-5">
											<button type="submit" class="btn btn-success btn-block w-100">Simpan</button>
										</div>
									</div>
									<?php echo form_close(); ?>
								</div>
								<!-- /.box-body -->
							</div>
						<?php

						} else { ?>
							<div class="box box-success">
								<div class="box-body table-responsive">
									<center>
										<h3>Data siswa tidak ditemukan</h3>
									</center>
								</div>
							</div>
						<?php }
					} else { ?>

						<div class="box box-success">
							<div class="box-body table-responsive">
								<center>
									<h3>Semua Santri Sudah Diabsen</h3>
								</center>
							</div>
						</div>

				<?php
					}
				}
				?>




			</div>
		</div>
</div>
<!-- /.content -->
</div>

<script>
	$(document).ready(function() {
		$("#class_id").change(function() {
			var class_id = $(this).val();
			getLesson(class_id);
		});
	});

	function getLesson(class_id) {
		$.ajax({
			url: '<?php echo base_url(); ?>guru/jurnal/lesson_searching',
			type: 'POST',
			data: {
				'class_id': class_id,
			},
			success: function(msg) {
				$("#td_lesson").html(msg);
			},
			error: function(errorResponse) {
				alert('Error: ' + errorResponse.responseText);
			}
		});
	}
</script>


<script>
	function matchData(mainId) {
		const mainData = document.getElementById(mainId);
		const mainValue = mainData.value;

		const childList = document.querySelectorAll("[id^='" + mainId + "']");

		childList.forEach(function(childData) {
			childData.value = mainValue;
		});
	}


	<?php
	if (!empty($jam_pelajaran)) :
		foreach ($jam_pelajaran as $jam) :
	?>

			document.querySelectorAll("[id^='schedule_id<?= $jam['schedule_id'] ?>']").forEach(function(mainData) {
				mainData.addEventListener('change', function() {
					const mainId = mainData.id;
					matchData(mainId);
				});
			});

			document.querySelectorAll("[id^='schedule_id<?= $jam['schedule_id'] ?>']").forEach(function(mainData) {
				const mainId = mainData.id;
				matchData(mainId);
			});

	<?php
		endforeach;
	endif;
	?>
</script>

<script>
</script>