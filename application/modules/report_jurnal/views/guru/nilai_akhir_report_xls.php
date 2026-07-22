<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Draft_Nilai_Akhir_Mapel_" . $lesson_name['lesson_name'] . " Kelas " . $class_name['class_name'] . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table>
    <tr>
        <td width="15%">Kelas</td>
        <td width="5%">:</td>
        <td><?php echo $class_name['class_name'] ?></td>
    </tr>
    <tr>
        <td width="15%">Mata Pelajaran</td>
        <td width="5%">:</td>
        <td><?php echo $lesson_name['lesson_name'] ?></td>
    </tr>
    <tr>
        <td>Guru/Pengajar</td>
        <td>:</td>
        <td><?php echo $this->session->userdata('ufullname_guru'); ?></td>
    </tr>
</table>
<br>
<br>
<table border="1" width="100%">
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