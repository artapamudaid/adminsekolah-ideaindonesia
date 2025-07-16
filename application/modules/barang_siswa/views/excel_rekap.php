<?php

    $filename = 'Data_Rekap_Permintaan_barang';    

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename = ".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

<html>
<head>
    <title><?php echo 'Data Rekap Permintaan Barang'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header-table {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            width: 100%;
        }
        .header-title {
            font-weight: bold;
            font-size: 14pt;
            padding-bottom: 5px;
        }
        .header-info {
            font-size: 8pt;
        }
        .title-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .title-table td {
            font-size: 8pt;
            text-align: center;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            font-size: 8pt;
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
        }
        .data-table th {
            font-weight: bold;
        }
        .data-table ol {
            padding-left: 20px;
            margin: 0;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr valign="top">
            <td valign="middle">
                <div class="header-title"><?php echo $setting_school['setting_value']; ?></div>
                <span class="header-info"><?php echo $setting_address['setting_value']; ?>, Telp. <?php echo $setting_phone['setting_value']; ?></span>
            </td>
        </tr>
    </table>

    <table class="title-table">
        <tr>
            <td style="width: 33%;"></td>
            <td style="width: 33%;"><b>Rekap Data Permintaan Barang</b></td>
            <td style="width: 33%; text-align: right;"></td>
        </tr>
    </table>

    <table class="data-table">
        <tr>
            <th>No.</th>
            <th>No. Permintaan Barang</th>
            <th>Nama Siswa</th>
            <th>NIS Siswa</th>
            <th>Barang Permintaan</th>
        </tr>
        <?php
        $i = 1;
        foreach ($barang_siswa as $row) {
            ?>
            <tr valign="top">
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['barang_siswa_no']; ?></td>
                <td><?php echo $row['student_full_name']; ?></td>
                <td><?php echo $row['student_nis']; ?></td>
                <td style="width:50%;">
                    <ol>
                        <?php
                        $barang = $row['barang_siswa_id'];
                        $data = $this->db->query("SELECT b.barang_name, a.jumlah FROM barang_permintaan_siswa a JOIN barang b ON a.barang_id = b.barang_id WHERE a.barang_siswa_id = '$barang'")->result_array();
                        foreach ($data as $item) {
                            echo '<li>' . $item['barang_name'] . ' (' . $item['jumlah'] . ')</li>';
                        }
                        ?>
                    </ol>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</body>
</html>
