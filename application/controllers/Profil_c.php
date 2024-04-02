<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil_c extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        access_login();
        $this->session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
        $this->data_session = data_session($this->session_token);
        $this->token = $this->security->get_csrf_hash();
    }
    public function index()
    {
        $jabatan = $this->db->get_where("tb_user_transmart",['username'=>$this->data_session['username']])->row()->role_id;
        $menu = menus($jabatan);
        $data['nama'] = $this->data_session['nama'];
        $username =$this->data_session['username'];
        $menu = menus($jabatan);

        $data['user'] = $this->db->get_where('tb_user_transmart', ['username' => $username])->row();
        $data['user'] = $this->db->query("SELECT * FROM tb_user_transmart as a LEFT JOIN tb_user_role as b on a.role_id = b.role_id WHERE a.username = '$username'")->row();
        // print_r($menu);die;

        $data['menus'] = $menu;
        $data['title'] = 'Profil';
        $data['menu_header'] = 'Profil';
        $data['main_menu'] = 'Profil';

        $this->load->view('template_dashboard/Header_v', $data);
        $this->load->view('Profil_v', $data);
        $this->load->view('template_dashboard/Footer_v', $data);
    }
    

    public function change_password()
    {
        $password_confirm = $this->input->post('password_confirm',TRUE);
        $username = $this->data_session['username'];
        // $username = $this->input->post('username',TRUE);
        // var_dump($password_confirm);die;

        $match_password = $password_new != $password_confirm;
        $regex_pass = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=]{8,}$/", $password_confirm);
        // $arr_res = [
        //     'token' => $this->token,
        //     'res' =>  $regex_pass
        // ];
        // echo json_encode($arr_res);
        // die;

        $data_insert = [
            'password' => password_hash($password_confirm,PASSWORD_DEFAULT)
        ];

        if ($regex_pass == 0) {
            $arr_res = [
                'token' => $this->token,
                'res' => 'Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character ! '
            ];
            echo json_encode($arr_res);
        }else{
            $this->db->where("username",$username);
            $this->db->set($data_insert);
            $update = $this->db->update("tb_user_transmart");
    
            if ($update) {
                $arr_res = [
                    'token' => $this->token,
                    'res' => 'oke'
                ];
                echo json_encode($arr_res);
                // echo 'oke';
            }else{
                $arr_res = [
                    'token' => $this->token,
                    'res' => $this->db->error()
                ];
                // echo 'oke';
                // echo $this->db->error();
                echo json_encode($arr_res);
            }
        }

    }

    public function validations()
    {
        $username = $this->data_session['username'];
        $password_old = $this->input->post('password_old',TRUE);
        $password_new = $this->input->post('password_new',TRUE);
        $password_confirm = $this->input->post('password_confirm',TRUE);

        $user = $this->db->get_where('tb_user_transmart', ['username' => $username])->row();

        // check password old
        $minimum_password = strlen($password_new) < 6;
        $match_password = $password_new != $password_confirm;
        $regex_pass = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=]{8,}$/", $password_confirm);
        
        if (password_verify($password_old, $user->password) == false) {
            $msg = "Old password doesn't match";
        }elseif ($regex_pass == 0) {
            $msg = "Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character";
        }elseif (password_verify($password_new, $user->password)) {
            $msg = "The new password is the same as the old password";
        }elseif ($match_password) {
            $msg = "The new password and the confirmation password don't match";
        }else{
            $msg = 'oke';
        }

        $arr_res = [
            'token' => $this->token,
            'res' =>  $msg
        ];
        echo json_encode($arr_res);
        // echo $msg;
    }
}
