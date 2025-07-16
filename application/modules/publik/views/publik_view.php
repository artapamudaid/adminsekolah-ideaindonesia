<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="publik">
			<div class="col-md-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-12 col-sm-12 col-xs-12 pull-left">
							<br>
							<div class="publik">
								
								<div class="col-md-12">
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>No publik</td>
												<td>:</td>
												<td><?php echo $publik['publik_no'] ?></td>
											</tr>
											<tr>
												<td>Nama</td>
												<td>:</td>
												<td><?php echo $publik['publik_nama'] ?></td>
											</tr>
											<tr>
												<td>NIK</td>
												<td>:</td>
												<td><?php echo $publik['publik_nik'] ?></td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td>:</td>
												<td><?php echo $publik['publik_alamat'] ?></td>
											</tr>
											<tr>
												<td>Telepon/ WA</td>
												<td>:</td>
												<td><?php echo $publik['publik_telepon'] ?></td>
											</tr>
											<tr>
												<td>Bidang Yang dituju</td>
												<td>:</td>
												<td><?php echo $publik['position_name'] ?></td>
											</tr>
											<tr>
												<td>Detail/ Keperluan</td>
												<td>:</td>
												<td><?php echo $publik['keperluan'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-4">
									<a href="<?php echo site_url('manage/publik') ?>" class="btn btn-default">
										<i class="fa fa-arpublik-circle-o-left"></i> Kembali
									</a>
									<?php if ($this->session->userdata('uroleid') == SUPERUSER) { ?>
									<a href="<?php echo site_url('manage/publik/edit/' . $publik['publik_id']) ?>" class="btn btn-success">
										<i class="fa fa-edit"></i> Edit
									</a>
									<!-- <a href="#delModal<?php echo $publik['publik_id']; ?>" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i> Hapus</a> -->
									<?php } ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
				<!-- /.publik -->
				<div class="modal modal-default fade" id="delModal<?php echo $publik['publik_id']; ?>">
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
									<?php echo form_open('manage/student/delete/' . $publik['publik_id']); ?>
									<input type="hidden" name="delName" value="<?php echo $publik['student_full_name']; ?>">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
									<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
									<?php echo form_close(); ?>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>

				</section>
			</div>