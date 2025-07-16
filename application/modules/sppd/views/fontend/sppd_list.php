<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('employees') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<a href="<?php echo site_url('employees/sppd/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>No. SPPD</th>
								<th>Tanggal Input</th>
								<th>Maksud</th>
								<th>Pemberi Perintah</th>
								<th>Mendapat Perintah </th>
								<th>Tujuan </th>
								<th>Tanggal Berangkat </th>
								<th>Tanggal Kembali </th>
								<th width="50px">Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($sppd)) {
									$i = 1;
									foreach ($sppd as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['no_sppd']; ?></td>
											<td><?php echo $row['tanggal_input']; ?></td>
											<td><?php echo $row['deskripsi']; ?></td>
											<td><?php echo $row['nama_perintah']; ?></td>
											<td><?php echo $row['nama_diperintah']; ?></td>
											<td><?php echo $row['tmp_tujuan']; ?></td>
											<td><?php echo $row['tgl_berangkat']; ?></td>
											<td><?php echo $row['tgl_kembali']; ?></td>
											<td>
												<!-- <a href="<?php echo site_url('employees/sppd/printdata/' . $row['id_sppd']) ?>" class="btn btn-xs btn-primary" target="_blank" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></a> -->
												<a href="<?php echo site_url('employees/sppd/view/' . $row['id_sppd']) ?>" class="btn btn-xs btn-primary"  data-toggle="tooltip" title="Lihat Detail"><i class="fa fa-eye"></i></a>
												
												<!-- <a href="<?php echo site_url('employees/sppd/edit/' . $row['id_sppd']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
												
												<a href="#delModal<?php echo $row['id_sppd']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a> -->
											</td>	
										</tr>
										<div class="modal modal-default fade" id="delModal<?php echo $row['id_sppd']; ?>">
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
															<?php echo form_open('employees/sppd/delete/' . $row['id_sppd']); ?>
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
											<td colspan="10" align="center">Data Kosong</td>
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
	</div>

	<!-- <script>
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
	</script> -->