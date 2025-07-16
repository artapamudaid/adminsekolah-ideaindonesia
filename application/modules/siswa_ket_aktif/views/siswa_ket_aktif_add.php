<?php

if (isset($siswa_ket_aktif)) {

	$inputNoSuratValue 			= $siswa_ket_aktif['siswa_ket_aktif_no'];
	$inputStudentidValue 		= $siswa_ket_aktif['student_id'];
	$inputKeperluanValue 		= $siswa_ket_aktif['keperluan'];
	$inputcatatanValue 			= $siswa_ket_aktif['catatan'];
	
} else {
	
	$inputNoSuratValue 			= set_value('siswa_ket_aktif_no');
	$inputStudentidValue 		= set_value('student_id');
	$inputKeperluanValue 		= set_value('keperluan');
	$inputcatatanValue 			= set_value('catatan');
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
			<li><a href="<?php echo site_url('manage/siswa_ket_aktif') ?>">sppd</a></li>
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
						<?php if (isset($siswa_ket_aktif)) { ?>
							<input type="hidden" name="siswa_ket_aktif_id" value="<?php echo $siswa_ket_aktif['siswa_ket_aktif_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="siswa_ket_aktif_no" type="text" class="form-control" value="<?php echo $siswa_ket_aktif_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Nama Siswa<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="student_id" id="student_id" class="form-control student_id">
							    <option value="">-Pilih Siswa-</option>
								<?php
								foreach($student as $row):
								?>
								<option value="<?php echo $row['student_id']; ?>" <?php echo ($inputStudentidValue == $row['student_id']) ? 'selected' : '' ?>><?php echo $row['student_full_name'] ?></option>
								<?php
								endforeach;
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Keperluan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="keperluan" type="text" class="form-control" value="<?php echo $inputKeperluanValue ?>" placeholder="Keperluan">
						</div>
						<div class="form-group">
							<label>Catatan/ Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="catatan" type="text" class="form-control" value="<?php echo $inputcatatanValue ?>" placeholder="catatan">
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
						<a href="<?php echo site_url('manage/siswa_ket_aktif'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_siswa_ket_aktif").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.student_id').select2();
	});
</script>