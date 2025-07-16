<?php

if (isset($publik)) {

	$inputNopublikValue 		= $publik['publik_no'];
	$inputPublikNamaValue 		= $publik['publik_nama'];
	$inputPublikAlamatValue 	= $publik['publik_alamat'];
	$inputPublikTlpnValue 		= $publik['publik_telepon'];
	$inputPublikNikValue 		= $publik['publik_nik'];
	$inputPublikPosIdValue 		= $publik['publik_position_id'];
	$inputPublikKeperluanValue 	= $publik['keperluan'];
	$inputPublikIdUserValue 	= $publik['id_users'];
	
} else {
	
	$inputNopublikValue 		= set_value('publik_no');
	$inputPublikNamaValue 		= set_value('publik_nama');
	$inputPublikAlamatValue 	= set_value('publik_alamat');
	$inputPublikTlpnValue 		= set_value('publik_telepon');
	$inputPublikNikValue	 	= set_value('publik_nik');
	$inputPublikPosIdValue 		= set_value('publik_position_id');
	$inputPublikKeperluanValue 	= set_value('keperluan');
	$inputPublikIdUserValue 	= set_value('id_users');
}
?>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/publik') ?>">layanan publik</a></li>
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
						<?php if (isset($publik)) { ?>
							<input type="hidden" name="publik_id" value="<?php echo $publik['publik_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Layanan Publik<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="publik_no" type="text" class="form-control" value="<?php echo $publik_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Nama <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="publik_nama" type="text" class="form-control" value="<?php echo $inputPublikNamaValue; ?>" placeholder="Nama">
						</div>
						<div class="form-group">
							<label>NIK <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="publik_nik" type="text" class="form-control" value="<?php echo $inputPublikNikValue; ?>" placeholder="NIK">
						</div>
						<div class="form-group">
							<label>Alamat <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="publik_alamat" type="text" class="form-control" value="<?php echo $inputPublikAlamatValue; ?>"  placeholder="Alamat">
						</div>
						<div class="form-group">
							<label>Telepon<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="publik_telepon" type="text" class="form-control" value="<?php echo $inputPublikTlpnValue ?>" placeholder="Telepon">
						</div>
						<div class="form-group">
							<label>Bidang Yang Dituju<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="publik_position_id" class="form-control" id="">
								<option value="">Pilih Bidang Yang dituju</option>
								<?php
								foreach($position as $row):
								?>
								<option value="<?php echo $row['position_id']; ?>" <?php echo ($inputPublikPosIdValue == $row['position_id']) ? 'selected' : '' ?>><?php echo $row['position_name'] ?></option>
								<?php
								endforeach;
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Detail Keperluan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="keperluan" type="text" class="form-control" value="<?php echo $inputPublikKeperluanValue ?>" placeholder="Detail keperluan">
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
						<a href="<?php echo site_url('manage/publik'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_publik").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.publik_nama').select2();
	});
</script>