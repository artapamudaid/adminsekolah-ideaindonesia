<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Nilai_Sumatif_Mapel_" . $lesson_name['lesson_name'] . " Kelas " . $class_name['class_name'] . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table width="100%">
    <tr>
        <td width="10%">Kelas</td>
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
<table width="100%">
    <tr>
        <td>
            <h3>Nilai Sumatif</h3>
        </td>
    </tr>
</table>
<br>

<table border="1" width="100%">
    <thead>
        <tr>
            <th width='5' rowspan='2'>
                <center>No.</center>
            </th>
            <th rowspan='2'>
                <center>Nama</center>
            </th>
            <th colspan='<?php echo count($columns) ?>'>
                <center><?php echo 'NILAI HARIAN' ?></center>
            </th>
            <th width='5' rowspan='2'>
                <center>RATA-RATA</center>
            </th>
        </tr>
        <tr>
            <?php foreach ($columns as $index => $column): ?>
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
                // Temukan data sumatif milik student ini
                $dataSiswa = array_filter($nilai_sumatif, function ($item) use ($row) {
                    return $item['sumatif_student_id'] == $row['student_id'];
                });

                // Ambil entri pertama (jika ada)
                $nilaiSiswa = reset($dataSiswa);

                // Cetak nilai sumatif
                $sumSumatif = 0;
                $count = 0;
                foreach ($columns as $column) {
                    $nilai = isset($nilaiSiswa[$column]) ? $nilaiSiswa[$column] : '';
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
        <?php endforeach; ?>
    </tbody>
</table>