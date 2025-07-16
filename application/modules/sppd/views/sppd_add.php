<?php

if (isset($sppd)) {

	$inputNosppdValue 			= $sppd['no_sppd'];
	$inputDeskripsiValue 		= $sppd['deskripsi'];
	$inputPerintahidValue 		= $sppd['perintah_id'];
	$inputTransportasiValue 	= $sppd['transportasi'];
	$inputTmpBerangkatValue		= $sppd['tmp_berangkat'];
	$inputTujuanValue 			= $sppd['tmp_tujuan'];
	$inputLamaValue 			= $sppd['lama_perjalanan'];
	$inputBerangkatValue 		= $sppd['tgl_berangkat'];
	$inputKembaliValue 			= $sppd['tgl_kembali'];
	$inputDiperintahidValue 	= $sppd['diperintah_id'];
	$inputAnggotaidValue 		= $sppd['anggota_id'];
	$inputIntansiValue 			= $sppd['instansi_anggaran'];
	$inputMataanggaranValue 	= $sppd['mata_anggaran'];
	$inputDasarsuratValue 		= $sppd['dasar_surat'];
	$inputKeteranganValue 		= $sppd['keterangan'];
	
} else {
	
	$inputNosppdValue 			= set_value('no_sppd');
	$inputDeskripsiValue 		= set_value('deskripsi');
	$inputPerintahidValue 		= set_value('perintah_id');
	$inputTransportasiValue 	= set_value('transportasi');
	$inputTmpBerangkatValue		= set_value('tmp_berangkat');
	$inputTujuanValue 			= set_value('tmp_tujuan');
	$inputLamaValue 			= set_value('lama_perjalanan');
	$inputBerangkatValue 		= set_value('tgl_berangkat');
	$inputKembaliValue 			= set_value('tgl_kembali');
	$inputDiperintahidValue 	= set_value('diperintah_id');
	$inputAnggotaidValue 		= set_value('anggota_id');
	$inputIntansiValue 			= set_value('instansi_anggaran');
	$inputMataanggaranValue 	= set_value('mata_anggaran');
	$inputDasarsuratValue 		= set_value('dasar_surat');
	$inputKeteranganValue 		= set_value('keterangan');
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
			<li><a href="<?php echo site_url('manage/sppd') ?>">sppd</a></li>
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
						<?php if (isset($sppd)) { ?>
							<input type="hidden" name="id_sppd" value="<?php echo $sppd['id_sppd']; ?>">
							<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						<?php } ?>
						
						<input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
						
						<div class="form-group">
							<label>Kode Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="no_sppd" type="text" class="form-control" value="<?php echo $no_sppd; ?>" readonly='' placeholder="Kode Surat">
						</div>
						<div class="form-group">
							<label>Pejabat Pemberi Perintah Tugas <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="perintah_id" class="form-control">
							    <option value="">-Pilih Pemberi Tugas-</option>
							    <?php foreach($emp_petugas as $row){?>
							        <option value="<?php echo $row['employee_id']; ?>" <?php echo ($inputPerintahidValue == $row['employee_id']) ? 'selected' : '' ?>><?php echo $row['employee_name'] ?></option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Maksud Perjalanan Dinas<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="deskripsi" type="text" class="form-control" value="<?php echo $inputDeskripsiValue ?>" placeholder="Maksud Perjalanan Dinas">
						</div>
						<div class="form-group">
							<label>Angkutan Yang Digunakan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="transportasi" type="text" class="form-control" value="<?php echo $inputTransportasiValue ?>" placeholder="Angkutan Yang Digunakan">
						</div>
						<div class="form-group">
							<label>Tempat Berangkat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tmp_berangkat" type="text" class="form-control" value="<?php echo $inputTmpBerangkatValue ?>" placeholder="Tempat Berangkat">
						</div>
						<div class="form-group">
							<label>Tempat Tujuan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tmp_tujuan" type="text" class="form-control" value="<?php echo $inputTujuanValue ?>" placeholder="Tempat Tujuan">
						</div>
						<div class="form-group">
							<label>Tanggal Berangkat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tgl_berangkat" type="date" class="form-control" value="<?php echo $inputBerangkatValue ?>" placeholder="Tanggal Kembali">
						</div>
						<div class="form-group">
							<label>Tanggal Kembali<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="tgl_kembali" type="date" class="form-control" value="<?php echo $inputKembaliValue ?>" placeholder="Tanggal Kembali">
						</div>
						<div class="form-group">
							<label>Pejabat yang diberi Tugas<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<select required="" name="diperintah_id" class="form-control">
							    <option value="">-Pilih Pejabat yang diberi Tugas-</option>
							    <?php foreach($emp_petugas as $row){?>
							        <option value="<?php echo $row['employee_id']; ?>" <?php echo ($inputDiperintahidValue == $row['employee_id']) ? 'selected' : '' ?>><?php echo $row['employee_name'] ?></option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group">
							<div id="p_scents_pengikut">
								<p>
								<label>Pengikut<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
								<select required="" name="pengikut_id[]" class="form-control">
									<option value="">-Pilih Pengikut-</option>
									<?php foreach($emp_petugas as $row){?>
										<option value="<?php echo $row['employee_id']; ?>" ><?php echo $row['employee_name'] ?></option>
									<?php } ?>
								</select>
								</p>
							</div>
							<h6 ><a href="#" class="btn btn-xs btn-success" id="addScnt_pengikut"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
						</div>
						<div class="form-group">
							<label>Instansi (Pembebanan Anggaran)<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="instansi_anggaran" type="text" class="form-control" value="<?php echo $inputIntansiValue ?>" placeholder="Instansi (Pembebanan Anggaran)">
						</div>
						<div class="form-group">
							<label>Mata Anggaran<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="mata_anggaran" type="text" class="form-control" value="<?php echo $inputMataanggaranValue ?>" placeholder="Mata Anggaran">
						</div>
						<div class="form-group">
							<label>Dasar Surat<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input name="dasar_surat" type="text" class="form-control" value="<?php echo $inputDasarsuratValue ?>" placeholder="Dasar Surat">
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
						<a href="<?php echo site_url('manage/sppd'); ?>" class="btn btn-block btn-info">Batal</a>
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
<script>
$(function() {
	var scntDiv = $('#p_scents_pengikut');
	var i = $('#p_scents_pengikut p').size() + 1;

	$("#addScnt_pengikut").click(function() {
		$('<p><label>Pengikut<small data-toggle="tooltip" title="Wajib diisi">*</small></label><select required="" name="pengikut_id[]" class="form-control"><option value="">-Pilih Pengikut-</option><?php foreach($emp_petugas as $row){?><option value="<?php echo $row['employee_id']; ?>" ><?php echo $row['employee_name'] ?></option><?php } ?></select><a href="#" class="btn btn-xs btn-danger remScnt_satuan">Hapus Baris</a></p>').appendTo(scntDiv);
		i++;
		return false;
	});

	$(document).on("click", ".remScnt_satuan", function() {
		if (i > 2) {
			$(this).parents('p').remove();
			i--;
		}
		return false;
	});
});
</script>