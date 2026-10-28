<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Awal</label>
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="ds" id="ds" readonly="readonly" value="<?php echo isset($q['ds']) ? $q['ds'] : date('Y-m-d') ?>" placeholder="Tanggal Awal">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Akhir</label>
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="de" id="de" readonly="readonly" value="<?php echo isset($q['de']) ? $q['de'] : date('Y-m-d') ?>" placeholder="Tanggal Akhir">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Unit</label>
									<select class="form-control" name="unit" id="unit" required>
										<option value="all">-- Semua Unit --</option>
										<?php foreach ($majors as $row) : ?>
											<option <?php echo (isset($q['unit']) and $q['unit'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Jenis Transaksi</label>
									<div id="div-pembayaran">
										<select class="form-control" name="source" id="source" required>
											<option value="all">--Semua Jenis Transaksi--</option>
											<?php foreach ($jenis_transaksi as $key => $value) : ?>
												<option <?php echo (isset($q['source']) and $q['source'] == $key) ? 'selected' : '' ?> data-source="<?php echo $key ?>" value="<?php echo $key ?>"><?php echo $value ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control" name="status" id="status" required>
										<option <?php echo (isset($q['status']) and $q['status'] == 'DRAFT') ? 'selected' : '' ?> value="DRAFT">DRAFT</option>
										<option <?php echo (isset($q['status']) and $q['status'] == 'POSTED') ? 'selected' : '' ?> value="POSTED">POSTED</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div style="margin-top:25px;">
									<button type="submit" class="btn btn-primary">Cari</button>
									<?php if (!empty($list) && isset($q['status']) && $q['status'] == 'DRAFT') : ?>
										<a class="btn btn-warning" href="<?php echo site_url('manage/akrual/posting' . '/?' . http_build_query($q)) ?>">Posting Akrual</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>

				<div class="box box-info">
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-responsive table-bordered" style="white-space: nowrap;">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Unit</th>
									<th>Jenis Transaksi</th>
									<th>Keterangan</th>
									<th>Jumlah</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php $total = 0;
								if (!empty($list)) :
								?>
									<?php foreach ($list as $row) : ?>
										<tr>
											<td style="text-align: center;"><?= $row->tanggal ?></td>
											<td style="text-align: center;"><?= $row->majors_short_name ?></td>
											<td style="text-align: center;"><?= $row->source_type ?></td>
											<td><?= $row->keterangan ?></td>
											<td class="text-right">Rp. <?= number_format($row->total_debet, 0, ',', '.') ?></td>
											<td style="text-align: center;">
												<?php if ($row->status_posting == 'DRAFT') : ?>
													<span class="badge badge-warning">Belum Diposting</span>
												<?php else : ?>
													<span class="badge badge-success">Sudah Diposting</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php
										$total += $row->total_debet;
									endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="5" class="text-center text-muted">
											Belum ada data untuk ditampilkan
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" align="center"><strong>TOTAL</strong></td>
									<td align="right"><strong>Rp. <?= number_format($total, 0, ',', '.') ?></strong></td>
									<td></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- /.content -->
</div>