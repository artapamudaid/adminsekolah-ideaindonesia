<?php

if (isset($barang)) {

	$inputNoBarangValue 		= $barang['barang_no'];
	$inputBarangNameValue 		= $barang['barang_name'];
	$inputStokValue 			= $barang['barang_stok'];
	$inputSatuanIdValue			= $barang['satuan_id'];
	$inputKeteranganValue		= $barang['keterangan'];
	$inputGambarValue 			= $barang['gambar'];
	
} else {
	
	$inputNoBarangValue 		= set_value('barang_no');
	$inputBarangNameValue 		= set_value('barang_name');
	$inputStokValue	 			= set_value('barang_stok');
	$inputSatuanIdValue			= set_value('satuan_id');
	$inputKeteranganValue		= set_value('keterangan');
	$inputGambarValue 			= set_value('gambar');
}
?>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/barang') ?>">sppd</a></li>
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
						<?php if (isset($barang)) { ?>
							<input type="hidden" name="barang_id" value="<?php echo $barang['barang_id']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Barang<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="barang_no" type="text" class="form-control" value="<?php echo $barang_no; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Nama Barang<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="barang_name" type="text" class="form-control" value="<?php echo $inputBarangNameValue ?>" placeholder="Nama Barang">
						</div>
						<div class="form-group">
							<label>Stok Barang<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="barang_stok" type="text" class="form-control" value="<?php echo $inputStokValue ?>" placeholder="Stok Barang">
						</div>	
						<div class="form-group">
							<label>Satuan Barang<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="satuan_id" id="satuan_id" class="form-control satuan_barang_id">
							    <option value="">-Pilih Satuan-</option>
								<?php
								foreach($satuan as $row):
								?>
								<option value="<?php echo $row['satuan_barang_id']; ?>" <?php echo ($inputSatuanIdValue == $row['satuan_barang_id']) ? 'selected' : '' ?>><?php echo $row['nama_satuan'] ?></option>
								<?php
								endforeach;
								?>
							</select>
						</div>		
						<div class="form-group">
							<label>Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="keterangan" type="text" class="form-control" value="<?php echo $inputKeteranganValue ?>" placeholder="Keterangan">
						</div>					
						<div class="form-group">
							<a href="#" class="thumbnail">
								<?php if (isset($barang['gambar']) != NULL) { 
									$p=  $barang['gambar']; 
									$explode = explode(".", $p);
									$ext = $explode[1];
									if ($ext=='jpg'){
										?><img width="100px" src="<?php echo upload_url('barang/' . $barang['gambar']) ?>" class="img-responsive avatar"><?php
									}else if($ext=='png'){
										?><img width="100px" src="<?php echo upload_url('barang/' . $barang['gambar']) ?>" class="img-responsive avatar"><?php
									}else{
										?><iframe src="<?php echo upload_url('barang/' . $barang['gambar']) ?>" width="100%" height="400px"></iframe>
										<a src="<?php echo upload_url('barang/' . $barang['gambar']) ?>"></a><?php
									}?>
								<?php } else { ?>
								<img id="target" alt="Choose image to upload">
								<?php } ?>
							</a>
							<label>Gambar <small data-toggle="tooltip" title="Wajib diisi">*</small><br><small style="color:red;" data-toggle="tooltip" title="Wajib diisi">(Gambar Upload harus berekstensi .JPG | .JPEG | .PNG)</small></label>
							<input type="file" name="gambar" id="gambar" class="form-control" >
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
						<a href="<?php echo site_url('manage/barang'); ?>" class="btn btn-block btn-info">Batal</a>
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
	$("#upl_barang").change(function() {
		readURL(this);
	});
</script>
</script> 
<script>
	$(document).ready(function() {
		$('.satuan_barang_id').select2();
	});
</script>