<?php
$guru_name = $this->session->userdata('ufullname_guru');
$guru_nip  = $this->session->userdata('unip_guru');
$is_wali   = ($this->session->userdata('uiswali_guru') == 1);
$hari_ini  = pretty_date(date('Y-m-d'), 'l, d F Y', false);

$presensi_map = array();
if (!empty($presensi_guru)) {
    foreach ($presensi_guru as $ps) {
        $presensi_map[$ps['presensi_guru_schedule_id']] = $ps['presensi_guru_attend'];
    }
}

$total_jadwal = !empty($jadwal) ? count($jadwal) : 0;
$hadir_count  = 0;
if (!empty($jadwal)) {
    foreach ($jadwal as $jd) {
        if (isset($presensi_map[$jd['schedule_id']]) && $presensi_map[$jd['schedule_id']] == '1') {
            $hadir_count++;
        }
    }
}
?>

<div class="content-wrapper guru-dashboard-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <h1>
            Dashboard Guru
            <small style="color: #64748B; font-size: 13px;">Panel Informasi & Jadwal Mengajar</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('guru'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main Content -->
    <section class="content">

        <!-- 1. Hero Greeting Banner (Spark Admin Orange Aesthetic) -->
        <div class="guru-hero-banner">
            <div class="guru-hero-content">
                <div class="guru-hero-badge">
                    <span class="hero-badge-dot"></span>
                    <span>Tahun Ajaran Aktif</span>
                </div>
                <h2 class="guru-hero-title">
                    Selamat Datang, <strong><?php echo ucfirst($guru_name); ?></strong> 👋
                </h2>
                <p class="guru-hero-desc">
                    Hari ini <strong><?php echo $hari_ini; ?></strong>. Anda memiliki <strong><?php echo $total_jadwal; ?> kelas mengajar</strong> yang terjadwal.
                </p>
                <div class="guru-hero-tags">
                    <span class="hero-tag-item"> NIP: <?php echo !empty($guru_nip) ? $guru_nip : '-'; ?></span>
                    <span class="hero-tag-item"> <?php echo $is_wali ? 'Wali Kelas & Guru Pengajar' : 'Guru Pengajar'; ?></span>
                </div>
            </div>

            <div class="guru-hero-widget">
                <div class="guru-clock-box">
                    <div class="clock-label"><i class="fa fa-clock-o"></i> Waktu Sekarang</div>
                    <div class="clock-time" id="currentTime"><?php echo date('H:i'); ?></div>
                    <div class="clock-status">
                        <span class="status-indicator"></span>
                        <span>Sesi: <strong><?php echo (strpos($jam_ke, 'Tidak') !== false) ? $jam_ke : 'Jam Ke-' . $jam_ke; ?></strong></span>
                    </div>
                </div>
            </div>

            <!-- Background decorative art -->
            <div class="guru-hero-bg-icon">
                <i class="fa fa-graduation-cap"></i>
            </div>
        </div>

        <!-- 2. Metric KPI Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="guru-stat-card card-orange">
                    <div class="stat-icon-wrapper bg-soft-orange text-orange">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Sesi Pelajaran</span>
                        <h3 class="stat-value"><?php echo (strpos($jam_ke, 'Tidak') !== false) ? 'Istirahat' : 'Jam Ke-' . $jam_ke; ?></h3>
                        <span class="stat-subtext"><i class="fa fa-circle text-success" style="font-size: 8px;"></i> Sesi KBM Berlangsung</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="guru-stat-card card-blue">
                    <div class="stat-icon-wrapper bg-soft-blue text-blue">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Jadwal Mengajar</span>
                        <h3 class="stat-value"><?php echo $total_jadwal; ?> <small style="font-size: 13px; font-weight: 600; color: #64748B;">Kelas</small></h3>
                        <span class="stat-subtext">Hari <?php echo pretty_date(date('Y-m-d'), 'l', false); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="guru-stat-card card-green">
                    <div class="stat-icon-wrapper bg-soft-green text-green">
                        <i class="fa fa-check-square-o"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Presensi Mengajar</span>
                        <h3 class="stat-value"><?php echo $hadir_count; ?> / <?php echo $total_jadwal; ?> <small style="font-size: 13px; font-weight: 600; color: #64748B;">Hadir</small></h3>
                        <span class="stat-subtext"><?php echo ($total_jadwal > 0 && $hadir_count == $total_jadwal) ? '<span class="text-green" style="font-weight: 600;"><i class="fa fa-check"></i> Selesai Semua</span>' : 'Progres kehadiran hari ini'; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="guru-stat-card card-purple">
                    <div class="stat-icon-wrapper bg-soft-purple text-purple">
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">E-Jurnal Mengajar</span>
                        <h3 class="stat-value" style="font-size: 18px;">Catat KBM</h3>
                        <a href="<?php echo site_url('guru/jurnal'); ?>" class="stat-link">Buka Jurnal <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Quick Action Hub -->
        <div class="guru-quick-actions">
            <div class="actions-header">
                <div>
                    <h4 class="actions-title"><i class="fa fa-bolt" style="color: #EA580C;"></i> Menu Cepat Pengajar</h4>
                    <span class="actions-subtitle">Akses instan modul pengajaran dan administrasi</span>
                </div>
            </div>
            <div class="actions-grid">
                <a href="<?php echo site_url('guru/jurnal'); ?>" class="action-tile">
                    <div class="tile-icon bg-soft-orange text-orange"><i class="fa fa-file-text-o"></i></div>
                    <div class="tile-details">
                        <span class="tile-title">Jurnal Mengajar</span>
                        <span class="tile-desc">Catat materi & agenda KBM</span>
                    </div>
                </a>

                <a href="<?php echo site_url('guru/nilai'); ?>" class="action-tile">
                    <div class="tile-icon bg-soft-blue text-blue"><i class="fa fa-pencil-square-o"></i></div>
                    <div class="tile-details">
                        <span class="tile-title">Penilaian Siswa</span>
                        <span class="tile-desc">Input nilai harian & sumatif</span>
                    </div>
                </a>

                <a href="<?php echo site_url('guru/report_jurnal'); ?>" class="action-tile">
                    <div class="tile-icon bg-soft-purple text-purple"><i class="fa fa-bar-chart"></i></div>
                    <div class="tile-details">
                        <span class="tile-title">Laporan KBM</span>
                        <span class="tile-desc">Rekapitulasi jurnal & nilai</span>
                    </div>
                </a>

                <a href="<?php echo site_url('guru/izin_guru'); ?>" class="action-tile">
                    <div class="tile-icon bg-soft-rose text-rose"><i class="fa fa-envelope-o"></i></div>
                    <div class="tile-details">
                        <span class="tile-title">Pengajuan Izin</span>
                        <span class="tile-desc">Form izin berhalangan KBM</span>
                    </div>
                </a>

                <?php if ($is_wali): ?>
                <a href="<?php echo site_url('guru/presensi_guru'); ?>" class="action-tile">
                    <div class="tile-icon bg-soft-teal text-teal"><i class="fa fa-check-square"></i></div>
                    <div class="tile-details">
                        <span class="tile-title">Presensi Kelas</span>
                        <span class="tile-desc">Presensi santri wali kelas</span>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. Schedule Table Card -->
        <div class="guru-schedule-card">
            <div class="schedule-card-header">
                <div class="header-title-group">
                    <div class="icon-circle">
                        <i class="fa fa-calendar" style="color: #EA580C;"></i>
                    </div>
                    <div>
                        <h3 class="schedule-title">Jadwal Mengajar Hari Ini</h3>
                        <p class="schedule-subtitle">Daftar kelas dan mata pelajaran yang Anda ampu hari <?php echo $hari_ini; ?></p>
                    </div>
                </div>
                <div class="header-badges">
                    <span class="badge-total-schedule"><i class="fa fa-list-ul"></i> Total <?php echo $total_jadwal; ?> Jadwal</span>
                </div>
            </div>

            <div class="schedule-card-body">
                <?php if (!empty($jadwal)): ?>
                    <div class="table-responsive">
                        <table class="table guru-modern-table">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Jam Pelajaran</th>
                                    <th style="width: 130px;">Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th style="width: 180px; text-align: center;">Status Kehadiran</th>
                                    <th style="width: 130px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jadwal as $jd): 
                                    $sched_id = $jd['schedule_id'];
                                    $attend_status = isset($presensi_map[$sched_id]) ? $presensi_map[$sched_id] : null;
                                ?>
                                    <tr>
                                        <td>
                                            <span class="pill-badge pill-jampel">
                                                <i class="fa fa-clock-o"></i> <?php echo html_escape($jd['jam_pelajaran_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="pill-badge pill-class">
                                                <i class="fa fa-building-o"></i> <?php echo html_escape($jd['class_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="lesson-info">
                                                <span class="lesson-icon"><i class="fa fa-book"></i></span>
                                                <span class="lesson-name"><?php echo html_escape($jd['lesson_name']); ?></span>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($attend_status === '1'): ?>
                                                <span class="status-pill status-hadir"><i class="fa fa-check-circle"></i> Hadir</span>
                                            <?php elseif ($attend_status === '0'): ?>
                                                <span class="status-pill status-absen"><i class="fa fa-times-circle"></i> Tidak Hadir</span>
                                            <?php elseif ($attend_status === '2'): ?>
                                                <span class="status-pill status-izin"><i class="fa fa-info-circle"></i> Izin / Sakit</span>
                                            <?php else: ?>
                                                <span class="status-pill status-pending"><i class="fa fa-hourglass-start"></i> Belum Ada Data</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="<?php echo site_url('guru/jurnal'); ?>" class="btn-action-jurnal" title="Isi Jurnal Mengajar">
                                                <i class="fa fa-pencil"></i> Jurnal
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="guru-empty-schedule">
                        <div class="empty-icon-wrapper">
                            <i class="fa fa-coffee"></i>
                        </div>
                        <h4>Tidak Ada Jadwal Mengajar Hari Ini</h4>
                        <p>Anda tidak memiliki agenda mengajar pada hari <?php echo pretty_date(date('Y-m-d'), 'l', false); ?>. Selamat mempersiapkan materi atau mengecek administrasi penilaian.</p>
                        <a href="<?php echo site_url('guru/jurnal'); ?>" class="btn btn-primary" style="margin-top: 8px;">
                            <i class="fa fa-book"></i> Buka Menu Jurnal
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </section>
</div>

<style>
/* ==========================================================================
   Guru Dashboard Modern Theme (Spark Admin Orange Style)
   ========================================================================== */
.guru-dashboard-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

/* 1. Hero Greeting Banner */
.guru-hero-banner {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #FF7A18 0%, #EA580C 100%);
    border-radius: 16px;
    padding: 26px 30px;
    color: #FFFFFF;
    box-shadow: 0 10px 25px -5px rgba(234, 88, 12, 0.35), 0 4px 10px rgba(234, 88, 12, 0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.guru-hero-content {
    position: relative;
    z-index: 2;
    flex: 1 1 340px;
}

.guru-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    color: #FFFFFF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}

.hero-badge-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #4ADE80;
    box-shadow: 0 0 8px rgba(74, 222, 128, 0.9);
}

.guru-hero-title {
    font-size: 24px;
    font-weight: 800;
    color: #FFFFFF !important;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
    line-height: 1.25;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.guru-hero-desc {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.95);
    margin: 0 0 14px 0;
    line-height: 1.5;
}

.guru-hero-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.hero-tag-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: rgba(0, 0, 0, 0.18);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: #FFFFFF;
}

.guru-hero-widget {
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}

.guru-clock-box {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 14px;
    padding: 18px 24px;
    text-align: center;
    color: #FFFFFF;
    min-width: 200px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.clock-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
    margin-bottom: 4px;
}

.clock-time {
    font-size: 36px;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.1;
    margin-bottom: 6px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.clock-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    background: rgba(0, 0, 0, 0.2);
    padding: 3px 10px;
    border-radius: 12px;
}

.status-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ADE80;
}

.guru-hero-bg-icon {
    position: absolute;
    right: -10px;
    bottom: -30px;
    font-size: 130px;
    color: rgba(255, 255, 255, 0.12);
    pointer-events: none;
    z-index: 1;
    transform: rotate(-10deg);
    line-height: 1;
}

/* 2. Stat / KPI Cards */
.guru-stat-card {
    background: #FFFFFF;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    border: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.guru-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    border-color: #E2E8F0;
}

.stat-icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.stat-info {
    flex: 1;
    min-width: 0;
}

.stat-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 2px;
}

.stat-value {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 2px 0;
    letter-spacing: -0.015em;
    line-height: 1.2;
}

.stat-subtext {
    font-size: 11.5px;
    color: #94A3B8;
    display: block;
}

.stat-link {
    font-size: 12px;
    font-weight: 700;
    color: #EA580C;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 3px;
    transition: all 0.2s ease;
}

.stat-link:hover {
    color: #C2410C;
    transform: translateX(2px);
}

/* Soft color palette */
.bg-soft-orange { background: #FFF7ED; }
.text-orange { color: #EA580C; }

.bg-soft-blue { background: #EFF6FF; }
.text-blue { color: #2563EB; }

.bg-soft-green { background: #F0FDF4; }
.text-green { color: #16A34A; }

.bg-soft-purple { background: #FAF5FF; }
.text-purple { color: #9333EA; }

.bg-soft-rose { background: #FFF1F2; }
.text-rose { color: #E11D48; }

.bg-soft-teal { background: #F0FDFA; }
.text-teal { color: #0D9488; }

/* 3. Quick Action Hub */
.guru-quick-actions {
    background: #FFFFFF;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    border: 1px solid #F1F5F9;
    margin-bottom: 24px;
}

.actions-header {
    margin-bottom: 16px;
}

.actions-title {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 4px 0;
    letter-spacing: -0.01em;
}

.actions-subtitle {
    font-size: 12.5px;
    color: #64748B;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 14px;
}

.action-tile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px 16px;
    border-radius: 12px;
    background: #F8FAFC;
    border: 1px solid #F1F5F9;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.action-tile:hover {
    background: #FFFFFF;
    border-color: #EA580C;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(234, 88, 12, 0.12);
}

.tile-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.tile-details {
    min-width: 0;
}

.tile-title {
    display: block;
    font-size: 13.5px;
    font-weight: 700;
    color: #1E293B;
    line-height: 1.25;
}

.tile-desc {
    display: block;
    font-size: 11px;
    color: #64748B;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* 4. Schedule Table Card */
.guru-schedule-card {
    background: #FFFFFF;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    border: 1px solid #F1F5F9;
    overflow: hidden;
    margin-bottom: 30px;
}

.schedule-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
}

.header-title-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #FFF7ED;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.schedule-title {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.01em;
}

.schedule-subtitle {
    font-size: 12.5px;
    color: #64748B;
    margin: 2px 0 0 0;
}

.badge-total-schedule {
    background: #FFF7ED;
    color: #EA580C;
    border: 1px solid #FFEDD5;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.schedule-card-body {
    padding: 0;
}

.guru-modern-table {
    margin-bottom: 0;
    border: none;
}

.guru-modern-table > thead > tr > th {
    background: #F8FAFC;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 14px 20px;
    border-top: none;
    border-bottom: 2px solid #E2E8F0;
    vertical-align: middle;
}

.guru-modern-table > tbody > tr > td {
    padding: 14px 20px;
    vertical-align: middle;
    border-top: 1px solid #F1F5F9;
    color: #334155;
    font-size: 13.5px;
}

.guru-modern-table > tbody > tr:hover {
    background-color: #FAFBFD;
}

/* Pills & Badges */
.pill-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 700;
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 6px;
}

.pill-jampel {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #DBEAFE;
}

.pill-class {
    background: #FEF3C7;
    color: #B45309;
    border: 1px solid #FDE68A;
}

.lesson-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.lesson-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #F1F5F9;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

.lesson-name {
    font-weight: 700;
    color: #1E293B;
}

/* Status Pills */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
}

.status-hadir {
    background: #DCFCE7;
    color: #15803D;
    border: 1px solid #BBF7D0;
}

.status-absen {
    background: #FEE2E2;
    color: #B91C1C;
    border: 1px solid #FECACA;
}

.status-izin {
    background: #FEF9C3;
    color: #A16207;
    border: 1px solid #FEF08A;
}

.status-pending {
    background: #F1F5F9;
    color: #64748B;
    border: 1px solid #E2E8F0;
}

/* Action button */
.btn-action-jurnal {
    background: #EA580C;
    color: #FFFFFF !important;
    font-weight: 700;
    font-size: 12px;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: none;
    box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);
}

.btn-action-jurnal:hover {
    background: #C2410C;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(234, 88, 12, 0.35);
}

/* Empty Schedule State */
.guru-empty-schedule {
    text-align: center;
    padding: 48px 24px;
}

.empty-icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #FFF7ED;
    color: #EA580C;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 16px;
}

.guru-empty-schedule h4 {
    font-size: 17px;
    font-weight: 700;
    color: #1E293B;
    margin: 0 0 6px 0;
}

.guru-empty-schedule p {
    font-size: 13.5px;
    color: #64748B;
    max-width: 440px;
    margin: 0 auto 16px;
    line-height: 1.5;
}
</style>

<script>
    function showTime() {
        var currentTime = document.getElementById("currentTime");
        if (!currentTime) return;
        var date = new Date();
        var hour = date.getHours();
        var min = date.getMinutes();
        hour = (hour < 10 ? "0" : "") + hour;
        min = (min < 10 ? "0" : "") + min;
        currentTime.innerText = hour + ":" + min;
    }
    setInterval(showTime, 10000);
</script>