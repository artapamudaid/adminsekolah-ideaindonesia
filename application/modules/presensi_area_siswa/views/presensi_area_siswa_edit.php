<?php

if (isset($presensi_area_siswa)) {
	$inputIdValue = $presensi_area_siswa['id'];
	$inputIdAreaAbsensiValue = $presensi_area_siswa['id_area_absensi'];
	$inputRadiusValue = $presensi_area_siswa['radius'];
} else {
	$inputIdValue = set_value('id');
	$inputIdAreaAbsensiValue = set_value('id_area_absensi');
	$inputRadiusValue = set_value('radius');
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
			<li><a href="<?php echo site_url('manage/presensi_area_siswa') ?>">Manage Area Presensi Siswa</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($presensi_area_siswa)) { ?>
							<input type="hidden" name="id" value="<?php echo $inputIdValue; ?>">
						<?php } ?>

						<div class="form-group">
							<label>Area Absen <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="id_area_absensi" id="id_area_absensi" class="form-control" required>
								<option value=""> -- Pilih Area Absen -- </option>
								<?php
								if (!empty($area_absen)) {
									foreach ($area_absen as $aa):
										$s = ($inputIdAreaAbsensiValue == $aa['id_area']) ? "selected" : "";
								?>
										<option value="<?php echo $aa['id_area'] ?>" <?php echo $s ?>> <?php echo $aa['nama_area'] ?> </option>
								<?php
									endforeach;
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Jarak Radius Absen (Meter) <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="radius" type="number" class="form-control" value="<?php echo $inputRadiusValue ?>" placeholder="Jarak Radius Absen (Meter)" required>
						</div>
						<p class="text-muted">*) Kolom wajib diisi.</p>
						<div class="form-group">
							<button type="submit" class="btn btn-success">Simpan</button>
							<a href="<?php echo site_url('manage/presensi_area_siswa'); ?>" class="btn btn-danger">Batal</a>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>