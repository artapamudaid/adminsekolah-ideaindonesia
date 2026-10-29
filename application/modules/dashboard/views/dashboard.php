<?php
$styleBoxSubscriptionHistory = '';
if ($showTableSubsHistory) {
    $styleBoxSubscriptionHistory = 'style="';
    $styleBoxSubscriptionHistory .= 'min-height: 565px;';
    $styleBoxSubscriptionHistory .= '"';
}
?>

<style>
    .info-box-icon {
        margin-left: 0;
    }

    @media (max-width: 768px) {
        .display-xs {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $this->lang->line('dash_title'); ?></h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <i class="fa fa-dashboard"></i>
                    <?= $this->lang->line('dash_home'); ?>
                </a>
            </li>
            <li class="active"><?= $this->lang->line('dash_title'); ?></li>
        </ol>
    </section>

    <section class="content">

        <div class="box">
            <div class="box-body">
                <div class="row display-xs">
                    <div class="col-lg-4 col-md-8 col-sm-7 col-xs-12">
                        <h4 class="box-title"><?= $this->lang->line('dash_greeting') ?>, <?= $role_name; ?>!</h4>
                        <span class="text-muted"><?= $this->lang->line('dash_summary') ?> <?= (isset($nama_unit) ? $nama_unit : '') ?></span>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                        <select name="majors_id" id="select_majors" class="form-control" <?= ($umajorsid != 0) ? 'disabled' : '' ?> onchange="if(this.value){ window.location.href='?m='+this.value + (window.location.hash || ''); } else { window.location.href='<?= site_url('manage') ?>' + (window.location.hash || ''); }">
                            <option value=""><?= $this->lang->line('dash_all_units') ?></option>
                            <?php
                            if (!empty($filter_majors)):
                                foreach ($filter_majors as $maj):
                                    // if user is tied to a specific major, only select that major, otherwise use $_GET['m']
                                    $isSelected = ($umajorsid != 0) ? ($umajorsid == $maj['majors_id']) : (isset($_GET['m']) && $_GET['m'] == $maj['majors_id']);
                            ?>
                                    <option value="<?= $maj['majors_id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                        <?= !empty($maj['majors_short_name']) ? $maj['majors_short_name'] . ' - ' . $maj['majors_name'] : $maj['majors_name'] ?>
                                    </option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-8 col-sm-7 col-xs-12">
                        <div class="input-group">
                            <input type="text" id="dash_search_input" class="form-control" placeholder="<?= $this->lang->line('dash_search_placeholder') ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="dash_search_btn" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-5 col-xs-12">
                        <a href="<?= $setting_website; ?>" class="btn btn-default btn-block">
                            <?= $this->lang->line('dash_visit_website') ?> <i class="fa fa-external-link"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            if (!empty($top_metrics)):
                foreach ($top_metrics as $m):
            ?>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="info-box" style="border-left: 4px solid <?= $m['color'] ?>;">
                            <span class="info-box-icon" style="background:<?= $m['color'] ?>; font-size:22px; width:60px;">
                                <i class="fa <?= $m['icon'] ?>" style="color:white;"></i>
                            </span>
                            <div class="info-box-content" style="padding: 8px 10px; margin-left:60px;">
                                <span class="info-box-text" style="font-size:10px; font-weight:700; text-transform:uppercase;"><?= $m['title'] ?></span>
                                <span class="info-box-number" style="font-size:16px; margin: 15px 0 0;"><?= $m['value'] ?></span>
                                <div style="font-size:10px; color:#888;">
                                    <?= $m['label'] ?> <br>
                                    <span class="<?= $m['subcolor'] ?>"><?= $m['subtext'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>

        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#finance-tab" data-toggle="tab" aria-expanded="true"><?= $this->lang->line('dash_tab_finance') ?></a>
                        </li>
                        <li class="">
                            <a href="#student-tab" data-toggle="tab" aria-expanded="false"><?= $this->lang->line('dash_tab_student') ?></a>
                        </li>
                        <li class="">
                            <a href="#employee-tab" data-toggle="tab" aria-expanded="false"><?= $this->lang->line('dash_tab_employee') ?></a>
                        </li>
                        <li class="">
                            <a href="#academic-tab" data-toggle="tab" aria-expanded="false"><?= $this->lang->line('dash_tab_academic') ?></a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="finance-tab" data-endpoint="<?= site_url('manage/dashboard/ajax_finance_tab') ?>">
                            <?php $this->load->view('tabs/finance_tab'); ?>
                        </div>

                        <div class="tab-pane" id="student-tab" data-endpoint="<?= site_url('manage/dashboard/ajax_student_tab') ?>">
                            <?php $this->load->view('tabs/student_tab'); ?>
                        </div>

                        <div class="tab-pane" id="employee-tab" data-endpoint="<?= site_url('manage/dashboard/ajax_employee_tab') ?>">
                            <?php $this->load->view('tabs/employee_tab'); ?>
                        </div>

                        <div class="tab-pane" id="academic-tab" data-endpoint="<?= site_url('manage/dashboard/ajax_academic_tab') ?>">
                            <?php $this->load->view('tabs/academic_tab'); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">

            <div class="col-lg-5">
                <div class="box box-primary" <?= $styleBoxSubscriptionHistory; ?>>
                    <div class="box-header with-border">
                        <h3 class="box-title" id="title_informasi"><?= ($this->lang->line('dash_information_title') ?: 'Informasi') . ' - ' . ($this->lang->line('dash_all_units') ?: 'Semua Unit') ?></h3>
                    </div>

                    <div class="box-body" style="max-height: 519px; overflow-y: auto !important;">
                        <div id="skeleton_informasi" style="min-height: 250px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        </div>
                        <ul class="products-list product-list-in-box" id="container_pengumuman" style="display: none;"></ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="box box-primary" <?= $styleBoxSubscriptionHistory; ?>>
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $this->lang->line('dash_subscription_history') ?: 'Riwayat Langganan'; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="subscription-hero-card">
                            <div class="subs-hero-content">
                                <div class="subs-badge">
                                    <span class="subs-badge-dot"></span>
                                    <span><?= $this->lang->line('dash_active_package') ?: 'Paket Aktif'; ?></span>
                                </div>
                                <h3 class="subs-title"><?= html_escape($packageName) . ' — ' . html_escape($durationStr); ?></h3>
                                <div class="subs-meta">
                                    <span class="subs-meta-item">
                                        <span><?= $this->lang->line('dash_nearest_bill') ?: 'Tagihan Terdekat' ?>: <strong><?= html_escape($nearestBillDate); ?></strong></span>
                                    </span>
                                    <span class="subs-badge-pill"><i class="fa fa-clock-o"></i> <?= (int)$daysLeft; ?> <?= $this->lang->line('dash_days_left') ?: 'hari tersisa' ?></span>
                                </div>
                            </div>
                            <div class="subs-hero-action">
                                <a href="<?= $whatsappUrl ?>" target="_blank" class="subs-btn-extend">
                                    <i class="fa fa-whatsapp"></i> <?= $this->lang->line('dash_extend') ?: 'Perpanjang' ?>
                                </a>
                            </div>
                            <div class="subs-hero-bg-icon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>

                        <?php if ($showTableSubsHistory): ?>
                            <!-- Riwayat Langganan -->
                            <div class="nav-tabs-custom subs-tabs-custom">
                                <ul class="nav nav-tabs" id="subscription_tabs">
                                    <li class="active"><a href="#sub_tab_all" data-status="all" data-toggle="tab"><?= $this->lang->line('dash_all') ?: 'Semua' ?></a></li>
                                    <li><a href="#sub_tab_pending" data-status="pending" data-toggle="tab"><?= $this->lang->line('dash_pending') ?: 'Pending' ?></a></li>
                                    <li><a href="#sub_tab_lunas" data-status="lunas" data-toggle="tab"><?= $this->lang->line('dash_paid_status') ?: 'Lunas' ?></a></li>
                                    <li><a href="#sub_tab_batal" data-status="batal" data-toggle="tab"><?= $this->lang->line('dash_cancelled') ?: 'Batal' ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="subscription_tab_content">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="subscription_history_table" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th><?= $this->lang->line('dash_invoice') ?: 'Faktur' ?></th>
                                                        <th><?= $this->lang->line('dash_status') ?: 'Status' ?></th>
                                                        <th><?= $this->lang->line('dash_date') ?: 'Tanggal' ?></th>
                                                        <th><?= $this->lang->line('dash_description') ?: 'Deskripsi' ?></th>
                                                        <th><?= $this->lang->line('dash_total') ?: 'Total' ?></th>
                                                        <th><?= $this->lang->line('dash_payment_link') ?: 'Link Bayar' ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $dummy_history = [
                                                        ['invoice' => 'INV-2026-003', 'status' => 'pending', 'date' => '24 Agu 2026 09:00', 'desc' => 'Admin Sekolah - RENEWAL - ATAS - 12 Bulan', 'total' => 'Rp 6.000.000', 'link' => 'https://ipaymu.com/pay/123'],
                                                        ['invoice' => 'INV-2025-002', 'status' => 'lunas', 'date' => '1 Okt 2025 10:50', 'desc' => 'Admin Sekolah - RENEWAL - ATAS - 12 Bulan', 'total' => 'Rp 6.000.000', 'link' => 'https://ipaymu.com/pay/122'],
                                                        ['invoice' => 'INV-2024-001', 'status' => 'batal', 'date' => '15 Agu 2024 14:20', 'desc' => 'Admin Sekolah - NEW - MENENGAH - 12 Bulan', 'total' => 'Rp 4.500.000', 'link' => '#']
                                                    ];
                                                    foreach ($dummy_history as $h):
                                                    ?>
                                                        <tr data-status="<?= html_escape($h['status']) ?>">
                                                            <td><strong style="color: #1E293B; letter-spacing: 0.3px;"><?= html_escape($h['invoice']) ?></strong></td>
                                                            <td>
                                                                <?php if ($h['status'] == 'lunas'): ?>
                                                                    <span class="label-status-lunas"><i class="fa fa-check-circle"></i> <?= ucfirst(html_escape($h['status'])) ?></span>
                                                                <?php elseif ($h['status'] == 'pending'): ?>
                                                                    <span class="label-status-pending"><i class="fa fa-clock-o"></i> <?= ucfirst(html_escape($h['status'])) ?></span>
                                                                <?php elseif ($h['status'] == 'batal'): ?>
                                                                    <span class="label-status-batal"><i class="fa fa-times-circle"></i> <?= ucfirst(html_escape($h['status'])) ?></span>
                                                                <?php else: ?>
                                                                    <span class="label label-default"><?= ucfirst(html_escape($h['status'])) ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td style="color: #475569; font-size: 13px;"><?= html_escape($h['date']) ?></td>
                                                            <td style="color: #334155; font-size: 13px;"><?= html_escape($h['desc']) ?></td>
                                                            <td><strong style="color: #0F172A;"><?= html_escape($h['total']) ?></strong></td>
                                                            <td style="white-space: nowrap;">
                                                                <?php if ($h['status'] == 'pending'): ?>
                                                                    <a href="<?= html_escape($h['link']) ?>" target="_blank" class="btn btn-action-pay"><i class="fa fa-external-link"></i> <?= $this->lang->line('dash_open_link') ?: 'Buka Link' ?></a>
                                                                    <button type="button" class="btn btn-action-cancel" onclick="alert('Batalkan transaksi?')"><i class="fa fa-times"></i> <?= $this->lang->line('dash_cancel') ?: 'Batalkan' ?></button>
                                                                <?php elseif ($h['status'] == 'lunas'): ?>
                                                                    <a href="<?= html_escape($h['link']) ?>" target="_blank" class="btn btn-action-receipt"><i class="fa fa-print"></i> Kwitansi</a>
                                                                <?php else: ?>
                                                                    <span class="text-muted" style="color: #94A3B8;">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>

    </section>
</div>

<script>
    $(document).ready(function() {
        var selectedMajors = '<?= isset($selected_majors) ? $selected_majors : '' ?>';
        var majorsId = $('#select_majors').val();


        $('#select_majors').select2({
            width: '100%',
            theme: 'classic',
        });

        function loadTabData(tabId) {
            var $tabPane = $(tabId);
            if (!$tabPane.length) return;

            if ($tabPane.data('loaded') === true) return;

            if (tabId === '#finance-tab') {
                $tabPane.data('loaded', true);
                if (typeof window.loadFinanceWidgets === 'function') {
                    window.loadFinanceWidgets(selectedMajors);
                }
                return;
            }

            if (tabId === '#academic-tab') {
                $tabPane.data('loaded', true);
                if (typeof window.loadAcademicWidgets === 'function') {
                    window.loadAcademicWidgets(selectedMajors);
                }
                return;
            }

            var endpoint = $tabPane.data('endpoint');
            if (!endpoint) return;

            var $overlay = $('<div class="tab-loading-overlay" style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,0.7);z-index:99;display:flex;align-items:center;justify-content:center;min-height:150px;"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i></div>');
            $tabPane.css('position', 'relative').append($overlay);

            $.ajax({
                url: endpoint,
                type: 'GET',
                dataType: 'json',
                data: {
                    m: selectedMajors
                },
                success: function(data) {
                    $overlay.remove();
                    $tabPane.data('loaded', true);

                    if (tabId === '#academic-tab' && typeof window.renderAcademicTab === 'function') {
                        window.renderAcademicTab(data);
                    } else if (tabId === '#student-tab' && typeof window.renderStudentTab === 'function') {
                        window.renderStudentTab(data);
                    } else if (tabId === '#employee-tab' && typeof window.renderEmployeeTab === 'function') {
                        window.renderEmployeeTab(data);
                    }
                },
                error: function() {
                    $overlay.remove();
                    alert('Gagal memuat data tab. Silakan coba lagi.');
                }
            });
        }

        $('.content-wrapper .nav-tabs:not(#subscription_tabs) a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var targetTab = $(e.target).attr('href');
            if (targetTab && targetTab.indexOf('#') === 0) {
                loadTabData(targetTab);
                if (history.replaceState) {
                    history.replaceState(null, null, targetTab);
                } else {
                    window.location.hash = targetTab;
                }
            }
        });

        var hash = window.location.hash;
        var validMainTabs = ['#finance-tab', '#student-tab', '#employee-tab', '#academic-tab'];
        var $initialTabLink = (validMainTabs.indexOf(hash) !== -1) ? $('.content-wrapper .nav-tabs:not(#subscription_tabs) a[href="' + hash + '"]') : [];

        if (hash && $initialTabLink.length) {
            var isAlreadyActive = $initialTabLink.parent().hasClass('active');
            $initialTabLink.tab('show');
            if (isAlreadyActive) {
                loadTabData(hash);
            }
        } else {
            var activeTabHref = $('.content-wrapper .nav-tabs:not(#subscription_tabs) li.active a').attr('href');
            if (activeTabHref) {
                loadTabData(activeTabHref);
            }
        }

        function doDashSearch() {
            var keyword = $('#dash_search_input').val().trim();
            if (keyword !== '') {
                window.location.href = '<?= site_url('manage/pencarian') ?>?q=' + encodeURIComponent(keyword);
            }
        }

        $('#dash_search_btn').on('click', function() {
            doDashSearch();
        });

        $('#dash_search_input').on('keypress', function(e) {
            if (e.which === 13) {
                doDashSearch();
            }
        });

        loadWidgetInformasi(majorsId);

        function loadWidgetInformasi(majorsId) {
            $('#skeleton_informasi').show();
            $('#container_pengumuman').hide();

            $.ajax({
                url: '<?= site_url('manage/dashboard/ajax_academic_informasi') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    majors_id: majorsId
                },
                success: function(res) {
                    $('#skeleton_informasi').hide();

                    if (res.title) {
                        $('#title_informasi').text(res.title);
                    }

                    var pengumumanHtml = '';
                    if (res.data && res.data.length > 0) {
                        $.each(res.data, function(i, pg) {
                            var thumbHtml = '';
                            if (pg.img) {
                                thumbHtml = '<div class="product-img" style="float:left; margin-right:10px;"><img src="' + pg.img + '" alt="Thumbnail" class="img-thumbnail" style="width:50px; height:50px; object-fit:cover;"></div>';
                            }
                            var infoStyle = pg.img ? 'margin-left:60px;' : 'margin-left:0;';

                            pengumumanHtml += '<li class="item" style="padding: 10px 10px; border-left: 3px solid; ' + pg.border + '">' +
                                thumbHtml +
                                '<div class="product-info" style="' + infoStyle + '">' +
                                '<a href="javascript:void(0);" class="product-title" style="font-size:12px;">' +
                                '<i class="fa ' + pg.icon + ' ' + pg.label + '"></i> ' + pg.title +
                                '<span class="label label-default pull-right" style="font-size:9px;">' + pg.type + '</span>' +
                                '</a>' +
                                '<span class="product-description" style="white-space:normal; font-size:11px; margin:3px 0;">' + pg.desc + '</span>' +
                                '<span class="product-description text-muted" style="font-size:10px;"><i class="fa fa-calendar"></i> ' + pg.date + '</span>' +
                                '</div></li>';
                        });
                    } else {
                        pengumumanHtml = '<li class="item text-center text-muted" style="padding:15px;"><?= $this->lang->line('dash_no_information') ?: 'Tidak ada informasi' ?></li>';
                    }

                    $('#container_pengumuman').html(pengumumanHtml).show();
                },
                error: function() {
                    $('#skeleton_informasi').html('<p class="text-danger text-center" style="padding:15px;">Gagal memuat data</p>');
                }
            });
        }

    });
</script>

<?php if ($showTableSubsHistory): ?>
    <script>
        $(document).ready(function() {

            // Subscription history client-side filtering
            $('#subscription_tabs a').on('shown.bs.tab', function(e) {
                var status = $(this).data('status');
                var $rows = $('#subscription_history_table tbody tr');
                if (status === 'all') {
                    $rows.show();
                } else {
                    $rows.hide();
                    $rows.filter(function() {
                        return $(this).data('status') === status;
                    }).show();
                }
            });

        });
    </script>
<?php endif; ?>