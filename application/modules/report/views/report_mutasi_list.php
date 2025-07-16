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
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header"> <br>
						<div class="row">
							<div class="col-md-2">  
								<div class="form-group">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="ds" id="ds" readonly="readonly" placeholder="Tanggal Awal">
									</div>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="de" id="de" readonly="readonly" placeholder="Tanggal Akhir">	
									</div>
								</div>
							</div>
							<div class="col-md-3">  
								<div class="form-group">
        						<select required="" name="majors_id" id="majors_id" class="form-control">
        						    <option value="">-- Pilih Unit Sekolah --</option>
        						    <?php if($this->session->userdata('umajorsid') == '0') { ?>
        						    	<option value="all">Semua Unit</option>
        						    <?php } ?>
        						    <?php foreach($majors as $row){?>
        						        <option value="<?php echo $row['majors_id']; ?>" ><?php echo $row['majors_short_name'] ?></option>
        						    <?php } ?>
        						</select>
								</div>
							</div>
							<div class="col-md-3">
								<div id="div_kas">
									<select class="form-control multiple-select" data-select2-id="kas_account_id" name="kas_account_id[]" multiple="multiple" required>
										<option value="">- Pilih Akun Kas -</option>
									</select>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" onclick="cari_data()">Filter</button>
						</div>
					<div id="div_show_data">
					</div>
				</div>
			</div>

		</div>
	</section>
	<!-- /.content -->
</div>

<script>
    function cari_data() {
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors_id = $("#majors_id").val();
		var kas_account_id = $("#kas_account_id").val();

		if (ds != '' && de != '' && kas_account_id != '' && majors_id != '') {
			$.ajax({
				url: '<?php echo base_url();?>manage/report/search_report_mutasi',
				type: 'POST',
				data: {
					'ds': ds,
					'de': de,
					'majors_id': majors_id,
					'kas_account_id': kas_account_id,
				},
				success: function (msg) {
					$("#div_show_data").html(msg);
				},
				error: function (msg) {
					alert('msg');
				}
			});
		}
	}

	$("#majors_id").change(function (e) {
		var id_majors = $("#majors_id").val();

		$.ajax({
			url: '<?php echo base_url();?>manage/report/cari_kas',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function (msg) {
				$("#div_kas").html(msg);
				$("#div_kas .multiple-select").attr("data-select2-id", "kas_account_id");
				$('.multiple-select').select2();
				tampil_data();
				$("#btnFinish").show();
			},
			error: function (msg) {
				alert('msg');
			}

		});
		e.preventDefault();
	});

	$(document).ready(function () {
		$('.multiple-select').select2();

		// Hide or show based on 'Semua Unit' selection
		$("#majors_id").change(function () {
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

		// Filter button click event
		$("#filterButton").on('click', function () {
			cari_data();
		});
	});

</script>
