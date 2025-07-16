<?php

if (isset($lainnya_guru)) {

	$inputNoSurtugValue 		= $lainnya_guru['lainnya_guru_no'];
	$inputEmployeeidValue 		= $lainnya_guru['employee_id'];
	$inputcatatanValue 			= $lainnya_guru['catatan'];
	
} else {
	
	$inputNoSurtugValue 		= set_value('lainnya_guru_no');
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
			<li><a href="<?php echo site_url('employees/lainnya_guru') ?>">Lainnya</a></li>
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
						<?php if (isset($lainnya_guru)) { ?>
							<input type="hidden" name="lainnya_guru_id" value="<?php echo $lainnya_guru['lainnya_guru_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="employee_id" value="<?php echo $this->session->userdata('uid_employees'); ?>">
						
						<div class="form-group">
							<label>Kode Layanan Lain<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="lainnya_guru_no" type="text" class="form-control" value="<?php echo $lainnya_guru_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Catatan/ Keterangan Layanan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
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
						<a href="<?php echo site_url('employees/lainnya_guru'); ?>" class="btn btn-block btn-info">Batal</a>
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