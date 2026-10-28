<div class="row">
    <!-- Widget 1: Jumlah Pegawai -->
    <div class="col-lg-8 col-md-12 col-sm-12" id="col_widget_employee_count">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_employee_count"><?= $this->lang->line('dash_employee_count') ?: 'Jumlah Pegawai Per Unit' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_employee_count" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%; display: none;" id="container_employee_count">
                    <canvas id="chart_employee_count"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget 2: Status Kepegawaian -->
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_employee_status') ?: 'Status Kepegawaian' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_employee_status" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%; display: none;" id="container_employee_status">
                    <canvas id="chart_employee_status"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget 3: Kehadiran Pegawai Hari Ini -->
    <div class="col-lg-12 col-md-12 col-sm-12" id="col_widget_employee_kehadiran">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_employee_attendance') ?: 'Presensi Pegawai Hari Ini' ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" id="btn_reload_emp_kehadiran" title="Refresh Data">
                        <i class="fa fa-refresh" id="icon_reload_emp_kehadiran"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div id="skeleton_employee_kehadiran" style="min-height: 100px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div id="container_employee_kehadiran" style="display: none;">
                    <!-- Metric Blocks: DATANG, IJIN, PULANG -->
                    <div class="row text-center" style="margin-bottom: 15px;">
                        <div class="col-sm-4 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-sign-in"></i></span>
                                <h5 class="description-header" id="txt_emp_datang">0</h5>
                                <span class="description-text"><?= $this->lang->line('dash_emp_datang') ?: 'DATANG' ?></span>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-blue"><i class="fa fa-info-circle"></i></span>
                                <h5 class="description-header" id="txt_emp_ijin">0</h5>
                                <span class="description-text"><?= $this->lang->line('dash_emp_ijin') ?: 'IJIN' ?></span>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                            <div class="description-block">
                                <span class="description-percentage text-orange"><i class="fa fa-sign-out"></i></span>
                                <h5 class="description-header" id="txt_emp_pulang">0</h5>
                                <span class="description-text"><?= $this->lang->line('dash_emp_pulang') ?: 'PULANG' ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- Table Detail -->
                    <div id="no_data_emp_kehadiran" class="text-center text-muted" style="display: none; padding: 20px;">
                        <i class="fa fa-calendar-times-o fa-2x"></i>
                        <p style="margin-top: 8px;"><?= $this->lang->line('dash_no_data') ?: 'Tidak ada data untuk ditampilkan' ?></p>
                    </div>
                    <div id="tbl_wrapper_emp_kehadiran" style="max-height: 209px; overflow-y: auto; overflow-x: auto;">
                        <table class="table table-bordered table-striped table-condensed" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"><?= $this->lang->line('dash_no') ?: 'No' ?></th>
                                    <th><?= $this->lang->line('dash_emp_nip') ?: 'NIP' ?></th>
                                    <th><?= $this->lang->line('dash_emp_name') ?: 'Nama' ?></th>
                                    <th><?= $this->lang->line('dash_emp_position') ?: 'Jabatan' ?></th>
                                    <th><?= $this->lang->line('dash_emp_attendance_status') ?: 'Status Kehadiran' ?></th>
                                    <th><?= $this->lang->line('dash_emp_time') ?: 'Waktu' ?></th>
                                    <th><?= $this->lang->line('dash_emp_note') ?: 'Catatan' ?></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_emp_kehadiran">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.renderEmployeeTab = function(data) {};

    var empPollingInterval = null;

    function startEmpKehadiranPolling() {
        if (!empPollingInterval) {
            empPollingInterval = setInterval(function() {
                loadWidgetEmployeeKehadiran($('#select_majors').val(), true);
            }, 30000);
        }
    }

    function stopEmpKehadiranPolling() {
        if (empPollingInterval) {
            clearInterval(empPollingInterval);
            empPollingInterval = null;
        }
    }

    $(document).ready(function() {
        var majorsId = $('#select_majors').val();

        $('a[data-toggle="tab"][href="#employee-tab"]').on('shown.bs.tab', function(e) {
            var $tabPane = $('#employee-tab');
            if (!$tabPane.data('widgets_loaded')) {
                $tabPane.data('widgets_loaded', true);
                loadEmployeeWidgets(majorsId);
            }
            startEmpKehadiranPolling();
        }).on('hidden.bs.tab', function(e) {
            stopEmpKehadiranPolling();
        });

        if ($('.nav-tabs li.active a').attr('href') === '#employee-tab') {
            var $tabPane = $('#employee-tab');
            if (!$tabPane.data('widgets_loaded')) {
                $tabPane.data('widgets_loaded', true);
                loadEmployeeWidgets(majorsId);
            }
            startEmpKehadiranPolling();
        }

        $('#btn_reload_emp_kehadiran').on('click', function(e) {
            e.preventDefault();
            var $icon = $('#icon_reload_emp_kehadiran');
            $icon.addClass('fa-spin');
            loadWidgetEmployeeKehadiran($('#select_majors').val(), true, function() {
                $icon.removeClass('fa-spin');
            });
        });
    });

    function loadEmployeeWidgets(majorsId) {
        loadWidgetEmployeeCount(majorsId);
        loadWidgetEmployeeStatus(majorsId);
        loadWidgetEmployeeKehadiran(majorsId, false);
    }

    var chartEmployeeCount = null;

    function loadWidgetEmployeeCount(majorsId) {
        $('#skeleton_employee_count').show();
        $('#container_employee_count').hide();
        $('#no_data_employee_count').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_employee_jumlah') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_employee_count').hide();
                $('#title_employee_count').text(res.title);

                var ctx = document.getElementById('chart_employee_count').getContext('2d');
                if (chartEmployeeCount) {
                    chartEmployeeCount.destroy();
                }

                if (!res.labels || res.labels.length === 0) {
                    if ($('#no_data_employee_count').length === 0) {
                        $('<div id="no_data_employee_count" class="text-center text-muted" style="height: 300px; display: flex; align-items: center; justify-content: center;"><?= $this->lang->line('dash_no_data') ?: 'Tidak ada data untuk ditampilkan' ?></div>').insertBefore('#container_employee_count');
                    }
                    $('#no_data_employee_count').show();
                    return;
                }

                if (res.labels.length > 5) {
                    $('#col_widget_employee_count').removeClass('col-lg-8').addClass('col-lg-12');
                    $('#col_widget_employee_kehadiran').removeClass('col-lg-12').addClass('col-lg-8');
                } else {
                    $('#col_widget_employee_count').removeClass('col-lg-12').addClass('col-lg-8');
                    $('#col_widget_employee_kehadiran').removeClass('col-lg-8').addClass('col-lg-12');
                }

                $('#container_employee_count').show();

                chartEmployeeCount = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: res.labels,
                        datasets: [{
                                type: 'line',
                                label: res.lang_total || 'Jumlah',
                                data: res.data_total || [],
                                borderColor: '#00a65a',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                pointRadius: 4,
                                pointBackgroundColor: '#00a65a',
                                fill: false
                            },
                            {
                                type: 'bar',
                                label: res.lang_l || 'Laki-laki',
                                data: res.data_l || [],
                                backgroundColor: '#3c8dbc'
                            },
                            {
                                type: 'bar',
                                label: res.lang_p || 'Perempuan',
                                data: res.data_p || [],
                                backgroundColor: '#f56954'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            labels: {
                                render: function() {
                                    return '';
                                }
                            }
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            }]
                        }
                    }
                });
            },
            error: function() {
                $('#skeleton_employee_count').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }

    var chartEmployeeStatus = null;

    function loadWidgetEmployeeStatus(majorsId) {
        $('#skeleton_employee_status').show();
        $('#container_employee_status').hide();
        $('#no_data_employee_status').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_employee_status') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_employee_status').hide();

                var ctx = document.getElementById('chart_employee_status').getContext('2d');
                if (chartEmployeeStatus) {
                    chartEmployeeStatus.destroy();
                }

                if (!res.labels || res.labels.length === 0) {
                    if ($('#no_data_employee_status').length === 0) {
                        $('<div id="no_data_employee_status" class="text-center text-muted" style="height: 300px; display: flex; align-items: center; justify-content: center;"><?= $this->lang->line('dash_no_data') ?: 'Tidak ada data untuk ditampilkan' ?></div>').insertBefore('#container_employee_status');
                    }
                    $('#no_data_employee_status').show();
                    return;
                }

                $('#container_employee_status').show();

                chartEmployeeStatus = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: res.colors || ['#00a65a', '#f39c12', '#dd4b39']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            },
            error: function() {
                $('#skeleton_employee_status').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }

    function loadWidgetEmployeeKehadiran(majorsId, isBackground, callback) {
        if (!isBackground) {
            $('#skeleton_employee_kehadiran').show();
            $('#container_employee_kehadiran').hide();
        }

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_employee_kehadiran') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                if (!isBackground) {
                    $('#skeleton_employee_kehadiran').hide();
                    $('#container_employee_kehadiran').show();
                }

                if (res.metrics) {
                    $('#txt_emp_datang').text(res.metrics.datang || 0);
                    $('#txt_emp_ijin').text(res.metrics.ijin || 0);
                    $('#txt_emp_pulang').text(res.metrics.pulang || 0);
                }

                var $tbody = $('#tbody_emp_kehadiran');
                $tbody.empty();

                if (res.list && res.list.length > 0) {
                    $('#no_data_emp_kehadiran').hide();
                    $('#tbl_wrapper_emp_kehadiran').show();

                    $.each(res.list, function(i, item) {
                        var badgeClass = 'label-default';
                        if (item.jenis_absen === 'DATANG') badgeClass = 'label-success';
                        else if (item.jenis_absen === 'IJIN') badgeClass = 'label-info';
                        else if (item.jenis_absen === 'PULANG') badgeClass = 'label-warning';

                        var statusHadir = item.statushadir ? ' <small class="text-muted">(' + item.statushadir + ')</small>' : '';
                        var position = item.position_name ? item.position_name : '-';
                        var time = item.time ? item.time : '-';
                        var note = item.note ? item.note : '-';
                        var noteClass = note != '-' ? 'text-left' : 'text-center'

                        var tr = '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td>' + (item.employee_nip || '-') + '</td>' +
                            '<td>' + item.employee_name + '</td>' +
                            '<td>' + position + '</td>' +
                            '<td class="text-left"><span class="label ' + badgeClass + '">' + item.jenis_absen + '</span>' + statusHadir + '</td>' +
                            '<td class="text-center">' + time + '</td>' +
                            '<td class="' + noteClass + '">' + note + '</td>' +
                            '</tr>';
                        $tbody.append(tr);
                    });
                } else {
                    $('#tbl_wrapper_emp_kehadiran').hide();
                    $('#no_data_emp_kehadiran').show();
                }

                if (typeof callback === 'function') callback();
            },
            error: function() {
                if (!isBackground) {
                    $('#skeleton_employee_kehadiran').html('<p class="text-danger">Gagal memuat data</p>');
                }
                if (typeof callback === 'function') callback();
            }
        });
    }
</script>