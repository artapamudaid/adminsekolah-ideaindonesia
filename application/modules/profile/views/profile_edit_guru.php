<?php

if (isset($employee)) {

	$inputPhoneValue = $employee['employee_phone'];
} else {
	$inputPhoneValue = set_value('employee_phone');
}
?>

<div class="content-wrapper">
	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($employee)) { ?>
							<input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
						<?php } ?>

						<div class="form-group">
							<label>Telp/HP (Whatsapp) <small data-bs-toggle="tooltip" title="Wajib diisi"></small></label>
							<input name="employee_phone" type="text" class="form-control" value="<?php echo $inputPhoneValue ?>" placeholder="Telp/HP (Whatsapp)">
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
						<a href="<?php echo site_url('guru/profile'); ?>" class="btn btn-block btn-info">Batal</a>

					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>