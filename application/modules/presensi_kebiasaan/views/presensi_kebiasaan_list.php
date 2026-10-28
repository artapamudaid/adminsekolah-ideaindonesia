<style>
	.select2-container .select2-selection--single {
		height: 50px;
		display: flex;
		align-items: center;
	}

	.select2-selection__rendered {
		line-height: 50px;
	}
</style>

<div class="content-wrapper">
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
						<h3><?php echo 'Presensi Kebiasaan Siswa Hari ' . pretty_date(date('Y-m-d'), 'l, d F Y', false); ?></h3>
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<input type="hidden" class="form-control" name="guru" id="guru" value="<?php echo $this->session->userdata('ufullname_guru') ?>">
						<input type="hidden" class="form-control" name="today" id="today" value="<?php echo date('Y-m-d') ?>">
						<div class="row">
							<div id="td_kelas">
								<div class="col-md-2">
									<div class="form-group">
										<label>Kelas</label>
										<select class="form-control" name="kelas" id="kelas" required>
											<option value="">-- Pilih Kelas --</option>
											<?php foreach ($kelas as $row): ?>
												<option <?php echo (isset($q['kelas']) and $q['kelas'] == $row['schedule_class_id']) ? 'selected' : '' ?> value="<?php echo $row['schedule_class_id'] ?>"><?php echo $row['class_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>

							<!-- <div id="td_habit">
								<div class="col-md-2">
									<div class="form-group">
										<label>Habit</label>
										<select class="form-control" name="habit" id="habit" required>
											<option value="">-- Pilih Kebiasaan --</option>
										</select>
									</div>
								</div>

							</div> -->

							<div class="col-md-4">
								<div style="margin-top:25px;">
									<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>

				<?php if (!empty($student)) { ?>
					<div class="box box-success">
						<div class="box-body table-responsive">
							<table style="width: 100%;">
								<tr>
									<td style="vertical-align: top;">
										<table class="table table-responsive table-bordered" style="white-space: nowrap;">
											<thead>
												<tr>
													<th rowspan="2">
														<center>No.</center>
													</th>
													<th rowspan="2">
														<center>NIS</center>
													</th>
													<th rowspan="2">
														Nama Siswa
													</th>
													<th colspan="2">Status</th>
												</tr>
												<tr>
													<td style="text-align: center;font-weight:bold;"><input name="selectAll" id="selectallhadir" type="radio" value="Y"> YA</td>
													<td style="text-align: center;font-weight:bold;"><input name="selectAll" id="selectallalpha" type="radio" value="Y"> TIDAK</td>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;
												foreach ($student as $row) :
												?>
													<tr>
														<td style="text-align: center;"><?php echo $no++ ?><input type="hidden" name="student_id[]" value="<?php echo $row['student_id'] ?>"></td>
														<td style="text-align: center;"><?php echo $row['student_nis'] ?></td>
														<td>
															<?php echo $row['student_full_name'] ?>
														</td>
														<td style="text-align: center;">
															<input type="radio" name="presensi_kebiasaan_status<?php echo $row['student_id'] ?>[]" class="hadir" value="H" required> YA
														</td>
														<td style="text-align: center;">
															<input type="radio" name="presensi_kebiasaan_status<?php echo $row['student_id'] ?>[]" class="alpha" value="A" required> TIDAK
														</td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</td>
									<td style="vertical-align: top;">
										<table class="table table-responsive table-bordered" style="white-space: nowrap;">
											<thead>
												<tr>
													<th>
														<center><input name="checkAll" id="checkHabit" type="checkbox"> Daftar Kebiasaan Anak</center>
													</th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach ($habit as $row) :
												?>
													<tr>
														<td>
															<input data-habit="<?php echo $row['id'] ?>" type="checkbox" name="habit[]" class="habit habitS<?php echo $row['id'] ?>" value="<?php echo $row['id'] ?>">
															<?php echo $row['name'] ?>
														</td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</td>
								</tr>
							</table>
						</div>

						<div class="box-footer clearfix">

							<button type="button" id="btnSimpan" class="btn btn-success pull-right">

								<i class="fa fa-save"></i> Simpan

							</button>

						</div>

					</div>
				<?php } ?>




			</div>
		</div>
	</section>
</div>

<script>
	$(document).ready(function() {
		$("#selectallhadir").click(function() {
			if ($(this).is(':checked'))
				$(".hadir").prop("checked", true);
			else
				$(".hadir").prop("checked", false);
		});

		$("#selectallalpha").click(function() {
			if ($(this).is(':checked'))
				$(".alpha").prop("checked", true);
			else
				$(".alpha").prop("checked", false);
		});

		// get_habit();

		jQuery("#habit").select2({
			width: '100%'
		});

		// Atur tinggi elemen Select2
		$('.select2-container .select2-selection--single').css({
			height: '35px',
			display: 'flex',
			'align-items': 'center'
		});

		$('.select2-selection__rendered').css('line-height', '35px');

	});

	function get_habit() {

		$.ajax({
			url: '<?php echo base_url(); ?>guru/presensi_kebiasaan/habit_list',
			type: 'GET',
			dataType: 'json', // penting
			success: function(result) {
				// console.log(result);
				let select = $('#habit');

				// kosongkan dulu kecuali option pertama
				select.find('option:not(:first)').remove();

				// loop data
				$.each(result, function(index, item) {

					select.append(
						`<option value="${item.id}">
				        ${item.name}
				    </option>`
					);

				});

			},
			error: function() {

				alert('Gagal mengambil data habit');

			}

		});

	}

	$("#checkPresensi").change(function() {
		if ($(this).is(':checked')) {
			$(".presensi").prop("checked", true);
		} else {
			$(".presensi").prop("checked", false);
		}
	});

	$("#checkHabit").change(function() {
		if ($(this).is(':checked')) {
			$(".habit").prop("checked", true);
		} else {
			$(".habit").prop("checked", false);
		}
	});

	// $("#btnSimpan").on("click", function() {

	// 	let reports = [];

	// 	// ambil habit yang dicentang
	// 	let habits = $("input.habit:checked");

	// 	if (habits.length === 0) {
	// 		alert("Pilih minimal 1 kebiasaan");
	// 		return;
	// 	}

	// 	// ambil semua student
	// 	$("input[name='student_id[]']").each(function() {

	// 		let student_id = $(this).val();

	// 		// ambil status hadir / tidak
	// 		let status = $("input[name='presensi_kebiasaan_status" + student_id + "[]']:checked").val();

	// 		// jika tidak dipilih skip
	// 		if (!status) return;

	// 		habits.each(function() {

	// 			let habit_id = $(this).val();

	// 			reports.push({
	// 				student_id: student_id,
	// 				habit_id: habit_id,
	// 				note: "",
	// 				created_by: "Admin",
	// 				date: "2026-02-19"
	// 			});

	// 		});

	// 	});

	// 	console.log(reports);

	// });

	$("#btnSimpan").click(function() {
		let reports = [];
		let namaGuru = $('#guru').val();
		let kelas_nama = $("#kelas option:selected").text();
		let created_by = "Teacher";
		let date = $('#today').val();
		let note = 'Presensi Kebiasaan Siswa Kelas ' + kelas_nama + ' oleh ' + namaGuru;

		// ambil habit yang dipilih
		let habits = [];
		$(".habit:checked").each(function() {

			habits.push($(this).val());

		});

		// VALIDASI HABIT
		if (habits.length == 0) {
			alert("Pilih minimal 1 kebiasaan");

			return;
		}

		// ambil student yang dipilih
		let students = [];

		$(".hadir:checked").each(function() {

			let name = $(this).attr("name");

			if (name.includes("presensi_kebiasaan_status")) {

				let student_id = name.replace("presensi_kebiasaan_status", "").replace("[]", "");

				students.push(student_id);

			}

		});

		// VALIDASI STUDENT
		if (students.length == 0) {

			alert("Pilih minimal 1 siswa");

			return;

		}

		// generate kombinasi student x habit
		students.forEach(function(student_id) {

			habits.forEach(function(habit_id) {

				reports.push({

					student_id: student_id,
					habit_id: habit_id,
					note: note,
					created_by: created_by,
					date: date

				});

			});

		});

		$.ajax({
			url: '<?php echo base_url(); ?>guru/presensi_kebiasaan/add_glob',
			type: 'post',
			dataType: 'json',
			data: {
				presensi: reports
			},
			success: function(result) {
				if (result.body.status == "success") {
					alert('Simpan Presensi kebiasaan Berhasil');
					window.location.href = "<?= base_url('guru/presensi_kebiasaan') ?>";
				} else {
					alert('Gagal simpan presensi kebiasaan');
				}
			},
			error: function() {
				alert('Gagal simpan presensi kebiasaan');
			}

		});


		// alert("Data generated, cek console");
	});
</script>