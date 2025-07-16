<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>Detail</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Anggaran - <?php echo $anggaran['account_code'] . ' ' . $anggaran['account_description'] . ' - T.A ' . $anggaran['period_start'] . '/' . $anggaran['period_end'] ?></h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<label for="" class="col-sm-2">Setting Nominal</label>
						<div class="col-sm-10">
							<a class="btn btn-primary btn-sm" href="<?php echo site_url('manage/anggaran/add_anggaran_detail/' . $anggaran['anggaran_id']) ?>"><span class="glyphicon glyphicon-plus"></span> Set Anggaran Per Bulan</a>
							<a class="btn btn-default btn-sm" href="<?php echo site_url('manage/anggaran') ?>"><span class="glyphicon glyphicon-repeat"></span> Kembali</a>
						</div>
					</div><!-- /.box-body -->
				</div><!-- /.box -->

				<div class="box box-primary">
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
							<thead>
								<tr>
									<th>Anggaran</th>
									<th>Total Nominal</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($detail as $row) :
								?>
									<tr>
										<td><?php echo $row['account_code'] . ' - ' . $row['account_description'] . ' ' . $row['period_start'] . '/' . $row['period_end']; ?></td>
										<td>Rp. <?php echo number_format($row['nominal'], 0, ",", "."); ?></td>
										<td>
											<a href="<?php echo site_url('manage/anggaran/edit_anggaran_detail/' . $row['anggaran_id'] . '/' . $row['student_student_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Ubah Nominal"><i class="fa fa-edit"></i></a>
											<a href="<?php echo site_url('manage/anggaran/delete_anggaran_detail/' . $row['anggaran_id'] . '/' . $row['student_student_id']) ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Hapus Nominal" onclick="return confirm('<?php echo 'Apakah anda akan menghapus nominal anggaran ini ?' ?>')"><i class="fa fa-trash"></i></a>
										</td>
									</tr>
								<?php
								endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>