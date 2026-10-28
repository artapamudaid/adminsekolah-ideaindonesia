<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged') == NULL) {
            redirect('manage/auth/login?location=' . urlencode($_SERVER['REQUEST_URI']));
        }

        $this->load->model(array('users/Users_model', 'student/Student_model'));
        $this->load->helper(array('form', 'url'));
    }

    // User_customer view in list
    public function index($offset = NULL)
    {
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();
        // Nip
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['search'] = $f['n'];
        }

        $role_id = $this->session->userdata('uroleid');

        if ($role_id != '3') {
            $params['role_id'] = '3';
        }
        // $data['user'] = $this->Users_model->get($params);

        // $data['majors'] = $this->Student_model->get_majors();
        $data['param'] = [];
        $data['title'] = 'Pengguna';
        $data['main'] = 'users/user_list';
        $this->load->view('manage/layout', $data);
    }

    function datatablesUsers()
    {
        $param = $this->input->post('param', TRUE);

        $draw = (int) $this->input->get_post('draw');
        $length = (int) $this->input->get_post('length');
        $start = (int) $this->input->get_post('start');
        $searchParam = $this->input->get_post('search', TRUE);
        $search = isset($searchParam['value']) ? $searchParam['value'] : '';

        $total = $this->Users_model->count_all(array());
        $output = array();
        $output['draw']   = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        if ($search != "") {
            $query = $this->Users_model->list_users($length, $start, $search);
            $output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
        } else {
            $query = $this->Users_model->list_users($length, $start, $search);
        }

        $nomor_urut = $start + 1;
        foreach ($query->result_array() as $dt) {
            $unit = $dt['user_majors_id'] == '0' ? 'Semua Unit Sekolah' : $dt['majors_short_name'];

            // Tombol Aksi
            $btnAksi = '<a href="' . site_url('manage/users/view/' . $dt['user_id']) . '" class="btn btn-xs btn-info" data-toggle="tooltip" title="Lihat"><i class="fa fa-eye"></i></a> ';

            if ($this->session->userdata('uid') != $dt['user_id']) {
                $btnAksi .= '<a href="' . site_url('manage/users/rpw/' . $dt['user_id']) . '" class="btn btn-xs btn-warning"><i class="fa fa-lock" data-toggle="tooltip" title="Reset Password"></i></a> ';
            } else {
                $btnAksi .= '<a href="' . site_url('manage/profile/cpw/') . '" class="btn btn-xs btn-warning"><i class="fa fa-rotate-left" data-toggle="tooltip" title="Ubah Password"></i></a> ';
            }


            // Nanti tambahkan agar role superadmin dan dan user ts tidak bisa dihapus
            if ($dt['user_id'] != $this->session->userdata('uid')) {
                $btnAksi .= '<button data-user="' . $dt['user_id'] . '" data-nama="' . $dt['user_full_name'] . '" class="btnHapusUser btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus"></i></button>';
            }

            $output['data'][] = array(
                $nomor_urut,
                $dt['user_email'],
                $dt['user_full_name'],
                $dt['role_name'],
                $unit,
                $btnAksi
            );
            $nomor_urut++;
        }

        echo json_encode($output);
    }

    // Add User and Update
    public function add($id = NULL)
    {
        if (!in_array($this->session->userdata('uroleid'), array('1', '3'), TRUE)) {
            redirect('manage/users');
        }
        $this->load->library('form_validation');

        if (!$this->input->post('user_id')) {
            $this->form_validation->set_rules(
                'user_password',
                'Password',
                array(
                    'trim',
                    'required',
                    'xss_clean',
                    array(
                        'complex_password',
                        function ($str) {
                            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$&])[a-zA-Z\d@#$&]{12,}$/', $str)) {
                                $this->form_validation->set_message('complex_password', 'Password minimal 12 karakter, mengandung huruf besar, kecil, angka, dan karakter khusus (@#$&).');
                                return FALSE;
                            }
                            return TRUE;
                        }
                    )
                )
            );
            $this->form_validation->set_rules('passconf', 'Konfirmasi password', 'trim|required|xss_clean|matches[user_password]');
            $this->form_validation->set_rules('user_email', 'Email', 'trim|required|xss_clean|is_unique[users.user_email]');
            $this->form_validation->set_message('passconf', 'Password dan konfirmasi password tidak cocok');
        }
        $this->form_validation->set_rules('role_id', 'Peran', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_full_name', 'Nama lengkap', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button position="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST and $this->form_validation->run() == TRUE) {

            if ($this->input->post('user_id')) {
                $params['user_id'] = (int) $id;
            } else {
                $params['user_input_date'] = date('Y-m-d H:i:s');
                $params['user_email'] = $this->input->post('user_email', TRUE);
                $params['user_password'] = md5($this->input->post('user_password'));
                $params['user_password_last_update'] = date('Y-m-d H:i:s');
            }
            $params['user_email'] = $this->input->post('user_email', TRUE);
            $params['user_role_role_id'] = (int) $this->input->post('role_id');
            $params['user_majors_id'] = (int) $this->input->post('majors_id');
            $params['user_last_update'] = date('Y-m-d H:i:s');
            $params['user_full_name'] = $this->input->post('user_full_name', TRUE);
            $params['user_description'] = $this->input->post('user_description', TRUE);
            $params['user_last_update'] = date('Y-m-d H:i:s');
            $status = $this->Users_model->add($params);

            if (!empty($_FILES['user_image']['name'])) {
                $paramsupdate['user_image'] = $this->do_upload($name = 'user_image', $fileName = $params['user_full_name']);
            }

            $paramsupdate['user_id'] = $status;
            $this->Users_model->add($paramsupdate);

            // activity log
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'user',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:' . $status . ';Name:' . $this->input->post('user_full_name')
                )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' Pengguna Berhasil');
            redirect('manage/users');
        } else {
            if ($this->input->post('user_id')) {
                redirect('manage/users/edit/' . (int) $this->input->post('user_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $object = $this->Users_model->get(array('id' => (int) $id));
                if ($object == NULL) {
                    redirect('manage/users');
                } else {
                    $data['user'] = $object;
                }
            }
            $data['roles'] = $this->Users_model->get_role();
            $data['majors'] = $this->Student_model->get_majors();
            $data['title'] = $data['operation'] . ' Pengguna';
            $data['main'] = 'users/user_add';
            $this->load->view('manage/layout', $data);
        }
    }

    // View data detail
    public function view($id = NULL)
    {
        $data['user'] = $this->Users_model->get(array('id' => (int) $id));
        $data['title'] = 'Pengguna';
        $data['main'] = 'users/user_view';
        $this->load->view('manage/layout', $data);
    }

    // Delete to database
    public function delete()
    {
        if (!in_array($this->session->userdata('uroleid'), array('1', '3'), TRUE)) {
            redirect('manage/users');
        }
        $idUser = (int) $this->input->post('delUser');

        if ($_POST) {
            $this->Users_model->delete($idUser);
            // activity log
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'user',
                    'log_action' => 'Hapus',
                    'log_info' => 'ID:' . $idUser . ';Nama:' . $this->input->post('delName')
                )
            );
            $this->session->set_flashdata('success', 'Hapus Pengguna berhasil');
            redirect('manage/users');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('manage/users/edit/' . $idUser);
        }
    }

    function role()
    {
        if (!in_array($this->session->userdata('uroleid'), array('1', '3'), TRUE)) {
            redirect('manage/users');
        }
        $data['roles'] = $this->Users_model->get_role();
        $data['title'] = 'Manajemen Pengguna';
        $data['main'] = 'users/user_role';
        $this->load->view('manage/layout', $data);
    }

    function modul()
    {
        if (!in_array($this->session->userdata('uroleid'), array('1', '3'), TRUE)) {
            redirect('manage/users');
        }
        $role_id = (int) $this->input->get('role_id');
        echo "<table class='table table-hover table-responsive'>
                    <tr>
                        <th width='10'>No</th>
                        <th>Nama Modul</th>
                        <th>Keterangan</th>
                        <th width='100'>Aksi</th>
                    </tr>";
        $main_menu = $this->db->where('menu_child', '0')->order_by('menu_order', 'ASC')->get('navmenu')->result();
        $no = 1;
        foreach ($main_menu as $main) {

            $main_id = (int) $main->menu_id;
            $sub_menu = $this->db->where('menu_child', $main_id)->order_by('menu_order', 'ASC')->get('navmenu')->result();

            if (count($sub_menu) > 0) {

                echo "<tr>
          <td>" . $no++ . "</td>
          <td>" . $main->menu_nama . "</td>
          <td>Main Menu</td>
          <td align='center'><input type='checkbox' ";
                $this->cek_akses($role_id, $main->menu_id);
                echo " onclick='addRule($main->menu_id)'></td>
          </tr>";

                foreach ($sub_menu as $sub) {

                    $sub_id = (int) $sub->menu_id;
                    $child_menu = $this->db->where('menu_child', $sub_id)->order_by('menu_order', 'ASC')->get('navmenu')->result();

                    if (count($child_menu) > 0) {
                        echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $sub->menu_nama . "</td>
                <td>Sub Menu 1</td>
                <td align='center'><input type='checkbox' ";
                        $this->cek_akses($role_id, $sub->menu_id);
                        echo " onclick='addRule($sub->menu_id)'></td>
            </tr>";

                        foreach ($child_menu as $child) {
                            echo "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . $child->menu_nama . "</td>
                    <td>Sub Menu 2</td>
                    <td align='center'><input type='checkbox' ";
                            $this->cek_akses($role_id, $child->menu_id);
                            echo " onclick='addRule($child->menu_id)'></td>
                </tr>";
                        }
                    } else {
                        echo "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . $sub->menu_nama . "</td>
                    <td>Sub Menu 1</td>
                    <td align='center'><input type='checkbox' ";
                        $this->cek_akses($role_id, $sub->menu_id);
                        echo " onclick='addRule($sub->menu_id)'></td>
                </tr>";
                    }
                }
            } else {
                echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $main->menu_nama . "</td>
                <td>Main Menu</td>
                <td align='center'><input type='checkbox' ";
                $this->cek_akses($role_id, $main->menu_id);
                echo " onclick='addRule($main->menu_id)'></td>
            </tr>";
            }
        }
        echo "</table>";
    }

    function cek_akses($role_id, $menu_id)
    {
        $data = array('role_id' => (int) $role_id, 'menu_id' => (int) $menu_id);
        $cek = $this->db->get_where('hak_akses', $data);
        if ($cek->num_rows() > 0) {
            echo "checked";
        }
    }

    function addrule()
    {
        if (!in_array($this->session->userdata('uroleid'), array('1', '3'), TRUE)) {
            redirect('manage/users');
        }
        $role_id = (int) $this->input->get('role_id');
        $menu_id = (int) $this->input->get('menu_id');
        $data       = array('role_id' => $role_id, 'menu_id' => $menu_id);
        $cek       = $this->db->get_where('hak_akses', $data);
        if ($cek->num_rows() < 1) {
            $this->db->insert('hak_akses', $data);
            echo "alert('berhasil memberikan akses modul')";
        } else {
            $this->db->where('menu_id', $menu_id);
            $this->db->where('role_id', $role_id);
            $this->db->delete('hak_akses');
            echo "alert('berhasil menghapus akses modul')";
        }
    }

    // Setting Upload File Requied
    function do_upload($name = NULL, $fileName = NULL)
    {
        $this->load->library('upload');

        $config['upload_path'] = FCPATH . 'uploads/users/';

        /* create directory if not exist */
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, TRUE);
        }

        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '10240';
        $fileName = preg_replace("/[^A-Za-z0-9_.-]/", "_", (string) $fileName);
        $config['file_name'] = base64_encode($fileName);
        $config["file_ext_tolower"] = TRUE;
        $config["remove_spaces"] = TRUE;
        $config["detect_mime"] = TRUE;
        $config["mod_mime_fix"] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($name)) {
            $this->session->set_flashdata('success', $this->upload->display_errors('', ''));
            redirect(uri_string());
        }

        $upload_data = $this->upload->data();

        return $upload_data['file_name'];
    }


    function rpw($id = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'user_password',
            'Password',
            array(
                'trim',
                'required',
                'xss_clean',
                array(
                    'complex_password',
                    function ($str) {
                        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/', $str)) {
                            $this->form_validation->set_message('complex_password', 'Password minimal 12 karakter, mengandung huruf besar, kecil, angka, dan karakter khusus.');
                            return FALSE;
                        }
                        return TRUE;
                    }
                )
            )
        );
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|xss_clean|matches[user_password]');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        if ($_POST and $this->form_validation->run() == TRUE) {
            $id = $this->input->post('user_id');
            $params['user_password'] = md5($this->input->post('user_password'));
            $params['user_password_last_update'] = date('Y-m-d H:i:s');
            $status = $this->Users_model->change_password($id, $params);

            // activity log
            $this->load->model('logs/Logs_model');
            $this->Logs_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('uid'),
                    'log_module' => 'Pengguna',
                    'log_action' => 'Reset Password',
                    'log_info' => 'ID:null;Title:' . $this->input->post('user_nik')
                )
            );
            $this->session->set_flashdata('success', 'Reset Password Berhasil');
            redirect('manage/users');
        } else {
            if ($this->Users_model->get(array('id' => $id)) == NULL) {
                redirect('manage/users');
            }
            $data['user'] = $this->Users_model->get(array('id' => $id));
            $data['title'] = 'Reset Password';
            $data['main'] = 'users/change_pass';
            $this->load->view('manage/layout', $data);
        }
    }
}
