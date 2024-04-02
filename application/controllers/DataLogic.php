<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataLogic extends CI_Controller
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
        $username = $this->data_session['username'];

        $jabatan = $this->db->get_where("tb_user_transmart", ['username' => $username])->row();
        $data['nama'] = $this->data_session['nama'];
        $menu = menus($jabatan->role_id);

        $data['listStore'] = $this->db->get('listStore2')->result_array();

        $data['menus'] = $menu;
        $data['title'] = 'Data Logic';
        $data['menu_header'] = 'Dashboard';
        $data['main_menu'] = 'Data Logic';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('DataLogicV', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }

    
}
