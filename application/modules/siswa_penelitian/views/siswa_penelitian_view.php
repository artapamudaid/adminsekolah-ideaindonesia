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
								<div class="col-md-2">
									<?php if (!empty($siswa_penelitian['student_img'])) { ?>
									<img src="<?php echo upload_url('student/'.$siswa_penelitian['student_img']) ?>" class="img-responsive avatar">
									<?php } else { ?>
									<img src="<?php echo media_url('img/user.png') ?>" class="img-responsive avatar">
									<?php } ?>
								</div>
								<div class="col-md-10">
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>NIS Siswa</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['student_nis'] ?></td>
											</tr>
											<tr>
												<td>NISN Siswa</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['student_nisn'] ?></td>
											</tr>
											<tr>
												<td>Nama lengkap</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['student_full_name'] ?></td>
											</tr>
											<tr>
												<td>Jenis Kelamin</td>
												<td>:</td>
												<td><?php echo ($siswa_penelitian['student_gender']=='L')? 'Laki-laki' : 'Perempuan' ?></td>
											</tr>
											<tr>
												<td>Tempat, Tanggal Lahir</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['student_born_place'].', '. pretty_date($siswa_penelitian['student_born_date'],'d F Y',false) ?></td>
											</tr>
											<tr>
												<td>Kelas</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['class_name'] ?></td>
											</tr>
											<tr>
												<td>Anggota Siswa Ikut</td>
												<td>:</td>
												<td>
													<?php
														if($siswa_penelitian['anggota_id'] != ''){
															?>
															<ol>
																<?php  
																$pengikut 	= $siswa_penelitian['anggota_id'];
																$id 		= explode("," , $pengikut);
																$data 		= $this->db->query("SELECT student_full_name, student_nis FROM student WHERE student_id in ($pengikut)")->result_array();
																foreach ($data as $string) {
																	echo '<li>'.$string['student_full_name'].' ( '.$string['student_nis']. ')</li>';
																}
																?>
															</ol>
															<?php
														}
													?>	
												</td>
											</tr>
											<tr>
												<td>Nama Instansi</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['nama_instansi'] ?></td>
											</tr>
											<tr>
												<td>Alamat Instansi</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['alamat_instansi'] ?></td>
											</tr>
											<tr>
												<td>Catatan/ Keterangan Lain</td>
												<td>:</td>
												<td><?php echo $siswa_penelitian['catatan'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-4">
									<a href="<?php echo site_url('manage/siswa_penelitian') ?>" class="btn btn-default">
										<i class="fa fa-arrow-circle-o-left"></i> Kembali
									</a>
									<?php if ($this->session->userdata('uroleid') == SUPERUSER) { ?>
									<a href="<?php echo site_url('manage/siswa_penelitian/edit/' . $siswa_penelitian['student_id']) ?>" class="btn btn-success">
										<i class="fa fa-edit"></i> Edit
									</a>
									<a href="#delModal<?php echo $siswa_penelitian['student_id']; ?>" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i> Hapus</a>
									<?php } ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
				<!-- /.row -->
				<div class="modal modal-default fade" id="delModal<?php echo $siswa_penelitian['student_id']; ?>">
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
									<?php echo form_open('manage/student/delete/' . $siswa_penelitian['student_id']); ?>
									<input type="hidden" name="delName" value="<?php echo $siswa_penelitian['student_full_name']; ?>">
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