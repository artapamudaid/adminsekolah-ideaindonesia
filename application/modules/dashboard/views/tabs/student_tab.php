<!-- Widget 1: Jumlah Siswa (Bar Chart) -->
<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
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

    <!-- Widget 2: Gender Siswa (Doughnut Chart) -->
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_student_gender') ?: 'Gender Siswa' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_student_gender" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%; display: none;" id="container_student_gender">
                    <canvas id="chart_student_gender"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Widget 3: Kehadiran Hari Ini -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->lang->line('dash_student_attendance') ?: 'Kehadiran Siswa Hari Ini' ?></h3>
            </div>
            <div class="box-body">
                <div id="skeleton_student_kehadiran" style="min-height: 100px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div id="container_student_kehadiran" style="display: none;">
                    <div class="row text-center">
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-check"></i></span>
                                <h5 class="description-header" id="txt_hadir">0</h5>
                                <span class="description-text">Hadir</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-blue"><i class="fa fa-info"></i></span>
                                <h5 class="description-header" id="txt_izin">0</h5>
                                <span class="description-text">Izin</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-yellow"><i class="fa fa-medkit"></i></span>
                                <h5 class="description-header" id="txt_sakit">0</h5>
                                <span class="description-text">Sakit</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block">
                                <span class="description-percentage text-red"><i class="fa fa-times"></i></span>
                                <h5 class="description-header" id="txt_alpa">0</h5>
                                <span class="description-text">Alpa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.renderStudentTab = function(data) {};

    $(document).ready(function() {
        var majorsId = $('#select_majors').val();

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
        loadWidgetStudentGender(majorsId);
        loadWidgetStudentKehadiran(majorsId);
    }

    var chartJumlahSiswa = null;

    function loadWidgetJumlahSiswa(majorsId) {
        $('#skeleton_jumlah_siswa').show();
        $('#container_jumlah_siswa').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_jumlah_siswa') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_jumlah_siswa').hide();
                $('#container_jumlah_siswa').show();
                $('#title_jumlah_siswa').text(res.title);

                var ctx = document.getElementById('chart_jumlah_siswa').getContext('2d');
                if (chartJumlahSiswa) {
                    chartJumlahSiswa.destroy();
                }
                chartJumlahSiswa = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            label: 'Jumlah Siswa',
                            data: res.data,
                            backgroundColor: '#3c8dbc'
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
                $('#skeleton_jumlah_siswa').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }

    var chartStudentGender = null;

    function loadWidgetStudentGender(majorsId) {
        $('#skeleton_student_gender').show();
        $('#container_student_gender').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_gender') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_student_gender').hide();
                $('#container_student_gender').show();

                var ctx = document.getElementById('chart_student_gender').getContext('2d');
                if (chartStudentGender) {
                    chartStudentGender.destroy();
                }
                chartStudentGender = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: ['#3c8dbc', '#f56954']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            },
            error: function() {
                $('#skeleton_student_gender').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }

    function loadWidgetStudentKehadiran(majorsId) {
        $('#skeleton_student_kehadiran').show();
        $('#container_student_kehadiran').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_student_kehadiran') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                majors_id: majorsId
            },
            success: function(res) {
                $('#skeleton_student_kehadiran').hide();
                $('#container_student_kehadiran').show();

                $('#txt_hadir').text(res.hadir);
                $('#txt_izin').text(res.izin);
                $('#txt_sakit').text(res.sakit);
                $('#txt_alpa').text(res.alpa);
            },
            error: function() {
                $('#skeleton_student_kehadiran').html('<p class="text-danger">Gagal memuat data</p>');
            }
        });
    }
</script>