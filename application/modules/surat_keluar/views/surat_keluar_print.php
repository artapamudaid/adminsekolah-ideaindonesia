<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <table style='border-bottom: 1px solid #000000; padding-bottom: 10px; width: 277mm;'>
        <tr valign='top'>
            <td style='width: 277mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value']; ?>
                </div>
                <span style='font-size: 8pt;'><?php echo $setting_address['setting_value']; ?>, Telp.
                    <?php echo $setting_phone['setting_value']; ?></span>
            </td>
        </tr>
    </table>
    <br>
    <table style='width: 277mm;'>
        <tr>
            <td style='width: 92mm; font-size: 8pt; text-align: center' valign='center'></td>
            <td style='width: 93mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Data Surat Keluar ' . $majors['majors_short_name']; ?></b></td>
            <td style='width: 92mm; font-size: 8pt;' align='right'></td>
        </tr>
    </table>
    <br>
    <table cellpadding='0' cellspacing='0' style='width: 277mm; padding-left: 0mm;'>
        <tr>
            <th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
            <th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No. Surat </th>
            <th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tujuan </th>
            <th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Jenis (Kode) Surat </th>
            <th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Surat</th>
            <th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Input</th>
            <th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>File Lampiran</th>
        </tr>
        <?php
        $i = 1;
        foreach ($surat_keluar as $row) {
        ?>
            <tr valign='top'>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['no_surat'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tujuan'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['nama_jenis'] ?> ( <?php echo $row['kode_surat'] ?> )</td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tgl_surat'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tgl_catat'] ?></td>
                <?php if ($row['file']==null){ ?>
                    <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><label class="btn btn-xs btn-danger">File Lampiran Kosong</label></td>
                <?php }else{ ?>
                    <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'>
                        <a href="#dwlSk<?php echo $row['id_surat']; ?>" data-toggle="modal" class="btn btn-xs btn-success"><i class="fa fa-download" data-toggle="tooltip" title="Download">File Tersedia</i></a>
                    </td>
                <?php }?>
            </tr>
        <?php } ?>
    </table>
</body>

</html>