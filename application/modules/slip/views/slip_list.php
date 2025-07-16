<?php
$this->load->helper('pembulatan');
if (isset($gaji)) {
    $inputAccountValue          = $gaji['akun_account_id'];
	$inputIdEmpValue            = $gaji['employee_id'];
	$inputNipValue              = $gaji['employee_nip'];
	$inputNameValue             = $gaji['employee_name'];
	$inputMajorsValue           = $gaji['majors_short_name'];
	$inputPositionValue         = $gaji['position_name'];
	$inputPokokValue            = $gaji['premier_pokok'];
	$inputFungsionalValue       = $gaji['premier_fungsional'];
	$inputStrukturalValue       = $gaji['premier_struktural'];
	$inputIstriValue            = $gaji['premier_istri'];
	$inputKhususValue           = $gaji['premier_khusus'];
	$inputOrangTuaValue         = $gaji['premier_ortu'];
	$inputAnakValue             = $gaji['premier_anak'];
	$inputPensiunanValue        = $gaji['potongan_pensiunan'];
	$inputOrganisasiValue       = $gaji['potongan_organisasi'];
	$inputKoperasiValue         = $gaji['potongan_koperasi'];
	$inputMajalahValue          = $gaji['potongan_majalah'];
	$inputBmhValue              = $gaji['potongan_bmh'];
	$inputRumdinValue           = $gaji['potongan_rumdin'];
	$inputSosialValue           = $gaji['potongan_sosial'];
	$inputAngsuranBankValue     = $gaji['potongan_angsuran_bank'];
	$inputGedungDakwahValue     = $gaji['potongan_gedung_dakwah'];
} else {
    $inputAccountValue          = set_value('akun_account_id');
	$inputIdEmpValue            = set_value('employee_id');
	$inputNipValue              = set_value('employee_nip');
	$inputNameValue             = set_value('employee_name');
	$inputMajorsValue           = set_value('majors_short_name');
	$inputPositionValue         = set_value('position_name');
	$inputPokokValue            = set_value('premier_pokok');
	$inputFungsionalValue       = set_value('premier_fungsional');
	$inputStrukturalValue       = set_value('premier_struktural');
	$inputIstriValue            = set_value('premier_istri');
	$inputKhususValue           = set_value('premier_khusus');
	$inputOrangTuaValue         = set_value('premier_ortu');
	$inputAnakValue             = set_value('premier_anak');
	$inputPensiunanValue        = set_value('potongan_pensiunan');
	$inputOrganisasiValue       = set_value('potongan_organisasi');
	$inputKoperasiValue         = set_value('potongan_koperasi');
	$inputMajalahValue          = set_value('potongan_majalah');
	$inputBmhValue              = set_value('potongan_bmh');
	$inputRumdinValue           = set_value('potongan_rumdin');
	$inputSosialValue           = set_value('potongan_sosial');
	$inputAngsuranBankValue     = set_value('potongan_angsuran_bank');
	$inputGedungDakwahValue     = set_value('potongan_gedung_dakwah');
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
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row"> 
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Filter Data Penggajian Pegawai</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
						<div class="form-group">
						    <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
							<div class="col-sm-2">
								<select class="form-control" name="n" id="th_ajar">
									<?php foreach ($period as $row): ?>
										<option <?php if (isset($f['n']) AND $f['n'] == $row['period_id']) {
										    echo 'selected';
										} else if (empty($f['n']) AND $periodActive['period_id'] == $row['period_id']) {
										    echo 'selected';
										} else {
										    echo '';
										} ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'].'/'.$row['period_end'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<label for="" class="col-sm-1 control-label">Bulan</label>
							<div class="col-sm-2">
								<select class="form-control" name="d" id="bulan">
									<?php foreach ($bulan as $row): ?>
										<option <?php if(isset($f['d']) AND $f['d'] == $row['month_id']) {echo 'selected';} else if(empty($f['d']) AND $monthActive == $row['month_id']) {echo 'selected';} else {echo '';} ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<label for="" class="col-sm-1 control-label">NIP</label>
							<div class="col-sm-4">
								<div class="input-group">
									<input type="text" class="form-control" autofocus name="r" id="employee_nip" <?php echo (isset($f['r'])) ? 'placeholder="'.$f['r'].'" value="'.$f['r'].'"' : 'placeholder="NIP Pegawai"' ?> required>
									<span class="input-group-btn">
										<button class="btn btn-success" type="submit">Cari</button>
									</span>
									<span class="input-group-btn">
									</span>
									<span class="input-group-btn">
									</span>
                					<span class="input-group-btn">
                					    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#dataPegawai"><b>Data Pegawai</b></button>
                					</span>
								</div>
							</div>
						</div>
					</form>
				</div><!-- /.box-body -->
			</div><!-- /.box -->

			<?php if ($f) { ?>

				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Informasi Pegawai</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-9">
							<table class="table table-striped">
								<tbody>
									<tr>
										<td width="200">Tahun Ajaran</td><td width="4">:</td>
										<?php foreach ($period as $row): ?>
											<?php echo (isset($f['n']) AND $f['n'] == $row['period_id']) ? 
											'<td><strong>'.$row['period_start'].'/'.$row['period_end'].'<strong></td>' : '' ?> 
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>NIP</td>
										<td>:</td>
										<?php foreach ($employee as $row): ?>
											<?php echo (isset($f['n']) AND $f['r'] == $row['employee_nip']) ? 
											'<td>'.$row['employee_nip'].'</td>' : '' ?> 
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Nama Pegawai</td>
										<td>:</td>
										<?php foreach ($employee as $row): ?>
											<?php echo (isset($f['n']) AND $f['r'] == $row['employee_nip']) ? 
											'<td>'.$row['employee_name'].'</td>' : '' ?> 
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Unit Sekolah</td>
										<td>:</td>
										<?php foreach ($employee as $row): ?>
											<?php echo (isset($f['n']) AND $f['r'] == $row['employee_nip']) ?  
											'<td>'.$row['majors_name'].' ('.$row['majors_short_name'].')</td>' : '' ?> 
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Jabatan</td>
										<td>:</td>
										<?php foreach ($employee as $row): ?>
											<?php echo (isset($f['n']) AND $f['r'] == $row['employee_nip']) ? 
											'<td>'.$row['position_name'].'</td>' : '' ?> 
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Pendidikan Terakhir</td>
										<td>:</td>
										<?php foreach ($employee as $row): ?>
											<?php echo (isset($f['n']) AND $f['r'] == $row['employee_nip']) ? 
											'<td>'.$row['employee_strata'].'</td>' : '' ?> 
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Status Kepegawaian</td>
										<td>:</td>
										<?php foreach ($employee as $row){ ?>
											<?php if(isset($f['n']) AND $f['r'] == $row['employee_nip']) { 
											        if($row['employee_category']== 1){ 
											            echo '<td> Tetap </td>';
											         } else if($row['employee_category']== 2){
											            echo '<td> Tidak Tetap </td>';
											         } else{ echo '<td></td>'; 
											             
											         }
											     } else {
											     '<td></td>';
											     } ?> 
										<?php }; ?>
									</tr>
									<tr>
										<td>Masa Kerja</td>
										<td>:</td>
										<td>
										<?php foreach ($employee as $row): ?>
											<?php if(isset($f['n']) AND $f['r'] == $row['employee_nip']){
											$start = date_create($row['employee_start']);
												    if($row['employee_end']!='0000-00-00'){
												        $end = date_create($row['employee_end']);
												    }
												    else{
												        $end = date_create();
												    }
												        $interval = date_diff($start, $end);
												        echo $interval->y.' tahun';
												    } else {
												        echo '';
												    } ?> 
										<?php endforeach; ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-3">
							<?php foreach ($employee as $row): ?>
								<?php if (isset($f['n']) AND $f['r'] == $row['employee_nip']) { ?> 
									<?php if (!empty($row['employee_photo'])) { ?>
										<img src="<?php echo upload_url('employee/'.$row['employee_photo']) ?>" class="img-thumbnail img-responsive">
									<?php } else { ?>
										<img src="<?php echo media_url('img/user.png') ?>" class="img-thumbnail img-responsive">
									<?php } 
								} ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="row">

					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">History Penggajian</h3>
											<a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
							</div><!-- /.box-header -->
							
							<div class="box-body table-responsive">
							    <div class="over">
								<table class="table table-responsive table-bordered" style="white-space: nowrap;">
								    <tr class="info">
										<th>No. Referensi</th>
										<th>Tanggal</th>
										<th>Bulan</th>
										<th>Gaji (Kotor)</th>
										<th>Potongan</th>
										<th>Gaji (Bersih)</th>
										<th>Gaji Diterima</th>
										<th>Aksi</th>
									</tr>
									<?php 
									    foreach($history as $row){ 
									    if($row['gaji_month_id']>0 && $row['gaji_month_id']<7){
                                            $tahun = $row['period_start'];   
									    } else {
									        $tahun = $row['period_end'];
									    }
									?>
									<tr>
									    <td><?php echo $row['kredit_kas_noref'] ?></td>
									    <td><?php echo pretty_date($row['gaji_tanggal'],'d/m/Y',FALSE) ?></td>
									    <td><?php echo $row['month_name'].' '.$tahun ?></td>
									    <td>Rp <?php echo number_format($row['gaji_pokok'],0,",",".") ?></td>
									    <td>Rp <?php echo number_format($row['gaji_potongan'],0,",",".") ?></td><td>Rp <?php echo number_format($row['gaji_pokok']-$row['gaji_potongan'],0,",",".") ?></td>
									    <td>Rp <?php echo number_format($row['gaji_jumlah'],0,",",".") ?></td>
									    <td><a href="#delModal<?php echo $row['gaji_id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Slip"></i></a>
									    <a href="<?php echo site_url('manage/slip/print_slip/' . $row['gaji_id']) ?>" class="btn btn-success btn-xs" title="Cetak Slip" target="_blank"><i class="fa fa-print"></i></a>
									    </td>
									</tr>
									
										<div class="modal modal-default fade" id="delModal<?php echo $row['gaji_id']; ?>">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span></button>
															<h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
														</div>
														<div class="modal-body">
															<p>Apakah anda yakin akan menghapus data ini?</p>
														</div>
														<div class="modal-footer">
															<?php echo form_open('manage/slip/delete_history/' . $row['gaji_id']); ?>
                    										<input type="hidden" name="delPeriod" value="<?php echo $row['gaji_period_id']; ?>">
                    										<input type="hidden" name="delMonth" value="<?php echo $row['gaji_month_id']; ?>">
                    										<input type="hidden" name="delNIP" value="<?php echo $f['r']; ?>">
                    										<input type="hidden" name="noRef" value="<?php echo $row['kredit_kas_noref'] ?>">
															<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
															<button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
															<?php echo form_close(); ?>
														</div>
													</div>
													<!-- /.modal-content -->
												</div>
												<!-- /.modal-dialog -->
											</div>
									<?php } ?>
								</table>
							    </div>
						    </div>
						</div>
					</div>

				</div>



				<!-- List Tagihan Bulanan --> 
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Kelola Penggajian</h3>
					</div><!-- /.box-header -->
					<form action="<?php echo site_url('manage/slip/add_slip') ?>" method="post" target="_blank">
					<div class="box-body">
					    
					    <div class="row">
                    		<div class="col-md-3">
                    		    <label>No. Referensi</label>
                    			<input required="" name="kas_noref" id="kas_noref" class="form-control" value="<?php echo $noref ?>" readonly="">
                    		</div>
    					    <div class="col-md-3">
                    		    <label>Pembayaran Gaji Via *</label>
                    			<select required="" name="kas_account_id" id="kas_account_id" class="form-control">
                    			    <option value="">-- Pilih Akun Kas --</option>
                    			    <?php
                    			    foreach($dataKas as $row){
                    			    ?>
                                		<option value="<?php echo $row['account_id'] ?>" <?php echo($dataKasActive['account_id']==$row['account_id']) ? 'selected' : '' ?> > 
                                		<?php echo $row['account_code'].' - '.$row['account_description'];
                                		?>
                                		 </option>
                                	<?php	 
                        			 }
                    			    ?>
                    			</select>
                    		</div>
                    		<div class="col-md-3">
                    			<input type="hidden" name="employee_id" id="employee_id" class="form-control" value="<?php echo $inputIdEmpValue ?>" readonly="">
                    			<input type="hidden" name="kas_majors_id" id="kas_majors_id" class="form-control" value="<?php echo $majorsID ?>" readonly="">
                    			<?php if(isset($f['n'])){ ?>
					            <input type="hidden" name="kas_period" value="<?php echo $f['n']?>">
					            <?php } ?>
                    		</div>
				        </div>
                	    <br><br>
					    
					    <div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">Gaji Pokok</a></li>
								<li><a href="#tab_2" data-toggle="tab">Potongan</a></li>
								<li><a href="#tab_3" data-toggle="tab">Kalkulasi Gaji</a></li>
							</ul>
							<div class="tab-content">
							    
							    <div class="tab-pane active" id="tab_1">
									<?php
										foreach($data_gaji AS $row){
											?>
											<div class="form-group">
												<div class="row">
												<div class="col-md-3">
													<label><?= $row['gaji_setting_name']?> </label>
												</div>
												<div class="col-md-1"><label> = </label></div>
												<div class="col-md-3">
													<input type='hidden' name='name_gaji[]' value='<?= $row['gaji_setting_name'] ?>' >
													<input type='hidden' name='gaji_setting_id[]' value='<?= $row['gaji_setting_id']?>' >
													<input name="nominal_gaji[]" type="text" class="form-control" value="<?= ($row['gaji_setting_nominal']==0 ? '0' : $row['gaji_setting_nominal'])?>" readonly="" placeholder="">
												</div>
												</div>
											</div>
											
											<?php
										}
									?>
                					
                					<hr>
                					
                					<?php
                					    $sumPokok = $sumGaji['sumGaji'];
                					?>
                					
                					<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						<label>Gaji Pokok</label>
                						</div>
                					    <div class="col-md-1"><label>=</label></div>
                					    <div class="col-md-3">
                						<input type="text" class="form-control" value="<?php echo $sumPokok ?>" readonly="">
                						</div>
                					    </div>
                					</div>
                					
								</div>
								
								<div class="tab-pane" id="tab_2">

									<?php
										foreach($data_potongan AS $rows){
											?>
											<div class="form-group">
												<div class="row">
												<div class="col-md-3">
													<label><?= $rows['potongan_setting_name']?> </label>
												</div>
												<div class="col-md-1"><label> = </label></div>
												<div class="col-md-3">
													<input type='hidden' name='potongan_name[]' value='<?= $rows['potongan_setting_name'] ?>' >
													<input type='hidden' name='potongan_setting_id[]' value='<?= $rows['potongan_setting_id']?>' >
													<input name="potongan_nominal[]" type="text" class="form-control" value="<?= ($rows['potongan_setting_nominal']==0 ? '0' : $rows['potongan_setting_nominal'])?>" readonly="" placeholder="">
												</div>
												</div>
											</div>
											
											<?php
										}
									?>                					
								    <hr>
                					
                					<?php
                					    $sumPotongan = $sumPotongan['sumPotongan'];
                					?>
                					
                					<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						<label>Potongan</label>
                						</div>
                					    <div class="col-md-1"><label>=</label></div>
                					    <div class="col-md-3">
                						<input type="text" class="form-control" value="<?php echo $sumPotongan ?>" readonly="">
                						</div>
                					    </div>
                					</div>
								</div>
								
								<div class="tab-pane" id="tab_3">
								    <div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						    <label>Gaji Pokok </label>
                					    </div>
                					    <div class="col-md-1"><label> = </label></div>
                					    <div class="col-md-3">
                					        <input name="gaji" type="text" class="form-control" value="<?php echo $sumPokok ?>" readonly="" placeholder="Gaji Pokok">
                					    </div>
                					    </div>
                					</div>
                
                					<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						<label>Potongan </label>
                					    </div>
                					    <div class="col-md-1"><label> = </label></div>
                					    <div class="col-md-3">
                						<input name="potongan" type="text" class="form-control" value="<?php echo $sumPotongan ?>" readonly="" placeholder="Tunjangan Struktural">
                					    </div>
                					    </div>
                					</div>
                					
                					<hr>
                					
                					<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						<label>Jumlah Gaji</label>
                						</div>
                					    <div class="col-md-1"><label>=</label></div>
                					    <div class="col-md-3">
                						<input name="jumlah_gaji" id="jumlah_gaji" type="text" class="form-control" value="<?php echo $sumPokok-$sumPotongan ?>" readonly="">
                						</div>
                					    </div>
                					</div>
                					
                					<hr>
                					
                					<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						<label>Pembulatan Gaji</label>
                						</div>
                					    <div class="col-md-1"><label>=</label></div>
                					    <div class="col-md-3">
                						<input name="pembulatan_gaji" id="pembulatan_gaji" type="text" class="form-control" value="<?php echo bulat_ratusan($sumPokok-$sumPotongan) ?>" readonly="">
                						</div>
                					    </div>
                					</div>
                					
                					<hr>
                					
                					<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-3">
                						<label>Catatan</label>
                						</div>
                					    <div class="col-md-1"><label>=</label></div>
                					    <div class="col-md-4">
                						<textarea name="catatan_gaji" class="form-control" cols="4" rows="3" id="comment"></textarea>
                						</div>
                					    </div>
                					</div>
                					<?php 
                					    
                            			$nip       = $f['r'];
                            			$month     = $f['d'];
                            			$period    = $f['n'];
                            			
                            			$cekGaji = $this->db->query("SELECT count(gaji_id) as num FROM gaji LEFT JOIN employee ON gaji.gaji_employee_id = employee.employee_id WHERE employee_nip = '$nip' 
                            			                            AND gaji_period_id = '$period' AND gaji_month_id = '$month'")->row_array();
                            			                            
                            			if($cekGaji['num'] == 0){
                            			    // echo "alert('Slip Gaji Sudah Pernah Dibuat')";
                            			
                					?>
									<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-4">
                						<label></label>
                						</div>
                					    <div class="col-md-3">
                            	        <button type="submit" class="btn btn-block btn-success">Cetak Slip Gaji</button>
                						</div>
                					    </div>
                					</div>
                					<?php
                            			} else { 
                            		?>	 
                            		<div class="form-group">
                					    <div class="row">
                					    <div class="col-md-4">
                						<label></label>
                						</div>
                					    <div class="col-md-3">
                            	        <button type="button" class="btn btn-block btn-default"  disabled>Slip Gaji Sudah Pernah Dibuat</button>
                						</div>
                					    </div>
                					</div>
                            		<?php
                            			}
                					?>
							</div>
						</div>
    					<?php if (isset($gaji)) { ?>
    						<input type="hidden" name="employee_id" value="<?php echo $gaji['employee_id']; ?>">
    						<input type="hidden" name="gaji_account_id" value="<?php echo $gaji['akun_account_id']; ?>">
    						<input type="hidden" name="premier_id" value="<?php echo $gaji['premier_id']; ?>">
    						<input type="hidden" name="potongan_id" value="<?php echo $gaji['potongan_id']; ?>">
    					<?php } ?>
    					    <input type="hidden" name="period_id" value="<?php echo $f['n'] ?>">
							<input type="hidden" name="month_id" value="<?php echo $f['d'] ?>">
    
						<p class="text-muted">*) Isi catatan jika diperlukan.</p>
					</div>
					</form>
				</div>
				<?php } ?>
			</section>
		</div>
		
		<div class="modal fade" id="dataPegawai" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Cari Data Pegawai</h4>
				</div>
				<div class="modal-body">
    <?php $dataPegawai = $this->Employees_model->get(array('status'=>'1'));
      
      echo '
            <div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
							    <thead>
								<tr>
									<th>No</th>
									<th>NIP</th>
									<th>Nama</th>
									<th>Unit Sekolah</th>
									<th>Jabatan</th>
									<th>Aksi</th>
								</tr>
								</thead>
								<tbody>';
									if (!empty($dataPegawai)) {
										$i = 1;
										foreach ($dataPegawai as $row):
						               echo '<tr>
												<td>'.
												$i
												.'</td>
												<td>'.
												$row['employee_nip']
												.'</td>
												<td>'.
												$row['employee_name']
												.'</td>
												<td>'.
												$row['majors_short_name']
												.'</td>
												<td>'.
												$row['position_name']
												.'</td>';
										echo '<td align="center">';
                                        echo '<button type="button" data-dismiss="modal" class="btn btn-primary btn-xs" onclick="ambil_data(';
                                        echo "'".$row['employee_nip']."'";
                                        echo ')">Pilih</button>';
                                        echo '</td>';
										echo '</tr>';
											$i++;
										endforeach;
									} else {
									echo '<tr id="row">
											<td colspan="8" align="center">Data Kosong</td>
										</tr>';
									    }
							echo	'</tbody>
								</table>
							</div>
      '; ?>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    				</div>
    			</div>
    		</div>
    	</div>
	</div>
	
<script>
    function ambil_data(nip){
        var nip      = nip;
        var bulan    = $("#bulan").val();
        var tahun    = $("#th_ajar").val();
        
        window.location.href = '<?php echo base_url()?>manage/slip?n='+tahun+'&d='+bulan+'&r='+nip;
        
      }
    
    function get_kelas(){
	    var id_majors    = $("#majors_id").val();
	    
        $.ajax({ 
            url: '<?php echo base_url();?>manage/payout/get_kelas',
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
	}
	
	function get_student(){
	    var id_majors       = $("#majors_id").val();
	    var id_kelas        = $("#class_id").val();
	    var student_name    = $("#student_name").val();
	    
        $.ajax({ 
            url: '<?php echo base_url();?>manage/payout/get_student',
            type: 'POST', 
            data: {
                    'id_majors'   : id_majors,
                    'id_kelas'    : id_kelas,
                    'student_name': student_name,
            },    
            success: function(msg) {
                    $("#div_data").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
	}
</script>

<script type="text/javascript">
function startCalculate(){
    interval=setInterval("Calculate()",10);
}

function Calculate() {
	var numberHarga = $('#harga').val(); // a string
	numberHarga = numberHarga.replace(/\D/g, '');
	numberHarga = parseInt(numberHarga, 10);

	var numberBayar = $('#bayar').val(); // a string
	numberBayar = numberBayar.replace(/\D/g, '');
	numberBayar = parseInt(numberBayar, 10);

	var total = numberBayar - numberHarga;
	$('#kembalian').val(total);
}

function stopCalc(){
	clearInterval(interval);
}
</script>

<script>
$(document).ready(function() {
	$("#selectall").change(function() {
		$(".checkbox").prop('checked', $(this).prop("checked"));
	});
});
</script>

<script type="text/javascript">
(function(a){
	a.createModal=function(b){
		defaults={
			title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false
		};
		var b=a.extend({},defaults,b);
		var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";
		html='<div class="modal fade" id="myModal">';
		html+='<div class="modal-dialog">';
		html+='<div class="modal-content">';
		html+='<div class="modal-header">';
		html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
		if(b.title.length>0){
			html+='<h4 class="modal-title">'+b.title+"</h4>"
		}
		html+="</div>";
		html+='<div class="modal-body" '+c+">";
		html+=b.message;
		html+="</div>";
		html+='<div class="modal-footer">';
		if(b.closeButton===true){
			html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
		}
		html+="</div>";
		html+="</div>";
		html+="</div>";
		html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){
			a(this).remove()})}})(jQuery);

/*
* Here is how you use it
*/
$(function(){    
	$('.view-cicilan').on('click',function(){
		var link = $(this).attr('href');      
		var iframe = '<object type="text/html" data="'+link+'" width="100%" height="350">No Support</object>'
		$.createModal({
			title:'Lihat Pembayaran/Ciiclan',
			message: iframe,
			closeButton:true,
			scrollable:false
		});
		return false;        
	});    
});
</script>
<style>
    div.over {
        width: 1083px;
        height: 165px;
        overflow: scroll;
    }
    
    div.extended {
        width: 1500px;
        height: 200px;
        overflow: scroll;
    }
</style>