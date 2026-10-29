<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU GURU</li>

      <li class="<?php echo ($this->uri->segment(2) == 'dashboard' or $this->uri->segment(2) == NULL) ? 'active' : '' ?>">
        <a href="<?php echo site_url('guru'); ?>">
          <i class="fa fa-th"></i> <span>Dashboard</span>
        </a>
      </li>

      <li class="<?php echo ($this->uri->segment(2) == 'jurnal') ? 'active' : '' ?>">
        <a href="<?php echo site_url('guru/jurnal'); ?>">
          <i class="fa fa-file-text-o"></i> <span>Jurnal</span>
        </a>
      </li>

      <li class="<?php echo ($this->uri->segment(2) == 'nilai') ? 'active' : '' ?>">
        <a href="<?php echo site_url('guru/nilai'); ?>">
          <i class="fa fa-file-o"></i> <span>Penilaian</span>
        </a>
      </li>

      <li class="<?php echo ($this->uri->segment(2) == 'report_jurnal') ? 'active' : '' ?>">
        <a href="<?php echo site_url('guru/report_jurnal'); ?>">
          <i class="fa fa-book"></i> <span>Laporan</span>
        </a>
      </li>

      <li class="<?php echo ($this->uri->segment(2) == 'izin_guru') ? 'active' : '' ?>">
        <a href="<?php echo site_url('guru/izin_guru'); ?>">
          <i class="fa fa-edit"></i> <span>Perizinan</span>
        </a>
      </li>

      <li class="<?php echo ($this->uri->segment(2) == 'profile') ? 'active' : '' ?>">
        <a href="<?php echo site_url('guru/profile'); ?>">
          <i class="fa fa-user"></i> <span>Setting</span>
        </a>
      </li>

      <?php if ($this->session->userdata('uiswali_guru') == 1): ?>
        <li class="header">WALI KELAS</li>

        <li class="<?php echo ($this->uri->segment(2) == 'presensi_guru') ? 'active' : '' ?>">
          <a href="<?php echo site_url('guru/presensi_guru'); ?>">
            <i class="fa fa-check"></i> <span>Presensi Kelas</span>
          </a>
        </li>

        <li class="<?php echo ($this->uri->segment(2) == 'catatan') ? 'active' : '' ?>">
          <a href="<?php echo site_url('guru/catatan'); ?>">
            <i class="fa fa-list"></i> <span>Catatan Wali Kelas</span>
          </a>
        </li>

        <li class="<?php echo ($this->uri->segment(2) == 'pelanggaran') ? 'active' : '' ?>">
          <a href="<?php echo site_url('guru/pelanggaran'); ?>">
            <i class="fa fa-ban"></i> <span>Pelanggaran Santri</span>
          </a>
        </li>
      <?php endif; ?>

      <li>
        <a href="<?php echo site_url('guru/auth/logout'); ?>">
          <i class="fa fa-sign-out"></i> <span>Logout</span>
        </a>
      </li>
    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->

  <!-- Sidebar User Profile Card (Sticky Bottom Docked) -->
  <div class="sidebar-profile-card">
    <div class="sidebar-avatar-wrapper">
      <?php if ($this->session->userdata('employees_img') != null) { ?>
        <img src="<?php echo upload_url() . '/student/' . $this->session->userdata('employees_img'); ?>" class="sidebar-avatar-img" alt="User Avatar">
      <?php } else { ?>
        <img src="<?php echo media_url('img/user.png'); ?>" class="sidebar-avatar-img" alt="User Avatar">
      <?php } ?>
      <span class="avatar-status-dot"></span>
    </div>
    <div class="sidebar-profile-info">
      <p class="sidebar-profile-name"><?php echo ucfirst($this->session->userdata('ufullname_guru')); ?></p>
      <span class="sidebar-profile-role"><?php echo ($this->session->userdata('uiswali_guru') == 1) ? 'Wali Kelas' : 'Guru / Pengajar'; ?></span>
    </div>
    <a href="<?php echo site_url('guru/auth/logout?location=' . htmlspecialchars($_SERVER['REQUEST_URI'])); ?>" class="sidebar-profile-logout" title="Logout">
      <i class="fa fa-sign-out"></i>
    </a>
  </div>
</aside>