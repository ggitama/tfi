<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Change_password_out_c extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->token = $this->security->get_csrf_hash();
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            $data['token'] = $this->token;
            $data['title'] = 'Change Password';
            $data['username'] = $_SESSION['username'];
            $this->load->view('Change_password_out_v', $data);
        } else {
            redirect('Login_c');
        }
    }

    public function validations()
    {
        $username = $_SESSION['username'];
        $password = $this->input->post('password', TRUE);
       
        // $password_new = $this->input->post('password_new',TRUE);
        $password_confirm = $this->input->post('password_confirm', TRUE);

        $user = $this->db->get_where('tb_user_transmart', ['username' => $username])->row();
        // var_dump()

        // check password old
        $minimum_password = strlen($password) < 6;
        $match_password = $password != $password_confirm;
        $regex_pass = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=-])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=-]{8,}$/", $password_confirm);

        // if (password_verify($password_old, $user->password) == false) {
        //     $msg = "Old password doesn't match";
        // }else
        if ($regex_pass == 0) {
            // $msg = "Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character";
            $arr_res = [
                'token' => $this->token,
                'res' => 'Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character'
            ];
        } elseif (password_verify($password, $user->password)) {
            // $msg = "The new password is the same as the old password";
            $arr_res = [
                'token' => $this->token,
                'res' => 'The new password is the same as the old password'
            ];
        } elseif ($match_password) {
            // $msg = "The new password and the confirmation password don't match";
            $arr_res = [
                'token' => $this->token,
                'res' => "The new password and the confirmation password don't match"
            ];
        } else {
            // $msg = 'oke';
            $arr_res = [
                'token' => $this->token,
                'res' => "oke"
            ];
        }
        $arr_res = [
            'token' => $this->token,
            'res' => "oke"
        ];
        echo json_encode($arr_res);
    }


    public function change_password()
    {
        $password_confirm = $this->input->post('password_confirm', TRUE);
        $username = $_SESSION['username'];
        $data_insert = [
            'password' => password_hash($password_confirm, PASSWORD_DEFAULT)
        ];

        $regex_pass = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=-])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=-]{8,}$/", $password_confirm);

        if ($regex_pass == 0) {
            $arr_res = [
                'token' => $this->token,
                'res' => 'Password Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character ! '
            ];
            echo json_encode($arr_res);

        }else{
            $this->db->where("username", $username);
            $this->db->set($data_insert);
            $update = $this->db->update("tb_user_transmart");
    
            $check = $this->User_model->get_user($username);
            $this->User_model->update_last_login($check->username);
            $get_user_update = $this->db->get_where('tb_user_transmart', ['username' => $username])->row_array();
            // $this->session->sess_destroy();
            $data = [
                'role_id' => $check->role_id,
                'username' => $username,
                'nama' => $get_user_update['nama'],
                'last_login' => $get_user_update['last_login'],
                'jabatan' => $get_user_update['role_id'],
                'user_logged_transmart' => true
            ];
            $this->session->set_userdata($data);
            $data_log = [
                'username' => $username,
                'status' => '1',
                'ipaddr' => $_SERVER['REMOTE_ADDR'],
                'date_in' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('log_activity', $data_log);
    
            if ($update) {
                $arr_res = [
                    'token' => $this->token,
                    'res' => "oke"
                ];
                echo json_encode($arr_res);
            } else {
                $arr_res = [
                    'token' => $this->token,
                    'res' => $this->db->error()
                ];
                echo json_encode($arr_res);
                // echo $this->db->error();
            }
        }
    }
}
