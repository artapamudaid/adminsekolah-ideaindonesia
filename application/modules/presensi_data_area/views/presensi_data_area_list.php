<style>
	.area-table-card {
		background: #ffffff;
		border-radius: 14px;
		border: 1px solid #e9ecef;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}
	.area-table-header {
		padding: 16px 20px;
		background: #ffffff;
		border-bottom: 1px solid #f1f3f5;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 10px;
	}
	.coord-badge {
		font-family: monospace;
		font-size: 12px;
		padding: 3px 8px;
		border-radius: 6px;
		display: inline-block;
	}
	.coord-badge-lat {
		background: #fee2e2;
		color: #b91c1c;
	}
	.coord-badge-lng {
		background: #eff6ff;
		color: #1d4ed8;
	}
	.btn-modern-add {
		background: #2575FC;
		color: #ffffff;
		border: none;
		border-radius: 8px;
		padding: 7px 16px;
		font-weight: 600;
		font-size: 13px;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		transition: background 0.2s ease;
	}
	.btn-modern-add:hover {
		background: #1a5ac9;
		color: #ffffff;
	}
	.btn-modern-excel {
		background: #10b981;
		color: #ffffff;
		border: none;
		border-radius: 8px;
		padding: 7px 16px;
		font-weight: 600;
		font-size: 13px;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		transition: background 0.2s ease;
	}
	.btn-modern-excel:hover {
		background: #059669;
		color: #ffffff;
	}
	.table-area-modern th {
		background: #f8fafc;
		color: #475569;
		font-weight: 600;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		padding: 12px 14px;
		border-top: none !important;
		border-bottom: 2px solid #e2e8f0 !important;
	}
	.table-area-modern td {
		padding: 12px 14px;
		font-size: 13px;
		color: #334155;
		vertical-align: middle !important;
		border-top: 1px solid #f1f5f9 !important;
	}
	.table-area-modern tbody tr:hover {
		background-color: #f8fafc;
	}
	.action-btn-group {
		display: inline-flex;
		gap: 4px;
	}
	.action-btn {
		width: 30px;
		height: 30px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 6px;
		font-size: 12px;
		border: none;
		transition: transform 0.15s ease;
	}
	.action-btn:hover {
		transform: translateY(-2px);
	}
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1 style="font-weight: 700; color: #1e293b;">
			<?php echo isset($title) ? $title : 'Data Area Presensi'; ?>
			<small style="color: #64748b; font-size: 14px;">Daftar Titik Lokasi Presensi Pegawai & Siswa</small>
		</h1>
		<ol class="breadcrumb" style="background: transparent;">
			<li><a href="<?php echo site_url('manage') ?>" style="color: #2575FC;"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? $title : 'Data Area Presensi'; ?></li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="area-table-card"> 
					<div class="area-table-header">
						<?php if ($this->session->userdata('uroleid') != USER) { ?>
							<div style="display: flex; gap: 8px;">
								<a href="<?php echo site_url('manage/presensi_data_area/add') ?>" class="btn-modern-add">
									<i class="fa fa-plus-circle"></i> Tambah Area Presensi
								</a>
								<a href="<?php echo site_url('manage/presensi_data_area/export_excel') ?>" class="btn-modern-excel" target="_blank">
									<i class="fa fa-file-excel-o"></i> Export Excel
								</a>
							</div>
						<?php } ?>
					</div>

					<!-- Table Body -->
					<div class="table-responsive" style="padding: 0;">
						<table id="dtable" class="table table-area-modern">
							<thead>
								<tr>
									<th style="width: 50px; text-align: center;">No</th>
									<th>Nama Area Presensi</th>
									<th>Garis Lintang (Latitude)</th>
									<th>Garis Bujur (Longitude)</th>
									<th>Keterangan / Alamat</th>
									<th style="text-align: center; width: 140px;">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($presensi_data_area)) {
									$i = 1;
									foreach ($presensi_data_area as $row):
								?>
									<tr>
										<td align="center"><?php echo $i; ?></td>
										<td>
											<div style="font-weight: 700; color: #1e293b;">
												<a href="javascript:void(0)" onclick="maps_area('<?php echo base64_encode($row['id_area']); ?>')" style="color: #2575FC;" data-toggle="tooltip" title="Lihat Peta Lokasi">
													<i class="fa fa-map-marker text-danger"></i> <?php echo htmlspecialchars($row['nama_area']); ?>
												</a>
											</div>
										</td>
										<td>
											<span class="coord-badge coord-badge-lat">
												<i class="fa fa-crosshairs"></i> <?php echo htmlspecialchars($row['lati']); ?>
											</span>
										</td>
										<td>
											<span class="coord-badge coord-badge-lng">
												<i class="fa fa-crosshairs"></i> <?php echo htmlspecialchars($row['longi']); ?>
											</span>
										</td>
										<td>
											<span style="color: #64748b;"><?php echo htmlspecialchars($row['remark'] ? $row['remark'] : '-'); ?></span>
										</td>
										<td align="center">
											<div class="action-btn-group">
												<a href="<?php echo site_url('manage/presensi_data_area/view/' . $row['id_area']) ?>" class="btn btn-info btn-xs action-btn" data-toggle="tooltip" title="Lihat Detail & Peta">
													<i class="fa fa-eye"></i>
												</a>
												<?php if ($this->session->userdata('uroleid') != USER) { ?>
													<a href="<?php echo site_url('manage/presensi_data_area/edit/' . $row['id_area']) ?>" class="btn btn-warning btn-xs action-btn" data-toggle="tooltip" title="Edit Lokasi">
														<i class="fa fa-edit"></i>
													</a>
												<?php } ?>
												<a href="#delModal<?php echo $row['id_area']; ?>" data-toggle="modal" class="btn btn-danger btn-xs action-btn" data-toggle="tooltip" title="Hapus">
													<i class="fa fa-trash-o"></i>
												</a>
											</div>
										</td>	
									</tr>

									<!-- Modal Konfirmasi Hapus -->
									<div class="modal fade" id="delModal<?php echo $row['id_area']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
												<div class="modal-header" style="background: #ef4444; color: #ffffff;">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;">
														<span aria-hidden="true">&times;</span>
													</button>
													<h4 class="modal-title" style="font-weight: 600;"><i class="fa fa-warning"></i> Konfirmasi Penghapusan</h4>
												</div>
												<div class="modal-body" style="padding: 20px;">
													<p style="color: #475569; margin-bottom: 12px;">Apakah Anda yakin ingin menghapus data area presensi berikut?</p>
													<div class="well well-sm" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 0;">
														<div style="font-size: 14px; font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($row['nama_area']); ?></div>
														<div style="font-size: 12px; color: #64748b; margin-top: 4px;">Lat: <?php echo $row['lati']; ?>, Lng: <?php echo $row['longi']; ?></div>
													</div>
												</div>
												<div class="modal-footer" style="background: #f8fafc;">
													<?php echo form_open('manage/presensi_data_area/delete/' . $row['id_area']); ?>
													<input type="hidden" name="delName" value="<?php echo htmlspecialchars($row['nama_area']); ?>">
													<button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
													<button type="submit" class="btn btn-danger" style="background: #ef4444; border-color: #ef4444; border-radius: 8px;">Hapus Data</button>
													<?php echo form_close(); ?>
												</div>
											</div>
										</div>
									</div>
								<?php
										$i++;
									endforeach;
								} else {
								?>
									<tr>
										<td colspan="6" align="center" style="padding: 28px; color: #94a3b8;">
											<i class="fa fa-map-o" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
											Belum ada data area presensi yang ditambahkan.
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>

					<div style="padding: 12px 20px;">
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
function maps_area(id_area) {    
	newWindow("<?php echo base_url()?>manage/presensi_data_area/maps_area?id_area=" + id_area, "popup", "1000", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");
}
</script>