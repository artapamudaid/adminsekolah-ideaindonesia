<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Package_set extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('logged') == NULL) {
        header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
    }
       
        $this->load->model(array('package/Package_model','student/Student_model'));
        $this->load->helper(array('form', 'url'));
    }

     // User_customer view in list
    
    function index(){
        $data['package'] = $this->Package_model->get(array('status' => 1));
        $data['title'] = 'Pengaturan Modul/Menu';
        $data['main'] = 'package/package_menu';
        $this->load->view('manage/layout', $data);   
    }
    
    function modul(){
        $package_id = $_GET['package_id'];
        echo "<table class='table table-hover table-responsive'>
                    <tr>
                        <th width='10'>No</th>
                        <th>Nama Modul</th>
                        <th>Keterangan</th>
                        <th width='100'>Aksi</th>
                    </tr>";
            $sql_menu = "SELECT * FROM navmenu WHERE menu_child = '0' ORDER BY menu_order ASC";
            $main_menu = $this->db->query($sql_menu)->result();
            $no = 1;
            foreach ($main_menu as $main) {
            
            $main_id = $main->menu_id;
            $sql_sub = "SELECT * FROM navmenu WHERE menu_child = '$main_id' ORDER BY menu_order ASC";
            $sub_menu = $this->db->query($sql_sub)->result();
            
            if (count($sub_menu) > 0) {
                
        echo "<tr>
          <td>".$no++."</td>
          <td>".$main->menu_nama."</td>
          <td>Main Menu</td>
          <td align='center'><input type='checkbox' ";
            $this->cek_akses($package_id, $main->menu_id);
             echo " onclick='addModul($main->menu_id)'></td>
          </tr>";
          
            foreach($sub_menu as $sub){
        
            $sub_id = $sub->menu_id;
            $sql_child = "SELECT * FROM navmenu WHERE menu_child = '$sub_id' ORDER BY menu_order ASC";
            $child_menu = $this->db->query($sql_child)->result();
            
            if (count($child_menu) > 0) {
            echo "<tr>
                <td>".$no++."</td>
                <td>".$sub->menu_nama."</td>
                <td>Sub Menu 1</td>
                <td align='center'><input type='checkbox' ";
                    $this->cek_akses($package_id, $sub->menu_id);
                    echo " onclick='addModul($sub->menu_id)'></td>
            </tr>";
            
            foreach($child_menu as $child) {
                echo "<tr>
                    <td>".$no++."</td>
                    <td>".$child->menu_nama."</td>
                    <td>Sub Menu 2</td>
                    <td align='center'><input type='checkbox' ";
                    $this->cek_akses($package_id, $child->menu_id);
                    echo " onclick='addModul($child->menu_id)'></td>
                </tr>";
            }
                } else {
          echo "<tr>
                    <td>".$no++."</td>
                    <td>".$sub->menu_nama."</td>
                    <td>Sub Menu 1</td>
                    <td align='center'><input type='checkbox' ";
                    $this->cek_akses($package_id, $sub->menu_id);
                    echo " onclick='addModul($sub->menu_id)'></td>
                </tr>";
                }
            }
                } else {
        echo "<tr>
                <td>".$no++."</td>
                <td>".$main->menu_nama."</td>
                <td>Main Menu</td>
                <td align='center'><input type='checkbox' ";
                $this->cek_akses($package_id, $main->menu_id);
                echo " onclick='addModul($main->menu_id)'></td>
            </tr>";
                }
            }
        echo "</table>";
    }
    
    function cek_akses($package_id,$menu_id){
        $data = array('package_package_id'=>$package_id,'navmenu_menu_id'=>$menu_id);
        $cek = $this->db->get_where('menu_paket',$data);
        if($cek->num_rows()>0){
            echo "checked";
        }
    }

    function addModul(){
        $package_id    = $_GET['package_id'];
        $menu_id    = $_GET['menu_id'];
        $data       = array('package_package_id'=>$package_id,'navmenu_menu_id'=>$menu_id);
        $cek       = $this->db->get_where('menu_paket',$data);
        if($cek->num_rows()<1){
            $this->db->insert('menu_paket',$data);
            echo "alert('berhasil memberikan akses modul')";
        }else{
            $this->db->where('navmenu_menu_id',$menu_id);
            $this->db->where('package_package_id',$package_id);
            $this->db->delete('menu_paket');
            echo "alert('berhasil menghapus akses modul')";
        }
    }

}
