<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_performance_c extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        access_login();
        $this->session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
        $this->data_session = data_session($this->session_token);
        // var_dump(session_id());die;
    }
    public function index()
    {
        // var_dump($_SERVER['HTTP_COOKIE']);die;
        // var_dump($_SERVER['REMOTE_ADDR']);die;
        $username = $this->data_session['username'];
        // var_dump($this->data_session);die;
        // var_dump($this->db->query("SELECT * FROM log_Activity WHERE username = '$username' ORDER BY id DESC LIMIT 2")->result_array());die;

        $jabatan = $this->db->get_where("tb_user_transmart", ['username' => $username])->row();
        $data['nama'] = $this->data_session['nama'];
        $menu = menus($jabatan->role_id);

        // $data['html_menu'] = $this->User_model->html_menu($menu);
        // print_r($data['html_menu']);die;


        $last = $this->uri->total_segments();
        $record_num = '';
        for ($i = 1; $i <= $last; $i++) {
            $record_num .= $this->uri->segment($i) . "/";
        }

        $menu_query = $this->db->get_where('tb_menu_transmart', ['file' => $record_num])->row_array();
        $id_menu = $menu_query['id_menu'];
        $id_role = $jabatan->role_id;
        $get_data_iframe = $this->db->query("SELECT * FROM tb_role_iframe as a LEFT JOIN tb_menu_transmart as b on a.id_menu = b.id_menu LEFT JOIN tb_iframe as c on c.id_iframe = a.id_iframe LEFT JOIN tb_user_role as d on a.id_role = d.role_id WHERE a.id_menu = '$id_menu' AND id_role = '$id_role'")->row();

        if ($get_data_iframe) {
            $data['iframe_name'] = $get_data_iframe->iframe_name;
            $data['iframe_tag'] = $get_data_iframe->iframe_tag;
        } else {
            $data['iframe_name'] = '';
            $data['iframe_tag'] = "tidak ada tampilan dashboard untuk anda";
        }

        $data['menus'] = $menu;
        $data['title'] = 'Dashboard Halaman Utama';
        $data['menu_header'] = 'Dashboard';
        $data['main_menu'] = 'Dashbord Halaman Utama';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Dashboard/Dashboard_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    
}
