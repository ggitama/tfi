<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_Iframe_c extends CI_Controller
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

        $data['user_transmart'] = $this->db->get('tb_user_transmart')->result_array();

        $data['title'] = 'Iframe';
        $data['menu_header'] = 'Parameter';
        $data['main_menu'] = 'Iframe';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Parameter/Manage_Iframe_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    public function List_query()
    {
        $limit = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        $search = $this->input->post('search', TRUE)['value'];
        $iframe_name_w = $search == '' ? "" : " AND a.iframe_name LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";

        $limit_cond = $limit == -1 ? "" : " LIMIT ? OFFSET ?";

        $query =     "SELECT a.id_iframe, a.iframe_name, a.iframe_tag, a.iframe_type, b.iframe_name as i_name
        FROM tb_iframe a
        left join template_iframe b 
        on a.iframe_type = b.id_template_iframe WHERE 1 $iframe_name_w ";

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


        // $query =     "SELECT * FROM tb_iframe";

        // $search = array('iframe_name', 'iframe_tag');
        // $where  = null;
        // // $where  = array('nama_kategori' => 'Tutorial');
        // // jika memakai IS NULL pada where sql
        // $isWhere = null;
        // // $isWhere = 'artikel.deleted_at IS NULL';
        // // $tes = $this->M_Datatables->get_tables_query($query,$search,$where,$isWhere);
        // header('Content-Type: application/json');
        // echo $this->M_Datatables->get_tables_query_train($query, $search, $where, $isWhere);
    }

    public function modal_add()
    {
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);
        $data['token'] = $this->token;

        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $html_modal = $this->load->view('Modal/Modal_Iframe_add', $data, TRUE);
        echo $html_modal;
    }

    public function check_iframe_name()
    {
        $iframe_name = $this->input->post('iframe_name', TRUE);
        $check = $this->db->get_where('tb_iframe', ['iframe_name' => $iframe_name])->num_rows();
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

    public function save_iframe()
    {
        $iframe_name = $this->input->post('iframe_name', TRUE);
        $iframe_tag = $this->input->post('iframe_tag', TRUE);
        $iframe_type = $this->input->post('iframe_type', TRUE);

        $data_insert = [
            'iframe_name' => $iframe_name,
            'iframe_tag' => $iframe_tag,
            'iframe_type' => $iframe_type
        ];

        $insert = $this->db->insert('tb_iframe', $data_insert);
        if ($insert) {
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
        $id_iframe = $this->input->post('id_iframe', TRUE);
        $iframe_data = $this->db->get_where('tb_iframe', ['id_iframe' => $id_iframe])->row();
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);
        $data['template'] = $this->db->get('template_iframe')->result_array();
        $data['iframe_data'] = $iframe_data;
        // var_dump($iframe_data);die;
        $data['modal_title'] = $modal;
        $data['id'] = $id;
        $data['token'] = $this->token;
        $html_modal = $this->load->view('Modal/Modal_iframe_edit', $data, TRUE);
        echo $html_modal;
    }

    public function edit_iframe()
    {
        $id_iframe = $this->input->post('id_iframe', TRUE);
        $iframe_name = $this->input->post('iframe_name', TRUE);
        $iframe_tag = $this->input->post('iframe_tag', TRUE);
        $iframe_type = $this->input->post('iframe_type', TRUE);

        $data_update = [
            'iframe_name' => $iframe_name,
            'iframe_tag' => $iframe_tag,
            'iframe_type' => $iframe_type
        ];

        // check name iframe
        $check = $this->db->get_where('tb_iframe', ['iframe_name' => $iframe_name])->result_array();
        $check_iframe_name = false;
        foreach ($check as $c) {
            if ($c['id_iframe'] != $id_iframe) {
                $check_iframe_name = true;
            }
        }
        if ($check_iframe_name) {
            $arr_res = [
                'token' => $this->token,
                'res' => 'Harap Periksa kembali, terdapat Role Iframe yang sama'
            ];
            echo json_encode($arr_res);
        } else {

            $this->db->where('id_iframe', $id_iframe);
            $this->db->set($data_update);
            $update = $this->db->update('tb_iframe');
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
        $id_iframe = $this->input->post('id_iframe', TRUE);
        $iframe_data = $this->db->get_where('tb_iframe', ['id_iframe' => $id_iframe])->row();
        $modal = $this->input->post('modal', TRUE);
        $id = $this->input->post('id', TRUE);
        $data['template'] = $this->db->get('template_iframe')->result_array();

        $data['iframe_data'] = $iframe_data;
        // var_dump($iframe_data);die;
        $data['modal_title'] = $modal;
        $data['token'] = $this->token;
        $data['id'] = $id;
        $html_modal = $this->load->view('Modal/Modal_iframe_delete', $data, TRUE);
        echo $html_modal;
    }

    public function delete_iframe()
    {
        $id_iframe = $this->input->post('id_iframe', TRUE);
        $this->db->where('id_iframe', $id_iframe);
        $delete = $this->db->delete('tb_iframe');

        $this->db->where('id_iframe', $id_iframe);
        $delete2 = $this->db->delete('tb_role_iframe');
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
