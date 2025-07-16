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
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addGallery"><i class="fa fa-plus"></i> Tambah</button>
						<a href="<?= base_url() . 'manage/homestay' ?>" type="button" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
					</div>
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Foto</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($gallery)) {
									$i = 1;
									foreach ($gallery as $row) :
								?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><img src="<?= base_url() . 'uploads/gallery/' . $row['gallery_img']; ?>" alt="" width="50px"></td>
											<td>
												<a href="#delModal<?php echo $row['gallery_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['gallery_id']; ?>">
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
														<?php echo form_open('manage/homestay/delete_gallery/' . $row['gallery_id']); ?>
														<input type="hidden" name="homestay_id" value="<?php echo $row['gallery_homestay_id']; ?>">
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
										<td colspan="5" align="center">Data Kosong</td>
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

	<div class="modal fade" id="addGallery" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Tambah Gambar/Foto</h4>
				</div>
				<?php echo form_open_multipart('manage/homestay/upload_gallery', array('method' => 'post')); ?>
				<div class="modal-body">
					<div id="p_scents_pos">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Gambar/Foto</label>
									<input type="file" id="gallery_img" name="gallery_img[]" class="form-control" multiple="multiple" required="required">
								</div>
							</div>
							<input type="hidden" name="homestay_id" value="<?= $homestay_id ?>">
						</div>

						<!--<button class="btn btn-xs btn-success" id="addScnt_pos"><i class="fa fa-plus"> Tambah Baris</i></button>-->
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<!-- /.content -->
</div>