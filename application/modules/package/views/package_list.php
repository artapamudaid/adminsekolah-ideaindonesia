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
					<!-- /.box-header -->
					<div class="box-body table-responsive no-padding">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>Paket</th>
								<th>Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($package)) {
									$i = 1;
									foreach ($package as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['package_name']; ?></td>
											<td>
												<a href="<?php echo site_url('manage/package/modules?package_id=' . $row['package_id']) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="Lihat"><i class="fa fa-cogs"></i></a>
												</td>	
											</tr>
												<?php
												$i++;
											endforeach;
										} else {
											?>
											<tr id="row">
												<td colspan="3" align="center">Data Kosong</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<!-- /.box-body -->
							</div>
							<div>
							</div>
							<!-- /.box -->
						</div>
					</div>
				</section>
				<!-- /.content -->
			</div>