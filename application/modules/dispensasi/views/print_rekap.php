<!DOCTYPE html>
<html>
<head>
	<title><?php echo 'Data Rekap Dispensasi Siswa'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header-table, .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
        }
        .header-table td {
            padding: 5px;
        }
        .title {
            font-weight: bold;
            font-size: 14pt;
            padding-bottom: 5px;
        }
        .sub-title {
            font-size: 8pt;
        }
        .main-table th, .main-table td {
            font-size: 8pt;
            padding: 5px;
            border: 1px solid #000;
            text-align: center;
        }
        .main-table th {
            font-weight: bold;
        }
        .main-table ol {
            padding-left: 15px;
            margin: 0;
        }
        .main-table li {
            text-align: left;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="title"><?php echo $setting_school['setting_value']; ?></div>
                <div class="sub-title">
                    <?php echo $setting_address['setting_value']; ?>, Telp. <?php echo $setting_phone['setting_value']; ?>
                </div>
            </td>
        </tr>
    </table>
    <table class="header-table">
        <tr>
            <td style="width: 70mm; text-align: center;"></td>
            <td style="width: 50mm; text-align: center;">
                <b>Rekap Data Dispensasi Siswa</b>
            </td>
            <td style="width: 92mm; text-align: right;"></td>
        </tr>
    </table>
    <br>
    <table class="main-table">
        <tr>
            <th>No.</th>
            <th>No. Surat Keterangan</th>
            <th>Nama Siswa PJ</th>
            <th>NIS Siswa</th>
            <th>Kelas</th>
            <th>Anggota</th>
            <th>Keperluan</th>
            <th>Tempat</th>
            <th>Tanggal Kegiatan</th>
        </tr>
        <?php
            $i = 1;
            foreach ($dispensasi as $row) {
        ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><?= $row['dispensasi_nomor_surat_id']; ?></td>
            <td><?= $row['student_full_name']; ?></td>
            <td><?= $row['student_nis']; ?></td>
            <td><?= $row['class_name']; ?></td>
            <td style="width:25%;">
                <ol>
                    <?php  
                    $pengikut = $row['dispensasi_student_id'];
                    $ids = array_map('intval', explode(",", $pengikut));
                    $ids = implode(",", $ids);
                    $query = "SELECT student_full_name, student_nis, class_name FROM student LEFT JOIN class ON class.class_id = student.class_class_id WHERE student_id IN ($ids)";
                    $data = $this->db->query($query)->result_array();

                    foreach ($data as $string) {
                    ?>
                    <li><?= $string['student_full_name'] . ' (' . $string['student_nis'] . ') ' . $string['class_name']; ?></li>
                    <?php
                    }
                    ?>
                </ol>
            </td>
            <td><?= $row['dispensasi_desc']; ?></td>
            <td><?= $row['dispensasi_lokasi']; ?></td>
            <td><?= $row['dispensasi_date']; ?> (<?= $row['dispensasi_time_start']; ?> s/d <?= $row['dispensasi_time_end']; ?>)</td>
        </tr>
        <?php 
            } 
        ?>
    </table>
</body>
</html>
