<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan-Neraca-per-Tanggal-" . pretty_date($_REQUEST['start'], 'd-m-Y', FALSE) . "-Sampai-" . pretty_date($_REQUEST['end'], 'd-m-Y', FALSE) . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<table style='border-bottom: 1px solid #999999; padding-bottom: 10px; width: 190mm;'>
    <tr valign='top'>
        <td colspan='7' valign='middle'>
            <div style='font-weight: bold; padding-bottom: 5px; font-size: 14pt;'><?php echo $setting_school['setting_value'] ?>
            </div>
            <span style='font-size: 8pt;'><?php echo $setting_address['setting_value'] ?>, Telp.
                <?php echo $setting_phone['setting_value'] ?></span>
        </td>
    </tr>
</table>
<br>
<table cellpadding='0' cellspacing='0' style='width: 190mm;'>
    <tr>
        <div style='font-weight: bold; width: 24mm; font-size: 12pt; text-align: left'>Tanggal : </td>
            <td style='font-size: 12pt; text-align: left'><?php echo pretty_date($_REQUEST['start'], 'd F Y', false) . 's/d' . pretty_date($_REQUEST['end'], 'd F Y', false) ?></td>
            <td colspan='5' style='font-size: 8pt; text-align: left'></td>
    </tr>
</table>
<br>
<?php
echo '<table cellpadding="0" cellspacing="0" style="width: 190mm;">
    <tr>
        <td>
<table class="table table-responsive" style="white-space: nowrap;">
    <tr>
        <td>
        <div class="md-6">
            <b>AKTIVA</b>
        </div>
        </td>
        <td>
        <div class="md-6">
            <b>HUTANG & MODAL</b>
        </div>
        </td>
    </tr>
    <tr>
        <td>
        <div class="md-6">
            <table class="table table-responsive" style="white-space: nowrap;">';

$sumAktiva = 0;
foreach ($aktiva as $row) {
    echo '
          <tr>
            <td colspan="2">' . $row['account_description'] . '</td>
            <td>' . 'Rp ' . number_format($row['saldo'], 0, ",", ".") . '</td>
        </tr>';
    $sumAktiva += $row['saldo'];
}
echo '<tr style="background-color: #fcfdff;">
        <td colspan = "2" align = "right"><strong>Total Aktiva</strong></td>
        <td>' . 'Rp ' . number_format($sumAktiva, 0, ",", ".") . '</td>
        </tr>
        </table>
    </div>
    </td>
    <td>
    <div class="md-6">
    <table class="table table-responsive" style="white-space: nowrap;">';

$sumPasiva = 0;
foreach ($pasiva as $row) {
    echo '
                      <tr>
                        <td colspan="2">' . $row['account_description'] . '</td>
                        <td>' . 'Rp ' . number_format($row['saldo'], 0, ",", ".") . '</td>
                    </tr>';
    $sumPasiva += $row['saldo'];
}
echo '<tr style="background-color: #fcfdff;">
                    <td colspan = "2" align = "right"><strong>Total Hutang</strong></td>
                    <td>' . 'Rp ' . number_format($sumPasiva, 0, ",", ".") . '</td>
                    </tr>
                    </table>
                    <table class="table table-responsive" style="white-space: nowrap;">';

$sumModal = 0;
foreach ($modal as $row) {
    echo '
                                      <tr>
                                        <td colspan="2">' . $row['account_description'] . '</td>
                                        <td>' . 'Rp ' . number_format($row['saldo'], 0, ",", ".") . '</td>
                                    </tr>';
    $sumModal += $row['saldo'];
}
echo '<tr style="background-color: #fcfdff;">
                                    <td colspan = "2" align = "right"><strong>Total Modal</strong></td>
                                    <td>' . 'Rp ' . number_format($sumModal, 0, ",", ".") . '</td>
                                    </tr>
                                    </table>';
echo '<table class="table table-responsive" style="white-space: nowrap;">
<tr style="background-color: #fcfdff;">
                                    <td colspan = "2" align = "right"><strong>Total Laba/Rugi</strong></td>
                                    <td>' . 'Rp ' . number_format($laba_rugi['nominal'], 0, ",", ".") . '</td>
                                    </tr>
                                    </table>';

echo '</div>
    </td>
</tr>
</table>

</td>
</tr>
<tr>
<td>
<table cellpadding="0" cellspacing="0" style="width: 190mm; font-weight: bold; text-align: right;">
<tr>
<td style="font-weight: bold; text-align: right;">

<font size="4">' . 'Rp ' . number_format($sumAktiva, 0, ",", ".") . '</font>

</td>
<td style="font-weight: bold; text-align: right;">

<font size="4">' . 'Rp ' . number_format($sumPasiva + $sumModal + $laba_rugi['nominal'], 0, ",", ".") . '</font>

</td>
</tr>
</table>
</td>

</tr>
</table>';



?>