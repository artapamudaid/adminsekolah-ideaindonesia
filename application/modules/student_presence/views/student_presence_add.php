<?php
$is_unlocked = isset($kiosk_unlocked) ? $kiosk_unlocked : false;
?>

<!-- Kiosk Container -->
<div class="kiosk-container" id="kiosk-container">

    <!-- Top Operator Bar -->
    <div class="kiosk-top-bar">
        <div class="bar-left">
            <span class="kiosk-badge-status"><span class="badge-dot pulse-green"></span> Presensi Siswa Aktif</span>
            <span class="kiosk-gps-status" id="gps-status-badge"><i class="fa fa-map-marker"></i> Mendeteksi Lokasi...</span>
        </div>
        <div class="bar-right">
            <button type="button" class="btn-kiosk-lock" id="btn-lock-kiosk" title="Kunci Presensi">
                <i class="fa fa-lock"></i> Kunci Kios
            </button>
        </div>
    </div>

    <!-- Hidden Form & Technical Inputs (Masked RFID to Prevent Titip Absen) -->
    <input type="password" id="rfid" name="rfid" autocomplete="off" style="position: absolute; left: -9999px; opacity: 0; width: 1px; height: 1px;">
    <input type="hidden" id="lati" name="latitude">
    <input type="hidden" id="longi" name="longitude">
    <canvas id="canvas-capture" style="display: none;"></canvas>

    <!-- Main Grid: Camera Validation & Attendance Scanner -->
    <div class="kiosk-main-grid">

        <!-- Column 1: Live Webcam Face Validation -->
        <div class="kiosk-cam-card">
            <div class="cam-header">
                <span class="cam-badge-live"><i class="fa fa-circle text-danger"></i> LIVE KAMERA</span>
                <span class="cam-tip">Arahkan wajah ke dalam frame</span>
            </div>
            <div class="cam-view-wrapper">
                <video id="webcam_video" autoplay playsinline muted></video>
                <div class="face-guide-frame">
                    <div class="face-guide-oval">
                        <div class="face-guide-glow"></div>
                        <div class="face-guide-ring"></div>
                        <div class="face-corner corner-tl"></div>
                        <div class="face-corner corner-tr"></div>
                        <div class="face-corner corner-bl"></div>
                        <div class="face-corner corner-br"></div>
                        <div class="face-crosshair ch-top"></div>
                        <div class="face-crosshair ch-bottom"></div>
                        <div class="face-crosshair ch-left"></div>
                        <div class="face-crosshair ch-right"></div>
                        <div class="face-scan-line"></div>
                    </div>
                </div>
                <div class="cam-overlay-status" id="cam-status-msg">
                    <i class="fa fa-camera"></i> Mengaktifkan Kamera...
                </div>
            </div>
        </div>

        <!-- Column 2: RFID Tap Zone & Real-time Feedback -->
        <div class="kiosk-scanner-card" id="scanner-card">

            <!-- Default State: Ready to Tap -->
            <div class="tap-ready-zone" id="tap-ready-zone">
                <div class="rfid-tap-art">
                    <div class="rfid-wave wave-3"></div>
                    <div class="rfid-wave wave-2"></div>
                    <div class="rfid-wave wave-1"></div>
                    <div class="rfid-center-icon">
                        <i class="fa fa-credit-card"></i>
                    </div>
                </div>
                <h3 class="tap-main-title">Tempelkan Kartu Anda</h3>
            </div>

            <!-- Loading State -->
            <div class="tap-loading-zone" id="tap-loading-zone" style="display: none;">
                <div class="kiosk-spinner"></div>
                <h4>Memproses Presensi Siswa...</h4>
                <p>Memvalidasi identitas, lokasi GPS & snapshot wajah</p>
            </div>

            <!-- Result Feedback Card (Success) -->
            <div class="tap-result-zone" id="tap-result-zone" style="display: none;">
                <div class="result-header" id="result-badge-container">
                    <span class="badge-absen-type" id="res-badge-type">DATANG</span>
                    <span class="badge-absen-status" id="res-badge-status">TEPAT WAKTU</span>
                </div>

                <div class="result-photos-duo">
                    <div class="photo-box">
                        <div class="photo-title">Foto Validasi Kamera</div>
                        <img id="res-capture-img" src="" alt="Capture">
                    </div>
                    <div class="photo-box">
                        <div class="photo-title">Foto Master Siswa</div>
                        <div id="res-master-photo-area"></div>
                    </div>
                </div>

                <div class="result-details">
                    <h3 class="res-nama" id="res-nama">-</h3>
                    <p class="res-meta" id="res-meta">-</p>
                    <div class="res-pesan-box" id="res-pesan">-</div>
                    <div class="res-time" id="res-time">-</div>
                </div>
            </div>

            <!-- Error Banner -->
            <div class="tap-error-banner" id="tap-error-banner" style="display: none;">
                <div class="error-icon"><i class="fa fa-times-circle"></i></div>
                <h4 id="error-title">Presensi Ditolak</h4>
                <p id="error-desc">-</p>
            </div>

        </div>

    </div>

</div>

<!-- Modal / Overlay Lock Screen Operator PIN -->
<div class="kiosk-lock-overlay" id="kiosk-lock-overlay" style="<?= $is_unlocked ? 'display:none;' : 'display:flex;' ?>">
    <div class="kiosk-lock-box">
        <div class="lock-icon-circle">
            <i class="fa fa-lock"></i>
        </div>
        <h3 class="lock-title">Presensi Terkunci</h3>
        <p class="lock-desc">Masukkan PIN Operator untuk membuka dan mengaktifkan sistem presensi siswa di perangkat ini.</p>

        <form id="form-unlock-kiosk" autocomplete="off">
            <div class="pin-input-group">
                <input type="password" id="input-operator-pin" class="pin-field" maxlength="8" placeholder="Masukkan PIN Operator" required autofocus>
                <div class="pin-hint">Default PIN: <strong>123456</strong> atau password login akun admin</div>
            </div>
            <div id="unlock-error-msg" class="unlock-error" style="display: none;"></div>
            <button type="submit" class="btn-unlock-submit" id="btn-unlock-submit">
                <i class="fa fa-unlock-alt"></i> Buka Presensi
            </button>
        </form>
    </div>
</div>

<style>

.presence-content {
    max-width: 980px !important;
    width: 100% !important;
}

.kiosk-container {
    width: 100%;
    position: relative;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

/* Top Operator Bar */
.kiosk-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #FFFFFF;
    border-radius: 14px;
    padding: 12px 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    border: 1px solid #F1F5F9;
}

.bar-left {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.kiosk-badge-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #15803D;
    background: #DCFCE7;
    padding: 4px 12px;
    border-radius: 20px;
}

.badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.pulse-green {
    background: #22C55E;
    box-shadow: 0 0 8px #22C55E;
    animation: pulseG 1.8s infinite;
}

@keyframes pulseG {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

.kiosk-gps-status {
    font-size: 12px;
    font-weight: 600;
    color: #64748B;
    background: #F8FAFC;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
}

.btn-kiosk-lock {
    background: #F1F5F9;
    color: #475569;
    border: 1px solid #E2E8F0;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-kiosk-lock:hover {
    background: #FEE2E2;
    color: #DC2626;
    border-color: #FECACA;
}

/* Main Grid */
.kiosk-main-grid {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 22px;
}

@media (max-width: 860px) {
    .kiosk-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Column 1: Live Webcam Card */
.kiosk-cam-card {
    background: #0F172A;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.25);
    border: 1px solid #1E293B;
    display: flex;
    flex-direction: column;
}

.cam-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    background: #1E293B;
    border-bottom: 1px solid #334155;
}

.cam-badge-live {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.5px;
    color: #F8FAFC;
    background: rgba(239, 68, 68, 0.2);
    padding: 3px 10px;
    border-radius: 12px;
    border: 1px solid rgba(239, 68, 68, 0.4);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.cam-tip {
    font-size: 11.5px;
    color: #94A3B8;
}

.cam-view-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    background: #020617;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

#webcam_video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1); /* Mirror camera feed */
}

.face-guide-frame {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 5;
}

.face-guide-oval {
    position: relative;
    width: 215px;
    height: 275px;
    border-radius: 140px;
    box-shadow: 0 0 0 9999px rgba(10, 15, 29, 0.65), inset 0 0 24px rgba(255, 122, 24, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

/* Pulsing Biometric Ambient Aura */
.face-guide-glow {
    position: absolute;
    inset: -6px;
    border-radius: 146px;
    border: 2px solid rgba(255, 122, 24, 0.45);
    animation: auraPulse 2.8s ease-in-out infinite;
    pointer-events: none;
}

@keyframes auraPulse {
    0% { transform: scale(0.985); opacity: 0.3; box-shadow: 0 0 0 0 rgba(255, 122, 24, 0.4); }
    50% { transform: scale(1.015); opacity: 0.9; box-shadow: 0 0 22px rgba(255, 122, 24, 0.45); }
    100% { transform: scale(0.985); opacity: 0.3; box-shadow: 0 0 0 0 rgba(255, 122, 24, 0.4); }
}

/* Inner Segmented Dotted Ring */
.face-guide-ring {
    position: absolute;
    inset: 0;
    border-radius: 140px;
    border: 2px dashed rgba(255, 255, 255, 0.45);
    box-sizing: border-box;
}

/* 4 High-tech Biometric Reticle Corners */
.face-corner {
    position: absolute;
    width: 26px;
    height: 26px;
    border: 3px solid #FF7A18;
    box-sizing: border-box;
    filter: drop-shadow(0 0 6px rgba(255, 122, 24, 0.7));
}

.corner-tl { top: -4px; left: 18px; border-right: none; border-bottom: none; border-top-left-radius: 12px; }
.corner-tr { top: -4px; right: 18px; border-left: none; border-bottom: none; border-top-right-radius: 12px; }
.corner-bl { bottom: -4px; left: 18px; border-right: none; border-top: none; border-bottom-left-radius: 12px; }
.corner-br { bottom: -4px; right: 18px; border-left: none; border-top: none; border-bottom-right-radius: 12px; }

/* Crosshair Markers */
.face-crosshair {
    position: absolute;
    background: rgba(255, 122, 24, 0.7);
    pointer-events: none;
    border-radius: 1px;
}
.ch-top { top: 8px; width: 2px; height: 10px; }
.ch-bottom { bottom: 8px; width: 2px; height: 10px; }
.ch-left { left: 8px; width: 10px; height: 2px; }
.ch-right { right: 8px; width: 10px; height: 2px; }

/* Glowing Laser Scan Beam */
.face-scan-line {
    position: absolute;
    width: 90%;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 122, 24, 0.3) 15%, #FFFFFF 50%, rgba(255, 122, 24, 0.3) 85%, transparent 100%);
    box-shadow: 0 0 14px rgba(255, 122, 24, 0.95), 0 0 28px rgba(234, 88, 12, 0.6);
    border-radius: 2px;
    animation: laserScan 2.4s ease-in-out infinite alternate;
}

@keyframes laserScan {
    0% { transform: translateY(-112px); opacity: 0.15; }
    30% { opacity: 0.95; }
    70% { opacity: 0.95; }
    100% { transform: translateY(112px); opacity: 0.15; }
}

.cam-overlay-status {
    position: absolute;
    bottom: 14px;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    color: #FFFFFF;
    font-size: 11.5px;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
    pointer-events: none;
    z-index: 10;
}

/* Column 2: Scanner Card */
.kiosk-scanner-card {
    background: #FFFFFF;
    border-radius: 18px;
    padding: 28px 24px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.06);
    border: 1px solid #F1F5F9;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 380px;
    text-align: center;
    position: relative;
}

/* Tap Ready State */
.rfid-tap-art {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rfid-center-icon {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FF7A18 0%, #EA580C 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    font-size: 34px;
    position: relative;
    z-index: 4;
    box-shadow: 0 8px 20px rgba(234, 88, 12, 0.35);
}

.rfid-wave {
    position: absolute;
    border-radius: 50%;
    border: 2px solid #FF7A18;
    opacity: 0;
    animation: wavePulse 2.8s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
}

.wave-1 { width: 95px; height: 95px; animation-delay: 0s; }
.wave-2 { width: 120px; height: 120px; animation-delay: 0.7s; }
.wave-3 { width: 145px; height: 145px; animation-delay: 1.4s; }

@keyframes wavePulse {
    0% { transform: scale(0.6); opacity: 0.8; }
    50% { opacity: 0.4; }
    100% { transform: scale(1.3); opacity: 0; }
}

.tap-main-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 8px 0;
}

.tap-sub-title {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.5;
    margin: 0 0 18px 0;
    max-width: 320px;
}

.tap-security-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFF7ED;
    border: 1px solid #FFEDD5;
    color: #C2410C;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 20px;
}

/* Loading State */
.tap-loading-zone {
    padding: 40px 20px;
}

.kiosk-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #FED7AA;
    border-top-color: #EA580C;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 18px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.tap-loading-zone h4 {
    font-size: 18px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 6px 0;
}

.tap-loading-zone p {
    font-size: 13px;
    color: #64748B;
    margin: 0;
}

/* Result Card */
.tap-result-zone {
    width: 100%;
    animation: fadeInScale 0.3s ease;
}

@keyframes fadeInScale {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.result-header {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.badge-absen-type {
    font-size: 13px;
    font-weight: 800;
    padding: 6px 16px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-absen-type.DATANG {
    background: #DCFCE7;
    color: #15803D;
}

.badge-absen-type.PULANG {
    background: #E0E7FF;
    color: #4338CA;
}

.badge-absen-status {
    font-size: 12px;
    font-weight: 700;
    background: #FEF3C7;
    color: #B45309;
    padding: 5px 12px;
    border-radius: 20px;
}

.result-photos-duo {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-bottom: 18px;
}

.photo-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.photo-title {
    font-size: 11px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
}

.photo-box img {
    width: 90px;
    height: 90px;
    border-radius: 14px;
    object-fit: cover;
    border: 3px solid #FFFFFF;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

.photo-box span[data-letters] {
    width: 90px;
    height: 90px;
    border-radius: 14px;
    background: #EA580C;
    color: #FFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 800;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

.photo-box span[data-letters]:before {
    content: attr(data-letters);
}

.result-details {
    background: #F8FAFC;
    border-radius: 14px;
    padding: 14px 18px;
    border: 1px solid #E2E8F0;
}

.res-nama {
    font-size: 18px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 4px 0;
}

.res-meta {
    font-size: 12.5px;
    color: #64748B;
    margin: 0 0 10px 0;
}

.res-pesan-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 700;
    color: #EA580C;
    margin-bottom: 8px;
}

.res-time {
    font-size: 11.5px;
    font-weight: 600;
    color: #94A3B8;
}

/* Error Banner */
.tap-error-banner {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 14px;
    padding: 20px;
    width: 100%;
    animation: shake 0.4s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}

.error-icon {
    font-size: 34px;
    color: #DC2626;
    margin-bottom: 8px;
}

.tap-error-banner h4 {
    font-size: 16px;
    font-weight: 800;
    color: #991B1B;
    margin: 0 0 4px 0;
}

.tap-error-banner p {
    font-size: 13px;
    color: #B91C1C;
    margin: 0;
}

/* Operator Lock Screen Overlay */
.kiosk-lock-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.kiosk-lock-box {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 36px 32px;
    width: 100%;
    max-width: 420px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.5);
    animation: scaleIn 0.25s ease;
}

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}

.lock-icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #FFF7ED;
    color: #EA580C;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin: 0 auto 16px;
    box-shadow: 0 4px 14px rgba(234, 88, 12, 0.2);
}

.lock-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 6px 0;
}

.lock-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.45;
    margin: 0 0 20px 0;
}

.pin-input-group {
    margin-bottom: 16px;
}

.pin-field {
    width: 100%;
    text-align: center;
    font-size: 22px;
    letter-spacing: 6px;
    font-weight: 800;
    padding: 12px 16px;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    outline: none;
    transition: all 0.2s ease;
}

.pin-field:focus {
    border-color: #EA580C;
    box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.15);
}

.pin-hint {
    font-size: 11.5px;
    color: #94A3B8;
    margin-top: 6px;
}

.unlock-error {
    background: #FEE2E2;
    color: #DC2626;
    border: 1px solid #FECACA;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 14px;
}

.btn-unlock-submit {
    width: 100%;
    background: linear-gradient(135deg, #FF7A18 0%, #EA580C 100%);
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 700;
    padding: 12px 20px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-unlock-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(234, 88, 12, 0.4);
}
</style>

<script>
$(document).ready(function() {

    var isUnlocked = <?= $is_unlocked ? 'true' : 'false' ?>;
    var videoStream = null;
    var audioCtx = null;
    var isProcessing = false;

    // Synthesized Sound Generator
    function playBeep(success) {
        try {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            var osc = audioCtx.createOscillator();
            var gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (success) {
                // Success Ding
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                osc.frequency.setValueAtTime(880.00, audioCtx.currentTime + 0.1); // A5
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.35);
                osc.start(audioCtx.currentTime);
                osc.stop(audioCtx.currentTime + 0.35);
            } else {
                // Error Buzz
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                osc.start(audioCtx.currentTime);
                osc.stop(audioCtx.currentTime + 0.3);
            }
        } catch(e) {}
    }

    // Camera Handler
    function initCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            $('#cam-status-msg').html('<i class="fa fa-warning"></i> Browser tidak mendukung WebRTC Kamera');
            return;
        }

        navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 640 }, 
                height: { ideal: 480 },
                facingMode: 'user' 
            }, 
            audio: false 
        })
        .then(function(stream) {
            videoStream = stream;
            var video = document.getElementById('webcam_video');
            video.srcObject = stream;
            video.play();
            $('#cam-status-msg').html('<i class="fa fa-check-circle text-success"></i> Kamera Siap');
        })
        .catch(function(err) {
            console.error('Kamera gagal:', err);
            $('#cam-status-msg').html('<i class="fa fa-exclamation-triangle text-danger"></i> Akses Kamera Ditolak / Tidak Ditemukan');
        });
    }

    function capturePhotoBase64() {
        try {
            var video = document.getElementById('webcam_video');
            var canvas = document.getElementById('canvas-capture');
            if (!video || !video.videoWidth) return '';

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            var ctx = canvas.getContext('2d');
            // Mirror back to original
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            return canvas.toDataURL('image/jpeg', 0.85);
        } catch(e) {
            console.error('Capture error:', e);
            return '';
        }
    }

    // Geolocation Handler
    function getDeviceLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                $('#longi').val(pos.coords.longitude);
                $('#lati').val(pos.coords.latitude);
                $('#gps-status-badge').html('<i class="fa fa-check-circle text-success"></i> GPS Terkoneksi (±' + Math.round(pos.coords.accuracy) + 'm)');
            }, function(err) {
                console.warn('Geolocation error:', err.message);
                $('#gps-status-badge').html('<i class="fa fa-exclamation-circle text-danger"></i> Akses GPS Belum Aktif');
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        } else {
            $('#gps-status-badge').html('<i class="fa fa-times text-danger"></i> GPS Tidak Didukung');
        }
    }

    // Auto-focus Manager on Masked RFID Input
    function keepRfidFocus() {
        if (isUnlocked && !isProcessing) {
            var rfid = document.getElementById('rfid');
            if (rfid && document.activeElement !== rfid && document.activeElement.tagName !== 'INPUT') {
                rfid.focus();
            }
        }
    }
    setInterval(keepRfidFocus, 1000);
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#kiosk-lock-overlay').length && !$(e.target).is('button')) {
            keepRfidFocus();
        }
    });

    // Start services if already unlocked
    if (isUnlocked) {
        initCamera();
        getDeviceLocation();
        setTimeout(keepRfidFocus, 500);
    }

    // Operator Unlock Flow
    $('#form-unlock-kiosk').on('submit', function(e) {
        e.preventDefault();
        var pin = $('#input-operator-pin').val().trim();
        if (!pin) return;

        $('#btn-unlock-submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memverifikasi...');
        $('#unlock-error-msg').hide();

        $.ajax({
            url: "<?= base_url('student_presence/unlock_kiosk') ?>",
            method: 'POST',
            data: { pin: pin },
            dataType: 'json',
            success: function(res) {
                $('#btn-unlock-submit').prop('disabled', false).html('<i class="fa fa-unlock-alt"></i> Buka Presensi');
                if (res.status === 'success') {
                    isUnlocked = true;
                    $('#kiosk-lock-overlay').fadeOut();
                    $('#input-operator-pin').val('');
                    initCamera();
                    getDeviceLocation();
                    setTimeout(keepRfidFocus, 500);
                } else {
                    $('#unlock-error-msg').text(res.message).fadeIn();
                    $('#input-operator-pin').focus();
                }
            },
            error: function() {
                $('#btn-unlock-submit').prop('disabled', false).html('<i class="fa fa-unlock-alt"></i> Buka Presensi');
                $('#unlock-error-msg').text('Gagal menghubungi server!').fadeIn();
            }
        });
    });

    // Lock Kiosk Action
    $('#btn-lock-kiosk').on('click', function() {
        if (!confirm('Kunci presensi siswa ini?')) return;
        $.post("<?= base_url('student_presence/lock_kiosk') ?>", function() {
            isUnlocked = false;
            $('#kiosk-lock-overlay').fadeIn();
            $('#input-operator-pin').val('').focus();
        });
    });

    // RFID Scanner Processing (Anti Manual Typing & Hardware Detection)
    $('#rfid').on('keydown', function(e) {
        if (e.which === 13) { // Enter key from barcode/RFID scanner
            e.preventDefault();
            var cardCode = $(this).val().trim();
            $(this).val('');

            if (cardCode.length >= 6) {
                processAttendance(cardCode);
            }
        }
    });

    function processAttendance(rfidCode) {
        if (isProcessing) return;
        isProcessing = true;

        // Visual State: Loading
        $('#tap-ready-zone').hide();
        $('#tap-result-zone').hide();
        $('#tap-error-banner').hide();
        $('#tap-loading-zone').fadeIn();

        // Snapshot face validation from camera
        var capturedPhoto = capturePhotoBase64();
        var longi = $('#longi').val();
        var lati = $('#lati').val();

        $.ajax({
            url: "<?= base_url('student_presence/presence') ?>",
            method: 'POST',
            data: {
                rfid: rfidCode,
                longitude: longi,
                latitude: lati,
                image: capturedPhoto
            },
            dataType: 'json',
            success: function(data) {
                $('#tap-loading-zone').hide();

                if (data.status === 'success') {
                    playBeep(true);

                    // Badge Types
                    $('#res-badge-type').removeClass('DATANG PULANG').addClass(data.jenis_absen).text(data.jenis_absen);
                    $('#res-badge-status').text(data.status_hadir);

                    // Photos
                    if (data.foto_capture) {
                        $('#res-capture-img').attr('src', data.foto_capture).show();
                    } else if (capturedPhoto) {
                        $('#res-capture-img').attr('src', capturedPhoto).show();
                    } else {
                        $('#res-capture-img').hide();
                    }

                    if (data.foto) {
                        $('#res-master-photo-area').html('<img src="<?= base_url('uploads/student/') ?>' + data.foto + '" alt="Master">');
                    } else {
                        var initial = (data.nama || 'S').trim()[0].toUpperCase();
                        $('#res-master-photo-area').html('<span data-letters="' + initial + '"></span>');
                    }

                    // Text Details
                    $('#res-nama').text(data.nama);
                    $('#res-meta').text('NIS: ' + (data.nis || '-') + ' • ' + (data.kelas || '-') + ' (' + (data.unit || '-') + ')');
                    $('#res-pesan').text(data.pesan);
                    $('#res-time').text(data.keterangan + ' pada ' + data.time);

                    $('#tap-result-zone').fadeIn();

                    // Auto-reset back to ready state after 4 seconds
                    setTimeout(function() {
                        $('#tap-result-zone').fadeOut(function() {
                            $('#tap-ready-zone').fadeIn();
                            isProcessing = false;
                            keepRfidFocus();
                        });
                    }, 4000);

                } else {
                    playBeep(false);
                    $('#error-title').text('Presensi Ditolak');
                    $('#error-desc').text(data.message || 'Kartu tidak dikenali atau di luar area!');
                    $('#tap-error-banner').fadeIn();

                    setTimeout(function() {
                        $('#tap-error-banner').fadeOut(function() {
                            $('#tap-ready-zone').fadeIn();
                            isProcessing = false;
                            keepRfidFocus();
                        });
                    }, 3500);
                }
            },
            error: function(xhr, status, error) {
                $('#tap-loading-zone').hide();
                playBeep(false);
                $('#error-title').text('Koneksi Terputus');
                $('#error-desc').text('Gagal menghubungi server. Periksa jaringan internet Anda.');
                $('#tap-error-banner').fadeIn();

                setTimeout(function() {
                    $('#tap-error-banner').fadeOut(function() {
                        $('#tap-ready-zone').fadeIn();
                        isProcessing = false;
                        keepRfidFocus();
                    });
                }, 3500);
            }
        });
    }

});
</script>