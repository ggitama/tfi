<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_c extends CI_Controller
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
        $jabatan = $this->db->get_where("tb_user_transmart",['username'=>$this->data_session['username']])->row()->role_id;
        $menu = menus($jabatan);
        $data['nama'] = $this->data_session['nama'];
        // var_dump($menu);die;
        $data['menus'] = $menu;
        $data['title'] = 'Dashboard';
        $data['menu_header'] = 'Dashboard';
        $data['main_menu'] = 'Halamaan Utama';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Dashboard/Dashboard_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    function csrf_token(){
        $token = $this->security->get_csrf_hash();
        echo $token;
    }
}
