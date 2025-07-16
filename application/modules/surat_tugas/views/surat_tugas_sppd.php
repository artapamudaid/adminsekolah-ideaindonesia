
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
				<div class="box box-success">
					<div class="box-header">
						<?php echo form_open(current_url(), array('method' => 'get')) ?>
						<div class="row">
							<div class="col-md-2">  
								<div class="form-group">
									<label>Tanggal Awal</label>
									<input type="date" class="form-control" name="ds" id="dari" value="<?php echo $date_start; ?>" placeholder="Dari .." />
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Tanggal Awal</label>
									<input type="date" class="form-control" name="de" id="sampai" value="<?php echo $date_end; ?>" placeholder="Sampai .." />
								</div>
							</div>
							<div class="col-md-2">
							<div style="margin-top:25px;">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
							<?php if ($q AND !empty($sppd)) { ?>
							<a class="btn btn-danger" target="_blank" href="<?php echo site_url('manage/sppd/cetak_sppd' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-pdf-o" ></i> Cetak</a>
							<?php } ?>
						    </div>
					        </div>
					</div>
					</form>
					</div>
				</div>
				
				<?php if ($q) { 
						
					?>
				<div class="box box-success">
					<div class="box-body table-responsive">
						<?php if(!empty($sppd)){
							
						?>
    					<table class="table table-responsive table-bordered" style="white-space: nowrap;">
							<thead>
								<tr>
									<th width="80px">No</th>
									<th>No SSPD</th>
									<th>Tanggal</th>
									<th>Maksud</th>
									<th>Pemberi Perintah</th>
									<th>yang di perintah</th>
									<th>Tujuan</th>
									<th>Berangkat</th>
									<th>Kembali</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$no = 1;
								foreach($sppd as $row){
							?>
							<tr>
								<td><?= $no++; ?></td>
								<td><?= $row['no_sppd'] ?></td>
								<td><?= $row['tanggal_input']; ?></td>
								<td><?= $row['deskripsi']; ?></td>
								<td><?= $row['nama_perintah']; ?></td>
								<td><?= $row['nama_diperintah']; ?></td>
								<td><?= $row['tmp_tujuan']; ?></td>
								<td><?= $row['tgl_berangkat']; ?></td>
								<td><?= $row['tgl_kembali']; ?></td>
							</tr>
							<?php } 
							}else{
								echo "<center>Tidak Ada Data yang di tampilkan...</center>";
							}
							?>
							</tbody>
    					</table>
					</div>
					<!-- /.box-body -->
				</div>
				<?php 
				}
				?>
				
				
				
				
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
