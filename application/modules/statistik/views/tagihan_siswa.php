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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<a target="_blank" href="<?php echo base_url('manage/statistik/tagihan_siswa?mode=pdf&m=') . $s['m'] ?>" type="button" class="btn btn-danger btn-sm"><i class="fa fa-print"></i> Cetak</a>
						<a target="_blank" href="<?php echo base_url('manage/statistik/tagihan_siswa?mode=excel&m=') . $s['m'] ?>" type="button" class="btn btn-success btn-sm"><i class="fa fa-excel"></i> Export Xls</a>
						<div class="row">
							<div class="col-md-9"><br>
								<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
								<table>
									<tr>
										<td>
											<select style="width: 200px;" id="m" name="m" class="form-control">
												<option value="">--- SEMUA UNIT ---</option>
												<?php foreach ($majors as $row) { ?>
													<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
												<?php } ?>
											</select>
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
										</td>
									</tr>
								</table>
								<?php echo form_close(); ?>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
								<thead>
									<tr>
										<th>No</th>
										<!-- <th>NIS</th> -->
										<!-- <th>Nama</th> -->
										<th>Kelas</th>
										<th>Tagihan Pembayaran Bulanan</th>
										<th>Tagihan Pembayaran Bebas</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (COUNT($tagihan) > 0) {
										$i = 1;
										$tBulan = 0;
										$tBebas = 0;
										foreach ($tagihan as $row):
											$tagihanBebas = $row['total_tagihan_bebas'] - $row['total_bebas_terbayar'];
											$tBulan += $row['total_tagihan_bulan'];
											$tBebas += $tagihanBebas;
									?>
											<tr>
												<td><?php echo $i; ?></td>
												<!-- <td><?php echo $row['student_nis']; ?></td> -->
												<!-- <td><?php echo $row['student_full_name']; ?></td> -->
												<td><?php echo $row['class_name']; ?></td>
												<td style="text-align: right;">Rp <?php echo number_format($row['total_tagihan_bulan'], 0, ",", "."); ?></td>
												<td style="text-align: right;">Rp <?php echo number_format($tagihanBebas, 0, ",", "."); ?></td>
											</tr>
										<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="8" align="center">Data Kosong</td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<th></th>
									<!-- <th></th>
									<th></th> -->
									<th>Total</th>
									<th style="text-align: right;">Rp <?php echo number_format($tBulan, 0, ",", "."); ?></th>
									<th style="text-align: right;">Rp <?php echo number_format($tBebas, 0, ",", "."); ?></th>
								</tfoot>
							</table>
						</div>
						<!-- /.box-body -->
					</div>

					<!-- /.box -->
				</div>
			</div>
			</form>
	</section>
	<!-- /.content -->
</div>