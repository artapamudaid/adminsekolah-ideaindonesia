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
						<h3 class="box-title">Filter Data Konseling Siswa</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
							<div class="col-sm-2">
								<select class="form-control" name="n" id="th_ajar">
									<?php foreach ($period as $row): ?>
										<option <?php if (isset($f['n']) and $f['n'] == $row['period_id']) {
													echo 'selected';
												} else if (empty($f['n']) and $periodActive['period_id'] == $row['period_id']) {
													echo 'selected';
												} else {
													echo '';
												} ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<label for="" class="col-sm-2 control-label">Cari Siswa</label>
							<div class="col-sm-4">
								<div class="input-group">
									<input type="text" class="form-control" autofocus name="r" id="student_nis" <?php echo (isset($f['r'])) ? 'value="' . $f['r'] . '"' : 'placeholder="NIS Siswa"' ?> required>
									<span class="input-group-btn">
										<button class="btn btn-success" type="submit">Cari</button>
									</span>
									<span class="input-group-btn">
									</span>
									<span class="input-group-btn">
									</span>
									<span class="input-group-btn">
									</span>
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" data-toggle="modal" data-target="#dataSiswa"><b>Data Siswa</b></button>
									</span>
								</div>
							</div>
						</div>
						</form>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
		</div>

		<?php if ($f && $f['r'] !== '') { ?>
			<!-- Data Konseling Siswa -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Informasi Siswa</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="col-md-9">
						<table class="table table-striped">
							<tbody>
								<tr>
									<td width="200">Tahun Ajaran</td>
									<td width="4">:</td>
									<?php foreach ($period as $row): ?>
										<?php echo (isset($f['n']) and $f['n'] == $row['period_id']) ?
											'<td><strong>' . $row['period_start'] . '/' . $row['period_end'] . '<strong></td>' : '' ?>
									<?php endforeach; ?>
								</tr>
								<tr>
									<td>NIS</td>
									<td>:</td>
									<?php foreach ($siswa as $row): ?>
										<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
											'<td>' . $row['student_nis'] . '</td>' : '' ?>
									<?php endforeach; ?>
								</tr>
								<tr>
									<td>Nama Siswa</td>
									<td>:</td>
									<?php foreach ($siswa as $row): ?>
										<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
											'<td>' . $row['student_full_name'] . '</td>' : '' ?>
									<?php endforeach; ?>
								</tr>
								<tr>
									<td>Unit Sekolah</td>
									<td>:</td>
									<?php foreach ($siswa as $row): ?>
										<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
											'<td>' . $row['majors_short_name'] . '</td>' : '' ?>
									<?php endforeach; ?>
								</tr>
								<tr>
									<td>Kelas</td>
									<td>:</td>
									<?php foreach ($siswa as $row): ?>
										<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
											'<td>' . $row['class_name'] . '</td>' : '' ?>
									<?php endforeach; ?>
								</tr>
								<?php if (majors() == 'senior') { ?>
									<tr>
										<td>Program Keahlian</td>
										<td>:</td>
										<?php foreach ($siswa as $row): ?>
											<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
												'<td>' . $row['majors_name'] . '</td>' : '' ?>
										<?php endforeach; ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-3">
						<!-- <center>
						    <a href="<?php echo base_url() . 'manage/konseling/printBook/?n=' . $f['n'] . '&r=' . $f['r']; ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-print"></i> Cetak Buku Pelanggaran</a>
						    </center> -->
						<?php foreach ($siswa as $row): ?>
							<?php if (isset($f['n']) and $f['r'] == $row['student_nis']) { ?>
								<?php if (!empty($row['student_img'])) { ?>
									<img src="<?php echo upload_url('student/' . $row['student_img']) ?>" class="img-thumbnail img-responsive">
								<?php } else { ?>
									<img src="<?php echo media_url('img/user.png') ?>" class="img-thumbnail img-responsive">
							<?php }
							} ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<!-- TAB -->
			<div id="konseling-box" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Konseling Siswa</h3>
				</div>
				<div class="box-body">
					<div class="nav-tabs-custom">
						<!-- TAB UTAMA -->
						<ul class="nav nav-tabs">
							<li class="<?php echo @$f['m'] == 0 ? 'active' : '' ?> tab-utama"> <a href="#pelanggaran" data-toggle="tab">Pelanggaran</a></li>
							<li class="<?php echo @$f['m'] == 1 ? 'active' : '' ?> tab-utama"><a href="#konsultasi" data-toggle="tab">Konsultasi</a></li>
							<li class="<?php echo @$f['m'] == 2 ? 'active' : '' ?> tab-utama"><a href="#prestasi" data-toggle="tab">Prestasi</a></li>
						</ul>

						<!-- ISI DARI TIAP TAB UTAMA -->
						<div class="tab-content">

							<!-- ================= PELANGGARAN ================= -->
							<div class="<?php echo @$f['m'] == 0 ? 'active' : '' ?> tab-pane" id="pelanggaran">
								<div class="d-flex justify-content-end mb-2" style="margin-bottom: 2px;">
									<a href="<?php echo base_url() . 'manage/konseling/printBook/?n=' . $f['n'] . '&r=' . $f['r']; ?>" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print"></i> Cetak Buku Pelanggaran</a>
								</div>
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="tab-pelanggaran active"><a href="#tambahPelanggaran" data-toggle="tab">Tambah Pelanggaran</a></li>
										<li class="tab-pelanggaran"><a href="#daftarPelanggaran" data-toggle="tab">Daftar Pelanggaran</a></li>
										<li class="tab-pelanggaran"><a href="#rekapPelanggaran" data-toggle="tab">Rekap Pelanggaran</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tambahPelanggaran">
											<!-- Form Tambah Pelanggaran (copy dari sebelumnya) -->
											<div class="row">

												<?php echo form_open('manage/konseling/add', array('method' => 'post', 'enctype' => "multipart/form-data")); ?>

												<?php if (isset($f['n'])) { ?>
													<input type="hidden" name="konseling_period_id" value="<?php echo $f['n'] ?>">
													<input type="hidden" name="konseling_student_nis" value="<?php echo $f['r'] ?>">
													<input type="hidden" name="konseling_mode" value="0">
												<?php } ?>

												<?php foreach ($siswa as $row): ?>
													<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
														'<input type="hidden" name="konseling_student_id" value=' . $row['student_id'] . '>' : '' ?>
												<?php endforeach; ?>

												<div class="col-md-2">
													<div class="form-group">
														<label>Tanggal</label>
														<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															<input class="form-control md-3" required="" type="text" name="konseling_date" value="<?php echo date("Y-m-d") ?>" placeholder="Tanggal Setor">
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label>Pelanggaran</label>
														<div style="display: flex;">
															<select name="konseling_foul" id="listPelanggaran" required="">
																<option data-poin="" value="">--Pilih Pelanggaran--</option>
																<?php foreach ($pelanggaran as $row) { ?>
																	<option data-poin="<?php echo $row->poin; ?>" value="<?php echo $row->id; ?>"><?php echo $row->pelanggaran; ?></option>
																<?php } ?>
															</select>
															<span style="padding-left: 2px;"><button id="tambah-jenis-pelanggaran" type="button" class="btn btn-success" style="height: 35px;"><i class="fa fa-plus"></i></button></span>
														</div>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<label>Poin</label>
														<input readonly type="number" class="form-control" required="" id="nilaiPoin" name="konseling_poin" class="form-control" placeholder="Poin">
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<label>Bukti Pelanggaran</label>
														<input type="file" name="bukti" id="bukti">
														<small class="text-muted">Max 1 MB</small>
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label>Tindakan</label>
														<input type="text" class="form-control" required="" name="konseling_action" class="form-control" placeholder="Tindakan">
													</div>
												</div>

												<div class="col-md-8">
													<div class="form-group">
														<label>Catatan</label>
														<input type="text" class="form-control" required="" name="konseling_note" class="form-control" placeholder="Catatan">
													</div>
												</div>

												<div class="col-md-6">
													<button type="submit" class="btn btn-success">Simpan</button>
													<button type="reset" class="btn btn-default">Kosongkan</button>
												</div>
												<?php echo form_close(); ?>
											</div>
										</div>
										<div class="tab-pane" id="daftarPelanggaran">
											<table id="dtablePelanggaran" class="table table-bordered" style="width:100%">
												<thead>
													<tr class="info">
														<th style="width: 5%;">No</th>
														<th style="width: 10%;">Tanggal</th>
														<th style="width: 30%;">Pelanggaran</th>
														<th style="width: 45%;">Tindakan</th>
														<th style="width: 5%;">Poin</th>
														<th style="width: 30%;">Catatan</th>
														<th style="width: 5%;">Aksi</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													foreach ($konseling as $row):
													?>
														<tr>
															<td style="width: 5%;"><?php echo $i ?></td>
															<td style="width: 10%;"><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
															<td style="width: 30%;white-space: normal;"><?php echo $row['konseling_foul'] ?? $row['pelanggaran'] ?></td>
															<td style="width: 15%;"><?php echo $row['konseling_action'] ?></td>
															<td style="width: 5%;"><?php echo $row['konseling_poin'] ?></td>
															<td style="width: 30%;white-space: normal;"><?php echo $row['konseling_note'] ?></td>
															<td style="width: 5%;">
																<a href="#delKonseling<?php echo $row['konseling_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
																<?php if (!empty($row['bukti'])) { ?>
																	<a target="_blank" href="<?php echo base_url('uploads/konseling/') . $row['bukti'] ?>" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" title="Bukti Pelanggaran"></i></a>
																<?php } ?>
															</td>
														</tr>

														<div class="modal modal-default fade" id="delKonseling<?php echo $row['konseling_id']; ?>">
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
																		<?php echo form_open('manage/konseling/delete/' . $row['konseling_id']); ?>

																		<?php foreach ($period as $p): ?>
																			<?php echo (isset($f['n']) and $f['n'] == $p['period_id']) ?
																				'<input type="hidden" name="period_id" value="' . $p['period_id'] . '">' : '' ?>
																		<?php endforeach; ?>
																		<?php foreach ($siswa as $s): ?>
																			<?php echo (isset($f['n']) and $f['r'] == $s['student_nis']) ?
																				'<input type="hidden" name="student_nis" value="' . $s['student_nis'] . '">' : '' ?>
																		<?php endforeach; ?>
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
													?>
												</tbody>
											</table>
										</div>
										<!-- rekapPelanggaran -->
										<div class="tab-pane" id="rekapPelanggaran">
											<table class="table table-bordered" style="white-space: nowrap;">
												<thead>
													<tr class="info">
														<th>No.</th>
														<th>NIS</th>
														<th>Nama</th>
														<th>Unit</th>
														<th>Kelas</th>
														<th>Total Poin Pelanggaran</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													foreach ($konselingSum as $row):
													?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $row['student_nis'] ?></td>
															<td><?php echo $row['student_full_name'] ?></td>
															<td><?php echo $row['majors_name'] ?></td>
															<td><?php echo $row['class_name'] ?></td>
															<td><?php echo $row['konselingSum'] . ' Poin' ?></td>
														</tr>
													<?php
														$i++;
													endforeach;
													?>
												</tbody>
											</table>
										</div>

									</div>
								</div>
							</div>

							<!-- ================= KONSULTASI ================= -->
							<div class="<?php echo @$f['m'] == 1 ? 'active' : '' ?> tab-pane" id="konsultasi">
								<div class="d-flex justify-content-end mb-2" style="margin-bottom: 2px;">
									<a href="<?php echo base_url() . 'manage/konseling/printBookKonsultasi/?n=' . $f['n'] . '&r=' . $f['r']; ?>" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print"></i> Cetak Buku Konsultasi</a>
								</div>
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active tab-konsultasi"><a href="#tambahKonsultasi" data-toggle="tab">Tambah Konsultasi</a></li>
										<li class="tab-konsultasi"><a href="#daftarKonsultasi" data-toggle="tab">Daftar Konsultasi</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tambahKonsultasi">
											<div class="row">

												<?php echo form_open('manage/konseling/add', array('method' => 'post', 'enctype' => "multipart/form-data")); ?>

												<?php if (isset($f['n'])) { ?>
													<input type="hidden" name="konseling_period_id" value="<?php echo $f['n'] ?>">
													<input type="hidden" name="konseling_student_nis" value="<?php echo $f['r'] ?>">
													<input type="hidden" name="konseling_mode" value="1">
												<?php } ?>


												<?php foreach ($siswa as $row): ?>
													<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
														'<input type="hidden" name="konseling_student_id" value=' . $row['student_id'] . '>' : '' ?>
												<?php endforeach; ?>

												<div class="col-md-2">
													<div class="form-group">
														<label>Tanggal</label>
														<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															<input class="form-control md-3" required="" type="text" name="konseling_date" value="<?php echo date("Y-m-d") ?>" placeholder="Tanggal Setor">
														</div>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<label>Bukti Konsultasi</label>
														<input type="file" name="bukti" id="buktiKonsultasi">
														<small class="text-muted">Max 1 MB</small>
													</div>
												</div>

												<div class="col-md-8">
													<div class="form-group">
														<label>Tentang Masalah</label>
														<input type="text" class="form-control" required="" name="konseling_foul" class="form-control" placeholder="Contoh : Masalah Pertemanan">
													</div>
												</div>

												<div class="col-md-12">
													<div class="form-group">
														<label>Problem/Masalah Yang di Konsultasikan</label>
														<textarea class="form-control" required="" name="konseling_action" placeholder="Masukkan Konsultasi"></textarea>
													</div>
												</div>

												<div class="col-md-12">
													<div class="form-group">
														<label>Solusi</label>
														<textarea class="form-control" required="" name="konseling_note" placeholder="Masukkan Solusi"></textarea>
													</div>
												</div>

												<div class="col-md-6">
													<button type="submit" class="btn btn-success">Simpan</button>
													<button type="reset" class="btn btn-default">Kosongkan</button>
												</div>
												<?php echo form_close(); ?>
											</div>
										</div>
										<div class="tab-pane" id="daftarKonsultasi">
											<table id="dtableKonsultasi" class="table table-bordered" style="width:100%">
												<thead>
													<tr class="info">
														<th>No.</th>
														<th>Tanggal</th>
														<th>Problem/Masalah</th>
														<th>Problem/Masalah Yang di Konsultasikan</th>
														<th>Solusi</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													foreach ($konsultasi as $row):
													?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
															<td><?php echo $row['konseling_foul'] ?></td>
															<td><?php echo $row['konseling_action'] ?></td>
															<td><?php echo $row['konseling_note'] ?></td>
															<td>
																<a href="#delKonseling<?php echo $row['konseling_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
																<?php if (!empty($row['bukti'])) { ?>
																	<a target="_blank" href="<?php echo base_url('uploads/konseling/') . $row['bukti'] ?>" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" title="Bukti Pelanggaran"></i></a>
																<?php } ?>
															</td>
														</tr>

														<div class="modal modal-default fade" id="delKonseling<?php echo $row['konseling_id']; ?>">
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
																		<?php echo form_open('manage/konseling/delete/' . $row['konseling_id']); ?>

																		<?php foreach ($period as $p): ?>
																			<?php echo (isset($f['n']) and $f['n'] == $p['period_id']) ?
																				'<input type="hidden" name="period_id" value="' . $p['period_id'] . '">' : '' ?>
																		<?php endforeach; ?>
																		<?php foreach ($siswa as $s): ?>
																			<?php echo (isset($f['n']) and $f['r'] == $s['student_nis']) ?
																				'<input type="hidden" name="student_nis" value="' . $s['student_nis'] . '">' : '' ?>
																		<?php endforeach; ?>
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
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<!-- ================= PRESTASI ================= -->
							<div class="<?php echo @$f['m'] == 2 ? 'active' : '' ?> tab-pane" id="prestasi">
								<div class="d-flex justify-content-end mb-2" style="margin-bottom: 2px;">
									<a href="<?php echo base_url() . 'manage/konseling/printBookPrestasi/?n=' . $f['n'] . '&r=' . $f['r']; ?>" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print"></i> Cetak Buku Prestasi</a>
								</div>
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active tab-prestasi"><a href="#tambahPrestasi" data-toggle="tab">Tambah Prestasi</a></li>
										<li class="tab-prestasi"><a href="#daftarPrestasi" data-toggle="tab">Daftar Prestasi</a></li>
										<li class="tab-prestasi"><a href="#rekapPrestasi" data-toggle="tab">Rekap Prestasi</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tambahPrestasi">
											<div class="row">
												<?php echo form_open('manage/konseling/add', array('method' => 'post', 'enctype' => "multipart/form-data")); ?>

												<?php if (isset($f['n'])) { ?>
													<input type="hidden" name="konseling_period_id" value="<?php echo $f['n'] ?>">
													<input type="hidden" name="konseling_student_nis" value="<?php echo $f['r'] ?>">
													<input type="hidden" name="konseling_mode" value="2">
												<?php } ?>


												<?php foreach ($siswa as $row): ?>
													<?php echo (isset($f['n']) and $f['r'] == $row['student_nis']) ?
														'<input type="hidden" name="konseling_student_id" value=' . $row['student_id'] . '>' : '' ?>
												<?php endforeach; ?>

												<div class="col-md-2">
													<div class="form-group">
														<label>Tanggal</label>
														<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															<input class="form-control md-3" required="" type="text" name="konseling_date" value="<?php echo date("Y-m-d") ?>" placeholder="Tanggal Setor">
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label>Prestasi</label>
														<div style="display: flex;">
															<select name="konseling_foul" id="listPrestasi" required="">
																<option data-poin="" value="">--Pilih Prestasi--</option>
																<?php foreach ($prestasi as $row) { ?>
																	<option data-poin="<?php echo $row->poin; ?>" value="<?php echo $row->id; ?>"><?php echo $row->prestasi; ?></option>
																<?php } ?>
															</select>
															<span style="padding-left: 2px;"><button id="tambah-jenis-prestasi" type="button" class="btn btn-success" style="height: 35px;"><i class="fa fa-plus"></i></button></span>
														</div>
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<label>Poin</label>
														<input readonly type="number" class="form-control" required="" id="poinPrestasi" name="konseling_poin" class="form-control" placeholder="Poin">
													</div>
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<label>Bukti Prestasi</label>
														<input type="file" name="bukti" id="buktiPrestasi">
														<small class="text-muted">Max 1 MB</small>
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label>Tujuan</label>
														<input type="text" class="form-control" required="" name="konseling_action" class="form-control" placeholder="Tujuan">
													</div>
												</div>

												<div class="col-md-8">
													<div class="form-group">
														<label>Catatan</label>
														<input type="text" class="form-control" required="" name="konseling_note" class="form-control" placeholder="Catatan">
													</div>
												</div>
												<div class="col-md-6">
													<button type="submit" class="btn btn-success">Simpan</button>
													<button type="reset" class="btn btn-default">Kosongkan</button>
												</div>
												<?php echo form_close(); ?>
											</div>
										</div>
										<div class="tab-pane" id="daftarPrestasi">
											<!-- Table Daftar Prestasi -->
											<table id="dtablePrestasi" class="table table-bordered" style="width:100%;">
												<thead>
													<tr class="info">
														<th style="width: 5%;">No.</th>
														<th style="width: 15%;">Tanggal</th>
														<th style="width: 20%;">Prestasi</th>
														<th style="width: 30%;">Tujuan</th>
														<th style="width: 5%;">Poin</th>
														<th style="width: 10%;">Catatan</th>
														<th style="width: 5%;">Aksi</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													foreach ($prestasi_siswa as $row):
													?>
														<tr>
															<td style="width: 5%;"><?php echo $i ?></td>
															<td><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
															<td><?php echo $row['prestasi'] ?></td>
															<td><?php echo $row['konseling_action'] ?></td>
															<td style="width: 5%;"><?php echo $row['konseling_poin'] ?></td>
															<td><?php echo $row['konseling_note'] ?></td>
															<td style="width: 5%;">
																<a href="#delKonseling<?php echo $row['konseling_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
																<?php if (!empty($row['bukti'])) { ?>
																	<a target="_blank" href="<?php echo base_url('uploads/konseling/') . $row['bukti'] ?>" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" title="Bukti Pelanggaran"></i></a>
																<?php } ?>
															</td>
														</tr>

														<div class="modal modal-default fade" id="delKonseling<?php echo $row['konseling_id']; ?>">
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
																		<?php echo form_open('manage/konseling/delete/' . $row['konseling_id']); ?>

																		<?php foreach ($period as $p): ?>
																			<?php echo (isset($f['n']) and $f['n'] == $p['period_id']) ?
																				'<input type="hidden" name="period_id" value="' . $p['period_id'] . '">' : '' ?>
																		<?php endforeach; ?>
																		<?php foreach ($siswa as $s): ?>
																			<?php echo (isset($f['n']) and $f['r'] == $s['student_nis']) ?
																				'<input type="hidden" name="student_nis" value="' . $s['student_nis'] . '">' : '' ?>
																		<?php endforeach; ?>

																		<input type="hidden" name="hapus_bukti" value="<?php echo $row['bukti'] ?>">

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
													?>
												</tbody>
											</table>
										</div>

										<div class="tab-pane" id="rekapPrestasi">
											<table class="table table-bordered" style="white-space: nowrap;">
												<thead>
													<tr class="info">
														<th>No.</th>
														<th>NIS</th>
														<th>Nama</th>
														<th>Unit</th>
														<th>Kelas</th>
														<th>Total Poin Prestasi</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													foreach ($rekapPrestasiSiswa as $row):
													?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $row['student_nis'] ?></td>
															<td><?php echo $row['student_full_name'] ?></td>
															<td><?php echo $row['majors_name'] ?></td>
															<td><?php echo $row['class_name'] ?></td>
															<td><?php echo $row['konselingSum'] . ' Poin' ?></td>
														</tr>
													<?php
														$i++;
													endforeach;
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

						</div> <!-- /.tab-content utama -->
					</div> <!-- /.nav-tabs-custom -->
				</div>
			</div>
			<!-- TAB -->
			<!-- Data Konseling Siswa -->
		<?php } ?>
	</section>
</div>

<div class="modal fade" id="dataSiswa" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cari Data Siswa</h4>
			</div>
			<div class="modal-body">
				<?php $dataSiswa = $this->Student_model->get(array('status' => '1'));

				echo '
            <div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
							    <thead>
								<tr>
									<th>No</th>
									<th>NIS</th>
									<th>Nama</th>
									<th>Unit Sekolah</th>
									<th>Kelas</th>
									<th>Aksi</th>
								</tr>
								</thead>
								<tbody>';
				if (!empty($dataSiswa)) {
					$i = 1;
					foreach ($dataSiswa as $row):
						echo '<tr>
												<td>' .
							$i
							. '</td>
												<td>' .
							$row['student_nis']
							. '</td>
												<td>' .
							$row['student_full_name']
							. '</td>
												<td>' .
							$row['majors_short_name']
							. '</td>
												<td>' .
							$row['class_name']
							. '</td>';
						echo '<td align="center">';
						echo '<button type="button" data-dismiss="modal" class="btn btn-primary btn-xs" onclick="ambil_data(';
						echo "'" . $row['student_nis'] . "'";
						echo ')">Pilih</button>';
						echo '</td>';
						echo '</tr>';
						$i++;
					endforeach;
				} else {
					echo '<tr id="row">
											<td colspan="8" align="center">Data Kosong</td>
										</tr>';
				}
				echo	'</tbody>
								</table>
							</div>
      '; ?>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalTambahJenisPelanggaran" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Jenis Pelanggaran</h4>
			</div>
			<form action="<?php echo base_url('manage/konseling/add_jenis_pelanggaran') ?>" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="period" value="<?php echo $f['n'] ?>">
					<input type="hidden" name="nis" value="<?php echo $f['r'] ?>">
					<div class="form-group">
						<label>Kategori Pelanggaran</label>
						<select class="form-control" name="kategori_pelanggaran" required>
							<?php foreach ($kategori_pelanggaran as $kp) { ?>
								<option value="<?php echo $kp['id'] ?>"><?php echo $kp['kategori'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Nama Pelanggaran</label>
						<input class="form-control" required="" type="text" name="nama_pelanggaran" value="" placeholder="Contoh: Tidak membawa buku">
					</div>
					<div class="form-group">
						<label>Poin Pelanggaran</label>
						<input class="form-control" required="" type="number" name="poin_pelanggaran" value="" placeholder="Contoh: 10">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalTambahJenisPrestasi" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Jenis Prestasi</h4>
			</div>
			<form action="<?php echo base_url('manage/konseling/add_jenis_prestasi') ?>" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="period" value="<?php echo $f['n'] ?>">
					<input type="hidden" name="nis" value="<?php echo $f['r'] ?>">
					<div class="form-group">
						<label>Kategori Prestasi</label>
						<select class="form-control" name="kategori_prestasi" required>
							<?php foreach ($kategori_prestasi as $kp) { ?>
								<option value="<?php echo $kp['id'] ?>"><?php echo $kp['kategori'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label>Nama Prestasi</label>
						<input class="form-control" required="" type="text" name="nama_prestasi" value="" placeholder="Contoh: Tidak membawa buku">
					</div>
					<div class="form-group">
						<label>Poin Prestasi</label>
						<input class="form-control" required="" type="number" name="poin_prestasi" value="" placeholder="Contoh: 10">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		$("#dtablePelanggaran").DataTable();
		$("#dtableKonsultasi").DataTable();
		$("#dtablePrestasi").DataTable();
		jQuery("#listPelanggaran").select2({
			width: '100%'
		});

		jQuery("#listPrestasi").select2({
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

	$("#listPelanggaran").change(function() {
		var poin = $("#listPelanggaran option:selected").attr("data-poin");
		// console.log(poin);
		$("#nilaiPoin").val(poin);
	});

	$("#tambah-jenis-pelanggaran").click(function() {
		$("#modalTambahJenisPelanggaran").modal('show');
	});

	$("#listPrestasi").change(function() {
		var poin = $("#listPrestasi option:selected").attr("data-poin");
		// console.log(poin);
		$("#poinPrestasi").val(poin);
	});

	$("#tambah-jenis-prestasi").click(function() {
		$("#modalTambahJenisPrestasi").modal('show');
	});

	function ambil_data(nis) {
		var nisSiswa = nis;
		var thAjaran = $("#th_ajar").val();

		window.location.href = '<?php echo base_url(); ?>manage/konseling?n=' + thAjaran + '&r=' + nisSiswa;
	}

	function get_kelas() {
		var id_majors = $("#majors_id").val();

		$.ajax({
			url: '<?php echo base_url(); ?>manage/konseling/get_kelas',
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
</script>

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