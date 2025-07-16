
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
									<label>Mulai Tanggal</label>
						            <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="s" id="s" value="<?= (isset($_GET['s'])) ? $_GET['s'] : '' ?>" readonly="readonly" placeholder="Mulai Tanggal">
									</div>
								</div>
							</div>
							<div class="col-md-2">  
								<div class="form-group">
									<label>Sampai Tanggal</label>
						            <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="e" id="e" value="<?= (isset($_GET['e'])) ? $_GET['e'] : '' ?>" readonly="readonly" placeholder="Sampai Tanggal">
									</div>
								</div>
							</div>
							
							<div class="col-md-2">
							<div style="margin-top:25px;">
							<button type="submit" class="btn btn-primary">Cari</button>
							<?php if ($q AND !empty($transfer)) { ?>
							<a class="btn btn-success" target="_blank" href="<?php echo site_url('manage/transfer/transfer_date_excel' . '/?' . http_build_query($q)) ?>"><i class="fa fa-file-excel-o" ></i> Excel</a>
							<?php } ?>
						    </div>
					        </div>
					</div>
					</form>
					</div>
				</div>
				<?php if (!empty($transfer)) { ?>
				<div class="box box-info">
					<div class="box-body table-responsive">
						<table id="dtable" class="table table-responsive table-bordered" style="white-space: nowrap;">
						    <thead>
							<tr>
								<th>Tanggal</th> 
								<th>Id Transfer</th> 
								<th>Keterangan Unit Pengirim </th>
								<th>Keterangan Unit Penerima </th>
								<th>Kredit</th>
								<th>Debit</th>
							</tr>
							</thead>
							<?php if ($q AND !empty($transfer)) { ?>
							<tbody>
							<?php 
							    $no = 1;
							    $debit = 0;
							    $kredit = 0;
							    foreach ($transfer as $row) : 
							?>
								<tr>
									<td><?php echo pretty_date($row['log_tf_date'], 'd-m-Y', false) ?></td> 
									<td><?php echo ($row['log_tf_balance_id'] != '' ? '<center>
													<a href="" data-toggle="modal" style="align:center;" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" ></i> '. $row['log_tf_balance_id'].'</a>
												</center>' : '<center>
													<a href="" data-toggle="modal" style="align:center;" class="btn btn-xs btn-danger"><i class="fa fa-times" data-toggle="tooltip" ></i> Not Balance</a>
												</center>') ?></td> 
									<td>
										<?php 
											$nameAcc 		= $row['combane_name'];
											$account_name 	= explode(",", $nameAcc);
											$nameMaj 		= $row['combane_majors_name'];
											$majors_name 	= explode(",", $nameMaj);
											
											// penerima
											echo 'Unit : '.$majors_name[0];
											echo '<br> Akun : '.$account_name[0];
											echo '<br> No. Referensi : '.$row['deb_kas_noref'];

										?>
									</td> 
									<td>
										<?php 
											
											// Pengirim
											echo 'Unit : '.$majors_name[1];
											echo '<br> Akun : '.$account_name[1];
											echo '<br> No. Referensi : '.$row['kre_kas_noref'];  

										?>
									</td> 
									<td>Rp <?php echo number_format($row['kre_value'], '0', ',', '.') ?></td>
									<td>Rp <?php echo number_format($row['deb_value'], '0', ',', '.') ?></td>
								</tr>
							<?php 
							    $debit += $row['deb_value'];
							    $kredit += $row['kre_value'];
							    endforeach;
							?>
                            </tbody>
                            <?php } ?>
                            
						    <tfoot>
							<tr>
								<th colspan="4">Total</th>
								<th>Rp <?php echo number_format($debit, '0', ',', '.') ?></th>
								<th>Rp <?php echo number_format($kredit, '0', ',', '.') ?></th>
							</tr>
							</tfoot>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<?php } ?>
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
            url: '<?php echo base_url();?>manage/banking/class_searching',
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
    
    function get_siswa(){
        var id_majors    = $("#majors_id").val();
        var id_class     = $("#class_id").val();
        //alert(id_jurusan+id_kelas);
        $.ajax({ 
            url: '<?php echo base_url();?>manage/banking/student_searching',
            type: 'POST', 
            data: {
                    'id_majors': id_majors,
                    'id_class': id_class,
            },    
            success: function(msg) {
                    $("#td_siswa").html(msg);
            },
			error: function(msg){
					alert('msg');
			}
            
        });
    }
</script>
<script>
	$(document).ready(function() {
		$("#selectall").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
	$(document).ready(function() {
		$("#selectall2").change(function() {
			$(".checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>