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
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="nav-tabs-custom">
							<!-- TAB UTAMA -->
							<ul class="nav nav-tabs">
								<li class="<?php echo $f['m'] == 0 ? 'active' : '' ?> tab-utama"> <a href="#detailSiswa" data-toggle="tab">Info Siswa</a></li>
								<li class="<?php echo $f['m'] == 1 ? 'active' : '' ?> tab-utama"><a href="#konseling" data-toggle="tab">Konseling</a></li>
							</ul>

							<div class="tab-content">
								<!-- detailSiswa -->
								<div class="active tab-pane" id="detailSiswa">
									<div class="col-md-12 col-sm-12 col-xs-12 pull-left">
										<br>
										<div class="row">
											<div class="col-md-2">
												<?php if (!empty($student['student_img'])) { ?>
													<img src="<?php echo upload_url('student/' . $student['student_img']) ?>" class="img-responsive avatar">
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
															<td><?php echo $student['student_nis'] ?></td>
														</tr>
														<tr>
															<td>NISN Siswa</td>
															<td>:</td>
															<td><?php echo $student['student_nisn'] ?></td>
														</tr>
														<tr>
															<td>Nama lengkap</td>
															<td>:</td>
															<td><?php echo $student['student_full_name'] ?></td>
														</tr>
														<tr>
															<td>Jenis Kelamin</td>
															<td>:</td>
															<td><?php echo ($student['student_gender'] == 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
														</tr>
														<tr>
															<td>Tempat, Tanggal Lahir</td>
															<td>:</td>
															<td><?php echo $student['student_born_place'] . ', ' . pretty_date($student['student_born_date'], 'd F Y', false) ?></td>
														</tr>
														<tr>
															<td>Hobi</td>
															<td>:</td>
															<td><?php echo $student['student_hobby'] ?></td>
														</tr>
														<tr>
															<td>No. Handphone</td>
															<td>:</td>
															<td><?php echo $student['student_phone'] ?></td>
														</tr>
														<tr>
															<td>Alamat</td>
															<td>:</td>
															<td><?php echo $student['student_address'] ?></td>
														</tr>
														<tr>
															<td>Nama Ibu Kandung</td>
															<td>:</td>
															<td><?php echo $student['student_name_of_mother'] ?></td>
														</tr>
														<tr>
															<td>Nama Ayah Kandung</td>
															<td>:</td>
															<td><?php echo $student['student_name_of_father'] ?></td>
														</tr>
														<tr>
															<td>No. Handphone Orang Tua</td>
															<td>:</td>
															<td><?php echo $student['student_parent_phone'] ?></td>
														</tr>
														<tr>
															<td>Unit Sekolah</td>
															<td>:</td>
															<td><?php echo $student['majors_short_name'] . ' (' . $student['majors_name'] . ')' ?></td>
														</tr>
														<tr>
															<td>Kelas</td>
															<td>:</td>
															<td><?php echo $student['class_name'] ?></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="col-md-4">
												<a href="<?php echo site_url('manage/student') ?>" class="btn btn-default">
													<i class="fa fa-arrow-circle-o-left"></i> Kembali
												</a>
												<?php if ($this->session->userdata('uroleid') == SUPERUSER) { ?>
													<a href="<?php echo site_url('manage/student/edit/' . $student['student_id']) ?>" class="btn btn-success">
														<i class="fa fa-edit"></i> Edit
													</a>
													<a href="#delModal<?php echo $student['student_id']; ?>" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i> Hapus</a>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<!-- Detail Siswa -->

								<!-- Konseling -->
								<div class="tab-pane" id="konseling">
									<div class="nav-tabs-custom">
										<ul class="nav nav-tabs">
											<li class="tab-pelanggaran active"><a href="#daftarPelanggaran" data-toggle="tab">Daftar Pelanggaran</a></li>
											<li class="tab-pelanggaran"><a href="#daftarKonsultasi" data-toggle="tab">Daftar Konsultasi</a></li>
											<li class="tab-pelanggaran"><a href="#daftarPrestasi" data-toggle="tab">Daftar Prestasi</a></li>
										</ul>

										<div class="tab-content">

											<div class="tab-pane active" id="daftarPelanggaran">
												<table id="dtablePelanggaran" class="table table-bordered" style="white-space: nowrap;width:100%">
													<thead>
														<tr class="info">
															<th style="width: 5%;">No</th>
															<th style="width: 10%;">Tanggal</th>
															<th style="width: 30%;">Pelanggaran</th>
															<th style="width: 45%;">Tindakan</th>
															<th style="width: 5%;">Poin</th>
															<th style="width: 30%;">Catatan</th>
															<th style="width: 5%;">Bukti</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 1;
														$poinPelanggaran = 0;
														foreach ($konseling as $row):
														?>
															<tr>
																<td style="width: 5%;"><?php echo $i ?></td>
																<td style="width: 10%;"><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
																<td style="width: 30%;white-space: normal;"><?php echo $row['konseling_foul'] ?? $row['pelanggaran'] ?></td>
																<td style="width: 15%;"><?php echo $row['konseling_action'] ?></td>
																<td style="width: 5%; text-align:center;">
																	<?php
																	echo $row['konseling_poin'];
																	$poinPelanggaran += $row['konseling_poin'];
																	?>
																</td>
																<td style="width: 30%;white-space: normal;"><?php echo $row['konseling_note'] ?></td>
																<td style="width: 5%;">
																	<?php if (!empty($row['bukti'])) { ?>
																		<a target="_blank" href="<?php echo base_url('uploads/konseling/') . $row['bukti'] ?>" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" title="Bukti Pelanggaran"></i></a>
																	<?php } ?>
																</td>
															</tr>
														<?php
															$i++;
														endforeach;
														?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="4" style="font-weight: bold;">Total Poin</td>
															<td style="font-weight: bold; text-align:center;"> <?php echo $poinPelanggaran; ?> </td>
															<td></td>
															<td></td>
														</tr>
													</tfoot>
												</table>
											</div>

											<div class="tab-pane" id="daftarKonsultasi">
												<table id="dtableKonsultasi" class="table table-bordered" style="white-space: nowrap;">
													<thead>
														<tr class="info">
															<th>No.</th>
															<th>Tanggal</th>
															<th>Problem/Masalah</th>
															<th>Problem/Masalah Yang di Konsultasikan</th>
															<th>Solusi</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 1;
														foreach ($konsultasi as $row):
														?>
															<tr>
																<td><?php echo $i ?></td>
																<td><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
																<td><?php echo $row['konseling_foul'] ?></td>
																<td><?php echo $row['konseling_action'] ?></td>
																<td><?php echo $row['konseling_note'] ?></td>
															</tr>
														<?php
															$i++;
														endforeach;
														?>
													</tbody>
												</table>
											</div>

											<div class="tab-pane" id="daftarPrestasi">
												<table id="dtablePrestasi" class="table table-bordered" style="white-space: nowrap;width:100%;">
													<thead>
														<tr class="info">
															<th style="width: 5%;">No.</th>
															<th style="width: 15%;">Tanggal</th>
															<th style="width: 20%;">Prestasi</th>
															<th style="width: 30%;">Tujuan</th>
															<th style="width: 5%;">Poin</th>
															<th style="width: 10%;">Catatan</th>
															<th style="width: 5%;">Bukti</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 1;
														$poinPrestasi = 0;
														foreach ($prestasi_siswa as $row):
														?>
															<tr>
																<td style="width: 5%;"><?php echo $i ?></td>
																<td><?php echo pretty_date($row['konseling_date'], 'd F Y', false) ?></td>
																<td><?php echo $row['prestasi'] ?></td>
																<td><?php echo $row['konseling_action'] ?></td>
																<td style="width: 5%; text-align:center;">
																	<?php
																	echo $row['konseling_poin'];
																	$poinPrestasi += $row['konseling_poin'];
																	?>
																</td>
																<td><?php echo $row['konseling_note'] ?></td>
																<td style="width: 5%;">
																	<?php if (!empty($row['bukti'])) { ?>
																		<a target="_blank" href="<?php echo base_url('uploads/konseling/') . $row['bukti'] ?>" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" title="Bukti Pelanggaran"></i></a>
																	<?php } ?>
																</td>
															</tr>

														<?php
															$i++;
														endforeach;
														?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="4" style="font-weight: bold;">Total Poin</td>
															<td style="font-weight: bold;text-align:center;"> <?php echo $poinPrestasi; ?> </td>
															<td></td>
															<td></td>
														</tr>
													</tfoot>
												</table>
											</div>

										</div>
									</div>
								</div>
								<!--  -->
							</div>
						</div>

					</div>
				</div>
				<!-- /.row -->

	</section>
</div>

<!-- modal -->
<div class="modal modal-default fade" id="delModal<?php echo $student['student_id']; ?>">
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
				<?php echo form_open('manage/student/delete/' . $student['student_id']); ?>
				<input type="hidden" name="delName" value="<?php echo $student['student_full_name']; ?>">
				<input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
				<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
				<?php echo form_close(); ?>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- Modal -->

<script type="text/javascript">
	$(document).ready(function() {
		$("#dtablePelanggaran").DataTable();
		$("#dtableKonsultasi").DataTable();
		$("#dtablePrestasi").DataTable();
	});
</script>