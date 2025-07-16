<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<?php
function createSummary($text)
{
	$limit = 300;
	$textWithoutTags = strip_tags($text);
	$textWithoutImages = preg_replace('/<img[^>]*>/', '', $textWithoutTags);

	$visibleText = mb_substr($textWithoutImages, 0, $limit);

	$lastPeriodPos = mb_strrpos($visibleText, '.');

	if ($lastPeriodPos !== false) {
		$visibleText = mb_substr($visibleText, 0, $lastPeriodPos + 1);
	}

	return $visibleText;
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
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
				<div class="box">
					<div class="box-header">
						<a href="<?php echo site_url('manage/program/add') ?>" type=" button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</a>
						<br>
						<br>

						<div class="box-body table-responsive">
							<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
							<table>
								<tr>
									<td>
										<select style="width: 200px;" id="m" name="m" class="form-control" required>
											<option value="">--- Pilih Unit ---</option>
											<?php if ($this->session->userdata('umajorsid') == '0') { ?>
												<option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
											<?php } ?>
											<?php foreach ($majors as $row) { ?>
												<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
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
							<?php echo form_close(); ?>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<div class="row">
							<?php foreach ($program as $row) : ?>
								<div class="col-md-4">
									<div class="card" style="padding: 10px; border: 1pt solid #d2d6de; box-shadow: 5px 5px 10px #d2d6de;">
										<img class="card-img-top img-fluid" src="<?= base_url() . 'uploads/program/' . $row['program_gambar'] ?>" width="315px" height="350px" alt="Gambar Program">
										<div class="card-body">
											<h3 class="card-title" style="font-weight: bold;"><?= $row['program_name'] ?></h3>
											<p class="card-text"><?= (strlen($row['program_description']) > 300) ? createSummary($row['program_description']) . '...' :  $row['program_description'] ?></p>
											<?php
											$today = new DateTime(date('Y-m-d'));
											$endDate = new DateTime(strval($row['program_end']));
											$interval = $today->diff($endDate);

											$progress = $row['program_earn'] / $row['program_target'] * 100;

											$progress = floor($progress);

											if ($progress >= 100) {
												$bg = 'progress-bar-success';
											} else if ($progress < 100 && $progress >= 50) {
												$bg = 'progress-bar-info';
											} else if ($progress < 50 && $progress >= 25) {
												$bg = 'progress-bar-warning';
											} else {
												$bg = 'progress-bar-danger';
											}
											?>
											<div class="row">
												<div class="col-md-12">
													<h3 style="font-weight: bold;">Rp <?= number_format($row['program_earn'], '0', ',', '.') ?></h3>
												</div>
												<div class="col-md-8">
													<p>Terkumpul Dari <?= number_format($row['program_target'], '0', ',', '.') ?></p>
												</div>
												<div class="col-md-4">
													<p class="pull-right"><?= $interval->format('%a Hari Lagi') ?></p>
												</div>
											</div>
											<div class="progress">
												<div class="progress-bar progress-bar-striped <?= $bg ?> active" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											<div class="row" style="margin-top: 20px;">
												<div class="col-md-6">
													<a href="<?php echo site_url('manage/program/edit/' . $row['program_id']) ?>" class="btn btn-primary" data-toggle="tooltip" title="Edit"> Edit Program</a>
												</div>
												<div class="col-md-6">
													<?php if ($row['program_status'] == 1) { ?>
														<button class="btn btn-warning pull-right" title="Simulasi Donasi" data-toggle="modal" data-target="#addDonasi<?= $row['program_id'] ?>">Simulasi Donasi</button>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="modal fade" id="addDonasi<?= $row['program_id'] ?>" role="dialog">
									<div class="modal-dialog modal-md">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Data Donasi Anda</h4>
											</div>
											<?php echo form_open('manage/program/create_donasi', array('method' => 'post')); ?>
											<div class="modal-body">
												<div class="row">
													<input type="hidden" name="program_program_id" class="form-control" value="<?= $row['program_id'] ?>">
													<input type="hidden" name="program_name" class="form-control" value="<?= $row['program_name'] ?>">
													<div class="col-md-12">
														<div class="form-group">
															<label for="">Nama Anda <small>*</small></label>
															<input type="text" required="" name="donasi_name" class="form-control" placeholder="Masukkan Nama Anda">
														</div>
														<div class="form-check">
															<input class="form-check-input" type="checkbox" value="Hamba Allah" name="as_anonim" id="as_anonim">
															<label class="form-check-label" for="as_anonim" style="color:lightslategray">
																Donasi Sebagai Hamba Allah
															</label>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group"><label for="">No. Whatsapp <small>*</small></label>
															<input type="text" required="" name="donasi_hp" class="form-control" placeholder="Masukkan No. Whatsapp Anda">
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group"><label for="">Email</label>
															<input type="email" name="donasi_email" class="form-control" placeholder="Masukkan No. Email Anda">
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group"><label for="">Nominal Donasi <small>*</small></label>
															<input type="number" name="donasi_nominal" class="form-control" placeholder="Masukkan Nominal Donasi">
														</div>
														<p>*) Nominal Donasi Minimal Rp 10.000</p>
													</div>

												</div>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-success">Simpan</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
											<?php echo form_close(); ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
	</section>
	<!-- /.content -->
</div>