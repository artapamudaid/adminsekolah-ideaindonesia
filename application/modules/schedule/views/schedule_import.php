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
                        <p>Penginputan Jadwal Pelajaran bisa dilakukan dengan mengcopy data dari file Ms. Excel. Format file excel harus sesuai kebutuhan aplikasi. Silahkan download formatnya <a href="<?php echo site_url('manage/schedule/download'); ?>"><span class="label label-success">Disini</span></a>
                        </p>
                        <hr>
                        <div class="col-md-4">
                            <?php echo form_open_multipart('manage/schedule/do_import') ?>

                            <div class="box-body">
                                <div class="form-group">
                                    <label>Masukkan File (.xlsx)</label>
                                    <input type="file" name="file" required="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm btn-flat">Import</button>
                                    <a href="<?php echo site_url('manage/schedule') ?>" class="btn btn-info btn-sm btn-flat">Kembali</a>
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
    </section>
    <!-- /.content -->
</div>