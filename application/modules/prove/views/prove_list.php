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
						<div class="row">
							<div class="col-md-9">
								<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get', 'onsubmit' => 'cari_data(); return false;')) ?>
								<div class="box-body table-responsive">
									<table>
										<tr>
											<td>
												<select style="width: 200px;" id="m" name="m" class="form-control" required onchange="get_kelas()">
													<option value="">--- Pilih Unit Sekolah ---</option>
													<?php foreach ($majors as $row) { ?>
														<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												&nbsp&nbsp
											</td>
											<td id="td_kelas">
												<select style="width: 200px;" id="c" name="c" class="form-control" required>
													<option value="">--- Pilih Kelas ---</option>
													<?php if (isset($s['m'])) { ?>
														<option value="all" <?php echo (isset($s['c']) && $s['c'] == 'all') ? 'selected' : '' ?>>Semua Kelas</option>
														<?php foreach ($class as $row) { ?>
															<option value="<?php echo $row['class_id']; ?>" <?php echo (isset($s['c']) && $s['c'] == $row['class_id']) ? 'selected' : '' ?>><?php echo $row['class_name'] ?></option>
														<?php } ?>
												</select>
											<?php } ?>
											<td>
												&nbsp&nbsp
											</td>
											<td>
												<select style="width: 200px;" id="s" name="s" class="form-control" required>
													<option value="" <?php echo (isset($s['s']) && $s['s'] == '') ? 'selected' : '' ?>> --- Pilih Status --- </option>
													<option value="all" <?php echo (isset($s['s']) && $s['s'] == 'all') ? 'selected' : '' ?>>Semua</option>
													<option value="1" <?php echo (isset($s['s']) && $s['s'] == '1') ? 'selected' : '' ?>>Terverifikasi</option>
													<option value="0" <?php echo (isset($s['s']) && $s['s'] == '0') ? 'selected' : '' ?>>Dibatalkan</option>
													<option value="2" <?php echo (isset($s['s']) && $s['s'] == '2') ? 'selected' : '' ?>>Dibayar</option>
													<option value="3" <?php echo (isset($s['s']) && $s['s'] == '3') ? 'selected' : '' ?>>Ditolak</option>
												</select>
											</td>
											<td>
												&nbsp&nbsp
											</td>
											<td>
												<button type="button" class="btn btn-primary" onclick="cari_data()"><i class="fa fa-search"></i> Cari</button>
											</td>
										</tr>
									</table>
								</div>
								<?php echo form_close(); ?>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
								<thead>
									<tr>
										<th>No</th>
										<th>NIS</th>
										<th>Nama</th>
										<th>Kelas</th>
										<th>Tanggal Bayar</th>
										<th>Bukti Bayar</th>
										<th>Keterangan</th>
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
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

<script>
	$(document).ready(function() {
		cari_data();
	});

	function cari_data() {
		var m = $("#m").val();
		var c = $("#c").val();
		var s = $("#s").val();

		$("#dtable").DataTable({
			ordering: true,
			order: [[4, 'desc']],
			processing: true,
			serverSide: true,
			destroy: true,
			language: {
				processing: "<img src='<?php echo base_url() ?>uploads/loading/loading.gif' style='width:50px; height:50px;' />"
			},
			ajax: {
				url: "<?php echo base_url('manage/prove/datatable_prove'); ?>",
				type: 'POST',
				data: {
					m: m,
					c: c,
					s: s
				}
			}
		});
	}

	function get_kelas() {
		var id_majors = $("#m").val();
		$.ajax({
			url: '<?php echo base_url(); ?>manage/prove/class_searching',
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