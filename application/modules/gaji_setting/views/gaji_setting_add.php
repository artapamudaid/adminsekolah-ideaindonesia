<?php

if (isset($gaji_setting)) {

	$inputDefNameValue = $gaji_setting['default_gaji_name'];
	$inputDefMajorsValue  = $gaji_setting['default_gaji_majors_id'];
	$inputDefModeValue = $gaji_setting['default_gaji_mode'];
	$inputMajorsNameValue = $gaji_setting['majors_short_name'];
	
} else {
	$inputDefNameValue = set_value('default_gaji_name');
	$inputDefMajorsValue  = set_value('default_gaji_majors_id');
	$inputDefModeValue = set_value('default_gaji_mode');
	$inputMajorsNameValue = set_value('majors_short_name');
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
			<li><a href="<?php echo site_url('manage/majors') ?>">Unit Sekolah</a></li>
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
						<?php if (isset($gaji_setting)) { ?>
						<input type="hidden" name="default_gaji_id" value="<?php echo $gaji_setting['default_gaji_id']; ?>">
						<?php } ?>
						

						<div class="form-group">
							<label>Nama Setting Penggajian <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="default_gaji_name" type="hidden" class="form-control" value="<?php echo $inputDefNameValue.' '. $inputMajorsNameValue ?>" placeholder="Isi Nama Unit Sekolah">
						</div>

						<div class="form-group">
							<label>Unit <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="default_gaji_majors_id" id="default_gaji_majors_id" class="form-control">
								<option value="<?php echo ($inputDefMajorsValue != NULL ? $inputDefMajorsValue : '')?>"><?php echo ($inputMajorsNameValue != NULL ? $inputMajorsNameValue : '-- Pilih Unit --') ?></option>
								<?php foreach ($majors as $row): ?>
									<option value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label>Model Setting <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="default_gaji_mode" class="form-control">
								<option value="<?php echo $inputDefModeValue ?>"><?php echo ($inputDefModeValue==0 ? 'Penggajian' : 'Potongan') ?></option>
								<option value="0">Penggajian</option>
								<option value="1">Potongan</option>
							</select>
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
						<a href="<?php echo site_url('manage/gaji_setting'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>