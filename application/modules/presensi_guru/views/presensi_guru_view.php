<style>
    /* Styling dasar tabel */
    .table-freeze {
        width: 100%;
        border-collapse: collapse;
    }

    .table-freeze th,
    .table-freeze td {
        border: 1px solid #ddd;
        padding: 8px;
        min-width: 40px;
        white-space: nowrap;
        background-color: #f9f9f9;
        /* z-index: 1; */
    }

    /* Freeze kolom pertama (No.) */
    .table-freeze thead th:nth-child(1),
    .table-freeze tbody td:nth-child(1) {
        position: sticky;
        left: 10px;
        z-index: 4;
        background-color: #fff;
    }

    /* Freeze kolom kedua (Nama) */
    .table-freeze thead th:nth-child(2),
    .table-freeze tbody td:nth-child(2) {
        position: sticky;
        left: 45px;
        /* Jarak left harus disesuaikan */
        z-index: 3;
        background-color: #fff;
    }

    /* Freeze kolom ketiga (Kamar) */
    .table-freeze thead th:nth-child(3),
    .table-freeze tbody td:nth-child(3) {
        position: sticky;
        left: 105px;
        /* Jarak left harus disesuaikan */
        z-index: 2;
        background-color: #fff;
    }

    /* Freeze kolom ketiga (Kamar) */
    .table-freeze thead th:nth-child(4),
    .table-freeze tbody td:nth-child(4) {
        position: sticky;
        left: 340px;
        /* Jarak left harus disesuaikan */
        z-index: 1;
        background-color: #fff;
    }
</style>


<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <form action="<?= current_url() ?>" method="get">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Kelas</label>
                                        <select class="form-control" name="c" id="class_id" required="">
                                            <option value="">-- Pilih Kelas --</option>
                                            <?php foreach ($class as $row) : ?>
                                                <option <?php echo (isset($f['c']) and $row['class_id'] == $f['c']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <div class="input-group date " data-bs-date="" data-bs-date-format="yyyy-mm-dd">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input class="form-control" type="text" name="date" id="date" readonly="readonly" placeholder="Tanggal Awal" value="<?= (isset($f['date']) ? $f['date'] : date('Y-m-d')) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div style="margin-top:25px;">
                                        <button type="submit" class="btn btn-primary btn-block w-100"> Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div><?php if ($f): ?>
                        <div class="box-body">
                            <div class="row mt-10">
                                <?php
                                $jam = array();
                                foreach ($jampel as $j) {
                                    $jam[] = $j;
                                }
                                $jp = $jampel;
                                ?>
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table class="table table-bordered table-freeze" style="white-space: nowrap;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <center>No.</center>
                                                    </th>
                                                    <th>
                                                        <center>Kelas</center>
                                                    </th>
                                                    <th>
                                                        <center>Nama</center>
                                                    </th>
                                                    <?php
                                                    foreach ($daterange as $dt) {
                                                        $d = $dt->format("Y-m-d");
                                                        $h = pretty_date($d, 'l', false);

                                                        $cnt = 0;
                                                        foreach ($jp as $jm) {
                                                            if ($h === $jm['day_name']) {
                                                                $cnt++;
                                                            }
                                                        }

                                                        if ($cnt > 0) {
                                                    ?>
                                                            <th colspan="<?= $cnt ?>"><?= $dt->format("d/m/y") ?></th>
                                                    <?php
                                                        }
                                                    }
                                                    ?>

                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <?php
                                                    $jurnal = array();
                                                    foreach ($presensi as $val) {
                                                        $jurnal[] = $val;
                                                    }
                                                    foreach ($daterange as $dt) {
                                                        $date = $dt->format("Y-m-d");
                                                        $hari = pretty_date($date, 'l', false);

                                                        foreach ($jam as $jm) {
                                                            if ($hari === $jm['day_name']) {
                                                    ?>
                                                                <th><?= str_replace('Jam Ke-', '', $jm['jam_pelajaran_name']) ?></th>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                $no = 1;

                                                foreach ($student as $row) :
                                                    $student_id = $row['student_id'];
                                                ?>
                                                    <tr>
                                                        <td><?php echo $no++ ?></td>
                                                        <td><?php echo $row['class_name'] ?></td>
                                                        <td><?php echo $row['student_full_name'] ?></td>
                                                        <?php
                                                        foreach ($daterange as $dt) {
                                                            $tanggal = $dt->format("Y-m-d");
                                                            $day = pretty_date($tanggal, 'l', false);
                                                            $found = false;

                                                            foreach ($jam as $jm) {
                                                                if ($hari === $jm['day_name']) {
                                                                    foreach ($jurnal as $v) {
                                                                        if ($v['student_id'] === $student_id && $v['jurnal_date'] === $date && $v['jam_pelajaran_id'] === $jm['jam_pelajaran_id']) {
                                                        ?>
                                                                            <td><?= $v['jurnal_status'] ?></td>
                                                                <?php
                                                                            $found = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            if (!$found) {
                                                                ?>
                                                                <td>-</td>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>