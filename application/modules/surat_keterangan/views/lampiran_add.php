<?php

if (isset($lampiran)) {

	$inputIsiValue 			= $lampiran['lampiran_isi'];
	$inputSuratKetIdValue 	= $lampiran['lampiran_surat_keterangan_id'];
	
} else {

	$inputIsiValue 			= $set_value('lampiran_isi');
	$inputSuratKetIdValue 	= $set_value('lampiran_surat_keterangan_id');

}
?>


<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/surat_masuk') ?>">Surat Masuk</a></li>
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
						<?php if (isset($lampiran['lampiran_id'])) { ?>
							<input type="hidden" name="lampiran_id" value="<?php echo $lampiran['lampiran_id']; ?>">
						<?php } ?>
							<input type="text" name="student_nis" value="<?php echo $lampiran['student_nis']; ?>">
							<input type="hidden" name="lampiran_surat_keterangan_id" value="<?php echo $lampiran['keterangan_id']; ?>">
						
						<div class="form-group">
							<label>Isi Lampiran <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                    		<textarea class="ckeditor form-control" name="lampiran_isi" style="height: 5000px;" ><?php echo $inputIsiValue ?></textarea>
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
						<a href="<?php echo site_url('manage/surat_keterangan/'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>