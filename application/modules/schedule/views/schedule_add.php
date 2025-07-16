<?php

if (isset($schedule)) {

	$inputLessonValue = $schedule['lesson_name'];
} else {

	$inputLessonValue = set_value('lesson_name');
}
?>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/schedule') ?>">Mata Pelajaran</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<!-- Main content -->
	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($schedule)) { ?>
							<input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">
							<input type="hidden" name="majors_id" value="<?php echo $schedule['majors_id']; ?>">
							<input type="hidden" name="class_id" value="<?php echo $schedule['schedule_class_id']; ?>">
						<?php } ?>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Hari</label>
									<select class="form-control" name="schedule_day" id="schedule_day">
										<option value="">-- Pilih Hari --</option>
										<?php foreach ($day as $row) { ?>
											<option value="<?= $row['day_id'] ?>" <?= ($schedule['schedule_day'] == $row['day_id']) ? 'selected' : '' ?>><?= $row['day_name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Pelajaran</label>
									<select class="form-control" name="schedule_lesson">
										<option value="">-- Pilih Mata Pelajaran --</option>
										<?php foreach ($lesson as $row) : ?>
											<option value="<?php echo $row['lesson_id'] ?>" <?= ($schedule['schedule_lesson_id'] == $row['lesson_id']) ? 'selected' : '' ?>><?php echo $row['lesson_code'] . ' - ' . str_replace("'", "", $row['employee_name']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Pilih Jam Pelajaran</label>
									<select class="form-control" name="schedule_jampel">
										<option value="">-- Pilih Jam Pelajaran --</option>
										<?php foreach ($jam_pelajaran as $row) : ?>
											<option value="<?php echo $row['jam_pelajaran_id'] ?>" <?= ($schedule['schedule_jampel'] == $row['jam_pelajaran_id']) ? 'selected' : '' ?>><?php echo $row['jam_pelajaran_name'] . ' (' . $row['jam_pelajaran_start'] . ' S/d ' . $row['jam_pelajaran_end'] . ' )' ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>

						<p class="text-muted">*) Kolom wajib diisi.</p>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
			<div class="col-md-3">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
						<a href="<?php echo site_url('manage/schedule?n=' . $schedule['majors_id'] . '&r=' . $schedule['schedule_class_id']); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>