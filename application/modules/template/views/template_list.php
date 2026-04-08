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
						

						<a href="<?= site_url('manage/template/add') ?>" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Tambah</a>
						
					</div>
					
						
					
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-hover">
						    <thead>
							<tr>
								<th>No</th>
								<th>Modul</th>
								<th>Label</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($templates)) {
									$i = 1;
									foreach ($templates as $row):
                                        switch ($row['location']) {
                                            case 'PAYOUT':
                                                $translate = "PEMBAYARAN";
                                                break;
                                            case 'PENGUMUMAN':
                                                $translate = "PENGUMUMAN";
                                                break;
                                            case 'SLIP':
                                                $translate = "SLIP GAJI";
                                                break;
                                            case 'BILLING':
                                                $translate = "TAGIHAN";
                                                break;
                                            case 'BANKING_IN':
                                                $translate = "SETOR TABUNGAN";
                                                break;
                                            case 'BANKING_OUT':
                                                $translate = "TARIK TABUNGAN";
                                                break;
                                            default:
                                                $translate = "-";
                                                break;
                                        }; 
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $translate; ?>
                                            </td>
											<td><?php echo $row['title']; ?></td>
											<td>
                                                <span class="label label-<?php echo ($row['is_active'] == 'Y') ? 'success' : 'danger'; ?>">
                                                    <?php echo ($row['is_active'] == 'Y') ? 'Aktif' : 'Non Aktif'; ?>
                                                </span>
                                            </td>
											<td>
												<a href="<?php echo site_url('manage/template/edit/' . $row['id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>			
                                                <?php if($row['location']=='PENGUMUMAN') : ?>
                                                    <a href="#delModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
                                                <?php
                                                    endif;
                                                ?>
											</td>	
										</tr>
                                            <?php if($row['location']=='PENGUMUMAN') : ?>
                                                <div class="modal modal-default fade" id="delModal<?php echo $row['id']; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                    <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah anda yakin akan menghapus data ini?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <?php echo form_open('manage/template/delete/' . $row['id']); ?>
                                                                
                                                                <input type="hidden" required="" name="location" class="form-control" value="<?php echo $row['location']?>">
                                                                <input type="hidden" name="delName" value="<?php echo $row['title'] . ' ' . $row['content']; ?>">
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
                                            endif;
                                            ?>
                                            <?php
											$i++;
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
							<!-- /.box-body -->
							
					
				</div>
				<!-- /.box -->
			</div>
		</div>
    </section>
</div>