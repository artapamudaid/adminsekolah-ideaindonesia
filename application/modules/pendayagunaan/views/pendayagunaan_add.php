<?php

if (isset($program)) {

	$inputNameValue = $program['program_name'];
	$inputDescValue = $program['program_description'];
	$inputAccountValue = $program['account_account_id'];
	$inputPendayagunaanValue = $program['pendayagunaan_account_id'];
	$inputTargetValue = $program['program_target'];
	$inputEndValue = $program['program_end'];
	$inputStatusValue = $program['program_status'];
} else {
	$inputNameValue = set_value('program_name');
	$inputDescValue = set_value('program_description');
	$inputAccountValue = set_value('account_account_id');
	$inputPendayagunaanValue = set_value('pendayagunaan_account_id');
	$inputTargetValue = set_value('program_target');
	$inputEndValue = date('Y-m-d');
	$inputStatusValue = '1';
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
			<li><a href="<?php echo site_url('manage/program') ?>">+ProgramBayar</a></li>
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
						<?php if (isset($program)) { ?>
							<input type="hidden" name="program_id" value="<?php echo $program['program_id']; ?>">
						<?php } ?>

						<div class="form-group">
							<label>Kode Akun Penerimaan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="account_account_id" class="form-control">
								<option value="">-Pilih Kode Akun-</option>
								<?php foreach ($account as $row) { ?>
									<option value="<?php echo $row['account_id']; ?>" <?php echo ($inputAccountValue == $row['account_id']) ? 'selected' : '' ?>>
										<?php echo $row['account_code'] . ' - ' . $row['account_description'] ?>
									</option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Kode Akun Pendayagunaan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="pendayagunaan_account_id" class="form-control">
								<option value="">-Pilih Kode Akun-</option>
								<?php foreach ($pendayagunaan as $row) { ?>
									<option value="<?php echo $row['account_id']; ?>" <?php echo ($inputPendayagunaanValue == $row['account_id']) ? 'selected' : '' ?>>
										<?php echo $row['account_code'] . ' - ' . $row['account_description'] ?>
									</option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Nama Program <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="program_name" type="text" class="form-control" value="<?php echo $inputNameValue ?>" placeholder="Program">
						</div>

						<div class="form-group">
							<label>Keterangan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<textarea name="program_description" class="form-control" placeholder="Keterangan" rows="5"><?php echo $inputDescValue ?></textarea>
						</div>

						<div class="form-group">
							<label>Gambar Program <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="program_gambar" type="file" class="form-control" placeholder="Program">
						</div>

						<div class="form-group">
							<label>Target Program <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="program_target" type="number" class="form-control" value="<?php echo $inputTargetValue ?>" placeholder="Target Program">
						</div>

						<div class="form-group">
							<label>Batas Akhir Program </label>
							<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
								<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								<input class="form-control" type="text" name="program_end" readonly="readonly" placeholder="Tanggal" value="<?php echo $inputEndValue; ?>">
							</div>
						</div>

						<div class="form-group">
							<label>Status <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="program_status" class="form-control">
								<option value="">-Pilih Status-</option>
								<option value="1" <?php echo ($inputStatusValue == '1') ? 'selected' : '' ?>>Aktif</option>
								<option value="0" <?php echo ($inputStatusValue == '0') ? 'selected' : '' ?>>Berhenti</option>
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
						<a href="<?php echo site_url('manage/program'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>