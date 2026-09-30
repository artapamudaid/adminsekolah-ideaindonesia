<!-- Row 1: 3 Kolom (Aktivitas Hari Ini, Pengumuman, Jadwal Hari Ini) -->
<div class="row">

    <!-- Kolom 1: Aktivitas Hari Ini -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Aktivitas Hari Ini</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="products-list product-list-in-box">
                    <?php if (!empty($dummy_aktivitas)): foreach ($dummy_aktivitas as $ak): ?>
                            <li class="item" style="padding: 8px 10px;">
                                <div class="product-img" style="float:left;">
                                    <i class="fa <?= $ak['icon'] ?>" style="width:34px; height:34px; line-height:34px; text-align:center; border-radius:50%; color:#fff; display:block; font-size:14px;"></i>
                                </div>
                                <div class="product-info" style="margin-left:45px;">
                                    <a href="javascript:void(0);" class="product-title" style="display:block; font-size:12px;">
                                        <?= $ak['title'] ?>
                                        <span class="label label-warning pull-right" style="font-size:10px;"><?= $ak['time'] ?></span>
                                    </a>
                                    <span class="product-description" style="display:block; font-size:11px; margin-top:2px;"><?= $ak['desc'] ?></span>
                                </div>
                            </li>
                    <?php endforeach;
                    endif; ?>
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
                <ul class="products-list product-list-in-box">
                    <?php if (!empty($dummy_pengumuman)): foreach ($dummy_pengumuman as $pg): ?>
                            <li class="item" style="border-left: 3px solid <?= str_replace('border-color: ', '', str_replace(';', '', $pg['border'])) ?>; padding: 8px 10px;">
                                <div class="product-info" style="margin-left: 0;">
                                    <a href="javascript:void(0);" class="product-title" style="font-size:12px;">
                                        <i class="fa <?= $pg['icon'] ?> <?= $pg['label'] ?>"></i>
                                        <?= $pg['title'] ?>
                                        <span class="label label-default pull-right" style="font-size:9px;"><?= $pg['type'] ?></span>
                                    </a>
                                    <span class="product-description" style="white-space:normal; font-size:11px; margin:3px 0;"><?= $pg['desc'] ?></span>
                                    <span class="product-description text-muted" style="font-size:10px;"><i class="fa fa-calendar"></i> <?= $pg['date'] ?></span>
                                </div>
                            </li>
                    <?php endforeach;
                    endif; ?>
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
                <ul class="timeline" style="padding-left: 0; margin-bottom: 0;">
                    <?php if (!empty($dummy_jadwal)): foreach ($dummy_jadwal as $jd): ?>
                            <li style="margin-bottom: 10px;">
                                <i class="fa fa-clock-o <?= $jd['color'] ?>" style="width:24px; height:24px; line-height:24px; font-size:12px;"></i>
                                <div class="timeline-item" style="margin-left: 30px; margin-top: 0; margin-bottom: 0; padding: 4px 8px;">
                                    <span class="time" style="font-size:10px;"><i class="fa fa-clock-o"></i> <?= $jd['time'] ?></span>
                                    <h3 class="timeline-header no-border" style="padding: 0; font-size: 12px;">
                                        <strong><?= $jd['title'] ?></strong><br>
                                        <small style="font-size:10px;"><?= $jd['desc'] ?></small>
                                    </h3>
                                </div>
                            </li>
                    <?php endforeach;
                    endif; ?>
                    <li><i class="fa fa-clock-o bg-gray" style="width:24px; height:24px; line-height:24px; font-size:12px;"></i></li>
                </ul>
            </div>
        </div>
    </div>

</div>