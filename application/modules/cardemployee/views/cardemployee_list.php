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
						<a href="<?php echo site_url('manage/cardemployee/set_rfid') ?>" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Import RFID</a>
					</div>
					<div class="box-body table-responsive">
						<table id="mydata" class="table table-hover">
							<thead>
								<tr>
									<th>NIP</th>
									<th>Nama</th>
									<th>Unit</th>
									<th>Jabatan</th>
									<th>RFID</th>
									<th>PIN</th>
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
									<h5 class="modal-title" id="exampleModalLabel">Set Pin</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="form-group row">
										<label class="col-md-2 col-form-label">NIP</label>
										<div class="col-md-10">
											<input type="text" name="nip" id="nip" class="form-control" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 col-form-label">RIFD</label>
										<div class="col-md-10">
											<input type="text" name="rfid" id="rfid" class="form-control" placeholder="Masukkan RIFD" maxlength="10">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 col-form-label">PIN</label>
										<div class="col-md-10">
											<input type="password" name="pin" id="pin" class="form-control" placeholder="PIN" maxlength="6">
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
				url: '<?php echo site_url('manage/cardemployee/get_data') ?>',
				async: false,
				dataType: 'json',
				success: function(data) {
					var html = '';
					var i;
					for (i = 0; i < data.length; i++) {
						if (data[i].rfid !== undefined && data[i].rfid !== null) {
							var rfid = data[i].rfid;
						} else {
							var rfid = '-';
						}
						if (data[i].pin !== undefined && data[i].pin !== null) {
							var pin = 'Sudah Di-SET';
						} else {
							var pin = 'Belum Di-SET';
						}
						html += '<tr>' +
							'<td>' + data[i].employee_nip + '</td>' +
							'<td>' + data[i].employee_name + '</td>' +
							'<td>' + data[i].majors_short_name + '</td>' +
							'<td>' + data[i].position_name + '</td>' +
							'<td>' + rfid + '</td>' +
							'<td>' + pin + '</td>' +
							'<td style="text-align:center;">' +
							'<a href="javascript:void(0);" class="btn btn-info btn-sm set_pin" data-nip="' + data[i].employee_nip + '" data-rfid="' + data[i].rfid + '">Set RFID</a>' + ' ' +
							'</td>' +
							'</tr>';
					}
					$('#show_data').html(html);
				}
			});
		}

		$('#show_data').on('click', '.set_pin', function() {
			var nip = $(this).data('nip');
			var rfid = $(this).data('rfid');
			// var pin = $(this).data('pin');

			$('#Modal_Set').modal('show');
			$('[name="nip"]').val(nip);
			$('[name="rfid"]').val(rfid);
			// $('[name="pin"]').val(pin);
		});

		$('#btn_update').on('click', function() {
			var nip = $('#nip').val();
			var rfid = $('#rfid').val();
			var pin = $('#pin').val();

			if (!nip) {
				toastNotifikasi("error", "NIP harus diisi", "Error");
				return;
			}

			if (!rfid || rfid.length != 10) {
				toastNotifikasi("error", "RFID harus 10 karakter angka.", "Error");
				return;
			}

			if (!pin || pin.length != 6) {
				toastNotifikasi("error", "PIN harus 6 karakter angka.", "Error");
				return;
			}
			let payload = {
				nip,
				rfid,
				pin
			};

			$.ajax({
				type: "POST",
				url: "<?php echo site_url('manage/cardemployee/set_data') ?>",
				dataType: "JSON",
				data: payload,
				success: function(response) {
					toastNotifikasi(
						(response.status == false ? "error" : "success"),
						response.message,
						(response.status == false ? "Error" : "Success")
					);

					if (response && response.status == true) {
						$('[name="nip"]').val("");
						$('[name="rfid"]').val("");
						$('[name="pin"]').val("");
						$('#Modal_Set').modal('hide');
						show_data();
					}
				}
			});
			// return false;
		});

	});
</script>