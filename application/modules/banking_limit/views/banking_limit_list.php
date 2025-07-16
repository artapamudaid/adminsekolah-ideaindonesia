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
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#massLimit"><i class="fa fa-plus"></i> Set Per Kelas</button>
						<a href="<?php echo site_url('manage/banking_limit/set_limit') ?>" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Import Data</a>
					</div>
					<p>*) Note : Jika limit per siswa tidak di set maka limit yang digunakan adalah limit yang ada di menu Setting -> Sekolah -> Konfigurasi</p>
					<div class="box-body table-responsive">
						<table id="mydata" class="table table-hover">
							<thead>
								<tr>
									<th>NIS</th>
									<th>Nama</th>
									<th>Unit</th>
									<th>Kelas</th>
									<th>Limit</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="show_data">
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<form>
					<div class="modal fade" id="Modal_Set" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Set Limit Tarik Tabungan</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="form-group row">
										<label class="col-md-2 col-form-label">NIS</label>
										<div class="col-md-10">
											<input type="text" name="nis" id="nis" class="form-control" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 col-form-label">Limit Penarikan</label>
										<div class="col-md-10">
											<input type="number" name="limit" id="limit" class="form-control" placeholder="Masukkan Limit Penarikan">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="button" type="submit" id="btn_update" class="btn btn-primary">Update</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

	</section>
	<div class="modal fade" id="massLimit" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Pilih Kelas</h4>
				</div>
				<form action="<?php echo base_url(); ?>manage/banking_limit/set_data_batch" method="post">
					<div class="modal-body">
						<label>Unit Sekolah</label>
						<select required="" name="majors_id" id="majors_id" class="form-control">
							<option value="all">-Unit Sekolah-</option>
							<?php foreach ($majors as $row) { ?>
								<option value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
							<?php } ?>
						</select>
						<label>Kelas</label>
						<div id="div_class">
							<select required="" name="class_id" id="class_id" class="form-control">
								<option value="">-Pilih Kelas-</option>
							</select>
						</div>

						<label>Limit Tabungan</label>
						<input type="number" class="form-control" name="limit" id="limit" required="">
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-checked"></i> Set Limit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /.content -->
</div>

</div>
<script type="text/javascript">
	$(document).ready(function() {
		show_data();

		$('#mydata').dataTable();

		function show_data() {
			$.ajax({
				type: 'ajax',
				url: '<?php echo site_url('manage/banking_limit/get_data') ?>',
				async: false,
				dataType: 'json',
				success: function(data) {
					var html = '';
					var i;
					for (i = 0; i < data.length; i++) {
						if (data[i].withdraw_limit !== undefined && data[i].withdraw_limit !== null) {
							var limit = data[i].withdraw_limit;
						} else {
							var limit = 0;
						}
						html += '<tr>' +
							'<td>' + data[i].student_nis + '</td>' +
							'<td>' + data[i].student_full_name + '</td>' +
							'<td>' + data[i].majors_short_name + '</td>' +
							'<td>' + data[i].class_name + '</td>' +
							'<td>Rp ' + limit + '</td>' +
							'<td style="text-align:center;">' +
							'<a href="javascript:void(0);" class="btn btn-primary btn-sm set_limit" data-nis="' + data[i].student_nis + '" data-limit="' + data[i].withdraw_limit + '">Set Limit</a>' + ' ' +
							'</td>' +
							'</tr>';
					}
					$('#show_data').html(html);
				}
			});
		}

		$('#show_data').on('click', '.set_limit', function() {
			var nis = $(this).data('nis');
			var limit = $(this).data('limit');

			$('#Modal_Set').modal('show');
			$('[name="nis"]').val(nis);
			$('[name="limit"]').val(limit);
		});

		$('#btn_update').on('click', function() {
			var nis = $('#nis').val();
			var limit = $('#limit').val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('manage/banking_limit/set_data') ?>",
				dataType: "JSON",
				data: {
					nis: nis,
					limit: limit
				},
				success: function(data) {
					$('[name="nis"]').val("");
					$('[name="limit"]').val("");
					$('#Modal_Set').modal('hide');
					alert('Set Limit Berhasil');
					// show_data();
					location.reload();
				}
			});
			return false;
		});

		$("#majors_id").change(function(e) {
			var majors_id = $("#majors_id").val();
			//alert(id_jurusan+id_kelas);
			$.ajax({
				url: '<?php echo base_url(); ?>manage/banking_limit/get_class',
				type: 'POST',
				data: {
					'majors_id': majors_id,
				},
				success: function(msg) {
					$("#div_class").html(msg);
				},
				error: function(msg) {
					alert('msg');
				}

			});
			e.preventDefault();
		});

	});
</script>