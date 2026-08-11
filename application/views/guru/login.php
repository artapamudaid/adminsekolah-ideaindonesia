<!DOCTYPE html>
<html lang="en">

<?php
$this->load->model(['setting/Setting_model']);
$setting_name = $this->Setting_model->get(['id' => 1]);
$setting_package = $this->Setting_model->get(['id' => 97]);
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Sekolah | Guru</title>
    <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo media_url() ?>auth/auth.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="page">

        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="carousel-wrap" id="dCarousel">
                <div class="carousel-track" id="dTrack">
                    <?php if (!empty($pengumuman)): ?>
                        <?php foreach ($pengumuman as $item): ?>
                            <div class="carousel-slide">
                                <img src="<?= $item['information_img'] ?>" alt="Pengumuman">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-slide" style="display:flex;align-items:center;justify-content:center;padding:3rem;">
                            <img src="<?php echo media_url('img/logo.svg') ?>" style="width:50%" alt="Logo">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="carousel-dots" id="dDots"></div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="form-card">

                <!-- Mobile carousel -->
                <div class="mobile-carousel-wrapper">
                    <div class="mobile-carousel">
                        <div class="carousel-track" id="mTrack">
                            <?php if (!empty($pengumuman)): ?>
                                <?php foreach ($pengumuman as $item): ?>
                                    <div class="carousel-slide">
                                        <img src="<?= $item['information_img'] ?>" alt="Pengumuman">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-slide" style="display:flex;align-items:center;justify-content:center;padding:2rem;">
                                    <img src="<?php echo media_url('img/logo.svg') ?>" style="width:50%" alt="Logo">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-wrapper">
                    <div class="form-header">
                        <img
                            class="brand-logo"
                            src="<?php echo media_url('img/as-landscape.png') ?>"
                            alt="logo" />
                        <div class="form-eyebrow">Selamat Datang</div>
                        <div class="form-title"><?= $setting_name['setting_value'] ?? 'AdminSekolah' ?></div>
                        <div class="form-subtitle">Masuk sebagai Guru / Pengajar</div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="form-tabs">
                        <a href="<?= base_url('manage/auth/login') ?>" class="tab-link">Login</a>
                        <?php if ($is_rfid_subscription): ?>
                            <a href="<?= base_url('student_presence') ?>" class="tab-link">Presensi Siswa</a>
                        <?php endif; ?>
                        <?php if ($is_rfid_subscription_employee): ?>
                            <a href="<?= base_url('employee_presence') ?>" class="tab-link">Presensi Pegawai</a>
                        <?php endif; ?>
                        <?php if ($setting_package && $setting_package['setting_value'] == '3'): ?>
                            <a href="<?= base_url('guru') ?>" class="tab-link active">Guru</a>
                        <?php endif; ?>
                        <!-- <a href="<?= base_url('manage/auth/login') ?>" class="tab-link">Login</a>
                        <a href="<?= base_url('student_presence') ?>" class="tab-link">Presensi Siswa</a>
                        <a href="<?= base_url('employee_presence') ?>" class="tab-link">Presensi Pegawai</a>
                        <a href="<?= base_url('guru') ?>" class="tab-link active">Guru</a> -->
                    </div>

                    <!-- Flash messages -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?= form_open('guru/auth/login') ?>

                    <div class="input-group">
                        <label class="input-label">NIP</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <input type="text" name="nip" placeholder="Masukkan NIP" required autofocus>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                            <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Toggle password">
                                <svg id="pw-eye" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Masuk sebagai Guru</button>

                    <?= form_close() ?>
                </div>

                <div class="download-row">
                    <div class="download-label">TERSEDIA JUGA APLIKASI MOBILE</div>
                    <div class="download-btns">
                        <a href="https://play.google.com/store/apps/dev?id=6395890906220495321" target="_blank" class="download-btn">
                            <i class="bi bi-google-play"></i>
                            <div>
                                <div class="store-name">Dapatkan di</div>
                                <div class="store-big">Android</div>
                            </div>
                        </a>
                        <a href="https://apps.apple.com/id/developer/indoweb-id/id1807922292" target="_blank" class="download-btn">
                            <i class="bi bi-apple"></i>
                            <div>
                                <div class="store-name">Dapatkan di</div>
                                <div class="store-big">iOS</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById("password");
            const eye = document.getElementById("pw-eye");
            const isHidden = pw.type === "password";
            pw.type = isHidden ? "text" : "password";
            eye.innerHTML = isHidden ?
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>` :
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        }

        const slideCount = <?= !empty($pengumuman) ? count($pengumuman) : 1 ?>;
        let dIdx = 0;

        function buildDots(count) {
            const c = document.getElementById('dDots');
            if (!c) return;
            for (let i = 0; i < count; i++) {
                const d = document.createElement('div');
                d.className = 'dot' + (i === 0 ? ' active' : '');
                d.onclick = () => goSlide(i);
                c.appendChild(d);
            }
        }

        function goSlide(idx) {
            document.getElementById('dTrack').style.transform = `translateX(-${idx * 100}%)`;
            dIdx = idx;
            document.querySelectorAll('#dDots .dot').forEach((d, i) => d.classList.toggle('active', i === idx));
        }

        buildDots(slideCount);
        setInterval(() => goSlide((dIdx + 1) % slideCount), 4000);
    </script>
</body>

</html>