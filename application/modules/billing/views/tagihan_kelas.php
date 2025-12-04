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
									<label>Tahun Ajaran</label>
									<select class="form-control" name="p" required="">
										<option value="">-- Pilih T.A. --</option>
										<?php foreach ($period as $row) : ?>
											<option <?php echo (isset($q['p']) and $q['p'] == $row['period_id']) ? 'selected' : '' ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Unit Sekolah</label>
									<select class="form-control" name="m" id="majors_id" onchange="get_kelas()" required="">
										<option value="">-- Pilih Unit --</option>
										<?php foreach ($majors as $row) : ?>
											<option <?php echo (isset($q['m']) and $q['m'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<label>Dari Bulan</label>
									<select class="form-control" name="d" id="dari_id" required="">
										<?php foreach ($from as $row) : ?>
											<option <?php echo (isset($q['d']) and $q['d'] == $row['month_id']) ? 'selected' : '' ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Sampai Bulan</label>
									<select class="form-control" name="s" id="sampai_id">
										<?php foreach ($to as $row) : ?>
											<?php if (isset($q['s'])) { ?>
												<option <?php echo (isset($q['s']) and $q['s'] == $row['month_id']) ? 'selected' : '' ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
											<?php } else { ?>
												<option <?php echo ($row['month_id'] == 12) ? 'selected' : '' ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
										<?php }
										endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div style="margin-top:25px;">
									<button type="submit" class="btn btn-primary">Cari</button>
									<?php if ($q and !empty($posBayar)) { ?>
										<a class="btn btn-success" target="_blank" href="<?php echo site_url('manage/billing/rekap_tagihan_kelas' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-excel-o"></i> Rekap Tagihan Kelas</a>
									<?php } ?>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>
				<?php if ($q and !empty($posBayar)) {
					$jumlahPos = COUNT($posBayar); ?>
					<div class="box box-info">
						<div class="box-body table-responsive">
							<table class="table table-responsive table-bordered" style="white-space: nowrap;">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle;">Kelas</th>
										<th colspan="<?php echo $jumlahPos; ?>">Jenis Pembayaran</th>
									</tr>
									<tr>
										<?php
										foreach ($posBayar as $jenis_bayar) {
											echo '<th>' . $jenis_bayar['pos_name'] . '</th>';
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php
									$totalTagihanPos = [];
									foreach ($kelas as $row) {
										echo '<tr>';
										echo '<td>' . $row['class_name'] . '</td>';
										foreach ($posBayar as $jenisBayar) {
											$totalTagihan = 0;
											foreach ($dataTagihan as $tagihan) {
												if ($tagihan['class_id'] == $row['class_id']) {
													if ($tagihan['pos_id'] == $jenisBayar['pos_id']) {
														$totalTagihan = $tagihan['totalTagihan'] ?? 0;
													}
												}
											}
											$totalTagihanPos[$jenisBayar['pos_id']] += $totalTagihan;
											echo '<td>Rp ' . number_format($totalTagihan, 0, ",", ".") . '</td>';
										}
										echo '</tr>';
									}

									?>
								</tbody>
								<tfoot>
									<tr>
										<th>Total</th>
										<?php
										foreach ($totalTagihanPos as $key => $totalPos) {
											echo '<th>Rp ' . number_format($totalPos, 0, ",", ".") . '</th>';
										}
										?>
									</tr>
								</tfoot>
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
			url: '<?php echo base_url(); ?>manage/billing/class_searching',
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
<script>
	$(document).ready(function() {
		$("#selectall").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
	$(document).ready(function() {
		$("#selectall2").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>