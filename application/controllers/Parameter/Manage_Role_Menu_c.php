<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_Role_Menu_c extends CI_Controller
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
        $this->token = $this->security->get_csrf_hash();
    }
    public function index()
    {
        $jabatan = $this->db->get_where("tb_user_transmart", ['username' => $this->data_session['username']])->row()->role_id;
        $menu = menus($jabatan);
        $data['nama'] = $this->data_session['nama'];
        $data['menus'] = $menu;

        $data['title'] = 'Role Menu';
        $data['menu_header'] = 'Parameter';
        $data['main_menu'] = 'Role Menu';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Parameter/Manage_Role_Menu_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    public function view_query()
    {
        $limit = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        $search = $this->input->post('search', TRUE)['value'];
        $role_name_w = $search == '' ? "" : " AND role_name LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";

        $limit_cond = $limit == -1 ? "" : " LIMIT ? OFFSET ?";

        $query =     "SELECT * FROM tb_user_role WHERE 1 $role_name_w ";

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


        $query =     "SELECT * FROM tb_user_role";
        $search = array('role_name');
        $where  = null;
    }

    public function modal_add()
    {
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);
        $data['token'] = $this->token;
        // $level_user = $this->input->post('level_user',TRUE);
        // $data['name_level_user'] = $this->db->get_where('level_user', ['id' => $level_user])->row();
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $html_modal = $this->load->view('Modal/Modal_role_access_add', $data, TRUE);
        echo $html_modal;
    }



    public function modal()
    {
        $data['id'] = "modal_edit";
        $role_id = $this->input->post('role_id', TRUE);
        $data['role_id'] = $role_id;
        $data['role_name'] = $this->User_model->level_user_where($data['role_id'])->role_name;
        $data['level_user'] = $this->User_model->get_level_user();
        $data['level_detail'] = $this->User_model->get_parent()->result_array();
        $data['level_detail2'] = $this->User_model->get_menu_name($parent = '',$role_id)->result_array();

        foreach ($data['level_detail'] as $parent_) {
            if ($parent_['parent'] !== '') {
                $data['level_detail3'][] = $this->User_model->get_parent2($parent_['parent'])->result_array();
            }
        }

        foreach ($data['level_detail3'] as $id_menu2) {
            foreach ($id_menu2 as $id_menu3) {
                $data['level_detail4'][] = $this->User_model->get_menu_name2($id_menu3['id_menu'],$role_id)->result_array();
            }
        }
        $data['token'] = $this->token;
        $html_modal = $this->load->view('Modal/Modal_role_access', $data, TRUE);
        echo $html_modal;
    }

    function modal_delete()
    {
        $data['token'] = $this->token;
        $data['id'] = "modal_delete";
        $role_id = $this->input->post('role_id', TRUE);
        $data['role_id'] =  $role_id;
        // $data['role_id'] = $this->input->post('role_id', TRUE);
        $data['role_name'] = $this->User_model->level_user_where($data['role_id'])->role_name;
        $data['level_user'] = $this->User_model->get_level_user();
        $data['level_detail'] = $this->User_model->get_parent()->result_array();
        $data['level_detail2'] = $this->User_model->get_menu_name($parent = '', $role_id)->result_array();
        // $data['access_crud'] = access_crud($this->id);

        foreach ($data['level_detail'] as $parent_) {
            if ($parent_['parent'] !== '') {
                $data['level_detail3'][] = $this->User_model->get_parent2($parent_['parent'])->result_array();
            }
        }

        foreach ($data['level_detail3'] as $id_menu2) {
            foreach ($id_menu2 as $id_menu3) {
                $data['level_detail4'][] = $this->User_model->get_menu_name2($id_menu3['id_menu'], $role_id)->result_array();
            }
        }
        // var_dump($data);die;
        $html_modal = $this->load->view('Modal/Modal_role_access_delete', $data, TRUE);
        echo $html_modal;
    }

    function validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        foreach ($_POST as $key => $val) {
            if ($key == 'id') {
                $require = '';
            } elseif ($key == 'role_name') {
                // $require = 'required|trim|is_unique[tb_user_role.role_name]';    
                $require =  array('required', 'trim', "regex_match[/^[a-z0-9A-Z\s'-]{1,100}$/]","is_unique[tb_user_role.role_name]");
            } else {
                $require = 'required|trim';
            }
            $this->form_validation->set_rules($key, $key, $require);
        }

        if (!$this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                $json[$key] = form_error($key, '<span class="mt-3 text-danger">', '</span>');
            }
        } else {
            $json = array(
                'action' => 'ok'
            );
        }
        $json['token'] = $this->token;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function update_role_user()
    {
        $tampil = (($this->input->post('view', TRUE)) ? $this->input->post('view', TRUE) : '');
        // $addm = (($this->input->post('addm',TRUE)) ? $this->input->post('addm',TRUE) : '');
        // $edit = (($this->input->post('edit',TRUE)) ? $this->input->post('edit',TRUE) : '');
        // $del = (($this->input->post('del',TRUE)) ? $this->input->post('del',TRUE) : '');
        $role_id = $this->input->post('role_id', TRUE);

        $this->_update($tampil, 'view', $role_id);
        // $this->_update($addm, 'addm', $role_id);
        // $this->_update($edit, 'edit', $role_id);
        // $this->_update($del, 'del', $role_id);
        // $data['token'] = $this->token;
        $arr_res = [
            'token' => $this->token,
            'res' => 1
        ];
        echo json_encode($arr_res);
        // redirect('Setting_parameter/Level_user_c');

    }

    public function _update($access, $field, $role_id)
    {
        $get_parent = $this->User_model->get_level_detail()->result_array();
        if ($access) {
            foreach ($access as $access) {
                $explode_access[] = explode('-', $access);
            }
        } else {
            $explode_access = null;
        }

        $data_up_access = [];

        // $data_kosong = [
        //     $field => 0
        // ];
        // $this->db->update('level_detail', $data_kosong);
        // $this->db->where('level_detail.id_role', $role_id);
        // $this->db->where('menu.is_menu', 'Yes');
        // $this->db->join('menu','manu.id_menu = level_detail.id_menu');
        $this->db->query("UPDATE tb_access_role_menu a JOIN tb_menu_transmart b ON a.id_menu = b.id_menu SET $field = 0 WHERE a.id_role = $role_id AND b.is_menu = 'Yes' ");
        if ($explode_access) {
            foreach ($explode_access as $up_access) {
                foreach ($get_parent as $parent) :
                    if ($parent['id_role'] == $up_access[0] && $parent['id_menu'] == $up_access[1]) :
                        $data_up_access[] = [
                            'id_menu' => $up_access[1],
                            $field => $up_access[2]
                        ];
                        $data_updatessss = [
                            'id_menu' => $up_access[1],
                            $field => $up_access[2]
                        ];
                        $this->db->where('id_role', $role_id);
                        $this->db->where('id_menu', $up_access[1]);
                        $this->db->set($data_updatessss);
                        $this->db->update('tb_access_role_menu');
                    endif;

                endforeach;
                // $this->db->where('id_role', $role_id);
                // $this->db->update_batch('tb_access_role_menu', $data_up_access, 'id_menu');
            }
        }
    }

    function save_()
    {
        $table = 'tb_user_role';
        $field = 'role_name';
        // foreach ($_POST as $key => $val) {
        //     $vals = $this->input->post($key,TRUE);
        //     $data[$key] = $vals;
        // }

        $data['role_name'] = $this->input->post('role_name',TRUE);


        // cek kode 
        $cek_kode = $this->User_model->where_trms($data[$field], $table, $field)->num_rows();
        if ($cek_kode > 0) {
            $msg = 'Role Name Sudah Ada';
        } else {
            $save = $this->User_model->save_trms($data, $table);
            $id_role_user = $this->db->insert_id();
            $select = array('id_menu');
            $get_menu = $this->User_model->get_trms('tb_menu_transmart', $select)->result_array();
            foreach ($get_menu as $menu) {
                if ($menu['id_menu'] == '9') {
                    $data_level = [
                        'id_role' => $id_role_user,
                        'id_menu' => $menu['id_menu'],
                        'view' => 1
                    ];
                } else {
                    $data_level = [
                        'id_role' => $id_role_user,
                        'id_menu' => $menu['id_menu']
                    ];
                }
                $this->User_model->save_trms($data_level, 'tb_access_role_menu');
            }


            if ($save == true) {
                $msg = 'Berhasil di Simpan';
            } else {
                $msg = 'Gagal Menyimpan';
            }
        }
        echo $msg;
    }

    function validate_keyup()
    {
        $this->form_validation->set_error_delimiters('', '');
        foreach ($_POST as $key => $val) {
            if ($key == $key) {
                if ($key == 'id') {
                    $require = '';
                } else {
                    $require = array('required', 'trim', "regex_match[/^[a-z0-9A-Z\s'-]{1,100}$/]");
                }
                $this->form_validation->set_rules($key, $key, $require);
            }
        }

        $this->form_validation->set_message('required', 'You missed the input {field}!');
        $this->form_validation->set_message('numeric', 'You input {field} just numeric!');

        if (!$this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                if ($key == $key) {
                    $json[$key] = form_error($key, '<span class="mt-3 text-danger">', '</span>');
                }
            }
            
        } else {
            foreach ($_POST as $key => $val) {
                $json = array(
                    $key => ''
                );
            }
            
        }
        $json['token'] = $this->token;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    function delete_()
    {
        $table = 'tb_access_role_menu';
        $field = 'id_role';

        foreach ($_POST as $key => $val) {
            $data[$key] = $val;
        }
        // var_dump($data);die;

        // delete
        $delete = $this->User_model->delete_trms($data['role_id'], $table, $field);
        $this->User_model->delete_trms($data['role_id'], 'tb_user_role', 'role_id');

        if ($delete) {
            $arr_res = [
                'token' => $this->token,
                'res' => 1
            ];
            echo json_encode($arr_res);
            // $msg = 'Berhasil di Hapus';
        } else {
            $arr_res = [
                'token' => $this->token,
                'res' => 'failed'
            ];
            echo json_encode($arr_res);
        }
        // echo $msg;
    }
}
