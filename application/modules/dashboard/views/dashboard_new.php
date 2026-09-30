<style>
    .info-box-icon {
        /* height: 100%; */
        margin-left: 0;
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

        <!-- Header Bar -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4">
                <strong>Selamat Datang, Admin</strong><br>
                <span class="text-muted">Dashboard Ringkasan</span>
            </div>
            <div class="col-md-3">
                <select name="majors_id" id="majors_id" class="form-control" onchange="if(this.value){ window.location.href='?majors_id='+this.value; } else { window.location.href='<?= site_url('manage') ?>'; }">
                    <option value="">Unit Sekolah</option>
                    <?php if (!empty($active_majors)): foreach ($active_majors as $maj): ?>
                            <option value="<?= $maj['majors_id'] ?>" <?= (isset($_GET['majors_id']) && $_GET['majors_id'] == $maj['majors_id']) ? 'selected' : '' ?>>
                                <?= !empty($maj['majors_short_name']) ? $maj['majors_short_name'] . ' - ' . $maj['majors_name'] : $maj['majors_name'] ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Cari siswa, tagihan, transaksi...">
            </div>
            <div class="col-md-2">
                <a href="" class="btn btn-default btn-block">
                    Lihat Website <i class="fa fa-external-link"></i>
                </a>
            </div>
        </div>

        <!-- Top Metrics Row -->
        <div class="row">
            <?php if (!empty($dummy_top_metrics)): foreach ($dummy_top_metrics as $m): ?>
                    <div class="col-md-2 col-sm-4 col-xs-6">
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
            <?php endforeach;
            endif; ?>
        </div>

        <!-- Row 2: 3 Kolom (Ikhtisar Keuangan, Arus Kas X Bulan Terakhir, Tagihan Bulan Ini) -->
        <div class="row">

            <!-- Kolom 1: Ikhtisar Keuangan -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ikhtisar Keuangan</h3>
                        <div class="box-tools pull-right">
                            <select id="filter_bulan_ikhtisar" class="form-control input-sm" style="width:auto; display:inline-block; font-size:11px; height:28px; padding:2px 8px;">
                                <option value="1">Januari 2026</option>
                                <option value="2">Februari 2026</option>
                                <option value="3">Maret 2026</option>
                                <option value="4">April 2026</option>
                                <option value="5" selected>Mei 2026</option>
                                <option value="6">Juni 2026</option>
                                <option value="7">Juli 2026</option>
                                <option value="8">Agustus 2026</option>
                                <option value="9">September 2026</option>
                                <option value="10">Oktober 2026</option>
                                <option value="11">November 2026</option>
                                <option value="12">Desember 2026</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 200px;">
                        <div class="row" style="display: flex; align-items: center; margin: 0;">
                            <div class="col-xs-6" style="padding: 0; position: relative;">
                                <div style="position: relative; height: 160px; width: 100%;">
                                    <canvas id="Chart_ikhtisarDoughnut"></canvas>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                        <span style="font-size: 10px; color: #888; display: block; line-height: 1;">Total</span>
                                        <strong style="font-size: 13px; color: #333; line-height: 1.2; display: block; margin-top: 2px;"><?= !empty($dummy_ikhtisar_doughnut['total_display']) ? $dummy_ikhtisar_doughnut['total_display'] : '245,75 JT' ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6" style="padding-left: 10px; padding-right: 0;">
                                <div style="margin-bottom: 12px;">
                                    <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                        <span style="display:inline-block; width:10px; height:10px; background-color:#28a745; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                        <span style="font-size: 11px; font-weight: 600; color: #444;">Pemasukan</span>
                                    </div>
                                    <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                        <?= !empty($dummy_ikhtisar_doughnut['pemasukan']['text']) ? $dummy_ikhtisar_doughnut['pemasukan']['text'] : '312.450.000' ?> (<?= !empty($dummy_ikhtisar_doughnut['pemasukan']['pct']) ? $dummy_ikhtisar_doughnut['pemasukan']['pct'] : '56.2%' ?>)
                                    </div>
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                        <span style="display:inline-block; width:10px; height:10px; background-color:#dc3545; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                        <span style="font-size: 11px; font-weight: 600; color: #444;">Pengeluaran</span>
                                    </div>
                                    <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                        <?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['text']) ? $dummy_ikhtisar_doughnut['pengeluaran']['text'] : '198.230.000' ?> (<?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['pct']) ? $dummy_ikhtisar_doughnut['pengeluaran']['pct'] : '35.6%' ?>)
                                    </div>
                                </div>
                                <div>
                                    <div style="display:flex; align-items:center; margin-bottom: 2px;">
                                        <span style="display:inline-block; width:10px; height:10px; background-color:#007bff; margin-right:6px; border-radius:2px; flex-shrink: 0;"></span>
                                        <span style="font-size: 11px; font-weight: 600; color: #444;">Saldo Akhir</span>
                                    </div>
                                    <div style="font-size: 11px; color: #555; padding-left: 16px;">
                                        <?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['text']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['text'] : '245.750.000' ?> (<?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['pct']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['pct'] : '8.2%' ?>)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Arus Kas 6 bulan Terakhir -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="title_arus_kas">Arus Kas 6 Bulan Terakhir</h3>
                        <div class="box-tools pull-right">
                            <select id="filter_arus_kas_bulan" class="form-control input-sm" style="width:auto; display:inline-block; font-size:11px; height:28px; padding:2px 8px;">
                                <option value="3">3 Bulan</option>
                                <option value="6" selected>6 Bulan</option>
                                <option value="9">9 Bulan</option>
                                <option value="12">12 Bulan</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 200px;">
                        <div style="position: relative; height: 160px; width: 100%;">
                            <canvas id="Chart_arusKas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 3: Tagihan Bulan Ini -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tagihan Bulan Ini</h3>
                    </div>
                    <div class="box-body" style="min-height: 200px;">
                        <?php if (!empty($dummy_tagihan_bulan)): foreach ($dummy_tagihan_bulan as $tg): ?>
                                <div class="progress-group" style="margin-bottom: 8px;">
                                    <span class="progress-text" style="font-size: 11px;"><?= $tg['name'] ?></span>
                                    <span class="progress-number pull-right" style="font-size: 11px;"><b><?= $tg['text'] ?></b> (<?= $tg['progress'] ?>%)</span>
                                    <div class="progress sm" style="margin-top: 3px; margin-bottom: 0; height: 8px;">
                                        <div class="progress-bar <?= $tg['bar'] ?>" style="width: <?= $tg['progress'] ?>%"></div>
                                    </div>
                                </div>
                        <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Row 3: 3 Kolom (Grafik Pembayaran SPP, Transaksi Terbaru, Tabungan Siswa) -->
        <div class="row">

            <!-- Kolom 1: Grafik Pembayaran (SPP) -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Pembayaran (SPP)</h3>
                    </div>
                    <div class="box-body" style="min-height: 200px;">
                        <div style="position: relative; height: 160px; width: 100%;">
                            <canvas id="Chart_pembayaranSPP"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Transaksi Terbaru -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transaksi Terbaru</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-default btn-sm">Lihat Semua</button>
                        </div>
                    </div>
                    <div class="box-body no-padding" style="min-height: 200px;">
                        <div class="table-responsive">
                            <table class="table table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($dummy_transaksi)): foreach ($dummy_transaksi as $tr): ?>
                                            <tr>
                                                <td style="font-size:11px;"><?= $tr['tgl'] ?></td>
                                                <td style="font-size:11px;"><?= $tr['desc'] ?></td>
                                                <td style="font-size:11px;"><?= $tr['nominal'] ?></td>
                                                <td><span class="label <?= $tr['label'] ?>" style="font-size:10px;"><?= $tr['status'] ?></span></td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 3: Top 5 Tabungan Siswa -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">5 Siswa Tabungan Terbesar</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-default btn-sm">Detail</button>
                        </div>
                    </div>
                    <div class="box-body no-padding" style="min-height: 200px;">
                        <table class="table table-condensed table-striped no-margin">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($dummy_tabungan)): foreach ($dummy_tabungan as $tb): ?>
                                        <tr>
                                            <td><?= $tb['no'] ?></td>
                                            <td><?= $tb['nama'] ?></td>
                                            <td><?= $tb['kelas'] ?></td>
                                            <td><?= $tb['nominal'] ?></td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Row 4: 3 Kolom (Aktivitas Hari Ini, Pengumuman, Jadwal Hari Ini) -->
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

    </section>
</div>

<script>
    $(document).ready(function() {
        // 1. Doughnut Chart: Ikhtisar Keuangan
        var ctxDoughnut = document.getElementById("Chart_ikhtisarDoughnut");
        if (ctxDoughnut) {
            new Chart(ctxDoughnut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran', 'Saldo Akhir'],
                    datasets: [{
                        data: [
                            <?= !empty($dummy_ikhtisar_doughnut['pemasukan']['val']) ? $dummy_ikhtisar_doughnut['pemasukan']['val'] : 312450000 ?>,
                            <?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['val']) ? $dummy_ikhtisar_doughnut['pengeluaran']['val'] : 198230000 ?>,
                            <?= !empty($dummy_ikhtisar_doughnut['saldo_akhir']['val']) ? $dummy_ikhtisar_doughnut['saldo_akhir']['val'] : 245750000 ?>
                        ],
                        backgroundColor: ['#28a745', '#dc3545', '#007bff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 70,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.labels[tooltipItem.index] || '';
                                var value = data.datasets[0].data[tooltipItem.index] || 0;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            });
        }

        // 2. Line Chart: Arus Kas X Bulan Terakhir (dengan filter [3, 6, 9, 12])
        var dummyArusKas = <?= !empty($dummy_arus_kas) ? json_encode($dummy_arus_kas) : json_encode([
                                6 => [
                                    'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
                                    'pemasukan' => [38000000, 42000000, 45000000, 52000000, 60000000, 58000000],
                                    'pengeluaran' => [25000000, 27000000, 28000000, 31000000, 35000000, 33000000]
                                ]
                            ]) ?>;

        var ctxArusKas = document.getElementById("Chart_arusKas");
        var chartArusKasInstance = null;

        if (ctxArusKas) {
            chartArusKasInstance = new Chart(ctxArusKas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dummyArusKas[6].labels,
                    datasets: [{
                            label: 'Pemasukan',
                            data: dummyArusKas[6].pemasukan,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            pointBackgroundColor: '#28a745',
                            pointRadius: 4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Pengeluaran',
                            data: dummyArusKas[6].pengeluaran,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            pointBackgroundColor: '#dc3545',
                            pointRadius: 4,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            fontSize: 10
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontSize: 10,
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' JT';
                                }
                            },
                            scaleLabel: {
                                display: false
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                var value = tooltipItem.yLabel || 0;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            });
        }

        $('#filter_arus_kas_bulan').on('change', function() {
            var selectedBulan = $(this).val();
            $('#title_arus_kas').text('Arus Kas ' + selectedBulan + ' Bulan Terakhir');

            if (chartArusKasInstance && dummyArusKas[selectedBulan]) {
                chartArusKasInstance.data.labels = dummyArusKas[selectedBulan].labels;
                chartArusKasInstance.data.datasets[0].data = dummyArusKas[selectedBulan].pemasukan;
                chartArusKasInstance.data.datasets[1].data = dummyArusKas[selectedBulan].pengeluaran;
                chartArusKasInstance.update();
            }
        });

        // 3. Combo Bar & Line Chart: Grafik Pembayaran SPP
        var ctxSpp = document.getElementById("Chart_pembayaranSPP");
        if (ctxSpp) {
            new Chart(ctxSpp.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?= !empty($dummy_spp_chart['labels']) ? json_encode($dummy_spp_chart['labels']) : json_encode(['Kelas 7A', 'Kelas 7B', 'Kelas 8A', 'Kelas 8B', 'Kelas 9A', 'Kelas 9B']) ?>,
                    datasets: [{
                            label: 'Collection Rate (%)',
                            type: 'line',
                            data: <?= !empty($dummy_spp_chart['collection_rate']) ? json_encode($dummy_spp_chart['collection_rate']) : json_encode([87.5, 83.3, 93.8, 84.4, 97.0, 90.6]) ?>,
                            borderColor: '#007bff',
                            backgroundColor: 'transparent',
                            pointBackgroundColor: '#007bff',
                            pointRadius: 4,
                            yAxisID: 'y-axis-rate',
                            fill: false,
                            borderWidth: 2
                        },
                        {
                            label: 'Lunas',
                            type: 'bar',
                            data: <?= !empty($dummy_spp_chart['lunas']) ? json_encode($dummy_spp_chart['lunas']) : json_encode([28, 25, 30, 27, 32, 29]) ?>,
                            backgroundColor: '#28a745',
                            yAxisID: 'y-axis-count'
                        },
                        {
                            label: 'Belum Lunas',
                            type: 'bar',
                            data: <?= !empty($dummy_spp_chart['belum_lunas']) ? json_encode($dummy_spp_chart['belum_lunas']) : json_encode([4, 5, 2, 5, 1, 3]) ?>,
                            backgroundColor: '#dc3545',
                            yAxisID: 'y-axis-count'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            fontSize: 10
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                                id: 'y-axis-count',
                                type: 'linear',
                                position: 'left',
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 10
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Siswa',
                                    fontSize: 10
                                }
                            },
                            {
                                id: 'y-axis-rate',
                                type: 'linear',
                                position: 'right',
                                ticks: {
                                    beginAtZero: true,
                                    max: 100,
                                    fontSize: 10,
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                gridLines: {
                                    display: false
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Rate (%)',
                                    fontSize: 10
                                }
                            }
                        ]
                    }
                }
            });
        }
    });
</script>