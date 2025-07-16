<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addWorksheet"><i class="fa fa-plus"></i> Tambah</button>

						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
						<div class="box-body table-responsive">
							<table>
								<tr>
									<td>
										<select style="width: 200px;" id="p" name="p" class="form-control" required>
											<option value="">--- Pilih Tahun Ajaran ---</option>
											<option value="all" <?php echo (isset($s['p']) && $s['p'] == 'all') ? 'selected' : '' ?>>Semua Tahun Ajaran</option>
											<?php foreach ($period as $row) { ?>
												<option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['p']) && $s['p'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
											<?php } ?>
										</select>
									</td>
									<td>
										&nbsp&nbsp
									</td>
									<td>
										<select style="width: 200px;" id="m" name="m" class="form-control" required>
											<option value="">--- Pilih Unit Pesantren ---</option>
											<?php if ($this->session->userdata('umajorsid') == '0') { ?>
												<option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
											<?php } ?>
											<?php foreach ($majors as $row) { ?>
												<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
											<?php } ?>
										</select>
									</td>
									<td>
										&nbsp&nbsp
									</td>
									<td>
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
									</td>
								</tr>
							</table>
						</div>
						<?php echo form_close(); ?>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Tahun Ajaran</th>
									<th>Unit Pesantren</th>
									<th>Nominal</th>
									<th>Status</th>
									<th>Alokasi</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($worksheet)) {
									$i = 1;
									foreach ($worksheet as $row) :

										$status = $row['worksheet_status'];
										$note = "";

										switch ($status) {
											case "S":
												$note = '<span class="label label-success">Disetujui</span>';
												break;
											case "TS":
												$note = '<span class="label label-danger">Tidak Disetujui</span>';
												break;
											case "D":
												$note = '<span class="label label-warning">Diajukan</span>';
												break;
											case "A":
												$note = '<span class="label label-success">Aktif</span>';
												break;
											default:
												$note = '<span class="label label-danger">Belum Aktif</span>';
										}

								?>
										<tr>
											<td style="text-align: left;"><?php echo $i; ?></td>
											<td style="text-align: center;"><?php echo $row['period_start'] . '/' . $row['period_end']; ?></td>
											<td style="text-align: center;"><?php echo $row['majors_short_name']; ?></td>
											<td>Rp <?php echo number_format($row['worksheet_nominal'], 0, ',', '.'); ?></td>
											<td style="text-align: center;"><?php echo $note; ?></td>
											<td style="text-align: center;">

												<?php if ($status == 'A') { ?>
													<a data-toggle="tooltip" data-placement="top" title="Alokasi Anggaran" class="btn btn-primary btn-xs" href="<?php echo site_url('manage/worksheet/alokasi/' . $row['worksheet_id']) ?>">
														Alokasi Anggaran
													</a>
												<?php } else { ?>
													-
												<?php } ?>
											</td>
											<td style="text-align: center;">
												<?php if ($row['worksheet_status'] == 'B') { ?>
													<a href="<?php echo site_url('manage/worksheet/set_active/' . $row['worksheet_id']) ?>" class="btn btn-xs btn-success" data-toggle="tooltip" title="Aktifkan"><i class="fa fa-check"></i></a>
													<a href="<?php echo site_url('manage/worksheet/edit/' . $row['worksheet_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
													<a href="#delModal<?php echo $row['worksheet_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
												<?php } else { ?>
													-
												<?php } ?>
											</td>
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['worksheet_id']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span></button>
														<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
													</div>
													<div class="modal-body">
														<p>Apakah anda yakin akan menghapus data ini?</p>
													</div>
													<div class="modal-footer">
														<?php echo form_open('manage/worksheet/delete/' . $row['worksheet_id']); ?>
														<input type="hidden" name="delName" value="<?php echo 'Kertas Kerja ' . $row['majors_short_name'] . ' ' . $row['period_start'] . ' ' . $row['period_end']; ?>">
														<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
														<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
														<?php echo form_close(); ?>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
									<?php
										$i++;
									endforeach;
								} else {
									?>
									<tr id="row">
										<td colspan="6" align="center">Data Kosong</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<div>
					<?php echo $this->pagination->create_links(); ?>
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<!-- Modal -->
<div class="modal fade" id="addWorksheet" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Kertas Kerja</h4>
			</div>
			<?php echo form_open('manage/worksheet/add_glob', array('method' => 'post')); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Tahun Ajaran</label>
							<select required="" name="worksheet_period_id" id="worksheet_period_id" class="form-control" onchange="get_anggaran()">
								<option value="">-Pilih Tahun Ajaran-</option>
								<?php foreach ($period as $row) { ?>
									<option value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Unit Pesantren</label>
							<select required="" name="worksheet_majors_id" id="worksheet_majors_id" class="form-control" onchange="get_anggaran()">
								<option value="">-Pilih Unit Pesantren-</option>
								<?php foreach ($majors as $row) { ?>
									<option value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Kepala Pesantren</label>
							<input type="text" required="" name="worksheet_nama_kepsek" class="form-control" placeholder="Masukkan Nama Kepala Pesantren">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>NIP Kepala Pesantren</label>
							<input type="text" required="" name="worksheet_nip_kepsek" class="form-control" placeholder="Masukkan NIP Kepala Pesantren">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Bendahara</label>
							<input type="text" required="" name="worksheet_nama_bendahara" class="form-control" placeholder="Masukkan Nama Bendahara">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>NIP Bendahara</label>
							<input type="text" required="" name="worksheet_nip_bendahara" class="form-control" placeholder="Masukkan NIP Bendahara">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Komite</label>
							<input type="text" required="" name="worksheet_nama_komite" class="form-control" placeholder="Masukkan Nama Komite">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Email Komite</label>
							<input type="email" required="" name="worksheet_email_komite" class="form-control" placeholder="Masukkan Email Komite">
						</div>
					</div>
				</div>
				<div id="nominal">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Nominal Anggaran</label>
								<input type="text" required="" name="worksheet_nominal" id="worksheet_nominal" class="form-control" placeholder="Masukkan Nominal Anggaran" readonly="">
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="worksheet_status" value="B">
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
</div>

<script>
	function get_anggaran() {
		var period = $("#worksheet_period_id").val();
		var majors = $("#worksheet_majors_id").val();

		if (period != "" && majors != "") {
			$.ajax({
				type: "POST",
				url: "<?= base_url() . "manage/worksheet/nominal_anggaran" ?>",
				data: {
					period: period,
					majors: majors,
				},

				success: function(data) {
					console.log(data);
					$("#nominal").html(data);
				}
			});
		}
	}
</script>