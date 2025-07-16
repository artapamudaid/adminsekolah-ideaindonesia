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
		<div class="survei">
			<div class="col-md-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-12 col-sm-12 col-xs-12 pull-left">
							<br>
							<div class="survei">
								
								<div class="col-md-12">
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>Nama Pensurvei</td>
												<td>:</td>
												<td><?php echo $survei['survei_nama'] ?></td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td>:</td>
												<td><?php echo $survei['survei_alamat'] ?></td>
											</tr>
											<tr>
												<td>Respon</td>
												<td>:</td>
												<td><?php 
													if($survei['survei_respon'] == 3){
														echo 'Sangat Puas';
													}elseif($survei['survei_respon'] == 2){
														echo 'Puas';
													}else{
														echo'Tidak Puas';
													} ?>
												</td>
											</tr>
											<?php 
											if($survei['survei_respon'] == 1){?>
											<tr>
												<td>Kritik Dan Saran</td>
												<td>:</td>
												<td><?php echo $survei['survei_catatan'] ?></td>
											</tr>
											<?php
											}?>
										</tbody>
									</table>
								</div>
								<div class="col-md-4">
									<a href="<?php echo site_url('manage/survei') ?>" class="btn btn-default">
										<i class="fa fa-arsurvei-circle-o-left"></i> Kembali
									</a>
									<?php if ($this->session->userdata('uroleid') == SUPERUSER) { ?>
									<a href="<?php echo site_url('manage/survei/edit/' . $survei['survei_id']) ?>" class="btn btn-success">
										<i class="fa fa-edit"></i> Edit
									</a>
									<!-- <a href="#delModal<?php echo $survei['survei_id']; ?>" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i> Hapus</a> -->
									<?php } ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
				<!-- /.survei -->
				<div class="modal modal-default fade" id="delModal<?php echo $survei['survei_id']; ?>">
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
									<?php echo form_open('manage/student/delete/' . $survei['survei_id']); ?>
									<input type="hidden" name="delName" value="<?php echo $survei['student_full_name']; ?>">
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