<?php
$setting_date = $this->db->get_where('setting', ['setting_name' => 'setting_date'])->row();
$setting_date_value = $setting_date ? $setting_date->setting_value : null;
$start_date_fmt = '';
if ($setting_date_value) {
    $start_date_fmt = pretty_date($setting_date_value, 'd F Y', false);
}
?>
<?php if ($location === 'navbar'): ?>
    <li id="app-suspend-info" class="dropdown tasks-menu">
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-calendar"></i>
            <span class="hidden-xs">Aktif hingga: <?= pretty_date($suspend_date, 'd F Y', false); ?></span>
        </a>
        <ul class="dropdown-menu" style="">
            <li class="header">
                <div>
                    <i class="fa fa-calendar"></i>
                    <strong>Mulai langganan:</strong> <?= $start_date_fmt; ?>
                </div>
                <div>
                    <i class="fa fa-calendar"></i>
                    <strong>Selesai langganan:</strong> <?= pretty_date($suspend_date, 'd F Y', false); ?>
                </div>
                <div>
                    <i class="fa fa-clock-o"></i>
                    <strong>Tersisa:</strong> <?= $days_left; ?> hari
                </div>
            </li>
            <li class="footer" style="display: flex; flex-direction: row; gap: 5px; justify-content: center; border: none; padding: 5px 0;">
                <button class="btn btn-default btn-sm" id="extend-subscription">Perpanjang</button>
                <button class="btn btn-default btn-sm" id="make-deposit">Deposit</button>
            </li>
        </ul>
    </li>
<?php else: ?>
    <div id="app-suspend-info" class="<?= $alert_class; ?>" style="margin-bottom: 15px;">
        <h4><i class="icon fa <?= $icon; ?>"></i> <strong><?= $title_text; ?></strong></h4>
        <p style="margin: 0; white-space: normal; word-wrap: break-word; word-break: break-word; overflow-wrap: anywhere;">
            <?= $message; ?>
        </p>
    </div>
<?php endif; ?>

<script>
    $(function() {
        $('#extend-subscription').on('click', function() {
            var whatsappUrl = '<?= $whatsappUrl ?>';
            window.open(whatsappUrl, '_blank');
        });

        $('#make-deposit').on('click', function() {
            toastNotifikasi('info', 'Fitur deposit akan segera hadir. Silakan hubungi admin untuk informasi lebih lanjut.', 'Informasi');
        });
    });
</script>