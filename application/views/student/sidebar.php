  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if ($this->session->userdata('student_img') != null) { ?>
          <img src="<?php echo upload_url().'/student/'.$this->session->userdata('student_img'); ?>" class="img-responsive">
          <?php } else { ?>
          <img src="<?php echo media_url() ?>img/user.png" class="img-responsive">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo ucfirst($this->session->userdata('ufullname_student')); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <div style="margin-top: 20px"></div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>

        <li class="<?php echo ($this->uri->segment(2) == 'dashboard' OR $this->uri->segment(2) == NULL) ? 'active' : '' ?>">
          <a href="<?php echo site_url('student'); ?>">
            <i class="fa fa-th"></i> <span>Dashboard</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-rss text-stock"></i> <span>Layanan Surat</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="">
                <a href="<?php echo site_url('student/siswa_ket_aktif'); ?>"><i class="fa fa-circle-o"></i> Surat Ket. Siswa Aktif</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/dispensasi'); ?>"><i class="fa fa-circle-o"></i> Surat Dispensasi Belajar</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/siswa_ket_baik'); ?>"><i class="fa fa-circle-o"></i> Surat Kelakuan Baik</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/siswa_rekomendasi'); ?>"><i class="fa fa-circle-o"></i> Surat Rekomendasi</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/siswa_mutasi'); ?>"><i class="fa fa-circle-o"></i> Surat Mutasi Siswa Keluar</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/siswa_penelitian'); ?>"><i class="fa fa-circle-o"></i> Surat Izin Penilitian</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/siswa_prestasi'); ?>"><i class="fa fa-circle-o"></i> Surat Ket. Berprestasi</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('student/siswa_surat_lain'); ?>"><i class="fa fa-circle-o"></i> Surat Lainnya</a>
              </li>
          </ul>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'barang_siswa' OR $this->uri->segment(2) == 'barang_siswa') ? 'active' : '' ?>">
          <a href="<?php echo site_url('student/barang_siswa'); ?>">
            <i class="fa fa-cubes"></i> <span>Permintaan Barang</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'lainnya_siswa' OR $this->uri->segment(2) == 'lainnya_siswa') ? 'active' : '' ?>">
          <a href="<?php echo site_url('student/lainnya_siswa'); ?>">
            <i class="fa fa-book"></i> <span>Lainnya</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'profile' OR $this->uri->segment(2) == 'profile') ? 'active' : '' ?>">
          <a href="<?php echo site_url('student/profile'); ?>">
            <i class="fa fa-user"></i> <span>Profil</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('student/auth/logout'); ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
            <span class="pull-right-container"></span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>