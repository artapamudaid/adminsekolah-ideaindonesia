<?php

if (isset($anggaran)) {
	$inputPeriodValue   = $anggaran['anggaran_period_id'];
	$inputAccountValue  = $anggaran['anggaran_account_id'];
	$inputMajorsValue   = $anggaran['majors_id'];
} else {
	$inputPeriodValue   = set_value('anggaran_period_id');
	$inputAccountValue  = set_value('anggaran_account_id');
	$inputMajorsValue   = set_value('majors_id');
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
			<li><a href="<?php echo site_url('manage/anggaran') ?>">Manage anggaran</a></li>
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
						<?php if (isset($anggaran)) { ?>
							<input type="hidden" name="anggaran_id" value="<?php echo $anggaran['anggaran_id']; ?>">
						<?php } ?>

						<div class="form-group">
							<label>Tahun Ajaran <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="period_id" class="form-control">
								<option value="">-Pilih Tahun-</option>
								<?php foreach ($period as $row) : ?>
									<option value="<?php echo $row['period_id']; ?>" <?php echo ($inputPeriodValue == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] . '/' . $row['period_end']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label>Unit Sekolah <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select name="majors_id" id="majors_id" class="form-control" onchange="get_account()">
								<option value="">-Pilih Unit-</option>
								<?php foreach ($majors as $row) : ?>
									<option value="<?php echo $row['majors_id']; ?>" <?php echo ($inputMajorsValue == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div id="div_pos">
							<div class="form-group">
								<label>Akun <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
								<select name="account_id" class="form-control">
									<option value="">-Pilih Akun-</option>
									<?php foreach ($account as $row) : ?>
										<option value="<?php echo $row['account_id']; ?>" <?php echo ($inputAccountValue == $row['account_id']) ? 'selected' : '' ?>><?php echo $row['account_code'] . ' - ' . $row['account_description']; ?></option>
									<?php endforeach; ?>
								</select>
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
						<a href="<?php echo site_url('manage/anggaran'); ?>" class="btn btn-block btn-info">Batal</a>
						<?php if (isset($anggaran['anggaran_id'])) { ?>
							<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deletePayment">Hapus
							</button>
						<?php } ?>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
	<?php if (isset($anggaran['anggaran_id'])) { ?>
		<div class="modal fade" id="deletePayment">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Konfirmasi Hapus</h4>
					</div>
					<form action="<?php echo site_url('manage/anggaran/delete/' . $anggaran['anggaran_id']) ?>" method="POST">
						<div class="modal-body">
							<p>Apakah anda akan menghapus data ini?</p>
							<input type="hidden" name="anggaran_id" value="<?= $anggaran['anggaran_id'] ?>">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-danger">Hapus</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<script>
	function get_account() {
		var id_majors = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/anggaran/get_account',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#div_pos").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>