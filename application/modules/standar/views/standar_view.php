<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>Detail</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo $title . ' ' . $judul; ?></h3>
				</div><!-- /.box-header -->
				<div class="box-body">


					<div class="nav-tabs-custom">

						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPendapatan"><i class="fa fa-plus"></i> Tambah Kegiatan</button>
						<a class="btn btn-default btn-sm" href="<?php echo site_url('manage/standar') ?>"><span class="glyphicon glyphicon-repeat"></span> Kembali</a>
						<div class="box-body table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>No</th>
										<th>Kode Kegiatan</th>
										<th>Akun Beban</th>
										<th>Nama Kegiatan</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									foreach ($list as $row) :
									?>
										<tr>
											<td><?php echo $no++ ?></td>
											<td><?php echo $row['aktivitas_kode'] ?></td>
											<td><?php echo $row['account_code'] . ' - ' . $row['account_description'] ?></td>
											<td><?php echo $row['aktivitas_name'] ?></td>
											<td>
												<a href="#delModalKegiatan<?php echo $row['aktivitas_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>
										</tr>

										<div class="modal modal-default fade" id="delModalKegiatan<?php echo $row['aktivitas_id']; ?>">
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
														<?php echo form_open('manage/standar/delete_item/' . $row['aktivitas_id']); ?>
														<input type="hidden" name="standar_id" value="<?php echo $this->uri->segment('4') ?>">
														<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
														<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
														<?php echo form_close(); ?>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!-- /.box-body -->
		</div>
</div>


<!-- Modal -->
<div class="modal fade" id="addPendapatan" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Kegiatan</h4>
			</div>
			<?php echo form_open('manage/standar/add_glob_item', array('method' => 'post')); ?>
			<div class="modal-body">
				<input type="hidden" name="standar_id" value="<?php echo $this->uri->segment('4') ?>">
				<div id="p_scents_akun_item">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Kode Kegiatan</label>
								<input type="text" required="" name="kode[]" class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Akun Beban</label>
								<select required="" name="aktivitas_account_id[]" class="form-control">
									<option value="">-- Pilih Akun --</option>
									<?php
									foreach ($account as $val) {
									?>
										<option value="<?= $val->account_id ?>"><?= $val->account_code . ' - ' . $val->account_description ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Nama Kegiatan</label>
								<input type="text" required="" name="name[]" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<h6><a href="#" class="btn btn-xs btn-success" id="addScnt_akun_item"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

</section>
</div>

<script>
	$(function() {
		var scntDiv = $('#p_scents_akun_item');
		var i = $('#p_scents_akun_item .row').size() + 1;

		$("#addScnt_akun_item").click(function() {
			$('<div class="row"><div class="col-md-12"><div class="form-group"><label>Kode Kegiatan</label><input type="text" required="" name="kode[]" class="form-control"></div></div><div class="col-md-12"><div class="form-group"><label>Akun Beban</label><select required="" name="aktivitas_account_id[]" class="form-control"><option value="">-- Pilih Akun --</option><?php foreach ($account as $val) { ?> <option value="<?= $val->account_id ?>"><?= $val->account_code . ' - ' . $val->account_description ?></option><?php } ?></select></div></div><div class="col-md-12"><div class="form-group"><label>Nama Kegiatan</label><input type="text" required="" name="name[]" class="form-control"><a href="#" class="btn btn-xs btn-danger remScnt_akun_item">Hapus Baris</a></div></div></div>').appendTo(scntDiv);
			i++;
			return false;
		});

		$(document).on("click", ".remScnt_akun_item", function() {
			if (i > 2) {
				$(this).parents('.row').remove();
				i--;
			}
			return false;
		});
	});
</script>