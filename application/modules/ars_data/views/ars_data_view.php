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
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-12 col-sm-12 col-xs-12 pull-left">
							<br>
							<div class="row">
								
								<div class="col-md-12">
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>Status Arsip</td>
												<td>:</td>
												<td><?php echo ($arsip['status']=='1')? 'Terverifikasi' : 'Belum Terverifikasi' ?></td>
											</tr>
											<tr>
												<td>Jenis Arsip</td>
												<td>:</td>
												<td><?php echo $arsip['jenis_arsip'] ?></td>
											</tr>
											<tr>
												<td>Nama Arsip</td>
												<td>:</td>
												<td><?php echo $arsip['nama_arsip'] ?></td>
											</tr>
											<tr>
												<td>Jumlah Arsip</td>
												<td>:</td>
												<td><?php echo  $arsip['jumlah'].$arsip['nama_satuan'].'( '.$arsip['keterangan'].' )'   ?> </td>
											</tr>
											<tr>
												<td>Lokasi Arsip</td>
												<td>:</td>
												<td><?php echo $arsip['nama_lokasi'] ?></td>
											</tr>
											<tr>
												
												<?php if ($arsip['status'] == 1 ) { ?>
												<td colspan="3">
													<?php if (isset($arsip['file_arsip']) != NULL) { 
														$p=  $arsip['file_arsip']; 
														$explode = explode(".", $p);
														$ext = $explode[1];
														if ($ext=='jpg'){
															?><img src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>" class="img-responsive avatar"><?php
														}else if($ext=='png'){
															?><img src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>" class="img-responsive avatar"><?php
														}else{
															?><iframe src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>" width="100%" height="400px"></iframe>
															<a src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>"></a><?php
														}?>
													<?php } else { ?>
													<img id="target" alt="Choose image to upload">
													<?php } ?>
														
												</td>
												<?php } else {?>
												<td>File </td>
												<td>:</td>
												<td>File Belum Terverifikasi</td>
												<?php } ?>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-4">
									<a href="<?php echo site_url('manage/ars_data') ?>" class="btn btn-default">
										<i class="fa fa-arrow-circle-o-left"></i> Kembali
									</a>
									<?php if ($this->session->userdata('uroleid') == SUPERUSER) { ?>
									<a href="<?php echo site_url('manage/ars_data/edit/' . $arsip['id_arsip']) ?>" class="btn btn-success">
										<i class="fa fa-edit"></i> Edit
									</a>
									<a href="#delModal<?php echo $arsip['id_arsip']; ?>" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i> Hapus</a>
									<?php } ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
				<!-- /.row -->
				<div class="modal modal-default fade" id="delModal<?php echo $arsip['id_arsip']; ?>">
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
									<?php echo form_open('manage/ars_data/delete/' . $arsip['id_arsip']); ?>
									<input type="hidden" name="delName" value="<?php echo $row['nama_arsip']; ?>">
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