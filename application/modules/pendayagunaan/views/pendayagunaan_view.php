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

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informasi Program</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-9">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td>Nama Program</td>
                                        <td>:</td>
                                        <td><?= $program['program_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Target</td>
                                        <td>:</td>
                                        <td><?= 'Rp' . number_format($program['program_target'], 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Pencapaian</td>
                                        <td>:</td>
                                        <td><?= 'Rp' . number_format($program['program_earn'], 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Pendayagunakan</td>
                                        <td>:</td>
                                        <td><?= 'Rp' . number_format($program['program_realisasi'], 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Sisa Dana</td>
                                        <td>:</td>
                                        <td><?= 'Rp ' . number_format($program['program_earn'] - $program['program_realisasi'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Aktif Sampai</td>
                                        <td>:</td>
                                        <td><?= pretty_date($program['program_end'], 'd F Y', false) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#exportX" style="margin-bottom: 10px;"><i class="fa fa-file-excel-o"></i> Export Laporan</button>
                            <img src="<?php echo upload_url('program/' . $program['program_gambar']) ?>" class="img-thumbnail img-responsive">
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exportX" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Export Laporan Program</h4>
                            </div>
                            <div class="modal-body">

                                <?php echo form_open('manage/pendayagunaan/export', array('method' => 'get', 'target' => '_blank')); ?>

                                <input type="hidden" name="program" value="<?= $program_id ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dari Tanggal</label>
                                            <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                <input class="form-control" required="" type="text" name="start" value="<?php echo date("Y-m-d") ?>" placeholder="Dari Tanggal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sampai Tanggal</label>
                                            <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                <input class="form-control" required="" type="text" name="end" value="<?php echo date("Y-m-d") ?>" placeholder="Sampai Tanggal">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Export</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <!-- List Tagihan Bulanan -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Jenis Transaksi</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Donasi</a></li>
                                <li><a href="#tab_2" data-toggle="tab">Pendayagunaan</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body table-responsive">
                                        <table class="table table-bordered" style="white-space: nowrap;">
                                            <thead>
                                                <tr class="info">
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>No. HP/WA</th>
                                                    <th>Nominal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                foreach ($donasi as $row) :
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i ?></td>
                                                        <td><?php echo pretty_date($row['donasi_datetime'], 'd F Y H:i:s', false) ?></td>
                                                        <td><?php echo $row['donasi_name'] ?></td>
                                                        <td><?php echo $row['donasi_email'] ?></td>
                                                        <td><?php echo $row['donasi_hp'] ?></td>
                                                        <td>Rp <?php echo number_format($row['donasi_nominal'], 0, ',', '.') ?></td>
                                                        <td>#</td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                endforeach;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <div class="box-body">
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addX"><i class="fa fa-money"></i> Tambah Pendayagunaan</button>
                                    </div>
                                    <div class="box-body table-responsive">
                                        <table class="table table-bordered" style="white-space: nowrap;">
                                            <thead>
                                                <tr class="info">
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Nominal</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                foreach ($pendayagunaan as $row) :
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i ?></td>
                                                        <td><?php echo pretty_date($row['pendayagunaan_date'], 'd F Y', false) ?></td>
                                                        <td>Rp <?php echo number_format($row['pendayagunaan_nominal'], 0, ',', '.') ?></td>
                                                        <td><?php echo $row['pendayagunaan_note'] ?></td>
                                                        <td>#</td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                endforeach;
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="modal fade" id="addX" role="dialog">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Tambah Pendayagunaan</h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <?php echo form_open('manage/pendayagunaan/add', array('method' => 'post')); ?>

                                                        <input type="hidden" name="program_program_id" value="<?= $program_id ?>">

                                                        <div class="form-group">
                                                            <label>Kas Aktiva</label>
                                                            <select class="form-control" name="aktiva_id" id="aktiva_id" required>
                                                                <option value="">-- Pilih Akun Kas ---</option>
                                                                <?php foreach ($account as $row) { ?>
                                                                    <option value="<?= $row['account_id'] ?>"><?php echo $row['account_code'] . ' - ' . $row['account_description'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Tanggal</label>
                                                            <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                <input class="form-control" required="" type="text" name="pendayagunaan_date" value="<?php echo date("Y-m-d") ?>" placeholder="Tanggal Setor">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Keterangan</label>
                                                            <textarea class="form-control" required="" name="pendayagunaan_note" class="form-control" placeholder="Keterangan" rows="5"></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Nominal</label>
                                                            <input type="text" class="form-control" required="" name="pendayagunaan_nominal" class="form-control" placeholder="Jumlah Nominal">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Simpan</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<style>
    div.over {
        width: 425px;
        height: 165px;
        overflow: scroll;
    }

    div.extended {
        width: 900px;
        height: 200px;
        overflow: scroll;
    }
</style>