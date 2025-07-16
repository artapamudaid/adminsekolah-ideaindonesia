<?php

if (isset($siswa_penelitian)) {

	$inputNoSuratValue 			= $siswa_penelitian['siswa_penelitian_no'];
	$inputStudentidValue 		= $siswa_penelitian['student_id'];
	$inputAnggotaidValue 		= $siswa_penelitian['anggota_id'];
	$inputKegiatanValue			= $siswa_penelitian['nama_kegiatan'];
	$inputNamaInstansiValue 	= $siswa_penelitian['nama_instansi'];
	$inputAlamatInstansiValue	= $siswa_penelitian['alamat_instansi'];
	$inputTglAwalValue 			= $siswa_penelitian['tanggal_mulai'];
	$inputTglAkhirValue			= $siswa_penelitian['tanggal_akhir'];
	$inputCatatanValue 			= $siswa_penelitian['catatan'];
	
} else {
	
	$inputNoSuratValue 			= set_value('siswa_penelitian_no');
	$inputStudentidValue 		= set_value('student_id');
	$inputAnggotaidValue 		= set_value('anggota_id');
	$inputKegiatanValue			= set_value('nama_kegiatan');
	$inputNamaInstansiValue 	= set_value('nama_instansi');
	$inputAlamatInstansiValue	= set_value('alamat_instansi');
	$inputTglAwalValue 			= set_value('tanggal_mulai');
	$inputTglAkhirValue			= set_value('tanggal_akhir');
	$inputCatatanValue 			= set_value('catatan');
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
			<li><a href="<?php echo site_url('student/siswa_penelitian') ?>">sppd</a></li>
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
						<?php if (isset($siswa_penelitian)) { ?>
							<input type="hidden" name="siswa_penelitian_id" value="<?php echo $siswa_penelitian['siswa_penelitian_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="student_id" value="<?php echo $this->session->userdata('uid_student'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="siswa_penelitian_no" type="text" class="form-control" value="<?php echo $siswa_penelitian_no; ?>" readonly='' placeholder="Kode Surat">
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
							<label>Pengikut<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<?php
								if($inputAnggotaidValue != ''){
									?>
									<ol>
										<?php  
										$pengikut 	= $inputAnggotaidValue;
										$id 		= explode("," , $pengikut);
										$data 		= $this->db->query("SELECT student_full_name, student_nis FROM student WHERE student_id in ($pengikut)")->result_array();
										foreach ($data as $string) {
											echo '<li>'.$string['student_full_name'].' ( '.$string['student_nis']. ')</li>';
										}
										?>
									</ol>
									<small style="color:red;"> <b>Jika Ubah data siswa, maka data yang lama akan hilang**</b> </small>
									<?php
								}
							?>
							<select required="" name="anggota_id[]" multiple="multiple" class="form-control multiple">
								<option value="">-Pilih Pengikut-</option>
								<?php foreach($student as $row){?>
									<option value="<?php echo $row['student_id']; ?>" ><?php echo $row['student_full_name'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Nama Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="nama_kegiatan" type="text" class="form-control" value="<?php echo $inputKegiatanValue ?>" placeholder="Nama Kegiatan">
						</div>			
						<div class="form-group">
							<label>Nama Instansi/Lembaga<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="nama_instansi" type="text" class="form-control" value="<?php echo $inputNamaInstansiValue ?>" placeholder="Nama Instansi/Lembaga">
						</div>			
						<div class="form-group">
							<label>Alamat Instansi/Lembaga<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="alamat_instansi" type="text" class="form-control" value="<?php echo $inputAlamatInstansiValue ?>" placeholder="Alamat Instansi/Lembaga">
						</div>			
						<div class="form-group">
							<label>Tanggal Awal<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tanggal_mulai" type="date" class="form-control" value="<?php echo $inputTglAwalValue ?>" placeholder="Tanggal Awal">
						</div>
						<div class="form-group">
							<label>Tanggal Akhir<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tanggal_akhir" type="date" class="form-control" value="<?php echo $inputTglAkhirValue ?>" placeholder="Tanggal Akhir">
						</div>	
						<div class="form-group">
							<label>Catatan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
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
						<a href="<?php echo site_url('student/siswa_penelitian'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_siswa_penelitian").change(function() {
		readURL(this);
	});
</script> 
<script>
	$(document).ready(function() {
		$('.student_id').select2();
	});
</script>
<script>
	$(document).ready(function() {
		$('.multiple').select2();
	});
</script> 