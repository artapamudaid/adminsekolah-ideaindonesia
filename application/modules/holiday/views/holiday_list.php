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
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addHoliday"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                            <tbody>
                                <?php
                                if (!empty($holiday)) {
                                    $i = 1;
                                    foreach ($holiday as $row) :
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td>
                                                <a href="#delModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></a>
                                            </td>
                                        </tr>

                                        <div class="modal modal-default fade" id="delModal<?php echo $row['id']; ?>">
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
                                                        <?php echo form_open('manage/holiday/delete/' . $row['id']); ?>
                                                        <input type="hidden" name="delName" value="<?php echo $row['date']; ?>">
                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                                        <button type="submit" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    <?php
                                        $i++;
                                    endforeach;
                                } else {
                                    ?>
                                    <tr id="row">
                                        <td colspan="3" align="center">Data Kosong</td>
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

        <div class="modal fade" id="addHoliday" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tambah Hari Libur</h4>
                    </div>
                    <?php echo form_open('manage/holiday/add_glob', array('method' => 'post')); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" name="date" id="multidate">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</div>
</section>
<!-- /.content -->
</div>