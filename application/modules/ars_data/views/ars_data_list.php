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
						<a href="<?php echo site_url('manage/ars_data/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addSatuan"><i class="fa fa-plus"></i> Tambah</button> -->
						
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
								<th>Jenis Arsip </th>
								<th>Pembuat </th>
								<th>Nama Arsip </th>
								<th>File Arsip </th>
								<th>Jumlah </th>
								<th>Satuan </th>
								<th>Lokasi </th>
								<th>Status </th>
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($data_arsip)) {
									$i = 1;
									foreach ($data_arsip as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['jenis_arsip']; ?></td>
											<td><?php echo $row['user_full_name']; ?></td>
											<td><?php echo $row['nama_arsip']; ?></td>
											<?php if ($row['file_arsip']==null){ ?>
												<td><label class="btn btn-xs btn-danger">File Kosong</label></td>
											<?php }else{ ?>
												<?php if ($row['status']==0){ ?>
													<td><label class="btn btn-xs btn-danger">File Belum Terverifikasi</label></td>
												<?php } else { ?>
													<td><a href="#dwlars<?php echo $row['id_arsip']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-download" data-toggle="tooltip" title="Download"> Download</i></a>
    							            		</td>
												<?php }?>
											<?php }?>
											<td><?php echo $row['jumlah']; ?></td>
											<td><?php echo $row['nama_satuan']; ?></td>
											<td><?php echo $row['nama_lokasi']; ?></td>
											<?php if ($row['status']==0){ ?>
												<td><label class="btn btn-xs btn-danger">Arsip Belum Terverifikasi</label></td>
											<?php }else { ?>
												<td><label class="btn btn-xs btn-success">Arsip Terverifikasi</label></a></td>
											<?php }?>
											<td>
												<a href="<?php echo site_url('manage/ars_data/view/' . $row['id_arsip']) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
												<?php if ($this->session->userdata('uroleid') == '1'){ ?>
													<a href="<?php echo site_url('manage/ars_data/edit/' . $row['id_arsip']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												<?php } else { ?>
													
												<?php }?>
												<a href="#delModal<?php echo $row['id_arsip']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
											</td>	
										</tr>
										<!-- /.download Arsip -->
										<div class="modal modal-default fade" id="dwlars<?php echo $row['id_arsip']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span></button>
															<h3 class="modal-title"><span class="fa fa-download"></span> Dokumen Preview</h3>
													</div>
													<div class="modal-body">
														
														<a href="#" class="thumbnail">
															<?php if (isset($row) AND $row['file_arsip'] != NULL) { 
																$p=  $row['file_arsip']; 
																$explode = explode(".", $p);
																$ext = $explode[1];
																if ($ext=='jpg'){
																	?><img src="<?php echo upload_url('arsip/' . $row['file_arsip']) ?>" class="img-responsive avatar"><?php
																}else if($ext=='png'){
																	?><img src="<?php echo upload_url('arsip/' . $row['file_arsip']) ?>" class="img-responsive avatar"><?php
																}else{
																	?><iframe src="<?php echo upload_url('arsip/' . $row['file_arsip']) ?>" width="100%" height="400px"></iframe>
																	<a src="<?php echo upload_url('arsip/' . $row['file_arsip']) ?>"></a><?php
																}?>
															<?php } else { ?>
															<img id="target" alt="Choose image to upload">
															<?php } ?>
														</a>
													</div>
													<div class="modal-footer">
														<?php echo form_open('manage/ars_data/download_arsip/' . $row['id_arsip']); ?>
														<input type="hidden" name="id_arsip" value="<?php echo $row['id_arsip']; ?>">
														<input type="hidden" name="file_arsip" value="<?php echo $row['file_arsip']; ?>">
														<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
														<button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Download</button>
														<?php echo form_close(); ?>
													</div>
												</div>
											</div>
										</div>
										<!-- Hapus Arsip -->
										<div class="modal modal-default fade" id="delModal<?php echo $row['id_arsip']; ?>">
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
															<?php echo form_open('manage/ars_data/delete/' . $row['id_arsip']); ?>
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

		<!-- Modal -->
		<div class="modal fade" id="addSatuan" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Tambah Satuan Arsip</h4>
					</div>
					<?php echo form_open('manage/ars_satuan/add_glob', array('method'=>'post')); ?>
					<div class="modal-body">
						<div id="p_scents_class">
							<p>
								<label>Nama Satuan</label>
								<input type="text" required="" name="nama_satuan[]" class="form-control" placeholder="Contoh: Pack">
								<label>Keterangan</label>
								<input type="text" name="keterangan[]" class="form-control" placeholder="Contoh: Bandel/ Lembar">
							</p>
						</div>
						<h6 ><a href="#" class="btn btn-xs btn-success" id="addScnt_satuan"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
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