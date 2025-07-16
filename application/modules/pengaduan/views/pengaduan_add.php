<?php

if (isset($pengaduan)) {

	$inputNoPengaduanValue 		= $pengaduan['pengaduan_no'];
	$inputPengaduanNamaValue 	= $pengaduan['pengaduan_nama'];
	$inputPengaduanTujuanValue 	= $pengaduan['pengaduan_tujuan'];
	$inputPengaduanDetailValue 	= $pengaduan['pengaduan_detail'];
	$inputPengaduanStatusValue 	= $pengaduan['pengaduan_status'];
	
} else {
	
	$inputNoPengaduanValue 		= set_value('pengaduan_no');
	$inputPengaduanNamaValue 	= set_value('pengaduan_nama');
	$inputPengaduanTujuanValue 	= set_value('pengaduan_tujuan');
	$inputPengaduanDetailValue 	= set_value('pengaduan_detail');
	$inputPengaduanStatusValue 	= set_value('pengaduan_status');
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
			<li><a href="<?php echo site_url('manage/pengaduan') ?>">sppd</a></li>
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
						<?php if (isset($pengaduan)) { ?>
							<input type="hidden" name="pengaduan_id" value="<?php echo $pengaduan['pengaduan_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Pengaduan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="pengaduan_no" type="text" class="form-control" value="<?php echo $pengaduan_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Status Pengadu Sebagai <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="pengaduan_nama" id="pengaduan_nama" class="form-control pengaduan_nama">
							    <option value="">-Pilih Status-</option>
							    <option value="1" <?php echo ($inputPengaduanNamaValue == '1') ? 'selected' : '' ?>>Guru</option>
							    <option value="2" <?php echo ($inputPengaduanNamaValue == '2') ? 'selected' : '' ?>>Siswa</option>
							    <option value="3"<?php echo ($inputPengaduanNamaValue == '3') ? 'selected' : '' ?>>Alumni</option>
							    <option value="4" <?php echo ($inputPengaduanNamaValue == '4') ? 'selected' : '' ?>>Publik/Umum</option>
							</select>
						</div>
						<div class="form-group">
							<label>Tujuan Pengaduan Kepada<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="pengaduan_tujuan" id="pengaduan_tujuan" class="form-control pengaduan_tujuan">
								<option value="">-Pilih Status-</option>
							    <option value="1" <?php echo ($inputPengaduanTujuanValue == '1') ? 'selected' : '' ?>>Pelayanan</option>
							    <option value="2" <?php echo ($inputPengaduanTujuanValue == '2') ? 'selected' : '' ?>>Guru</option>
							    <option value="3"<?php echo ($inputPengaduanTujuanValue == '3') ? 'selected' : '' ?>>Lingkungan</option>
							    <option value="4" <?php echo ($inputPengaduanTujuanValue == '4') ? 'selected' : '' ?>>Keamanan</option>
							    <option value="5"<?php echo ($inputPengaduanTujuanValue == '5') ? 'selected' : '' ?>>Kesiswaan</option>
							    <option value="6" <?php echo ($inputPengaduanTujuanValue == '6') ? 'selected' : '' ?>>Kegiatan</option>
							</select>
						</div>
						<div class="form-group">
							<label>Detail Pengaduan Anda<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="pengaduan_detail" type="text" class="form-control" value="<?php echo $inputPengaduanDetailValue ?>" placeholder="catatan">
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
						<a href="<?php echo site_url('manage/pengaduan'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_pengaduan").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.pengaduan_nama').select2();
	});
</script>