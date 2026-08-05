<style>
    #app-password-reminder {
        position: fixed;
        top: 50px;
        width: 100%;
        z-index: 98;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.3s ease-in-out;
    }

    #app-password-reminder .callout {
        margin: 0;
        border-radius: 0;
        padding: 10px 30px 10px 10px;
    }

    #app-password-reminder .btn-dismiss {
        cursor: pointer;
        text-decoration: underline;
    }

    @media (max-width: 767px) {
        #app-password-reminder {
            top: 100px;
        }
    }
</style>

<div id="app-password-reminder">
    <div class="callout callout-warning">
        <p>
            <strong>Perhatian!</strong>&nbsp;&nbsp;&nbsp;
            Sudah lebih dari 4 bulan sejak Anda terakhir memperbarui password.
            Kami menyarankan Anda untuk <a href="<?= site_url('manage/profile/cpw') ?>"><strong>memperbarui password</strong></a> demi keamanan akun Anda.
            <span class="btn-dismiss" onclick="dismissPasswordReminder()">
                <strong>Abaikan</strong>
            </span>
        </p>
    </div>
</div>

<script>
    function dismissPasswordReminder() {
        $.post('<?= site_url("manage/profile/dismiss_password_reminder") ?>', function() {
            $('#app-password-reminder').slideUp();
        });
    }
</script>