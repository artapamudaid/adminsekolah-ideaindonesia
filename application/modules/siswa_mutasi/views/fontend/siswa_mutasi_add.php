<?php

if (isset($siswa_mutasi)) {

	$inputNoSuratValue 			= $siswa_mutasi['siswa_mutasi_no'];
	$inputStudentidValue 		= $siswa_mutasi['student_id'];
	$inputcatatanValue 			= $siswa_mutasi['catatan'];
	$inputSekTujuanValue		= $siswa_mutasi['sekolah_tujuan'];
	$inputAlmTujuanValue 		= $siswa_mutasi['alamat_sekolah_tujuan'];
	$inputAlasanPindahValue		= $siswa_mutasi['alasan_pindah'];
	$inputFileValue 			= $siswa_mutasi['file'];
	
} else {
	
	$inputNoSuratValue 			= set_value('siswa_mutasi_no');
	$inputStudentidValue 		= set_value('student_id');
	$inputcatatanValue 			= set_value('catatan');
	$inputSekTujuanValue		= set_value('sekolah_tujuan');
	$inputAlmTujuanValue 		= set_value('alamat_sekolah_tujuan');
	$inputAlasanPindahValue		= set_value('alasan_pindah');
	$inputFileValue 			= set_value('file');
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
			<li><a href="<?php echo site_url('student/siswa_mutasi') ?>">sppd</a></li>
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
						<?php if (isset($siswa_mutasi)) { ?>
							<input type="hidden" name="siswa_mutasi_id" value="<?php echo $siswa_mutasi['siswa_mutasi_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="student_id" value="<?php echo $this->session->userdata('uid_student'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="siswa_mutasi_no" type="text" class="form-control" value="<?php echo $siswa_mutasi_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<!-- <div class="form-group">
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
						</div> -->
						<div class="form-group">
							<label>Sekolah Tujuan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="sekolah_tujuan" type="text" class="form-control" value="<?php echo $inputSekTujuanValue ?>" placeholder="Sekolah Tujuan">
						</div>			
						<div class="form-group">
							<label>Alamat Sekolah Tujuan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="alamat_sekolah_tujuan" type="text" class="form-control" value="<?php echo $inputAlmTujuanValue ?>" placeholder="Alamat Sekolah Tujuan">
						</div>			
						<div class="form-group">
							<label>Alasan Pindah<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="alasan_pindah" type="text" class="form-control" value="<?php echo $inputAlasanPindahValue ?>" placeholder="Alasan Pindah">
						</div>			
						<div class="form-group">
							<label>Catatan/ Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="catatan" type="text" class="form-control" value="<?php echo $inputcatatanValue ?>" placeholder="catatan">
						</div>						
						<div class="form-group">
							<a href="#" class="thumbnail">
								<?php if (isset($siswa_mutasi['file']) != NULL) { 
									$p=  $siswa_mutasi['file']; 
									$explode = explode(".", $p);
									$ext = $explode[1];
									if ($ext=='jpg'){
										?><img src="<?php echo upload_url('siswa_mutasi/' . $siswa_mutasi['file']) ?>" class="img-responsive avatar"><?php
									}else if($ext=='png'){
										?><img src="<?php echo upload_url('siswa_mutasi/' . $siswa_mutasi['file']) ?>" class="img-responsive avatar"><?php
									}else{
										?><iframe src="<?php echo upload_url('siswa_mutasi/' . $siswa_mutasi['file']) ?>" width="100%" height="400px"></iframe>
										<a src="<?php echo upload_url('siswa_mutasi/' . $siswa_mutasi['file']) ?>"></a><?php
									}?>
								<?php } else { ?>
								<img id="target" alt="Choose image to upload">
								<?php } ?>
							</a>
							<label>File Bukti Pendukung mutasi <small data-toggle="tooltip" title="Wajib diisi">*</small><br><small style="color:red;" data-toggle="tooltip" title="Wajib diisi">(File Upload harus berekstensi .PDF | .JPG | .JPEG | .PNG)</small></label>
							<input type="file" name="upl_surat_mutasi" id="upl_surat_mutasi" class="form-control" >
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
						<a href="<?php echo site_url('student/siswa_mutasi'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_siswa_mutasi").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.student_id').select2();
	});
</script>