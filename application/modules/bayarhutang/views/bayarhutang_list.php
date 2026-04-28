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
			<div class="col-md-12">
				<?php if (empty($f)) { ?>
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Data Hutang</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="box-body table-responsive">
								<table id="dtable" class="table table-hover">
									<thead>
										<tr>
											<th>No</th>
											<th>Unit</th>
											<th>Noref</th>
											<th>Tanggal</th>
											<th>Kreditur</th>
											<th>Nominal</th>
											<th>Terbayar</th>
											<th>Sisa</th>
											<th>Keterangan</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (!empty($dataHutang)) {
											$i = 1;
											foreach ($dataHutang as $row) :
												$sisa = $row['hutang_bill'] - $row['hutang_cicil'];
										?>
												<tr>
													<td><?= $i ?></td>
													<td><?= $row['majors_short_name'] ?></td>
													<td><?= $row['hutang_noref'] ?></td>
													<td style="text-align: center;"><?= pretty_date($row['hutang_date'], 'd F Y', false) ?></td>
													<td><?= $row['hutang_kreditur'] ?></td>
													<td>Rp. <?= number_format((!empty($row['hutang_bill']) ? $row['hutang_bill'] : 0), 0, ',', '.') ?></td>
													<td>Rp. <?= number_format((!empty($row['hutang_cicil']) ? $row['hutang_cicil'] : 0), 0, ',', '.') ?></td>
													<td>Rp. <?= number_format($sisa, 0, ',', '.') ?></td>
													<td><span class="label <?php echo ($sisa > 0) ?  'label-warning' : 'label-success' ?>"><?php echo ($sisa > 0) ? 'Belum Lunas' : 'Lunas' ?></span></td>
													<td align="center">
														<button type="button" data-dismiss="modal" class="btn btn-success btn-xs" onclick="ambil_data('<?= $row['hutang_noref'] ?>')"><i class="fa fa-money"></i> Bayar Hutang</button>
													</td>
												</tr>
											<?php
												$i++;
											endforeach;
										} else {
											?>
											<tr id="row">
												<td colspan="9" align="center">Data Kosong</td>
											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				<?php } ?>
				<?php if ($f) { ?>

					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Informasi Hutang</h3>
							<?php if (isset($f['r'])) { ?>
								<div class="pull-right">
									<a href="<?php echo site_url('manage/bayarhutang') ?>" class="btn btn-default btn-xs">Kembali</a>
									<a href="<?php echo site_url('manage/bayarhutang/printBook' . '/?' . http_build_query($f)) ?>" target="_blank" class="btn btn-danger btn-xs">Cetak Rincian Hutang</a>
								</div>
							<?php } ?>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="col-md-12">
								<table class="table table-striped">
									<tbody>
										<tr>
											<td>No. Ref Hutang</td>
											<td>:</td>
											<?php foreach ($kreditur as $row) : ?>
												<?php echo ($f['r'] == $row['hutang_noref']) ?
													'<td>' . $row['hutang_noref'] . '</td>' : '' ?>
											<?php endforeach; ?>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>Nama Kreditur</td>
											<td>:</td>
											<?php $namaKreditur = '';
											foreach ($kreditur as $row) : ?>
												<?php
												$namaKreditur = $row['hutang_kreditur'];
												echo ($f['r'] == $row['hutang_noref']) ?
													'<td>' . $row['hutang_kreditur'] . '</td>' : '' ?>
											<?php endforeach; ?>
											<td width="200"></td>
											<td width="4">:</td>
											<td></td>
										</tr>
										<tr>
											<td>Tanggal Hutang</td>
											<td>:</td>
											<?php foreach ($kreditur as $row) : ?>
												<?php echo ($f['r'] == $row['hutang_noref']) ?
													'<td>' . pretty_date($row['hutang_date'], 'd F Y', false) . '</td>' : '' ?>
											<?php endforeach; ?>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>Nominal Hutang</td>
											<td>:</td>
											<?php foreach ($kreditur as $row) : ?>
												<?php echo ($f['r'] == $row['hutang_noref']) ?
													'<td>Rp ' . number_format($row['hutang_bill'], '0', ',', '.') . '</td>' : '' ?>
											<?php endforeach; ?>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Rekap Hutang</h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Total Hutang</label>
												<input type="text" class="form-control" name="total_setor" id="total_setor" value="<?php echo 'Rp ' . number_format($sumHutang, '0', ',', '.') ?>" placeholder="Total Hutang" readonly="">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Terbayar</label>
												<input type="text" class="form-control" name="total_tarik" id="total_tarik" value="<?php echo 'Rp ' . number_format($sumHutangPay, '0', ',', '.') ?>" placeholder="Total Lunas" readonly="">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label>Sisa</label>
										<input type="text" class="form-control" readonly="" name="saldo" id="saldo" value="<?php echo 'Rp ' . number_format($sumHutang - $sumHutangPay, '0', ',', '.') ?>" placeholder="Sisa Hutang">
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Cetak Bukti Bayar</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									<form action="<?php echo site_url('manage/bayarhutang/cetakBukti') ?>" method="GET" class="view-pdf">
										<input type="hidden" name="n" value="<?php echo $f['n'] ?>">
										<input type="hidden" name="r" value="<?php echo $f['r'] ?>">
										<div class="form-group">
											<label>Tanggal Transaksi</label>
											<div class="input-group date " data-date="<?php echo date('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" readonly="" required="" type="text" name="d" value="<?php echo date('Y-m-d') ?>">
											</div>
										</div>
										<button class="btn btn-danger btn-block" formtarget="_blank" type="submit">Cetak</button>
									</form>
								</div>
							</div>
						</div>
					</div>

					<!-- List Tagihan Bulanan -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Cicil Hutang</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#bayarHutang">Bayar Hutang</button>
							<br>
							<div class="box-body table-responsive">
								<table id="dtable" class="table table-bordered" style="white-space: nowrap;">
									<thead>
										<tr class="info">
											<th>No.</th>
											<th>Tanggal</th>
											<th>Keterangan</th>
											<th>Nominal</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										$count = 1;
										foreach ($hutang_pay as $row) :
											$count += 1;
										?>
											<tr>
												<td><?= $i ?></td>
												<td style="text-align: center;"><?= pretty_date($row['hutang_pay_date'], 'd F Y', false) ?></td>
												<td><?= $row['hutang_pay_note'] ?></td>
												<td>Rp <?= number_format($row['hutang_pay_bill'], 0, ",", ".") ?></td>
												<td>
													<a href="<?php echo site_url('manage/bayarhutang/delete_pay/' . $row['hutang_pay_id']) ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Hapus Hutang" onclick="return confirm('<?php echo 'Apakah anda akan pembayaran hutang a.n ' . $namaKreditur . '?' ?>')"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php
											$i++;
										endforeach;
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>



				<?php } ?>
			</div>
		</div>
		<div class="modal fade" id="bayarHutang" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Bayar Hutang</h4>
					</div>
					<div class="modal-body">

						<?php echo form_open('manage/bayarhutang/pay', array('method' => 'post')); ?>

						<?php foreach ($kreditur as $row) : ?>
							<input type="hidden" name="hutang_pay_hutang_id" value="<?= $row['hutang_id'] ?>">
						<?php endforeach; ?>

						<?php foreach ($kreditur as $row) : ?>
							<input type="hidden" name="hutang_account_id" value="<?= $row['account_id'] ?>">
						<?php endforeach; ?>

						<div class="form-group">
							<label>Akun Kas *</label>
							<select required="" name="kas_account_id" id="kas_account_id" class="form-control">
								<option value="">-- Pilih Akun Kas --</option>
								<?php
								foreach ($dataKas as $row) {
								?>
									<option value="<?php echo $row['account_id'] ?>">
										<?php echo $row['account_code'] . ' - ' . $row['account_description'];
										?>
									</option>
								<?php
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Tanggal</label>
							<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
								<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								<input class="form-control" required="" type="text" name="hutang_pay_date" value="<?php echo date("Y-m-d") ?>" placeholder="Tanggal Bayar">
							</div>
						</div>

						<div class="form-group">
							<label>Jumlah Bayar</label>
							<input type="text" class="form-control" required="" name="hutang_pay_bill" class="form-control" placeholder="Jumlah Bayar" value="<?= $sumHutang - $sumHutangPay ?>">
						</div>

						<div class="form-group">
							<label>Catatan</label>
							<input type="text" class="form-control" required="" name="hutang_pay_note" class="form-control" placeholder="Catatan" value="<?= 'Cicilan ' . $count ?>">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Bayar Hutang</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Modal -->


<script>
	function ambil_data(noref) {
		var norefHutang = noref;
		var thAjaran = $("#th_ajar").val();

		window.location.href = '<?php echo base_url(); ?>manage/bayarhutang?r=' + norefHutang;
	}
</script>

<style>
	div.over {
		width: 425px;
		height: 165px;
		overflow: scroll;
	}

	div.extended {
		width: 900px;
		height: 200px;
		overflow: scroll;
	}
</style>