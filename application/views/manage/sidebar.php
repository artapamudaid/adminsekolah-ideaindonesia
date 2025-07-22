  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if ($this->session->userdata('user_image') != null) { ?>
            <img src="<?php echo upload_url() . '/users/' . $this->session->userdata('user_image'); ?>" class="img-responsive">
          <?php } else { ?>
            <img src="<?php echo media_url() ?>img/user.png" class="img-responsive">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo ucfirst($this->session->userdata('ufullname')); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>

        <div class="col-8">

          <?php
          $packages = $this->db->query("SELECT * FROM package WHERE package_status = 1")->result_array();
          $sett = $this->db->query("SELECT setting_value as paket FROM setting WHERE setting_name = 'setting_package'")->row_array();

          $paket = $sett['paket'];

          ?>
        </div>
      </div>

      <!-- Navmenu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header" style="margin-top: 0px; margin-bottom: 10px;">MENU</li>
        <?php

        // Menu Utama
        if ($this->session->userdata('uroleid') != null) {
          $role_id = $this->session->userdata('uroleid');
          $sql_menu = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '0' AND menu_status='0' ORDER BY menu_order ASC";
          $main_menu = $this->db->query($sql_menu)->result();
          $uriAktif = $this->uri->uri_string();

          foreach ($main_menu as $main) {

            $main_id = $main->menu_id;
            $sql_sub =
              "SELECT menu_id, menu_nama, menu_link, menu_icon, navmenu.menu_child, has_child.hasChildAktif 
                FROM navmenu 
                JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id 
                JOIN package ON menu_paket.package_package_id = package.package_id
                LEFT JOIN (
                  SELECT b.menu_id AS hasChildAktif, b.menu_child
                  FROM navmenu b 
                  WHERE b.menu_link = '$uriAktif' OR INSTR('$uriAktif', b.menu_link) > 0
                  ORDER BY LENGTH(b.menu_link) DESC
                  LIMIT 1
                ) AS has_child ON has_child.menu_child = navmenu.menu_id OR has_child.hasChildAktif = navmenu.menu_id
                WHERE package_id = '$paket' 
                AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) 
                AND (navmenu.menu_child = '$main_id') 
                AND menu_status='0' 
                ORDER BY menu_order ASC";
            $sub_menu = $this->db->query($sql_sub)->result();
            $mainAktif = '';
            $subMenuAktif = null;
            $subMenuPanjang = 0;
            if (count((array)$sub_menu) > 0) {
              foreach ($sub_menu as $sMenu) {
                if ($sMenu->menu_link === $uriAktif or $sMenu->hasChildAktif) {
                  // $mainAktif = $sMenu->menu_child;
                  // break; // keluar dari loop setelah ketemu
                  $panjang = strlen($sMenu->menu_link);
                  if ($panjang > $subMenuPanjang) {
                    $subMenuPanjang = $panjang;
                    $mainAktif = $sMenu->menu_child;
                    $subMenuAktif = $sMenu->menu_id;
                  }
                }
              }
              $mClassAktive = '';
              if ($main->menu_id == $mainAktif) {
                $mClassAktive = 'active';
              }

        ?>
              <!-- Main Menu yang punya child -->
              <li class="treeview <?php echo $mClassAktive ?>" style="margin-top: -10px; margin-bottom: -10px;">
                <a href="<?php echo $main->menu_link ?>">
                  <i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php
                  foreach ($sub_menu as $sub) {

                    $sub_id = $sub->menu_id;
                    $sql_child =
                      "SELECT menu_id, menu_nama, menu_link, menu_icon, menu_child 
                    FROM navmenu 
                    JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id 
                    JOIN package ON menu_paket.package_package_id = package.package_id 
                    WHERE package_id = '$paket' 
                      AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) 
                      AND menu_child = '$sub_id' AND menu_status='0' ORDER BY menu_order ASC";
                    $child_menu = $this->db->query($sql_child)->result();


                    if (count((array)$child_menu) > 0) {
                      // $subAktif = '';
                      $menuAktif = null;
                      $panjangTerpanjang = 0;
                      foreach ($child_menu as $cMenu) {
                        // if ($cMenu->menu_link === $uriAktif or strpos($cMenu->menu_link, $uriAktif) === 0) {
                        //   $subAktif = $cMenu->menu_child;
                        //   break; // keluar dari loop setelah ketemu
                        // }
                        if ($cMenu->menu_child == $subMenuAktif  and ($uriAktif === $cMenu->menu_link || strpos($uriAktif, $cMenu->menu_link) === 0)) {
                          $panjang = strlen($cMenu->menu_link);
                          if ($panjang > $panjangTerpanjang) {
                            $panjangTerpanjang = $panjang;
                            // $subAktif = $cMenu->menu_child;
                            $menuAktif = $cMenu->menu_id;
                          }
                        }
                      }
                      $classAktive = '';
                      // if ($uriAktif == $sub->menu_link or $subAktif == $sub_id) {
                      //   $classAktive = 'active';
                      // }
                      if ($subMenuAktif == $sub->menu_id) {
                        $classAktive = 'active';
                      }
                  ?>
                      <!-- Sub Menu yang punya child -->
                      <li class="treeview <?php echo $classAktive ?>" style="margin-top: -5px; margin-bottom: 0px;">
                        <a href="<?php echo $sub->menu_link ?>"><i class="<?php echo $sub->menu_icon ?>"></i> <?php echo $sub->menu_nama ?>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">
                          <?php foreach ($child_menu as $child) {
                            $classAktive = '';
                            // if ($uriAktif == $child->menu_link or strpos($uriAktif, $child->menu_link) === 0) {
                            //   $classAktive = 'active';
                            // }
                            if ($child->menu_id == $menuAktif) {
                              $classAktive = 'active';
                            }
                          ?>
                            <li class="<?php echo $classAktive ?>" style="margin-top: -5px; margin-bottom: -5px;">
                              <a href="<?php echo base_url() . $child->menu_link ?>"><i class="<?php echo $child->menu_icon ?>"></i> <?php echo $child->menu_nama ?></a>
                            </li>
                          <?php } ?>
                        </ul>
                      </li>
                    <?php
                    } else {
                      $nAktif = '';
                      // if ($uriAktif == $sub->menu_link or strpos($uriAktif, $sub->menu_link) === 0) {
                      //   // 'manage/student' ada di awal URI
                      //   $nAktif = 'active';
                      // }
                      if ($sub->menu_id == $subMenuAktif) {
                        $nAktif = 'active';
                      }
                    ?>
                      <!-- Sub Menu yang tidak punya child -->
                      <li class="<?php echo $nAktif ?>" style="margin-top: -5px; margin-bottom: 0px;">
                        <a href="<?php echo base_url() . $sub->menu_link ?>"><i class="<?php echo $sub->menu_icon ?>"></i> <?php echo $sub->menu_nama ?></a>
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
              <!-- Main Menu yang tidak punya child -->
              <li class="<?php echo $uriAktif == $main->menu_link ? 'active' : '' ?>" style="margin-top: -10px; margin-bottom: -10px;">
                <a href="<?php echo base_url() . $main->menu_link; ?>">
                  <i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama; ?></span>
                  <span class="pull-right-container"></span>
                </a>
              </li>
            <?php
            }
          }
        }
        // Menu Utama

        // Menu Add-Ons 
        if ($this->session->userdata('uroleid') != null) {
          $role_id = $this->session->userdata('uroleid');
          $sql_addon = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '0' AND menu_status='1' ORDER BY menu_order ASC";
          $main_addon = $this->db->query($sql_addon)->result();
          if (count((array)$main_addon) > 0) {
            ?>
            <li class="header" style="margin-top: 10px; margin-bottom: 10px;">ADD-ONS</li>
            <?php
          }
          foreach ($main_addon as $main) {

            $main_id = $main->menu_id;
            $sql_subaddon = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '$main_id' AND menu_status='1' ORDER BY menu_order ASC";
            $sub_menuaddon = $this->db->query($sql_subaddon)->result();

            if (count((array)$sub_menuaddon) > 0) {
            ?>
              <li class="treeview" style="margin-top: -10px; margin-bottom: -10px;">
                <a href="<?php echo $main->menu_link ?>">
                  <i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama ?></span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php
                  foreach ($sub_menuaddon as $sub) {

                    $sub_id = $sub->menu_id;
                    $sql_childaddon = "SELECT menu_id, menu_nama, menu_link, menu_icon FROM navmenu JOIN menu_paket ON menu_paket.navmenu_menu_id = navmenu.menu_id JOIN package ON menu_paket.package_package_id = package.package_id WHERE package_id = '$paket' AND menu_id IN(SELECT menu_id FROM hak_akses WHERE role_id=$role_id) AND menu_child = '$sub_id' AND menu_status='1' ORDER BY menu_order ASC";
                    $child_menuaddon = $this->db->query($sql_childaddon)->result();

                    if (count((array)$child_menuaddon) > 0) {
                  ?>
                      <li class="treeview" style="margin-top: -5px; margin-bottom: 0px;">
                        <a href="<?php echo $sub->menu_link ?>"><i class="<?php echo $sub->menu_icon ?>"></i> <?php echo $sub->menu_nama ?>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">
                          <?php foreach ($child_menuaddon as $child) { ?>
                            <li style="margin-top: -5px; margin-bottom: -5px;">
                              <a href="<?php echo base_url() . $child->menu_link ?>"><i class="<?php echo $child->menu_icon ?>"></i> <?php echo $child->menu_nama ?></a>
                            </li>
                          <?php } ?>
                        </ul>
                      </li>
                    <?php
                    } else {
                    ?>
                      <li style="margin-top: -5px; margin-bottom: 0px;">
                        <a href="<?php echo base_url() . $sub->menu_link ?>"><i class="<?php echo $sub->menu_icon ?>"></i> <?php echo $sub->menu_nama ?></a>
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
              <li style="margin-top: -10px; margin-bottom: -10px;">
                <?php
                if (strpos($main->menu_link, "https://") !== false) {
                ?>
                  <a href="<?php echo $main->menu_link ?>" target="_blank"><i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama ?></a></span>
                <?php } else { ?>
                  <a href="<?php echo base_url() . $main->menu_link ?>"><i class="<?php echo $main->menu_icon ?>"></i> <span><?php echo $main->menu_nama ?></a></span>
                <?php } ?>
              </li>
        <?php
            }
          }
        }
        ?>
        <!-- Menu Add-Ons -->
        <?php if ($this->session->userdata('uroleid') == 3) { ?>
          <li>
            <a href="<?php echo site_url('manage/package'); ?>">
              <i class="fa fa-star"></i> <span>Setting Modul Paket</span>
              <span class="pull-right-container"></span>
            </a>
          </li>
        <?php } ?>
        <li>
          <a href="<?php echo site_url('manage/auth/logout'); ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
            <span class="pull-right-container"></span>
          </a>
        </li>

      </ul>
      <!-- Navmenu -->
    </section>
    <!-- /.sidebar -->
  </aside>