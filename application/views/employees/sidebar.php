  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if ($this->session->userdata('employees_img') != null) { ?>
          <img src="<?php echo upload_url().'/employees/'.$this->session->userdata('employees_img'); ?>" class="img-responsive">
          <?php } else { ?>
          <img src="<?php echo media_url() ?>img/user.png" class="img-responsive">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo ucfirst($this->session->userdata('ufullname_employees')); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <div style="margin-top: 20px"></div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>

        <li class="<?php echo ($this->uri->segment(2) == 'dashboard' OR $this->uri->segment(2) == NULL) ? 'active' : '' ?>">
          <a href="<?php echo site_url('employees'); ?>">
            <i class="fa fa-th"></i> <span>Dashboard</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li class="treeview <?php echo ($this->uri->segment(2) == 'surat_tugas' OR $this->uri->segment(2) == 'sppd' OR $this->uri->segment(2) == 'surat_mengajar' OR $this->uri->segment(2) == 'surat_cuti' OR $this->uri->segment(2) == 'surat_lain_guru') ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-rss text-stock"></i> <span>Layanan Surat</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="">
                <a href="<?php echo site_url('employees/surat_tugas'); ?>"><i class="fa fa-circle-o"></i> Surat Tugas</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('employees/sppd'); ?>"><i class="fa fa-circle-o"></i> Surat Perjalanan Dinas (SPD)</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('employees/surat_mengajar'); ?>"><i class="fa fa-circle-o"></i> Surat Ket. Mengajar</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('employees/surat_cuti'); ?>"><i class="fa fa-circle-o"></i> Surat Cuti</a>
              </li>
              <li class="">
                <a href="<?php echo site_url('employees/surat_lain_guru'); ?>"><i class="fa fa-circle-o"></i> Surat Lainnya</a>
              </li>
          </ul>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'barang_guru') ? 'active' : '' ?>">
          <a href="<?php echo site_url('employees/barang_guru'); ?>">
            <i class="fa fa-cubes"></i> <span>Permintaan Barang</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'lainnya_guru') ? 'active' : '' ?>">
          <a href="<?php echo site_url('employees/lainnya_guru'); ?>">
            <i class="fa fa-book"></i> <span>Lainnya</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'profile') ? 'active' : '' ?>">
          <a href="<?php echo site_url('employees/profile'); ?>">
            <i class="fa fa-user"></i> <span>Profil</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('employees/auth/logout'); ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
            <span class="pull-right-container"></span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>