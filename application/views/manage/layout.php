<?php
// MOD - 24082026- Reference demo-epesantren - implement caching
$umajorsid = $this->session->userdata('umajorsid');
$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
$cache_name = 'v_transaksi_tertunda_cache_opt_' . $umajorsid;
$notifTrx = $this->cache->get($cache_name);

if ($notifTrx === FALSE) {
  $this->db->select('bl.bulan_noref AS noref, s.student_full_name AS nama, s.student_nis AS nis, py.period_period_id AS idPeriod, bl.bulan_bill AS penerimaan');
  $this->db->from('bulan bl');
  $this->db->join('student s', 's.student_id = bl.student_student_id');
  $this->db->join('payment py', 'py.payment_id = bl.payment_payment_id');
  $this->db->where('bl.bulan_status', 1);
  $this->db->where('bl.bulan_bill > ', 0);
  $this->db->where('bl.bulan_noref IS NOT NULL', NULL, FALSE);
  $this->db->where('NOT EXISTS (SELECT 1 FROM kas ks WHERE ks.kas_noref = bl.bulan_noref)', NULL, FALSE);
  $this->db->where('NOT EXISTS (SELECT 1 FROM jurnal_umum ju WHERE ju.noref = bl.bulan_noref)', NULL, FALSE);

  if ($umajorsid != 0) {
    $this->db->where('s.majors_majors_id', $umajorsid);
  }

  $query1 = $this->db->get_compiled_select();

  $this->db->select('bp.bebas_pay_noref AS noref, s.student_full_name AS nama, s.student_nis AS nis, py.period_period_id AS idPeriod, bp.bebas_pay_bill AS penerimaan');
  $this->db->from('bebas_pay bp');
  $this->db->join('bebas b', 'b.bebas_id = bp.bebas_bebas_id');
  $this->db->join('student s', 's.student_id = b.student_student_id');
  $this->db->join('payment py', 'py.payment_id = b.payment_payment_id');
  $this->db->where('bp.bebas_pay_noref IS NOT NULL', NULL, FALSE);
  $this->db->where('NOT EXISTS (SELECT 1 FROM kas ks WHERE ks.kas_noref = bp.bebas_pay_noref)', NULL, FALSE);
  $this->db->where('NOT EXISTS (SELECT 1 FROM jurnal_umum ju WHERE ju.noref = bp.bebas_pay_noref)', NULL, FALSE);

  if ($umajorsid != 0) {
    $this->db->where('s.majors_majors_id', $umajorsid);
  }

  $query2 = $this->db->get_compiled_select();

  $this->db->select('noref, nama, nis, idPeriod, SUM(penerimaan) AS totalTransaksi');
  $this->db->from("($query1 UNION ALL $query2) AS vtt");
  $this->db->group_by(array('nama', 'nis', 'idPeriod'));

  $notifTrx = $this->db->get()->result_array();

  // Cache for 5 minutes (300 seconds)
  $this->cache->save($cache_name, $notifTrx, 300);
}

$totalNotif = count($notifTrx);
?>
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
  <link rel="stylesheet" href="<?php echo media_url() ?>css/skin-blue.css?v=<?php echo file_exists(FCPATH . 'media/css/skin-blue.css') ? filemtime(FCPATH . 'media/css/skin-blue.css') : '1.0'; ?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo media_url() ?>css/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>DataTables/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="<?php echo media_url() ?>DataTables/css/jquery.dataTables.css">

  <link rel="stylesheet" href="<?php echo media_url(); ?>css/fullcalendar.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css">
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

  <style>
    .navbar-actions #app-suspend-info {
      list-style: none;
      display: inline-flex;
      align-items: center;
      margin: 0;
      padding: 0;
    }
    .navbar-actions #app-suspend-info > a {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #EFF6FF;
      border: 1px solid #DBEAFE;
      color: #2575FC;
      font-size: 12px;
      font-weight: 700;
      padding: 7px 12px;
      border-radius: 10px;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .navbar-actions #app-suspend-info > a:hover {
      background: #DBEAFE;
      color: #1D4ED8;
    }
    .navbar-actions #app-suspend-info .dropdown-menu {
      top: 100%;
      right: 0;
      left: auto;
      min-width: 260px;
      padding: 12px;
      border-radius: 12px;
      border: 1px solid #E2E8F0;
      box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
    }
  </style>
</head>



<body class="hold-transition skin-blue fixed sidebar-mini" <?php echo isset($ngapp) ? $ngapp : null; ?>>
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
          <!-- Language Switcher Dropdown -->
          <div class="dropdown">
            <button class="navbar-action-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Ubah Bahasa / Language">
              <i class="fa fa-globe"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 140px; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); padding: 8px 0; border: 1px solid #E2E8F0;">
              <li>
                <a href="<?php echo site_url('manage/change_language/id') ?>" style="padding: 8px 16px; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                  <span style="background: #EFF6FF; color: #2575FC; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 800;">ID</span> Indonesia
                </a>
              </li>
              <li>
                <a href="<?php echo site_url('manage/change_language/en') ?>" style="padding: 8px 16px; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                  <span style="background: #F1F5F9; color: #475569; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 800;">EN</span> English
                </a>
              </li>
            </ul>
          </div>

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
                  <div class="item-icon-box"><i class="fa fa-user" style="color: #2575FC;"></i></div>
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
    <script src="<?php echo media_url() ?>js/loadingoverlay.min.js"></script>

    <!-- select2 -->
    <link href="<?php echo media_url() ?>select2_css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo media_url() ?>select2_js/select2.min.js"></script>

    <script>
      $('.s2').select2();

      // Use native smooth hardware-accelerated scrollbar for .sidebar
      // and prevent AdminLTE SlimScroll from hijacking it and jumping to 100% on drag
      $(function() {
        if ($.fn.slimScroll) {
          var originalSlimScroll = $.fn.slimScroll;
          $.fn.slimScroll = function(options) {
            if (this.is('.sidebar') || this.hasClass('sidebar')) {
              // Destroy slimScroll if attached to keep native smooth scrollbar
              if (this.parent().hasClass('slimScrollDiv')) {
                originalSlimScroll.call(this, { destroy: true });
              }
              this.css({
                'height': 'calc(100vh - 65px - 68px)',
                'max-height': 'calc(100vh - 65px - 68px)',
                'overflow-y': 'auto',
                'overflow-x': 'hidden'
              });
              return this;
            }
            return originalSlimScroll.apply(this, arguments);
          };

          // Destroy any already attached slimScroll on sidebar
          if ($('.sidebar').length) {
            $('.sidebar').slimScroll({ destroy: true });
          }
        }
      });
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

      $('#multidate').datepicker({
        multidate: true,
        format: "yyyy-mm-dd"
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
      function toastNotifikasi(status, message, title) {
        let loaderBg;

        switch (status) {
          case 'success':
            loaderBg = '#28a745';
            break;
          case 'error':
            loaderBg = '#dc3545';
            break;
          case 'warning':
            loaderBg = '#ffc107';
            break;
          case 'info':
            loaderBg = '#17a2b8';
            break;
          default:
            loaderBg = '#9EC600';
        }

        $.toast({
          heading: title,
          text: message,
          icon: status,
          loader: true,
          loaderBg: loaderBg,
          position: 'top-right',
          hideAfter: 3500,
          stack: 6,
        });
      }

      function handleAjaxError(jqXHR, textStatus) {
        // console.log("Error details:", jqXHR, textStatus, errorThrown);
        if (jqXHR.status === 0) {
          toastNotifikasi("error", 'Tidak ada koneksi. Periksa jaringan Anda.!', 'Error');
        } else if (jqXHR.status == 404) {
          toastNotifikasi("error", 'Halaman yang diminta tidak ditemukan.!', 'Error');
        } else if (jqXHR.status == 500) {
          toastNotifikasi("error", 'Kesalahan server internal.!', 'Error');
        } else if (textStatus === "parsererror") {
          toastNotifikasi("error", 'Kesalahan parsing JSON.!', 'Error');
        } else if (textStatus === "timeout") {
          toastNotifikasi("error", 'Waktu request habis.!', 'Error');
        } else if (textStatus === "abort") {
          toastNotifikasi("error", 'Request dibatalkan.!', 'Error');
        } else {
          toastNotifikasi("error", 'Kesalahan tidak diketahui: ' + jqXHR.responseText, 'Error');
        }
      }

      function loadingOverlay() {
        var loadingOverlayCustom = $("<div>", {
          "css": {
            "display": "flex",
            "flex-direction": "column",
            "text-align": "center",
          },
          "html": `
						<div>
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>
						</div>
						<div style="font-size:16px; margin-top:5px">
						<span>Mohon Tunggu...</span>
						</div>
						`,
        });

        $.LoadingOverlay("show", {
          image: "",
          custom: loadingOverlayCustom
        });
      }
    </script>

    <script>
      $(function() {
        let suspend_show = <?= isset($this->suspend_info['show']) ? json_encode($this->suspend_info['show']) : false; ?>;
        let suspend_location = <?= isset($this->suspend_info['location']) ? json_encode($this->suspend_info['location']) : '"content-wrapper"'; ?>;
        if (suspend_show) {
          let suspendUI = <?= isset($this->suspend_info['ui']) ? json_encode($this->suspend_info['ui']) : '""'; ?>;
          if (suspend_location === 'navbar') {
            if ($(".navbar-actions").length) {
              $(".navbar-actions").prepend(suspendUI);
            } else if ($(".navbar-custom-menu .navbar-nav").length) {
              $(".navbar-custom-menu .navbar-nav").prepend(suspendUI);
            }
          } else {
            if ($(".content-wrapper section.content").length) {
              $(".content-wrapper section.content").first().prepend(suspendUI);
            } else {
              $(".content-wrapper").prepend(suspendUI);
            }
          }
        }

        // Password reminder
        let pwd_reminder_show = <?= isset($this->password_reminder_info['show']) ? json_encode($this->password_reminder_info['show']) : 'false'; ?>;
        if (pwd_reminder_show) {
          let pwdReminderUI = <?= isset($this->password_reminder_info['ui']) ? json_encode($this->password_reminder_info['ui']) : '""'; ?>;
          if ($(".content-wrapper section.content").length) {
            $(".content-wrapper section.content").first().prepend(pwdReminderUI);
          } else {
            $(".content-wrapper").prepend(pwdReminderUI);
          }
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