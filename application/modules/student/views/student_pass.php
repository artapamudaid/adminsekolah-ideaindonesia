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
				<div class="alert alert-danger">
					Warning!... !
					Halaman ini digunakan untuk merubah status siswa menjadi lulus. Pastikan siswa yang di proses adalah siswa kelas akhir.
				</div>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-body">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon alert-success">Unit</div>
								<select class="form-control" name="m" id="m" onchange="get_kelas()">
									<option value="">-- Pilih Unit Sekolah --</option>
									<?php foreach ($majors as $row): ?>
										<option <?php echo (isset($f['m']) and $f['m'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
									<?php endforeach; ?>
								</select>
								<div class="input-group-addon alert-info">Kelas</div>
								<div id="div_kelas">
									<select class="form-control" name="pr" id="pr" onchange="this.form.submit()">
										<option value="">-- Pilih Kelas --</option>
										<?php if (isset($f['m'])) { ?>
											<?php foreach ($class as $row): ?>
												<option <?php echo (isset($f['pr']) and $f['pr'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
											<?php endforeach; ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<?php echo form_close() ?>
						<table class="table table-hover table-bordered table-responsive">
							<form action="<?php echo site_url('manage/student/multiple'); ?>" method="post" id="lulus">
								<input type="hidden" name="majors_id" value="<?php echo (isset($f['m'])) ? $f['m'] : '' ?>">
								<input type="hidden" name="action" value="pass">
								<thead>
									<tr>
										<th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th>
										<th>No</th>
										<th>NIS</th>
										<th>Nama</th>
										<th>Status Pembayaran</th>
										<th>Tagihan</th>
										<!-- <th>Status Kelulusan</th> -->
									</tr>
								</thead>
								<tbody>
									<?php if ($this->input->get(NULL)) { ?>
										<?php
										if (!empty($notpass)) {
											$i = 1;
											foreach ($notpass as $row):
										?>
												<tr style="<?php echo ($row['student_status'] == 0) ? 'color:#00E640' : '' ?>">
													<td><input type="checkbox" class="<?php echo ($row['student_status'] == 0) ? NULL : 'checkbox' ?>" <?php echo ($row['student_status'] == 0) ? 'disabled' : NULL ?> name="msg[]" value="<?php echo $row['student_id']; ?>"></td>
													<td><?php echo $i; ?></td>
													<td><?php echo $row['student_nis']; ?></td>
													<td><?php echo $row['student_full_name']; ?></td>
													<td>
														<?php
														$bulan = $this->db->query("SELECT COUNT(bulan_id) as c_bulan FROM bulan WHERE student_student_id = '" . $row['student_id'] . "' AND bulan_status = 0")->row_array();
														$bebas = $this->db->query("SELECT COUNT(bebas_id) as c_bebas FROM bebas WHERE student_student_id = '" . $row['student_id'] . "' AND (bebas_bill - bebas_diskon - bebas_total_pay) = 0")->row_array();
														?>
														<label class="label <?php echo ($bulan['c_bulan'] == 0 and $bebas['c_bebas'] == 0) ? 'label-success' : 'label-danger' ?>"><?php echo ($bulan['c_bulan'] == 0 and $bebas['c_bebas'] == 0) ? 'Lunas' : 'Belum Lunas' ?></label>
													</td>
													<?php
													$nomBulan = $this->db->query("SELECT SUM(bulan_bill) as s_bulan FROM bulan WHERE student_student_id = '" . $row['student_id'] . "' AND bulan_status = 0")->row_array();
													$nomBebas = $this->db->query("SELECT SUM(bebas_bill - bebas_diskon - bebas_total_pay) as s_bebas FROM bebas WHERE student_student_id = '" . $row['student_id'] . "'")->row_array();
													?>
													<td>
														<?php echo 'Rp ' .  number_format($nomBulan['s_bulan'] + $nomBebas['s_bebas'], '0', ',', '.') ?>
													</td>
													<!-- <td><label class="label <?php echo ($row['student_status'] == 0) ? 'label-success' : 'label-warning' ?>"><?php echo ($row['student_status'] == 0) ? 'Lulus' : 'Belum Lulus' ?></label></td>	 -->
												</tr>
											<?php
												$i++;
											endforeach;
										} else {
											?>
											<tr id="row">
												<td colspan="6" align="center">Data Kosong</td>
											</tr>
										<?php } ?>
									<?php } else {
									?>
										<tr id="row">
											<td colspan="6" align="center">Data Kosong</td>
										</tr>
									<?php } ?>
								</tbody>
							</form>
						</table>
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="panel panel-info">
					<div class="panel-body">
						<button class="btn btn-info btn-block" type="submit" onclick="$('#lulus').submit()"><span class="glyphicon glyphicon glyphicon-chevron-right"></span> Proses Lulus</button>
						<br>
						<button class="btn btn-danger btn-block" onclick="$('#kembali').submit();"><span class="glyphicon glyphicon glyphicon-chevron-left"></span> Batal Lulus</button>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="box">
					<div class="box-body">
						<h4>Data Kelulusan</h4>
						<table class="table table-hover table-bordered table-responsive">
							<form action="<?php echo site_url('manage/student/multiple'); ?>" method="post" id="kembali">
								<input type="hidden" name="action" value="notpass">
								<tr>
									<th><input type="checkbox" id="selectall2" value="checkbox" name="checkbox"></th>
									<th>No</th>
									<th>NIS</th>
									<th>Nama</th>
									<!-- <th>Status Kelulusan</th> -->
								</tr>
								<tbody>
									<?php
									if (!empty($pass)) {
										$i = 1;
										foreach ($pass as $row):
									?>
											<tr style="color:#00E640">
												<td><input type="checkbox" class="checkbox2" name="msg[]" value="<?php echo $row['student_id']; ?>"></td>
												<td><?php echo $i; ?></td>
												<td><?php echo $row['student_nis']; ?></td>
												<td><?php echo $row['student_full_name']; ?></td>
												<!-- <td><label class="label label-success"><?php echo ($row['student_status'] == 0) ? 'Lulus' : 'Belum Lulus' ?></label></td> -->
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
							</form>
						</table>
					</div>
					</form>
				</div>
			</div>

		</div>

	</section>
	<!-- /.content -->
</div>

<script>
	$(document).ready(function() {
		$("#selectall").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
	$(document).ready(function() {
		$("#selectall2").change(function() {
			$(".checkbox2").prop('checked', $(this).prop("checked"));
		});
	});
</script>

<script>
	function get_kelas() {
		var id_majors = $("#m").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/student/cari_kelas',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#div_kelas").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>