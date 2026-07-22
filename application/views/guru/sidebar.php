<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <?php if (!empty(logo())) { ?>
          <img src="<?php echo upload_url('school/' . logo()) ?>" class="img-circle" alt="Logo" style="height:45px;" />
        <?php } else { ?>
          <img src="<?php echo media_url('img/logo.png') ?>" class="img-circle" alt="Logo" style="height:45px;" />
        <?php } ?>
      </div>
      <div class="pull-left info">
        <p>Guru</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
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

      <li class="treeview">
        <a href="#">
          <i class="fa fa-users"></i> <span>Kebiasaan Anak Hebat</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="">
            <a href="<?php echo site_url('guru/presensi_kebiasaan'); ?>"><i class="fa fa-circle-o"></i> Presensi Kebiasaan Anak</a>
          </li>
        </ul>
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
</aside>