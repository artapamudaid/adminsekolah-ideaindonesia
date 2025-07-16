<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
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

						<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#printStudent"><i class="fa fa-print"></i> Cetak</button>
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#xlsStudent"><i class="fa fa-excel"></i> Export Xls</button>
						<div class="row">
							<div class="col-md-12"><br>
								<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'post')) ?>
								<table style="width: 100%;">
									<tr>
										<td style="width: 20%;">
											<select id="majors_id" name="majors_id" class="form-control" required>
												<option value="">--- Pilih Unit ---</option>
												<?php foreach ($majors as $row) { ?>
													<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
												<?php } ?>
											</select>
										</td>
										<td style="width: 2%;"></td>

										<td style="width: 30%;">
											<div id="div_kas">
												<select class="form-control multiple-select" data-select2-id="kas_account_id" name="kas_account_id[]" multiple="multiple">
													<option value="">--- Pilih Akun ---</option>
												</select>
											</div>
										</td>
										<td style="width: 2%;"></td>
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
										<th>Akun Pembayaran</th>
										<th>Unit</th>
										<th>Total Pemasukan</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($pemasukan)) {
										$i = 1;
										foreach ($pemasukan as $row):
									?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row['account_description']; ?></td>
												<td><?php echo $row['majors_short_name']; ?></td>
												<td><?php echo 'Rp. ' . number_format($row['total_deb'], 0, ',', '.'); ?></td>
											</tr>
										<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="4" align="center">Data Kosong</td>
										</tr>
									<?php } ?>
								</tbody>
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



<script type="text/javascript">
	$(document).ready(function() {
		$('.multiple-select').select2();
	});

	$("#majors_id").change(function() {
		var val = $(this).val();
		if (val === 'all') {
			$("#div_kas .multiple-select").attr("multiple", true);
			$('.multiple-select').attr("name", "kas_account_id[]");
		} else {
			$("#div_kas .multiple-select").removeAttr("multiple");
			$('.multiple-select').removeAttr("name");
		}
		$('.multiple-select').select2();
	});

	$("#majors_id").change(function(e) {
		var id_majors = $("#majors_id").val();

		$.ajax({
			url: '<?php echo base_url(); ?>manage/statistik/cari_akun',
			type: 'POST',
			data: {
				'id_majors': id_majors,
				'kategori': 'debit'
			},
			success: function(msg) {
				$("#div_kas").html(msg);
				$("#div_kas .multiple-select").attr("data-select2-id", "kas_account_id");
				$('.multiple-select').select2();
				// tampil_data();
				$("#btnFinish").show();
			},
			error: function(msg) {
				alert('msg');
			}

		});
		e.preventDefault();
	});
</script>