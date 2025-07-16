<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-hea	der">
		<h1>
			<?php echo isset($title) ? '' .  $anggaran['account_code'] . ' - ' . $anggaran['account_description'] . ' ' . $anggaran['period_start'] . '/' . $anggaran['period_end']  : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="box-body">
				<?php echo form_open_multipart(current_url(), array('class' => 'form-horizontal')); ?>
				<?php echo validation_errors(); ?>
				<?php if (isset($detail)) { ?>
					<input type="hidden" name="anggaran_id" value="<?php echo $anggaran['anggaran_id']; ?>">
				<?php } ?>

				<div class="col-md-5">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Anggaran Setiap Bulan Sama</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label for="" class="col-sm-4 control-label">Nominal (Rp.)</label>
								<div class="col-sm-8">
									<input type="text" placeholder="Masukkan Nilai dan Tekan Enter" id="allAnggaran" name="allAnggaran" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Anggaran Setiap Bulan Tidak Sama</h3>
						</div>
						<div class="box-body">
							<table class="table">
								<tbody>
									<?php foreach ($month as $row) : ?>
										<input type="hidden" name="month_id[]" value="<?php echo $row['month_id']; ?>">
										<tr>
											<td><strong><?php echo $row['month_name']; ?></strong></td>
											<td><input type="text" id="n<?php echo $row['month_id'] ?>" name="nominal[]" class="form-control" required="">
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-success">Simpan</button>
							<a href="<?php echo site_url('manage/anggaran/view_detail/' . $anggaran['anggaran_id']) ?>" class="btn btn-default">Cancel</a>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$("#allAnggaran").keypress(function(event) {
		var allAnggaran = $("#allAnggaran").val();
		if (event.keyCode == 13) {
			event.preventDefault();
			<?php foreach ($month as $row) : ?>
				$("#n<?php echo $row['month_id'] ?>").val(allAnggaran);
			<?php endforeach ?>
		}
	});
</script>