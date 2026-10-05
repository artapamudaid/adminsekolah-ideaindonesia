<!-- Row 1: 3 Kolom (Aktivitas Hari Ini, Pengumuman, Jadwal Hari Ini) -->
<div class="row">

    <!-- Kolom 1: Aktivitas Hari Ini -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Aktivitas Hari Ini</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="products-list product-list-in-box" id="container_aktivitas">
                    <!-- diisi oleh javascript -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Pengumuman -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengumuman</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="products-list product-list-in-box" id="container_pengumuman">
                    <!-- diisi oleh javascript -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom 3: Jadwal Hari Ini -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Jadwal Hari Ini</h3>
            </div>
            <div class="box-body" style="padding: 10px;">
                <ul class="timeline" style="padding-left: 0; margin-bottom: 0;" id="container_jadwal">
                    <!-- diisi oleh javascript -->
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    window.renderAcademicTab = function(data) {
        // Aktivitas
        var aktivitasHtml = '';
        if (data.dummy_aktivitas && data.dummy_aktivitas.length > 0) {
            $.each(data.dummy_aktivitas, function(i, ak) {
                aktivitasHtml += '<li class="item" style="padding: 8px 10px;">' +
                    '<div class="product-img" style="float:left;">' +
                    '<i class="fa ' + ak.icon + '" style="width:34px; height:34px; line-height:34px; text-align:center; border-radius:50%; color:#fff; display:block; font-size:14px;"></i>' +
                    '</div>' +
                    '<div class="product-info" style="margin-left:45px;">' +
                    '<a href="javascript:void(0);" class="product-title" style="display:block; font-size:12px;">' +
                    ak.title +
                    '<span class="label label-warning pull-right" style="font-size:10px;">' + ak.time + '</span>' +
                    '</a>' +
                    '<span class="product-description" style="display:block; font-size:11px; margin-top:2px;">' + ak.desc + '</span>' +
                    '</div></li>';
            });
        } else {
            aktivitasHtml = '<li class="item text-center text-muted" style="padding:15px;">Tidak ada aktivitas</li>';
        }
        $('#container_aktivitas').html(aktivitasHtml);

        // Pengumuman
        var pengumumanHtml = '';
        if (data.dummy_pengumuman && data.dummy_pengumuman.length > 0) {
            $.each(data.dummy_pengumuman, function(i, pg) {
                var borderClass = pg.border ? pg.border.replace('border-color: ', '').replace(';', '') : 'gray';
                pengumumanHtml += '<li class="item" style="border-left: 3px solid ' + borderClass + '; padding: 8px 10px;">' +
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
            pengumumanHtml = '<li class="item text-center text-muted" style="padding:15px;">Tidak ada pengumuman</li>';
        }
        $('#container_pengumuman').html(pengumumanHtml);

        // Jadwal
        var jadwalHtml = '';
        if (data.dummy_jadwal && data.dummy_jadwal.length > 0) {
            $.each(data.dummy_jadwal, function(i, jd) {
                jadwalHtml += '<li style="margin-bottom: 10px;">' +
                    '<i class="fa fa-clock-o ' + jd.color + '" style="width:24px; height:24px; line-height:24px; font-size:12px;"></i>' +
                    '<div class="timeline-item" style="margin-left: 30px; margin-top: 0; margin-bottom: 0; padding: 4px 8px;">' +
                    '<span class="time" style="font-size:10px;"><i class="fa fa-clock-o"></i> ' + jd.time + '</span>' +
                    '<h3 class="timeline-header no-border" style="padding: 0; font-size: 12px;">' +
                    '<strong>' + jd.title + '</strong><br>' +
                    '<small style="font-size:10px;">' + jd.desc + '</small>' +
                    '</h3></div></li>';
            });
        } else {
            jadwalHtml = '<li style="padding:15px;" class="text-center text-muted">Tidak ada jadwal</li>';
        }
        jadwalHtml += '<li><i class="fa fa-clock-o bg-gray" style="width:24px; height:24px; line-height:24px; font-size:12px;"></i></li>';
        $('#container_jadwal').html(jadwalHtml);
    };
</script>