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
				<div class="box">
					<!-- /.box-header -->
					<div class="box-header">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<table class="table">
							<tr>
								<td width="20%">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="start" id="start" readonly="readonly" placeholder="Tanggal Awal" value="<?= (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d') ?>">
									</div>
								</td>
								<td width="20%">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="end" id="end" readonly="readonly" placeholder="Tanggal Akhir" value="<?= (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ?>">
									</div>
								</td>
								<td>
									<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
								</td>
							</tr>
						</table>
						<?php echo form_close() ?>
					</div>
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>No. INV</th>
									<th>Status</th>
									<th>Nama Pemesan</th>
									<th>HP Pemesan</th>
									<th>Nama Penginapan</th>
									<th>Check-In</th>
									<th>Check-Out</th>
									<th>Nominal</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($reservasi)) {
									$i = 1;
									$total = 0;
									foreach ($reservasi as $row) :
										$total += $row['reservasi_total'];
								?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['reservasi_ref_id']; ?></td>
											<td><?php echo $row['reservasi_status']; ?></td>
											<td><?php echo $row['reservasi_name']; ?></td>
											<td><?php echo $row['reservasi_hp']; ?></td>
											<td><?php echo $row['homestay_name']; ?></td>
											<td><?php echo pretty_date($row['reservasi_checkin'], 'd F Y', false); ?></td>
											<td><?php echo pretty_date($row['reservasi_checkout'], 'd F Y', false); ?></td>
											<td><?php echo 'Rp ' . number_format($row['reservasi_total'], 0, ',', '.'); ?></td>
										</tr>
									<?php
										$i++;
									endforeach;
								} else {
									?>
									<tr id="row">
										<td colspan="9" align="center">Data Kosong</td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="8" style="text-align: center;">Total Nominal</th>
									<th><?php echo 'Rp ' . number_format($total, 0, ',', '.'); ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>