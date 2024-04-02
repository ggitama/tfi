<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_c extends CI_Controller
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

        $data['title'] = 'Menu';
        $data['menu_header'] = 'Parameter';
        $data['main_menu'] = 'Menu';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Parameter/Menu_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    public function list_menu()
    {
        $limit = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        $search = $this->input->post('search', TRUE)['value'];
        $like_w = $search == '' ? "" : " AND menu_name LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";

        $limit_cond = $limit == -1 ? "" : " LIMIT ? OFFSET ?";

        $query =     "SELECT * FROM tb_menu_transmart WHERE 1 $like_w ";

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

        // // $query =     "SELECT * FROM menu";
        // $query =     "SELECT * FROM tb_menu_transmart";

        // $search = array('menu_name', 'type');
        // $where  = ['is_menu' => 'Yes'];
        // // $where  = array('nama_kategori' => 'Tutorial');
        // // jika memakai IS NULL pada where sql
        // $isWhere = null;
        // // $isWhere = 'artikel.deleted_at IS NULL';
        // // $tes = $this->M_Datatables->get_tables_query($query,$search,$where,$isWhere);
        // header('Content-Type: application/json');
        // echo $this->M_Datatables->get_tables_query_train($query, $search, $where, $isWhere);
    }

    public function Modal_view()
    {
        $data_modal = $this->input->post('data_modal', TRUE);
        $id_menu = $this->input->post('id_menu', TRUE);
        $data['data_menu'] = $this->db->get_where('tb_menu_transmart', ['id_menu' => $id_menu])->row();
        // $data['modal_title'] = 'Tambah Menu';
        $data['id'] = $data_modal;
        $data['token'] = $this->token;
        $html_modal = $this->load->view('Modal/Modal_manage_menu', $data, TRUE);
        echo $html_modal;
    }

    public function parent_menu()
    {
        $type = $this->input->post('type', TRUE);
        if ($type == '1') {
            $type = '0';
        } elseif ($type == '2') {
            $type = '1';
        } else {
            $type = '2';
        }
        $get_menu = $this->User_model->get_parent_name($type)->result_array();
        $loop = '';
        foreach ($get_menu as $menu) :
            $loop .= '<option value="' . $menu['id_menu'] . '">' . $menu['menu_name'] . '</option>';
        endforeach;
        $html = '<label class="col-sm-5 col-form-label">Parent Menu</label>
                    <div class="col-sm-7">
                    <select id="input-parent" class="form-select" name="parent">
                        <option value="" selected></option>
                        ' . $loop . '
                    </select>
                    <div id="error"></div>
                    </div>';

                    $json = array(
                        'html' => $html,
                        'token' => $this->token
                    );
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($json));
    }

    public function posisi_menu()
    {
        $id_parent = $this->input->post('value_parent', TRUE);
        $get_posisi = $this->User_model->get_posisi_menu($id_parent)->result_array();
        $loop = '';
        foreach ($get_posisi as $posisi) :
            $loop .= '<option value="' . $posisi['position'] . '">' . $posisi['position'] . '</option>';
        endforeach;
        $html = '<label class="col-sm-5 col-form-label">Posisition Menu</label>
                    <div class="col-sm-7">
                    <select onkeyup="check_v(this)" id="input-position" class="form-select" name="position">
                        <option value="0" selected>-- Posisi Menu --</option>
                        ' . $loop . '
                    </select>
                    <div id="error"></div>
                    </div>';
        $json = array(
            'html' => $html,
            'token' => $this->token
        );
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }


    function validate()
    {
        $this->form_validation->set_error_delimiters('', '');
        foreach ($_POST as $key => $val) {
            // $require = 'required|trim';
            $require = array('required', 'trim', "regex_match[~^[_/a-z0-9A-Z\s'-]{1,100}$~]");
            $this->form_validation->set_rules($key, $key, $require);
            $tes[] = $key;
        }

        $this->form_validation->set_message('required', 'You missed the input {field}!');
        $this->form_validation->set_message('numeric', 'You input {field} just numeric!');

        if (!$this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                $json[$key] = form_error($key, '<span class="mt-3 text-danger">', '</span>');
            }
        } else {
            $json = array(
                'action' => 'ok',
            );
        }
        $json['token'] = $this->token;
        // var_dump($json);die;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    function validate_keyup()
    {
        $this->form_validation->set_error_delimiters('', '');
        foreach ($_POST as $key => $val) {
            if ($key == $key) {
                // $require = 'required|trim';
                $require = array('required', 'trim', "regex_match[~^[_/a-z0-9A-Z\s'-]{1,100}$~]");
                $this->form_validation->set_rules($key, $key, $require);
            }
        }

        // $this->form_validation->set_message('required', 'You missed the input {field}!');
        // $this->form_validation->set_message('numeric', 'You input {field} just numeric!');
        // $this->form_validation->set_message('regex_match', 'The {field} field is not in the correct format.');

        if (!$this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                if ($key == $key) {
                    $json[$key] = form_error($key, '<span class="mt-3 text-danger">', '</span>');
                }
            }
        } else {
            foreach ($_POST as $key => $val) {
                $json = array(
                    $key => '',
                );
            }
        }
        $json['token'] = $this->token;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    function save_()
    {
        $table = 'tb_menu_transmart';
        foreach ($_POST as $key => $val) {
            $data[$key] = $val;
        }


        if ($data['type'] == '0') {
            $posisi = $this->db->query("SELECT MAX(position)+1 as posisi FROM tb_menu_transmart WHERE (parent = '' or parent is NULL) and position < 99")->row();
            if ($posisi) {
                $data['position'] = $posisi->posisi;
            } else {
                $data['position'] = '1';
            }
        } else {
            $parent = $data['parent'];
            $posisi = $this->db->query("SELECT CASE WHEN MAX(position) is NULL THEN 1 ELSE MAX(position)+1 END as posisi FROM tb_menu_transmart WHERE parent = $parent")->row();
            $data['position'] = $posisi->posisi;
        }

        // var_dump($data['file']);
        // die;
        $save = $this->db->insert($table, $data);
        $id_menu = $this->db->insert_id();
        $select = array('role_id');
        $get_level = $this->User_model->get_trms('tb_user_role', $select)->result_array();
        foreach ($get_level as $level) {
            $data_level = [
                'id_role' => $level['role_id'],
                'id_menu' => $id_menu
            ];
            $this->db->insert('tb_access_role_menu', $data_level);
        }


        if ($save) {
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



        // if ($save == true) {
        //     $msg = 'Berhasil di Simpan';
        // } else {
        //     $msg = 'Gagal Menyimpan';
        // }
        // echo $msg;
        // // cek kode 
        // $cek_kode = $this->Training_parameter->where($data[$field], $table, $field)->num_rows();
        // if ($cek_kode) {
        //     $msg = 'Kode Lokasi Sudah Ada';
        // } else {
        //     $save = $this->Training_parameter->save($data, $table);
        //     if ($save == true) {
        //         $msg = 'Berhasil di Simpan';
        //     } else {
        //         $msg = 'Gagal Menyimpan';
        //     }
        // }
        // echo $msg;
    }


    function Modal_edit()
    {
        $data_modal = $this->input->post('data_modal', TRUE);
        $id_menu = $this->input->post('id_menu', TRUE);
        $data['data_menu'] = $this->db->get_where('tb_menu_transmart', ['id_menu' => $id_menu])->row();
        if ($data['data_menu']->type == '0') {
            $data['posisi'] = $this->db->query("SELECT position FROM tb_menu_transmart WHERE type = '0' ORDER BY position ASC")->result_array();
        } else {
            $parent = $data['data_menu']->parent;
            $data['posisi'] = $this->db->query("SELECT position FROM tb_menu_transmart WHERE parent = '$parent' ORDER BY position ASC")->result_array();
        }
        // var_dump( $data['data_menu']);die;

        $data['id'] = $data_modal;
        $data['token'] = $this->token;
        $html_modal = $this->load->view('Modal/Modal_manage_menu_edit', $data, TRUE);
        echo $html_modal;
    }

    function edit_()
    {
        $data_modal = $this->input->post('data_modal', TRUE);
        $id_menu = $this->input->post('id_menu', TRUE);
        $menu_name = $this->input->post('menu_name', TRUE);
        $position = $this->input->post('position', TRUE);
        $is_menu = $this->input->post('is_menu', TRUE);

        $data_menu = $this->db->get_where('tb_menu_transmart', ['id_menu' => $id_menu])->row();
        // var_dump($data_menu->position > $position);die;
        // var_dump("SELECT * FROM tb_menu_transmart WHERE position BETWEEN  $data_menu->position AND  $position AND parent = '$data_menu->parent' ORDER BY position ASC");die;
        if ($data_menu->position > $position) {

            $parent_cond = $data_menu->parent == "" ? "parent is NULL" : "parent = '$data_menu->parent'";
            $query_posisition = $this->db->query("SELECT * FROM tb_menu_transmart WHERE position BETWEEN $position  AND $data_menu->position  AND $parent_cond ORDER BY position ASC")->result_array();
            // var_dump($data_menu->parent);die;
            // var_dump("SELECT * FROM tb_menu_transmart WHERE position BETWEEN $position  AND $data_menu->position  AND $parent_cond ORDER BY position ASC");die;
            foreach ($query_posisition as $query_posisitions) {
                if ($query_posisitions['position'] == $data_menu->position) {
                    $position_update = $position;
                    $menu_names = $menu_name;
                    $is_menus = $is_menu;
                } else {
                    $position_update = (int)$query_posisitions['position'] + 1;
                    $menu_names = $query_posisitions['menu_name'];
                    $is_menus = $query_posisitions['is_menu'];
                }
                $set_posisi = [
                    // 'id_menu' => $query_posisitions['id_menu'],
                    'menu_name' => $menu_names,
                    'position' => $position_update,
                    'is_menu' => $is_menus
                ];
                $this->db->where("id_menu", $query_posisitions['id_menu']);
                $this->db->set($set_posisi);
                $this->db->update('tb_menu_transmart');
            }
        } elseif ($data_menu->position < $position) {
            $parent_cond = $data_menu->parent == "" ? "parent is NULL" : "parent = '$data_menu->parent'";
            $query_posisition = $this->db->query("SELECT * FROM tb_menu_transmart WHERE position BETWEEN $data_menu->position  AND  $position  AND $parent_cond ORDER BY position ASC")->result_array();

            foreach ($query_posisition as $query_posisitions) {
                if ($query_posisitions['position'] == $data_menu->position) {
                    $position_update = $position;
                    $menu_names = $menu_name;
                    $is_menus = $is_menu;
                } else {
                    $position_update = (int)$query_posisitions['position'] - 1;
                    $menu_names = $query_posisitions['menu_name'];
                    $is_menus = $query_posisitions['is_menu'];
                }
                $set_posisi = [
                    // 'id_menu' => $query_posisitions['id_menu'],
                    'menu_name' => $menu_names,
                    'position' => $position_update,
                    'is_menu' => $is_menus
                ];
                $this->db->where("id_menu", $query_posisitions['id_menu']);
                $this->db->set($set_posisi);
                $this->db->update('tb_menu_transmart');
            }
        } else {
            $set_posisi = [
                'menu_name' => $menu_name,
                'is_menu' => $is_menu
            ];
            $this->db->where("id_menu", $id_menu);
            $this->db->set($set_posisi);
            $this->db->update('tb_menu_transmart');
        }

        $arr_res = [
            'token' => $this->token,
            'res' => 1
        ];
        echo json_encode($arr_res);
    }
}
