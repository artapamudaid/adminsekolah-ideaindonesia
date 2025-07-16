<section class="content">
  <div class="row"> 
    <div class="col-md-12">
        <div class="box box-info box-solid" style="border: 1px solid #2ABB9B !important;">
            <div class="box-header backg with-border">
              <h3 class="box-title">Pendaftaran Peneliti Berhasil</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td>
                				<p>Berikut username dan password Anda: </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    <b>Username :</b>
                				<?php echo $researcher_list['researcher_username']; ?>
                				</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                <b>Password :</b>
    				            <?php echo $researcher_list['researcher_password']; ?>
    				            </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="<?php echo site_url('researcher/print_researcher/'.$researcher_list['researcher_id'])?>" target="_blank"><button type="button" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button></a>
                                &nbsp;&nbsp;
                                <a href="<?php echo base_url(); ?>"><button type="button" class="btn btn-default"> Kembali</button></a>
                            </td>
                        </tr>
                    </table>
				</div>
        	</div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section><br><br>