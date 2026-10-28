<div class="content-wrapper">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">

						<div class="row">
							<div class="col-md-9"><br>
								<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
								<table>
									<tr>
										<td>
											<select style="width: 300px;" id="c" name="c" class="form-control" required>
												<option value="">--- Pilih Kelas ---</option>
												<?php foreach ($class as $row) { ?>
													<option value="<?php echo $row['class_id']; ?>" <?php echo (isset($f['c']) && $f['c'] == $row['class_id']) ? 'selected' : '' ?>><?php echo $row['class_name'] ?></option>
												<?php } ?>
											</select>
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											<button type="submit" class="btn btn-primary">Pilih</button>
										</td>
									</tr>
								</table>
								<?php echo form_close(); ?>
							</div>
						</div>
						<!-- /.box-header -->
						<?php if ($f):
							$check_kelas = $this->db->query("SELECT * FROM wali_kelas WHERE wali_kelas_class_id = '$f[c]'")->num_rows();
							if ($check_kelas > 0) :
						?>
								<div class="box-body table-responsive">
									<table id="dtable" class="table table-bordered">
										<thead>
											<tr>
												<th>No.</th>
												<th>Nama</th>
												<th>Kelas</th>
												<th>Catatan</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (!empty($student)) {
												$i = 1;
												foreach ($student as $row) :
											?>
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo $row['student_full_name']; ?></td>
														<td><?php echo $row['class_name']; ?></td>
														<td>
															<textarea name="catatan" rows="4" cols="20"
																data-student-id="<?= $row['student_id'] ?>"
																placeholder="Masukkan Catatan Santri"
																class="form-control catatan" required><?= $row['catatan_text'] ?></textarea>
														</td>
													</tr>
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
							<?php
							else :
							?>
								<div class="box-body table-responsive">
									Maaf, Anda Bukan Wali dari Kelas Tersebut!
								</div>
						<?php
							endif;
						endif; ?>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
			</form>
		</div>
		<!-- /.content -->
	</section>
</div>

<script>
	$(document).ready(function() {
		$('.catatan').on('input', function() {
			var studentId = $(this).data('student-id');
			var catatanGuru = $(this).val();
			$.ajax({
				type: "POST",
				url: "<?= base_url('guru/catatan/change_catatan') ?>",
				data: {
					student_id: studentId,
					catatan: catatanGuru
				},
				success: function(response) {
					console.log(response); // Tampilkan respon dari server
				},
				error: function() {
					alert("Terjadi kesalahan saat mengirim data.");
				}
			});
		});
	});
</script>