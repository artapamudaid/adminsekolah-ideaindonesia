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
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addKitab"><i class="fa fa-plus"></i> Tambah</button>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Kitab</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($kitab)) {
									$i = 1;
									foreach ($kitab as $row) :
								?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['kitab_name']; ?></td>
											<td>
												<a href="<?php echo site_url('manage/kitab/edit/' . $row['kitab_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>

												<a href="#delModal<?php echo $row['kitab_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['kitab_id']; ?>">
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
														<?php echo form_open('manage/kitab/delete/' . $row['kitab_id']); ?>
														<input type="hidden" name="delName" value="<?php echo $row['kitab_name']; ?>">
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
										<td colspan="3" align="center">Data Kosong</td>
									</tr>
								<?php } ?>
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

<div class="modal fade" id="addKitab" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Kitab</h4>
			</div>
			<?php echo form_open('manage/kitab/add_glob', array('method' => 'post')); ?>
			<div class="modal-body">
				<div id="p_scents_kitab">
					<p>
						<label>Nama Kitab</label>
						<input type="text" required="" name="kitab_name[]" class="form-control" placeholder="Contoh: Alfiyah">
					</p>
				</div>
				<h6><a href="#" class="btn btn-xs btn-success" id="addScnt_kitab"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>


<script>
	$(function() {
		var scntDiv = $('#p_scents_kitab');
		var i = $('#p_scents_kitab p').size() + 1;

		$("#addScnt_kitab").click(function() {
			$('<p><label>Nama Kitab</label><input required type="text" name="kitab_name[]" class="form-control" placeholder="Contoh: Alfiyah"><a href="#" class="btn btn-xs btn-danger remScnt_kitab">Hapus Baris</a></p>').appendTo(scntDiv);
			i++;
			return false;
		});

		$(document).on("click", ".remScnt_kitab", function() {
			if (i > 2) {
				$(this).parents('p').remove();
				i--;
			}
			return false;
		});
	});
</script>