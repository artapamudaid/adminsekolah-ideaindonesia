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
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<!-- <a href="<?php echo site_url('manage/debit/trx') ?>"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</button></a> -->
						<br>
						<br>
						<div class="box-body table-responsive">
							<table>
								<tr>
									<td>
										<div class="form-group">
											<label>Unit Sekolah</label>
											<select id="m" name="m" style="width: 200px;" class="form-control search-input-text" data-column="1">
												<?php if ($this->session->userdata('umajorsid') == '0') { ?>
													<option value="" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
												<?php } ?>
												<?php foreach ($majors as $row) { ?>
													<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
												<?php } ?>
											</select>
										</div>

									</td>
									<td>
										&nbsp;&nbsp;
									</td>
									<td>
										<div class="form-group">
											<label>Mulai Tanggal</label>
											<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="ds" id="ds" value="<?php echo (isset($q['de'])) ? $q['de'] : date('Y-m-01')  ?>" readonly="readonly" placeholder="Tanggal Awal">
											</div>
										</div>
									</td>
									<td>
										&nbsp;&nbsp;
									</td>
									<td>
										<div class="form-group">
											<label>Tanggal Akhir</label>
											<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="de" id="de" value="<?php echo (isset($q['de'])) ? $q['de'] : date('Y-m-t')  ?>" readonly="readonly" placeholder="Tanggal Akhir">
											</div>
										</div>
									</td>
									<td>
										&nbsp;&nbsp;
									</td>
									<td style="padding-top: 10px;">
										<button id="searchDebit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
										<a href="<?php echo site_url('manage/debit/trx') ?>"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Debit</button></a>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtableDebit" class="table table-hover">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>No. Ref</th>
									<th>Kas</th>
									<th>Kode Akun</th>
									<th>Keterangan</th>
									<th>Nominal (Rp.)</th>
									<th>Pajak</th>
									<th>Unit POS</th>
									<th>Total (Rp.)</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>

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
<div class="modal fade" id="adddebit" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Kas Masuk</h4>
			</div>
			<?php echo form_open('manage/debit/add_glob', array('method' => 'post')); ?>
			<div class="modal-body">
				<div id="p_scents_debit">
					<div class="form-group">
						<label>Tanggal</label>
						<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input class="form-control" required="" type="text" name="debit_date" placeholder="Tanggal Kas Masuk">
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Unit Sekolah *</label>
							<select required="" name="majors_id" id="majors_id" class="form-control" onchange="get_kode()">
								<option value="">-Pilih Unit Sekolah-</option>
								<?php foreach ($majors as $row) { ?>
									<option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-3">
							<div id="div_kode">
								<label>Kode Akun *</label>
								<select required="" name="debit_account_id[]" class="form-control">
									<option value="">-Pilih Kode Akun-</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<label>Jumlah Rupiah *</label>
							<input type="text" class="form-control" id="debit" required="" name="debit_value[]" class="form-control" placeholder="Jumlah">
						</div>
						<div class="col-md-3">
							<label>Keterangan *</label>
							<input type="text" required="" name="debit_desc[]" class="form-control" placeholder="Keterangan Kas Masuk">
						</div>
					</div>
				</div>
				<!--<h6 ><a href="#" class="btn btn-xs btn-success" id="addScnt_debit"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>-->
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

<div class="modal modal-default fade" id="modalDellDebit">
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
				<?php echo form_open('manage/debit/delete/'); ?>
				<input type="hidden" name="delDebit" id="delDebit">
				<input type="hidden" name="delNoref" id="delNoref">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
				<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
				<?php echo form_close(); ?>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<script>
	$(document).ready(function() {
		var param = "<?php echo $param; ?>";
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors = $("#m").val();

		$('#dtableDebit').DataTable({
			"serverSide": true,
			"processing": true,
			"destroy": true,
			"ajax": {
				"url": "<?php echo base_url() ?>manage/debit/list_debit",
				"type": "POST",
				"data": {
					param: param,
					ds: ds,
					de: de,
					majors: majors
				}
			}
		});

		$('#dtableDebit').on('click', '.btnDellDebit', function(e) {
			e.preventDefault();

			// Ambil data dari tombol yang diklik
			const debitId = $(this).data('debit');
			const noref = $(this).data('noref');

			$("#delDebit").val(debitId);
			$("#delNoref").val(noref);

			$("#modalDellDebit").modal('show');
		});
	});

	$("#searchDebit").click(function() {
		var param = "<?php echo $param; ?>";
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors = $("#m").val();

		$('#dtableDebit').DataTable({
			"serverSide": true,
			"processing": true,
			"destroy": true,
			"ajax": {
				"url": "<?php echo base_url() ?>manage/debit/list_debit",
				"type": "POST",
				"data": {
					param: param,
					ds: ds,
					de: de,
					majors: majors
				}
			}
		});
	});

	function get_kode() {

		var id_majors = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/debit/cari_kode',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#div_kode").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>
<?php $this->load->view('manage/rupiah'); ?>