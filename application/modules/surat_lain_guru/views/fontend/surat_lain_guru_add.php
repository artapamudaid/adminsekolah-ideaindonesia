<?php

if (isset($surat_lain_guru)) {

	$inputNoSurtugValue 		= $surat_lain_guru['surat_lain_guru_no'];
	$inputEmployeeidValue 		= $surat_lain_guru['employee_id'];
	$inputcatatanValue 			= $surat_lain_guru['catatan'];
	
} else {
	
	$inputNoSurtugValue 		= set_value('surat_lain_guru_no');
	$inputEmployeeidValue 		= set_value('employee_id');
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
			<li><a href="<?php echo site_url('employees') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('employees/surat_tugas') ?>">sppd</a></li>
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
						<?php if (isset($surat_lain_guru)) { ?>
							<input type="hidden" name="surat_lain_guru_id" value="<?php echo $surat_lain_guru['surat_lain_guru_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="employee_id" value="<?php echo $this->session->userdata('uid_employees'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="surat_lain_guru_no" type="text" class="form-control" value="<?php echo $surat_lain_guru_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<!-- <div class="form-group">
							<label>Nama Guru/Pegawai<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="employee_id" id="employee_id" class="form-control employee_id">
							    <option value="">-Pilih Guru-</option>
								<?php
								foreach($emp_petugas as $row):
								?>
								<option value="<?php echo $row['employee_id']; ?>" <?php echo ($inputEmployeeidValue == $row['employee_id']) ? 'selected' : '' ?>><?php echo $row['employee_name'] ?></option>
								<?php
								endforeach;
								?>
							</select>
						</div> -->
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
						<a href="<?php echo site_url('employees/surat_tugas'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$(document).ready(function() {
		$('.employee_id').select2();
	});
</script>