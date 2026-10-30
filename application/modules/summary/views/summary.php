<style>
  /* FullCalendar Clean Theme Styling */
  #calendar {
    width: 100%;
    margin: 0 auto;
  }
  #calendar .fc-header-title h2 {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
  }
  #calendar .fc-button {
    border-radius: 6px;
    font-weight: 600;
  }
  #calendar .fc-event {
    background-color: #2575FC !important;
    border-color: #1a5ac9 !important;
    border-radius: 4px;
    padding: 2px 4px;
    color: #ffffff !important;
    font-size: 11px;
    font-weight: 600;
  }
  #calendar .fc-state-highlight {
    background-color: #eff6ff !important;
    color: #2575FC !important;
    font-weight: bold;
  }

  /* Modern Summary & Analytic Dashboard Styles */
  .summary-stat-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    padding: 20px;
    margin-bottom: 24px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    position: relative;
    overflow: hidden;
  }
  .summary-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(37, 117, 252, 0.08);
  }
  .summary-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #2575FC;
  }
  .summary-stat-card.card-green::before { background: #10b981; }
  .summary-stat-card.card-red::before { background: #ef4444; }
  .summary-stat-card.card-amber::before { background: #f59e0b; }
  .summary-stat-card.card-purple::before { background: #8b5cf6; }
  .summary-stat-card.card-cyan::before { background: #06b6d4; }

  .summary-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #f1f3f5;
  }
  .summary-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
  }
  .summary-card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }
  .icon-blue { background: #ebf3ff; color: #2575FC; }
  .icon-green { background: #e6f9f2; color: #10b981; }
  .icon-red { background: #fee2e2; color: #ef4444; }
  .icon-amber { background: #fef3c7; color: #f59e0b; }
  .icon-purple { background: #f3e8ff; color: #8b5cf6; }

  .stat-metric-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 13px;
    border-bottom: 1px solid #f8fafc;
  }
  .stat-metric-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
  .stat-metric-label {
    color: #64748b;
    font-weight: 500;
  }
  .stat-metric-value {
    color: #1e293b;
    font-weight: 700;
    font-family: inherit;
  }
  .stat-badge {
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-subtle-blue { background: #eff6ff; color: #1d4ed8; }
  .badge-subtle-green { background: #ecfdf5; color: #047857; }
  .badge-subtle-amber { background: #fffbeb; color: #b45309; }

  /* Modern Card Box */
  .modern-box {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
    overflow: hidden;
  }
  .modern-box-header {
    padding: 16px 20px;
    background: #ffffff;
    border-bottom: 1px solid #f1f3f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modern-box-title {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .modern-box-title i {
    color: #2575FC;
  }
  .modern-box-body {
    padding: 16px 20px;
  }
  .modern-box-footer {
    padding: 12px 20px;
    background: #f8fafc;
    border-top: 1px solid #f1f3f5;
    text-align: center;
  }
  .modern-box-footer a {
    color: #2575FC;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    transition: color 0.2s ease;
  }
  .modern-box-footer a:hover {
    color: #1a5ac9;
    text-decoration: underline;
  }

  /* Table Custom */
  .table-modern {
    width: 100%;
    margin-bottom: 0;
  }
  .table-modern th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 12px;
    border-top: none !important;
    border-bottom: 2px solid #e2e8f0 !important;
  }
  .table-modern td {
    padding: 10px 12px;
    font-size: 13px;
    color: #334155;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
  }
  .table-modern tbody tr:hover {
    background-color: #f8fafc;
  }
  .table-modern tfoot td {
    background-color: #f8fafc;
    font-weight: 700;
    color: #1e293b;
    border-top: 2px solid #e2e8f0 !important;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="font-weight: 700; color: #1e293b;">
      Analisa dan Performa
      <small style="color: #64748b; font-size: 14px;">Ringkasan Data Finansial, Siswa, dan Aktivitas Terkini</small>
    </h1>
    <ol class="breadcrumb" style="background: transparent;">
      <li><a href="<?php echo site_url('manage') ?>" style="color: #2575FC;"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Analisa dan Performa</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Top KPI Cards -->
    <div class="row">
      <!-- 1. Penerimaan KAS -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-blue">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Penerimaan KAS</h4>
            <div class="summary-card-icon icon-blue">
              <i class="fa fa-calculator"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #2575FC;">Rp <?php echo number_format((float)($total_debit_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_debit_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_debit_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 2. Pengeluaran KAS -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-red">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Pengeluaran KAS</h4>
            <div class="summary-card-icon icon-red">
              <i class="fa fa-money"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #ef4444;">Rp <?php echo number_format((float)($total_kredit_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_kredit_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_kredit_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 3. Pembayaran Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-green">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Pembayaran Siswa</h4>
            <div class="summary-card-icon icon-green">
              <i class="fa fa-bank"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #10b981;">Rp <?php echo number_format((float)($total_pay_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_pay_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_pay_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 4. Tagihan Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-amber">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Tagihan Siswa</h4>
            <div class="summary-card-icon icon-amber">
              <i class="fa fa-credit-card"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value" style="color: #d97706;">Rp <?php echo number_format((float)($tagihan_bulan_ini ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($tagihan_tahun_ini ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Status</span>
            <span class="stat-badge badge-subtle-amber"><i class="fa fa-clock-o"></i> Berjalan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Second Row KPI Cards -->
    <div class="row">
      <!-- 5. Jumlah Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-cyan">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Jumlah Siswa</h4>
            <div class="summary-card-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fa fa-graduation-cap"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Siswa Aktif</span>
            <span class="stat-metric-value" style="color: #0284c7;"><?php echo number_format((int)($total_active ?? 0), 0, ',', '.') ?> Siswa</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Alumni</span>
            <span class="stat-metric-value"><?php echo number_format((int)($total_alumni ?? 0), 0, ',', '.') ?> Siswa</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Lainnya</span>
            <span class="stat-metric-value"><?php echo number_format((int)($total_other ?? 0), 0, ',', '.') ?> Siswa</span>
          </div>
        </div>
      </div>

      <!-- 6. Jumlah Guru & Pegawai -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-green">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Guru & Pegawai</h4>
            <div class="summary-card-icon icon-green">
              <i class="fa fa-users"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Status Tetap</span>
            <span class="stat-metric-value" style="color: #059669;"><?php echo (int)($total_tetap ?? 0) ?> Orang</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tidak Tetap</span>
            <span class="stat-metric-value"><?php echo (int)($total_non_tetap ?? 0) ?> Orang</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Total Keseluruhan</span>
            <span class="stat-metric-value"><?php echo (int)($total_all ?? 0) ?> Orang</span>
          </div>
        </div>
      </div>

      <!-- 7. Tabungan Siswa -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-purple">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Tabungan Siswa</h4>
            <div class="summary-card-icon icon-purple">
              <i class="fa fa-google-wallet"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Hari ini</span>
            <span class="stat-metric-value" style="color: #7c3aed;">Rp <?php echo number_format((float)($total_banking_today ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Bulan ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_banking_month ?? 0), 0, ',', '.') ?></span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Tahun ini</span>
            <span class="stat-metric-value">Rp <?php echo number_format((float)($total_banking_period ?? 0), 0, ',', '.') ?></span>
          </div>
        </div>
      </div>

      <!-- 8. Masa Aktif Sistem -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="summary-stat-card card-blue">
          <div class="summary-card-header">
            <h4 class="summary-card-title">Masa Aktif Aplikasi</h4>
            <div class="summary-card-icon icon-blue">
              <i class="fa fa-calendar-check-o"></i>
            </div>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Paket Langganan</span>
            <span class="stat-badge badge-subtle-blue">Aktif Terverifikasi</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Status Server</span>
            <span class="stat-metric-value" style="color: #10b981;"><i class="fa fa-circle" style="font-size: 9px;"></i> Normal</span>
          </div>
          <div class="stat-metric-row">
            <span class="stat-metric-label">Waktu Sistem</span>
            <span class="stat-metric-value"><?php echo pretty_date(date('Y-m-d'), 'd F Y', false) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
      <!-- Kolom Kiri -->
      <div class="col-md-6">
        <!-- 1. Tagihan Siswa Setiap Unit -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-exclamation-circle"></i> Tagihan Siswa Setiap Unit</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Jenjang / Unit</th>
                    <th style="text-align: right;">Terlambat &le; 3 Bulan</th>
                    <th style="text-align: right;">Terlambat &gt; 3 Bulan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalA = 0;
                  $totalB = 0;
                  if (!empty($groupTagihan)) {
                    foreach ($groupTagihan as $key => $value) {
                      $val_a = (float)($value['kategori_a'] ?? 0);
                      $val_b = (float)($value['kategori_b'] ?? 0);
                      $totalA += $val_a;
                      $totalB += $val_b;
                  ?>
                    <tr>
                      <td style="font-weight: 600;"><?php echo htmlspecialchars($key) ?></td>
                      <td align="right" style="color: #d97706; font-weight: 600;">
                        Rp <?php echo number_format($val_a, 0, ',', '.') ?>
                      </td>
                      <td align="right" style="color: #ef4444; font-weight: 600;">
                        Rp <?php echo number_format($val_b, 0, ',', '.') ?>
                      </td>
                    </tr>
                  <?php 
                    }
                  } else {
                  ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted" style="padding: 20px;">Tidak ada data tagihan tertunda.</td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total</td>
                    <td align="right" style="color: #d97706;">Rp <?php echo number_format($totalA, 0, ',', '.') ?></td>
                    <td align="right" style="color: #ef4444;">Rp <?php echo number_format($totalB, 0, ',', '.') ?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/tagihan_siswa'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Detail Rekap Tagihan</a>
          </div>
        </div>

        <!-- 2. Top 5 Pemasukan per Akun -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-trophy"></i> 5 Pemasukan Terbesar per Akun</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Keterangan / Akun</th>
                    <th style="text-align: center;">Unit</th>
                    <th style="text-align: right;">Jumlah Penerimaan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if (!empty($topfive)) {
                    foreach ($topfive as $row) { 
                  ?>
                    <tr>
                      <td><?php echo htmlspecialchars($row['account_description'] ?? '-') ?></td>
                      <td align="center"><span class="stat-badge badge-subtle-blue"><?php echo htmlspecialchars($row['majors_short_name'] ?? '-') ?></span></td>
                      <td align="right" style="color: #10b981; font-weight: 600;">Rp <?php echo number_format((float)($row['total_deb'] ?? 0), 0, ',', '.') ?></td>
                    </tr>
                  <?php 
                    }
                  } else {
                  ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted" style="padding: 20px;">Belum ada riwayat transaksi penerimaan pada periode ini.</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/pemasukan_per_akun'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Semua Pemasukan per Akun</a>
          </div>
        </div>

        <!-- 3. Status Guru & Pegawai -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-id-badge"></i> Rekap Status Guru & Pegawai</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Kategori Status</th>
                    <th style="text-align: right;">Jumlah Orang</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Guru & Pegawai Tetap</td>
                    <td align="right" style="font-weight: 600; color: #10b981;"><?php echo (int)($total_tetap ?? 0) ?> Orang</td>
                  </tr>
                  <tr>
                    <td>Guru & Pegawai Tidak Tetap / Kontrak</td>
                    <td align="right" style="font-weight: 600; color: #f59e0b;"><?php echo (int)($total_non_tetap ?? 0) ?> Orang</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total Guru & Pegawai Terdaftar</td>
                    <td align="right"><?php echo (int)($total_all ?? 0) ?> Orang</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/total_guru_pegawai'); ?>"><i class="fa fa-arrow-circle-right"></i> Kelola Data Guru & Pegawai</a>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="col-md-6">
        <!-- 4. Jurnal Keuangan -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-balance-scale"></i> Jurnal Keuangan</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Komponen Jurnal</th>
                    <th style="text-align: right;">Bulan Ini</th>
                    <th style="text-align: right;">Tahun Ajaran Ini</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Penerimaan Kas</td>
                    <td align="right" style="color: #10b981; font-weight: 600;">Rp <?php echo number_format((float)($total_debit_month ?? 0), 0, ',', '.') ?></td>
                    <td align="right" style="color: #10b981; font-weight: 600;">Rp <?php echo number_format((float)($total_debit_period ?? 0), 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td>Pengeluaran Kas</td>
                    <td align="right" style="color: #ef4444; font-weight: 600;">Rp <?php echo number_format((float)($total_kredit_month ?? 0), 0, ',', '.') ?></td>
                    <td align="right" style="color: #ef4444; font-weight: 600;">Rp <?php echo number_format((float)($total_kredit_period ?? 0), 0, ',', '.') ?></td>
                  </tr>
                </tbody>
                <tfoot>
                  <?php 
                    $saldo_bulan = (float)($total_debit_month ?? 0) - (float)($total_kredit_month ?? 0);
                    $saldo_tahun = (float)($total_debit_period ?? 0) - (float)($total_kredit_period ?? 0);
                  ?>
                  <tr>
                    <td>Saldo Bersih</td>
                    <td align="right" style="color: <?php echo $saldo_bulan >= 0 ? '#2575FC' : '#ef4444' ?>;">
                      Rp <?php echo number_format($saldo_bulan, 0, ',', '.') ?>
                    </td>
                    <td align="right" style="color: <?php echo $saldo_tahun >= 0 ? '#2575FC' : '#ef4444' ?>;">
                      Rp <?php echo number_format($saldo_tahun, 0, ',', '.') ?>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/jurnal_keuangan'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Laporan Jurnal Lengkap</a>
          </div>
        </div>

        <!-- 5. Laporan KAS Setiap Unit -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-building-o"></i> Laporan KAS Setiap Unit</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Unit Pendidikan</th>
                    <th style="text-align: right;">Bulan Ini</th>
                    <th style="text-align: right;">Tahun Ajaran Ini</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $subKasBulan = 0;
                  $subKasTahun = 0;
                  if (!empty($kasUnit)) {
                    foreach ($kasUnit as $key => $kas) {
                      $val_kas_bulan = (float)($kas['bulan'] ?? 0);
                      $val_kas_tahun = (float)($kas['tahun'] ?? 0);
                      $subKasBulan += $val_kas_bulan;
                      $subKasTahun += $val_kas_tahun;
                  ?>
                    <tr>
                      <td style="font-weight: 600;"><?php echo htmlspecialchars($key) ?></td>
                      <td align="right">Rp <?php echo number_format($val_kas_bulan, 0, ',', '.') ?></td>
                      <td align="right" style="font-weight: 600; color: #2575FC;">Rp <?php echo number_format($val_kas_tahun, 0, ',', '.') ?></td>
                    </tr>
                  <?php 
                    }
                  } else {
                  ?>
                    <tr>
                      <td colspan="3" class="text-center text-muted" style="padding: 20px;">Data kas unit belum tersedia.</td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total Penerimaan Unit</td>
                    <td align="right">Rp <?php echo number_format($subKasBulan, 0, ',', '.') ?></td>
                    <td align="right" style="color: #2575FC;">Rp <?php echo number_format($subKasTahun, 0, ',', '.') ?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/laporan_kas'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Semua Rincian Kas</a>
          </div>
        </div>

        <!-- 6. Jumlah Siswa Setiap Jenjang -->
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-users"></i> Distribusi Siswa per Jenjang</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Jenjang / Unit</th>
                    <th style="text-align: center;">Siswa Aktif</th>
                    <th style="text-align: center;">Total Seluruhnya</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $sumAktif = 0;
                  $sumAll = 0;
                  if (!empty($majors)) {
                    foreach ($majors as $major) { 
                      $studentActive = 0;
                      $studentAll = 0;
                      if (!empty($student)) {
                        foreach ($student as $stud) {
                          if ($major['majors_id'] == $stud['majors_majors_id']) {
                            $studentAll++;
                            if (($stud['student_status'] ?? 0) == 1) {
                              $studentActive++;
                            }
                          }
                        }
                      }
                      $sumAktif += $studentActive;
                      $sumAll += $studentAll;
                  ?>
                    <tr>
                      <td style="font-weight: 600;"><?php echo htmlspecialchars($major['majors_short_name']) ?></td>
                      <td align="center"><span class="stat-badge badge-subtle-green"><?php echo $studentActive ?> Siswa</span></td>
                      <td align="center"><span class="stat-badge badge-subtle-blue"><?php echo $studentAll ?> Siswa</span></td>
                    </tr>
                  <?php 
                    }
                  } 
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>Total Siswa</td>
                    <td align="center"><?php echo $sumAktif ?> Siswa</td>
                    <td align="center"><?php echo $sumAll ?> Siswa</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/statistik/jumlah_siswa'); ?>"><i class="fa fa-arrow-circle-right"></i> Lihat Detail Data Siswa</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Informasi dan Kalender Row -->
    <div class="row">
      <div class="col-md-6">
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-bullhorn"></i> Pengumuman & Informasi</h3>
          </div>
          <div class="modern-box-body" style="padding: 0;">
            <div class="table-responsive">
              <table class="table table-modern">
                <tbody>
                  <?php 
                  if (!empty($information)) {
                    foreach ($information as $info) : 
                  ?>
                    <tr>
                      <td style="width: 70px;">
                        <?php if (!empty($info['information_img'])) { ?>
                          <img src="<?php echo upload_url('information/' . $info['information_img']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        <?php } else { ?>
                          <div style="width: 50px; height: 50px; background: #eff6ff; color: #2575FC; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-info-circle"></i>
                          </div>
                        <?php } ?>
                      </td>
                      <td>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;"><?php echo htmlspecialchars($info['information_title']) ?></div>
                        <small style="color: #94a3b8;"><i class="fa fa-clock-o"></i> <?php echo pretty_date($info['information_input_date'] ?? date('Y-m-d'), 'd F Y', false) ?></small>
                      </td>
                    </tr>
                  <?php 
                    endforeach; 
                  } else {
                  ?>
                    <tr>
                      <td colspan="2" class="text-center text-muted" style="padding: 24px;">Belum ada pengumuman yang dipublikasikan.</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modern-box-footer">
            <a href="<?php echo site_url('manage/information'); ?>"><i class="fa fa-arrow-circle-right"></i> Kelola Informasi</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="modern-box">
          <div class="modern-box-header">
            <h3 class="modern-box-title"><i class="fa fa-calendar"></i> Kalender Agenda & Hari Libur</h3>
          </div>
          <div class="modern-box-body">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

<!-- Modal Tambah Agenda -->
<div class="modal fade in" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <?php echo form_open(current_url()); ?>
    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header" style="background: #2575FC; color: #ffffff;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addModalLabel" style="font-weight: 600;"><i class="fa fa-plus-circle"></i> Tambah Agenda / Hari Libur</h4>
      </div>
      <div class="modal-body" style="padding: 20px;">
        <input type="hidden" name="add" value="1">
        <div class="form-group">
          <label style="font-weight: 600; color: #475569;">Tanggal Terpilih</label>
          <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-weight: 700; color: #2575FC;" id="labelDate">-</div>
          <input type="hidden" name="date" class="form-control" id="inputDate">
        </div>
        <div class="form-group">
          <label style="font-weight: 600; color: #475569;">Keterangan Agenda*</label>
          <textarea name="info" id="inputDesc" class="form-control" rows="3" placeholder="Masukkan judul agenda atau keterangan libur..." style="border-radius: 8px;"></textarea>
        </div>
      </div>
      <div class="modal-footer" style="background: #f8fafc;">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
        <button type="submit" id="btnSimpan" class="btn btn-primary disabled" style="background: #2575FC; border-color: #2575FC; border-radius: 8px;">Simpan Agenda</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- Modal Hapus Agenda -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <?php echo form_open(current_url()); ?>
    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header" style="background: #ef4444; color: #ffffff;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delModalLabel" style="font-weight: 600;"><i class="fa fa-trash"></i> Hapus Agenda</h4>
      </div>
      <div class="modal-body" style="padding: 20px;">
        <input type="hidden" name="del" value="1">
        <input type="hidden" name="id" id="idDel">
        <p style="color: #64748b; margin-bottom: 15px;">Apakah Anda yakin ingin menghapus agenda kegiatan berikut?</p>
        <div class="well well-sm" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
          <div style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;">Tanggal: <span id="showDate" style="color: #1e293b; font-weight: 600;"></span></div>
          <div style="font-size: 14px; color: #1e293b; font-weight: 700;" id="showDesc">-</div>
        </div>
      </div>
      <div class="modal-footer" style="background: #f8fafc;">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
        <button type="submit" class="btn btn-danger" style="background: #ef4444; border-color: #ef4444; border-radius: 8px;">Hapus</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
  function renderCalendarIcons() {
    $('.fc-button-prev').html('<i class="fa fa-chevron-left"></i>');
    $('.fc-button-next').html('<i class="fa fa-chevron-right"></i>');
    $('.fc-button-prevYear').html('<i class="fa fa-angle-double-left"></i>');
    $('.fc-button-nextYear').html('<i class="fa fa-angle-double-right"></i>');
    $('.fc-button-today').text('Hari Ini');
  }

  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'prevYear,nextYear',
      },
      events: "<?php echo site_url('manage/summary/get'); ?>",
      viewDisplay: function(view) {
        renderCalendarIcons();
      },
      dayClick: function(date, jsEvent, view) {
        var tanggal = date.getDate();
        var bulan = date.getMonth() + 1;
        var tahun = date.getFullYear();
        var fullDate = tahun + '-' + (bulan < 10 ? '0' + bulan : bulan) + '-' + (tanggal < 10 ? '0' + tanggal : tanggal);

        $('#addModal').modal('show');
        $("#inputDate").val(fullDate);
        $("#labelDate").text(fullDate);
      },
      eventClick: function(calEvent, jsEvent, view) {
        $("#delModal").modal('show');
        $("#idDel").val(calEvent.id);
        var tgl = calEvent.start.getDate();
        var bln = calEvent.start.getMonth() + 1;
        var thn = calEvent.start.getFullYear();
        $("#showDate").text((tgl < 10 ? '0' + tgl : tgl) + '/' + (bln < 10 ? '0' + bln : bln) + '/' + thn);
        $("#showDesc").text(calEvent.title);
      }
    });

    renderCalendarIcons();

    $("#inputDesc").on('change keyup focus input propertychange', function() {
      var desc = $("#inputDesc").val();
      if (desc && desc.trim().length > 0) {
        $("#btnSimpan").removeClass('disabled');
      } else {
        $("#btnSimpan").addClass('disabled');
      }
    });
  });
</script>