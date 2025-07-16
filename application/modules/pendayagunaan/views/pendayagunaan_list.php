<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<?php
function createSummary($text)
{
	$limit = 100;
	$textWithoutTags = strip_tags($text);
	$textWithoutImages = preg_replace('/<img[^>]*>/', '', $textWithoutTags);

	$visibleText = mb_substr($textWithoutImages, 0, $limit);

	$lastPeriodPos = mb_strrpos($visibleText, '.');

	if ($lastPeriodPos !== false) {
		$visibleText = mb_substr($visibleText, 0, $lastPeriodPos + 1);
	}

	return $visibleText;
}
?>
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
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<a href="<?php echo site_url('manage/program/add') ?>" type=" button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</a>
						<br>
						<br>

						<div class="box-body table-responsive">
							<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
							<table>
								<tr>
									<td>
										<select style="width: 200px;" id="m" name="m" class="form-control" required>
											<option value="">--- Pilih Unit ---</option>
											<?php if ($this->session->userdata('umajorsid') == '0') { ?>
												<option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
											<?php } ?>
											<?php foreach ($majors as $row) { ?>
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
							<?php echo form_close(); ?>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Gambar</th>
									<th>Nama Program</th>
									<th>Target</th>
									<th>Pencapaian</th>
									<th>Kekurangan</th>
									<th>Pendayagunaan</th>
									<th>Sisa</th>
									<th>Status</th>
									<th>Sampai</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($pendayagunaan)) {
									$i = 1;
									foreach ($pendayagunaan as $row) :
								?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><img class="card-img-top img-fluid" src="<?= base_url() . 'uploads/program/' . $row['program_gambar'] ?>" width="100px" alt="Gambar Program"></td>
											<td><?php echo $row['program_name']; ?></td>
											<td><?php echo 'Rp ' . number_format($row['program_target'], 0, ',', '.'); ?></td>
											<td><?php echo 'Rp ' . number_format($row['program_earn'], 0, ',', '.'); ?></td>
											<td><?php echo 'Rp ' . number_format($row['program_target'] - $row['program_earn'], 0, ',', '.'); ?></td>
											<td><?php echo 'Rp ' . number_format($row['program_realisasi'], 0, ',', '.'); ?></td>
											<td><?php echo 'Rp ' . number_format($row['program_earn'] - $row['program_realisasi'], 0, ',', '.'); ?></td>
											<td><label class="label <?php echo ($row['program_status'] == 1) ? 'label-success' : 'label-danger' ?>"><?php echo ($row['program_status'] == 1) ? 'Aktif' : 'Tidak Aktif' ?></label></td>
											<td><?php echo pretty_date($row['program_end'], 'd F Y', false); ?></td>
											<td>
												<a href="<?php echo site_url('manage/pendayagunaan/view/' . $row['program_id']) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="Lihat"><i class="fa fa-eye"></i></a>
											</td>
										</tr>
									<?php
										$i++;
									endforeach;
								} else {
									?>
									<tr id="row">
										<td colspan="8" align="center">Data Kosong</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>