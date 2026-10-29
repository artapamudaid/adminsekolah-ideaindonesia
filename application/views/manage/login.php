<!DOCTYPE html>
<html lang="en">

<?php
$this->load->model(['setting/Setting_model']);
$setting_name = $this->Setting_model->get(['id' => 1]);
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AdminSekolah | Login</title>
    <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo media_url() ?>auth/auth.css?v=<?php echo file_exists(FCPATH . 'media/auth/auth.css') ? filemtime(FCPATH . 'media/auth/auth.css') : '1.0'; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <?php if (ENVIRONMENT === 'production' && !empty($turnstile_site_key)): ?>
    <link rel="preconnect" href="https://challenges.cloudflare.com">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
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
                        <?php
                        $this->load->model(['setting/Setting_model']);
                        $setting_logo = $this->Setting_model->get(['id' => 6]);
                        ?>
                        <div class="carousel-slide" style="display:flex;align-items:center;justify-content:center;padding:3rem;">
                            <?php if (isset($setting_logo) && $setting_logo['setting_value'] != NULL): ?>
                                <img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" style="width:60%;" alt="Logo">
                            <?php else: ?>
                                <img src="<?php echo media_url('img/logo.svg') ?>" style="width:50%;" alt="Logo">
                            <?php endif; ?>
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
                            src="<?php echo media_url('img/idea-landscape.png') ?>"
                            alt="logo" />
                        <div class="form-eyebrow">Selamat Datang</div>
                        <div class="form-title"><?= $setting_name['setting_value'] ?? 'AdminSekolah' ?></div>
                        <div class="form-subtitle">Masuk untuk mengelola sekolah Anda</div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="form-tabs">
                        <a href="<?= base_url() ?>" class="tab-link active">Login</a>
                        <a href="<?= base_url('student_presence') ?>" class="tab-link">Presensi Siswa</a>
                        <a href="<?= base_url('employee_presence') ?>" class="tab-link">Presensi Pegawai</a>
                        <a href="<?= base_url('guru') ?>" class="tab-link">Guru</a>
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

                    <?= form_open('manage/auth/login') ?>

                    <div class="input-group">
                        <label class="input-label">Alamat Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="2" y="4" width="20" height="16" rx="3" />
                                    <path d="m2 7 10 7 10-7" />
                                </svg>
                            </span>
                            <input type="email" name="email" placeholder="Masukkan email" required>
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

                    <?php if (ENVIRONMENT === 'production' && !empty($turnstile_site_key)): ?>
                    <div class="turnstile-wrap">
                        <div class="cf-turnstile"
                            data-sitekey="<?= $turnstile_site_key ?>"
                            data-theme="light"
                            data-size="normal"
                            data-callback="onSuccess"></div>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-submit">Masuk ke Dashboard</button>

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
        let dIdx = 0,
            mIdx = 0;

        function buildDots(containerId, count) {
            const c = document.getElementById(containerId);
            if (!c) return;
            for (let i = 0; i < count; i++) {
                const d = document.createElement('div');
                d.className = 'dot' + (i === 0 ? ' active' : '');
                d.onclick = () => goSlide(i, 'd', containerId);
                c.appendChild(d);
            }
        }

        function goSlide(idx, which, dotsId) {
            const track = document.getElementById(which + 'Track');
            if (!track) return;
            if (which === 'd') dIdx = idx;
            else mIdx = idx;
            track.style.transform = `translateX(-${idx * 100}%)`;
            document.querySelectorAll('#' + dotsId + ' .dot')
                .forEach((d, i) => d.classList.toggle('active', i === idx));
        }

        buildDots('dDots', slideCount);
        setInterval(() => goSlide((dIdx + 1) % slideCount, 'd', 'dDots'), 4000);
    </script>
</body>

</html>