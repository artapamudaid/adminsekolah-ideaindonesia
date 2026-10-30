<style>
  /* Modern Statistik Styles */
  .stat-card-modern {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
    transition: box-shadow 0.25s ease;
    overflow: hidden;
  }
  .stat-card-modern:hover {
    box-shadow: 0 8px 25px rgba(37, 117, 252, 0.08);
  }
  .stat-card-modern .card-header-modern {
    padding: 16px 20px;
    background: #ffffff;
    border-bottom: 1px solid #f1f3f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .stat-card-modern .card-title-modern {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .stat-card-modern .card-title-modern i {
    color: #2575FC;
  }
  .stat-card-modern .card-body-modern {
    padding: 20px;
  }

  .stat-filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 16px;
    padding: 12px 14px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
  }
  .stat-filter-form .form-control {
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    height: 36px;
    padding: 4px 12px;
    font-size: 13px;
    box-shadow: none;
  }
  .stat-filter-form .form-control:focus {
    border-color: #2575FC;
  }
  .btn-filter-modern {
    background: #2575FC;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    height: 36px;
    padding: 0 16px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
  }
  .btn-filter-modern:hover, .btn-filter-modern:focus {
    background: #1a5ac9;
    color: #ffffff;
  }

  .chart-canvas-container {
    position: relative;
    width: 100%;
    min-height: 270px;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="font-weight: 700; color: #1e293b;">
      Statistik & Visualisasi Data
      <small style="color: #64748b; font-size: 14px;">Grafik Finansial, Kepegawaian, dan Distribusi Siswa</small>
    </h1>
    <ol class="breadcrumb" style="background: transparent;">
      <li><a href="<?php echo site_url('manage') ?>" style="color: #2575FC;"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Statistik</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- Kolom Kiri -->
      <div class="col-md-6">
        <!-- 1. BAR CHART: Tagihan Siswa Tiap Unit -->
        <div class="stat-card-modern">
          <div class="card-header-modern">
            <h3 class="card-title-modern"><i class="fa fa-exclamation-triangle"></i> Tagihan Siswa Tiap Unit</h3>
            <div class="box-tools pull-right">
              <a href="<?php echo site_url('manage/statistik/tagihan_siswa'); ?>" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 600; color: #2575FC;">Detail Data</a>
            </div>
          </div>
          <div class="card-body-modern">
            <div class="chart-canvas-container">
              <canvas id="Chart_tagihan"></canvas>
            </div>
          </div>
        </div>

        <!-- 2. LINE CHART: Laporan Akunting -->
        <div class="stat-card-modern">
          <div class="card-header-modern">
            <h3 class="card-title-modern"><i class="fa fa-line-chart"></i> Laporan Akunting (Tren Penerimaan & Pengeluaran)</h3>
          </div>
          <div class="card-body-modern">
            <?php echo form_open(current_url(), array('class' => 'stat-filter-form', 'method' => 'get')) ?>
              <span style="font-size: 12px; font-weight: 600; color: #64748b;"><i class="fa fa-filter"></i> Periode:</span>
              <select name="period_akunting" class="form-control" style="width: auto; min-width: 170px;">
                <?php if (!empty($period)) { foreach ($period as $row) { ?>
                  <option value="<?php echo $row['period_id']; ?>" <?php echo ($selected_period_akunting == $row['period_id']) ? 'selected' : '' ?>>
                    T.A. <?php echo $row['period_start'] ?>/<?php echo $row['period_end'] ?>
                  </option>
                <?php } } ?>
              </select>
              <button type="submit" class="btn btn-filter-modern"><i class="fa fa-check"></i> Terapkan</button>
            <?php echo form_close(); ?>

            <div class="chart-canvas-container">
              <canvas id="lap_akunting_line"></canvas>
            </div>
          </div>
        </div>

        <!-- 3. DOUGHNUT CHART: Jumlah Status Guru & Pegawai -->
        <div class="stat-card-modern">
          <div class="card-header-modern">
            <h3 class="card-title-modern"><i class="fa fa-users"></i> Proporsi Guru & Pegawai</h3>
            <div class="box-tools pull-right">
              <a href="<?php echo site_url('manage/statistik/total_guru_pegawai'); ?>" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 600; color: #2575FC;">Detail Data</a>
            </div>
          </div>
          <div class="card-body-modern">
            <div class="chart-canvas-container" style="max-height: 290px;">
              <canvas id="Chart_guru_pegawai"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="col-md-6">
        <!-- 4. BAR CHART: Jurnal Keuangan -->
        <div class="stat-card-modern">
          <div class="card-header-modern">
            <h3 class="card-title-modern"><i class="fa fa-balance-scale"></i> Jurnal Keuangan (Penerimaan vs Pengeluaran)</h3>
          </div>
          <div class="card-body-modern">
            <?php echo form_open(current_url(), array('class' => 'stat-filter-form', 'method' => 'get')) ?>
              <span style="font-size: 12px; font-weight: 600; color: #64748b;"><i class="fa fa-calendar"></i> Bulan:</span>
              <select name="month_jurnal" class="form-control" style="width: auto; min-width: 130px;">
                <?php if (!empty($month)) { foreach ($month as $row) { ?>
                  <option value="<?php echo $row['month_id']; ?>" <?php echo ($selected_month_jurnal == $row['month_id']) ? 'selected' : '' ?>>
                    <?php echo $row['month_name'] ?>
                  </option>
                <?php } } ?>
              </select>

              <span style="font-size: 12px; font-weight: 600; color: #64748b;">Tahun:</span>
              <select name="period_jurnal" class="form-control" style="width: auto; min-width: 140px;">
                <?php if (!empty($period)) { foreach ($period as $row) { ?>
                  <option value="<?php echo $row['period_id']; ?>" <?php echo ($selected_period_jurnal == $row['period_id']) ? 'selected' : '' ?>>
                    T.A. <?php echo $row['period_start'] ?>/<?php echo $row['period_end'] ?>
                  </option>
                <?php } } ?>
              </select>
              <button type="submit" class="btn btn-filter-modern"><i class="fa fa-check"></i> Filter</button>
            <?php echo form_close(); ?>

            <div class="chart-canvas-container">
              <canvas id="Chart_jurnalKeuangan"></canvas>
            </div>
          </div>
        </div>

        <!-- 5. BAR CHART: Laporan Kas Tiap Unit -->
        <div class="stat-card-modern">
          <div class="card-header-modern">
            <h3 class="card-title-modern"><i class="fa fa-money"></i> Penerimaan KAS Tiap Unit</h3>
            <div class="box-tools pull-right">
              <a href="<?php echo site_url('manage/statistik/laporan_kas'); ?>" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 600; color: #2575FC;">Detail Data</a>
            </div>
          </div>
          <div class="card-body-modern">
            <div class="chart-canvas-container">
              <canvas id="Chart_lap_kas"></canvas>
            </div>
          </div>
        </div>

        <!-- 6. BAR CHART: Jumlah Siswa Tiap Jenjang -->
        <div class="stat-card-modern">
          <div class="card-header-modern">
            <h3 class="card-title-modern"><i class="fa fa-graduation-cap"></i> Jumlah Siswa Tiap Jenjang</h3>
            <div class="box-tools pull-right">
              <a href="<?php echo site_url('manage/statistik/jumlah_siswa'); ?>" class="btn btn-default btn-xs" style="border-radius: 6px; font-weight: 600; color: #2575FC;">Detail Data</a>
            </div>
          </div>
          <div class="card-body-modern">
            <div class="chart-canvas-container">
              <canvas id="myChart"></canvas>
            </div>
          </div>
        </div>
      </div>  
    </div>
  </section>
  <!-- /.content -->
</div>

<script type="text/javascript">
  // Helper Format Rupiah
  function formatRupiah(num) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
  }

  $(document).ready(function() {
    Chart.defaults.global.defaultFontFamily = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.global.defaultFontColor = '#64748b';

    // 1. Chart Tagihan Siswa Tiap Unit
    var ctxTagihan = document.getElementById("Chart_tagihan");
    if (ctxTagihan) {
      new Chart(ctxTagihan.getContext('2d'), {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($chart_tagihan['labels']); ?>,
          datasets: [
            {
              label: 'Terlambat <= 3 Bulan',
              data: <?php echo json_encode($chart_tagihan['kategori_a']); ?>,
              backgroundColor: '#f59e0b',
              borderColor: '#d97706',
              borderWidth: 1,
              borderRadius: 6
            },
            {
              label: 'Terlambat > 3 Bulan',
              data: <?php echo json_encode($chart_tagihan['kategori_b']); ?>,
              backgroundColor: '#ef4444',
              borderColor: '#dc2626',
              borderWidth: 1,
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                return label + ': ' + formatRupiah(tooltipItem.yLabel);
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { display: false }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                callback: function(value) { return formatRupiah(value); }
              },
              gridLines: { color: '#f1f5f9' }
            }]
          }
        }
      });
    }

    // 2. Chart Laporan Akunting (Line Chart)
    var ctxAkunting = document.getElementById("lap_akunting_line");
    if (ctxAkunting) {
      new Chart(ctxAkunting.getContext('2d'), {
        type: 'line',
        data: {
          labels: <?php echo json_encode($chart_akunting['labels']); ?>,
          datasets: [
            {
              label: 'Penerimaan',
              data: <?php echo json_encode($chart_akunting['penerimaan']); ?>,
              borderColor: '#2575FC',
              backgroundColor: 'rgba(37, 117, 252, 0.08)',
              borderWidth: 2,
              pointBackgroundColor: '#2575FC',
              pointRadius: 4,
              pointHoverRadius: 6,
              fill: true,
              lineTension: 0.3
            },
            {
              label: 'Pengeluaran',
              data: <?php echo json_encode($chart_akunting['pengeluaran']); ?>,
              borderColor: '#ef4444',
              backgroundColor: 'rgba(239, 68, 68, 0.05)',
              borderWidth: 2,
              pointBackgroundColor: '#ef4444',
              pointRadius: 4,
              pointHoverRadius: 6,
              fill: true,
              lineTension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                return label + ': ' + formatRupiah(tooltipItem.yLabel);
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { display: false }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                callback: function(value) { return formatRupiah(value); }
              },
              gridLines: { color: '#f1f5f9' }
            }]
          }
        }
      });
    }

    // 3. Chart Status Guru & Pegawai (Doughnut)
    var ctxGuru = document.getElementById("Chart_guru_pegawai");
    if (ctxGuru) {
      new Chart(ctxGuru.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: ['Guru Tetap', 'Guru Tidak Tetap / Kontrak'],
          datasets: [{
            data: [
              <?php echo (int)$chart_guru_pegawai['tetap']; ?>,
              <?php echo (int)$chart_guru_pegawai['non_tetap']; ?>
            ],
            backgroundColor: ['#10b981', '#f59e0b'],
            hoverBackgroundColor: ['#059669', '#d97706'],
            borderWidth: 2,
            borderColor: '#ffffff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 14,
              padding: 16
            }
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.labels[tooltipItem.index] || '';
                var value = data.datasets[0].data[tooltipItem.index] || 0;
                return label + ': ' + value + ' Orang';
              }
            }
          }
        }
      });
    }

    // 4. Chart Jurnal Keuangan
    var ctxJurnal = document.getElementById("Chart_jurnalKeuangan");
    if (ctxJurnal) {
      new Chart(ctxJurnal.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ["Penerimaan", "Pengeluaran", "Saldo Bersih"],
          datasets: [
            {
              label: "Bulan Terpilih",
              data: <?php echo json_encode($chart_jurnal['bulan']); ?>,
              backgroundColor: '#2575FC',
              borderWidth: 0,
              borderRadius: 6
            },
            {
              label: "Tahun Ajaran Terpilih",
              data: <?php echo json_encode($chart_jurnal['tahun']); ?>,
              backgroundColor: '#10b981',
              borderWidth: 0,
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                return label + ': ' + formatRupiah(tooltipItem.yLabel);
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { display: false }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                callback: function(value) { return formatRupiah(value); }
              },
              gridLines: { color: '#f1f5f9' }
            }]
          }
        }
      });
    }

    // 5. Chart Laporan KAS Tiap Unit
    var ctxKas = document.getElementById("Chart_lap_kas");
    if (ctxKas) {
      new Chart(ctxKas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($chart_kas_unit['labels']); ?>,
          datasets: [{
            label: "Penerimaan Kas",
            data: <?php echo json_encode($chart_kas_unit['values']); ?>,
            backgroundColor: '#3b82f6',
            hoverBackgroundColor: '#2575FC',
            borderRadius: 6,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { display: false },
          tooltips: {
            callbacks: {
              label: function(tooltipItem) {
                return 'Penerimaan: ' + formatRupiah(tooltipItem.yLabel);
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { display: false }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                callback: function(value) { return formatRupiah(value); }
              },
              gridLines: { color: '#f1f5f9' }
            }]
          }
        }
      });
    }

    // 6. Chart Jumlah Siswa Tiap Jenjang
    var ctxSiswa = document.getElementById("myChart");
    if (ctxSiswa) {
      new Chart(ctxSiswa.getContext('2d'), {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($chart_siswa['labels']); ?>,
          datasets: [
            {
              label: "Siswa Aktif",
              data: <?php echo json_encode($chart_siswa['aktif']); ?>,
              backgroundColor: '#10b981',
              borderRadius: 6,
              borderWidth: 0
            },
            {
              label: "Total Siswa",
              data: <?php echo json_encode($chart_siswa['total']); ?>,
              backgroundColor: '#2575FC',
              borderRadius: 6,
              borderWidth: 0
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                return label + ': ' + tooltipItem.yLabel + ' Siswa';
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { display: false }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                stepSize: 10
              },
              gridLines: { color: '#f1f5f9' }
            }]
          }
        }
      });
    }
  });
</script>
