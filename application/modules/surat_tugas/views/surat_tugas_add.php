<?php

if (isset($surat_tugas)) {

	$inputNoSurtugValue 		= $surat_tugas['no_surat_tugas'];
	$inputEmployeeidValue 		= $surat_tugas['employee_id'];
	$inputPengikutidValue 		= $surat_tugas['pengikut_id'];
	$inputDeskripsiValue 		= $surat_tugas['deskripsi'];
	$inputTglKegiatanValue 		= $surat_tugas['tgl_kegiatan'];
	$inputTmpKegiatanValue		= $surat_tugas['tmp_kegiatan'];
	$inputAlamatKegiatanValue 	= $surat_tugas['alamat_kegiatan'];
	$inputKeteranganValue 		= $surat_tugas['keterangan'];
	$inputFileValue 			= $surat_tugas['file'];
	
} else {
	
	$inputNoSurtugValue 		= set_value('no_surat_tugas');
	$inputEmployeeidValue 		= set_value('employee_id');
	$inputPengikutidValue 		= set_value('pengikut_id');
	$inputDeskripsiValue 		= set_value('deskripsi');
	$inputTglKegiatanValue 		= set_value('tgl_kegiatan');
	$inputTmpKegiatanValue		= set_value('tmp_kegiatan');
	$inputAlamatKegiatanValue 	= set_value('alamat_kegiatan');
	$inputKeteranganValue 		= set_value('keterangan');
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
			<li><a href="<?php echo site_url('manage/surat_tugas') ?>">sppd</a></li>
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
						<?php if (isset($surat_tugas)) { ?>
							<input type="hidden" name="id_surat_tugas" value="<?php echo $surat_tugas['id_surat_tugas']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="no_surat_tugas" type="text" class="form-control" value="<?php echo $no_surat_tugas; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Guru yang diberi Tugas<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="employee_id" id="employee_id" class="form-control employee_id">
							    <option value="">-Pilih Guru yang diberi Tugas-</option>
								<?php
								foreach($emp_petugas as $row):
								?>
								<option value="<?php echo $row['employee_id']; ?>" <?php echo ($inputEmployeeidValue == $row['employee_id']) ? 'selected' : '' ?>><?php echo $row['employee_name'] ?></option>
								<?php
								endforeach;
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Pengikut<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<?php
								if($inputPengikutidValue != ''){
									?>
									<ol>
										<?php  
										$pengikut 	= $inputPengikutidValue;
										$id 		= explode("," , $pengikut);
										$data 		= $this->db->query("SELECT employee_name, employee_nip FROM employee WHERE employee_id in ($pengikut)")->result_array();
										foreach ($data as $string) {
											echo '<li>'.$string['employee_name'].' ( '.$string['employee_nip']. ')</li>';
										}
										?>
									</ol>
									<small style="color:red;"> <b>Jika Ubah data siswa, maka data yang lama akan hilang**</b> </small>
									<?php
								}
							?>
							<select required="" name="pengikut_id[]" multiple="multiple" class="form-control multiple">
								<option value="">-Pilih Pengikut-</option>
								<?php foreach($emp_petugas as $row){?>
									<option value="<?php echo $row['employee_id']; ?>" ><?php echo $row['employee_name'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Nama Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="deskripsi" type="text" class="form-control" value="<?php echo $inputDeskripsiValue ?>" placeholder="Maksud Perjalanan Dinas">
						</div>
						<div class="form-group">
							<label>Tanggal Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tgl_kegiatan" type="date" class="form-control" value="<?php echo $inputTglKegiatanValue ?>" placeholder="Tanggal KEgiatan Berlangsung">
						</div>
						<div class="form-group">
							<label>Nama Tempat Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tmp_kegiatan" type="text" class="form-control" value="<?php echo $inputTmpKegiatanValue ?>" placeholder="Tempat Kegiatan">
						</div>
						<div class="form-group">
							<label>Alamat Lengkap Kegiatan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="alamat_kegiatan" type="text" class="form-control" value="<?php echo $inputAlamatKegiatanValue ?>" placeholder="Alamat Kegiatan">
						</div>
						<div class="form-group">
							<label>Catatan/ Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="keterangan" type="text" class="form-control" value="<?php echo $inputKeteranganValue ?>" placeholder="Keterangan">
						</div>
						<div class="form-group">
							<a href="#" class="thumbnail">
								<?php if (isset($surat_tugas['file']) != NULL) { 
									$p=  $surat_tugas['file']; 
									$explode = explode(".", $p);
									$ext = $explode[1];
									if ($ext=='jpg'){
										?><img src="<?php echo upload_url('surat_tugas/' . $surat_tugas['file']) ?>" class="img-responsive avatar"><?php
									}else if($ext=='png'){
										?><img src="<?php echo upload_url('surat_tugas/' . $surat_tugas['file']) ?>" class="img-responsive avatar"><?php
									}else{
										?><iframe src="<?php echo upload_url('surat_tugas/' . $surat_tugas['file']) ?>" width="100%" height="400px"></iframe>
										<a src="<?php echo upload_url('surat_tugas/' . $surat_tugas['file']) ?>"></a><?php
									}?>
								<?php } else { ?>
								<img id="target" alt="Choose image to upload">
								<?php } ?>
							</a>
							<label>File surat_tugas <small data-toggle="tooltip" title="Wajib diisi">*</small><br><small style="color:red;" data-toggle="tooltip" title="Wajib diisi">(File Upload harus berekstensi .PDF | .JPG | .JPEG | .PNG)</small></label>
							<input type="file" name="upl_surat_tugas" id="upl_surat_tugas" class="form-control" >
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
						<a href="<?php echo site_url('manage/surat_tugas'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_surat_tugas").change(function() {
		readURL(this);
	});
</script>
<script>
	$(document).ready(function() {
		$('.multiple').select2();
	});
</script> 
<script>
	$(document).ready(function() {
		$('.employee_id').select2();
	});
</script>
<script>
	$(document).ready(function() {
		$('.employee_id').select2();
	});
</script>