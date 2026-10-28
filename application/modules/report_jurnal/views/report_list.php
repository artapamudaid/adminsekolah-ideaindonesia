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
    }

    /* Freeze kolom pertama (No.) */
    .table-freeze thead th:nth-child(1),
    .table-freeze tbody td:nth-child(1) {
        position: sticky;
        left: 0;
        z-index: 3;
        background-color: #fff;
    }

    /* Freeze kolom kedua (Nama) */
    .table-freeze thead th:nth-child(2),
    .table-freeze tbody td:nth-child(2) {
        position: sticky;
        left: 40px;
        /* Jarak left harus disesuaikan */
        z-index: 2;
        background-color: #fff;
    }

    /* Freeze kolom ketiga (Kamar) */
    .table-freeze thead th:nth-child(3),
    .table-freeze tbody td:nth-child(3) {
        position: sticky;
        left: 310px;
        /* Jarak left harus disesuaikan */
        z-index: 1;
        background-color: #fff;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <?php echo form_open(current_url(), array('method' => 'get')) ?>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Tahun Ajaran </label>
                                    <select required="" name="p" id="period_id" class="form-control" onchange="getSemester(this.value)">
                                        <option value="">-Pilih Tahun Ajaran-</option>
                                        <?php foreach ($period as $p) { ?>
                                            <option value="<?php echo $p['period_id'] ?>" <?= (isset($q['p']) && $q['p'] == $p['period_id']) ? 'selected' : '' ?>><?php echo $p['period_start'] . '/' . $p['period_end'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Semester </label>
                                    <select required="" name="s" id="semester_id" class="form-control">
                                        <option value="">-Pilih Semester-</option>

                                        <?php
                                        if (isset($q['p'])):
                                            foreach ($semester as $s) {
                                        ?>
                                                <option value="<?php echo $s['semester_id'] ?>" <?= ($q['s'] == $s['semester_id']) ? 'selected' : '' ?>><?php echo $s['semester_name'] ?></option>
                                        <?php }
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Guru</label>
                                    <select style="width: 100%;" class="form-control select2" name="t" id="guru_id" required="" onchange="getClass(this.value)">
                                        <option value="">-- Pilih Guru --</option>
                                        <?php foreach ($employees as $row) : ?>
                                            <option <?php echo (isset($q['t']) and $q['t'] == $row['employee_id']) ? 'selected' : '' ?> value="<?php echo $row['employee_id'] ?>"><?php echo $row['employee_nip'] . ' - ' . $row['employee_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select class="form-control" name="c" id="class_id" required="" onchange="get_lesson(this.value)">
                                        <option value="">- Pilih Kelas -</option><?php if (isset($q['l'])) { ?>
                                            <?php foreach ($class as $row) : ?>
                                                <option <?php echo (isset($q['c']) and $q['c'] == $row['class_id']) ? 'selected' : '' ?> value="<?php echo $row['class_id'] ?>"><?php echo $row['class_name'] ?></option>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="td_lesson">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Mapel</label>
                                        <select class="form-control" name="l" id="lesson_id" required="">
                                            <option value="">- Pilih Mapel -</option>
                                            <?php if (isset($q['l'])) { ?>
                                                <?php foreach ($lesson as $row) : ?>
                                                    <option <?php echo (isset($q['c']) and $q['l'] == $row['lesson_id']) ? 'selected' : '' ?> value="<?php echo $row['lesson_id'] ?>"><?php echo $row['lesson_code'] . ' - ' . $row['lesson_name'] ?></option>
                                                <?php endforeach; ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Laporan</label>
                                    <select class="form-control" name="r" id="laporan_id" required="">
                                        <option value="">- Pilih Laporan -</option>
                                        <option value="presensi" <?= (isset($q['r']) && $q['r'] == 'presensi') ? 'selected' : '' ?>>Rekap Presensi</option>
                                        <option value="harian" <?= (isset($q['r']) && $q['r'] == 'harian') ? 'selected' : '' ?>>Rekap Nilai Harian</option>
                                        <option value="sumatif" <?= (isset($q['r']) && $q['r'] == 'sumatif') ? 'selected' : '' ?>>Rekap Nilai Sumatif</option>
                                        <option value="akhir" <?= (isset($q['r']) && $q['r'] == 'akhir') ? 'selected' : '' ?>>Draft Nilai Akhir</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div style="margin-top:25px;">
                                    <button type="submit" class="btn btn-primary btn-block"> Pilih</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>

                <?php if ($q) { ?>
                    <?php if ($q['r'] == 'presensi') : ?>
                        <div class="box box-success">
                            <div class="box-body">
                                <div style="margin-top: 10px;">
                                    <?php if (count((array)$kehadiran) > 0) : ?>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-freeze">
                                                        <thead>
                                                            <tr>
                                                                <th width='5'>
                                                                    <center>No.</center>
                                                                </th>
                                                                <th>
                                                                    <center>Nama</center>
                                                                </th>
                                                                <th colspan='<?php echo $pertemuan ?>'>
                                                                    <center><?php echo 'Kehadiran' ?></center>
                                                                </th>
                                                                <th colspan='<?php echo 4 ?>'>
                                                                    <center><?php echo 'Jumlah' ?></center>
                                                                </th>
                                                                <th>
                                                                    <center>Hadir (%)</center>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                                <?php
                                                                for ($i = 1; $i <= $pertemuan; $i++) {
                                                                    echo '<th style="width:10px;">' . $i . '</th>';
                                                                }
                                                                ?>
                                                                <th>H</th>
                                                                <th>I</th>
                                                                <th>S</th>
                                                                <th>A</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            $pres_hadir = 0;
                                                            $hadir = 'hadir';
                                                            $alpha = 'alpha';
                                                            $sakit = 'sakit';
                                                            $izin = 'izin';

                                                            $presensi = array();
                                                            foreach ($kehadiran as $val) {
                                                                $presensi[] = $val;
                                                            }
                                                            foreach ($student as $row) :

                                                                ${$hadir . $row['student_id']} = 0;
                                                                ${$izin . $row['student_id']} = 0;
                                                                ${$sakit . $row['student_id']} = 0;
                                                                ${$alpha . $row['student_id']} = 0;
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $no++ ?></td>
                                                                    <td><?php echo $row['student_full_name'] ?></td>
                                                                    <?php
                                                                    foreach ($presensi as $presen) {
                                                                        if ($row['student_id'] == $presen['presensi_pelajaran_student_id']) {
                                                                    ?>
                                                                            <td style="width:10px;"><?= $presen['presensi_pelajaran_status'] ?></td>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <td>
                                                                        <?php
                                                                        foreach ($presensi as $presen) {
                                                                            if ($row['student_id'] == $presen['presensi_pelajaran_student_id'] && $presen['presensi_pelajaran_status'] == 'H') {
                                                                                ${$hadir . $row['student_id']} += 1;
                                                                            } else {
                                                                                ${$hadir . $row['student_id']} += 0;
                                                                            }
                                                                        } ?>
                                                                        <?= ${$hadir . $row['student_id']} ?>
                                                                    </td>

                                                                    <td>
                                                                        <?php
                                                                        foreach ($presensi as $presen) {
                                                                            if ($row['student_id'] == $presen['presensi_pelajaran_student_id'] && $presen['presensi_pelajaran_status'] == 'I') {
                                                                                ${$izin . $row['student_id']} += 1;
                                                                            } else {
                                                                                ${$izin . $row['student_id']} += 0;
                                                                            }
                                                                        } ?>
                                                                        <?= ${$izin . $row['student_id']} ?>
                                                                    </td>

                                                                    <td>
                                                                        <?php
                                                                        foreach ($presensi as $presen) {
                                                                            if ($row['student_id'] == $presen['presensi_pelajaran_student_id'] && $presen['presensi_pelajaran_status'] == 'S') {
                                                                                ${$sakit . $row['student_id']} += 1;
                                                                            } else {
                                                                                ${$sakit . $row['student_id']} += 0;
                                                                            }
                                                                        } ?>
                                                                        <?= ${$sakit . $row['student_id']} ?>
                                                                    </td>

                                                                    <td>
                                                                        <?php
                                                                        foreach ($presensi as $presen) {
                                                                            if ($row['student_id'] == $presen['presensi_pelajaran_student_id'] && $presen['presensi_pelajaran_status'] == 'A') {
                                                                                ${$alpha . $row['student_id']} += 1;
                                                                            } else {
                                                                                ${$alpha . $row['student_id']} += 0;
                                                                            }
                                                                        } ?>
                                                                        <?= ${$alpha . $row['student_id']} ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $pres_hadir = round((${$hadir . $row['student_id']} / $pertemuan  * 100), 2);
                                                                        echo $pres_hadir . '%';
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12" style="margin-top: 10px;">
                                                <a class="btn btn-primary" href="<?= base_url() . 'manage/report_jurnal/jurnal?p=' . $q['p'] . '&s=' . $q['s'] . '&t=' . $q['t'] . '&c=' . $q['c'] . '&l=' . $q['l'] ?>" target="_blank">Download Rekap Presensi Santri <i class="fa fa-download"></i></button></a>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        Belum Ada Data
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if ($q['r'] == 'harian') : ?>

                        <?php
                        // Ambil semua nama kolom harian dari entri pertama
                        $kolomHarian = [];
                        if (!empty($nilai_harian)) {
                            foreach ($nilai_harian[0] as $key => $val) {
                                if (preg_match('/^harian_\d+$/', $key)) {
                                    $kolomHarian[] = $key;
                                }
                            }
                        }

                        // Urutkan berdasarkan angka setelah 'harian_'
                        usort($kolomHarian, function ($a, $b) {
                            return (int) filter_var($a, FILTER_SANITIZE_NUMBER_INT) - (int) filter_var($b, FILTER_SANITIZE_NUMBER_INT);
                        });
                        ?>

                        <div class="box box-success">
                            <div class="box-body">
                                <div style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th width='5' rowspan='2'>
                                                                <center>No.</center>
                                                            </th>
                                                            <th rowspan='2'>
                                                                <center>Nama</center>
                                                            </th>
                                                            <th colspan='<?= count($kolomHarian) ?>'>
                                                                <center>NILAI HARIAN</center>
                                                            </th>
                                                            <th width='5' rowspan='2'>
                                                                <center>RATA-RATA</center>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <?php foreach ($kolomHarian as $index => $kolom): ?>
                                                                <th><?= $index + 1 ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $no = 1;
                                                        foreach ($student as $row) :
                                                        ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= $row['student_full_name'] ?></td>

                                                                <?php
                                                                // Temukan data harian milik student ini
                                                                $dataSiswa = array_filter($nilai_harian, function ($item) use ($row) {
                                                                    return $item['harian_student_id'] == $row['student_id'];
                                                                });

                                                                // Ambil entri pertama (jika ada)
                                                                $nilaiSiswa = reset($dataSiswa);

                                                                // Cetak nilai harian
                                                                $sumHarian = 0;
                                                                $count = 0;
                                                                foreach ($kolomHarian as $kolom) {
                                                                    $nilai = isset($nilaiSiswa[$kolom]) ? $nilaiSiswa[$kolom] : '';
                                                                    echo "<td>" . ($nilai !== '' ? $nilai : '-') . "</td>";

                                                                    if ($nilai !== '' && $nilai != 0) {
                                                                        $sumHarian += $nilai;
                                                                        $count++;
                                                                    }
                                                                }
                                                                ?>
                                                                <!-- Kolom RATA-RATA -->
                                                                <td><?= $count > 0 ? round($sumHarian / $count, 2) : '-' ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12" style="margin-bottom: 10px;">
                                            <a class="btn btn-primary" href="<?= base_url() . 'manage/report_jurnal/nilai_harian?p=' . $q['p'] . '&s=' . $q['s'] . '&t=' . $q['t'] . '&c=' . $q['c'] . '&l=' . $q['l'] ?>" target="_blank">
                                                Download Nilai Harian Santri <i class="fa fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <?php endif; ?>

                    <?php if ($q['r'] == 'sumatif') : ?>

                        <?php
                        // Ambil semua nama kolom sumatif dari entri pertama
                        $kolomSumatif = [];
                        if (!empty($nilai_sumatif)) {
                            foreach ($nilai_sumatif[0] as $key => $val) {
                                if (preg_match('/^sumatif_\d+$/', $key)) {
                                    $kolomSumatif[] = $key;
                                }
                            }
                        }

                        // Urutkan berdasarkan angka setelah 'sumatif_'
                        usort($kolomSumatif, function ($a, $b) {
                            return (int) filter_var($a, FILTER_SANITIZE_NUMBER_INT) - (int) filter_var($b, FILTER_SANITIZE_NUMBER_INT);
                        });

                        ?>


                        <div class="box box-success">
                            <div class="box-body">
                                <div style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width='5' rowspan='2'>
                                                                <center>No.</center>
                                                            </th>
                                                            <th rowspan='2'>
                                                                <center>Nama</center>
                                                            </th>
                                                            <th colspan='<?php echo count($kolomSumatif) ?>'>
                                                                <center><?php echo 'NILAI SUMATIF' ?></center>
                                                            </th>
                                                            <th width='5' rowspan='2'>
                                                                <center>RATA-RATA</center>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            foreach ($kolomSumatif as $index => $kolom): ?>
                                                                <th><?= $index + 1 ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $no = 1;

                                                        $sumatif = array();
                                                        foreach ($nilai_sumatif as $val) {
                                                            $sumatif[] = $val;
                                                        }
                                                        foreach ($student as $row) :
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $no++ ?></td>
                                                                <td><?php echo $row['student_full_name'] ?></td>
                                                                <?php
                                                                // Temukan data sumatif milik student ini
                                                                $dataSiswa = array_filter($nilai_sumatif, function ($item) use ($row) {
                                                                    return $item['sumatif_student_id'] == $row['student_id'];
                                                                });

                                                                // Ambil entri pertama (jika ada)
                                                                $nilaiSiswa = reset($dataSiswa);

                                                                // Cetak nilai sumatif
                                                                $sumSumatif = 0;
                                                                $count = 0;
                                                                foreach ($kolomSumatif as $kolom) {
                                                                    $nilai = isset($nilaiSiswa[$kolom]) ? $nilaiSiswa[$kolom] : '';
                                                                    echo "<td>" . ($nilai !== '' ? $nilai : '-') . "</td>";

                                                                    if ($nilai !== '' && $nilai != 0) {
                                                                        $sumSumatif += $nilai;
                                                                        $count++;
                                                                    }
                                                                }
                                                                ?>
                                                                <!-- Kolom RATA-RATA -->
                                                                <td><?= $count > 0 ? round($sumSumatif / $count, 2) : '-' ?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12" style="margin-bottom: 10px;">
                                            <a class="btn btn-primary" href="<?= base_url() . 'manage/report_jurnal/nilai_sumatif?p=' . $q['p'] . '&s=' . $q['s'] . '&t=' . $q['t'] . '&c=' . $q['c'] . '&l=' . $q['l'] ?>" target="_blank">Download Nilai Sumatif Santri <i class="fa fa-download"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($q['r'] == 'akhir') : ?>
                        <div class="box box-success">
                            <div class="box-body">
                                <div style="margin-top: 10px;">
                                    <?php if (count((array)$bobot) > 0): ?>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th width='5' rowspan='3'>
                                                                    <center>No.</center>
                                                                </th>
                                                                <th rowspan='3'>
                                                                    <center>Nama</center>
                                                                </th>
                                                                <th colspan='<?php echo 3 ?>'>
                                                                    <center><?php echo 'BOBOT' ?></center>
                                                                </th>
                                                                <th colspan='<?php echo 4 ?>' rowspan='3'>
                                                                    <center><?php echo 'NILAI AKHIR' ?></center>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th><?= $bobot['absensi'] ?>%</th>
                                                                <th><?= $bobot['harian'] ?>%</th>
                                                                <th><?= $bobot['sumatif'] ?>%</th>
                                                            </tr>
                                                            <tr>
                                                                <th>PRESENSI</th>
                                                                <th>HARIAN</th>
                                                                <th>SUMATIF</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;

                                                            $presensi = array();
                                                            foreach ($kehadiran as $val) {
                                                                $presensi[] = $val;
                                                            }

                                                            $sumatif = array();
                                                            foreach ($rata_sumatif as $val) {
                                                                $sumatif[] = $val;
                                                            }

                                                            $harian = array();
                                                            foreach ($rata_harian as $val) {
                                                                $harian[] = $val;
                                                            }

                                                            $hadir = 0;
                                                            $bobot_hadir = 0;

                                                            $nilai_sumatif = 0;
                                                            $bobot_umatif = 0;

                                                            $nilai_harian = 0;
                                                            $bobot_harian = 0;

                                                            foreach ($student as $row) :
                                                                $student_id = $row['student_id'];
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $no++ ?></td>
                                                                    <td><?php echo $row['student_full_name'] ?></td>
                                                                    <?php
                                                                    foreach ($presensi as $presen) {
                                                                        if ($student_id == $presen['presensi_pelajaran_student_id']) {
                                                                            $hadir = $presen['presen'] ?? 0;
                                                                        }
                                                                        $bobot_hadir = round(($hadir ?? 0) * ($bobot['absensi'] ?? 0) / 100, 2, PHP_ROUND_HALF_UP);
                                                                    }
                                                                    ?>
                                                                    <td><?= round($hadir ?? 0, 2) ?></td>
                                                                    <?php
                                                                    foreach ($harian as $h) {
                                                                        if ($student_id == $h['harian_student_id']) {
                                                                            $nilai_harian = $h['rata_harian'] ?? 0;
                                                                        }
                                                                        $bobot_harian = round(($nilai_harian ?? 0) * ($bobot['harian'] ?? 0) / 100, 2, PHP_ROUND_HALF_UP);
                                                                    }
                                                                    ?>
                                                                    <td><?= round($nilai_harian ?? 0, 2) ?></td>
                                                                    <?php
                                                                    foreach ($sumatif as $s) {
                                                                        if ($student_id == $s['sumatif_student_id']) {
                                                                            $nilai_sumatif = $s['rata_sumatif'] ?? 0;
                                                                        }
                                                                        $bobot_sumatif = round(($nilai_sumatif ?? 0) * ($bobot['sumatif'] ?? 0) / 100, 2, PHP_ROUND_HALF_UP);
                                                                    }
                                                                    ?>
                                                                    <td><?= round($nilai_sumatif ?? 0, 2) ?></td>
                                                                    <td><?= ($bobot_hadir ?? 0) + ($bobot_harian ?? 0) + ($bobot_sumatif ?? 0) ?></td>
                                                                </tr>
                                                            <?php endforeach ?>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12" style="margin-bottom: 10px;">
                                                <a class="btn btn-primary" href="<?= base_url() . 'manage/report_jurnal/nilai_akhir?p=' . $q['p'] . '&s=' . $q['s'] . '&t=' . $q['t'] . '&c=' . $q['c'] . '&l=' . $q['l'] ?>" target="_blank">Download Draft Nilai Akhir <i class="fa fa-download"></i></a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12" style="margin-bottom: 10px;">
                                                Maaf, Anda Belum Setting Bobot Nilai. Silahkan Setting <a class="btn btn-info btn-sm" href="<?= base_url() . 'manage/profile/edit_bobot' ?>" target="_blank">Disini</a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                <?php } ?>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<script>
    getSemester = function(period_id) {
        $.ajax({
            url: "<?php echo site_url('manage/report_jurnal/get_semester') ?>",
            type: "POST",
            data: {
                period_id: period_id
            },
            dataType: "json",
            success: function(data) {
                var options = '<option value="">-Pilih Semester-</option>';
                $.each(data, function(index, semester) {
                    options += '<option value="' + semester.semester_id + '">' + semester.semester_name + '</option>';
                });
                $('select[id="semester_id"]').html(options);
            }
        });
    };

    getClass = function(guru_id) {
        $.ajax({
            url: "<?php echo site_url('manage/report_jurnal/get_class') ?>",
            type: "POST",
            data: {
                guru_id: guru_id
            },
            dataType: "json",
            success: function(data) {
                var options = '<option value="">-Pilih Kelas-</option>';
                $.each(data, function(index, kelas) {
                    options += '<option value="' + kelas.class_id + '">' + kelas.class_name + '</option>';
                });
                $('select[id="class_id"]').html(options);
            }
        });
    };

    get_lesson = function(class_id) {
        var guru_id = $('#guru_id').val();
        if (guru_id == '') {
            alert('Silahkan Pilih Guru Terlebih Dahulu');
            return false;
        }
        $.ajax({
            url: '<?php echo base_url(); ?>manage/report_jurnal/lesson_searching',
            type: 'POST',
            data: {
                'class_id': class_id,
                'guru_id': guru_id
            },
            success: function(msg) {
                $("#td_lesson").html(msg);
            },
            error: function(msg) {
                alert('msg');
            }

        });
    }
</script>