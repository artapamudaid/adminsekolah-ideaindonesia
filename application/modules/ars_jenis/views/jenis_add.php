<?php

if (isset($jenis)) {

	$inputJenisValue = $jenis['jenis_arsip'];
	$inputMajorsValue = $jenis['majors_id'];
	
} else {
	
	$inputJenisValue = set_value('class_name');
	$inputMajorsValue = set_value('majors_id');
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
			<li><a href="<?php echo site_url('manage/ars_jenis') ?>">Jenis</a></li>
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
						<?php if (isset($jenis)) { ?>
						<input type="hidden" name="id_jenis" value="<?php echo $jenis['id_jenis']; ?>">
						<?php } ?>
						

						<div class="form-group">
							<label>Nama Jenis <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="jenis_arsip" type="text" class="form-control" value="<?php echo $inputJenisValue ?>" placeholder="Isi Nama Kelas">
							
							<input name="majors_id" type="hidden" class="form-control" value="<?php echo $inputMajorsValue ?>" placeholder="Isi Nama Kelas">
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
						<a href="<?php echo site_url('manage/ars_jenis'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>