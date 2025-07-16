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
							<div class="col-md-3">
								<div class="form-group">
									<label>Tahun Ajaran</label>
									<select class="form-control" name="p">
										<option value="">-- Pilih Tahun Ajaran --</option>
										<?php foreach ($period as $row): ?>
											<option <?php echo (isset($q['p']) and $q['p'] == $row['period_id']) ? 'selected' : '' ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Unit Sekolah</label>
									<select class="form-control" name="k" id="majors_id" onchange="get_kelas()">
										<option value="">-- Pilih Unit Sekolah --</option>
										<?php foreach ($majors as $row): ?>
											<option <?php echo (isset($q['k']) and $q['k'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div id="td_kelas">
								<div class="col-md-3">
									<div class="form-group">
										<label>Kelas</label>
										<select class="form-control" name="c" id="class_id">
											<option value="">-- Pilih Kelas --</option>
											<?php if (isset($q['k'])) { ?>
												<?php foreach ($class as $row): ?>
													<option <?php echo (isset($q['c']) and $q['c'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
												<?php endforeach; ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div style="margin-top:25px;">
									<button type="submit" class="btn btn-primary">Filter</button>
									<?php if ($q and !empty($py)) { ?>
										<a class="btn btn-success" target="_blank" href="<?php echo site_url('manage/report/report_bill_detail' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-excel-o"></i> Export Excel</a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($q and !empty($py)) { ?>
					<div class="box box-info">
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-responsive table-hover table-bordered" style="white-space: nowrap;">
								<thead>
									<tr>
										<th style="border: solid 1px;vertical-align:middle;" rowspan="2">NIS</th>
										<th style="border: solid 1px;vertical-align:middle;" rowspan="2">Kelas</th>
										<th style="border: solid 1px;vertical-align:middle;" rowspan="2">Nama</th>
										<?php foreach ($py as $row) : ?>
											<th style="border: solid 1px;" colspan="<?php echo count($month) ?>">
												<center><?php echo $row['pos_name'] . ' - T.A ' . $row['period_start'] . '/' . $row['period_end']; ?></center>
											</th>
										<?php endforeach ?>
										<?php foreach ($bebas as $key) : ?>
											<th style="border: solid 1px;vertical-align:middle;" rowspan="2"><?php echo $key['pos_name'] . ' - T.A ' . $key['period_start'] . '/' . $key['period_end']; ?></th>
										<?php endforeach ?>
									</tr>
									<tr>
										<?php
										for ($a = 1; $a <= $looping['jml']; $a++) {
											foreach ($month as $key) {
										?>
												<th
													<?php
													$style = '';
													if ($key['month_name'] == 'Juli') {
														$style = 'border-left: solid 1px;';
													} elseif ($key['month_name'] == 'Juni') {
														$style = 'border-right: solid 1px;';
													}
													echo $style ? 'style="' . $style . '"' : '';
													?>>
													<?php echo $key['month_name']; ?>
												</th>
										<?php
											}
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($student as $row) : ?>
										<tr>
											<td style="border-left: solid 1px; "><?php echo $row['student_nis'] ?></td>
											<td><?php echo $row['class_name'] ?></td>
											<td style="border-right: solid 1px; "><?php echo $row['student_full_name'] ?></td>
											<!-- Bulan -->
											<?php foreach ($arrayPaymentBulan as $key) : ?>
												<?php $tPayBul = 12; ?>
												<?php foreach ($dataBulan[$key] as $paymentBulan) { ?>
													<?php if ($paymentBulan['student_student_id'] == $row['student_id']) { ?>
														<td <?php echo ($paymentBulan['month_month_id'] == 12) ? 'style="border-right: solid 1px;"' : ''  ?>> <span style="color:<?php echo ($paymentBulan['bulan_status'] == 1) ? '#00E640' : 'red'; ?>"> <?php echo ($paymentBulan['bulan_status'] == 1) ? 'Lunas' : number_format($paymentBulan['bulan_bill'], 0, ',', '.') ?> </span></td>
														<?php $tPayBul--; ?>
													<?php } ?>
												<?php } ?>
												<?php for ($i = 0; $i < $tPayBul; $i++) {
													if ($i == 11) {
														echo '<td style="border-right: solid 1px;">-</td>';
													} else {
														echo '<td>-</td>';
													}
												} ?>
											<?php endforeach ?>
											<!-- End Bulan -->

											<!-- Bebas -->
											<?php foreach ($arrayPaymentBebas as $key) : ?>
												<?php foreach ($dataFree[$key] as $paymentFree) : ?>
													<?php $isPayment = false; ?>
													<?php if ($paymentFree['student_student_id'] == $row['student_id']) { ?>
														<td style="text-align:center; border-left: solid 1px;border-right: solid 1px;"> <span style="text-align:center;color:<?php echo ($paymentFree['bebas_bill'] == $paymentFree['bebas_total_pay']) ? '#00E640' : 'red' ?> "> <?php echo ($paymentFree['bebas_bill'] == $paymentFree['bebas_total_pay']) ? 'Lunas' : number_format($paymentFree['bebas_bill'] - $paymentFree['bebas_total_pay'], 0, ',', '.') ?> </span></td>
														<?php
														$isPayment = true;
														break;
														?>
													<?php } ?>
												<?php endforeach ?>
												<?php if (!$isPayment) {
													echo '<td style="text-align:center; border-left: solid 1px;border-right: solid 1px;">-</td>';
												} ?>
											<?php endforeach ?>
											<!-- End Bebas -->
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<script>
	function get_kelas() {
		var id_majors = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/report/class_searching',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#td_kelas").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>