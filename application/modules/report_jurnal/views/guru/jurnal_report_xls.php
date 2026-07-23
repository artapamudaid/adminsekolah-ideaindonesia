<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekap_Presensi_Mapel_" . $lesson_name['lesson_name'] . " Kelas " . $class_name['class_name'] . ".xls");
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