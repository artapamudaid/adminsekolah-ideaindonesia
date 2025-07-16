<?php
if (isset($homestay)) {

	$inputNameValue = $homestay['homestay_name'];
	$inputDescValue = $homestay['homestay_desc'];
	$inputPriceValue = $homestay['homestay_price'];
	$inputLatitudeValue = $homestay['homestay_latitude'];
	$inputLongitudeValue = $homestay['homestay_longitude'];
	$inputUserIdValue = $homestay['homestay_user_id'];
} else {

	$inputNameValue = set_value('homestay_name');
	$inputDescValue = set_value('homestay_desc');
	$inputPriceValue = set_value('homestay_price');
	$inputLatitudeValue = set_value('homestay_latitude');
	$inputLongitudeValue = set_value('homestay_longitude');
	$inputUserIdValue = set_value('homestay_user_id');
}
?>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<?php if (!isset($homestay)) echo validation_errors(); ?>
	<?php echo form_open_multipart(current_url()); ?>

	<section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php if (isset($homestay)) : ?>
							<input type="hidden" name="homestay_id" value="<?php echo $homestay['homestay_id']; ?>" />
						<?php endif; ?>
						<?php if ($this->session->userdata('uroleid') == 9) { ?>
							<input type="hidden" name="homestay_user_id" value="<?php echo $this->session->userdata('uid'); ?>" />
						<?php } else { ?>
							<label>Pemilik Penginapan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="homestay_user_id" class="form-control" value="<?php echo $inputNameValue; ?>">
								<option value="">-- Pilih Nama Pemilik --</option>
								<?php
								foreach ($users as $user) { ?>
									<option value="<?= $user['user_id'] ?>" <?= ($user['user_id'] == $inputUserIdValue) ? 'selected' : '' ?>>
										<?= $user['user_full_name'] ?>
									</option>
								<?php } ?>
							</select><br>
						<?php } ?>
						<label>Nama Penginapan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
						<input name="homestay_name" placeholder="Nama Penginapan" type="text" class="form-control" value="<?php echo $inputNameValue; ?>"><br>
						<label>Harga Penginapan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
						<input name="homestay_price" placeholder="Harga Penginapan" type="number" class="form-control" value="<?php echo $inputPriceValue; ?>"><br>
						<div class="form-group">
							<label>Deskripsi Penginapan *</label>
							<textarea name="homestay_desc" rows="10" class="form-control" placeholder="Deskripsikan Penginapan Anda"><?php echo $inputDescValue; ?></textarea>
						</div>
						<br>
						<label>Latitude <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
						<input name="homestay_latitude" placeholder="Nilai Latitude Bisa Lihat di Google Maps" type="text" class="form-control" value="<?php echo $inputLatitudeValue; ?>"><br>
						<label>Longitude <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
						<input name="homestay_longitude" placeholder="Nilai Longitude Bisa Lihat di Google Maps" type="text" class="form-control" value="<?php echo $inputLongitudeValue; ?>"><br>

						<p style="color:#9C9C9C;margin-top: 5px"><i>*) Field Wajib Diisi</i></p>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="form-group">
							<button name="action" type="submit" value="save" class="btn btn-success btn-form"><i class="fa fa-check"></i> Simpan</button>
							<a href="<?php echo site_url('manage/homestay'); ?>" class="btn btn-info btn-form"><i class="fa fa-arrow-left"></i> Batal</a>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
</div>
</div>
</section>
</div>