<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header">
						<?= form_open(current_url(), array('method' => 'get')) ?>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label>Kelas</label>
									<select class="form-control" name="c" id="class_id" required="" onchange="get_lesson()">
										<option value="">-- Pilih Kelas --</option>
										<?php foreach ($class as $row) : ?>
											<option <?= (isset($q['c']) and $q['c'] == $row['class_id']) ? 'selected' : '' ?> value="<?= $row['class_id'] ?>"><?= $row['class_name'] ?></option>
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
													<option <?= (isset($q['c']) and $q['l'] == $row['lesson_id']) ? 'selected' : '' ?> value="<?= $row['lesson_id'] ?>"><?= $row['lesson_code'] . ' - ' . $row['lesson_name'] ?></option>
												<?php endforeach; ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div id="td_lesson">
								<div class="col-md-12 col-sm-12">
									<div class="form-group">
										<label>Penilaian</label>
										<select class="form-control" name="n" id="nilai_id" required="">
											<option value="">-- Pilih Jenis Penilaian --</option>
											<?php if (isset($q['c'])) { ?>
												<option <?= (isset($q['n']) and $q['n'] == 'harian') ? 'selected' : '' ?> value="harian">Harian</option>
												<option <?= (isset($q['n']) and $q['n'] == 'sumatif') ? 'selected' : '' ?> value="sumatif">Sumatif</option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div style="margin-top:10px;">
									<button type="submit" class="btn btn-primary btn-block"> Pilih</button>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>

				<?php if ($q) { ?>


					<?php if (!empty($student)) { ?>
						<div class="box box-success">
							<div class="box-body table-responsive">
								<?= form_open('guru/nilai/save', array('method' => 'post')); ?>
								<input type="hidden" name="class_id" value="<?= $q['c'] ?>" required>
								<input type="hidden" name="semester_id" value="<?= $semester['semester_id'] ?>" required>
								<input type="hidden" name="lesson_id" value="<?= $q['l'] ?>" required>
								<input type="hidden" name="period_id" value="<?= $period['period_id'] ?>" required>
								<input type="hidden" name="nilai" value="<?= $q['n'] ?>">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th width='5' rowspan='2' style="vertical-align: middle;">
												<center>No.</center>
											</th>
											<th rowspan="2" width="30%">
												<center>Nama</center>
											</th>
											<th colspan='<?= count($nilai_item) ?>'>
												<center>Nilai <?= ucfirst($q['n']) ?></center>
											</th>
										</tr>
										<tr>
											<?php
											$results = array();
											foreach ($nilai_item as $n) :
											?>
												<?php
												$key = $n;
												$results[$key] = 0;
												?>
												<th>
													<?= str_replace($q['n'] . '_', '', $n) ?>
												</th>
											<?php
											endforeach;
											$results[$q['n'] . '_student_id'] = null;
											?>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										$data = array();
										$datax = array();
										$datay = array();
										$datas = array();
										if (!empty($nilai)) {

											foreach ($nilai as $value) {
												$datas[] = $value;
											}


											foreach ($datas as $item) {
												$items = array();
												foreach ($item as $key => $value) {
													if (array_key_exists($key, $results)) {
														$items[$key] = $value;
													}
												}
												$datax[] = $items;
											}
											foreach ($datax as $valuex) {
												foreach ($valuex as $keyx => $valx) {
													$datay[$keyx] = $valx;
												}
												$data[] = $datay;
											}
										} else {

											foreach ($results as $res => $result) {
												$data[$res] = $result;
											}
										}

										foreach ($student as $row) :
										?>
											<tr>
												<td><?= $no++ ?><input type="hidden" name="student_id[]" value="<?= $row['student_id'] ?>"></td>
												<td width="30%"><?= $row['student_full_name'] ?></td>

												<?php
												$nilai_found = false;
												if (!empty($nilai)) { ?>
													<?php foreach ($data as $array) : ?>
														<?php foreach ($array as $key => $val) : ?>
															<?php
															if ($array[$q['n'] . '_student_id'] == $row['student_id']) : ?>
																<?php foreach ($nilai_item as $n) : ?>
																	<?php if ($key == $n) : ?>
																		<td>
																			<input type="number" name="<?= $n . '[]' ?>" value="<?= $array[$n] ?>" style="width: 50px;">
																		</td>
																	<?php endif; ?>
																<?php endforeach; ?>
																<?php $nilai_found = true; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endforeach; ?>
												<?php }

												if (!$nilai_found) { ?>
													<?php foreach ($nilai_item as $n) : ?>
														<td>
															<input type="number" name="<?= $n . '[]' ?>" value="" style="width: 50px;">
														</td>
													<?php endforeach; ?>
												<?php } ?>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>

								<div class="col-md-12 col-sm-12">
									<button type="submit" class="btn btn-success btn-block">Simpan</button>
								</div>
								<?= form_close(); ?>
							</div>
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
				} ?>




			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<script>
	function get_lesson() {
		var class_id = $("#class_id").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?= base_url(); ?>guru/nilai/lesson_searching',
			type: 'POST',
			data: {
				'class_id': class_id,
			},
			success: function(msg) {
				$("#td_lesson").html(msg);
			},
			error: function(msg) {
				alert('msg');
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
</script>