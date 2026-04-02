<style>
    #app-suspend-info {
        position: fixed;
        top: 50px;
        width: 100%;
        z-index: 99;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.3s ease-in-out;
    }

    #app-suspend-info .callout {
        margin: 0;
        border-radius: 0;
        padding: 10px 30px 10px 10px;
    }

    @media (max-width: 767px) {
        #app-suspend-info {
            top: 100px;
        }

        .content-wrapper {
            margin-top: 55px;
        }

        .sidebar-open #app-suspend-info {
            margin-top: -60px;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .content-wrapper {
        margin-top: 40px;
    }
</style>

<div id="app-suspend-info">
    <div class="callout <?= $bg_color; ?>">
        <p>
            <strong><?= $title_text; ?></strong>&nbsp;&nbsp;&nbsp;
            Status berlangganan Aplikasi anda akan habis dalam <strong><?= $days_left; ?></strong> hari.
            Segera lakukan pembayaran sebelum tanggal jatuh tempo pada <b><?= pretty_date($suspend_date, 'l, d F Y', false); ?></b>.
        </p>
    </div>
</div>