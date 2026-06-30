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
						<?php if ($this->session->userdata('uroleid') == '1' or $this->session->userdata('uroleid') == '3') { ?>
							<a href="<?php echo site_url('manage/users/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<?php } ?>
						<?php
						if ($this->session->userdata('uroleid') == '1' or $this->session->userdata('uroleid') == '3') {
						?>
							<a href="<?php echo site_url('manage/users/role') ?>" class="btn btn-sm btn-danger"><i class="fa fa-cogs"></i> Role</a>
						<?php
						}
						?>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive no-padding">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Email</th>
									<th>Nama</th>
									<th>Hak Akses</th>
									<th>Unit Sekolah</th>
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
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<div class="modal modal-default fade" id="delModalUser">
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
				<?php echo form_open('manage/users/delete', ['method' => 'post']); ?>
				<input type="hidden" id="delUser" name="delUser" value="">
				<input type="hidden" id="delName" name="delName" value="">
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
		var param = <?= json_encode($param); ?>;
		$("#dtable").DataTable({
			ordering: false,
			processing: false,
			serverSide: true,

			ajax: {
				url: "<?php echo base_url('manage/users/datatablesUsers'); ?>",
				type: 'POST',
				data: {
					param: param
				}
			},
		});
	});

	$(document).on('click', '.btnHapusUser', function() {
		var userId = $(this).data('user'); // ambil data-user
		var userName = $(this).data('nama'); // ambil data-user
		$('#delUser').val(userId); // set ke input hidden dalam modal
		$('#delName').val(userName); // set ke input hidden dalam modal
		$('#delModalUser').modal('show'); // tampilkan modal
	});
</script>