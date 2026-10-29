<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->config->item('app_name') ?> <?php echo isset($title) ? ' | ' . $title : null; ?></title>
  <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>css/style.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>css/load-font-googleapis.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
  <!-- Notyfy JS - Notification -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/jquery.toast.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>css/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>css/skin-orange.css?v=<?php echo file_exists(FCPATH . 'media/css/skin-orange.css') ? filemtime(FCPATH . 'media/css/skin-orange.css') : '1.0'; ?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>DataTables/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>DataTables/css/jquery.dataTables.css">

  <link href="<?php echo base_url('/media/js/fullcalendar/fullcalendar.css'); ?>" rel="stylesheet">
  <!-- Chat widget disabled
  <link rel="stylesheet" href="<?= base_url('/assets/chat/css/style.css') ?>">
  -->

  <script src="<?php echo media_url() ?>js/jquery.min.js"></script>
  <script src="<?php echo media_url() ?>js/dateFormat.js"></script>
  <script src="<?php echo media_url() ?>js/jquery.dateFormat.js"></script>
  <script src="<?php echo media_url() ?>DataTables/js/jquery.dataTables.js"></script>
  <!--<script src="<?php echo media_url() ?>DataTables/plugins/range-date.js"></script>-->
  <script src="<?php echo media_url() ?>js/jquery.ajaxfileupload.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo media_url() ?>js/jquery-ui.min.js"></script>
  <script src="<?php echo media_url() ?>js/jquery.inputmask.bundle.js"></script>

  <script src="<?php echo base_url('/media/js/fullcalendar/fullcalendar.js'); ?>"></script>
  <!-- <script src="<?php echo base_url('/media/js/Chart.js'); ?>"></script> -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
  <script type="text/javascript" src="https://emn178.github.io/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
</head>



<body class="hold-transition skin-orange fixed sidebar-mini" <?php echo isset($ngapp) ? $ngapp : null; ?>>
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo: Clean Modern Style (No PRO badge) -->
      <a href="<?php echo site_url('manage') ?>" class="logo">
        <span class="logo-lg">
          <div class="brand-wrapper">
            <span class="brand-name"><?php echo $this->config->item('app_name') ?></span>
          </div>
        </span>
      </a>

      <!-- Header Navbar: Modern Clean Header -->
      <nav class="navbar navbar-static-top navbar-custom-spark">
        <div class="navbar-left-wrapper">
          <!-- Sidebar Toggle Button (Squircle Modern Style) -->
          <a href="#" class="sidebar-toggle-btn" data-toggle="push-menu" role="button" title="Buka/Tutup Menu">
            <i class="fa fa-bars"></i>
          </a>

          <?php
          $schoolName = "SELECT setting_value as name FROM `setting` WHERE setting_name = 'setting_school'";
          $resultName = $this->db->query($schoolName)->row();
          ?>
          <?php
          $schoolAddress = "SELECT setting_value as address FROM `setting` WHERE setting_name = 'setting_address'";
          $resultAddress = $this->db->query($schoolAddress)->row();
          ?>
          <?php
          $schoolDistrict = "SELECT setting_value as district FROM `setting` WHERE setting_name = 'setting_district'";
          $resultDistrict = $this->db->query($schoolDistrict)->row();
          ?>
          <?php
          $schoolCity = "SELECT setting_value as city FROM `setting` WHERE setting_name = 'setting_city'";
          $resultCity = $this->db->query($schoolCity)->row();
          ?>
          <?php
          $schoolPhone = "SELECT setting_value as phone FROM `setting` WHERE setting_name = 'setting_phone'";
          $resultPhone = $this->db->query($schoolPhone)->row();
          ?>

          <!-- School Information Container -->
          <div class="header-school-info">
            <span class="header-school-name"><?php echo $resultName->name; ?></span>
            <span class="header-school-meta">
              <span><i class="fa fa-map-marker text-orange"></i> <?php echo $resultAddress->address . ' ' . $resultDistrict->district . ' - ' . $resultCity->city; ?></span>
              <span class="meta-separator hidden-xs">•</span>
              <span class="hidden-xs"><i class="fa fa-phone text-orange"></i> <?php echo $resultPhone->phone; ?></span>
            </span>
          </div>
        </div>

        <!-- Right Header Actions -->
        <div class="navbar-actions">
          <?php
          $notifTrx     = $this->db->query(
            "SELECT vtt.noref, vtt.nama, vtt.nis, vtt.idPeriod, SUM(vtt.penerimaan) AS totalTransaksi
             FROM v_transaksi_tertunda vtt 
             GROUP BY vtt.noref, vtt.nama, vtt.nis, vtt.idPeriod"
          )->result_array();
          $totalNotif = count($notifTrx);
          ?>

          <!-- Fullscreen Toggle Button -->
          <button class="navbar-action-btn hidden-xs" type="button" onclick="toggleFullScreen()" title="Layar Penuh">
            <i class="fa fa-arrows-alt"></i>
          </button>

          <!-- Notification Bell Dropdown -->
          <div class="dropdown">
            <button class="navbar-action-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifikasi Transaksi">
              <i class="fa fa-bell-o"></i>
              <?php if ($totalNotif > 0) { ?>
                <span class="navbar-action-badge"><?php echo $totalNotif; ?></span>
              <?php } ?>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-notification-spark">
              <div class="notification-spark-header">
                <div class="notif-header-title">
                  <i class="fa fa-bell-o text-orange"></i>
                  <span>Pembayaran Belum Disimpan</span>
                </div>
                <span class="notif-header-badge"><?php echo $totalNotif; ?> Baru</span>
              </div>
              <div class="notification-spark-body">
                <?php if ($totalNotif > 0) { ?>
                  <?php foreach ($notifTrx as $nTrx) { ?>
                    <a href="<?php echo base_url('manage/payout?n=') . $nTrx['idPeriod'] . '&r=' . $nTrx['nis'] ?>" class="notification-spark-item">
                      <div class="notif-item-icon">
                        <i class="fa fa-credit-card"></i>
                      </div>
                      <div class="notif-item-info">
                        <div class="notif-item-name"><?php echo $nTrx['nama'] ?></div>
                        <div class="notif-item-meta">
                          <span class="nis-label">NIS: <?php echo $nTrx['nis'] ?></span>
                          <span class="amount-label">Rp <?php echo number_format($nTrx['totalTransaksi'], 0, ',', '.'); ?></span>
                        </div>
                      </div>
                    </a>
                  <?php } ?>
                <?php } else { ?>
                  <div class="notif-empty-state">
                    <i class="fa fa-check-circle-o notif-empty-icon"></i>
                    <span>Semua transaksi telah tersimpan</span>
                  </div>
                <?php } ?>
              </div>
              <?php if ($totalNotif > 0) { ?>
                <div class="notification-spark-footer">
                  <a href="<?php echo site_url('manage/payout') ?>">Buka Menu Pembayaran <i class="fa fa-arrow-right"></i></a>
                </div>
              <?php } ?>
            </div>
          </div>

          <!-- User Profile Dropdown Pill -->
          <div class="dropdown">
            <button class="navbar-profile-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="navbar-avatar-wrapper">
                <?php if ($this->session->userdata('user_image') != null) { ?>
                  <img src="<?php echo upload_url() . '/users/' . $this->session->userdata('user_image'); ?>" class="navbar-profile-img" alt="Avatar">
                <?php } else { ?>
                  <img src="<?php echo media_url() ?>img/user.png" class="navbar-profile-img" alt="Avatar">
                <?php } ?>
                <span class="navbar-online-dot"></span>
              </div>
              <div class="navbar-user-text hidden-xs">
                <span class="navbar-profile-name"><?php echo ucfirst($this->session->userdata('ufullname')); ?></span>
                <span class="navbar-profile-role"><?php echo ucfirst($this->session->userdata('urolename')); ?></span>
              </div>
              <i class="fa fa-angle-down navbar-profile-caret"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-profile-spark">
              <li class="profile-dropdown-header">
                <div class="profile-header-card">
                  <?php if ($this->session->userdata('user_image') != null) { ?>
                    <img src="<?php echo upload_url() . '/users/' . $this->session->userdata('user_image'); ?>" class="profile-card-img" alt="Avatar">
                  <?php } else { ?>
                    <img src="<?php echo media_url() ?>img/user.png" class="profile-card-img" alt="Avatar">
                  <?php } ?>
                  <div class="profile-header-details">
                    <div class="profile-dropdown-name"><?php echo ucfirst($this->session->userdata('ufullname')); ?></div>
                    <span class="profile-dropdown-role-badge"><?php echo ucfirst($this->session->userdata('urolename')); ?></span>
                    <div class="profile-dropdown-email"><?php echo $this->session->userdata('uemail'); ?></div>
                  </div>
                </div>
              </li>
              <li class="profile-menu-section-title">Menu Pengguna</li>
              <li>
                <a href="<?php echo site_url('manage/profile') ?>" class="profile-dropdown-item">
                  <div class="item-icon-box"><i class="fa fa-user text-orange"></i></div>
                  <span>Profil Saya</span>
                </a>
              </li>
              <li class="profile-dropdown-divider"></li>
              <li>
                <a href="<?php echo site_url('manage/auth/logout?location=' . htmlspecialchars($_SERVER['REQUEST_URI'])) ?>" class="profile-dropdown-logout">
                  <div class="item-icon-box logout-icon-box"><i class="fa fa-sign-out"></i></div>
                  <span>Keluar Sistem</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <?php $files = glob('media/barcode_student/*');
    foreach ($files as $file) { // iterate files
      if (is_file($file))
        unlink($file); // delete file
    } ?>

    <?php $this->load->view('manage/sidebar'); ?>
    <!-- Content Wrapper. Contains page content -->
    <?php isset($main) ? $this->load->view($main) : null; ?>
    <!-- Content Wrapper. Contains page content -->


    <?php /* Chat Bubble & Widget Disabled
    <div class="widget-button" id="widget-toggle">
      💬
      <div class="notif-badge" id="notif-badge">0</div>
    </div>

    <div class="widget-container" id="widget-container">

      <div id="file-preview-modal" class="file-preview-modal">
        <div class="preview-header">
          <span style="cursor: pointer;" id="btn-cancel-file">✖ Batal</span>
          <span style="margin-left: auto;">Pratinjau File</span>
        </div>
        <div class="preview-body" id="preview-content"></div>
        <div class="preview-footer">
          <input type="text" id="file-caption" placeholder="Tambah keterangan (caption)...">
          <button class="btn-send" id="btn-send-file">➤</button>
        </div>
      </div>

      <div class="widget-header">
        <h4>Halo Indoweb</h4>
        <div class="header-actions">
          <button class="btn-logout" id="btn-logout">Keluar</button>
          <button class="widget-close" id="widget-close">×</button>
        </div>
      </div>

      <div class="widget-login" id="widget-login-view">
        <h3 style="text-align: center; margin-top: 0; color: #333;">Selamat Datang 👋</h3>
        <p style="text-align: center; font-size: 14px; color: #666;">Masukkan username dan password akun halo.indoweb.id Anda memulai percakapan.</p>
        <form id="form-login">
          <input type="text" id="login-username" placeholder="Username/Email di halo.indoweb.id" required>
          <input type="password" id="login-password" placeholder="Password di halo.indoweb.id" required>
          <button type="submit" id="btn-login">Mulai Chat</button>
        </form>
      </div>

      <div id="widget-chat-view" style="display: none; height: calc(100% - 60px); flex-direction: column;">

        <div class="widget-messages" id="widget-messages"></div>

        <form id="form-chat" class="widget-input-area">
          <input type="file" id="widget-file" style="display: none;" accept=".png,.jpg,.jpeg,.pdf,.docx">
          <label for="widget-file" class="icon-btn" id="btn-attach" title="Lampirkan File">📎</label>

          <button type="button" class="icon-btn" id="btn-emoji" title="Emoji">😀</button>

          <input type="text" id="widget-input-msg" placeholder="Ketik pesan..." autocomplete="off">

          <button type="submit" class="btn-send">➤</button>
        </form>

      </div>
    </div>
    */ ?>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <?php echo $this->config->item('app_name') . ' ' . $this->config->item('version') ?>
      </div>
      <?php echo $this->config->item('created') ?>
    </footer>

    <!-- jQuery 3 -->




    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo media_url() ?>js/bootstrap.min.js"></script>
    <script src="<?php echo media_url() ?>js/moment.min.js"></script>

    <script src="<?php echo media_url() ?>js/fullcalendar.min.js"></script>


    <!-- daterangepicker -->
    <script src="<?php echo media_url() ?>js/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo media_url() ?>js/bootstrap-datepicker.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo media_url() ?>js/jquery.slimscroll.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo media_url() ?>js/adminlte.min.js"></script>
    <!-- Notyfy JS -->
    <script src="<?php echo media_url() ?>js/jquery.toast.js"></script>

    <!-- select2 -->
    <link href="<?php echo media_url() ?>select2_css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo media_url() ?>select2_js/select2.min.js"></script>

    <script>
      $('.s2').select2();
    </script>

    <script>
      $(".years").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        todayHighlight: true
      });
      $(".input-group.date").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
      });
    </script>

    <?php if ($this->session->flashdata('success')) { ?>
      <script>
        $(document).ready(function() {
          $.toast({
            heading: 'Berhasil',
            text: '<?php echo $this->session->flashdata('success') ?>',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'success',
            hideAfter: 3500,
            stack: 6
          })
        });
      </script>
    <?php } ?>

    <?php if ($this->session->flashdata('failed')) { ?>
      <script>
        $(document).ready(function() {
          $.toast({
            heading: 'Gagal',
            text: '<?php echo $this->session->flashdata('failed') ?>',
            position: 'top-right',
            loaderBg: '#ff2e2e',
            icon: 'error',
            hideAfter: 3500,
            stack: 6
          })
        });
      </script>
    <?php } ?>


    <script>
      $(document).ready(function() {
        $('#dtable').DataTable();
      });
    </script>

    <script>
      $(document).ready(function() {
        $('.numeric').inputmask("numeric", {
          removeMaskOnSubmit: true,
          radixPoint: ".",
          groupSeparator: ",",
          digits: 2,
          autoGroup: true,
          prefix: 'Rp ', //Space after $, this will not truncate the first character.
          rightAlign: false,
          // oncleared: function() {
          //   self.Value('');
          // }
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>

    <script>
      // JavaScript New Window
      var win = null;

      function newWindow(mypage, myname, w, h, features) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        if (winl < 0) winl = 0;
        if (wint < 0) wint = 0;
        var settings = 'height=' + h + ',';
        settings += 'width=' + w + ',';
        settings += 'top=' + wint + ',';
        settings += 'left=10,';
        settings += features;
        win = window.open(mypage, myname, settings);
        win.window.focus();
      }
    </script>

    <script>
      // Fungsi untuk format nominal angka
      function number_format(number, decimals, dec_point, thousands_sep) {

        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s = '',
          toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
          };

        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
      }
    </script>

    <script>
      function pilih_paket() {
        var paket = $("#package").val();

        $.ajax({
          url: '<?= base_url() . 'manage/setting/change_package'; ?>',
          type: 'POST',
          data: {
            'paket': paket,
          },
          success: function(msg) {
            window.location.href = '<?= base_url() . 'manage'; ?>';
          },
          error: function(msg) {
            alert('msg');
          }

        });
        e.preventDefault();
      }
    </script>

    <script>
      $(function() {
        // Password reminder
        let pwd_reminder_show = <?= isset($this->password_reminder_info['show']) ? json_encode($this->password_reminder_info['show']) : 'false'; ?>;
        if (pwd_reminder_show) {
          let pwdReminderUI = <?= isset($this->password_reminder_info['ui']) ? json_encode($this->password_reminder_info['ui']) : '""'; ?>;
          $(".content-wrapper").prepend(pwdReminderUI);
        }
      });
    </script>

    <?php /* Chat Pusher & scripts disabled
    <audio id="notif-sound" src="https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3" preload="auto"></audio>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@2.12.1/dist/index.min.js"></script>
    <script src="<?= base_url('assets/chat/js/script.js?v=' . filemtime(FCPATH . 'assets/chat/js/script.js')) ?>"></script>
    */ ?>

    <script>
      function toggleFullScreen() {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen().catch(function(err) {});
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          }
        }
      }
    </script>

</body>

</html>