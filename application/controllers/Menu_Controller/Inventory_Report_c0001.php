<?php
        defined('BASEPATH') or exit('No direct script access allowed');
        
        class Inventory_Report_c0001 extends CI_Controller
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
        

                $menu_query = $this->db->get_where('tb_menu_transmart', ['file' => $record_num])->row_array();
                $id_menu = $menu_query['id_menu'];
                $id_role = $jabatan;
                $get_data_iframe = $this->db->query("SELECT * FROM tb_role_iframe as a LEFT JOIN tb_menu_transmart as b on a.id_menu = b.id_menu LEFT JOIN tb_iframe as c on c.id_iframe = a.id_iframe LEFT JOIN tb_user_role as d on a.id_role = d.role_id WHERE a.id_menu = $id_menu AND id_role = $id_role")->row();

                if ($get_data_iframe) {
                    $template_iframe = $this->db->get('template_iframe')->row();
                    $data['iframe_name'] = $get_data_iframe->iframe_name;
                    $iframe_tag = $get_data_iframe->iframe_tag;
                    $data['iframe_tag'] = '<iframe    src="https://transmart.bankmega.com/public/question/a2f9e707-f570-4919-ad0f-95d9fab7b3f4"    frameborder="0"    width="100%"    height="1200"    allowtransparency></iframe>';
                } else {
                    $data['iframe_name'] = '';
                    $data['iframe_tag'] = `tidak ada tampilan dashboard untuk anda`;
                }
                

                // if ($menu_querys) {
                //     $menu_query = $menu_querys;
                // }else{
                //     $menu_query2 = $this->db->get_where('tb_menu_transmart', ['file' => substr($record_num,0, -1)])->row_array();
                //     $menu_query = $menu_query2;
                // }
        
                // $id_menu = $menu_query['id_menu'];
                // $id_role = $jabatan;
                // $get_data_iframe = $this->User_model->get_iframe($id_menu,$id_role);
        
                // if ($get_data_iframe) {
                //     $data['iframe_name'] = $get_data_iframe->iframe_name;
                //     $data['iframe_tag'] = $get_data_iframe->iframe_tag;
                // } else {
                //     $data['iframe_name'] = '';
                //     $data['iframe_tag'] = 'tidak ada tampilan dashboard untuk anda';
                // }
        
                $data['menus'] = $menu;
                $data['title'] = 'Inventory Report';
                $data['menu_header'] = 'Dashboard';
                $data['main_menu'] = 'Inventory Report';
        
                $this->load->view('template_dashboard/Header_v', $data);
                $this->load->view('Menu_View/Inventory_Report_v0001', $data);
                $this->load->view('template_dashboard/Footer_v', $data);
            }
        }