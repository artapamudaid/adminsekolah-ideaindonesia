<div class="content-wrapper">
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12 col-sm-12 col-xs-12 pull-left">
                            <br>
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label>Tanggal <small data-bs-toggle="tooltip" title="Wajib diisi">*</small></label>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group date" data-bs-date="" data-bs-date-format="yyyy-mm-dd">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                <input class="form-control" type="text" name="date" id="izin_date" readonly="readonly" placeholder="Tanggal" value="<?php echo date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Pilih</button>
                                    </div>
                                </div>
                            </form>
                            <form action="<?= site_url('guru/izin_guru/save') ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="izin_date" value="<?= (!empty($f['date'])) ? $f['date'] : date('Y-m-d') ?>">
                                <?php if (!empty($f['date'])) : ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php

                                            if (!empty($schedules)) {
                                            ?>
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="selectall" value="checkbox" name="checkbox"></th>
                                                            <th>Jam</th>
                                                            <th>Kelas</th>
                                                            <th>Tugas untuk Siswa</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="text-align: center;">
                                                        <?php

                                                        foreach ($schedules as &$schedule) {
                                                            foreach ($izins as $izin) {
                                                                if ($schedule["schedule_id"] == $izin["schedule_id"]) {
                                                                    $schedule["is_izin"] = 1;
                                                                    $schedule["task"] = $izin['izin_detail_task'];
                                                                    break;
                                                                }
                                                            }

                                                            if (!isset($schedule["is_izin"])) {
                                                                $schedule["is_izin"] = 0;
                                                                $schedule["task"] = null;
                                                            }

                                                            foreach ($jurnals as $jurnal) {
                                                                if ($schedule["schedule_id"] == $jurnal["presensi_pelajaran_schedule_id"]) {
                                                                    $schedule["is_set"] = 1;
                                                                    break;
                                                                }
                                                            }

                                                            if (!isset($schedule["is_set"])) {
                                                                $schedule["is_set"] = 0;
                                                            }
                                                        }

                                                        foreach ($schedules as $row) :
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <?php if (($row['is_izin'] == 1) || ($row['is_set'] == 1)) : ?>
                                                                        <span class="label label-danger"><i class="fa fa-ban"></i></span>
                                                                    <?php else : ?>
                                                                        <input type="checkbox" class="checkbox" name="izin_detail_schedule_id[]" value="<?php echo $row['schedule_id']; ?>">
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?= $row['jam_pelajaran_name'] ?></td>
                                                                <td><?= $row['class_name'] ?></td>
                                                                <td><input type="text" class="form-control" name="izin_detail_task[]" value="<?= $row['task'] ?>" <?= (($row['is_izin'] == 1) || $row['is_set'] == 1) ? 'disabled' : '' ?>></td>
                                                            </tr>
                                                        <?php
                                                        endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php
                                            } else {
                                            ?>

                                                <table class="table table-hover">
                                                    <tr>
                                                        <td colspan="4" style="text-align: center;">Tidak Ada Jadwal Hari Ini</td>
                                                    </tr>

                                                </table>
                                            <?php } ?>
                                        </div>

                                        <div class="col-md-4 pull-right">
                                            <!-- aksi -->

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom: 30px;">
                                            <label>Keterangan Izin <small data-bs-toggle="tooltip" title="Wajib diisi">*</small></label>
                                            <textarea name="izin_note" id="izin_note" rows="5" class="form-control" required></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom: 30px;">
                                            <label for="">Upload Bukti/Surat Pendukung (Image atau PNG)</label>
                                            <input type="file" id="camera" name="camera">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-success btn-lg">Simpan</button>
                                        </div>
                                    </div>


                            </form>
                        </div>
                    <?php endif; ?>
                    <!-- /.box-body -->
                    </div>
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