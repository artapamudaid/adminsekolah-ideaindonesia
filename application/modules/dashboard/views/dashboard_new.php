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
                        <select name="majors_id" id="select_majors" class="form-control" onchange="if(this.value){ window.location.href='?m='+this.value; } else { window.location.href='<?= site_url('manage') ?>'; }">
                            <option value=""><?= $this->lang->line('dash_all_units') ?></option>
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
            if (!empty($dummy_top_metrics)):
                foreach ($dummy_top_metrics as $m):
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
        var selectedMajors = '<?= isset($selected_majors) ? $selected_majors : '' ?>';

        $('#select_majors').select2({
            width: '100%',
            theme: 'classic',
        });

        // 1. DataTables Realisasi Akun Pembayaran
        if ($('#table_realisasi_akun').length) {
            $('#filter_period_realisasi').select2({
                width: '100%',
                theme: 'classic'
            });
            $('#start_month').select2({
                theme: 'classic',
                width: '100%',
            });
            $('#end_month').select2({
                theme: 'classic',
                width: '100%'
            });

            var tableRealisasi = $('#table_realisasi_akun').DataTable({
                "processing": true,
                "serverSide": false,
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
                "ajax": {
                    "url": "<?= site_url('manage/dashboard/ajax_realisasi_akun') ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.period_id = $('#filter_period_realisasi').val();
                        d.start_month = $('#start_month').val();
                        d.end_month = $('#end_month').val();
                        d.majors_id = selectedMajors;
                    }
                },
                "columns": [{
                        "data": "majors_short_name",
                        "className": "text-center"
                    },
                    {
                        "data": "unit"
                    },
                    {
                        "data": "tagihan",
                        "className": "text-right"
                    },
                    {
                        "data": "terbayar",
                        "className": "text-right"
                    },
                    {
                        "data": "belum",
                        "className": "text-right"
                    },
                    {
                        "data": "pencapaian",
                        "className": "text-center"
                    }
                ],
                "language": {
                    "emptyTable": "<?= $this->lang->line('dash_no_data') ?>",
                    "zeroRecords": "<?= $this->lang->line('dash_no_data') ?>"
                },
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    var sumCol = function(col) {
                        return api.column(col).data().reduce(function(a, b) {
                            var n = parseFloat(String(b).replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
                            return a + n;
                        }, 0);
                    };
                    var tagihan = api.column(2).data().reduce(function(a, b) {
                        return a + (parseFloat(String(b).replace(/[^0-9]/g, '')) || 0);
                    }, 0);
                    var terbayar = api.column(3).data().reduce(function(a, b) {
                        return a + (parseFloat(String(b).replace(/[^0-9]/g, '')) || 0);
                    }, 0);
                    var belum = api.column(4).data().reduce(function(a, b) {
                        return a + (parseFloat(String(b).replace(/[^0-9]/g, '')) || 0);
                    }, 0);
                    var pct = tagihan > 0 ? (terbayar / tagihan * 100).toFixed(1) : '0.0';

                    var fmt = function(n) {
                        return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    };
                    $(api.column(2).footer()).html(fmt(tagihan));
                    $(api.column(3).footer()).html(fmt(terbayar));
                    $(api.column(4).footer()).html(fmt(belum));
                    $(api.column(5).footer()).html('<span class="label ' + (pct >= 100 ? 'label-success' : pct >= 75 ? 'label-primary' : pct >= 50 ? 'label-warning' : 'label-danger') + '">' + pct.replace('.', ',') + '%</span>');
                },
                "bDestroy": true,
                "responsive": true,
                "autoWidth": false
            });

            $('#btn_filter_realisasi').on('click', function() {
                tableRealisasi.ajax.reload();
            });

            $('#btn_export_realisasi').on('click', function(e) {
                e.preventDefault();
                var period_id = $('#filter_period_realisasi').val() || '';
                var start_month = $('#start_month').val() || '';
                var end_month = $('#end_month').val() || '';
                var url = "<?= site_url('manage/dashboard/export_realisasi_excel') ?>?period_id=" + period_id + "&start_month=" + start_month + "&end_month=" + end_month;
                window.open(url, '_blank');
            });
        }

        // 2. Doughnut Chart: Ikhtisar Keuangan
        var ctxDoughnut = document.getElementById("Chart_ikhtisarDoughnut");
        var chartIkhtisarInstance = null;
        if (ctxDoughnut) {
            chartIkhtisarInstance = new Chart(ctxDoughnut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran'],
                    datasets: [{
                        data: [
                            <?= !empty($dummy_ikhtisar_doughnut['pemasukan']['val']) ? $dummy_ikhtisar_doughnut['pemasukan']['val'] : 0 ?>,
                            <?= !empty($dummy_ikhtisar_doughnut['pengeluaran']['val']) ? $dummy_ikhtisar_doughnut['pengeluaran']['val'] : 0 ?>
                        ],
                        backgroundColor: ['#28a745', '#dc3545'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 61,
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

        $('#filter_bulan_ikhtisar').on('change', function() {
            var selectedMonth = $(this).val();
            var selectedYear = $(this).find(':selected').data('year');

            $.ajax({
                url: '<?= site_url('manage/dashboard/ajax_ikhtisar_keuangan') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    month: selectedMonth,
                    year: selectedYear,
                    majors: selectedMajors
                },
                beforeSend: function() {
                    $('#box_ikhtisar_keuangan').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                },
                success: function(res) {
                    if (res) {
                        $('#ikhtisar_total_display').text(res.total_display);
                        $('#ikhtisar_pemasukan_text').text(res.pemasukan.text);
                        $('#ikhtisar_pemasukan_pct').text(res.pemasukan.pct);
                        $('#ikhtisar_pengeluaran_text').text(res.pengeluaran.text);
                        $('#ikhtisar_pengeluaran_pct').text(res.pengeluaran.pct);
                        $('#ikhtisar_saldo_text').text(res.saldo_akhir.text);
                        $('#ikhtisar_saldo_pct').text(res.saldo_akhir.pct);

                        if (chartIkhtisarInstance) {
                            chartIkhtisarInstance.data.datasets[0].data = [
                                res.pemasukan.val,
                                res.pengeluaran.val
                            ];
                            chartIkhtisarInstance.update();
                        }
                    }
                },
                complete: function() {
                    $('#box_ikhtisar_keuangan .overlay').remove();
                }
            });
        });

        // 4. Line Chart: Arus Kas X Bulan Terakhir (dengan filter [3, 6, 9, 12])
        var dataArusKas = <?= !empty($arus_kas) ? json_encode($arus_kas) : json_encode([]) ?>;

        var ctxArusKas = document.getElementById("Chart_arusKas");
        var chartArusKasInstance = null;

        if (ctxArusKas) {
            chartArusKasInstance = new Chart(ctxArusKas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dataArusKas[6].labels,
                    datasets: [{
                            label: 'Pemasukan',
                            data: dataArusKas[6].pemasukan,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            pointBackgroundColor: '#28a745',
                            pointRadius: 4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Pengeluaran',
                            data: dataArusKas[6].pengeluaran,
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
            var titleTemplate = $('#title_arus_kas').data('title-template') || 'Arus Kas %s Bulan Terakhir';
            $('#title_arus_kas').text(titleTemplate.replace('%s', selectedBulan));

            if (chartArusKasInstance && dataArusKas[selectedBulan]) {
                chartArusKasInstance.data.labels = dataArusKas[selectedBulan].labels;
                chartArusKasInstance.data.datasets[0].data = dataArusKas[selectedBulan].pemasukan;
                chartArusKasInstance.data.datasets[1].data = dataArusKas[selectedBulan].pengeluaran;
                chartArusKasInstance.update();
            }
        });

        // 5. Combo Bar & Line Chart: Grafik Pembayaran SPP
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