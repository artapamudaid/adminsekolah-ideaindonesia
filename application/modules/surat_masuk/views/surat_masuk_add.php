<?php

if (isset($surat_masuk)) {

	$inputAgendaValue 		= $surat_masuk['no_agenda'];
	$inputNosuratValue 		= $surat_masuk['no_surat'];
	$inputAsalValue 		= $surat_masuk['asal_surat'];
	$inputIsiValue 			= $surat_masuk['isi'];
	$inputKodeValue 		= $surat_masuk['kode'];
	$inputIndeksValue 		= $surat_masuk['indeks'];
	$inputTglsuratValue		= $surat_masuk['tgl_surat'];
	$inputTglDiterimaValue 	= $surat_masuk['tgl_diterima'];
	$inputFileValue 		= $surat_masuk['file'];
	$inputKeteranganValue 	= $surat_masuk['keterangan'];
	$inputIduserValue 		= $surat_masuk['id_user'];
	$inputDisposisiValue 	= $surat_masuk['disposisi'];
	
} else {
	
	$inputAgendaValue 		= set_value('no_agenda');
	$inputNosuratValue 		= set_value('no_surat');
	$inputAsalValue 		= set_value('asal_surat');
	$inputIsiValue 			= set_value('isi');
	$inputKodeValue 		= set_value('kode');
	$inputIndeksValue 		= set_value('indeks');
	$inputTglsuratValue		= set_value('tgl_surat');
	$inputTglDiterimaValue 	= set_value('tgl_diterima');
	$inputFileValue 		= set_value('file');
	$inputKeteranganValue 	= set_value('keterangan');
	$inputIduserValue 		= set_value('id_user');
	$inputDisposisiValue 	= set_value('disposisi');
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
			<li><a href="<?php echo site_url('manage/surat_masuk') ?>">Surat Masuk</a></li>
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
						<?php if (isset($surat_masuk)) { ?>
							<input type="hidden" name="id_surat" value="<?php echo $surat_masuk['id_surat']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
							<input type="hidden" name="status" value=" <?php echo  $surat_masuk['status']; ?>">
							
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>No. Agenda<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="no_agenda" type="text" class="form-control" value="<?php echo $inputAgendaValue ?>" placeholder="No. Agenda">
						</div>
						<div class="form-group">
							<label>Nomor Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="no_surat" type="text" class="form-control" value="<?php echo $inputNosuratValue ?>" placeholder="Nomor Surat">
						</div>
						<div class="form-group">
							<label>Asal Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="asal_surat" type="text" class="form-control" value="<?php echo $inputAsalValue ?>" placeholder="Asal Surat">
						</div>
						<div class="form-group">
							<label>Isi Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="isi" type="text" class="form-control" value="<?php echo $inputIsiValue ?>" placeholder="Isi Surat">
						</div>
						<div class="form-group">
							<label>Kode<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="kode" type="text" class="form-control" value="<?php echo $inputKodeValue ?>" placeholder="Kode">
						</div>
						<div class="form-group">
							<label>Indeks<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="indeks" type="text" class="form-control" value="<?php echo $inputIndeksValue ?>" placeholder="Indeks">
						</div>
						<div class="form-group">
							<label>Tanggal Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tgl_surat" type="date" class="form-control" value="<?php echo $inputTglsuratValue ?>" placeholder="Tanggal Surat">
						</div>
						<div class="form-group">
							<label>Tanggal Diterima<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tgl_diterima" type="date" class="form-control" value="<?php echo $inputTglDiterimaValue ?>" placeholder="Tanggal Diterima">
						</div>
						<div class="form-group">
							<a href="#" class="thumbnail">
								<?php if (isset($surat_masuk['file']) != NULL) { 
									$p=  $surat_masuk['file']; 
									$explode = explode(".", $p);
									$ext = $explode[1];
									if ($ext=='jpg'){
										?><img src="<?php echo upload_url('surat_masuk/' . $surat_masuk['file']) ?>" class="img-responsive avatar"><?php
									}else if($ext=='png'){
										?><img src="<?php echo upload_url('surat_masuk/' . $surat_masuk['file']) ?>" class="img-responsive avatar"><?php
									}else{
										?><iframe src="<?php echo upload_url('surat_masuk/' . $surat_masuk['file']) ?>" width="100%" height="400px"></iframe>
										<a src="<?php echo upload_url('surat_masuk/' . $surat_masuk['file']) ?>"></a><?php
									}?>
								<?php } else { ?>
								<img id="target" alt="Choose image to upload">
								<?php } ?>
							</a>
							<label>File Arsip <small data-toggle="tooltip" title="Wajib diisi">*</small><br><small style="color:red;" data-toggle="tooltip" title="Wajib diisi">(File Upload harus berekstensi .PDF | .JPG | .JPEG | .PNG)</small></label>
							<input type="file" name="file" id="file" class="form-control" >
						</div>
						<div class="form-group">
							<label>Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="keterangan" type="text" class="form-control" value="<?php echo $inputKeteranganValue ?>" placeholder="Keterangan">
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
						<a href="<?php echo site_url('manage/surat_masuk'); ?>" class="btn btn-block btn-info">Batal</a>
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