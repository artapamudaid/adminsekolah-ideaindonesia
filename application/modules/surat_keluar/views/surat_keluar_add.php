<?php

if (isset($surat_keluar)) {

	$inputIdjenisValue 		= $surat_keluar['id_jenis_surat'];
	$inputTujuanValue 		= $surat_keluar['tujuan'];
	$inputNosuratValue 		= $surat_keluar['no_surat'];
	$inputIsiValue 			= $surat_keluar['isi'];
	$inputKodeValue 		= $surat_keluar['kode'];
	$inputTglsuratValue		= $surat_keluar['tgl_surat'];
	$inputFileValue 		= $surat_keluar['file'];
	$inputKeteranganValue 	= $surat_keluar['keterangan'];
	$inputIduserValue 		= $surat_keluar['id_user'];
	
} else {
	
	$inputIdjenisValue 		= set_value('id_jenis_surat');
	$inputTujuanValue 		= set_value('tujuan');
	$inputNosuratValue 		= set_value('no_surat');
	$inputIsiValue 			= set_value('isi');
	$inputKodeValue 		= set_value('kode');
	$inputTglsuratValue		= set_value('tgl_surat');
	$inputFileValue 		= set_value('file');
	$inputKeteranganValue 	= set_value('keterangan');
	$inputIduserValue 		= set_value('id_user');
}
?>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/surat_keluar') ?>">Surat Keluar</a></li>
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
						<?php if (isset($surat_keluar)) { ?>
							<input type="hidden" name="id_surat" value="<?php echo $surat_keluar['id_surat']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
							<input type="hidden" name="status" value=" <?php echo  $surat_keluar['status']; ?>">
							
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<div class="form-group">
							<label>Tujuan Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tujuan" type="text" class="form-control" value="<?php echo $inputTujuanValue ?>" placeholder="Tujuan Surat">
						</div>
						<div class="form-group">
							<label>Jenis Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="id_jenis" id="id_jenis" class="form-control">
							    <option value="">-Pilih Jenis Surat-</option>
							    <?php foreach($jenis as $row){?>
							        <option value="<?php echo $row['id_jenis']; ?>" <?php echo ($inputIdjenisValue == $row['id_jenis']) ? 'selected' : '' ?>><?php echo $row['nama_jenis'].' | '.$row['kode_surat'] ?></option>
							    <?php } ?>
							</select>	
						</div>
						<div id="div_nosur">
							<label>Nomor Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="no_surat"  id="no_surat" type="text" class="form-control" readonly="" value="<?php echo $inputNosuratValue ?>" placeholder="Nomor Surat">
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
							<label>Tanggal Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tgl_surat" type="date" class="form-control" value="<?php echo $inputTglsuratValue ?>" placeholder="Tanggal Surat">
						</div>
						<div class="form-group">
							<a href="#" class="thumbnail">
								<?php if (isset($surat_keluar['file']) != NULL) { 
									$p=  $surat_keluar['file']; 
									$explode = explode(".", $p);
									$ext = $explode[1];
									if ($ext=='jpg'){
										?><img src="<?php echo upload_url('surat_keluar/' . $surat_keluar['file']) ?>" class="img-responsive avatar"><?php
									}else if($ext=='png'){
										?><img src="<?php echo upload_url('surat_keluar/' . $surat_keluar['file']) ?>" class="img-responsive avatar"><?php
									}else{
										?><iframe src="<?php echo upload_url('surat_keluar/' . $surat_keluar['file']) ?>" width="100%" height="400px"></iframe>
										<a src="<?php echo upload_url('surat_keluar/' . $surat_keluar['file']) ?>"></a><?php
									}?>
								<?php } else { ?>
								<img id="target" alt="Choose image to upload">
								<?php } ?>
							</a>
							<label>File Arsip <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
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
						<a href="<?php echo site_url('manage/surat_keluar'); ?>" class="btn btn-block btn-info">Batal</a>
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

	$("#id_jenis").change(function(e){
		var id_jenis    = $("#id_jenis").val();
		
		$.ajax({ 
			url: '<?php echo base_url();?>manage/surat_keluar/cari_nosur',
			type: 'POST', 
			data: {
					'id_jenis' : id_jenis,
			},    
			success: function(msg) {
					$("#div_nosur").html(msg);
			},
			error: function(msg){
					alert('msg');
			}
			
		});
		e.preventDefault();
	});
</script>