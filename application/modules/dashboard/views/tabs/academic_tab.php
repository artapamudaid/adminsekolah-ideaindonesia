<!-- Row 1: 2 Kolom (Left: col-lg-7, Right: col-lg-5) -->
<div class="row">

    <!-- Kolom Kiri: col-lg-7 (Aktivitas Hari Ini & Informasi) -->
    <div class="col-lg-7">
        
        <!-- Widget Aktivitas Hari Ini -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_aktivitas"><?= $this->lang->line('dash_today_activity') ?: 'Aktivitas Hari Ini' ?></h3>
            </div>
            <div class="box-body no-padding">
                <div id="skeleton_aktivitas" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <ul class="products-list product-list-in-box" id="container_aktivitas" style="display: none;">
                    <!-- diisi oleh javascript -->
                </ul>
            </div>
        </div>

        <!-- Widget Informasi -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_informasi"><?= ($this->lang->line('dash_information_title') ?: 'Informasi') . ' - ' . ($this->lang->line('dash_all_units') ?: 'Semua Unit') ?></h3>
            </div>
            <div class="box-body no-padding">
                <div id="skeleton_informasi" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <ul class="products-list product-list-in-box" id="container_pengumuman" style="display: none;">
                    <!-- diisi oleh javascript -->
                </ul>
            </div>
        </div>

    </div>

    <!-- Kolom Kanan: col-lg-5 (Jadwal Hari Ini - Timeline List) -->
    <div class="col-lg-5">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title" id="title_jadwal"><?= ($this->lang->line('dash_today_schedule') ?: 'Jadwal Hari Ini') . ' - ' . ($this->lang->line('dash_all_units') ?: 'Semua Unit') ?></h3>
            </div>
            <div class="box-body no-padding" style="padding: 10px;">
                <div id="skeleton_jadwal" style="min-height: 250px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div id="container_jadwal" style="display: none; padding: 10px;">
                    <!-- Timeline akan dirender oleh javascript -->
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.renderAcademicTab = function(data) {
        // dummy for legacy call
    };

    window.loadAcademicWidgets = function(majorsId) {
        if (!$('#container_aktivitas').data('loaded')) {
            $('#container_aktivitas').data('loaded', true);
            loadWidgetAktivitas(majorsId);
        }
        if (!$('#container_pengumuman').data('loaded')) {
            $('#container_pengumuman').data('loaded', true);
            loadWidgetInformasi(majorsId);
        }
        if (!$('#container_jadwal').data('loaded')) {
            $('#container_jadwal').data('loaded', true);
            loadWidgetJadwal(majorsId);
        }
    };

    $(document).ready(function() {
        var majorsId = $('#select_majors').val();

        $('a[data-toggle="tab"][href="#academic-tab"]').on('shown.bs.tab', function(e) {
            window.loadAcademicWidgets(majorsId);
        });

        if ($('.nav-tabs li.active a').attr('href') === '#academic-tab') {
            window.loadAcademicWidgets(majorsId);
        }
    });

    function loadWidgetJadwal(majorsId) {
        $('#skeleton_jadwal').show();
        $('#container_jadwal').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_academic_jadwal') ?>',
            type: 'POST',
            dataType: 'json',
            data: { majors_id: majorsId },
            success: function(res) {
                $('#skeleton_jadwal').hide();

                if (res.title) {
                    $('#title_jadwal').text(res.title);
                }

                var jadwalHtml = '';
                if (res.data && res.data.length > 0) {
                    jadwalHtml += '<ul class="timeline timeline-inverse" style="margin-bottom:0;">';
                    $.each(res.data, function(i, item) {
                        var colorClass = item.color || 'bg-blue';
                        jadwalHtml += '<li>' +
                            '<i class="fa fa-clock-o ' + colorClass + '"></i>' +
                            '<div class="timeline-item" style="margin-right:0;">' +
                            '<span class="time" style="font-size:11px;"><i class="fa fa-clock-o"></i> ' + item.time + '</span>' +
                            '<h3 class="timeline-header" style="font-size:13px; font-weight:bold; padding:8px 10px;">' + item.title + '</h3>' +
                            '<div class="timeline-body" style="font-size:12px; padding:6px 10px;">' + item.desc + '</div>' +
                            '</div>' +
                            '</li>';
                    });
                    jadwalHtml += '</ul>';
                } else {
                    jadwalHtml = '<div class="text-center text-muted" style="padding:15px;"><?= $this->lang->line('dash_no_schedule') ?: 'Tidak ada jadwal hari ini' ?></div>';
                }

                $('#container_jadwal').html(jadwalHtml).show();
            },
            error: function() {
                $('#skeleton_jadwal').html('<p class="text-danger text-center" style="padding:15px;">Gagal memuat data</p>');
            }
        });
    }

    function loadWidgetInformasi(majorsId) {
        $('#skeleton_informasi').show();
        $('#container_pengumuman').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_academic_informasi') ?>',
            type: 'POST',
            dataType: 'json',
            data: { majors_id: majorsId },
            success: function(res) {
                $('#skeleton_informasi').hide();

                if (res.title) {
                    $('#title_informasi').text(res.title);
                }

                var pengumumanHtml = '';
                if (res.data && res.data.length > 0) {
                    $.each(res.data, function(i, pg) {
                        pengumumanHtml += '<li class="item" style="padding: 10px 10px; border-left: 3px solid; ' + pg.border + '">' +
                            '<div class="product-info" style="margin-left: 0;">' +
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

    function loadWidgetAktivitas(majorsId) {
        $('#skeleton_aktivitas').show();
        $('#container_aktivitas').hide();

        $.ajax({
            url: '<?= site_url('manage/dashboard/ajax_academic_aktivitas') ?>',
            type: 'POST',
            dataType: 'json',
            data: { majors_id: majorsId },
            success: function(res) {
                $('#skeleton_aktivitas').hide();

                if (res.title) {
                    $('#title_aktivitas').text(res.title);
                }

                var aktivitasHtml = '';
                if (res.data && res.data.length > 0) {
                    $.each(res.data, function(i, ak) {
                        aktivitasHtml += '<li class="item" style="padding: 8px 10px;">' +
                            '<div class="product-img" style="float:left;">' +
                            '<i class="fa ' + ak.icon + '" style="width:34px; height:34px; line-height:34px; text-align:center; border-radius:50%; color:#fff; display:block; font-size:14px;"></i>' +
                            '</div>' +
                            '<div class="product-info" style="margin-left:45px;">' +
                            '<a href="javascript:void(0);" class="product-title" style="display:block; font-size:12px;">' +
                            ak.title +
                            (ak.time && ak.time !== '-' ? '<span class="label label-warning pull-right" style="font-size:10px;">' + ak.time + '</span>' : '') +
                            '</a>' +
                            '<span class="product-description" style="display:block; font-size:11px; margin-top:2px;">' + ak.desc + '</span>' +
                            '</div></li>';
                    });
                } else {
                    aktivitasHtml = '<li class="item text-center text-muted" style="padding:15px;"><?= $this->lang->line('dash_no_activity') ?: 'Tidak ada aktivitas' ?></li>';
                }

                $('#container_aktivitas').html(aktivitasHtml).show();
            },
            error: function() {
                $('#skeleton_aktivitas').html('<p class="text-danger text-center" style="padding:15px;">Gagal memuat data</p>');
            }
        });
    }
</script>
