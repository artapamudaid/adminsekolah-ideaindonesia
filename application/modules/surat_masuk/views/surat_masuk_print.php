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
            <td style='width: 93mm; font-size: 8pt; text-align: center' valign='top' align='center'><b><?php echo 'Data Surat Masuk ' . $majors['majors_short_name']; ?></b></td>
            <td style='width: 92mm; font-size: 8pt;' align='right'></td>
        </tr>
    </table>
    <br>
    <table cellpadding='0' cellspacing='0' style='width: 277mm; padding-left: 0mm;'>
        <tr>
            <th align='center' style='font-size: 8pt; width: 5mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No.</th>
            <th align='center' style='font-size: 8pt; width: 35mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No. Agenda </th>
            <th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>No. Surat </th>
            <th align='center' style='font-size: 8pt; width: 45mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Asal Surat </th>
            <th align='center' style='font-size: 8pt; width: 20mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Isi Surat </th>
            <th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Tanggal Diterima</th>
            <th align='center' style='font-size: 8pt; width: 50mm; padding: 1px 0px 1px 0px; border: 1px solid #000000;'>Lampiran File</th>
        </tr>
        <?php
        $i = 1;
        foreach ($surat_masuk as $row) {
        ?>
            <tr valign='top'>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['no_agenda'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['no_surat'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['asal_surat'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['isi'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['tgl_diterima'] ?></td>
                <td style='padding: 1px; font-size: 8pt; border: 1px solid #000000;'>
                <?php
                    if($row['id_disposisi']==NULL){
                    ?>
                        Tidak Ada Lampiran
                    <?php
                    }else{
                    ?>
                        <a href="<?php echo site_url('manage/surat_masuk/printdata/' . $row['id_surat']) ?>" target="_blank" data-toggle="modal" class="btn btn-xs btn-primary"><i class="fa fa-print" data-toggle="tooltip" title="Print Disposisi"></i>Lampiran Tersedia</a>
                    <?php
                    }
                ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>