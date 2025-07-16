<?php

if (isset($lainnya_siswa)) {

	$inputNoSuratValue 			= $lainnya_siswa['lainnya_siswa_no'];
	$inputStudentidValue 		= $lainnya_siswa['student_id'];
	$inputcatatanValue 			= $lainnya_siswa['catatan'];
	
} else {
	
	$inputNoSuratValue 			= set_value('lainnya_siswa_no');
	$inputStudentidValue 		= set_value('student_id');
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
			<li><a href="<?php echo site_url('manage/lainnya_siswa') ?>">sppd</a></li>
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
						<?php if (isset($lainnya_siswa)) { ?>
							<input type="hidden" name="lainnya_siswa_id" value="<?php echo $lainnya_siswa['lainnya_siswa_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="lainnya_siswa_no" type="text" class="form-control" value="<?php echo $lainnya_siswa_no; ?>" readonly='' placeholder="Kode Surat">
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
							<label>Keterangan Lengkap layanan yang dibutuhkan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
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
						<a href="<?php echo site_url('manage/lainnya_siswa'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_lainnya_siswa").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.student_id').select2();
	});
</script>