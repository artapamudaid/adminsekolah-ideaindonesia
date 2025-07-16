<?php

if (isset($lokasi)) {

	$inputNamalokasiValue = $lokasi['nama_lokasi'];
	// $inputKeteranganValue = $lokasi['keterangan'];
	
} else {
	
	$inputNamalokasiValue = set_value('nama_lokasi');
	// $inputKeteranganValue = set_value('keterangan');
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
			<li><a href="<?php echo site_url('manage/ars_lokasi') ?>">Satuan</a></li>
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
						<?php if (isset($lokasi)) { ?>
						<input type="hidden" name="id_lokasi" value="<?php echo $lokasi['id_lokasi']; ?>">
						<?php } ?>
						

						<div class="form-group">
							<label>Nama Satuan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="nama_lokasi" type="text" class="form-control" value="<?php echo $inputNamalokasiValue ?>" placeholder="Contoh : Ruang Guru">
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
						<a href="<?php echo site_url('manage/ars_lokasi'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>