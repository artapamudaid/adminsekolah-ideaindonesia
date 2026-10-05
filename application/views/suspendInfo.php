<?php if ($location === 'navbar'): ?>
<li id="app-suspend-info" class="dropdown user user-menu">
    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-calendar"></i>
        <span class="hidden-xs">Aktif hingga: <?= pretty_date($suspend_date, 'd F Y', false); ?></span>
    </a>
</li>
<?php else: ?>
<div id="app-suspend-info" class="<?= $alert_class; ?>" style="margin-bottom: 15px;">
    <h4><i class="icon fa <?= $icon; ?>"></i> <strong><?= $title_text; ?></strong></h4>
    <p style="margin: 0; white-space: normal; word-wrap: break-word; word-break: break-word; overflow-wrap: anywhere;">
        <?= $message; ?>
    </p>
</div>
<?php endif; ?>