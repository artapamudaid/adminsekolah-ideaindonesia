<div class="content-wrapper">
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
									<select class="form-control" name="c" id="class_id" required="" onchange="get_lesson()">
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
							<div id="td_lesson">
								<div class="col-md-12 col-sm-12">
									<div class="form-group">
										<label>Penilaian</label>
										<select class="form-control" name="n" id="nilai_id" required="">
											<option value="">-- Pilih Jenis Penilaian --</option>
											<option <?php echo (isset($q['n']) and $q['n'] == 'harian') ? 'selected' : '' ?> value="harian">Harian</option>
											<option <?php echo (isset($q['n']) and $q['n'] == 'sumatif') ? 'selected' : '' ?> value="sumatif">Sumatif</option>
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


					<?php if (!empty($student)) { ?>
						<div class="box box-success">
							<div class="box-body table-responsive">
								<center>
									<h3>Data nilai</h3>
								</center>
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
				} else { ?>

				<?php
				}
				?>




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
			url: '<?php echo base_url(); ?>guru/nilai/lesson_searching',
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