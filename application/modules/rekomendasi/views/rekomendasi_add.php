<?php

if (isset($rekomendasi)) {

	$inputNodisValue 			= $rekomendasi['rekomendasi_nomor_surat_id'];
	$inputDeskripsiValue 		= $rekomendasi['rekomendasi_desc'];
	$inputStudentIDValue 		= $rekomendasi['rekomendasi_student_id'];
	$inputRekomendasiDsValue 	= $rekomendasi['rekomendasi_date_start'];
	$inputRekomendasiDeValue 	= $rekomendasi['rekomendasi_date_end'];
	$inputRekomendasiTsValue	= $rekomendasi['rekomendasi_time_start'];
	$inputRekomendasiTdValue	= $rekomendasi['rekomendasi_time_end'];
	$inputLokasiValue 			= $rekomendasi['rekomendasi_lokasi'];
	$inputUsersValue	 		= $rekomendasi['rekomendasi_users'];
	
} else {

	$inputNodisValue 			= set_value('rekomendasi_nomor_surat_id');
	$inputDeskripsiValue 		= set_value('rekomendasi_desc');
	$inputStudentIDValue 		= set_value('rekomendasi_student_id');
	$inputRekomendasiDsValue 	= set_value('rekomendasi_date_start');
	$inputRekomendasiDeValue 	= set_value('rekomendasi_date_end');
	$inputRekomendasiTsValue	= set_value('rekomendasi_time_start');
	$inputRekomendasiTdValue	= set_value('rekomendasi_time_end');
	$inputLokasiValue 			= set_value('rekomendasi_lokasi');
	$inputUsersValue	 		= set_value('rekomendasi_users');
}
?>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/rekomendasi') ?>">sppd</a></li>
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
						<?php if (isset($rekomendasi)) { ?>
							<input type="hidden" name="rekomendasi_id" value="<?php echo $rekomendasi['rekomendasi_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Nomor Surat Dispensasi<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="nomor_surat" type="text" class="form-control" value="<?php echo ($inputNodisValue == NULL ? $nomor_surat : $inputNodisValue) ?>" readonly='' placeholder="Kode Surat">
							<input name="nomor_urut" type="hidden" class="form-control" value="<?php echo $nomor_urut; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Tanggal Mulai Acara<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="rekomendasi_date_start" type="date" class="form-control" value="<?php echo $inputRekomendasiDsValue ?>" placeholder="Tanggal Acara">
						</div>
						<div class="form-group">
							<label>Tanggal Selesai Acara<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="rekomendasi_date_end" type="date" class="form-control" value="<?php echo $inputRekomendasiDeValue ?>" placeholder="Tanggal Acara">
						</div>
						<div class="form-group">
							<label>Deskripsi<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="rekomendasi_desc" type="text" class="form-control" value="<?php echo $inputDeskripsiValue ?>" placeholder="Deskripsi tujuan dispensasi">
						</div>
						<div class="form-group">
							<label>Waktu Mulai<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="rekomendasi_time_start" type="text" class="form-control" value="<?php echo $inputRekomendasiTsValue ?>" placeholder="Contoh 08.00" minlength="5" maxlength="5">
						</div>
						<div class="form-group">
							<label>Waktu Selesai<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="rekomendasi_time_end" type="text" class="form-control" value="<?php echo $inputRekomendasiTdValue ?>" placeholder="Contoh 13.00" minlength="5" maxlength="7">
						</div>
						<div class="form-group">
							<label>Tempat Tujuan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="rekomendasi_lokasi" type="text" class="form-control" value="<?php echo $inputLokasiValue ?>" placeholder="Tempat Tujuan">
						</div>
						<div class="form-group">
							<label>Siswa Yang Diberi Rekomendasi<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<?php
								if($inputStudentIDValue != ''){
									?>
									<ol>
										<?php  
										$pengikut 	= $inputStudentIDValue;
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
							<select class="form-control multiple-select" name="rekomendasi_student_id[]" multiple="multiple" placeholder="pilih siswa">
								<?php foreach($student as $row){?>
							        <option value="<?php echo $row['student_id']; ?>"  >
										<?php echo $row['student_full_name'] ?>
									</option>
							    <?php } ?>
							</select>
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
						<a href="<?php echo site_url('manage/rekomendasi'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>

<!-- Tambahkan script inisialisasi Select2 -->
<style>
    .select2-selection__choice[title]:nth-child(n) {
        color: black; /* ubah warna teks ke merah */
    }
</style>

<script>
    $(document).ready(function() {
        $('.multiple-select').select2();
    });
</script>

<script>
$(function() {
	var scntDiv = $('#p_scents_pengikut');
	var i = $('#p_scents_pengikut p').size() + 1;

	$("#addScnt_pengikut").click(function() {
		$('<p><label>Pengikut<small data-toggle="tooltip" title="Wajib diisi">*</small></label><select required="" name="pengikut_id[]" class="form-control"><option value="">-Pilih Pengikut-</option><?php foreach($emp_petugas as $row){?><option value="<?php echo $row['employee_id']; ?>" ><?php echo $row['employee_name'] ?></option><?php } ?></select><a href="#" class="btn btn-xs btn-danger remScnt_satuan">Hapus Baris</a></p>').appendTo(scntDiv);
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