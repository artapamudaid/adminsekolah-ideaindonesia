<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminSekolah | Daftar</title>
    <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo media_url() ?>auth/auth.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://challenges.cloudflare.com">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        .left-panel {
            position: sticky;
            top: 50%;
            transform: translateY(-50%);
            height: fit-content;
        }

        @media (min-width: 861px) {
            .right-panel {
                width: 55%;
                padding-left: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="page">

        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div style="text-align:center; max-width: 460px;">
                <img src="https://adminsekolah.net/wp-content/uploads/2023/07/logo-adminsekolah-baru.png"
                    alt="AdminSekolah"
                    style="width: 70%; margin-bottom: 2rem; display: block; margin-left: auto; margin-right: auto;">
                <div style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.7; padding: 0 1rem;">
                    Kelola sekolah Anda dengan mudah — absensi siswa, absensi pegawai, nilai, dan laporan dalam satu platform terpadu.
                </div>
                <div style="display:flex; gap:1rem; margin-top:2rem; justify-content:center; flex-wrap:wrap;">
                    <div style="background:var(--white); border:1px solid var(--glass-border); border-radius:12px; padding:1rem 1.25rem; text-align:center; min-width:100px;">
                        <div style="font-size:1rem; font-weight:800; color:var(--primary-bright)"><i class="bi bi-check-circle" style="margin-right: 6px;"></i>Terintegrasi</div>
                    </div>
                    <div style="background:var(--white); border:1px solid var(--glass-border); border-radius:12px; padding:1rem 1.25rem; text-align:center; min-width:100px;">
                        <div style="font-size:1rem; font-weight:800; color:var(--primary-bright)"><i class="bi bi-check-circle" style="margin-right: 6px;"></i>Tersentral</div>
                    </div>
                    <div style="background:var(--white); border:1px solid var(--glass-border); border-radius:12px; padding:1rem 1.25rem; text-align:center; min-width:100px;">
                        <div style="font-size:1rem; font-weight:800; color:var(--primary-bright)"><i class="bi bi-check-circle" style="margin-right: 6px;"></i>Satu Platform</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel" style="padding-top: 2.5rem; padding-bottom: 2.5rem;">
            <div class="form-card form-card-wide">

                <div class="form-wrapper">
                    <div class="form-header">
                        <div class="form-eyebrow">Mulai Gratis</div>
                        <div class="form-title">Daftar Akun Demo</div>
                        <div class="form-subtitle">Username &amp; password akan dikirim via Email / WhatsApp</div>
                    </div>

                    <!-- Server-side flash fallback -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= html_escape($this->session->flashdata('error')) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <?= html_escape($this->session->flashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Registration Form -->
                    <?php echo form_open_multipart(current_url()); ?>

                    <div class="input-group field-gap">
                        <label class="input-label">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <input type="text" name="registrasi_name" placeholder="Nama lengkap Anda" required>
                        </div>
                    </div>

                    <div class="input-group field-gap">
                        <label class="input-label">Nama Sekolah / Yayasan</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                            </span>
                            <input type="text" name="registrasi_school" placeholder="Nama sekolah / yayasan" required>
                        </div>
                    </div>

                    <div class="input-group field-gap">
                        <label class="input-label">Provinsi</label>
                        <select name="registrasi_province" id="registrasi_province" onchange="get_city()" required>
                            <option value="">-- Pilih Provinsi --</option>
                            <?php foreach ($province as $row): ?>
                                <option value="<?php echo (int) $row['province_id'] ?>"><?php echo html_escape($row['province_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group field-gap" id="div_city">
                        <label class="input-label">Kota / Kabupaten</label>
                        <select name="registrasi_city" id="registrasi_city" required>
                            <option value="">-- Pilih Kota/Kabupaten --</option>
                        </select>
                    </div>

                    <div class="input-group field-gap">
                        <label class="input-label">Tahu AdminSekolah Dari?</label>
                        <select name="registrasi_sumber" id="registrasi_sumber" required>
                            <option value="" <?php echo ($medium == '') ? 'selected' : '' ?>>-- Pilih Sumber --</option>
                            <option value="Website" <?php echo ($medium == 'website_header')   ? 'selected' : '' ?>>Website</option>
                            <option value="CS AdminSekolah" <?php echo ($medium == 'cs_header')        ? 'selected' : '' ?>>CS AdminSekolah</option>
                            <option value="Whatsapp" <?php echo ($medium == 'whatsapp_header')  ? 'selected' : '' ?>>Whatsapp</option>
                            <option value="Instagram" <?php echo ($medium == 'instagram_header') ? 'selected' : '' ?>>Instagram</option>
                            <option value="Facebook" <?php echo ($medium == 'facebook_header')  ? 'selected' : '' ?>>Facebook</option>
                            <option value="Tiktok" <?php echo ($medium == 'tiktok_header')    ? 'selected' : '' ?>>Tiktok</option>
                            <option value="Teman/Sekolah Lain" <?php echo ($medium == 'teman_header')     ? 'selected' : '' ?>>Teman / Sekolah Lain</option>
                        </select>
                    </div>

                    <div class="input-group field-gap">
                        <label class="input-label">Alamat</label>
                        <textarea name="registrasi_address" placeholder="Alamat lengkap sekolah"></textarea>
                    </div>

                    <div class="input-group field-gap">
                        <label class="input-label">Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="2" y="4" width="20" height="16" rx="3" />
                                    <path d="m2 7 10 7 10-7" />
                                </svg>
                            </span>
                            <input type="email" name="registrasi_email" placeholder="email@sekolah.com" required>
                        </div>
                    </div>

                    <div class="input-group field-gap">
                        <label class="input-label">Nomor Telepon</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.24 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 5.49 5.49l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                            </span>
                            <input type="number" name="registrasi_phone" placeholder="08xxxxxxxxxx" maxlength="14" required>
                        </div>
                    </div>

                    <?php if (ENVIRONMENT == 'production'): ?>
                        <div class="turnstile-wrap" style="margin-bottom:0.9rem;">
                            <div class="cf-turnstile"
                                data-sitekey="0x4AAAAAACrlalzyG7pflMkD"
                                data-theme="light"
                                data-size="normal"
                                data-callback="onSuccess"></div>
                        </div>
                    <?php endif; ?>

                    <p class="form-note">
                        <i class="bi bi-whatsapp" style="color:var(--primary-bright)"></i>
                        Username &amp; password akan dikirim ke Email / WhatsApp Anda setelah pendaftaran
                    </p>

                    <button id="submit" type="submit" class="btn-submit">Daftar Sekarang</button>

                    <div id="pesan" style="display:none; margin-top:0.75rem;">
                        <div class="alert alert-success">
                            <i class="bi bi-hourglass-split"></i>
                            Username &amp; password sedang dikirim ke Email / WhatsApp Anda...
                        </div>
                    </div>

                    <div class="register-row">
                        Sudah punya akun?
                        <a href="<?= base_url() ?>">Masuk disini</a>
                    </div>

                    </form>
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

    <!-- Floating Bubbles -->
    <div class="bubble-container">
        <a href="https://tutorial.adminsekolah.net" class="bubble" style="background:var(--text-muted);" target="_blank" title="Tutorial">
            <i class="bi bi-journal-check"></i>
        </a>
        <span class="bubble-label">Tutorial</span>

        <a href="https://wa.me/6281233640003" class="bubble" target="_blank" title="Hubungi CS">
            <i class="bi bi-whatsapp"></i>
        </a>
        <span class="bubble-label">Hubungi CS</span>
    </div>

    <!-- Scripts -->
    <script src="<?php echo media_url() ?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

    <script>
        // ── Province → City AJAX ──
        function get_city() {
            var province_id = $("#registrasi_province").val();
            $.ajax({
                url: '<?php echo base_url(); ?>registrasi/get_city',
                type: 'POST',
                data: {
                    province_id: province_id
                },
                success: function(msg) {
                    $("#div_city").html(msg);
                },
                error: function() {
                    alert('Gagal memuat data kota.');
                }
            });
        }

        // ── Submit feedback ──
        $(document).ready(function() {
            $("#submit").click(function() {
                $("#submit").hide();
                $("#pesan").show();
            });
            $("#login_form").validate();
            $("[rel=tooltip]").tooltip({
                placement: 'top'
            });
        });

        // ── Image preview ──
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#target').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#user_image").change(function() {
            readURL(this);
        });

        function showPassword() {
            var input = document.getElementById("password");
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>

    <!-- Flash Notifications -->
    <script>
        <?php
        $success = $this->session->flashdata('success');
        $error   = $this->session->flashdata('error');
        $message = $message ?? '';
        ?>

        function showToast(message, type) {
            var colors = {
                success: {
                    bg: 'var(--primary-pale)',
                    border: 'var(--primary-mid)',
                    text: 'var(--primary-dark)',
                    icon: 'bi-check-circle-fill'
                },
                danger: {
                    bg: 'var(--red-pale)',
                    border: 'var(--red-border)',
                    text: '#991b1b',
                    icon: 'bi-exclamation-triangle-fill'
                }
            };
            var c = colors[type] || colors.success;
            var toast = $('<div>')
                .css({
                    position: 'fixed',
                    top: '20px',
                    right: '20px',
                    zIndex: 9999,
                    background: c.bg,
                    border: '1px solid ' + c.border,
                    color: c.text,
                    borderRadius: '12px',
                    padding: '0.8rem 1.1rem',
                    fontSize: '0.82rem',
                    fontWeight: 600,
                    boxShadow: '0 4px 20px rgba(0,0,0,0.1)',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.5rem',
                    maxWidth: '320px',
                    lineHeight: 1.4
                })
                .html('<i class="bi ' + c.icon + '"></i> ' + message);
            $('body').append(toast);
            setTimeout(function() {
                toast.fadeOut(400, function() {
                    $(this).remove();
                });
            }, 5000);
        }

        <?php if (!empty($success)): ?>
            $(document).ready(function() {
                showToast(<?php echo json_encode($success) ?>, 'success');
            });
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            $(document).ready(function() {
                showToast(<?php echo json_encode($error) ?>, 'danger');
            });
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            $(document).ready(function() {
                showToast(<?php echo json_encode($message) ?>, 'success');
            });
        <?php endif; ?>

        <?php $err_msg = $this->input->get('err_msg', TRUE); ?>
        <?php if (!empty($err_msg)): ?>
            $(document).ready(function() {
                showToast(<?php echo json_encode($err_msg) ?>, 'danger');
            });
        <?php endif; ?>

        <?php $success_msg = $this->input->get('success_msg', TRUE); ?>
        <?php if (!empty($success_msg)): ?>
            $(document).ready(function() {
                showToast(<?php echo json_encode($success_msg) ?>, 'success');
            });
        <?php endif; ?>
    </script>
</body>

</html>
