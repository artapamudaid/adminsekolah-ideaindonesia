<?php

if (isset($employee)) {
    $inputAccountValue          = $employee['akun_account_id'];
	$inputIdEmpValue            = $employee['employee_id'];
	$inputNipValue              = $employee['employee_nip'];
	$inputNameValue             = $employee['employee_name'];
	$inputMajorsValue           = $employee['majors_short_name'];
	$inputPositionValue         = $employee['position_name'];
} else {
    $inputAccountValue          = set_value('akun_account_id');
	$inputIdEmpValue            = set_value('employee_id');
	$inputNipValue              = set_value('employee_nip');
	$inputNameValue             = set_value('employee_name');
	$inputMajorsValue           = set_value('majors_short_name');
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
			<li><a href="<?php echo site_url('manage/penggajian') ?>">Manage Penggajian</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<!-- Main content -->
	<section class="content">
	    <div class="row">
			<div class="col-md-9">
    	        <div class="box">
				<table class="table">
    	               <tr>
    	                   <td width="200">Unit</td>
    	                   <td width="4">:</td>
    	                   <td><?php echo $inputMajorsValue ?></td>
    	               </tr>
    	               <tr>
    	                   <td>NIP</td>
    	                   <td>:</td>
    	                   <td><?php echo $inputNipValue ?></td>
    	               </tr>
    	               <tr>
    	                   <td>Nama</td>
    	                   <td>:</td>
    	                   <td><?php echo $inputNameValue ?></td>
    	               </tr>
    	               <tr>
    	                   <td>Jabatan</td>
    	                   <td>:</td>
    	                   <td><?php echo $inputPositionValue ?></td>
    	               </tr>
    	           </table>
    	        </div>
	        </div>
	    </div>
		<?php echo form_open_multipart(current_url()); ?>
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-9">
				<div class="box">
					<!-- /.box-header -->
					<div class="box-body">
					    		    
					    <div class="form-group">
    					    <div class="row">
    					    <div class="col-md-3">
    						<label>Akun Gaji<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
    						</div>
    					    <div class="col-md-1"><label> = </label></div>
    					    <div class="col-md-4">
    					    <select required="" name="gaji_account_id" class="form-control">
        							    <option value="">-Pilih Kode Akun-</option>
        							    <?php foreach($account as $row){?>
        							        <option value="<?php echo $row['account_id']; ?>" <?php echo ($inputAccountValue == $row['account_id']) ? 'selected' : '' ?>><?php echo $row['account_code'].' - '.$row['account_description'] ?></option>
        							    <?php } ?>
        					</select>
    					    </div>
    					    <div class="col-md-1">
    					    </div>
    					    </div>
    					</div>
    					
					    <div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">Gaji Pokok</a></li>
								<li><a href="#tab_2" data-toggle="tab">Potongan</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<input type='hidden' name='employee_id' value='<?= $inputIdEmpValue ?>' >
								    <?php
									$def_gaji = array();
									foreach ($def_penggajian as $row) :
										$def_gaji[] = $row;
									endforeach;
									
									foreach($def_gaji AS $default_gaji):
										?>
										<div class="form-group">
											<div class="row">
											<div class="col-md-3">
												<label><?= $default_gaji['default_gaji_name']?> <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
											</div>
											<div class="col-md-1"><label> = </label></div>
											<div class="col-md-3">
												<input type='hidden' name='gaji_name[]' value='<?= $default_gaji['default_gaji_name'] ?>' >
												<input type='hidden' name='gaji_default_id[]' value='<?= $default_gaji['default_gaji_id']?>' >
												<input type='hidden' name='gaji_majors_id[]' value='<?= $default_gaji['default_gaji_majors_id']?>' >
												<?php
													$default_id = $default_gaji['default_gaji_id'];
													$penggajian = $this->Penggajian_model->get_setting_gaji(array('gaji_setting_default_id'=> $default_id , 'employee_id'=> $inputIdEmpValue));
												?>
												<input type='hidden' name='gaji_setting_id[]' value='<?= $penggajian['gaji_setting_id']?>' >
												<input type="text" name="gaji_nominal[]" class="form-control" value="<?= ($penggajian['gaji_setting_default_id'] == $default_gaji['default_gaji_id'] ? $penggajian['gaji_setting_nominal'] : '0') ?>" required="" placeholder="<?= $penggajian['default_gaji_name']?>">
												
											</div>
											</div>
										</div>
										<?php
									endforeach;
									?>
                					
                					
								</div>
								
								<div class="tab-pane" id="tab_2">
								    
								<?php
									$def_potong = array();
									foreach ($def_potongan as $rows) :
										$def_potong[] = $rows;
									endforeach;
									
									foreach($def_potong AS $default_potongan):
										?>
										<div class="form-group">
											<div class="row">
											<div class="col-md-3">
												<label><?= $default_potongan['default_gaji_name']?> <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
											</div>
											<div class="col-md-1"><label> = </label></div>
											<div class="col-md-3">
												<input type='hidden' name='potongan_name[]' value='<?= $default_potongan['default_gaji_name'] ?>' >
												<input type='hidden' name='potongan_majors_id[]' value='<?= $default_potongan['default_gaji_majors_id']?>' >
												<input type='hidden' name='potongan_default_id[]' value='<?= $default_potongan['default_gaji_id']?>' >
												<?php
													$default_id = $default_potongan['default_gaji_id'];
													$potongan = $this->Penggajian_model->get_setting_potongan(array('potongan_setting_default_id'=> $default_id , 'employee_id'=> $inputIdEmpValue));
												?>
												<input type='hidden' name='potongan_setting_id[]' value='<?= $potongan['potongan_setting_id']?>' >
												<input type="text" name="potongan_nominal[]" class="form-control" value="<?= ($potongan['potongan_setting_default_id'] == $default_potongan['default_gaji_id'] ? $potongan['potongan_setting_nominal'] : '0') ?>" required="" placeholder="<?= $penggajian['default_potongan_name']?>">
												
											</div>
											</div>
										</div>
										<?php
									endforeach;
									?>
								    
								</div>
							</div>
						</div>
    					<?php if (isset($employee)) { ?>
    						<input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
    					<?php } ?>
    
						<p class="text-muted">*) Kolom wajib diisi.</p>
                	    <button type="submit" class="btn btn-md btn-success">Simpan</button>
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

	function getId(id) {
		$('#studentId').val(id)
	}
</script>