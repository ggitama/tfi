<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_c extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('Curl');
        // $this->API = "QJvP4Hox";
        // $this->URL = "http://10.153.192.6/services/check_login.php";
        $this->load->model('User_model');
        $this->session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
        $this->data_session = data_session($this->session_token);
        $this->token = $this->security->get_csrf_hash();
        // $this->flow();
        $this->nocache();
    }

    function nocache()
	{
		// CodeIgniter Framework version:
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
	}

    function flow()
    {
        $remote = $this->get_client_ip();
        $device_id = $this->device_id();
        // var_dump($_SERVER);die;

        
        $str = (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0);
        preg_match('/[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1}/i', $str, $matches, PREG_OFFSET_CAPTURE);

        $sv['ci_last_regenerate'] = $this->session->userdata('__ci_last_regenerate');
        $sv['remote_ip'] = $remote;
        $sv['srcaddr'] = $_SERVER['REMOTE_ADDR'];
        // $sv['srcaddr'] = (!empty($matches[0][0]) ? $matches[0][0] : 0);
        $sv['dstaddr'] = (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        // $sv['path'] = (!empty($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : null;
        $sv['path'] = $_SERVER['REQUEST_URI'];
        $sv['content_type'] = (!empty($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : null;
        $sv['DateTime'] = $_SERVER['REQUEST_TIME'];
        $sv['dev_id'] = $device_id;

        //$this->scan_flow()

        // echo '<pre>';
        // print_r($_SERVER);
        // die;

        $this->User_model->save_flow($sv);
    }

    function get_client_ip()
    {
        $ipaddress = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }


        return $ipaddress;
    }
    
    protected function device_id()
    {
        $device_id = $this->session->userdata('device_id');

        if (empty($device_id)) {
            
            $str = (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0);
            preg_match('/[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1}/i', $str, $matches, PREG_OFFSET_CAPTURE);

            $device_txt = (!empty($_SERVER['HTTP_USER_AGENT']) ?  $_SERVER['HTTP_USER_AGENT'] : null);
            // $device_txt .= (!empty($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null);
            // $device_txt .= (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null);
            $device_txt .= (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null);
            // $device_txt .= (!empty($matches[0][0]) ? $matches[0][0] : 0); //same function REMOTE_ADDR substring by gateway
            $device_txt .= $this->get_client_ip();  // remote_ip


            $set_device_id = md5($device_txt);
            $add2['device_id'] = $set_device_id;
            // $this->session->set_userdata($add2);
          
            return $set_device_id;
            
            // echo '<pre>';
            // print_r($set_device_id);
            // print_r($_SERVER);
            // die;
        }
    }
    protected function scan_flow($path = '/transmart_new/Login_c/do_login')
    {
        // $sv['ci_last_regenerate'] =
        // $this->session->userdata('__ci_last_regenerate');
        // validasi untuk bruteforce maximum 5x
        // $res_scan = $this->scan_flow($path='/welcome/signin');

        // $find['ci_last_regenerate'] =
        $this->session->userdata('__ci_last_regenerate');
        // $find['dev_id'] = $this->session->userdata('device_id');
        $find['dev_id'] = $this->device_id();
        $find['ci_last_regenerate'] = $this->session->userdata('__ci_last_regenerate');
        $find['path'] = $path;
        $find['content_type'] = 'POST';
        $find['UpdatedInDB >= now() - interval 10 minute'] = null;

        $res = $this->User_model->get_flow($find);
        // echo '<pre>';
        // print_r($find);
        // print_r($res);
        // die;
        $max_hit = 7;
        if (!empty($res->jmlh)) {
            if ($res->jmlh > $max_hit) {
                // var_dump($res->jmlh);
                $sts['result'] = 'error';
                $sts['fields']['Failed'] = 'To many attempt, wait for a minutes.';

                return $sts;
            }
        }
    }


    public function index()
    {
        // $this->session->sess_destroy();
        $data['url_bs5'] = base_url('Assets/Login_template/css/bootstrap.min.css');
        // var_dump($_SERVER);die;
        // var_dump($this->session_token);die;
        if ($this->session->userdata($this->session_token)) {
            redirect('Dashboard_performance_c');
        } else {
            $data['title'] = 'LOGIN';
            $this->load->view('Login_v', $data);
        }
    }

    public function do_login()
    {
        // var_dump($_POST);
        if ($this->session->userdata($this->session_token)) {
            redirect('Dashboard_performance_c');
        } else {
            // $data['title'] = 'LOGIN';
            // $this->load->view('Login_v', $data);
            if (!empty($this->input->post())) {
                // $this->session->sess_destroy();
    
                $res_scan = $this->scan_flow('/transmart_new/Login_c/do_login');
                if (!empty($res_scan)) {
                    $msg = 'To many attempt, wait for a minutes.';
                    $icon = 'info';
                    $action = 'Login_c';
                } else {
                    $username = $this->input->post('username', TRUE);
                    $password = $this->input->post('password', TRUE);
                    $msg = '';
                    $icon = '';
                    $action = '';
    
                    $check = $this->User_model->get_user($username);
                    if ($check) {
                        // Check user Ldap atau bukan
                        if ($check->ldap == 'Yes') {
                            $this->cek_ldap($username, $password, $check);
                        } else {
                            if (password_verify($password, $check->password)) {
                                // check check log activity
                                $log_activity_user = $this->db->get_where('log_activity', ['username' => $username])->num_rows();
                                // check is active 
                                if ($check->is_active == 1) {

                                if ($check->ldap == 'No' && $log_activity_user < 1) {
                                    $data_ses = [
                                        'username' => $username,
                                    ];
                                    $this->session->set_userdata($data_ses);
                                    $msg .= 'Please Change Your Password';
                                    $icon .= 'info';
                                    $action .= 'Change_password_out_c/';
                                } else {
                                    $log_activity_user2 = $this->db->query("SELECT *,CASE WHEN 
                                    TIME_TO_SEC(TIMEDIFF( NOW(), date_in )) > 1800 THEN SEC_TO_TIME(1800) ELSE TIMEDIFF( NOW(), date_in ) END as long_time,
                                    CASE WHEN TIME_TO_SEC(TIMEDIFF( NOW(), date_in )) > 1800 THEN DATE_ADD(date_in,INTERVAL 30 MINUTE) ELSE NOW() END as time_out
                                     FROM log_activity WHERE username = '$username' AND status = 1 ORDER BY id DESC LIMIT 1")->row();

                                    if($log_activity_user2){
                                        $set_update_log = [
                                            'date_out' => date('Y-m-d H:i:s'),
                                            'status' => '0',
                                        ];
                                        $this->db->set($set_update_log);
                                        $this->db->where('id',$log_activity_user2->id);
                                        $this->db->update('log_activity');

                                        // check log metabase
                                        $log_metabase = $this->User_model->check_log_from_metabase($check->username);
                                        $id_log_metabase = $log_metabase->id;

                                        // update log for metabase
                                        $this->User_model->update_log_for_metabase($check->username,$id_log_metabase,$log_activity_user2->long_time,$log_activity_user2->time_out);
                                    }
                                        $data_log = [
                                            'username' => $username,
                                            'status' => '1',
                                            'ipaddr' => $_SERVER['REMOTE_ADDR'],
                                            'date_in' => date('Y-m-d H:i:s'),
                                            'date' => date('Y-m-d')
                                        ];
                                        $this->db->insert('log_activity', $data_log);

                                        // insert log for metabase
                                        $id_log_dashboard = $this->User_model->insert_log_for_metabase($check->username);
                                    
                                    
                                    $this->User_model->update_last_login($check->username);
                                    $get_user_update = $this->db->get_where('tb_user_transmart', ['username' => $username])->row_array();
    
                                    // $this->session->sess_destroy();
                                    $data_session = [
                                        $this->session_token => [
                                            'username' => $username,
                                            'last_login' => $get_user_update['last_login'],
                                            'nama' => $get_user_update['nama'],
                                            'date_in' => date('Y-m-d H:i:s'),
                                            'ipaddr' => $_SERVER['REMOTE_ADDR'],
                                            'id_log_dashboard' => $id_log_dashboard
                                        ],
                                    ];
                                    $this->session->set_userdata($data_session);
                                    $msg .= 'Berhasil Login';
                                    $icon .= 'success';
                                    $action .= 'Dashboard_performance_c/';
                                    // update log activity
    
                                }
                            } else {
                                $this->flow();
                                $msg .= 'wrong username or password';

                                $icon .= 'error';

                                $action .= 'Login_c';
                            }
                            } else {
                                $this->flow();
                                $msg .= 'wrong username or password';
    
                                $icon .= 'error';
    
                                $action .= 'Login_c';
                            }
                        }
                    } else {
                        $this->flow();
                        $msg .= 'wrong username or password';
    
                        $icon .= 'error';
    
                        $action .= 'Login_c';
                    }
                }
    
                return  $this->message_action($msg, $icon, $action);
            }
        }
    }

    public function cek_ldap($username, $password, $check)
    {
        $msg = '';
        $icon = '';
        $action = '';

        $url = "http://10.153.192.6/services/check_login.php?API=QJvP4Hox&UID=$username&PWD=$password";

        /* Init cURL resource */
        $ch = curl_init($url);

        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* execute request */
        $result = curl_exec($ch);

        /* close cURL resource */
        curl_close($ch);

        // [15.20, 28/12/2022] Syafi: Ini contoh response jika User dan Password match (terdapat angka 0 paling depan):
        // 0|00100080220|Entis Sutrisna|Human Resources|HO|sutrisna@alfa-retail.co.id|CN=Entis Sutrisna,OU=Application,OU=Information Technology,OU=userc4,DC=id,DC=carrefour,DC=com|CN=Edy Susanto,OU=Application,OU=Information Technology,OU=HO,OU=User_TransRetail,DC=id,DC=carrefour,DC=com
        // [15.20, 28/12/2022] Syafi: JIka User dan Password tidak sesuai (terdapat angka 1 paling depan)
        // 1|PASSWORD DO NOT MATCH||||||
        // [15.20, 28/12/2022] Syafi: Jika user belum tersedia (terdapat angka 2 paling depan)
        // 2|USER NOT FOUND||||||
        $explode = explode("|", $result);

        if ($explode[0] == 0) {
            $data_log = [
                'username' => $username,
                'status' => '1',
                'ipaddr' => $_SERVER['REMOTE_ADDR'],
                'date_in' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('log_activity', $data_log);

            $log_activity_user2 = $this->db->query("SELECT * FROM log_activity WHERE username = '$username' ORDER BY id DESC LIMIT 1")->row();
            // var_dump($log_activity_user2->date_in);
            // die;
            $this->User_model->update_last_login($check->username);
            $get_user_update = $this->db->get_where('tb_user_transmart', ['username' => $username])->row_array();

            $data_session = [
                $this->session_token => [
                    'username' => $username,
                    'last_login' => $get_user_update['last_login'],
                    'nama' => $get_user_update['nama'],
                    'date_in' => $log_activity_user2->date_in,
                    'ipaddr' => $log_activity_user2->ipaddr,
                ],
            ];
            $this->session->set_userdata($data_session);
            $msg .= 'Berhasil Login';
            $icon .= 'success';
            $action .= 'Dashboard_performance_c/';
        } elseif ($explode[0] == 1) {
            // $msg .= $explode[1];
            $msg .= "wrong username or password";

            $icon .= 'error';

            $action .= 'Login_c';
        } elseif ($explode[0] == 2) {
            // $msg .= $explode[1];
            $msg .= "wrong username or password";

            $icon .= 'error';

            $action .= 'Login_c';
        }

        return  $this->message_action($msg, $icon, $action);
    }

    public function message_action($msg, $icon, $action)
    {
        // if ($action = 'Login_c') {
        //     $res_scan = $this->scan_flow('/Login_c/do_login');
        //     // var_dump($res_scan);die;
        //     if (!empty($res_scan)) {
        //         return $res_scan;
        //     } else {
        //         $this->session->set_flashdata('msg', '<script>Swal.fire("","' . $msg . '","' . $icon . '")</script>');
        //         redirect($action);
        //     }
        // } else {
        $this->session->set_flashdata('msg', '<script>Swal.fire("","' . $msg . '","' . $icon . '")</script>');
        redirect($action);
        // }
    }

    public function logout()
    {
        $username = $this->data_session['username'];
        $log_activity_user2 = $this->db->query("SELECT *,CASE WHEN 
                                    TIME_TO_SEC(TIMEDIFF( NOW(), date_in )) > 1800 THEN SEC_TO_TIME(1800) ELSE TIMEDIFF( NOW(), date_in ) END as long_time,
                                    CASE WHEN TIME_TO_SEC(TIMEDIFF( NOW(), date_in )) > 1800 THEN DATE_ADD(date_in,INTERVAL 30 MINUTE) ELSE NOW() END as time_out
                                     FROM log_activity WHERE username = '$username' AND status = 1 ORDER BY id DESC LIMIT 1")->row();

        if($log_activity_user2){
            $set_update_log = [
                'date_out' => date('Y-m-d H:i:s'),
                'status' => '0',
            ];
            $this->db->set($set_update_log);
            $this->db->where('id',$log_activity_user2->id);
            $this->db->update('log_activity');

             // check log metabase
            $log_metabase = $this->User_model->check_log_from_metabase($username);
            $id_log_metabase = $log_metabase->id;
            if($id_log_metabase){
                $id_log_metabase = $log_metabase->id;
                // update log for metabase
                $this->User_model->update_log_for_metabase($username,$id_log_metabase,$log_activity_user2->long_time,$log_activity_user2->time_out);
            }
        }

        

        // $data_log = [
        //     'username' => $this->data_session['username'],
        //     'status' => '0',
        //     'ipaddr' => $_SERVER['REMOTE_ADDR'],
        //     'date_in' => $this->data_session['date_in'],
        //     'date_out' => date('Y-m-d H:i:s'),
        // ];
        // $this->db->insert('log_activity', $data_log);
        $this->session->unset_userdata($this->session_token);
        $this->session->sess_destroy();
        redirect('Login_c');
    }
}
