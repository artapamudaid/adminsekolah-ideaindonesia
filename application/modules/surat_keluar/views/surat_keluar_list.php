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
						<a href="<?php echo site_url('manage/surat_keluar/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<a href="<?php echo site_url('manage/surat_keluar/printRekap') ?>" target="_blank" class="btn btn-danger btn-sm"><i class="fa fa-print"></i> Cetak Rekap Data</a>
						
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
								<th>No Surat</th>
								<th>Tujuan</th>
								<th>Jenis Surat</th>
								<th>Kode Surat</th>
								<th>Tanggal Surat</th>
								<th>Tanggal Input</th>
								<th>File </th>
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($surat_keluar)) {
									$i = 1;
									foreach ($surat_keluar as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['no_surat']; ?></td>
											<td><?php echo $row['tujuan']; ?></td>
											<td><?php echo $row['nama_jenis']; ?></td>
											<td><?php echo $row['kode_surat']; ?></td>
											<td><?php echo $row['tgl_surat']; ?></td>
											<td><?php echo $row['tgl_catat']; ?></td>
											<?php if ($row['file']==null){ ?>
												<td><label class="btn btn-xs btn-danger">File Kosong</label></td>
											<?php }else{ ?>
												<td>
													<a href="#dwlSk<?php echo $row['id_surat']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-download" data-toggle="tooltip" title="Download"> Lihat File</i></a>
    							            	</td>
											<?php }?>
											<td>
												<a href="<?php echo site_url('manage/surat_keluar/view/' . $row['id_surat']) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
												<a href="<?php echo site_url('manage/surat_keluar/edit/' . $row['id_surat']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												<a href="#delModal<?php echo $row['id_surat']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>	
										</tr>
										<!-- /.download Arsip -->
										<div class="modal modal-default fade" id="dwlSk<?php echo $row['id_surat']; ?>">
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
																	?><img src="<?php echo upload_url('surat_keluar/' . $row['file']) ?>" class="img-responsive avatar"><?php
																}else if($ext=='png'){
																	?><img src="<?php echo upload_url('surat_keluar/' . $row['file']) ?>" class="img-responsive avatar"><?php
																}else{
																	?><iframe src="<?php echo upload_url('surat_keluar/' . $row['file']) ?>" width="100%" height="400px"></iframe>
																	<a src="<?php echo upload_url('surat_keluar/' . $row['file']) ?>"></a><?php
																}?>
															<?php } else { ?>
															<img id="target" alt="Choose image to upload">
															<?php } ?>
														</a>
													</div>
													<div class="modal-footer">
														<?php echo form_open('manage/surat_keluar/download_sk/' . $row['id_surat']); ?>
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
															<?php echo form_open('manage/surat_keluar/delete/' . $row['id_surat']); ?>
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
			var scntDiv = $('#p_scents_class');
			var i = $('#p_scents_class p').size() + 1;

			$("#addScnt_satuan").click(function() {
				$('<p><label>Nama Satuan</label><input required type="text" name="nama_satuan[]" class="form-control" placeholder="Contoh: Pack"><label>Keterangan</label><input required type="text" name="keterangan[]" class="form-control" placeholder="Contoh: Bandel/ Lembar"><a href="#" class="btn btn-xs btn-danger remScnt_satuan">Hapus Baris</a></p>').appendTo(scntDiv);
				i++;
				return false;
			});

			$(document).on("click", ".remScnt_satuan", function() {
				if (i > 2) {
					$(this).parents('p').remove();
					i--;
				}
				return false;
			});
		});
	</script>