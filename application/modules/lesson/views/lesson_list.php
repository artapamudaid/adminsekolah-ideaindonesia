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

					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addLesson"><i class="fa fa-plus"></i> Tambah</button>
						<a href="<?php echo site_url('manage/lesson/import_lesson') ?>" class="btn btn-sm btn-info"><i class="fa fa-upload"></i> Import Pelajaran</a>
						<br>
						<br>
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>ID Mapel</th>
									<th>Kode Mapel</th>
									<th>Nama Mapel</th>
									<th>Pengajar</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($lessons)) {
									$i = 1;
									foreach ($lessons as $row) :
								?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['lesson_id']; ?></td>
											<td><?php echo $row['lesson_code']; ?></td>
											<td><?php echo $row['lesson_name']; ?></td>
											<td><?php echo $row['employee_name']; ?></td>
											<td>
												<a href="<?php echo site_url('manage/lesson/edit/' . $row['lesson_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>

												<a href="#delModal<?php echo $row['lesson_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['lesson_id']; ?>">
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
														<?php echo form_open('manage/lesson/delete/' . $row['lesson_id']); ?>
														<input type="hidden" name="delName" value="<?php echo $row['lesson_name']; ?>">
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
										<td colspan="4" align="center">Data Kosong</td>
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

<!-- Modal -->
<div class="modal fade" id="addLesson" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Mata Pelajaran</h4>
			</div>
			<?php echo form_open('manage/lesson/add_glob', array('method' => 'post')); ?>
			<div class="modal-body">
				<div id="p_scents_lesson">
					<p>
						<label>Kode Mapel</label>
						<input type="text" required="" name="lesson_code[]" class="form-control" placeholder="Contoh: MTK">
						<label>Nama Mapel</label>
						<input type="text" required="" name="lesson_name[]" class="form-control" placeholder="Contoh: Matematika">
						<label>Pengajar</label>
						<select id="lesson_teacher" name="lesson_teacher[]" class="form-control" required>
							<option value="">--- Pilih Pengajar ---</option>
							<?php foreach ($employee as $row) { ?>
								<option value="<?php echo $row['employee_id']; ?>"><?php echo str_replace("'", "", $row['employee_name']) . ' - ' . $row['position_name']; ?></option>
							<?php } ?>
						</select>
					</p>
				</div>
				<h6><a href="#" class="btn btn-xs btn-success" id="addScnt_lesson"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
</div>

<script>
	$(function() {
		var scntDiv = $('#p_scents_lesson');
		var i = $('#p_scents_lesson p').size() + 1;

		$("#addScnt_lesson").click(function() {
			$('<p><label>Kode Mapel</label><input type="text" required="" name="lesson_code[]" class="form-control" placeholder="Contoh: MTK"><label>Nama Mapel</label><input type="text" required="" name="lesson_name[]" class="form-control" placeholder="Contoh: Matematika"><label>Pengajar</label><select id="lesson_teacher" name="lesson_teacher[]" class="form-control" required><option value="">--- Pilih Pengajar ---</option><?php foreach ($employee as $row) { ?><option value="<?php echo $row['employee_id']; ?>"><?php echo str_replace("'", "", $row['employee_name']) . ' - ' . $row['position_name']; ?></option><?php } ?></select><a href="#" class="btn btn-xs btn-danger remScnt_lesson">Hapus Baris</a></p>').appendTo(scntDiv);
			i++;
			return false;
		});

		$(document).on("click", ".remScnt_lesson", function() {
			if (i > 2) {
				$(this).parents('p').remove();
				i--;
			}
			return false;
		});
	});
</script>