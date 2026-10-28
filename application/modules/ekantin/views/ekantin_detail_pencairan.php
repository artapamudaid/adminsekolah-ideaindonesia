<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12 col-sm-12 col-xs-12 pull-left">
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td>No. HP</td>
                                                <td>:</td>
                                                <td><?php echo html_escape($detail['id_penjual']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nama Toko</td>
                                                <td>:</td>
                                                <td><?php echo html_escape($detail['nama_toko']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Akan Dicairkan</td>
                                                <td>:</td>
                                                <td><?php echo html_escape('Rp' . number_format($detail['total_pencairan'], 0, ',', '.')) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div>
                                        <h4 style="font-weight: bold;">Data Pencairan</h4>

                                        <?php if (!empty($detail['data_pencairan'])) { ?>
                                            <div class="row" style="margin-bottom: 10px; margin-left: 10px;">
                                                <a data-toggle="modal" class="btn btn-success" title="Pastikan Sudah Ada Tanggal yang Dicentang" href="#doPencairan" onclick="get_form_pencairan()"><span class="fa fa-money"></span> Lakukan Pencairan</a>
                                            </div>
                                        <?php } ?>
                                        <table class="table table-hover table-striped text-center">
                                            <thead style="background-color: #636262; color: white;">
                                                <tr>
                                                    <th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th></th>
                                                    <th>Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detail['data_pencairan'] as $dl) :
                                                ?>

                                                    <tr>
                                                        <td style="text-align: center;">
                                                            <input type="checkbox" class="checkbox" name="msg[]" id="msg" value="<?php echo $dl['tanggal_pengajuan']; ?>">
                                                        </td>
                                                        <td style="text-align: left;">
                                                            <?php echo pretty_date(html_escape($dl['tanggal_pengajuan']), 'd-m-Y', false) ?>
                                                        </td>
                                                        <td>Rp</td>
                                                        <td style="text-align: right;">
                                                            <?php echo number_format(html_escape($dl['total_per_tanggal']), 0, ',', '.') ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?php echo site_url('manage/ekantin/pencairan') ?>" class="btn btn-default">
                                            <i class="fa fa-arrow-circle-o-left"></i> Kembali
                                        </a>
                                    </div>

                                    <div class="modal fade" id="doPencairan" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Pencairan Dana Toko/Kantin</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo form_open('manage/ekantin/do_pencairan/', array('method' => 'post')); ?>
                                                    <p>Anda yakin akan melakukan pencairan dana untuk Toko/Kantin : <?= $detail['nama_toko'] ?></p>
                                                    <p>Pastikan Sudah Ada Tanggal yang Tercentang</p>
                                                    <input type="hidden" name="no_hp" value="<?= $detail['id_penjual'] ?>">
                                                    <div id="fbatchpencairan"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Cairkan</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $("#selectall").change(function() {
            $(".checkbox").prop('checked', $(this).prop("checked"));
        });
    });
</script>

<script>
    function get_form_pencairan() {
        var pencairan = $('#msg:checked');
        if (pencairan.length > 0) {
            var pencairan_value = [];
            $(pencairan).each(function() {
                pencairan_value.push($(this).val());
            });

            $.ajax({
                url: '<?php echo base_url(); ?>manage/ekantin/get_form/',
                method: "POST",
                data: {
                    pencairan: pencairan_value,
                },
                success: function(msg) {
                    $("#fbatchpencairan").html(msg);
                },
                error: function(msg) {
                    alert('msg');
                }
            });
        } else {
            alert("Belum ada tanggal yang dipilih");
            $("#doPencairan").hide();
        }
    }
</script>