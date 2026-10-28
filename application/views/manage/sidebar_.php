  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if ($this->session->userdata('user_image') != null) { ?>
          <img src="<?php echo upload_url().'/users/'.$this->session->userdata('user_image'); ?>" class="img-responsive">
          <?php } else { ?>
          <img src="<?php echo media_url() ?>img/user.png" class="img-responsive">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo ucfirst($this->session->userdata('ufullname')); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
        <div style="margin-top: 80px"></div>
        <div class="col-8">
            
            <?php
                $packages = $this->db->query("SELECT * FROM package WHERE package_status = 1")->result_array();
                $sett = $this->db->query("SELECT setting_value as paket FROM setting WHERE setting_name = 'setting_package'")->row_array();
                
                $paket = $sett['paket'];
            
            ?>
            <form>
              <select class="form-control" name="package" id="package" onchange="pilih_paket()">
                  <?php foreach ($packages as $package) : ?>
                  <option value="<?php echo $package['package_id'] ?>" <?= ($package['package_id'] == $paket) ? 'selected' : '' ?>><?php echo 'DEMO PAKET ' . $package['package_name'] ?></option>
                  <?php endforeach ?>
              </select>
            </form>
        </div>
      </div>

      <div style="margin-top: 20px"></div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
      <?php
      
        if($this->session->userdata('uroleid') != null ){
            $role_id = $this->session->userdata('uroleid');
            $sql_menu = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '0' ORDER BY menu_order ASC";
            $main_menu = $this->db->query($sql_menu)->result();
            
            foreach ($main_menu as $main) {
            
            $main_id = $main->menu_id;
            $sql_sub = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '$main_id' ORDER BY menu_order ASC";
            $sub_menu = $this->db->query($sql_sub)->result();
            
            if (count($sub_menu) > 0) {
      ?>
        <li class="treeview">
          <a href="<?php echo $main->menu_link ?>">
            <i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php
                foreach($sub_menu as $sub){
            
                $sub_id = $sub->menu_id;
                $sql_child = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '$sub_id' ORDER BY menu_order ASC";
                $child_menu = $this->db->query($sql_child)->result();
            
            if (count($child_menu) > 0) {
          ?>
            <li class="treeview">
              <a href="<?php echo $sub->menu_link ?>"><i class="<?php echo $sub->menu_icon ?>"></i> <?php echo $sub->menu_nama ?>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
              <?php foreach($child_menu as $child) {?>
                <li class="">
                  <a href="<?php echo base_url().$child->menu_link ?>"><i class="<?php echo $child->menu_icon ?>"></i> <?php echo $child->menu_nama ?></a>
                </li>
              <?php } ?>
              </ul>
            </li>
          <?php
                } else {
          ?>
          <li class="">
              <a href="<?php echo base_url().$sub->menu_link ?>"><i class="<?php echo $sub->menu_icon ?>"></i> <?php echo $sub->menu_nama ?></a>
            </li>
          <?php
                }
            }
          ?>
          </ul>
        </li>
        <?php 
                } else {
        ?>
        <li class="">
          <a href="<?php echo base_url().$main->menu_link; ?>">
            <i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama; ?></span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <?php
                }
            }
        }
        ?>
        <?php if ($this->session->userdata('uroleid') == 3) { ?>
        <li>
          <a href="<?php echo site_url('manage/registrasi'); ?>">
            <i class="fa fa-users"></i> <span>Data Demonstran</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <?php } ?>
        <li>
          <a href="<?php echo site_url('manage/auth/logout_pengganti_db_baru'); ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
            <span class="pull-right-container"></span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
