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
	<section class="content">
		<div class="row">
			<div class="box-body">
				<?php echo form_open_multipart(current_url(), array('class' => 'form-horizontal')); ?>
				<?php echo validation_errors(); ?>

				<div class="col-md-6">
					<div class="box box-danger">
						<div class="box-header">
							<h3 class="box-title">Informasi Hutang</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label for="" class="col-sm-4 control-label">Jenis Bayar</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo $settinghutang['poshutang_name'] ?>" readonly="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-4 control-label">Tahun Ajaran</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo $settinghutang['period_start'] . '/' . $settinghutang['period_end'] ?>" readonly="">
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-md-6">

					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title">Jumlah Hutang</h3>
						</div>
						<div class="box-body table-responsive">
							<input type="hidden" name="majors_id" value="<?= $settinghutang['majors_id'] ?>">
							<input type="hidden" name="hutang_settinghutang_id" value="<?= $settinghutang['settinghutang_id'] ?>">
							<input type="hidden" name="poshutang_account_id" value="<?= $settinghutang['poshutang_account_id'] ?>">
							<table class="table">
								<tbody>
									<tr>
										<td><strong>No. Ref Hutang</strong></td>
										<td><input type="text" name="hutang_noref" id="hutang_noref" readonly="" class="form-control" value="<?php echo $noref ?>">
										</td>
									</tr>
									<tr>
										<td><strong>Tanggal Hutang</strong></td>
										<td>
											<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="hutang_date" id="hutang_date" readonly="readonly" placeholder="Tanggal Hutang">
											</div>
										</td>
									</tr>
									<tr>
										<td><strong>Nama Kreditur</strong></td>
										<td>
											<input type="text" name="hutang_kreditur" class="form-control" required>
										</td>
									</tr>
									<tr>
										<td><strong>Nominal Hutang (Rp.)</strong></td>
										<td><input type="number" name="hutang_bill" id="hutang_bill" placeholder="Masukan Jumlah Hutang" required="" class="form-control">
										</td>
									</tr>
									<tr>
										<td><strong>Akun Penerimaan Hutang</strong></td>
										<td>
											<select name="hutang_account_id" id="hutang_account_id" required="" class="form-control">
												<option value="">-- Pilih Akun Kas</option>
												<?php foreach ($account as $val) { ?>
													<option value="<?= $val['account_id'] ?>"><?= $val['account_code'] . ' - ' . $val['account_description'] ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-success">Simpan</button>
							<a href="<?php echo site_url('manage/settinghutang/view_hutang/' . $settinghutang['settinghutang_id']) ?>" class="btn btn-default">Cancel</a>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</section>
</div>

<script>
	set_default();

	function set_default() {

		if ($('#hutang_bill').val() == '') {
			$('#hutang_bill').val('0');
		}

	}
</script>