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
                <strong>Selamat Datang, <?= $role_name; ?>!</strong><br>
                <span class="text-muted">Dashboard Ringkasan <?= (isset($nama_unit) ? $nama_unit : '') ?></span>
            </div>
            <div class="col-md-3">
                <select name="majors_id" id="select_majors" class="form-control" onchange="if(this.value){ window.location.href='?m='+this.value; } else { window.location.href='<?= site_url('manage') ?>'; }">
                    <option value="">Semua Unit</option>
                    <?php
                    if (!empty($filter_majors)):
                        foreach ($filter_majors as $maj):
                    ?>
                            <option value="<?= $maj['majors_id'] ?>" <?= (isset($_GET['m']) && $_GET['m'] == $maj['majors_id']) ? 'selected' : '' ?>>
                                <?= !empty($maj['majors_short_name']) ? $maj['majors_short_name'] . ' - ' . $maj['majors_name'] : $maj['majors_name'] ?>
                            </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
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
            <?php
            if (!empty($dummy_top_metrics)):
                foreach ($dummy_top_metrics as $m):
            ?>
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
                            <a href="#finance-tab" data-toggle="tab" aria-expanded="true">Keuangan</a>
                        </li>
                        <li class="">
                            <a href="#student-tab" data-toggle="tab" aria-expanded="false">Kesiswaan</a>
                        </li>
                        <li class="">
                            <a href="#staffing-tab" data-toggle="tab" aria-expanded="false">Kepegawaian</a>
                        </li>
                        <li class="">
                            <a href="#academic-tab" data-toggle="tab" aria-expanded="false">Akademik</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="finance-tab">
                            <?= $this->load->view('tabs/finance_tab'); ?>
                        </div>

                        <div class="tab-pane" id="academic-tab">
                            <?= $this->load->view('tabs/academic_tab'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
    $(document).ready(function() {
        $('#select_majors').select2({
            width: '100%',
            theme: 'classic',
        });

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