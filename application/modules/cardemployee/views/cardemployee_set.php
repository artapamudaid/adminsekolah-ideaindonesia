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
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <h4>Petunjuk Singkat</h4>
                        <p>Penginputan RFID Pegawai bisa dilakukan dengan mengcopy data dari file Ms. Excel. Format file excel harus sesuai kebutuhan aplikasi. Silahkan download formatnya <a data-toggle="modal" data-target="#template_rfid"><span class="label label-success">Disini</span></a>
                            <br><br>
                            <strong>CATATAN :</strong>
                        <ol>
                            <li>
                                Setelah selesai mengisi save as file excel ke format microsoft excel (.xlsx)
                            </li>
                        </ol>
                        </p>
                        <hr>
                        <div class="col-md-4">
                            <?php echo form_open_multipart('manage/cardemployee/set_mass') ?>

                            <div class="box-body">
                                <div class="form-group">
                                    <label>Masukkan File (.xlsx)</label>
                                    <input type="file" name="file" required="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm btn-flat">Update</button>
                                    <a href="<?php echo site_url('manage/cardemployee') ?>" class="btn btn-info btn-sm btn-flat">Kembali</a>
                                </div>
                            </div>
                            <?php echo form_close() ?>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <!-- /.box -->
            </div>
        </div>


        <div class="modal fade" id="template_rfid" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Download Template Excel</h4>
                    </div>
                    <form action="<?php echo base_url(); ?>manage/cardemployee/download_rfid" method="post" target="_blank">
                        <div class="modal-body">
                            <label>Unit Pesantren</label>
                            <select required="" name="xls_majors" id="xls_majors" class="form-control">
                                <option value="all">-Unit Pesantren-</option>
                                <?php foreach ($majors as $row) { ?>
                                    <option value="<?php echo $row['majors_id'] ?>"><?php echo html_escape($row['majors_short_name']) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-excel-o"></i> Download</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>