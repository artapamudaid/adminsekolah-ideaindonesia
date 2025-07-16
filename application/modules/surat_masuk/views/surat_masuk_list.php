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
						<a href="<?php echo site_url('manage/surat_masuk/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<a href="<?php echo site_url('manage/surat_masuk/printRekap') ?>" target="_blank" class="btn btn-danger btn-sm"><i class="fa fa-print"></i> Cetak Rekap Data</a>
						
						<!-- <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
					    <div class="box-body table-responsive">
							<table>
							    <tr>
							        <td>     
    								<select style="width: 200px;" id="m" name="m" class="form-control" required>
    								    <option value="">--- Pilih Unit Sekolah ---</option>
    								    <?php if($this->session->userdata('umajorsid') == '0') { ?>
    								    <option value="all" <?php echo (isset($s['m']) && $s['m']=='all') ? 'selected' : '' ?> >Semua Unit</option>
    								    <?php } ?>
            						    <?php foreach($majors as $row){?>
            						        <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
            						    <?php } ?>
    								</select>
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>    
							        </td>
							    </tr>
							</table>
							</div>
							<?php echo form_close(); ?> -->
					</div>
					
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>No Agenda</th>
								<th>No Surat</th>
								<th>Asal Surat</th>
								<th>Isi</th>
								<th>Tanggal Diterima </th>
								<th>File </th>
								<!-- <th>Disposisi </th> -->
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($surat_masuk)) {
									$i = 1;
									foreach ($surat_masuk as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['no_agenda']; ?></td>
											<td><?php echo $row['no_surat']; ?></td>
											<td><?php echo $row['asal_surat']; ?></td>
											<td><?php echo $row['isi']; ?></td>
											<td><?php echo $row['tgl_diterima']; ?></td>
											<?php if ($row['file']==null){ ?>
												<td><label class="btn btn-xs btn-danger">File Kosong</label></td>
											<?php }else{ ?>
												<td>
													<a href="#dwlSm<?php echo $row['id_surat']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-download" data-toggle="tooltip" title="Download"> Lihat File</i></a>
    							            	</td>
											<?php }?>
											<td>
												<!-- <a href="<?php echo site_url('manage/surat_masuk/view/' . $row['id_surat']) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a> -->
												<!-- <a href="#addDispo<?php echo $row['id_surat']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-plus" data-toggle="tooltip" title="Disposisi"></i></a> -->
												<?php
													if($row['id_disposisi']==NULL){
													?>
														<a href="<?php echo site_url('manage/surat_masuk/add_disposisi/' . $row['id_surat']) ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-plus" data-toggle="tooltip" title="Disposisi"></i></a>
													<?php
													}else{
													?>
														<a href="<?php echo site_url('manage/surat_masuk/printdata/' . $row['id_surat']) ?>" target="_blank" data-toggle="modal" class="btn btn-xs btn-primary"><i class="fa fa-print" data-toggle="tooltip" title="Print Disposisi"></i></a>
													<?php
													}
												?>
												<a href="<?php echo site_url('manage/surat_masuk/edit/' . $row['id_surat']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												<a href="#delModal<?php echo $row['id_surat']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>	
										</tr>
										<!-- Tambah Disposisi -->
										<div class="modal modal-default fade" id="addDispo<?php echo $row['id_surat']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Tambah Disposi Surat</h4>
													</div>
													<?php echo form_open_multipart('manage/surat_masuk/add_disposisi', array('method'=>'post')); ?>
													<div class="modal-body">
														<input type="hidden" name="id_surat[]" value="<?php echo $row['id_surat']; ?>">
														<div id="p_scents_disposisi">
															<p>
																<label>Tujuan Disposisi</label>
																<input type="text" required="" name="tujuan[]" class="form-control" placeholder="Contoh: Bag. Keuangan">
																<h6 ><a href="#" class="btn btn-xs btn-success" id="addScnt_disposisi"><i class="fa fa-plus"></i><b> Tambah Tujuan</b></a></h6>
																<label>Isi Disposisi</label>
																<input type="text" required="" name="isi_disposisi[]" class="form-control" placeholder="Isi Disposisi">
																<label>Tanggal Batas Waktu</label>
																<input type="date" required="" name="batas_waktu[]" class="form-control" placeholder="Batas Waktu">
																<label>catatan</label>
																<input type="text" required="" name="catatan[]" class="form-control" placeholder="Catatan">
																<label>Sifat</label>
																<select name="sifat[]" class="form-control">
																	<option value="Biasa"> Biasa </option>
																	<option value="Penting"> Penting </option>
																	<option value="Segera"> Segera </option>
																	<option value="Rahasia"> Rahasia </option>
																</select>
																<!-- <input type="text" required="" name="sifat[]" class="form-control" placeholder="Contoh: Penting"> -->
															</p>
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
										<!-- /.download Arsip -->
										<div class="modal modal-default fade" id="dwlSm<?php echo $row['id_surat']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span></button>
															<h3 class="modal-title"><span class="fa fa-download"></span> Dokumen Preview</h3>
													</div>
													<div class="modal-body">
														
														<a href="#" class="thumbnail">
															<?php if (isset($row) AND $row['file'] != NULL) { 
																$p=  $row['file']; 
																$explode = explode(".", $p);
																$ext = $explode[1];
																if ($ext=='jpg'){
																	?><img src="<?php echo upload_url('surat_masuk/' . $row['file']) ?>" class="img-responsive avatar"><?php
																}else if($ext=='png'){
																	?><img src="<?php echo upload_url('surat_masuk/' . $row['file']) ?>" class="img-responsive avatar"><?php
																}else{
																	?><iframe src="<?php echo upload_url('surat_masuk/' . $row['file']) ?>" width="100%" height="400px"></iframe>
																	<a src="<?php echo upload_url('surat_masuk/' . $row['file']) ?>"></a><?php
																}?>
															<?php } else { ?>
															<img id="target" alt="Choose image to upload">
															<?php } ?>
														</a>
													</div>
													<div class="modal-footer">
														<?php echo form_open('manage/surat_masuk/download_sm/' . $row['id_surat']); ?>
														<input type="hidden" name="id_surat" value="<?php echo $row['id_surat']; ?>">
														<input type="hidden" name="file" value="<?php echo $row['file']; ?>">
														<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
														<button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Download</button>
														<?php echo form_close(); ?>
													</div>
												</div>
											</div>
										</div>
										<!-- Hapus Data -->
										<div class="modal modal-default fade" id="delModal<?php echo $row['id_surat']; ?>">
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
															<?php echo form_open('manage/surat_masuk/delete/' . $row['id_surat']); ?>
															<input type="hidden" name="delName" value="<?php echo $row['no_surat']; ?>">
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
						<div>
							<?php echo $this->pagination->create_links(); ?>
						</div>
						<!-- /.box -->
					</div>
				</div>
			</section>
			<!-- /.content -->
		</div>
	</div>

	<script>
		$(function() {
			var scntDiv = $('#p_scents_disposisi');
			var i = $('#p_scents_disposisi p').size() + 1;

			$("#addScnt_disposisi").click(function() {
				$('<p><label>Tujuan Disposisi</label><input type="text" required="" name="tujuan[]" class="form-control" placeholder="Contoh: Bag. Keuangan"><a href="#" class="btn btn-xs btn-danger remScnt_disposisi">Hapus Baris</a></p>').appendTo(scntDiv);
				i++;
				return false;
			});

			$(document).on("click", ".remScnt_disposisi", function() {
				if (i > 2) {
					$(this).parents('p').remove();
					i--;
				}
				return false;
			});
		});
	</script>