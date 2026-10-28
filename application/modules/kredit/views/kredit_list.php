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
										<button id="searchKredit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
										<a href="<?php echo site_url('manage/kredit/trx') ?>"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Kredit</button></a>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtableKredit" class="table table-hover">
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
				<!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<div class="modal modal-default fade" id="modalDellKredit">
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
				<?php echo form_open('manage/kredit/delete/'); ?>
				<input type="hidden" name="delKredit" id="delKredit">
				<input type="hidden" name="delNoref" id="delNoref">
				<input type="hidden" name="delGaji" id="delGaji">
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
		var param = <?php echo json_encode($param); ?>;
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors = $("#m").val();

		$('#dtableKredit').DataTable({
			"serverSide": true,
			"processing": true,
			"destroy": true,
			"ajax": {
				"url": "<?php echo base_url() ?>manage/kredit/list_kredit",
				"type": "POST",
				"data": {
					param: param,
					ds: ds,
					de: de,
					majors: majors
				}
			}
		});

		$('#dtableKredit').on('click', '.btnDellKredit', function(e) {
			e.preventDefault();

			// Ambil data dari tombol yang diklik
			const kreditId = $(this).data('kredit');
			const noref = $(this).data('noref');
			const gajiId = $(this).data('gaji');

			$("#delKredit").val(kreditId);
			$("#delNoref").val(noref);
			$("#delGaji").val(gajiId);

			$("#modalDellKredit").modal('show');
		});
	});

	$("#searchKredit").click(function() {
		var param = <?php echo json_encode($param); ?>;
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors = $("#m").val();

		$('#dtableKredit').DataTable({
			"serverSide": true,
			"processing": true,
			"destroy": true,
			"ajax": {
				"url": "<?php echo base_url() ?>manage/kredit/list_kredit",
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
			url: '<?php echo base_url(); ?>manage/kredit/cari_kode',
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