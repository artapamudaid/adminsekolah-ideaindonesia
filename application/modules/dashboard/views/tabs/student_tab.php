<div class="row">
    <!-- Widget 1: Jumlah Siswa (Bar Chart) -->
    <div id="col_widget_jumlah_siswa" class="col-lg-8 col-md-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_jumlah_siswa"><?= $this->lang->line('dash_student_count') ?: 'Jumlah Siswa Per Unit' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_jumlah_siswa" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%; display: none;" id="container_jumlah_siswa">
                    <canvas id="chart_jumlah_siswa"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget 2: Rekap Mutasi & Status Siswa (Doughnut Chart) -->
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_student_status_recap') ?: 'Rekap Mutasi & Status Siswa' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_student_status" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%; display: none;" id="container_student_status">
                    <canvas id="chart_student_status"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget 3: Kehadiran Hari Ini -->
    <div id="col_widget_kehadiran" class="col-lg-12 col-md-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_student_kehadiran"><?= $this->lang->line('dash_student_attendance') ?: 'Kehadiran Siswa Hari Ini' ?></h3>

                <div class="box-tools pull-right">
                    <select id="filter_kelas_kehadiran_hari_ini" class="form-control input-sm s2" style="width:auto; display:none; font-size:12px; height:28px; padding:2px 8px;">
                        <option value="all"><?= $this->lang->line('dash_all_classes') ?: 'Semua Kelas' ?></option>
                    </select>
                </div>
            </div>
            <div class="box-body">
                <div id="skeleton_student_kehadiran" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%; display: none;" id="container_student_kehadiran">
                    <canvas id="chart_student_kehadiran"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.renderStudentTab = function(data) {};

    $(document).ready(function() {
        var majorsId = $('#select_majors').val();

        $('#filter_kelas_kehadiran_hari_ini').on('change', function() {
            var selectedClass = $(this).val();
            loadWidgetStudentKehadiran($('#select_majors').val(), selectedClass);
        });

        $('a[data-toggle="tab"][href="#student-tab"]').on('shown.bs.tab', function(e) {
            var $tabPane = $('#student-tab');
            if ($tabPane.data('widgets_loaded')) return;
            $tabPane.data('widgets_loaded', true);
            loadStudentWidgets(majorsId);
        });

        if ($('.nav-tabs li.active a').attr('href') === '#student-tab') {
            var $tabPane = $('#student-tab');
            if (!$tabPane.data('widgets_loaded')) {
                $tabPane.data('widgets_loaded', true);
                loadStudentWidgets(majorsId);
            }
        }
    });

    function loadStudentWidgets(majorsId) {
        loadWidgetJumlahSiswa(majorsId);
        loadWidgetStudentStatus(majorsId);
        initFilterKelasKehadiran(majorsId);
        loadWidgetStudentKehadiran(majorsId);
    }

    var chartJumlahSiswa = null;

    function loadWidgetJumlahSiswa(majorsId) {
        $('#skeleton_jumlah_siswa').show();
        $('#container_jumlah_siswa').hide();
        $('#no_data_jumlah_siswa').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_jumlah_siswa') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_jumlah_siswa').hide();
                $('#title_jumlah_siswa').text(res.title);

                var ctx = document.getElementById('chart_jumlah_siswa').getContext('2d');
                if (chartJumlahSiswa) {
                    chartJumlahSiswa.destroy();
                }

                if (!res.labels || res.labels.length === 0) {
                    if ($('#no_data_jumlah_siswa').length === 0) {
                        $('<div id="no_data_jumlah_siswa" class="text-center text-muted" style="height: 300px; display: flex; align-items: center; justify-content: center;"><?= $this->lang->line('dash_no_data') ?: 'Tidak ada data untuk ditampilkan' ?></div>').insertBefore('#container_jumlah_siswa');
                    }
                    $('#no_data_jumlah_siswa').show();
                    return;
                }

                if (res.labels.length > 5) {
                    $('#col_widget_jumlah_siswa').removeClass('col-lg-8').addClass('col-lg-12');
                    $('#col_widget_kehadiran').removeClass('col-lg-12').addClass('col-lg-8');
                } else {
                    $('#col_widget_jumlah_siswa').removeClass('col-lg-12').addClass('col-lg-8');
                    $('#col_widget_kehadiran').removeClass('col-lg-8').addClass('col-lg-12');
                }

                $('#container_jumlah_siswa').show();

                chartJumlahSiswa = new Chart(ctx, {
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
                $('#skeleton_jumlah_siswa').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }

    var chartStudentStatus = null;

    function loadWidgetStudentStatus(majorsId) {
        $('#skeleton_student_status').show();
        $('#container_student_status').hide();
        $('#no_data_student_status').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_status') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_student_status').hide();

                if (!res.labels || res.labels.length === 0) {
                    if ($('#no_data_student_status').length === 0) {
                        $('<div id="no_data_student_status" class="text-center text-muted" style="height: 300px; display: flex; align-items: center; justify-content: center;"><?= $this->lang->line('dash_no_data') ?: 'Tidak ada data untuk ditampilkan' ?></div>').insertBefore('#container_student_status');
                    }
                    $('#no_data_student_status').show();
                    return;
                }

                $('#container_student_status').show();

                var ctx = document.getElementById('chart_student_status').getContext('2d');
                if (chartStudentStatus) {
                    chartStudentStatus.destroy();
                }
                chartStudentStatus = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: res.colors || ['#00a65a', '#3c8dbc', '#f39c12', '#d2d6de']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            labels: {
                                render: 'percentage',
                                precision: 1,
                                fontColor: '#fff'
                            }
                        }
                    }
                });
            },
            error: function() {
                $('#skeleton_student_status').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }

    function initFilterKelasKehadiran(majorsId) {
        var $select = $('#filter_kelas_kehadiran_hari_ini');
        if (!majorsId) {
            $select.hide();
            return;
        }

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_classes') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(classes) {
                $select.empty();
                $select.append('<option value="all"><?= $this->lang->line('dash_all_classes') ?: 'Semua Kelas' ?></option>');
                if (classes && classes.length > 0) {
                    $.each(classes, function(i, cls) {
                        $select.append('<option value="' + cls.class_id + '">' + cls.class_name + '</option>');
                    });
                    $select.show();
                } else {
                    $select.hide();
                }
            },
            error: function() {
                $select.hide();
            }
        });
    }

    var chartStudentKehadiran = null;

    function loadWidgetStudentKehadiran(majorsId, classId) {
        $('#skeleton_student_kehadiran').show();
        $('#container_student_kehadiran').hide();
        $('#no_data_student_kehadiran').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_kehadiran') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId,
                class_id: classId || 'all'
            },
            success: function(res) {
                $('#skeleton_student_kehadiran').hide();

                if (res.title) {
                    $('#title_student_kehadiran').text(res.title);
                }

                var total = 0;
                if (res.data && res.data.length > 0) {
                    for (var i = 0; i < res.data.length; i++) {
                        total += res.data[i];
                    }
                }

                if (!res.data || res.data.length === 0 || total === 0) {
                    if ($('#no_data_student_kehadiran').length === 0) {
                        $('<div id="no_data_student_kehadiran" class="text-center text-muted" style="height: 300px; display: flex; align-items: center; justify-content: center;"><?= $this->lang->line('dash_no_data') ?: 'Tidak ada data untuk ditampilkan' ?></div>').insertBefore('#container_student_kehadiran');
                    }
                    $('#no_data_student_kehadiran').show();
                    if (chartStudentKehadiran) {
                        chartStudentKehadiran.destroy();
                        chartStudentKehadiran = null;
                    }
                    return;
                }

                $('#container_student_kehadiran').show();

                var ctx = document.getElementById('chart_student_kehadiran').getContext('2d');
                if (chartStudentKehadiran) {
                    chartStudentKehadiran.destroy();
                }

                chartStudentKehadiran = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: res.colors || ['#00a65a', '#3c8dbc', '#f39c12', '#f56954']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        plugins: {
                            labels: {
                                render: function() {
                                    return '';
                                }
                            }
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return ' ' + data.labels[tooltipItem.index] + ': ' + tooltipItem.yLabel;
                                }
                            }
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
                $('#skeleton_student_kehadiran').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }
</script>