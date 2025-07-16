<?php

if (isset($survei)) {

	$inputsurveiNamaValue 		= $survei['survei_nama'];
	$inputsurveiAlamatValue 	= $survei['survei_alamat'];
	$inputsurveiResponValue		= $survei['survei_respon'];
	$inputsurveiCatatanValue	= $survei['survei_catatan'];
	
} else {
	
	$inputsurveiNamaValue 		= set_value('survei_nama');
	$inputsurveiAlamatValue 	= set_value('survei_alamat');
	$inputsurveiResponValue	 	= set_value('survei_respon');
	$inputsurveiCatatanValue	= set_value('survei_catatan');
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
			<li><a href="<?php echo site_url('manage/survei') ?>">layanan survei</a></li>
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
						<?php if (isset($survei)) { ?>
							<input type="hidden" name="survei_id" value="<?php echo $survei['survei_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">

						<div class="form-group">
							<label>Nama <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="survei_nama" type="text" class="form-control" value="<?php echo $inputsurveiNamaValue; ?>" placeholder="Nama Penyurvei">
						</div>
						<div class="form-group">
							<label>Alamat <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="survei_alamat" type="text" class="form-control" value="<?php echo $inputsurveiAlamatValue; ?>"  placeholder="Alamat">
						</div>
						<div class="form-group">
							<label>Respon<small data-toggle="tooltip" title="Wajib diisi">*</small></label><br>
							<input type="radio" class="radio-control" id="survei_respon1" name="survei_respon" value="3" <?php echo ($inputsurveiResponValue == 3 ? 'checked' : ''); ?>>
							<label for="survei_respon1">Sangat Puas</label><br>
							<input type="radio" class="radio-control" id="survei_respon2" name="survei_respon" value="2" <?php echo ($inputsurveiResponValue == 2 ? 'checked' : ''); ?>>
							<label for="survei_respon2">Puas</label><br>
							<input type="radio" class="radio-control" id="survei_respon3" name="survei_respon" value="1" <?php echo ($inputsurveiResponValue == 1 ? 'checked' : ''); ?>>
							<label for="survei_respon3">Tidak Puas</label>
						</div>
						<div class="form-group" id="saran_kritik_group" <?php echo ($inputsurveiResponValue == 1 ? 'style="display: block;"' : ''); ?>>
							<label>Saran Dan Kritik<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="survei_catatan" type="text" class="form-control" value="<?php echo $inputsurveiCatatanValue ?>" placeholder="Detail Catatan">
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
						<a href="<?php echo site_url('manage/survei'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_survei").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.survei_nama').select2();
	});
</script>
<script>
    document.querySelectorAll('.radio-control').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                document.getElementById('saran_kritik_group').style.display = 'block';
            } else {
                document.getElementById('saran_kritik_group').style.display = 'none';
            }
        });
    });
</script>