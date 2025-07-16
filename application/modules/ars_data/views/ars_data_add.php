<?php

if (isset($arsip)) {

	$inputNamaValue 	= $arsip['nama_arsip'];
	$inputFileValue 	= $arsip['file_arsip'];
	$inputStatusValue 	= $arsip['status'];
	$inputJumlahValue 	= $arsip['jumlah'];
	$inputIdjenisValue 	= $arsip['id_jenis'];
	$inputIdusersValue 	= $arsip['id_users'];
	$inputIdsatuanValue = $arsip['id_satuan'];
	$inputLokasiValue 	= $arsip['lokasi'];
	
} else {
	
	$inputNamaValue 	= set_value('nama_arsip');
	$inputFileValue 	= set_value('file_arsip');
	$inputStatusValue 	= set_value('status');
	$inputJumlahValue 	= set_value('jumlah');
	$inputIdjenisValue 	= set_value('id_jenis');
	$inputIdusersValue 	= set_value('id_users');
	$inputIdsatuanValue = set_value('id_satuan');
	$inputLokasiValue 	= set_value('lokasi');
	$inputStatusValue 	= set_value('status');
}
?>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/ars_data') ?>">Data Arsip</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<!-- Main content -->
	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($arsip)) { ?>
							<input type="hidden" name="id_arsip" value="<?php echo $arsip['id_arsip']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
							<input type="hidden" name="status" value=" <?php echo  $arsip['status']; ?>">
							
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php if ($this->session->userdata('uroleid') == '1'){?>
							<div class="form-group">
								<label>Status<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
								<select required="" name="status" class="form-control">
									<option value="">-Pilih Status Arsip-</option>
									<option value="<?php echo $row['status']; ?>" selected ><?php echo ($inputStatusValue == 1 ) ? 'Terverifikasi' : 'Belum Terverifikasi' ?></option>
									<option value="1">Verifikasi</option>
									<option value="0">Batal Verifikasi</option>
								</select>
							</div>
						<?php }else{?>
							<input type="hidden" name="status" value="0">
						<?php }  ?>

						
						<div class="form-group">
							<label>Jenis Arsip<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="id_jenis" class="form-control">
							    <option value="">-Pilih Jenis Arsip-</option>
							    <?php foreach($jenis as $row){?>
							        <option value="<?php echo $row['id_jenis']; ?>" <?php echo ($inputIdjenisValue == $row['id_jenis']) ? 'selected' : '' ?>><?php echo $row['jenis_arsip'] ?></option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Nama Arsip <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="nama_arsip" type="text" class="form-control" value="<?php echo $inputNamaValue ?>" placeholder="Nama Arsip">
						</div>
						<div class="form-group">
							<label>Jumlah Arsip<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="jumlah" type="text" class="form-control" value="<?php echo $inputJumlahValue ?>" placeholder="Jumlah Arsip">
						</div>
						<div class="form-group">
							<label>Satuan Arsip<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="id_satuan" class="form-control">
							    <option value="">-Pilih Satuan Arsip-</option>
							    <?php foreach($satuan as $row){?>
							        <option value="<?php echo $row['id_satuan']; ?>" <?php echo ($inputIdsatuanValue == $row['id_satuan']) ? 'selected' : '' ?>><?php echo $row['nama_satuan'] .' ('.$row['keterangan'].') ' ?></option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Lokasi Arsip<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="lokasi" class="form-control">
							    <option value="">-Pilih Lokasi Arsip-</option>
							    <?php foreach($lokasi as $row){?>
							        <option value="<?php echo $row['id_lokasi']; ?>" <?php echo ($inputLokasiValue == $row['id_lokasi']) ? 'selected' : '' ?>><?php echo $row['nama_lokasi'] ?></option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group">
							<a href="#" class="thumbnail">
								<?php if (isset($arsip['file_arsip']) != NULL) { 
									$p=  $arsip['file_arsip']; 
									$explode = explode(".", $p);
									$ext = $explode[1];
									if ($ext=='jpg'){
										?><img src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>" class="img-responsive avatar"><?php
									}else if($ext=='png'){
										?><img src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>" class="img-responsive avatar"><?php
									}else{
										?><iframe src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>" width="100%" height="400px"></iframe>
										<a src="<?php echo upload_url('arsip/' . $arsip['file_arsip']) ?>"></a><?php
									}?>
								<?php } else { ?>
								<img id="target" alt="Choose image to upload">
								<?php } ?>
							</a>
							<label>File Arsip <small data-toggle="tooltip" title="Wajib diisi">*</small><br><small style="color:red;" data-toggle="tooltip" title="Wajib diisi">(File Upload harus berekstensi .PDF | .JPG | .JPEG | .PNG)</small></label>
							<input type="file" name="upl_arsip" id="upl_arsip" class="form-control" >
						</div>
						
						<p class="text-muted">*) Kolom wajib diisi.</p>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
			<div class="col-md-3">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
						<a href="<?php echo site_url('manage/ars_data'); ?>" class="btn btn-block btn-info">Batal</a>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
		<!-- /.row -->
	</section>
</div>
<script>
	$("#upl_arsip").change(function() {
		readURL(this);
	});
</script>