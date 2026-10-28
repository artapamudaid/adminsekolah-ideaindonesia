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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

  <style>
    body {
      min-height: 100vh;
      background-color: #f8f9fa;
    }

    .left-section {
      background-color: #fff;
      display: flex;
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

    .form-check-input {
      width: 1.25em;
      height: 1.25em;
      border: 2px solid white;
      appearance: none;
      cursor: pointer;
    }

    @media (max-width: 1024px) {
      .left-section {
        display: none !important;
      }

      .right-section {
        flex: 1 0 100%;
        max-width: 100%;
      }

      .right-section #announcementCarouselRight {
        display: block !important;
      }
    }



    .carousel-inner img {
      max-height: 500px;
      /* object-fit: cover; */
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row min-vh-100">
      <!-- LEFT SECTION -->
      <div class="col-md-6 left-section">
        <div id="announcementCarouselLeft" class="carousel slide w-100" data-bs-ride="carousel">
          <?php if (!empty($pengumuman)): ?>
            <div class="carousel-indicators">
              <?php foreach ($pengumuman as $idx => $item): ?>
                <button type="button" data-bs-target="#announcementCarouselLeft" data-bs-slide-to="<?= $idx ?>" class="<?= $idx == 0 ? 'active' : '' ?>" aria-current="<?= $idx == 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $idx + 1 ?>"></button>
              <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
              <?php foreach ($pengumuman as $idx => $item): ?>
                <div class="carousel-item <?= $idx == 0 ? 'active' : '' ?>">
                  <img src="<?= $item['information_img'] ?>" class="d-block" style="margin: 0 auto 0 auto;" alt="Slide <?= $idx + 1 ?>" />
                </div>
              <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarouselLeft" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#announcementCarouselLeft" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
              <span class="visually-hidden">Next</span>
            </button>
          <?php else: ?>
            <?php
            $this->load->model(array('setting/Setting_model'));
            $setting_logo     = $this->Setting_model->get(array('id' => 6));
            ?>
            <center>
              <?php if (isset($setting_logo) and $setting_logo['setting_value'] == NULL) { ?>
                <img src="<?php echo media_url('img/logo.svg') ?>" class="img-responsive" style="width:50%">
              <?php } else { ?>
                <img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" class="img-responsive" style="width:50%">
              <?php } ?>
            </center>
          <?php endif; ?>
        </div>
      </div>

      <!-- RIGHT SECTION -->
      <div class="col-md-6 right-section">
        <div class="w-100" style="max-width: 400px;">
          <p class="text-center"><strong>Aplikasi AdminSekolah</strong></p>
          <h3 class="text-center pb-4"><?= $setting_name['setting_value'] ?? '' ?></h3>

          <!-- MOBILE Carousel -->
          <div id="announcementCarouselRight" class="carousel slide d-md-none mb-4" data-bs-ride="carousel">
            <?php if (!empty($pengumuman)): ?>
              <div class="carousel-indicators">
                <?php foreach ($pengumuman as $idx => $item): ?>
                  <button type="button" data-bs-target="#announcementCarouselRight" data-bs-slide-to="<?= $idx ?>" class="<?= $idx == 0 ? 'active' : '' ?>" aria-current="<?= $idx == 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $idx + 1 ?>"></button>
                <?php endforeach; ?>
              </div>
              <div class="carousel-inner">
                <?php foreach ($pengumuman as $idx => $item): ?>
                  <div class="carousel-item <?= $idx == 0 ? 'active' : '' ?>">
                    <img src="<?= $item['information_img'] ?>" class="d-block w-100" alt="Slide <?= $idx + 1 ?>" />
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p>Tidak ada pengumuman.</p>
            <?php endif; ?>
          </div>

          <!-- Login Form -->

          <!-- Flash Message -->
          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= $this->session->flashdata('error'); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $this->session->flashdata('success'); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?= form_open('student/auth/login') ?>
          <div class="mb-3">
            <input type="nis" class="form-control" name="nis" placeholder="Masukan NIS" required />
          </div>
          <div class="mb-3">
            <div class="input-group">
              <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password" required />
              <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer">
                <i id="toggle-icon" class="bi bi-eye"></i>
              </span>
            </div>
          </div>

          <button type="submit" class="btn btn-light w-100 mb-3">Login</button>

          <?= form_close() ?>

          <div class="mt-3">
            <span>Download Aplikasi Mobile di: </span>
            <i class="fa fa-android text-white"></i>
            <a href="https://play.google.com/store/apps/dev?id=6395890906220495321" target="_blank" class="text-white fw-bold">Android</a>
            <span> atau </span>
            <a href="https://apps.apple.com/id/developer/indoweb-id/id1807922292" target="_blank" class="text-white fw-bold">iOS</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <script>
    function togglePassword() {
      const pw = document.getElementById("password");
      const icon = document.getElementById("toggle-icon");
      const isPassword = pw.type === "password";
      pw.type = isPassword ? "text" : "password";
      icon.className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
    }
  </script>

</body>

</html>