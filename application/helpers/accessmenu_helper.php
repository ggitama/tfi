<?php

function __construct()
{
    $ci = get_instance();
    $ci->load->model('User_model');
}

function access_login()
{
    $ci = get_instance();
    $session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
    // var_dump($ci->session->userdata($session_token));die;

    if (!$ci->session->userdata($session_token)) {
        // $ci->session->sess_destroy();
        redirect('Login_c/logout');
    } else {
        $sessions = $ci->session->userdata($session_token);
        $ippdr = $sessions['ipaddr'];
        $date_in = $sessions['date_in'];
        $log_activity_user2 = $ci->db->query("SELECT * FROM log_activity WHERE username = '$sessions[username]' AND date_out IS NULL ORDER BY id DESC LIMIT 1")->row();
        $check_log = $ippdr == $log_activity_user2->ipaddr && $date_in ==  $log_activity_user2->date_in;
        if (!$check_log) {
            redirect('Login_c/logout');
        } else {
            $session = $ci->session->userdata($session_token);
            $get_data_user = $ci->db->get_where("tb_user_transmart", ['username' => $session['username']])->row();
            $access_level = $get_data_user->role_id;

            $last = $ci->uri->total_segments();
            $record_num = '';
            for ($i = 1; $i <= $last; $i++) {
                $record_num .= $ci->uri->segment($i) . "/";
            }

            if($get_data_user){
                $menu_query = $ci->db->get_where('tb_menu_transmart', ['file' => $record_num])->row_array();
                // var_dump($menu_query);die;
                if ($menu_query) {
                    $menu_id = $menu_query['id_menu'];
                    $userAccess_view = $ci->db->get_where('tb_access_role_menu', [
                        'id_role' => $access_level,
                        'id_menu' => $menu_id,
                        'view' => '1'
                    ]);
                    // var_dump($userAccess_view->num_rows());die;
    
                    // action cek acess view
                    
                    if ($userAccess_view->num_rows() < 1) {
                        redirect('Dashboard_performance_c/');
                    }
                }
            }else{
                redirect('Login_c/logout');
            }
        }
    }
}

function data_session($session_token)
{
    $ci = get_instance();
    return $ci->session->userdata($session_token);
}


function menus($jabatan)
{
    $ci = get_instance();
    $menus = $ci->User_model->menu($jabatan);
    return $ci->User_model->html_menu($menus);
}


function access_crud($kode_id)
{
    $ci = get_instance();
    $access_level = $ci->session->userdata('jabatan');

    $last = $ci->uri->total_segments();
    $record_num = '';
    for ($i = 1; $i <= $last; $i++) {
        $record_num .= $ci->uri->segment($i) . "/";
    }

    $menu_query = $ci->db->get_where('menu', ['file' => $record_num])->row_array();
    if ($menu_query) {
        $menu_id = $menu_query['id_menu'];
        $userAccess_view = $ci->db->get_where('level_detail', [
            'id_level' => $access_level,
            'id_menu' => $menu_id,
            'tampil' => '1'
        ]);
        $userAccess_add = $ci->db->get_where('level_detail', [
            'id_level' => $access_level,
            'id_menu' => $menu_id,
            'addm' => '1'
        ]);
        $userAccess_edit = $ci->db->get_where('level_detail', [
            'id_level' => $access_level,
            'id_menu' => $menu_id,
            'edit' => '1'
        ]);
        $userAccess_delete = $ci->db->get_where('level_detail', [
            'id_level' => $access_level,
            'id_menu' => $menu_id,
            'del' => '1'
        ]);

        // action cek acess view
        if ($userAccess_view->num_rows() < 1) {
            redirect('Home_c');
        }

        $data_access = [
            'access_add' => '',
            'access_edit' =>  [
                'btn' => '',
                'visible' => ''
            ],
            'access_delete' => [
                'btn' => '',
                'visible' => ''
            ],
        ];

        // action cek acess create
        if ($userAccess_add->num_rows() > 0) {
            // $data_access['access_add'] = '1';
            $data_access['access_add'] = '<button class="btn btn-sm btn-primary" id="add">ADD</button>';
        }

        // action cek edit
        if ($userAccess_edit->num_rows() > 0) {
            // $data_access['access_edit'] = '1';
            $kode_ = $kode_id;
            $kode = "'+data." . $kode_ . "+'";
            $data_access['access_edit']['btn'] = '<button id="btn-edits" value="' . $kode . '" class="btn btn-success m-3" onclick="edit_modal()">Edit</button>';
            $data_access['access_edit']['visible'] = 'true';
        }

        // action cek delete
        if ($userAccess_delete->num_rows() > 0) {
            // $data_access['access_delete'] = '1';
            $kode_ = $kode_id;
            $kode = "'+data." . $kode_ . "+'";
            $data_access['access_delete']['btn'] =  '<button id="btn-deletes"  value="' . $kode . '" class="btn btn-danger" onclick="delete_modal()">Delete</button>';
            $data_access['access_delete']['visible'] = 'true';
        }

        return $data_access;
    }
}
