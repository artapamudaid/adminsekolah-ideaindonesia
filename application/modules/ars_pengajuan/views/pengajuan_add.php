<?php

if (isset($satuan)) {

	$inputValue = $satuan['nama_satuan'];
	$inputKeteranganValue = $satuan['keterangan'];
	
} else {
	
	$inputNamasatuanValue = set_value('nama_satuan');
	$inputKeteranganValue = set_value('keterangan');
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
			<li><a href="<?php echo site_url('manage/ars_satuan') ?>">Satuan</a></li>
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
						<?php if (isset($satuan)) { ?>
						<input type="hidden" name="id_satuan" value="<?php echo $satuan['id_satuan']; ?>">
						<?php } ?>
						

						<div class="form-group">
							<label>Nama Satuan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="nama_satuan" type="text" class="form-control" value="<?php echo $inputNamasatuanValue ?>" placeholder="Pack">
						</div>
						<div class="form-group">
							<label>Keterangan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="keterangan" type="text" class="form-control" value="<?php echo $inputKeteranganValue ?>" placeholder="Lembar/Bundle">
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
						<a href="<?php echo site_url('manage/ars_satuan'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>