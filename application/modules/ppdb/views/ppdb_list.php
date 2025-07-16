<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						
						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
					    <div class="box-body table-responsive">
							<table>
							    <tr>
							        <td>     
    								<select style="width: 200px;" id="u" name="u" class="form-control">
    								    <option value="">Semua Unit</option>
            						    <?php foreach($unit as $row){?>
            						        <option value="<?php echo $row['id_unit']; ?>" <?php echo (isset($s['u']) && $s['u'] == $row['id_unit']) ? 'selected' : '' ?>><?php echo $row['short_name_unit'] ?></option>
            						    <?php } ?>
    								</select>
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>     
    								<select style="width: 200px;" id="t" name="t" class="form-control" required>
    								    <option>-- Pilih Tahun Ajaran --</option>
            						    <?php foreach($tahun as $row){?>
            						        <option value="<?php echo $row['id_tahun']; ?>" <?php echo (isset($s['t']) && $s['t'] == $row['id_tahun']) ? 'selected' : '' ?>><?php echo $row['start_tahun'] . '/' . $row['end_tahun'] ?></option>
    								    <?php } ?>
    								</select>
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td id="td_gelombang">     
    								<select style="width: 200px;" id="g" name="g" class="form-control" required>
            						    <option>-- Pilih Gelombang --</option>
							        <?php if(isset($s['t'])){?>
            						    <?php foreach($gelombang as $row){?>
            						        <option value="<?php echo $row['id_gelombang']; ?>" <?php echo (isset($s['g']) && $s['g'] == $row['id_gelombang']) ? 'selected' : '' ?>><?php echo $row['nama_gelombang'] ?></option>
            						    <?php } ?>
    								</select>
    								<?php } ?>
    								</select>
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>    
							        </td>
							    </tr>
							</table>
							</div>
							<?php echo form_close(); ?>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="ppdbtable" class="table table-hover">
						    <thead>
							<tr>
							    <th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th> 
                                <th>No. Pendaftaran</th>
                                <th>Nama Lengkap</th>
                                <th>Sekolah Asal</th>
                                <th>Unit</th>
                                <th>Tahun Ajaran</th>
                                <th>Gelombang</th>
                                <th>Jalur</th>
							</tr>
							</thead>
							<tbody>
									<?php
									if (!empty($student)) {
										foreach ($student as $row):
											?>
											<tr <?= ($row['penempatan_kelas']==1) ? 'style="background-color: #9dff87 !important;"' : ''; ?>>
												<td>
												    <?php if($row['penempatan_kelas']==0) : ?>
												        <input type="checkbox" class="checkbox" name="msg[]" id="msg" value="<?php echo $row['no_pendaftaran']; ?>">
												    <?php endif; ?>
												</td>
												<td><?php echo $row['no_pendaftaran']; ?></td>
												<td><?php echo $row['nama_lengkap']; ?></td>
												<td><?php echo $row['nama_sekolah']; ?></td>
												<td><?php echo $row['short_name_unit']; ?></td>
												<td><?php echo $row['start_tahun'].'/'.$row['end_tahun']; ?></td>
												<td><?php echo $row['nama_gelombang']; ?></td>
												<td><?php echo $row['jalur_pendaftaran']; ?></td>
											</tr>
											<?php
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="8" align="center">Data Kosong</td>
										</tr>
										<?php } ?>
									</tbody>
						</table>
						<?php if (!empty($student)) { ?>
						<a data-toggle="modal" class="btn btn-success btn-xs" title="Pastikan Sudah Ada yang Dicentang" href="#gotoClass" onclick="get_form()"><span class="fa fa-check"></span> Tempatkan Kelas</a>
						<?php } ?>
					</div>
					
					<div class="modal fade" id="gotoClass" role="dialog">
                    	<div class="modal-dialog modal-md">
                    		<div class="modal-content">
                    			<div class="modal-header">
                    				<button type="button" class="close" data-dismiss="modal">&times;</button>
                    				<h4 class="modal-title">Tempatkan Kelas</h4>
                    			</div>
                    			<div class="modal-body">
                                <?php echo form_open('manage/ppdb/go_to/', array('method'=>'post')); ?>
                    				
                    				<div class="form-group">
                    				<label>Unit</label>
    								<select id="major" name="major" class="form-control" required>
    								    <option>-- Pilih Unit --</option>
            						    <?php foreach($majors as $major){?>
            						        <option value="<?php echo $major['majors_id']; ?>"><?php echo $major['majors_short_name'] ?></option>
    								    <?php } ?>
    								</select>	
                    				</div>
                    				
                    				<div class="form-group div_kelas">
                    				<label>Kelas</label>
    								<select id="class" name="class" class="form-control" required>
    								    <option>-- Pilih Kelas --</option>
    								</select>	
                    				</div>
                    				
                        		    <div id="fbatch"></div>
                    			</div>
                    			<div class="modal-footer">
                    				<button type="submit" class="btn btn-success">Tempatkan</button>
                    				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    			</div>
                    			<?php echo form_close(); ?>
                    		</div>
                    	</div>
                    </div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
				</div>
			</section>
			<!-- /.content -->
		</div>

		<!-- Modal -->
	</div>

<script>
	$(document).ready(function() {
		$("#selectall").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>
	
<script>
    
    $("#t").change(function(e){
	    var tahun    = $("#t").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/ppdb/gelombang_pendaftaran',
            type: 'POST', 
            data: {
                    'tahun': tahun,
            },    
            success: function(msg) {
                    $("#td_gelombang").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
</script>
	
<script>
    
    $("#major").change(function(e){
	    var major    = $("#major").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/ppdb/data_kelas',
            type: 'POST', 
            data: {
                    'major': major,
            },    
            success: function(msg) {
                    $(".div_kelas").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
</script>

<script>
    function get_form(){
        
        var no_pendaftaran = $('#msg:checked');
        
        if(no_pendaftaran.length > 0)
        {
            var no_pendaftaran_value = [];
            $(no_pendaftaran).each(function(){
                no_pendaftaran_value.push($(this).val());
            });
        
            $.ajax({
                url: '<?php echo base_url();?>manage/ppdb/get_form/',
                method:"POST",
                data: {
                        no_pendaftaran : no_pendaftaran_value,
                },
                success: function(msg){
                        $("#fbatch").html(msg);
                },
        		error: function(msg){
        				alert('msg');
        		}
            });
        }
        else
        {
            alert("Belum ada Santri yang dipilih");
        }
    }
</script>