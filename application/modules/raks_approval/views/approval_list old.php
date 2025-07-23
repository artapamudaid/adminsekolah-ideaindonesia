<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>

<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
        <?php
        $majors_id = $this->session->userdata('umajorsid');
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Unit</h3>
                    </div>
                    <div class="box-body">

                        <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
                        <div class="box-body table-responsive">
                            <table>
                                <tr>
                                    <td>
                                        <select style="width: 200px;" id="m" name="m" class="form-control" required>
                                            <option value="">--- Pilih Unit ---</option>
                                            <?php if ($this->session->userdata('umajorsid') == '0') { ?>
                                                <option value="all" <?php echo (isset($s['m']) && $s['m'] == 'all') ? 'selected' : '' ?>>Semua Unit</option>
                                            <?php } ?>
                                            <?php foreach ($majors as $row) { ?>
                                                <option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        &nbsp&nbsp
                                    </td>
                                    <td>
                                        <select style="width: 200px;" id="p" name="p" class="form-control" required>
                                            <option value="">--- Pilih Tahun Ajaran ---</option>
                                            <?php foreach ($period as $row) : ?>
                                                <option <?php if (isset($s['p']) and $s['p'] == $row['period_id']) {
                                                            echo 'selected';
                                                        } else if (empty($s['p']) and $periodActive['period_id'] == $row['period_id']) {
                                                            echo 'selected';
                                                        } else {
                                                            echo '';
                                                        } ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        &nbsp&nbsp
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                </div>

                <!-- /.box -->
            </div>
        </div>

        <?php
        if (!empty($status_approval)) {
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="a">
                        <!-- <div class="box-header with-border">
                            <h3 class="box-title">Approval</h3>
                        </div> -->
                        <div class="a">
                            <div class="row">
                                <!-- <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box bg-aqua-gradient">
                                        <span class="info-box-icon"></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Koordinator</strong></span>
                                            <span class="info-box-number" style="opacity: 0;">kosong</span>
                                            <?php
                                            if ($status_approval == 'PROCESS') {
                                                echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                echo '<span class="info-box-number" style="opacity: 0;">kosong</span>';
                                                echo '<span class="info-box-text dash-text"><button class="btn btn-success btnApproved" data-approval="KOORDINATOR"><i class="fa fa-thumbs-up"></i> Approve</button></span>';
                                            } else {
                                                echo '<span class="info-box-text dash-text">Sudah Disetujui</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box bg-aqua-gradient">


                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Kepala Sekolah</strong></span>
                                            <span class="info-box-number" style="opacity: 0;">kosong</span>

                                            <?php
                                            if ($status_approval != 'PROCESS' and $status_approval != 'KOORDINATOR') {
                                                echo '<span class="info-box-text dash-text">Sudah Disetujui</span>';
                                            } else {
                                                if ($status_approval == 'KOORDINATOR') {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                    echo '<span class="info-box-number" style="opacity: 0;">kosong</span>';
                                                    echo '<span class="info-box-text dash-text"><button class="btn btn-success btnApproved" data-approval="KEPALA SEKOLAH"><i class="fa fa-thumbs-up"></i> Approve</button></span>';
                                                } else {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box bg-aqua-gradient">
                                        <span class="info-box-icon"></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Kepala Pengembang</strong></span>
                                            <span class="info-box-number" style="opacity: 0;">kosong</span>

                                            <?php
                                            if ($status_approval != 'PROCESS' and $status_approval != 'KOORDINATOR' and $status_approval != 'KEPALA SEKOLAH') {
                                                echo '<span class="info-box-text dash-text">Sudah Disetujui</span>';
                                            } else {
                                                if ($status_approval == 'KEPALA SEKOLAH') {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                    echo '<span class="info-box-number" style="opacity: 0;">kosong</span>';
                                                    echo '<span class="info-box-text dash-text"><button class="btn btn-success btnApproved" data-approval="KEPALA PENGEMBANG"><i class="fa fa-thumbs-up"></i> Approve</button></span>';
                                                } else {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box bg-aqua-gradient">
                                        <span class="info-box-icon"></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Kepala Operasional</strong></span>
                                            <span class="info-box-number" style="opacity: 0;">kosong</span>

                                            <?php
                                            if (
                                                $status_approval != 'PROCESS' and $status_approval != 'KOORDINATOR' and $status_approval != 'KEPALA SEKOLAH'
                                                and $status_approval != 'KEPALA PENGEMBANG'
                                            ) {
                                                echo '<span class="info-box-text dash-text">Sudah Disetujui</span>';
                                            } else {
                                                if ($status_approval == 'KEPALA PENGEMBANG') {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                    echo '<span class="info-box-number" style="opacity: 0;">kosong</span>';
                                                    echo '<span class="info-box-text dash-text"><button class="btn btn-success btnApproved" data-approval="KEPALA OPERASIONAL"><i class="fa fa-thumbs-up"></i> Approve</button></span>';
                                                } else {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box bg-aqua-gradient">
                                        <span class="info-box-icon"></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Kepala Keuangan</strong></span>
                                            <span class="info-box-number" style="opacity: 0;">kosong</span>

                                            <?php
                                            if (
                                                $status_approval != 'PROCESS' and $status_approval != 'KOORDINATOR' and $status_approval != 'KEPALA SEKOLAH'
                                                and $status_approval != 'KEPALA PENGEMBANG' and $status_approval != 'KEPALA OPERASIONAL'
                                            ) {
                                                echo '<span class="info-box-text dash-text">Sudah Disetujui</span>';
                                            } else {
                                                if ($status_approval == 'KEPALA OPERASIONAL') {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                    echo '<span class="info-box-number" style="opacity: 0;">kosong</span>';
                                                    echo '<span class="info-box-text dash-text"><button class="btn btn-success btnApproved" data-approval="KEPALA KEUANGAN"><i class="fa fa-thumbs-up"></i> Approve</button></span>';
                                                } else {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box bg-aqua-gradient">
                                        <span class="info-box-icon"></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Owner</strong></span>
                                            <span class="info-box-number" style="opacity: 0;">kosong</span>

                                            <?php
                                            if ($status_approval == 'OWNER') {
                                                echo '<span class="info-box-text dash-text">Sudah Disetujui</span>';
                                            } else {
                                                if ($status_approval == 'KEPALA KEUANGAN') {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                    echo '<span class="info-box-number" style="opacity: 0;">kosong</span>';
                                                    echo '<span class="info-box-text dash-text"><button class="btn btn-success btnApproved" data-approval="OWNER"><i class="fa fa-thumbs-up"></i> Approve</button></span>';
                                                } else {
                                                    echo '<span class="info-box-text dash-text">Belum Disetujui</span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detail RAKS</h3>
                    </div>
                    <div class="box-body">
                        <!-- Tabel RAKS -->
                        <div class="box-body table-responsive">
                            <table id='dtable' class='table table-hover table-responsive'>
                                <thead>
                                    <tr>
                                        <th hidden="true"></th>
                                        <th hidden="true"></th>
                                        <th style="width: 10%;">Kode</th>
                                        <th style="width: 50%;">Nama</th>
                                        <th style="width: 20%;">Total</th>
                                        <!-- <th>Status</th> -->
                                        <!-- <th style="width: 20%;">Aksi</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($s['m']) and !empty($s['p'])) { ?>
                                        <tr style="font-weight: bold;">
                                            <td hidden="true"></td>
                                            <td hidden="true" class="kode-entry"></td>
                                            <td colspan="2">RAKS <?php echo $dataPeriod['period_start'] . '/' . $dataPeriod['period_end'] . " - " . $dataMajors['majors_short_name'] ?> <?php echo !empty($status_approval) ? " [Disetujui oleh " . $status_approval . "]" : "" ?></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    }
                                    foreach ($raks_entry as $main) {
                                    ?>
                                        <tr style="font-weight: bold;">
                                            <td hidden="true"></td>
                                            <td hidden="true" class="kode-entry"><?php echo $main->kode; ?></td>
                                            <td><?php echo $main->indented . "" . $main->kode; ?></td>
                                            <td class="nama-entry"><?php echo $main->nama; ?></td>
                                            <td class="nama-entry"><?php echo $main->totalPrice != 0 ? number_format($main->totalPrice, 0, ',', '.') : ''; ?></td>

                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                        <!-- TAbel RAKS -->
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
    $(".btnApproved").click(function() {
        let approval = $(this).attr("data-approval");
        let userConfirmed = confirm("Apakah anda yakin akan menyetujui RAKS ini?");
        if (userConfirmed) {
            $.ajax({
                url: '<?php echo base_url(); ?>manage/raks_approval/approve',
                type: 'POST',
                data: {
                    approval: approval,
                    majors_id: <?php echo $s['m'] ?>,
                    period_id: <?php echo $s['p'] ?>
                },
                success: function(res) {
                    if (res) {
                        location.reload();
                    }
                },
                error: function(msg) {
                    alert('msg');
                }

            });
        }
    });
</script>