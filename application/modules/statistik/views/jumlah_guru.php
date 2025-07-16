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
						
						<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#printStudent"><i class="fa fa-print"></i> Cetak</button>
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#xlsStudent"><i class="fa fa-excel"></i> Export Xls</button>
						<div class="row">
						<div class="col-md-9"><br>
						<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
							<table>
							    <tr>
                                <td id="keterangan">
    								<select style="width: 200px;" id="c" name="c" class="form-control" required>
    								<option value="">--- Keterangan ---</option>
                                    <option value="">Guru & Pegawai</option>
                                    <option value="">Guru Tetap</option>
                                    <option value="">Guru Tidak Tetap</option>
                                    <!-- <?php foreach($period as $row){?>
            						        <option value="<?php echo $row['period_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['period_id']) ? 'selected' : '' ?>><?php echo $row['period_start'] ?>/<?php echo $row['period_end'] ?></option>
            						    <?php } ?> -->
    								</select>
    								</td>
                                    <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							            &nbsp&nbsp
							        </td>
							        <td>
							        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>    
							        </td>
							    </tr>
							</table>
						<?php echo form_close(); ?>
						</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
							    <thead>
								<tr>
									<th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th> 
									<th>No</th>
									<th>Keterangan</th>
									<th>Jumlah</th>
									<!-- <th>Kelas</th>
									<th>Kamar</th>
									<th>WA Ortu</th>
									<th>Status</th> -->
									<th>Aksi</th>
								</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($student)) {
										$i = 1;
										foreach ($student as $row):
											?>
											<tr>
												<td><input type="checkbox" class="checkbox" name="msg[]" value="<?php echo $row['student_id']; ?>"></td>
												<td><?php echo $i; ?></td>
												<td><?php echo 'Guru & Pegawai'; ?></td>
												<td><?php echo '700'; ?></td>
												
												<td>
													<a href="<?php echo site_url('manage/student/view/' . $row['student_id']) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="Lihat"><i class="fa fa-eye"></i></a>
													
													<a href="<?php echo site_url('manage/student/printPdf/' . $row['student_id']) ?>" class="btn btn-success btn-xs view-pdf" data-toggle="tooltip" title="Cetak Kartu"><i class="fa fa-print"></i></a>
												</td>	
											</tr>
											<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="8" align="center">Data Kosong</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<!-- /.box-body -->
						</div>
						<div>
							<?php echo $this->pagination->create_links(); ?>
						</div>
						<!-- /.box -->
					</div>
				</div>
			</form>
		</section>
		<!-- /.content -->
	</div>
	
	<div class="modal fade" id="printStudent" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Pilih Keterangan</h4>
				</div>
				<form action="<?php echo base_url();?>manage/student/print_students" method="post" target="_blank">
				<div class="modal-body">
					<label>Keterangan</label>
					<div id="div_ket">
					<select required="" name="modal_ket" id="modal_ket" class="form-control">
					    <option value="">-Pilih Keterangan-</option>
					</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger"><i class="fa fa-print"></i> Cetak</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="xlsStudent" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Pilih Keterangan</h4>
				</div>
				<form action="<?php echo base_url();?>manage/student/excel_students" method="post" target="_blank">
				<div class="modal-body">
					<label>Keterangan</label>
					<div id="div_ket">
					<select required="" name="modal_ket" id="modal_ket" class="form-control">
					    <option value="">-Pilih Keterangan-</option>
					</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-excel-o"></i> Export</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
				</form>
			</div>
		</div>
	</div>

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
	$('.view-pdf').on('click',function(){
		var pdf_link = $(this).attr('href');      
		var iframe = '<object type="application/pdf" data="'+pdf_link+'" width="100%" height="350">No Support</object>'
		$.createModal({
			title:'Cetak Kartu Pembayaran',
			message: iframe,
			closeButton:true,
			scrollable:false
		});
		return false;        
	});    
})
</script>
<script>
	$(document).ready(function() {
		$("#selectall").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>

<script>
    
    $("#m").change(function(e){
	    var id_majors    = $("#m").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/student/class_searching',
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
		e.preventDefault();
	});
    
    $("#m").change(function(e){
	    var id_majors    = $("#m").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/student/madin_searching',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#td_madin").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
	$("#modal_majors").change(function(e){
	    var id_majors    = $("#modal_majors").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/student/get_mclass',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#div_class").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
	$("#modal_majors").change(function(e){
	    var id_majors    = $("#modal_majors").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/student/get_mmadin',
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
	
	$("#xls_majors").change(function(e){
	    var id_majors    = $("#xls_majors").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/student/get_xls_class',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#div_class_xls").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
	$("#xls_majors").change(function(e){
	    var id_majors    = $("#xls_majors").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/student/get_xls_madin',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
            },    
            success: function(msg) {
                    $("#div_madin_xls").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
		e.preventDefault();
	});
	
</script>