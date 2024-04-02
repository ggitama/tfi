<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_Role_Iframe_c extends CI_Controller
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

    public function refreshCsrf()
    {
        // echo $this->output
        // 	->set_content_type('application/json')
        // 	->set_status_header(200) // Return status
        // 	->set_output(json_encode([
        // 		'csrfName' => $this->security->get_csrf_token_name(),
        // 		'csrfHash' => $this->security->get_csrf_hash()
        // 	]));
        $tokens =   ['token' => $this->security->get_csrf_hash()];
        echo json_encode($tokens);
    }


    public function index()
    {
        $jabatan = $this->db->get_where("tb_user_transmart", ['username' => $this->data_session['username']])->row()->role_id;
        $menu = menus($jabatan);
        $data['nama'] = $this->data_session['nama'];
        $data['menus'] = $menu;


        $data['title'] = 'Role Iframe';
        $data['menu_header'] = 'Parameter';
        $data['main_menu'] = 'Role Iframe';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Parameter/Manage_Role_Iframe_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    public function view_query()
    {

        $limit = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        $search = $this->input->post('search', TRUE)['value'];
        $like_w = $search == '' ? "" : " AND iframe_name LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";

        $limit_cond = $limit == -1 ? "" : " LIMIT ? OFFSET ?";

        $query =     "SELECT DISTINCT(a.id_menu), b.menu_name, c.iframe_name FROM tb_role_iframe as a LEFT JOIN tb_menu_transmart as b on a.id_menu = b.id_menu LEFT JOIN tb_iframe as c on c.id_iframe = a.id_iframe LEFT JOIN tb_user_role as d on a.id_role = d.role_id where 1 $like_w ";

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


        // $query =     "SELECT * FROM tb_role_iframe as a LEFT JOIN tb_menu_transmart as b on a.id_menu = b.id_menu LEFT JOIN tb_iframe as c on c.id_iframe = a.id_iframe LEFT JOIN tb_user_role as d on a.id_role = d.role_id";

        $search = array('c.iframe_name', 'b.menu_name');
        // $where  = null;
        // $where  = array('nama_kategori' => 'Tutorial');
        // jika memakai IS NULL pada where sql
        // $isWhere = null;
        // $isWhere = 'artikel.deleted_at IS NULL';
        // $tes = $this->M_Datatables->get_tables_query($query,$search,$where,$isWhere);
        // header('Content-Type: application/json');
        // echo $this->M_Datatables->get_tables_query_train($query, $search, $where, $isWhere);
    }

    public function modal_add()
    {
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);

        $data['role_user'] = $this->db->get('tb_user_role')->result_array();
        // $data['menu_user'] = $this->db->get('tb_menu_transmart')->result_array();
        $this->db->select("a.*,CONCAT('(',b.menu_name,')') as parent_name");
        $this->db->from("tb_menu_transmart a");
        // $this->db->where('(a.type = 2 OR a.type =3)');
        $this->db->where('a.is_menu', 'Yes');
        $this->db->join('tb_menu_transmart b', 'a.parent = b.id_menu', 'left');
        $this->db->order_by("b.type", "asc");
        $this->db->order_by("b.position", "asc");
        $data['menu_user'] = $this->db->get()->result_array();
        $data['iframe_role'] = $this->db->get('tb_iframe')->result_array();
        $data['token'] = $this->token;

        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $html_modal = $this->load->view('Modal/Modal_Role_Iframe_add', $data, TRUE);
        echo $html_modal;
    }

    public function check_role_iframe()
    {
        // $id_role = $this->input->post('role', TRUE);
        $id_menu = $this->input->post('menu', TRUE);
        $id_iframe = $this->input->post('iframe', TRUE);
        // $check = $this->db->get_where('tb_role_iframe', ['id_menu' => $id_menu, 'id_iframe' => $id_iframe])->row();
        $check = $this->db->get_where('tb_role_iframe', ['id_menu' => $id_menu])->num_rows();
        if ($check < 1) {
            $arr_res = [
                'token' => $this->token,
                'res' => 0
            ];
            echo json_encode($arr_res);
            // echo '0';
        } else {
            $arr_res = [
                'token' => $this->token,
                'res' => 1
            ];
            echo json_encode($arr_res);
            // echo '1';
        }
    }

    public function save_role_iframe()
    {
        // $id_role = $this->input->post('role', TRUE);
        $id_menu = $this->input->post('menu', TRUE);
        $id_iframe = $this->input->post('iframe', TRUE);
        // $id_role = $this->db->query("SELECT role_id FROM tb_user_role");
        $this->db->select('role_id');
    $this->db->from('tb_user_role');
    $query = $this->db->get()->result_array();
    // echo json_encode($query);

    foreach ($query as $level) {
        $data_level = [
            'id_role' => $level['role_id'],
            'id_menu' => $id_menu,
            'id_iframe' => $id_iframe
        ];
        $insrt= $this->db->insert('tb_role_iframe', $data_level);
        
    }   
    if ($insrt) {
        $arr_res = [
            'token' => $this->token,
            'res' => 1
        ];
        echo json_encode($arr_res);
        // echo "Iframe Berhasil disimpan";
    } else {
        $arr_res = [
            'token' => $this->token,
            'res' => 'failed'
        ];
        echo json_encode($arr_res);
        // echo $this->db->error();
    }    
    }

    public function modal_edit()
    {
        $id_menu = $this->input->post('id_menu', TRUE);
        $role_iframe_data = $this->db->get_where('tb_role_iframe', ['id_menu' => $id_menu])->row();
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);

        $data['iframe_data'] = $role_iframe_data;
        // $data['role_user'] = $this->db->get('tb_user_role')->result_array();
        $data['menu_user'] = $this->db->get('tb_menu_transmart')->result_array();
        $data['iframe_role'] = $this->db->get('tb_iframe')->result_array();
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $data['token'] = $this->token;
        $html_modal = $this->load->view('Modal/Modal_role_iframe_edit', $data, TRUE);
        echo $html_modal;
    }

    public function edit_role_iframe()
    {
        // $id_role_iframe = $this->input->post('id_role_iframe', TRUE);
        // $role = $this->input->post('role', TRUE);
        $menu = $this->input->post('menu', TRUE);
        $iframe = $this->input->post('iframe', TRUE);

        $data_update = [
            // 'id_role_iframe' => $id_role_iframe,
            // 'id_role' => $role,
            'id_menu' => $menu,
            'id_iframe' => $iframe,
        ];

        $check = $this->db->get_where('tb_role_iframe', ['id_menu' => $menu])->result_array();
        $check_iframe_role = false;
        foreach ($check as $c) {
            if ($c['id_menu'] != $menu) {
                $check_iframe_role = true;
            }
        }

        if ($check_iframe_role) {
            $arr_res = [
                'token' => $this->token,
                'res' => "Harap Periksa kembali, terdapat Role Iframe yang sama"
            ];
            echo json_encode($arr_res);
        } else {
            $this->db->where('id_menu', $menu);
            $this->db->set($data_update);
            $update = $this->db->update('tb_role_iframe');
            if ($update) {
                $arr_res = [
                    'token' => $this->token,
                    'res' => 1
                ];
                echo json_encode($arr_res);
            } else {
                $arr_res = [
                    'token' => $this->token,
                    'res' => 'failed'
                ];
                echo json_encode($arr_res);
            }
        }
    }

    public function modal_delete()
    {
        $id_menu = $this->input->post('id_menu', TRUE);
        $iframe_data = $this->db->get_where('tb_role_iframe', ['id_menu' => $id_menu])->row();
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);

        $data['iframe_data'] = $iframe_data;
        $data['role_user'] = $this->db->get('tb_user_role')->result_array();
        $data['menu_user'] = $this->db->get('tb_menu_transmart')->result_array();
        $data['iframe_role'] = $this->db->get('tb_iframe')->result_array();
        // var_dump($iframe_data);die;
        $data['token'] = $this->token;
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $html_modal = $this->load->view('Modal/Modal_role_iframe_delete', $data, TRUE);
        echo $html_modal;
    }

    public function delete_role_iframe()
    {
        $id_menu = $this->input->post('id_menu', TRUE);
        $this->db->where('id_menu', $id_menu);
        $delete = $this->db->delete('tb_role_iframe');
        if ($delete) {
            $arr_res = [
                'token' => $this->token,
                'res' => 1
            ];
            echo json_encode($arr_res);
        } else {
            $arr_res = [
                'token' => $this->token,
                'res' => 'failed'
            ];
            echo json_encode($arr_res);
        }
    }
}
