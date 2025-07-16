<div class="content-wrapper">
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
						<a href="<?php echo site_url('manage/anggaran/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</a>
						<br>
						<br>
						<div class="box-body table-responsive">
							<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
							<table>
								<tr>
									<td>
										<select style="width: 200px;" id="p" name="p" class="form-control" required>
											<option value="">--- Pilih Tahun Ajaran ---</option>
											<option value="all" <?php echo (isset($s['p']) && $s['p'] == 'all') ? 'selected' : '' ?>> Semua Tahun Ajaran </option>
											<?php foreach ($period as $row) { ?>
												<option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['p']) && $s['p'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] . '/' . $row['period_start'] ?></option>
											<?php } ?>
										</select>
									</td>
									<td>
										&nbsp&nbsp
									</td>
									<td>
										<select style="width: 200px;" id="m" name="m" class="form-control" required>
											<option value="">--- Pilih Unit Sekolah ---</option>
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
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>No.</th>
									<th>Akun</th>
									<th>Tahun</th>
									<th>Setting Anggaran</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($anggaran)) {
									$no = 1;
									foreach ($anggaran as $row) :
								?>
										<tr>
											<td><?php echo $no++ ?></td>
											<td><?php echo $row['account_code'] . ' - ' . $row['account_description']; ?></td>
											<td><?php echo $row['period_start'] . '/' . $row['period_end']; ?></td>
											<td style="text-align: center;">
												<a data-toggle="tooltip" data-placement="top" title="Setting Anggaran" class="btn btn-primary btn-xs" href="<?php echo site_url('manage/anggaran/view_detail/' . $row['anggaran_id']) ?>">
													Setting Anggaran
												</a>
											</td>
											<td>
												<a href="<?php echo site_url('manage/anggaran/edit/' . $row['anggaran_id']) ?>" class="btn btn-xs btn-success" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>

											</td>
										</tr>
									<?php
									endforeach;
								} else {
									?>
									<tr id="row">
										<td colspan="5" align="center">Data Kosong</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<div>
					<?php echo $this->pagination->create_links(); ?>
				</div>
			</div>
		</div>
	</section>
</div>