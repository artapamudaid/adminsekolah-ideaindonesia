<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
            <small>Detail</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo 'Anggaran ' . $worksheet['majors_short_name'] . ' ' . $worksheet['period_start'] . '/' . $worksheet['period_end']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label ">Pagu Anggaran : </label>
                                <input type="text" class="form-control" value="<?= 'Rp ' . number_format($worksheet['worksheet_nominal'], '0', ',', '.') ?>" readonly style="color:grey; font-weight: bold; font-size:14pt; text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#addKegiatan" style="color:black; background-color: white;
          border-color: black; margin-top: 25px;"><i class="fa fa-plus"></i> Tambah Kegiatan</button>
                                <a type="button" class="btn btn-sm" href="<?= site_url('manage/worksheet/review/' . $this->uri->segment('4')) ?>" style="color:white; background-color: black;
          border-color: transparent; margin-top: 25px;"><i class="fa fa-pie-chart"></i> Review</a>
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ajukanKertasKerja" style="margin-top: 25px;"><i class="fa fa-send"></i> Ajukan Pengesahan</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h3 style="font-weight: bold; margin-top: -3px;">Periode Anggaran <?= $worksheet['period_start'] . '/' . $worksheet['period_end'] ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="pull-right">
                                <input type="text" class="form-control" value="<?= 'Rp ' . number_format($sum_alokasi['total'], '0', ',', '.') ?>" readonly style="color:grey; font-weight: bold; font-size:14pt; text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="pull-right">
                                <input type="text" class="form-control" value="<?= 'Rp ' . number_format($worksheet['worksheet_nominal'] - $sum_alokasi['total'], '0', ',', '.') ?>" readonly style="color:grey; font-weight: bold; font-size:14pt; text-align: right;">
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">

                                <?php foreach ($bulan as $bul) {
                                ?>
                                    <li <?= ($bul['month_id'] == '1') ? 'class="active"' : '' ?>>
                                        <a href="#tab_<?= $bul['month_id'] ?>" data-toggle="tab"><?= substr($bul['month_name'], 0, 3) ?></a>
                                    </li>
                                <?php
                                } ?>
                            </ul>
                            <div class="tab-content">
                                <?php
                                foreach ($bulan as $bul) {
                                    $key = $bul['month_id'];
                                ?>
                                    <div class="tab-pane <?= ($bul['month_id'] == '1') ? 'active' : '' ?>" id="tab_<?= $bul['month_id'] ?>">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Program</th>
                                                    <th>Kegiatan</th>
                                                    <th>Rekening Belanja</th>
                                                    <th>Uraian</th>
                                                    <th>Jumlah</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($alokasi as $row) {
                                                    if ($row['budget_detail_bulan_id'] == $key) {
                                                ?>
                                                        <tr>
                                                            <td><?= $row['name'] ?></td>
                                                            <td><?= $row['aktivitas_name'] ?></td>
                                                            <td><?= $row['account_code'] . ' - ' . $row['account_description'] ?></td>
                                                            <td><?= $row['budgeting_uraian'] ?></td>
                                                            <td><?= $row['budget_detail_jumlah'] ?></td>
                                                            <td><?= $row['satuan_name'] ?></td>
                                                            <td><?= 'Rp ' . number_format($row['budget_detail_nominal'], '0', ',', '.') ?></td>
                                                            <td><?= 'Rp ' . number_format($row['budget_detail_jumlah'] * $row['budget_detail_nominal'], '0', ',', '.') ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>


                    <a class="btn btn-default btn-sm" href="<?php echo site_url('manage/worksheet') ?>"><span class="glyphicon glyphicon-repeat"></span> Kembali</a>
                </div>
            </div><!-- /.box-body -->
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addKegiatan" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Isi Detail Anggaran Kegiatan</h4>
                    </div>
                    <?php echo form_open('manage/worksheet/add_alokasi', array('method' => 'post')); ?>
                    <div class="modal-body">
                        <input type="hidden" name="worksheet_id" value="<?php echo $this->uri->segment('4') ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Kegiatan</label>
                                <select required="" name="aktivitas_id" class="form-control">
                                    <option value="">-Pilih Kegiatan-</option>
                                    <?php foreach ($aktivitas as $row) { ?>
                                        <option value="<?php echo $row['aktivitas_id'] ?>">
                                            <?php echo $row['aktivitas_kode'] . '-' . $row['aktivitas_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Uraian</label>
                                <input type="text" required="" name="uraian" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Harga Satuan</label>
                                <input type="number" required="" name="nominal" class="form-control">
                            </div>
                        </div>
                        <div id="p_scents_bulan">
                            <div class="row">
                            </div>
                        </div>
                        <div class="row">
                            <h6><a href="#" class="btn btn-xs btn-success" id="addScnt_bulan"><i class="fa fa-plus"></i><b> Tambah Bulan</b></a></h6>
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
        <!-- Modal -->
        <div class="modal modal-default fade" id="ajukanKertasKerja">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title"><span class="fa fa-send"></span> Ajukan Pengesahan Kertas Kerja?</h3>
                    </div>
                    <div class="modal-body">
                        <p>
                            Kertas Kerja Anda akan dikirim ke Yayasan untuk diperiksa kesesuaiannya dengan peraturan yang berlaku.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <?php echo form_open('manage/worksheet/ajukan/'); ?>
                        <input type="hidden" name="worksheet_id" value="<?php echo $this->uri->segment('4'); ?>">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                        <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Ajukan</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </section>
</div>

<script>
    $(function() {
        var scntDiv = $('#p_scents_bulan');
        var i = $('#p_scents_bulan .row').size() + 1;

        $("#addScnt_bulan").click(function() {
            $('<div class="row"><div class="col-md-12"><div class="form-group"><label>Bulan</label><select required="" name="bulan_id[]" class="form-control"><option value="">-Pilih Bulan-</option><?php foreach ($bulan as $bul) { ?><option value="<?php echo $bul['month_id'] ?>"><?php echo $bul['month_name'] ?></option><?php } ?></select><a href="#" class="btn btn-xs btn-danger remScnt_bulan">Hapus Bulan</a></div></div><div class="col-md-6"><div class="form-group"><label>Jumlah</label><input type="number" required="" name="jumlah[]" class="form-control" placeholder="Jumlah"></div></div><div class="col-md-6"><div class="form-group"><label>Satuan</label><select required="" name="satuan[]" class="form-control"><option value="">-Pilih Satuan-</option><?php foreach ($satuan as $row) { ?><option value="<?php echo $row->satuan_id ?>"><?php echo $row->satuan_name ?></option><?php } ?></select></div></div>').appendTo(scntDiv);
            i++;
            return false;
        });

        $(document).on("click", ".remScnt_bulan", function() {
            if (i > 2) {
                $(this).parents('.row').remove();
                i--;
            }
            return false;
        });
    });
</script>