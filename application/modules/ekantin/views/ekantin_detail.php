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
                                <div class="col-md-2">
                                    <div class="text-center" style="margin-bottom: 10px;">
                                        <h4 style="font-weight: bold;">Foto Banner</h4>
                                        <?php if ($detail['foto_banner'] != $this->config->item('kantin_url') . '/') { ?>
                                            <img src="<?php echo $detail['foto_banner'] ?>" class="img-responsive avatar">
                                        <?php } else { ?>
                                            <img src="<?php echo media_url('img/missing.png') ?>" class="img-responsive avatar">
                                        <?php } ?>
                                    </div>
                                    <div class="text-center">
                                        <h4 style="font-weight: bold;">Foto Profil</h4>
                                        <?php if ($detail['foto_profil'] != $this->config->item('kantin_url') . '/') { ?>
                                            <img src="<?php echo $detail['foto_profil'] ?>" class="img-responsive avatar">
                                        <?php } else { ?>
                                            <img src="<?php echo media_url('img/missing.png') ?>" class="img-responsive avatar">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td>No. HP</td>
                                                <td>:</td>
                                                <td><?php echo html_escape($detail['no_hp']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nama Toko</td>
                                                <td>:</td>
                                                <td><?php echo html_escape($detail['nama_toko']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nama Pemilik</td>
                                                <td>:</td>
                                                <td><?php echo html_escape($detail['nama_pemilik']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>:</td>
                                                <td><?php echo html_escape($detail['email']) ?></td>
                                            </tr>

                                            <tr>
                                                <td>Total Pendapatan</td>
                                                <td>:</td>
                                                <td><?php echo html_escape('Rp ' . number_format($detail['totalPendapatan'], 0, ',', '.')) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Akan Dicairkan</td>
                                                <td>:</td>
                                                <td><?php echo html_escape('Rp' . number_format($detail['totalAkanDicairkan'], 0, ',', '.')) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div>
                                        <h4 style="font-weight: bold;">Detail Langganan</h4>
                                        <table class="table table-hover table-striped text-center">
                                            <thead style="background-color: #636262; color: white;">
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Tanggal Berlanggan</th>
                                                    <th>Tanggal Berakhir</th>
                                                    <?php if ($this->session->userdata('uroleid') == 3) : ?>
                                                        <th>Aksi</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detail['dataLangganan'] as $dl) :
                                                ?>

                                                    <tr>
                                                        <td style="font-weight: 600; color:<?php echo (html_escape($dl['status'] == 'active')) ? 'black' : 'grey' ?>;">
                                                            <?php echo (html_escape($dl['status'] == 'active')) ? 'Aktif' : 'Non-Aktif' ?>
                                                        </td>
                                                        <td style="font-weight: 600; color:<?php echo (html_escape($dl['status'] == 'active')) ? 'black' : 'grey' ?>;">
                                                            <?php echo html_escape($dl['awal_langganan']) ?>
                                                        </td>
                                                        <td style="font-weight: 600; color:<?php echo (html_escape($dl['status'] == 'active')) ? 'black' : 'grey' ?>;">
                                                            <?php echo html_escape($dl['expired']) ?></td>
                                                        <?php if ($this->session->userdata('uroleid') == 3) : ?>
                                                            <td>
                                                                <?php
                                                                if (empty($dl['awal_langganan'])) { ?>
                                                                    <button class="btn btn-default btn-xs" id="activeBtn"
                                                                        data-toggle="modal"
                                                                        data-target="#langgananModal"
                                                                        data-id_langganan="<?= $dl['id_langganan'] ?>"
                                                                        data-id_toko="<?= $detail['no_hp'] ?>"
                                                                        data-nama_toko="<?= $detail['nama_toko'] ?>"
                                                                        title="Aktifkan Langganan Perdana">
                                                                        <i class="fa fa-check-circle-o"></i>
                                                                    </button>

                                                                    <?php } else {
                                                                    if ($dl['status'] == 'active') { ?>
                                                                        <button class="btn btn-danger btn-xs" id="inactiveBtn" onclick="setInactive(<?= $dl['id_langganan'] ?>)" data-toggle="tooltip" title="Non-aktifkan Langganan"><i class="fa fa-remove"></i></button>
                                                                    <?php } else { ?>
                                                                        <button class="btn btn-success btn-xs" id="activeBtn" onclick="setActive(<?= $dl['id_langganan'] ?>)" data-toggle="tooltip" title="Aktifkan Langganan"><i class="fa fa-check"></i></button>
                                                                <?php }
                                                                } ?>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?php echo site_url('manage/ekantin') ?>" class="btn btn-default">
                                            <i class="fa fa-arrow-circle-o-left"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="langgananModal" tabindex="-1" role="dialog" aria-labelledby="langgananModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="langgananModalLabel">Aktivasi Langganan</h4>
                                </div>
                                <div class="modal-body">
                                    <form id="formLangganan" method="post" action="<?php echo site_url('manage/ekantin/aktif_perdana') ?>">
                                        <p>Aktifkan Langganan: <strong id="namaToko"></strong></p>
                                        <input type="hidden" id="id_toko" name="id_toko" value="">
                                        <input type="hidden" id="id_langganan" name="id_langganan" value="">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tanggal Berlanggan</label>
                                                <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    <input class="form-control" type="text" name="awal_langganan" id="awal_langganan" readonly="readonly" placeholder="Tanggal Berlanggan" value="<?php echo date('Y-m-d') ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Lama Berlanganan</label>
                                                <select name="durasi" id="durasi" class="form-control" required>
                                                    <option value="">- Pilih Lama Berlanganan - </option>
                                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                        <option value="<?= $i ?>"><?= $i ?> Bulan</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Aktifkan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

    </section>
</div>

<script>
    function setActiveFirst(id_langganan) {

        var awal_langganan = $('#awal_langganan').val();
        var expired = $('#expired').val();

        $.ajax({
            url: "<?php echo site_url('manage/ekantin/aktif_perdana'); ?>",
            type: "POST",
            data: {
                awal_langganan: awal_langganan,
                expired: expired,
                id_langganan: id_langganan
            },
            success: function(response) {

                if (response.is_correct == false) {
                    $.toast({
                        heading: 'Error',
                        text: response.message,
                        icon: 'error',
                        position: 'top-right',
                        loaderBg: '#ff2e2e',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                    return;
                }
                $.toast({
                    heading: 'Sukses',
                    text: 'Langganan Toko Berhasil Diaktifkan',
                    icon: 'success',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });

                window.location.reload();
            },
            error: function(xhr, status, error) {
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi kesalahan: ' + error,
                    icon: 'error',
                    position: 'top-right',
                    loaderBg: '#ff2e2e',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
            }
        });
    }

    function setActive(id_langganan) {

        if (!confirm('Apakah Anda yakin ingin mengaktifkan langganan ini?')) {
            return;
        }

        $.ajax({
            url: "<?php echo site_url('manage/ekantin/aktifkan_langganan'); ?>",
            type: "POST",
            data: {
                id_langganan: id_langganan
            },
            success: function(response) {

                if (response.is_correct == false) {
                    $.toast({
                        heading: 'Error',
                        text: response.message,
                        icon: 'error',
                        position: 'top-right',
                        loaderBg: '#ff2e2e',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                    return;
                }
                $.toast({
                    heading: 'Sukses',
                    text: 'Langganan Toko Berhasil Diaktifkan',
                    icon: 'success',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });

                window.location.reload();
            },
            error: function(xhr, status, error) {
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi kesalahan: ' + error,
                    icon: 'error',
                    position: 'top-right',
                    loaderBg: '#ff2e2e',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
            }
        });
    }

    function setInactive(id_langganan) {

        if (!confirm('Apakah Anda yakin ingin menonaktifkan langganan ini?')) {
            return;
        }

        $.ajax({
            url: "<?php echo site_url('manage/ekantin/cancel_langganan'); ?>",
            type: "POST",
            data: {
                id_langganan: id_langganan
            },
            success: function(response) {

                if (response.is_correct == false) {
                    $.toast({
                        heading: 'Error',
                        text: response.message,
                        icon: 'error',
                        position: 'top-right',
                        loaderBg: '#ff2e2e',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                    return;
                }
                $.toast({
                    heading: 'Sukses',
                    text: 'Langganan Toko Berhasil Dinonaktifkan',
                    icon: 'success',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });

                window.location.reload();
            },
            error: function(xhr, status, error) {
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi kesalahan: ' + error,
                    icon: 'error',
                    position: 'top-right',
                    loaderBg: '#ff2e2e',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#langgananModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang diklik
            var id_toko = button.data('id_toko'); // Ambil data-id_langganan
            var id_langganan = button.data('id_langganan');
            var nama_toko = button.data('nama_toko');
            var modal = $(this);
            modal.find('#id_toko').val(id_toko); // Set ke dalam input text
            modal.find('#id_langganan').val(id_langganan);
            modal.find('#namaToko').text(nama_toko); // Set nama toko
        });
    });
</script>
