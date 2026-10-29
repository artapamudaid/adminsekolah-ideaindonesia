<?php
$user_image    = $this->session->userdata('user_image');
$user_fullname = $this->session->userdata('ufullname');
$role_id       = $this->session->userdata('uroleid');
$paket         = 3;

$menus_by_parent = array(0 => array(), 1 => array());
$active_ids      = array();

$build_url = function($link) {
    if (empty($link) || $link === '#') {
        return '#';
    }
    if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0) {
        return $link;
    }
    return base_url($link);
};

if ($role_id !== null) {
    // 1 single query to fetch all accessible menus for this role & package
    $sql = "SELECT DISTINCT 
                n.menu_id, 
                n.menu_nama, 
                n.menu_link, 
                n.menu_icon, 
                n.menu_child, 
                n.menu_order, 
                n.menu_status
            FROM navmenu n
            JOIN menu_paket mp ON mp.navmenu_menu_id = n.menu_id
            JOIN package p ON mp.package_package_id = p.package_id
            WHERE p.package_id = ?
              AND n.menu_id IN (SELECT menu_id FROM hak_akses WHERE role_id = ?)
            ORDER BY n.menu_order ASC, n.menu_id ASC";

    $menus = $this->db->query($sql, array($paket, $role_id))->result();
    $menu_by_id = array();

    foreach ($menus as $m) {
        $menu_by_id[$m->menu_id] = $m;
        $status = ($m->menu_status == 1) ? 1 : 0;
        $menus_by_parent[$status][$m->menu_child][] = $m;
    }

    // Determine active menu based on longest matching link with current URI
    $uriAktif = $this->uri->uri_string();
    $best_match_id = null;
    $best_match_len = 0;

    foreach ($menus as $m) {
        if (empty($m->menu_link) || $m->menu_link === '#') {
            continue;
        }
        if ($uriAktif === $m->menu_link || strpos($uriAktif . '/', $m->menu_link . '/') === 0) {
            $len = strlen($m->menu_link);
            if ($len > $best_match_len) {
                $best_match_len = $len;
                $best_match_id = $m->menu_id;
            }
        }
    }

    // Trace active ancestors (leaf -> parent -> grandparent)
    $curr = $best_match_id;
    while ($curr && isset($menu_by_id[$curr])) {
        $active_ids[$curr] = true;
        $curr = $menu_by_id[$curr]->menu_child;
    }
}

// Reusable menu renderer
$render_menu_section = function($status, $header_title, $header_style) use ($menus_by_parent, $active_ids, $build_url) {
    if (empty($menus_by_parent[$status][0])) {
        return;
    }
    ?>
    <li class="header" style="<?php echo $header_style; ?>"><?php echo $header_title; ?></li>
    <?php
    foreach ($menus_by_parent[$status][0] as $main) {
        $main_id          = $main->menu_id;
        $has_sub          = !empty($menus_by_parent[$status][$main_id]);
        $main_active      = isset($active_ids[$main_id]) ? 'active' : '';
        $main_url         = $build_url($main->menu_link);
        $main_is_external = (strpos($main->menu_link, 'http://') === 0 || strpos($main->menu_link, 'https://') === 0);
        $main_target      = $main_is_external ? ' target="_blank" rel="noopener noreferrer"' : '';

        if ($has_sub) { ?>
            <li class="treeview <?php echo $main_active; ?>" style="margin-top: -10px; margin-bottom: -10px;">
                <a href="<?php echo $main_url; ?>">
                    <i class="<?php echo $main->menu_icon; ?>"></i> <span><?php echo $main->menu_nama; ?></span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php foreach ($menus_by_parent[$status][$main_id] as $sub) {
                        $sub_id          = $sub->menu_id;
                        $has_child       = !empty($menus_by_parent[$status][$sub_id]);
                        $sub_active      = isset($active_ids[$sub_id]) ? 'active' : '';
                        $sub_url         = $build_url($sub->menu_link);
                        $sub_is_external = (strpos($sub->menu_link, 'http://') === 0 || strpos($sub->menu_link, 'https://') === 0);
                        $sub_target      = $sub_is_external ? ' target="_blank" rel="noopener noreferrer"' : '';

                        if ($has_child) { ?>
                            <li class="treeview <?php echo $sub_active; ?>" style="margin-top: -5px; margin-bottom: 0px;">
                                <a href="<?php echo $sub_url; ?>">
                                    <i class="<?php echo $sub->menu_icon; ?>"></i> <?php echo $sub->menu_nama; ?>
                                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php foreach ($menus_by_parent[$status][$sub_id] as $child) {
                                        $child_active      = isset($active_ids[$child->menu_id]) ? 'active' : '';
                                        $child_url         = $build_url($child->menu_link);
                                        $child_is_external = (strpos($child->menu_link, 'http://') === 0 || strpos($child->menu_link, 'https://') === 0);
                                        $child_target      = $child_is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
                                    ?>
                                        <li class="<?php echo $child_active; ?>" style="margin-top: -5px; margin-bottom: -5px;">
                                            <a href="<?php echo $child_url; ?>"<?php echo $child_target; ?>>
                                                <i class="<?php echo $child->menu_icon; ?>"></i> <?php echo $child->menu_nama; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <li class="<?php echo $sub_active; ?>" style="margin-top: -5px; margin-bottom: 0px;">
                                <a href="<?php echo $sub_url; ?>"<?php echo $sub_target; ?>>
                                    <i class="<?php echo $sub->menu_icon; ?>"></i> <?php echo $sub->menu_nama; ?>
                                </a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </li>
        <?php } else { ?>
            <li class="<?php echo $main_active; ?>" style="margin-top: -10px; margin-bottom: -10px;">
                <a href="<?php echo $main_url; ?>"<?php echo $main_target; ?>>
                    <i class="<?php echo $main->menu_icon; ?>"></i> <span><?php echo $main->menu_nama; ?></span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
        <?php }
    }
};
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php if (!empty($user_image)) { ?>
            <img src="<?php echo upload_url('users/' . $user_image); ?>" class="img-responsive">
          <?php } else { ?>
            <img src="<?php echo media_url('img/user.png'); ?>" class="img-responsive">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo ucfirst($user_fullname); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <div style="margin-top: 20px"></div>
      <!-- Navmenu -->
      <ul class="sidebar-menu" data-widget="tree">
        <?php
        // Render Menu Utama (status 0)
        $render_menu_section(0, 'MENU', 'margin-top: 0px; margin-bottom: 10px;');

        // Render Menu Add-Ons (status 1)
        $render_menu_section(1, 'ADD-ONS', 'margin-top: 10px; margin-bottom: 10px;');
        ?>

        <?php if ($role_id == 3) { ?>
          <li>
            <a href="<?php echo site_url('manage/registrasi'); ?>">
              <i class="fa fa-users"></i> <span>Data Demonstran</span>
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