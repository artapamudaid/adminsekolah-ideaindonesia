
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
									<label>Semester</label>
									<select class="form-control" name="s" id="semester_id" onchange="get_bulan()"required="">
										<option value="">-- Pilih Semester --</option>
										<?php foreach ($semester as $row): ?>
											<option <?php echo (isset($q['s']) AND $q['s'] == $row['semester_id']) ? 'selected' : '' ?> value="<?php echo $row['semester_id'] ?>"><?php echo $row['semester_name'].' ('. $row['period_start'].'/'. $row['period_end'] .')'?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Mulai Tanggal</label>
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="ds" id="ds" value="<?php echo (isset($q['de'])) ? $q['de'] : ''  ?>" readonly="readonly" placeholder="Tanggal Awal">
									</div>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Tanggal Akhir</label>
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="de" id="de" value="<?php echo (isset($q['de'])) ? $q['de'] : ''  ?>" readonly="readonly" placeholder="Tanggal Akhir">	
									</div>
								</div>
							</div>
							<!-- <div class="col-md-2">  
								<div class="form-group">
									<label>Bulan</label>
									<select class="form-control" name="m" id="month_id" required="">
										<option value="">-- Pilih Bulan --</option>
        						    	<option  <?php echo (isset($q['m']) AND $q['m'] == '0') ? 'selected' : '' ?> value="0">Semua Bulan</option>
										<?php foreach ($month as $row): ?>
											<option <?php echo (isset($q['m']) AND $q['m'] == $row['month_id']) ? 'selected' : '' ?> value="<?php echo $row['month_id'] ?>"><?php echo $row['month_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div> -->
							<div class="col-md-2">  
								<div class="form-group">
									<label>Guru Mapel</label>
									<select class="form-control" name="e" id="employee_id" required="">
										<option value="">-- Pilih Guru Mapel --</option>
        						    	<option  <?php echo (isset($q['e']) AND $q['e'] == '0') ? 'selected' : '' ?> value="0">Semua Guru</option>
										<?php foreach ($lesson as $row): ?>
											<option <?php echo (isset($q['e']) AND $q['e'] == $row['employee_id']) ? 'selected' : '' ?> value="<?php echo $row['employee_id'] ?>"><?php echo $row['employee_name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Unit Sekolah</label>
									<select class="form-control" name="k" id="majors_id" onchange="get_kelas()"  required="">
										<option value="">-- Pilih Unit --</option>
        						    	<option  <?php echo (isset($q['k']) AND $q['k'] == '0') ? 'selected' : '' ?> value="0">Semua Unit</option>
										<?php foreach ($majors as $row): ?>
											<option <?php echo (isset($q['k']) AND $q['k'] == $row['majors_id']) ? 'selected' : '' ?> value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div id="td_kelas">
							<div class="col-md-2">  
								<div class="form-group">
									<label>Kelas</label>
									<select class="form-control" name="c" id="class_id" required="">
										<option value="">-- Pilih Kelas --</option>
										<option  <?php echo (isset($q['c']) AND $q['c'] == '0') ? 'selected' : '' ?> value="0">Semua Kelas</option>
										<?php if(isset($q['k'])){?>
										<?php foreach ($class as $row): ?>
											<option <?php echo (isset($q['c']) AND $q['c'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
										<?php endforeach; ?>
										<?php } ?>
									</select>
								</div>
							</div>
							</div>
							<div class="col-md-2">
							<div style="margin-top:25px;">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
							<?php if ($q AND !empty($student)) { ?>
							<a class="btn btn-danger" target="_blank" href="<?php echo site_url('manage/presensi_pelajaran/report_jurnal' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-pdf-o" ></i> Cetak</a>
							<?php } ?>
						    </div>
					        </div>
					</div>
					</form>
					</div>
				</div>
				
				<?php if ($q AND !empty($student)) { ?>
				<div class="box box-success">
					<div class="box-body table-responsive">
    					<table class="table table-responsive table-bordered" style="white-space: nowrap;">
    						    <thead>
    							<tr>
    								<th width='5' rowspan='2'>No.</th> 
    								<th rowspan="2">Tanggal</th> 
    								<th rowspan="2">Guru</th>
    								<th rowspan="2">Kelas</th> 
    								<th rowspan="2">Mapel</th>
    								<th rowspan="2">Materi</th> 
    								<th colspan="4">Kehadiran</th> 
    								<th rowspan="2">Keterangan</th>
    							</tr>
								<tr>
    								<th>H</th>
    								<th>S</th>
    								<th>I</th>
    								<th>A</th>
								</tr>
    							</thead>
    							<tbody>
    							<?php 
    							    $no = 1;
									$teaches = array();
    							    foreach ($teaching as $row) :
										$teaches[] = $row;
									endforeach;
									
									$presens = array();
									foreach ($presensi as $row) :
										$presens[] = $row;
									endforeach;

									foreach ($teaches as $teach) :
    						    ?>
    						    <tr>
									<td><?= $no++; ?></td>
									<td><?= pretty_date($teach['teaching_date_create'],"d-m-Y",FALSE); ?></td>
									<td><?= $teach['employee_name']; ?></td>
									<td><?= $teach['class_name']; ?></td>
									<td><?= $teach['lesson_name']; ?></td>
									<td><?= $teach['teaching_materi']; ?></td>
									<td>
										<?php
										$hadir = 0;
											foreach ($presens as $presen) :
												if($presen['c_pre'] == 'H' AND $presen['teach_id'] == $teach['teaching_id']) {
													$hadir += 1;
												}
											endforeach;
											echo $hadir;
										?>
									</td>
									<td>
										<?php
										$sakit = 0;
											foreach ($presens as $presen) :
												if($presen['c_pre'] == 'S' AND $presen['teach_id'] == $teach['teaching_id']) {
													$sakit += 1;
												}
											endforeach;
											echo $sakit;
										?>
									</td>
									<td>
										<?php
										$ijin = 0;
											foreach ($presens as $presen) :
												if($presen['c_pre'] == 'I' AND $presen['teach_id'] == $teach['teaching_id']) {
													$ijin += 1;
												}
											endforeach;
											echo $ijin;
										?>
									</td>
									<td>
										<?php
										$alpha = 0;
											foreach ($presens as $presen) :
												if($presen['c_pre'] == 'A' AND $presen['teach_id'] == $teach['teaching_id']) {
													$alpha += 1;
												}
											endforeach;
											echo $alpha;
										?>
									</td>
									<td>
										<center><a href="#viewModal<?php echo $teach['teaching_id']; ?>" data-toggle="modal" class="btn btn-xs btn-warning"><i class="fa fa-eye" data-toggle="tooltip" title="Hapus"> Detail Data</i></a></center>
									</td>
            					</tr>
								
								<div class="modal modal-default fade" id="viewModal<?php echo $teach['teaching_id']; ?>">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span></button>
												<h3 class="modal-title"><span class="fa fa-folder-open-o"></span> Detail Data</h3>
											</div>
											<div class="modal-body">
												Sakit :
												<?php
													foreach ($presens as $presen) :
														if($presen['c_pre'] == 'S' AND $presen['les_id'] == $teach['lesson_id']) {
															$s_sakit = $presen['student_full_name'];
															?>
															<ul>
																<li><?php echo $s_sakit; ?></li>
															</ul>
															<?php
														}
													endforeach;
												?>
												Izin :
												<?php
													foreach ($presens as $presen) :
														if($presen['c_pre'] == 'I' AND $presen['les_id'] == $teach['lesson_id']) {
															$s_ijin = $presen['student_full_name'];
															?>
															<ul>
																<li><?php echo $s_ijin; ?></li>
															</ul>
															<?php
														}
													endforeach;
												?>
												Alpha :
												<?php
													foreach ($presens as $presen) :
														if($presen['c_pre'] == 'A' AND $presen['les_id'] == $teach['lesson_id']) {
															$s_alpha = $presen['student_full_name'];
															?>
															<ul>
																<li><?php echo $s_alpha; ?></li>
															</ul>
															<?php
														}
													endforeach;
												?>
											</div>
											<div class="modal-footer">
												<?php echo form_open('manage/class/delete/' . $row['class_id']); ?>
												<input type="hidden" name="delName" value="<?php echo $row['class_name']; ?>">
												<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><span class="fa fa-undo"></span> Kembali</button>
												<!-- <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button> -->
												<?php echo form_close(); ?>
											</div>
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>
    							<?php endforeach ?>
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
<script>
    function get_kelas(){
        var id_majors    = $("#majors_id").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/presensi_pelajaran/class_searching',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#td_kelas").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
    }
</script>
<script>
    function get_pelajaran(){
        var class_id    = $("#class_id").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/presensi_pelajaran/lesson_searching',
            type: 'POST', 
            data: {
                    'class_id': class_id,
            },    
            success: function(msg) {
                    $("#td_pelajaran").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
    }
</script>
<script>
	$(document).ready(function() {
		$("#selectallhadir").click(function() {
            if ($(this).is(':checked'))
                $(".hadir").attr("checked", "checked");
            else
                $(".hadir").removeAttr("checked");
        });
		$("#selectallsakit").click(function() {
            if ($(this).is(':checked'))
                $(".sakit").attr("checked", "checked");
            else
                $(".sakit").removeAttr("checked");
        });
		$("#selectallizin").click(function() {
            if ($(this).is(':checked'))
                $(".izin").attr("checked", "checked");
            else
                $(".izin").removeAttr("checked");
        });
		$("#selectallalpha").click(function() {
            if ($(this).is(':checked'))
                $(".alpha").attr("checked", "checked");
            else
                $(".alpha").removeAttr("checked");
        });
	});
</script>