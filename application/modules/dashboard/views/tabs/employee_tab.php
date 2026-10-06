<!-- Widget 1: Jumlah Pegawai -->
<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
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
</div>

<!-- Widget 3: Kehadiran Pegawai Hari Ini -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_employee_attendance') ?: 'Presensi Pegawai Hari Ini' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_employee_kehadiran" style="min-height: 100px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div id="container_employee_kehadiran" style="display: none;">
                    <div class="row text-center">
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-check"></i></span>
                                <h5 class="description-header" id="txt_emp_hadir">0</h5>
                                <span class="description-text">Hadir</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-blue"><i class="fa fa-info"></i></span>
                                <h5 class="description-header" id="txt_emp_izin">0</h5>
                                <span class="description-text">Izin</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-yellow"><i class="fa fa-medkit"></i></span>
                                <h5 class="description-header" id="txt_emp_sakit">0</h5>
                                <span class="description-text">Sakit</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block">
                                <span class="description-percentage text-red"><i class="fa fa-times"></i></span>
                                <h5 class="description-header" id="txt_emp_alpa">0</h5>
                                <span class="description-text">Alpa / Belum Hadir</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.renderEmployeeTab = function(data) {};

    $(document).ready(function() {
        var majorsId = $('#select_majors').val();

        $('a[data-toggle="tab"][href="#employee-tab"]').on('shown.bs.tab', function(e) {
            var $tabPane = $('#employee-tab');
            if ($tabPane.data('widgets_loaded')) return;
            $tabPane.data('widgets_loaded', true);
            loadEmployeeWidgets(majorsId);
        });

        if ($('.nav-tabs li.active a').attr('href') === '#employee-tab') {
            var $tabPane = $('#employee-tab');
            if (!$tabPane.data('widgets_loaded')) {
                $tabPane.data('widgets_loaded', true);
                loadEmployeeWidgets(majorsId);
            }
        }
    });

    function loadEmployeeWidgets(majorsId) {
        loadWidgetEmployeeCount(majorsId);
        loadWidgetEmployeeStatus(majorsId);
        loadWidgetEmployeeKehadiran(majorsId);
    }

    var chartEmployeeCount = null;

    function loadWidgetEmployeeCount(majorsId) {
        $('#skeleton_employee_count').show();
        $('#container_employee_count').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_employee_jumlah') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_employee_count').hide();
                $('#container_employee_count').show();
                $('#title_employee_count').text(res.title);

                var ctx = document.getElementById('chart_employee_count').getContext('2d');
                if (chartEmployeeCount) {
                    chartEmployeeCount.destroy();
                }
                chartEmployeeCount = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            label: 'Jumlah Pegawai',
                            data: res.data,
                            backgroundColor: '#00c0ef'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
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

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_employee_status') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_employee_status').hide();
                $('#container_employee_status').show();

                var ctx = document.getElementById('chart_employee_status').getContext('2d');
                if (chartEmployeeStatus) {
                    chartEmployeeStatus.destroy();
                }
                chartEmployeeStatus = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: ['#00a65a', '#f39c12', '#dd4b39']
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

    function loadWidgetEmployeeKehadiran(majorsId) {
        $('#skeleton_employee_kehadiran').show();
        $('#container_employee_kehadiran').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_employee_kehadiran') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_employee_kehadiran').hide();
                $('#container_employee_kehadiran').show();

                $('#txt_emp_hadir').text(res.hadir);
                $('#txt_emp_izin').text(res.izin);
                $('#txt_emp_sakit').text(res.sakit);
                $('#txt_emp_alpa').text(res.alpa);
            },
            error: function() {
                $('#skeleton_employee_kehadiran').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }
</script>