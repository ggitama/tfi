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
        } elseif ($type == '4') {
            $type = '3';
        } else {
            $type = '2';
        }
        // $get_menu = $this->User_model->get_parent_name($type)->result_array();
        $get_menu = $this->User_model->parent_menus($type)->result_array();

        $loop = '';
        foreach ($get_menu as $menu) :
            $loop .= '<option value="' . $menu['id_menu'] . '">' . $menu['menu_name'] . ' ' . $menu['parent_name'] . '</option>';
        endforeach;
        $html = '<label class="col-sm-5 col-form-label">Parent Menu</label>
                    <div class="col-sm-7">
                    <select onchange="parentss()" id="input-parent" class="form-select" name="parent">
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
        $id_menu = $this->input->post('id_menu', TRUE);
        $id_parent = $this->input->post('value_parent', TRUE);
        $get_posisi = $this->db->get_where('tb_menu_transmart', ['id_menu' => $id_menu, 'parent' => $id_parent])->row();

        $get_parent_posisi = $this->db->order_by('position', 'ASC')->get_where('tb_menu_transmart', ['parent' => $id_parent])->result_array();
        if ($get_posisi) {
            $posisi = $get_posisi->position;
        } else {
            $posisi = 0;
        }
        $json = array(
            'get_parent_posisi' => json_encode($get_parent_posisi),
            'posisi' => $posisi,
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
            $require = array('required', 'trim', "regex_match[^[_/a-z0-9A-Z\s'-]{1,100}$]");
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
                $require = array('required', 'trim', "regex_match[^[_/a-z0-9A-Z\s'-]{1,100}$]");
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
            $posisi = $this->db->query("SELECT MAX(position)+1 as posisi FROM tb_menu_transmart WHERE (parent = '' or parent is NULL) and position < 999")->row();
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
        // $file_name = $this->input->post('nama_menu', TRUE);
        $menu_name = $this->input->post('menu_name', TRUE);

        $name_file = str_replace(' ', '_', $menu_name) . '_c';
        // $directory_and_file = "Menu_Controller/".$name_file.'/';
        $directory_and_file_where = "Menu_Controller/" . $name_file;


        $file_name = $this->db->query("SELECT SUBSTRING(file FROM -5 FOR 4) AS kd_max FROM tb_menu_transmart WHERE file like '%$directory_and_file_where%'");
        // var_dump("SELECT SUBSTRING(file FROM -5 FOR 4) AS kd_max FROM tb_menu_transmart WHERE file like '%$directory_and_file_where%'");die;
        $url_file = "";
        if ($file_name->num_rows() > 0) {
            if ($file_name->row()->kd_max == '9999') {
                $url_file = "0001";
            } else {
                foreach ($file_name->result() as $k) {
                    $tmp = ((int)$k->kd_max) + 1;
                    $url_file = sprintf("%04s", $tmp);
                    // var_dump($k->kd_max);
                }
                // die;
            }
        } else {
            // var_dump($file_name->result());die;
            $url_file = "0001";
        }
        // die;
        $name_file = $name_file . $url_file;
        $directory_and_file = "Menu_Controller/" . $name_file . '/';
        // var_dump($name_file);die;

        $menu_name = $menu_name;
        $view_ = str_replace(' ', '_', $menu_name) . '_v' . $url_file;

        $data['file'] = $directory_and_file;


        $fileController = fopen("application/controllers/Menu_Controller/" . $name_file . ".php", "w") or die("Unable to open file!");
        $value_file = $this->value_files($name_file, $menu_name, $view_);
        $txt = $value_file;
        fwrite($fileController, $txt);

        $fileView = fopen("application/views/Menu_View/" . $view_ . ".php", "w") or die("Unable to open file!");
        $value_file_2 = $this->value_file_2();
        fwrite($fileView, $value_file_2);

        fclose($fileController);
        fclose($fileView);



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
    }

    function value_files($name_file, $menu_name, $view_)
    {

        $values = "<?php
        defined('BASEPATH') or exit('No direct script access allowed');
        
        class $name_file extends CI_Controller
        {
            public function __construct()
            {
                parent::__construct();
                $" . "this->load->model('User_model');
                access_login();
                $" . "this->session_token =  hash('sha256', $" . "_SERVER['SCRIPT_NAME']);
                $" . "this->data_session = data_session($" . "this->session_token);
            }
            public function index()
            {
                $" . "jabatan = $" . "this->db->get_where('tb_user_transmart', ['username' => $" . "this->data_session['username']])->row()->role_id;
                $" . "menu = menus($" . "jabatan);
                $" . "data['nama'] = $" . "this->data_session['nama'];
        
                $" . "last = $" . "this->uri->total_segments();
                $" . "record_num = '';
                $" . "record_num2 = '';
                for ($" . "i = 1; $" . "i <= $" . "last; $" . "i++) {
                    $" . "record_num .= $" . "this->uri->segment($" . "i) . '/';
                }
        

                $" . "menu_query = $" . "this->db->get_where('tb_menu_transmart', ['file' => $" . "record_num])->row_array();
                $" . "id_menu = $" . "menu_query['id_menu'];
                $" . "id_role = $" . "jabatan;
                $" . "get_data_iframe = $" . "this->db->query(\"SELECT c.iframe_name, c.iframe_tag, c.iframe_type, e.tag_open,e.tag_close, e.ip_iframe,e.width,e.height, e.attributes FROM tb_role_iframe as a LEFT JOIN tb_menu_transmart as b on a.id_menu = b.id_menu LEFT JOIN tb_iframe as c on c.id_iframe = a.id_iframe LEFT JOIN tb_user_role as d on a.id_role = d.role_id left join template_iframe e on c.iframe_type = e.id_template_iframe WHERE a.id_menu = $" . "id_menu AND id_role = $" . "id_role\")->row();

                if ($" . "get_data_iframe) {
                    $" . "template_iframe = $" . "this->db->get('template_iframe')->row();
                    $" . "data['iframe_name'] = $" . "get_data_iframe->iframe_name;
                    $" . "iframe_tag = $" . "get_data_iframe->iframe_tag;
                    $" . "data['iframe_tag'] = $" . "get_data_iframe->tag_open . $" . "get_data_iframe->ip_iframe . $" . "iframe_tag . \"'\" . $" . "get_data_iframe->width . $" . "get_data_iframe->height . $" . "get_data_iframe->attributes . $" . "get_data_iframe->tag_close;
                } else {
                    $" . "data['iframe_name'] = '';
                    $" . "data['iframe_tag'] = tidak ada tampilan dashboard untuk anda;
                }
                

                // if ($" . "menu_querys) {
                //     $" . "menu_query = $" . "menu_querys;
                // }else{
                //     $" . "menu_query2 = $" . "this->db->get_where('tb_menu_transmart', ['file' => substr($" . "record_num,0, -1)])->row_array();
                //     $" . "menu_query = $" . "menu_query2;
                // }
        
                // $" . "id_menu = $" . "menu_query['id_menu'];
                // $" . "id_role = $" . "jabatan;
                // $" . "get_data_iframe = $" . "this->User_model->get_iframe($" . "id_menu,$" . "id_role);
        
                // if ($" . "get_data_iframe) {
                //     $" . "data['iframe_name'] = $" . "get_data_iframe->iframe_name;
                //     $" . "data['iframe_tag'] = $" . "get_data_iframe->iframe_tag;
                // } else {
                //     $" . "data['iframe_name'] = '';
                //     $" . "data['iframe_tag'] = 'tidak ada tampilan dashboard untuk anda';
                // }
        
                $" . "data['menus'] = $" . "menu;
                $" . "data['title'] = '$menu_name';
                $" . "data['menu_header'] = 'Dashboard';
                $" . "data['main_menu'] = '$menu_name';
        
                $" . "this->load->view('template_dashboard/Header_v', $" . "data);
                $" . "this->load->view('Menu_View/" . "$view_" . "', $" . "data);
                $" . "this->load->view('template_dashboard/Footer_v', $" . "data);
            }
        }";
        return  $values;
    }

    function value_file_2()
    {
        $html_view = "<div class='row'>
                    <div class='col-md-12'>
                        <h2 class='text-center'><?= $" . "iframe_name ?></h2>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <?= $" . "iframe_tag ?>
                    </div>
                </div>";

        return $html_view;
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

    function validated($menu_name, $parent, $type)
    {
        $validate = false;
        $msg = "";

        if ($menu_name == '') {
            $validate = true;
            $msg .= "Nama Menu Harap diisi ! \n";
        }

        if ($type != 0) {
            if ($parent == '') {
                $validate = true;
                $msg .= "Parent Menu Harap diisi ! \n";
            }
        }


        return [
            'validate' => $validate,
            'msg' => $msg
        ];
    }

    function edit_()
    {
        $data_modal = $this->input->post('data_modal', TRUE);
        $id_menu = $this->input->post('id_menu', TRUE);
        $parent = $this->input->post('parent', TRUE) == "" ? null : $this->input->post('parent', TRUE);

        $type = $this->input->post('type', TRUE);
        $menu_name = $this->input->post('menu_name', TRUE);
        $position = $this->input->post('position', TRUE);
        $is_menu = $this->input->post('is_menu', TRUE);

        if ($type == 0) {
            $parent = null;
        }

        // VALIDATED    
        $validated = $this->validated($menu_name, $parent, $type);


        $data_menu = $this->db->get_where('tb_menu_transmart', ['id_menu' => $id_menu])->row();
        $data_menus = $this->db->get_where('tb_menu_transmart', ['id_menu' => $id_menu, 'parent' => $parent])->row();




        // get much
        if ($validated['validate']) {
            $arr_res = [
                'token' => $this->token,
                'res' => $validated['msg']
            ];
            echo json_encode($arr_res);
        } else {
            $data_parent = $this->db->get_where('tb_menu_transmart', ['parent' => $parent])->num_rows();
            if ($position) {
                $position = $position;
            } else {
                $position = (int)$data_parent + 1;
            }
            if ($data_menus) {
                if ($data_menu->position > $position) {

                    $parent_cond = $data_menu->parent == "" ? "parent is NULL" : "parent = '$data_menu->parent'";
                    $query_posisition = $this->db->query("SELECT * FROM tb_menu_transmart WHERE position BETWEEN $position  AND $data_menu->position  AND $parent_cond ORDER BY position ASC")->result_array();
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
                            'type' => $type,
                            'parent' => $parent,
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
                            'type' => $type,
                            'parent' => $parent,
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
                        'type' => $type,
                        'parent' => $parent,
                        'menu_name' => $menu_name,
                        'is_menu' => $is_menu
                    ];
                    $this->db->where("id_menu", $id_menu);
                    $this->db->set($set_posisi);
                    $this->db->update('tb_menu_transmart');
                }
            } else {
                // rapihkan posisi yang ditinggalkan diparent sebelumnya
                $before_parent = $data_menu->parent;
                $bf_posisi = $data_menu->position;
                $get_date_before_parent = $this->db->order_by('position', 'ASC')->get_where('tb_menu_transmart', ['parent' => $before_parent, 'position <' => 99])->result_array();
                $last_position = count($get_date_before_parent);

                if ($bf_posisi != $last_position) {
                    foreach ($get_date_before_parent as $key => $value) {
                        $repair_posisi = $value['position'];
                        if ($repair_posisi > $bf_posisi && $bf_posisi != $repair_posisi) {
                            $set_posisi = [
                                'position' => (int)$repair_posisi - 1
                            ];
                            $this->db->where("id_menu", $value['id_menu']);
                            $this->db->set($set_posisi);
                            $this->db->update('tb_menu_transmart');
                        }
                    }
                }

                $data_after_parent = $this->db->order_by('position', 'ASC')->get_where('tb_menu_transmart', ['parent' => $parent, 'position <' => 99])->result();
                $last_position = count($data_after_parent);
                // print_r($data_after_parent);die;
                foreach ($data_after_parent as $key_af => $value_af) {
                    if ($value_af->position == $position) {
                        $set_posisi = [
                            'type' => $type,
                            'parent' => $parent,
                            'menu_name' => $menu_name,
                            'position' => $position,
                            'is_menu' => $is_menu
                        ];
                        $this->db->where("id_menu", $id_menu);
                        $this->db->set($set_posisi);
                        $this->db->update('tb_menu_transmart');

                        $set_posisi2 = [
                            'type' => $value_af->type,
                            'parent' => $value_af->parent,
                            'menu_name' => $value_af->menu_name,
                            'position' => (int)$value_af->position + 1,
                            'is_menu' => $value_af->is_menu
                        ];
                        $this->db->where("id_menu", $value_af->id_menu);
                        $this->db->set($set_posisi2);
                        $this->db->update('tb_menu_transmart');
                    } elseif ($value_af->position > $position) {
                        $set_posisi2 = [
                            'type' => $value_af->type,
                            'parent' => $value_af->parent,
                            'menu_name' => $value_af->menu_name,
                            'position' => (int)$value_af->position + 1,
                            'is_menu' => $value_af->is_menu
                        ];
                        $this->db->where("id_menu", $value_af->id_menu);
                        $this->db->set($set_posisi2);
                        $this->db->update('tb_menu_transmart');
                    } elseif ($position > $last_position) {
                        $set_posisi = [
                            'type' => $type,
                            'parent' => $parent,
                            'menu_name' => $menu_name,
                            'position' => $position,
                            'is_menu' => $is_menu
                        ];
                        $this->db->where("id_menu", $id_menu);
                        $this->db->set($set_posisi);
                        $this->db->update('tb_menu_transmart');
                    }
                }
            }

            $arr_res = [
                'token' => $this->token,
                'res' => 1,
            ];
            echo json_encode($arr_res);
        }
    }
}