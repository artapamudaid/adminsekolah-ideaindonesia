<?php

if (isset($alumni)) {

	$inputFullnameValue = $alumni['alumni_full_name'];
	$inputClassValue = $alumni['alumni_kelas'];
	$inputMajorValue = $alumni['alumni_unit'];
	$inputNisValue = $alumni['alumni_nis'];
	$inputNisNValue = $alumni['alumni_nisn'];
	$inputPlaceValue = $alumni['alumni_born_place'];
	$inputDateValue = $alumni['alumni_born_date'];
	$inputParPhoneValue = $alumni['alumni_parent_phone'];
	$inputAddressValue = $alumni['alumni_address'];
	$inputGenderValue = $alumni['alumni_gender'];
	$inputMotherValue = $alumni['alumni_name_of_mother'];
	$inputFatherValue = $alumni['alumni_name_of_father'];
	$inputTahunValue = $alumni['alumni_tahun_id'];
} else {
	$inputFullnameValue = set_value('alumni_full_name');
	$inputClassValue = set_value('alumni_kelas');
	$inputMajorValue = set_value('alumni_unit');
	$inputNisValue = set_value('alumni_nis');
	$inputNisNValue = set_value('alumni_nisn');
	$inputPlaceValue = set_value('alumni_born_place');
	$inputDateValue = set_value('alumni_born_date');
	$inputParPhoneValue = set_value('alumni_parent_phone');
	$inputAddressValue = set_value('alumni_address');
	$inputGenderValue = set_value('alumni_gender');
	$inputMotherValue = set_value('alumni_name_of_mother');
	$inputFatherValue = set_value('alumni_name_of_father');
	$inputTahunValue = set_value('alumni_tahun_id');
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
			<li><a href="<?php echo site_url('manage/alumnis') ?>">Manage alumnis</a></li>
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
				<div class="box">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">Data Pribadi</a></li>
								<li><a href="#tab_2" data-toggle="tab">Data Sekolah</a></li>
								<li><a href="#tab_3" data-toggle="tab">Data Keluarga</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<?php echo validation_errors(); ?>
									<?php if (isset($alumni)) { ?>
										<input type="hidden" name="alumni_id" value="<?php echo $alumni['alumni_id']; ?>">
									<?php } ?>
									
									<div class="form-group">
										<label>Nama lengkap <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
										<input name="alumni_full_name" type="text" class="form-control" value="<?php echo $inputFullnameValue ?>" placeholder="Nama lengkap">
									</div>
									<div class="form-group">
										<label>Jenis Kelamin</label>
										<div class="radio">
											<label>
												<input type="radio" name="alumni_gender" value="L" <?php echo ($inputGenderValue == 'L') ? 'checked' : ''; ?>> Laki-laki
											</label>&nbsp;&nbsp;
											<label>
												<input type="radio" name="alumni_gender" value="P" <?php echo ($inputGenderValue == 'P') ? 'checked' : ''; ?>> Perempuan
											</label>
										</div>
									</div>

									<div class="form-group">
										<label>Tempat Lahir</label>
										<input name="alumni_born_place" type="text" class="form-control" value="<?php echo $inputPlaceValue ?>" placeholder="Tempat Lahir">
									</div>

									<div class="form-group">
										<label>Tanggal Lahir </label>
										<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
											<input class="form-control" type="text" name="alumni_born_date" readonly="readonly" placeholder="Tanggal" value="<?php echo $inputDateValue; ?>">
										</div>
									</div>
									<div class="form-group">
										<label>Alamat</label>
										<textarea class="form-control" name="alumni_address" placeholder="Alamat Tempat Tinggal"><?php echo $inputAddressValue ?></textarea>
									</div>
								</div>

								<div class="tab-pane" id="tab_2">
									<div class="form-group">
										<label>NIS <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
										<input name="alumni_nis" type="text" class="form-control" value="<?php echo $inputNisValue ?>" placeholder="NIS Siswa">
									</div> 

									<?php if (!isset($alumni)) { ?>
										<div class="form-group">
											<label>Password <small data-toggle="tooltip" title="Wajib diisi">Defaul :  <font color="red">123456</font></small></label>
											<input name="alumni_password" type="password" class="form-control" placeholder="Password">
										</div>            

										<div class="form-group">
											<label>Konfirmasi Password <small data-toggle="tooltip" title="Wajib diisi">Kosongi jika password kosong</small></label>
											<input name="passconf" type="password" class="form-control" placeholder="Konfirmasi Password">
										</div>       
									<?php } ?>

									<div class="form-group">
										<label>NISN <small data-toggle="tooltip" title="Wajib diisi"></small></label>
										<input name="alumni_nisn" type="text" class="form-control" value="<?php echo $inputNisNValue ?>" placeholder="NISN Siswa">
									</div>
									<?php //if ($setting_level['setting_value']=='senior') { ?>
										<div class="form-group">
											<label>Unit <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
											<select name="alumni_unit" id="alumni_unit" class="form-control">
												<option value=""> -- Pilih Unit-- </option>
												<?php foreach ($majors as $row): ?>
													<option value="<?php echo $row['majors_id'] ?>" <?php echo ($inputMajorValue == $row['majors_id']) ? 'selected' : '' ?> ><?php echo $row['majors_short_name'] ?></option>
												<?php endforeach ?>
											</select>
										</div> 
									<?php //} ?>
									<div id="div_kelas">
										<div class="form-group"> 
											<label >Kelas *</label>
											<select name="alumni_kelas" id="alumni_kelas" class="form-control">
												<option value=""> -- Pilih Kelas -- </option>
												<?php foreach ($class as $row): ?>
											    <option value="<?php echo $row['class_id'] ?>" <?php echo ($inputClassValue == $row['class_id']) ? 'selected' : '' ?> ><?php echo $row['class_name'] ?>
											    </option>
												<?php endforeach ?>
											</select>
									    </div>
								    </div>
									<!-- <div id="div_madin">
										<div class="form-group"> 
											<label >Kamar </label>
											<select name="alumni_madin" id="alumni_madin" class="form-control">
												<option value=""> Tidak Ada </option>
												<?php foreach ($madin as $row): ?>
    											<option value="<?php echo $row['madin_id'] ?>" <?php echo ($inputMadinValue == $row['madin_id']) ? 'selected' : '' ?> ><?php echo $row['madin_name'] ?>
    											</option>
												<?php endforeach ?>
											</select>
									    </div>
								    </div> -->
								    
								    <div class="form-group"> 
											<label >Tahun </label>
											<select name="alumni_tahun_id" id="alumni_tahun_id" class="form-control">
												<option value=""> Pilih Tahun </option>
												<?php foreach ($period as $row): ?>
    											<option value="<?php echo $row['period_id'] ?>" <?php echo ($inputTahunValue == $row['period_id']) ? 'selected' : '' ?> ><?php echo $row['period_end'] ?>
    											</option>
												<?php endforeach ?>
											</select>
									    </div>
									
								</div>
								<div class="tab-pane" id="tab_3">
									<div class="form-group">
										<label>Nama Ibu Kandung</label>
										<input name="alumni_name_of_mother" type="text" class="form-control" value="<?php echo $inputMotherValue ?>" placeholder="Nama Ibu">
									</div>
									<div class="form-group">
										<label>Nama Ayah Kandung</label>
										<input name="alumni_name_of_father" type="text" class="form-control" value="<?php echo $inputFatherValue ?>" placeholder="Nama Ayah">
									</div>
									<div class="form-group">
										<label>No. Handphone Orang Tua <small data-toggle="tooltip" title="Wajib diisi"></small></label>
										<input name="alumni_parent_phone" type="text" class="form-control" value="<?php echo $inputParPhoneValue ?>" placeholder="No Handphone Orang Tua">
									</div>
									
								</div>

							</div>
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
						<label >Foto</label>
						<a href="#" class="thumbnail">
							<?php if (isset($alumni['alumni_img']) != NULL) { ?>
								<img src="<?php echo upload_url('alumni/' . $alumni['alumni_img']) ?>" class="img-responsive avatar">
							<?php } else { ?>
								<img src="<?php echo media_url('img/missing.png') ?>" id="target" alt="Choose image to upload">
							<?php } ?>
						</a>
						<input type='file' id="alumni_img" name="alumni_img">
						<br>
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
						<a href="<?php echo site_url('manage/alumni'); ?>" class="btn btn-block btn-info">Batal</a>
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

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#target').attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#alumni_img").change(function() {
		readURL(this);
	});
	
</script>

<script>
	
	$("#alumni_unit").change(function(e){
	    var id_majors    = $("#alumni_unit").val();
	    
        $.ajax({ 
            url: '<?php echo base_url();?>manage/alumni/get_kelas',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#div_kelas").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
	$("#alumni_unit").change(function(e){
	    var id_majors    = $("#alumni_unit").val();
	    
        $.ajax({ 
            url: '<?php echo base_url();?>manage/alumni/get_madin',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#div_madin").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});

</script>