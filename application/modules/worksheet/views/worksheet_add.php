<?php


if (isset($worksheet)) {
	$inputMajorsValue 		 = $worksheet['worksheet_majors_id'];
	$inputPeriodValue 		 = $worksheet['worksheet_period_id'];
	$inputNamaKepsekValue 	 = $worksheet['worksheet_nama_kepsek'];
	$inputNipKepsekValue 	 = $worksheet['worksheet_nip_kepsek'];
	$inputNamaBendaharaValue = $worksheet['worksheet_nama_bendahara'];
	$inputNipBendaharaValue  = $worksheet['worksheet_nip_bendahara'];
	$inputNamaKomiteValue 	 = $worksheet['worksheet_nama_komite'];
	$inputEmailKomiteValue 	 = $worksheet['worksheet_email_komite'];
	$inputNominalValue 		 = $worksheet['worksheet_nominal'];
} else {
	$inputMajorsValue 		 = set_value('worksheet_majors_id');
	$inputPeriodValue 		 = set_value('worksheet_period_id');
	$inputNamaKepsekValue 	 = set_value('worksheet_nama_kepsek');
	$inputNipKepsekValue 	 = set_value('worksheet_nip_kepsek');
	$inputNamaBendaharaValue = set_value('worksheet_nama_bendahara');
	$inputNipBendaharaValue  = set_value('worksheet_nip_bendahara');
	$inputNamaKomiteValue 	 = set_value('worksheet_nama_komite');
	$inputEmailKomiteValue 	 = set_value('worksheet_email_komite');
	$inputNominalValue 		 = set_value('worksheet_nominal');
}
?>

<div class="content-wrapper">

	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/worksheet') ?>">Kertas Kerja <?= $worksheet['period_start'] . '/' . $worksheet['period_end'] ?></a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($worksheet)) { ?>
							<input type="hidden" name="worksheet_id" value="<?php echo $worksheet['worksheet_id']; ?>">
						<?php } ?>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Tahun Ajaran <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="" type="text" class="form-control" value="<?php echo $worksheet['period_start'] . '/' .  $worksheet['period_end'] ?>" placeholder="Isi Nominal" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Unit Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="" type="text" class="form-control" value="<?php echo $worksheet['majors_short_name'] ?>" placeholder="Isi Nominal" readonly>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Kepala Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_nama_kepsek" type="text" class="form-control" value="<?php echo $inputNamaKepsekValue ?>" placeholder="Isi Nama Kepala Pesantren">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>NIP Kepala Pesantren <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_nip_kepsek" type="text" class="form-control" value="<?php echo $inputNipKepsekValue ?>" placeholder="Isi NIP Kepala Pesantren">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Bendahara <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_nama_bendahara" type="text" class="form-control" value="<?php echo $inputNamaBendaharaValue ?>" placeholder="Isi Nama Bendahara">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>NIP Bendahara <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_nip_bendahara" type="text" class="form-control" value="<?php echo $inputNipBendaharaValue ?>" placeholder="Isi NIP Bendahara">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Komite <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_nama_komite" type="text" class="form-control" value="<?php echo $inputNamaKomiteValue ?>" placeholder="Isi Nama Komite">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Email Komite <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_email_komite" type="email" class="form-control" value="<?php echo $inputEmailKomiteValue ?>" placeholder="Isi Email Komite">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">

								<div class="form-group">
									<label>Nominal <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
									<input name="worksheet_nominal" type="number" class="form-control" value="<?php echo $inputNominalValue ?>" placeholder="Isi Nominal">
								</div>
							</div>
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
						<a href="<?php echo site_url('manage/worksheet'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>