<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Create_file extends CI_Controller
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
        $menus = $this->db->query("SELECT
        *
        FROM
            tb_menu_transmart
        WHERE parent IN(39,41,42,43,44,45,46,47,48,49,50)")->result_array();


        foreach ($menus as $menu) :
            // $name_file = "Store_" . str_replace(' ', '_', $menu['menu_name']);
            // $menu_name = $menu['menu_name'];
            // $view_ = $menu['file'].'_v';

            // $fileController = fopen("application/controllers/" . $menu['file'] . ".php", "w") or die("Unable to open file!");
            // $value_file = $this->value_files($name_file, $menu_name,$view_);
            // $txt = $value_file;
            // fwrite($fileController, $txt);

            // $fileView = fopen("application/views/" . $view_ . ".php", "w") or die("Unable to open file!");
            // $value_file_2 = $this->value_file_2();
            // fwrite($fileView, $value_file_2);

            // fclose($fileController);
            // fclose($fileView);
            
        endforeach;
    }


    function value_files($name_file, $menu_name,$view_)
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
        
                $" . "menu_querys = $" . "this->db->get_where('tb_menu_transmart', ['file' => $" . "record_num])->row_array();
                if ($" . "menu_querys) {
                    $" . "menu_query = $" . "menu_querys;
                }else{
                    $" . "menu_query2 = $" . "this->db->get_where('tb_menu_transmart', ['file' => substr($" . "record_num,0, -1)])->row_array();
                    $" . "menu_query = $" . "menu_query2;
                }
        
                $" . "id_menu = $" . "menu_query['id_menu'];
                $" . "id_role = $" . "jabatan;
                $" . "get_data_iframe = $" . "this->User_model->get_iframe($" . "id_menu,$" . "id_role);
        
                if ($" . "get_data_iframe) {
                    $" . "data['iframe_name'] = $" . "get_data_iframe->iframe_name;
                    $" . "data['iframe_tag'] = $" . "get_data_iframe->iframe_tag;
                } else {
                    $" . "data['iframe_name'] = '';
                    $" . "data['iframe_tag'] = 'tidak ada tampilan dashboard untuk anda';
                }
        
                $" . "data['menus'] = $" . "menu;
                $" . "data['title'] = '$menu_name';
                $" . "data['menu_header'] = 'Dashboard';
                $" . "data['main_menu'] = '$menu_name';
        
                $" . "this->load->view('template_dashboard/Header_v', $" . "data);
                $" . "this->load->view('$view_" ."', $" . "data);
                $" . "this->load->view('template_dashboard/Footer_v', $" . "data);
            }
        }";
        return  $values;
    }

    function value_file_2()
    {
        $html_view = "<div class='row'>
                    <div class='col-md-12'>
                        <h2 class='text-center'><?= $"."iframe_name ?></h2>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <?= $"."iframe_tag ?>
                    </div>
                </div>";

        return $html_view;
    }
}
