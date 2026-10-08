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
                    <div class="col-lg-4 col-md-12 col-sm-7 col-xs-12">
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
                    <div class="col-lg-3 col-md-4 col-sm-7 col-xs-12">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('dash_search_placeholder') ?>">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-5 col-xs-12">
                        <a href="" class="btn btn-default btn-block">
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

    </section>
</div>

<script>
    $(document).ready(function() {
        var selectedMajors = '<?= isset($selected_majors) ? $selected_majors : '' ?>';

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

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var targetTab = $(e.target).attr('href');
            loadTabData(targetTab);
            if (history.replaceState) {
                history.replaceState(null, null, targetTab);
            } else {
                window.location.hash = targetTab;
            }
        });

        var hash = window.location.hash;
        var initialTab = hash ? hash : '.nav-tabs li.active a';
        var $initialTabLink = $('a[href="' + hash + '"]');

        if (hash && $initialTabLink.length) {
            $initialTabLink.tab('show');
        } else {
            var activeTabHref = $('.nav-tabs li.active a').attr('href');
            if (activeTabHref) {
                loadTabData(activeTabHref);
            }
        }
    });
</script>
