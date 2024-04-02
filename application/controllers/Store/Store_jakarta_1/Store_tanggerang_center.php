<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store_tanggerang_center extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        access_login();
        $this->session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
        $this->data_session = data_session($this->session_token);
    }
    public function index()
    {
        $jabatan = $this->db->get_where('tb_user_transmart', ['username' => $this->data_session['username']])->row()->role_id;
        $menu = menus($jabatan);
        $data['nama'] = $this->data_session['nama'];

        $last = $this->uri->total_segments();
        $record_num = '';
        $record_num2 = '';
        for ($i = 1; $i <= $last; $i++) {
            $record_num .= $this->uri->segment($i) . '/';
        }

        $menu_querys = $this->db->get_where('tb_menu_transmart', ['file' => $record_num])->row_array();
        if ($menu_querys) {
            $menu_query = $menu_querys;
        } else {
            $menu_query2 = $this->db->get_where('tb_menu_transmart', ['file' => substr($record_num, 0, -1)])->row_array();
            $menu_query = $menu_query2;
        }

        $id_menu = $menu_query['id_menu'];
        $id_role = $jabatan;
        $get_data_iframe = $this->User_model->get_iframe($id_menu, $id_role);

        if ($get_data_iframe) {
            $template_iframe = $this->db->get('template_iframe')->row();
            $data['iframe_name'] = $get_data_iframe->iframe_name;
            $iframe_tag = $get_data_iframe->iframe_tag;
            $data['iframe_tag'] = $template_iframe->tag_open . $template_iframe->ip_iframe . $iframe_tag . "'" . $template_iframe->width . $template_iframe->height . $template_iframe->attributes . $template_iframe->tag_close;
        } else {
            $data['iframe_name'] = '';
            $data['iframe_tag'] = "tidak ada tampilan dashboard untuk anda";
        }

        $data['menus'] = $menu;
        $data['title'] = 'Tangerang Center';
        $data['menu_header'] = 'Dashboard';
        $data['main_menu'] = 'Tangerang Center';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Store/Store_jakarta_1/Store_tanggerang_center_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }
}
