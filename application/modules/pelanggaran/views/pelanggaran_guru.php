<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body">
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
						<?php if ($f) {
							$check_kelas = $this->db->query("SELECT * FROM wali_kelas WHERE wali_kelas_class_id = '$f[c]'")->num_rows();
							if ($check_kelas > 0) : ?>
								<div class="row mt-10">

									<div class="col-md-12">
										<div class="box-body table-responsive">
											<table id="dtable" class="table table-bordered" style="white-space: nowrap;">
												<thead>
													<tr class="info">
														<th>No.</th>
														<th>Nama</th>
														<th>Tanggal</th>
														<th>Pelanggaran</th>
														<th>Poin</th>
														<th>Tindakan</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													foreach ($konselings as $row) :
													?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $row['student_full_name'] ?></td>
															<td><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
															<td><?php echo $row['konseling_foul'] ?></td>
															<td><?php echo $row['konseling_poin'] ?></td>
															<td><?php echo $row['konseling_note'] ?></td>
															<td><?php echo $row['konseling_action'] ?></td>
														</tr>
													<?php
														$i++;
													endforeach;
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div><?php
									else :
										?>
								<div class="box-body table-responsive">
									Maaf, Anda Bukan Wali dari Kelas Tersebut!
								</div>
						<?php
									endif;
								} ?>
						<!-- /.box-body -->
					</div><!-- /.box -->


				</div>
			</div>
		</div>
	</section>
</div>