<!DOCTYPE html>
<html lang="en">

<?php
$this->load->model(array('setting/Setting_model'));
$setting_logo     = $this->Setting_model->get(array('id' => 6));
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sekolah | Login</title>
	<link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>b-asset/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>b-asset/css/iofrm-theme9.css">

    <link href="<?php echo media_url() ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo media_url() ?>css/font-awesome.min.css" rel="stylesheet" />

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<body>
    <div class="form-body">
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <h3 style="text-align: center; color: #2A8DBB;">Selamat Datang</h3>
                    <p style="text-align: center;"><strong>Sistem Pengelolaan Sekolah</strong></p>

                    <center>
                        <?php if (isset($setting_logo) and $setting_logo['setting_value'] == NULL) { ?>
                            <img src="<?php echo media_url('img/logo.svg') ?>" class="img-responsive" style="width:50%">
                        <?php } else { ?>
                            <img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" class="img-responsive" style="width:50%">
                        <?php } ?>
                    </center>
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items" style="max-width:380px">
                        <h3 style="text-align: center;padding-bottom: 45px;">Guru Login</h3>
                        <div class="website-logo-inside">
                            <div class="page-links">
                                <!-- <a href="<?php echo base_url() ?>">Login</a>
                                <a href="<?php echo base_url('student') ?>">Lynan. Siswa</a> -->
                                <a href="<?php echo base_url('employees')?>" class="active">Login</a>
                                <!-- <a href="<?php echo base_url('layanan_publik')?>">Lynan. Publik</a> -->
                            </div>
                            <?php echo form_open('employees/auth/login', array('class' => 'login100-form validate-form')); ?>
                            <input type="text" required="" autofocus="" name="nip" placeholder="Masukan NIP Pegawai" class="form-control flat">
                            <input type="password" required="" name="password" id="password" placeholder="Masukan password" class="form-control flat">

                            <input type="checkbox" class="form-check-input" onclick="showPassword()" id="exampleCheck2">

                            <label class="form-check-label" for="exampleCheck2"> Show Password</label>
                            <br><br>
                            <!-- <div class="g-recaptcha" data-sitekey="6LepNDMoAAAAAD24USrmyyryitTewEaNvLbVK26l"></div> -->

                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Login</button>
                            </div>
                            <?php echo form_close(); ?>
                            <div class="other-links">
                                <span>Aplikasi Wali Siswa</span>
                                <i class="fa fa-android" aria-hidden="true" style="color: white;font-size: 18px;"></i>&nbsp<a style="text-decoration: none;" href="https://play.google.com/store/apps/details?id=id.indoweb.adminsekolah.walimurid&hl=id-ID" target="_blank">Android</a>
                                <span> atau </span>
                                <a style="text-decoration: none;" href="https://m.adminsekolah.co.id" target="_blank">iOS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://brandio.io/envato/iofrm/html/js/jquery.min.js"></script>
        <script src="https://brandio.io/envato/iofrm/html/js/popper.min.js"></script>
        <script src="https://brandio.io/envato/iofrm/html/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>b-asset/js/main.js"></script>
</body>

</html>

<script src="<?php echo base_url() ?>b-asset/js/bootstrap-notify.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

<script>
    $("#login_form").validate();
</script>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip({
            placement: 'top'
        });
    });
</script>
<!-- Notification Script -->
<script>
    <?php
    $success = $this->session->flashdata('success');
    $error   = $this->session->flashdata('error');
    if (!empty($success)) {
    ?>
        $.notify({

            icon: 'glyphicon glyphicon-info-sign',
            title: '<b>Notification</b><br>',
            message: '<?php echo $success ?>',
        }, {
            type: "success success-noty col-md-3",
            allow_dismiss: true,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 1431,
            delay: 5000,
            timer: 1000,
            animate: {
                enter: 'animated bounceInDown',
                exit: 'animated bounceOutUp'
            }
        });
    <?php
    }
    if (!empty($error)) {
    ?>
        $.notify({

            icon: 'glyphicon glyphicon-info-sign',
            title: '<b>Notification</b><br>',
            message: '<?php echo $error ?>',
        }, {


            type: "danger noty-color col-md-3",
            allow_dismiss: true,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 1431,
            delay: 5000,
            timer: 1000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            }
        });
    <?php
    }
    ?>
</script>
<script>
    <?php
    if (!empty($message)) {
    ?>
        $.notify({

            icon: 'glyphicon glyphicon-info-sign',
            title: '<b>Notification</b><br>',
            message: '<?php echo $message; ?>',
        }, {

            type: "success success-noty col-md-3 col-md-offset-2",
            allow_dismiss: true,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 1431,
            delay: 5000,
            timer: 1000,
            animate: {
                enter: 'animated bounceInDown',
                exit: 'animated bounceOutUp'
            }
        });
    <?php
    }
    ?>
</script>
<script>
    <?php
    if (!empty($_GET['err_msg'])) {
    ?>
        $.notify({
            title: '<strong><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Error!</strong>',
            message: '<?php echo $_GET['err_msg'] ?>'
        }, {
            type: 'danger alert-danger-alt col-md-3 col-md-offset-3'
        });
    <?php
    } elseif (!empty($_GET['success_msg'])) {
    ?>

        $.notify({
            title: '<strong> <span class= "glyphicon glyphicon-ok"></span> Success!</strong>',
            message: '<?php echo $_GET['success_msg'] ?>'
        }, {
            type: 'success alert-success-alt col-md-3'
        });

    <?php
    }
    ?>
</script>
<script>
    function showPassword() {
        var input = document.getElementById("password");
        if (input.type === "password") {
            input.type = "teinputt";
        } else {
            input.type = "password";
        }
    }
</script>