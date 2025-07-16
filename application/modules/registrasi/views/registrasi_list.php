<section class="content">
  <div class="row"> 
    <div class="col-md-12">
        <div class="box box-info box-solid" style="border: 1px solid #2ABB9B !important;">
            <div class="box-header backg with-border">
              <h3 class="box-title">Daftar Peneliti</h3>
              <a href="<?php echo site_url('researcher/add')?>"><button type="button" class="btn btn-default navbar-btn pull-right"><i class="fa fa-pencil-square-o"></i> Pendaftaran Peneliti</button></a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- /.box-header -->
        		<div class="box-body table-responsive">
        			<table id="dtable" class="table table-hover">
        			    <thead>
        				<tr> 
        					<th>No</th>
        					<th>Nama Peneliti</th>
        					<th>Prodi</th>
        					<th>Alamat</th>
        					<th>Kota/Kab.</th>
        					<th>Provinsi</th>
        					<th>Telepon</th>
        					<th>Email</th>
        				</tr>
        				</thead>
        				<tbody>
        					<?php
        					if (!empty($researcher_list)) {
        						$i = 1;
        						foreach ($researcher_list as $row):
        							?>
        							<tr>
        								<td><?php echo $i; ?></td>
        								<td><?php echo $row['researcher_name']; ?></td>
        								<td><?php echo $row['prodi_nama']; ?></td>
        								<td><?php echo $row['researcher_address']; ?></td>
        								<td><?php echo $row['city_name']; ?></td>
        								<td><?php echo $row['province_name']; ?></td>
        								<td><?php echo $row['researcher_phone']; ?></td>
        								<td><?php echo $row['researcher_email']; ?></td>
        							</tr>
        							<?php
        							$i++;
        						endforeach;
        					} else {
        						?>
        						<tr id="row">
        							<td colspan="7" align="center">Data Kosong</td>
        						</tr>
        						<?php } ?>
        					</tbody>
        				</table>
        
                </div>
        	    <!-- /.box-body -->
        	</div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section><br><br>