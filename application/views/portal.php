<!DOCTYPE html>
<html lang="en">

<?php
$this->load->model(array('setting/Setting_model'));
$setting_name     = $this->Setting_model->get(array('id' => 1));
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sekolah | Login</title>
    <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
    body {
      min-height: 100vh;
      background-color: #f8f9fa;
    }
    .left-section {
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }
    .right-section {
      background-color: #2A8DBB;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .nav-tabs .nav-link.active {
      border-bottom: 3px solid white;
      color: white;
      background-color: transparent;
    }
    .nav-tabs .nav-link {
      color: white;
    }
    .form-control::placeholder {
      color: #bbb;
    }
    .form-check-input {
  width: 1.25em;
  height: 1.25em;
  background-color: transparent;
  border: 2px solid white;
  appearance: none;
  outline: none;
  cursor: pointer;
  position: relative;
}

@media (max-width: 991.98px) {
  .left-section {
    display: none !important;
  }

  .right-section {
    flex: 1 0 100%;
    max-width: 100%;
  }
}

/* #announcement di right-section hanya tampil di mobile/tablet portrait */
.right-section #announcement {
  display: none;
}

@media (max-width: 991.98px) {
  .right-section #announcement {
    display: block;
  }
}

@media (max-width: 991.98px) {
  .left-section #announcement {
    display: none;
  }
}



  </style>

<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <div class="col-md-6 left-section text-center">

                <div id="announcement" class="mb-4" style="max-width: 100%; height: 400px;">

                        <?php if (!empty($pengumuman)): ?>
                        <div id="carouselPengumuman" class="carousel slide" data-ride="carousel" style="width: 300px; margin: 0 auto;">
                            <ol class="carousel-indicators">
                                <?php foreach ($pengumuman as $idx => $item): ?>
                                    <li data-target="#carouselPengumuman" data-slide-to="<?= $idx ?>" class="<?= $idx == 0 ? 'active' : '' ?>"></li>
                                <?php endforeach; ?>
                            </ol>
                            <div class="carousel-inner">
                                <?php foreach ($pengumuman as $idx => $item): ?>
                                    <div class="carousel-item <?= $idx == 0 ? 'active' : '' ?>">
                                        <img class="d-block w-100" src="<?= $item['information_img'] ?>" alt="Slide <?= $idx+1 ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else: ?>
                            <p>Tidak ada pengumuman.</p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 right-section">
                <div class="w-100" style="max-width: 400px;">
                    <p style="text-align: center;"><strong>Sistem Pengelolaan Sekolah</strong></p>
                    <h3 style="text-align: center;padding-bottom: 45px;"><?= $setting_name['setting_value'] ?? '' ?></h3>
                    <div id="announcement" class="mb-4" style="max-width: 100%; height: 400px;">

                            <?php if (!empty($pengumuman)): ?>
                            <div id="carouselPengumuman" class="carousel slide" data-ride="carousel" style="width: 300px; margin: 0 auto;">
                                <ol class="carousel-indicators">
                                    <?php foreach ($pengumuman as $idx => $item): ?>
                                        <li data-target="#carouselPengumuman" data-slide-to="<?= $idx ?>" class="<?= $idx == 0 ? 'active' : '' ?>"></li>
                                    <?php endforeach; ?>
                                </ol>
                                <div class="carousel-inner">
                                    <?php foreach ($pengumuman as $idx => $item): ?>
                                        <div class="carousel-item <?= $idx == 0 ? 'active' : '' ?>">
                                            <img class="d-block w-100" src="<?= $item['information_img'] ?>" alt="Slide <?= $idx+1 ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php else: ?>
                                <p>Tidak ada pengumuman.</p>
                            <?php endif; ?>
                    </div>
                    <div class="website-logo-inside">
                        <div style="text-align: left; margin-bottom: 20px; ">
                            <a href="<?php echo base_url() ?>" class="active" style="color: #fff; text-decoration: none; font-weight: bold;">Login</a> |
                            <a href="<?php echo base_url() . 'student_presence' ?>" target="_blank" style="color: #fff; text-decoration: none; font-weight: bold;">Presensi Siswa</a> |
                            <a href="<?php echo base_url() . 'employee_presence' ?>" target="_blank" style="color: #fff; text-decoration: none; font-weight: bold;">Presensi Pegawai</a>
                        </div>
                        <?php echo form_open('manage/auth/login'); ?>
                            <div class="mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Masukan email" required>
                            </div>
                            <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password" required>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input bg-light" type="checkbox" id="showPassword" onclick="togglePassword()">
                                <label class="form-check-label" for="showPassword">Lihat Password</label>
                            </div>
                            <?php if(ENVIRONMENT=='production'): ?>
                                <div class="mb-3">
                                    <div class="g-recaptcha" data-sitekey="6LeuXAwrAAAAABgfW5GvcAVx1lNt4NYKqfrCOeR3"></div>
                                </div>
                            <?php endif; ?>

                        
                            <button type="submit" class="btn btn-light w-100 mb-3" >Login</button>

                            <label>Belum Punya Akun? </label> &nbsp;&nbsp;<a href="<?php echo site_url('registrasi') ?>" style="font-weight:bold; color: #fff;"> Daftar Di Sini</a>

                        <?php echo form_close(); ?>
                        <div class="other-links">
                            <span>Download Aplikasi Mobile di : </span>
                            <i class="fa fa-android" aria-hidden="true" style="color: white;font-size: 18px;"></i>&nbsp<a style="text-decoration: none;font-weight: bold; color: #fff" href="https://play.google.com/store/apps/dev?id=6395890906220495321" target="_blank">Android</a>
                            <span> atau </span>
                            <a style="text-decoration: none;font-weight: bold; color: #fff" href="https://apps.apple.com/id/developer/indoweb-id/id1807922292" target="_blank">iOS</a>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>


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
     function togglePassword() {
      const pw = document.getElementById("password");
      pw.type = pw.type === "password" ? "text" : "password";
    }

</script>

</body>
</html>