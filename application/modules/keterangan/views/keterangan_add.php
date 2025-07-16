<?php

if (isset($keterangan)) {

	$inputKetIsiValue 		= $keterangan['keterangan_isi'];
	$inputKetNamaValue 		= $keterangan['keterangan_nama'];
	
} else {
	
	$inputKetIsiValue 		= set_value('keterangan_isi');
	$inputKetNamaValue 		= set_value('keterangan_nama');
}
?>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/keterangan') ?>">keterangan</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<!-- Main content -->
	<section class="content">
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="form-group">
							<label>Cara Pengisian <small data-toggle="tooltip" title="Wajib diisi">*</small></label><br>
							Untuk Memasukkan data nama sekolah, nama siswa, kelas dll bisa menggunakan kode yang sudah disediakan dengan mengetikan <b style="color:red;">kode tanda bintang (*) setelah/sesudah nya dan diantara tanda bintang tersebut diselipkan nama kodenya.</b> <br>
							Misalnya akan memasukan <b style="color:red;">nama siswa</b> maka kodenya yaitu <b style="color:red;">siswa</b> maka bisa di tulis <b style="color:red;"><i>*siswa*</i></b>. <br><br>
							<small data-toggle="tooltip" title="Wajib diisi"><b>* </b></small>Untuk Kop sekolah Bisa upload file kop surat dalam bentuk .png agar lebih transparant dengan ukuran 2076 X 322 Pixel. <br>
							<small data-toggle="tooltip" title="Wajib diisi"><b>* </b></small>Untuk lebih jelasnya dalam pengisiannya dapat melihat pada contoh pengisian. <br>
							<small data-toggle="tooltip" title="Wajib diisi"><b>* </b></small>Untuk data kode pengisian bisa check pada tabel kode pengisian.
						</div>
						<div class="form-group">
							<label>Contoh Pengisian <small data-toggle="tooltip" title="Wajib diisi">*</small></label><br>
							Assalamualaikum Warahmatullahi Wabarakatuh  Ayah Bunda, <br>

							Kami akan mengirimkan invoice <b><i>*sekolah*</i></b> a/n  <b><i>*siswa*</i></b> , Kelas  <b><i>*kelas*</i></b>  <b><i>*sekolah*</i></b>, sudah kami terima. Atas perhatiannya kami ucapkan terimakasih. Semoga Allah SWT memudahkan segala urusan kita. <br><br>
						</div>
						<div class="form-group">
							<label>Hasil Pengisian <small data-toggle="tooltip" title="Wajib diisi">*</small></label><br>
							Assalamualaikum Warahmatullahi Wabarakatuh  Ayah Bunda, <br>
							Kami akan mengirimkan invoice SMP Negeri Kediri a/n Azkafa Tsaqif Athaillah , Kelas Indoweb SMP Negeri Kediri, sudah kami terima. Atas perhatiannya kami ucapkan terimakasih. Semoga Allah SWT memudahkan segala urusan kita. <br><br>
						</div>
						<div class="box-body table-responsive">
							<label>Tabel Pengisian <small data-toggle="tooltip" title="Wajib diisi">*</small></label>

							<table id="dtable" class="table table-hover">
								<thead>
									<tr>
										<th>No.</th>
										<th>Nama Pengisian</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									foreach ($kode as $row):
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row['kode_pengisian_name']; ?></td>
											<td><?php echo $row['kode_pengisian_keterangan']; ?></td>
										</tr>
										
										<?php
										$i++;
									endforeach;
									
									?>
								</tbody>
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<?php if (isset($keterangan)) { ?>
							<input type="hidden" name="keterangan_id" value="<?php echo $keterangan['keterangan_id']; ?>">
						<?php } ?>

						<div class="form-group">
							<label>Nama Jenis Keterangan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<input type="text" name="keterangan_nama" class="form-control" value="<?php echo $inputKetNamaValue ?>">
						</div>

						<div class="form-group">
							<label>Upload Kop Surat Keterangan <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
							<label for=""><img src="" alt="">
							<img src="<?php echo media_url('template_kop/'.($keterangan['keterangan_kop'] != NULL ? $keterangan['keterangan_kop'] : 'kop_surat.png')) ?>" style="height: 110px;"></label>
							<input type="file" name="keterangan_kop" class="form-control" >
						</div>
						
						<div class="form-group">
							<label>Isi Jenis Surat Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                    		<textarea class="ckeditor form-control" name="keterangan_isi" style="height: 5000px;" ><?php echo $inputKetIsiValue ?></textarea>
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
						<a href="<?php echo site_url('manage/keterangan'); ?>" class="btn btn-block btn-info">Batal</a>
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