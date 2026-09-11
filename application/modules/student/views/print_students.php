<page backtop="3mm" backbottom="3mm" backleft="4mm" backright="4mm">
    <table style='border-bottom: 1px solid #000000; padding-bottom: 10px; width: 267mm;'>
        <tr valign='top'>
            <td style='width: 267mm;' valign='middle'>
                <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value']; ?></div>
                <span style='font-size: 8pt;'><?php echo $setting_address['setting_value']; ?>, Telp. <?php echo $setting_phone['setting_value']; ?></span>
            </td>
        </tr>
    </table>
    <br>
    <div style='width: 267mm; font-size: 10pt; font-weight: bold; text-align: center; margin-bottom: 5px;'>
        <?php echo 'Data Siswa ' . $majors['majors_short_name'] . ' Kelas ' . $kelas['class_name']; ?>
    </div>
    <br>
    <table cellpadding='0' cellspacing='0' style='width: 267mm;'>
        <thead>
            <tr>
                <th align='center' style='font-size: 8pt; width: 7mm; padding: 6px 2px; border: 1px solid #000000;'>No.</th>
                <th align='center' style='font-size: 8pt; width: 20mm; padding: 6px 2px; border: 1px solid #000000;'>NIS</th>
                <th align='center' style='font-size: 8pt; width: 23mm; padding: 6px 2px; border: 1px solid #000000;'>NISN</th>
                <th align='center' style='font-size: 8pt; width: 40mm; padding: 6px 2px; border: 1px solid #000000;'>Nama Lengkap</th>
                <th align='center' style='font-size: 8pt; width: 18mm; padding: 6px 2px; border: 1px solid #000000;'>Jenis Kelamin</th>
                <th align='center' style='font-size: 8pt; width: 63mm; padding: 6px 2px; border: 1px solid #000000;'>Alamat</th>
                <th align='center' style='font-size: 8pt; width: 25mm; padding: 6px 2px; border: 1px solid #000000;'>Nama Ayah</th>
                <th align='center' style='font-size: 8pt; width: 25mm; padding: 6px 2px; border: 1px solid #000000;'>Nama Ibu</th>
                <th align='center' style='font-size: 8pt; width: 23mm; padding: 6px 2px; border: 1px solid #000000;'>No. HP/Telp</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($student as $row) {
                $student_address_fmt = $row['student_address'] ? nl2br(htmlspecialchars($row['student_address'])) : '';
            ?>
                <tr valign='top'>
                    <td align="center" style='width: 7mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $i++ ?></td>
                    <td align="center" style='width: 20mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_nis'] ?></td>
                    <td align="center" style='width: 23mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_nisn'] ?></td>
                    <td style='width: 40mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_full_name'] ?></td>
                    <td align="center" style='width: 18mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_gender'] ?></td>
                    <td style='width: 63mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $student_address_fmt ?></td>
                    <td style='width: 25mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_name_of_father'] ?></td>
                    <td style='width: 25mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_name_of_mother'] ?></td>
                    <td style='width: 23mm; padding: 4px 2px; font-size: 8pt; border: 1px solid #000000;'><?php echo $row['student_parent_phone'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</page>