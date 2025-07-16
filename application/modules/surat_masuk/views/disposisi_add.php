<?php

if (isset($disposisi)) {

	$inputTujuanValue 		= $disposisi['tujuan'];
	$inputIsiValue 			= $disposisi['isi_disposisi'];
	$inputSifatValue 		= $disposisi['sifat'];
	$inputBataswaktuValue 	= $disposisi['batas_waktu'];
	$inputCatatanValue 		= $disposisi['catatan'];
	
} else {
	
	$inputTujuanValue 		= $set_value('tujuan');
	$inputIsiValue 			= $set_value('isi_disposisi');
	$inputSifatValue 		= $set_value('sifat');
	$inputBataswaktuValue 	= $set_value('batas_waktu');
	$inputCatatanValue 		= $set_value('catatan');
}
?>


<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>

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
						<?php if (isset($disposisi)) { ?>
							<input type="hidden" name="id_surat" value="<?php echo $disposisi['id_surat']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
							
						<div class="form-group">
							<label>No Surat <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="surat" type="text" readonly="" class="form-control" value="<?php echo $disposisi['no_surat']; ?>" placeholder="No. Surat">
						</div>
						<?php } ?>
						
						
						<div class="form-group">
							<div id="p_scents_tujuan">
								<p>
									<label>Tujuan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="tujuan[]" type="text" class="form-control" value="<?php echo $inputTujuanValue ?>" placeholder="Tujuan">
								</p>
							</div>
							<h6 ><a href="#" class="btn btn-xs btn-success" id="addScnt_tujuan"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
						</div>
						<div class="form-group">
							<label>Isi Disposisi <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="isi_disposisi" type="text" class="form-control" value="<?php echo $inputIsiValue ?>" placeholder="Isi Disposisi">
						</div>
						<div class="form-group">
							<label>Sifat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="sifat" class="form-control" require>
								<option value=""> -- Pilih Sifat -- </option>
								<option value="Biasa"> Biasa</option>
								<option value="Penting"> Penting</option>
								<option value="Segera"> Segera</option>
								<option value="Rahasia"> Rahasia</option>
							</select>
						</div>
						<div class="form-group">
							<label>Batas Waktu<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="batas_waktu" type="date" class="form-control" value="<?php echo $inputBataswaktuValue ?>" placeholder="Batas Waktu">
						</div>
						<div class="form-group">
							<label>Catatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="catatan" type="text" class="form-control" value="<?php echo $inputCatatanValue ?>" placeholder="Catatan">
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
						<a href="<?php echo site_url('manage/surat_masuk'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>
<script>
$(function() {
	var scntDiv = $('#p_scents_tujuan');
	var i = $('#p_scents_tujuan p').size() + 1;

	$("#addScnt_tujuan").click(function() {
		$('<p><label>Tujuan<small data-toggle="tooltip" title="Wajib diisi">*</small></label><input name="tujuan[]" type="text" class="form-control" placeholder="Tujuan"><a href="#" class="btn btn-xs btn-danger remScnt_satuan">Hapus Baris</a></p>').appendTo(scntDiv);
		i++;
		return false;
	});

	$(document).on("click", ".remScnt_satuan", function() {
		if (i > 2) {
			$(this).parents('p').remove();
			i--;
		}
		return false;
	});
});
</script>