<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_Manage_c extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('M_Datatables');
        $this->load->library('form_validation');
        access_login();
        $this->session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
        $this->data_session = data_session($this->session_token);
    }
    public function index()
    {
        $jabatan = $this->db->get_where("tb_user_transmart", ['username' => $this->data_session['username']])->row()->role_id;
        $menu = menus($jabatan);
        $data['nama'] = $this->data_session['nama'];
        $data['menus'] = $menu;

        $data['user_transmart'] = $this->db->get('tb_user_transmart')->result_array();

        $data['title'] = 'User';
        $data['menu_header'] = 'Parameter';
        $data['main_menu'] = 'User';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Parameter/User_Manage_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    public function List_query()
    {
        $limit = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        $search = $this->input->post('search', TRUE)['value'];
        $username_w = $search == '' ? "" : " AND username LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";

        $limit_cond = $limit == -1 ? "" : " LIMIT ? OFFSET ?";

        $query =     "SELECT * FROM tb_user_transmart as a LEFT JOIN tb_user_role as b on a.role_id = b.role_id WHERE 1 $username_w ";

        $count_query = $this->db->query($query)->result_array();

        $query_exec = $this->db->query($query . $limit_cond, array((int)$limit, (int)$start))->result_array();

        $res_data = array(
            'draw' =>  $this->input->post('draw', TRUE), // Ini dari datatablenya    
            'recordsTotal' => count($count_query),
            'recordsFiltered' => count($count_query),
            'data' => $query_exec,
            'token' => $this->security->get_csrf_hash()
        );

        echo json_encode($res_data);
    }

    public function modal_add()
    {
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);

        $data['data_role'] = $this->db->get('tb_user_role')->row();
        $data['role_user'] = $this->db->get('tb_user_role')->result_array();
        // var_dump($data['role_user']);die;
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $data['token'] = $this->security->get_csrf_hash();
        $html_modal = $this->load->view('Modal/Modal_user_add', $data, TRUE);
        echo $html_modal;
    }

    function check_username()
    {
        $username = $this->input->post('username', TRUE);
        $token = $this->security->get_csrf_hash();
        $check_username = $this->db->get_where('tb_user_transmart', ['username' => $username])->row();
        if ($check_username) {
            $arr_res = [
                'token' => $token,
                'res' => 1
            ];
            echo json_encode($arr_res);
        } else {
            $arr_res = [
                'token' => $token,
                'res' => 0
            ];
            echo json_encode($arr_res);
        }
    }

    public function save_user_action()
    {
        $username_session = $this->data_session['username'];
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $nama = $this->input->post('nama', TRUE);
        $role_id = $this->input->post('role', TRUE);
        $ldap = $this->input->post('ldap', TRUE);
        $token = $this->security->get_csrf_hash();

        $data_user_action = $this->db->get_where('tb_user_transmart',['username'=>$username_session])->row();

        if($data_user_action->role_id == 2){
            if ($ldap == 'No') {
                $regex_pass = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=]{8,}$/", $password);
            }else{
                $regex_pass = 1;
            }

            $data_insert = [
                'username' => $username,
                'nama' => $nama,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role_id' => $role_id,
                'is_active' => 1,
                'ldap' => $ldap
            ];

            if ($regex_pass == 0) {
                $arr_res = [
                    'token' => $token,
                    'res' => 'Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character ! '
                ];
                echo json_encode($arr_res);
            } else {
                $insert = $this->db->insert('tb_user_transmart', $data_insert);
                if ($insert) {
                    $arr_res = [
                        'token' => $token,
                        'res' => 1
                    ];
                    echo json_encode($arr_res);
                } else {
                    $arr_res = [
                        'token' => $token,
                        'res' => 'failed'
                    ];
                    echo json_encode($arr_res);
                }
            }
        }else{
            $arr_res = [
                'token' => $token,
                'res' => 'failed'
            ];
            echo json_encode($arr_res);
        }
    }



    public function modal_edit()
    {
        $username = $this->input->post('username', TRUE);
        $user_transmart = $this->db->get_where('tb_user_transmart', ['username' => $username])->row();
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);

        $data['user_transmart'] = $user_transmart;
        // var_dump($username);die;

        $data['data_role'] = $this->db->get('tb_user_role')->row();
        $data['role_user'] = $this->db->get('tb_user_role')->result_array();
        // var_dump($data['role_user']);die;
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $data['token'] = $this->security->get_csrf_hash();
        $html_modal = $this->load->view('Modal/Modal_user_edit', $data, TRUE);
        echo $html_modal;
    }

    public function edit_user()
    {
        $token = $this->security->get_csrf_hash();
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $ldap = $this->input->post('ldap', TRUE);
        $nama = $this->input->post('nama', TRUE);
        $role = $this->input->post('role', TRUE);
        $isActive = $this->input->post('isActive', TRUE);

        $username_session = $this->data_session['username'];
        $data_user_action = $this->db->get_where('tb_user_transmart',['username'=>$username_session])->row();

        if($data_user_action->role_id == 2){
            if($ldap == 'Yes'){
                $data_set = [
                    'nama' => $nama,
                    'ldap' => $ldap,
                    'role_id' => $role,
                    'is_active' => $isActive,
                ];
                $this->db->where('username', $username);
                $this->db->set($data_set);
                $update = $this->db->update('tb_user_transmart');
                if ($update) {
                    $arr_res = [
                        'token' => $token,
                        'res' => 1
                    ];
                    echo json_encode($arr_res);
                } else {
                    $arr_res = [
                        'token' => $token,
                        'res' => 'failed'
                    ];
                    echo json_encode($arr_res);
                }
            }else{
                if ($password) {
                    $data_set = [
                        'nama' => $nama,
                        'ldap' => $ldap,
                        'role_id' => $role,
                        'is_active' => $isActive,
                        'password' =>  password_hash($password, PASSWORD_DEFAULT),
                    ];
                }else{
                    $data_set = [
                        'nama' => $nama,
                        'ldap' => $ldap,
                        'role_id' => $role,
                        'is_active' => $isActive
                    ];
                }
                $regex_pass = 1;
                if ($password) {
                    $regex_pass = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=]{8,}$/", $password);
                }

                if ($regex_pass == 0) {
                    $arr_res = [
                        'token' => $token,
                        'res' => 'Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character ! '
                    ];
                    echo json_encode($arr_res);
                    // die;
                }else{
                    $this->db->where('username', $username);
                    $this->db->set($data_set);
                    $update = $this->db->update('tb_user_transmart');
                    if ($update) {
                        $arr_res = [
                            'token' => $token,
                            'res' => 1
                        ];
                        echo json_encode($arr_res);
                    } else {
                        $arr_res = [
                            'token' => $token,
                            'res' => 'failed'
                        ];
                        echo json_encode($arr_res);
                    }
                }
            
            }
        }else{
            $arr_res = [
                'token' => $token,
                'res' => 'failed'
            ];
            echo json_encode($arr_res);
        }
        
        
    }


    function modal_delete()
    {
        $username = $this->input->post('username',true);
        $user_transmart = $this->db->get_where('tb_user_transmart', ['username' => $username])->row();
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);

        $data['user_transmart'] = $user_transmart;
        // var_dump($username);die;

        $data['data_role'] = $this->db->get('tb_user_role')->row();
        $data['role_user'] = $this->db->get('tb_user_role')->result_array();
        // var_dump($data['role_user']);die;
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $data['token'] = $this->security->get_csrf_hash();
        $html_modal = $this->load->view('Modal/Modal_user_delete', $data, TRUE);
        echo $html_modal;
    }

    function delete_user()
    {
        $token = $this->security->get_csrf_hash();
        $username = $this->input->post('username', TRUE);

        $username_session = $this->data_session['username'];
        $data_user_action = $this->db->get_where('tb_user_transmart',['username'=>$username_session])->row();

        if($data_user_action->role_id == 2){

            $this->db->where('username', $username);
            $delete = $this->db->delete('tb_user_transmart');
            if ($delete) {
                $arr_res = [
                    'token' => $token,
                    'res' => 1
                ];
                echo json_encode($arr_res);
            } else {
                $arr_res = [
                    'token' => $token,
                    'res' => 'failed'
                ];
                echo json_encode($arr_res);
            }
        }else{
            $arr_res = [
                'token' => $token,
                'res' => 'failed'
            ];
            echo json_encode($arr_res);
        }
    }
}
