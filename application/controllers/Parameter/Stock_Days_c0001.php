<?php
        defined('BASEPATH') or exit('No direct script access allowed');

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        use PhpOffice\PhpSpreadsheet\Style\Border;
        use PhpOffice\PhpSpreadsheet\Style\Color;
        
        class Stock_Days_c0001 extends CI_Controller
        {
            public function __construct()
            {
                parent::__construct();
                $this->load->model('User_model');
                $this->load->model('M_Stock_Days');
                access_login();
                $this->session_token =  hash('sha256', $_SERVER['SCRIPT_NAME']);
                $this->data_session = data_session($this->session_token);
                $this->token = $this->security->get_csrf_hash();
            }
            public function index()
            {
                $jabatan = $this->db->get_where('tb_user_transmart', ['username' => $this->data_session['username']])->row()->role_id;
                $menu = menus($jabatan);
                $data['nama'] = $this->data_session['nama'];
                $data_user = $this->db->get_where('tb_user_transmart', ['username' => $this->data_session['username']])->row();

                $this->db->where("(store_code IS NOT NULL OR store_code != '')");
                $data['data_user_store'] = $this->db->get_where('tb_user_transmart',
                 [
                    'username' => $this->data_session['username']
                 ]
                 )->row();
                
                // as is 3 store 
                $as_is_3store = "AND store_code IN ('10011','10072','10093')";

                if($data['data_user_store']){
                    // $this->db->where(['store_code'=>$data['data_user_store']->store_code]);
                    $store_code_user = $data['data_user_store']->store_code;
                    $where_store_codes = " AND store_code = '$store_code_user'";
                    $data['business_unit'] = $this->M_Stock_Days->biu_store($store_code_user);
                }elseif (isset($data_user->store_codes_regional)) {
                    $where_store_codes = " AND store_code IN ($data_user->store_codes_regional) $as_is_3store";
                }else{
                    $where_store_codes = "$as_is_3store";
                }
                
                $data['store'] = $this->db->query("SELECT * FROM store WHERE 1 $where_store_codes")->result();

                // $biu = $this->M_Stock_Days->biu();
                // $maping = $this->M_Stock_Days->maping();
                // $maping = $this->M_Stock_Days->ou_trshd_fam(1,2);
                // $maping = $this->M_Stock_Days->biu();
                // echo '<pre>';
                // print_r($maping);die;

                
                // print_r($this->db->last_query());die;
                // print_r(isset($data_user->store_codes_regional));die;
                // print_r($data['data_user_store']);die;
                // print_r($data['store']);die;
        
                $data['menus'] = $menu;
                $data['title'] = 'Stock Days';
                $data['menu_header'] = 'Dashboard';
                $data['main_menu'] = 'Stock Days';
        
                $this->load->view('template_dashboard/Header_v', $data);
                $this->load->view('Parameter/Stock_Days_v0001', $data);
                $this->load->view('template_dashboard/Footer_v', $data);
            }

            function data_biu_store(){
                $store = $this->input->post('store');
                
                $biu_store = json_encode($this->M_Stock_Days->biu_store($store));

                $arr_res = [
                    'token' => $this->token,
                    'res' => $biu_store
                ];
                echo json_encode($arr_res);

            }
            function data_dept(){
                $biu = $this->input->post('biu');
                $store = $this->input->post('store');
                
                $dept = json_encode($this->M_Stock_Days->dept($biu,$store));

                $arr_res = [
                    'token' => $this->token,
                    'res' => $dept
                ];
                echo json_encode($arr_res);

            }

            function data_cat(){
                $dept = $this->input->post('dept');
                $store = $this->input->post('store');
                
                $cat = json_encode($this->M_Stock_Days->categoty($dept,$store));

                $arr_res = [
                    'token' => $this->token,
                    'res' => $cat
                ];
                echo json_encode($arr_res);
            }

            function data_fam(){
                $cat = $this->input->post('cat');
                $store = $this->input->post('store');
                
                $fam = json_encode($this->M_Stock_Days->family($cat,$store));

                $arr_res = [
                    'token' => $this->token,
                    'res' => $fam
                ];
                echo json_encode($arr_res);
            }

            function data_sub_fam(){
                $fam = $this->input->post('fam');
                $store = $this->input->post('store');
                
                $subfam = json_encode($this->M_Stock_Days->subfamily($fam,$store));

                $arr_res = [
                    'token' => $this->token,
                    'res' => $subfam
                ];
                echo json_encode($arr_res);
            }

            function modal_add_fam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $fam;

                if($fam == 'all_fam'){
                    $fams = $this->M_Stock_Days->family($cat,$store);
                    $data['fams'] = $fams;
                }else{
                    $data['fams'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);

                }

                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Stock_Days_Fams', $data, TRUE);
                echo $html_modal;
            }

            function modal_edit_fam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('str_code',TRUE);
                $fam = $this->input->post('famcode',TRUE);


                $biu = substr($fam,0,1);
                $dept = substr($fam,0,2);
                $cat = substr($fam,0,3);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fams'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);

                $data_sdFam = $this->M_Stock_Days->ou_trshd_fam_detail($store,$fam);
                $data['stock_day_min'] = $data_sdFam->stock_day_min;
                $data['stock_day_max'] = $data_sdFam->stock_day_max;


                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Edit_Stock_Days_Fams', $data, TRUE);
                echo $html_modal;
            }

            function delete_modal_fam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('str_code',TRUE);
                $fam = $this->input->post('famcode',TRUE);


                $biu = substr($fam,0,1);
                $dept = substr($fam,0,2);
                $cat = substr($fam,0,3);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fams'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);

                $data_sdFam = $this->M_Stock_Days->ou_trshd_fam_detail($store,$fam);
                $data['stock_day_min'] = $data_sdFam->stock_day_min;
                $data['stock_day_max'] = $data_sdFam->stock_day_max;


                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Delete_Stock_Days_Fams', $data, TRUE);
                echo $html_modal;
            }

            function List_query_fam(){
                $limit = $this->input->post('length', TRUE);
                $start = $this->input->post('start', TRUE);
                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sub_fam = $this->input->post('sub_fam',TRUE);
                // $search = $this->input->post('search', TRUE)['value'];
                // $iframe_name_w = $search == '' ? "" : " AND iframe_name LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";
                $data_fam = $this->M_Stock_Days->ou_trshd_fam($limit,$start,$store,$fam,$cat);
                $data_fam_all = $this->M_Stock_Days->ou_trshd_fam_count($store,$fam,$cat);
                
                // var_dump($data_fam);die;
                // $query =     "SELECT * FROM tb_iframe WHERE 1 $iframe_name_w ";
        
                // $count_query = $this->db->query($query)->result_array();
        
                // $query_exec = $this->db->query($query . $limit_cond, array((int)$limit, (int)$start))->result_array();
        
                $res_data = array(
                    'draw' =>  $this->input->post('draw', TRUE), // Ini dari datatablenya    
                    'recordsTotal' => count($data_fam_all),
                    'recordsFiltered' => count($data_fam_all),
                    'data' => $data_fam,
                    'token' => $this->security->get_csrf_hash()
                );
        
                echo json_encode($res_data);
            }

            function List_query_sub_fam(){
                $limit = $this->input->post('length', TRUE);
                $start = $this->input->post('start', TRUE);
                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sub_fam = $this->input->post('sub_fam',TRUE);

                $data_subfam = $this->M_Stock_Days->ou_trshd_subfam($limit,$start,$store,$sub_fam,$fam);
                $data_subfam_all = $this->M_Stock_Days->ou_trshd_subfam_count($store,$sub_fam,$fam);
                // var_dump($data_subfam_all);die;

                $res_data = array(
                    'draw' =>  $this->input->post('draw', TRUE), // Ini dari datatablenya    
                    'recordsTotal' => count($data_subfam_all),
                    'recordsFiltered' => count($data_subfam_all),
                    'data' => $data_subfam,
                    'token' => $this->security->get_csrf_hash()
                );
        
                echo json_encode($res_data);
                
            }

            function edit_sdFam(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $famcode = $this->input->post('famcode',TRUE);
                $sdMin = $this->input->post('sdMin',TRUE);
                $sdMax = $this->input->post('sdMax',TRUE);

                 // check stock days fam store sudah ada atau belum
                 $data_update = [
                    'stock_day_min' => $sdMin,
                    'stock_day_max' => $sdMax,
                    'updated_date' => date('Y-m-d'),
                    'updated_by' => $username
                 ];

                 $update = $this->M_Stock_Days->ou_trshd_fam_update($data_update,$str_code,$famcode);
                 if($update){
                    $msg = 'Data Berhasil diupdate';
                    $value = 1;
                }else{
                    $msg = 'Failed';
                    $value = 0;
                }

                $arr_res = [
                    'msg' => $msg,
                    'value' => $value,
                    'token' => $this->token
                ];

                echo json_encode($arr_res);
            }

            function delete_sdFam(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $famcode = $this->input->post('famcode',TRUE);
                $sdMin = $this->input->post('sdMin',TRUE);
                $sdMax = $this->input->post('sdMax',TRUE);

                $delete = $this->M_Stock_Days->ou_trshd_fam_delete($str_code,$famcode);
                if($delete){
                    $msg = 'Data Berhasil dihapus';
                    $value = 1;
                }else{
                    $msg = 'Failed';
                    $value = 0;
                }
                
                $arr_res = [
                    'msg' => $msg,
                    'value' => $value,
                    'token' => $this->token
                ];

                echo json_encode($arr_res);
            }


            function save_sdFam(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $str_name = $this->input->post('str_name',TRUE);
                $famcode = $this->input->post('famcode',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $sdMin = $this->input->post('sdMin',TRUE);
                $sdMax = $this->input->post('sdMax',TRUE);


                $famname = $this->M_Stock_Days->family_detail($cat,$str_code,$famcode)->fam_name;

                // check stock days fam store sudah ada atau belum
                $data_sdFam = $this->M_Stock_Days->ou_trshd_fam_detail($str_code,$famcode);
                if($data_sdFam){
                    $msg = 'Data Sudah ada Harap Update/Edit data tersebut';
                    $value = 0;
                }else{
                    $data_insert = [
                        'str_code' => $str_code,
                        'str_name' => $str_name,
                        'famcode' => $famcode,
                        'fam_name' => $famname,
                        'stock_day_min' => $sdMin,
                        'stock_day_max' => $sdMax,
                        'updated_date' => date('Y-m-d'),
                        'updated_by' => $username
                    ];
                    $insert = $this->M_Stock_Days->ou_trshd_fam_insert($data_insert);
                    if($insert){
                        $msg = 'Data Berhasil disimpan';
                        $value = 1;
                    }else{
                        $msg = 'Failed';
                        $value = 0;
                    }
                }

                $arr_res = [
                    'msg' => $msg,
                    'value' => $value,
                    'token' => $this->token
                ];

                echo json_encode($arr_res);
            }



            // SUB FAMILY
            function modal_add_subfam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sub_fam = $this->input->post('sub_fam',TRUE);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);
                $data['sub_fam'] = $sub_fam;

                if($sub_fam == 'all_subfam'){
                    $subfams = $this->M_Stock_Days->subfamily($fam,$store);
                    $data['subfams'] = $subfams;
                }else{
                    $data['subfams'] = $this->M_Stock_Days->subfamily_detail($fam,$store,$sub_fam);

                }
                // var_dump($data['subfams']);die;

                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Stock_Days_subFams', $data, TRUE);
                echo $html_modal;
            }

            function save_sdSubFam(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $str_name = $this->input->post('str_name',TRUE);
                $subfamcode = $this->input->post('subfamcode',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sdMin = $this->input->post('sdMin',TRUE);
                $sdMax = $this->input->post('sdMax',TRUE);


                $subfam_name = $this->M_Stock_Days->subfamily_detail($fam,$str_code,$subfamcode)->subfam_name;

                // check stock days fam store sudah ada atau belum
                $data_sdSubFam = $this->M_Stock_Days->ou_trshd_subfam_detail($str_code,$subfamcode);
                // var_dump($data_sdSubFam);die;
                if($data_sdSubFam){
                    $msg = 'Data Sudah ada Harap Update/Edit data tersebut';
                    $value = 0;
                }else{
                    $data_insert = [
                        'str_code' => $str_code,
                        'str_name' => $str_name,
                        'subfamcode' => $subfamcode,
                        'subfam_name' => $subfam_name,
                        'stock_day_min' => $sdMin,
                        'stock_day_max' => $sdMax,
                        'updated_date' => date('Y-m-d'),
                        'updated_by' => $username
                    ];
                    $insert = $this->M_Stock_Days->ou_trshd_subfam_insert($data_insert);
                    if($insert){
                        $msg = 'Data Berhasil disimpan';
                        $value = 1;
                    }else{
                        $msg = 'Failed';
                        $value = 0;
                    }
                }

                $arr_res = [
                    'msg' => $msg,
                    'value' => $value,
                    'token' => $this->token
                ];

                echo json_encode($arr_res);
            }

            function modal_edit_subfam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('str_code',TRUE);
                $subfam = $this->input->post('subfamcode',TRUE);


                $biu = substr($subfam,0,1);
                $dept = substr($subfam,0,2);
                $cat = substr($subfam,0,3);
                $fam = substr($subfam,0,4);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);
                $data['subfam'] = $this->M_Stock_Days->subfamily_detail($fam,$store,$subfam);

                $data_sdSubFam = $this->M_Stock_Days->ou_trshd_subfam_detail($store,$subfam);
                $data['stock_day_min'] = $data_sdSubFam->stock_day_min;
                $data['stock_day_max'] = $data_sdSubFam->stock_day_max;


                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Edit_Stock_Days_Sub_Fams', $data, TRUE);
                echo $html_modal;
            }

            function edit_sdSubFam(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $subfamcode = $this->input->post('subfamcode',TRUE);
                $sdMin = $this->input->post('sdMin',TRUE);
                $sdMax = $this->input->post('sdMax',TRUE);

                 // check stock days fam store sudah ada atau belum
                 $data_update = [
                    'stock_day_min' => $sdMin,
                    'stock_day_max' => $sdMax,
                    'updated_date' => date('Y-m-d'),
                    'updated_by' => $username
                 ];

                 $update = $this->M_Stock_Days->ou_trshd_subfam_update($data_update,$str_code,$subfamcode);
                 if($update){
                    $msg = 'Data Berhasil diupdate';
                    $value = 1;
                }else{
                    $msg = 'Failed';
                    $value = 0;
                }

                $arr_res = [
                    'msg' => $msg,
                    'value' => $value,
                    'token' => $this->token
                ];

                echo json_encode($arr_res);
            }

            function delete_modal_subfam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('str_code',TRUE);
                $subfam = $this->input->post('subfamcode',TRUE);


                $biu = substr($subfam,0,1);
                $dept = substr($subfam,0,2);
                $cat = substr($subfam,0,3);
                $fam = substr($subfam,0,4);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);
                $data['subfam'] = $this->M_Stock_Days->subfamily_detail($fam,$store,$subfam);

                $data_sdSubFam = $this->M_Stock_Days->ou_trshd_subfam_detail($store,$subfam);
                $data['stock_day_min'] = $data_sdSubFam->stock_day_min;
                $data['stock_day_max'] = $data_sdSubFam->stock_day_max;


                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Delete_Stock_Days_Sub_Fams', $data, TRUE);
                echo $html_modal;
            }

            function delete_sdSubFam(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $famcode = $this->input->post('famcode',TRUE);
                $subfamcode = $this->input->post('subfamcode',TRUE);
                $sdMin = $this->input->post('sdMin',TRUE);
                $sdMax = $this->input->post('sdMax',TRUE);

                $delete = $this->M_Stock_Days->ou_trshd_subfam_delete($str_code,$subfamcode);
                if($delete){
                    $msg = 'Data Berhasil dihapus';
                    $value = 1;
                }else{
                    $msg = 'Failed';
                    $value = 0;
                }
                
                $arr_res = [
                    'msg' => $msg,
                    'value' => $value,
                    'token' => $this->token
                ];

                echo json_encode($arr_res);
            }

            function modal_upload_fam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);

                $data['store'] = $store;
                $data['biu'] = $biu;
                $data['dept'] = $dept;
                $data['cat'] = $cat;
                $data['fam'] = $fam;

                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $data['token'] = $this->token;
                $html_modal = $this->load->view('Modal/Modal_upload_fam_stock_day', $data, TRUE);
                echo $html_modal;
            }



            public function export_to_excel_fam()
            {
                $store = $this->input->get('store',TRUE);
                $biu = $this->input->get('biu',TRUE);
                $dept = $this->input->get('dept',TRUE);
                $cat = $this->input->get('cat',TRUE);
                $fam = $this->input->get('fam',TRUE);


                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                if($fam =='all_fam'){
                    $data['fam'] = $this->M_Stock_Days->family($cat,$store);
                }else{
                    $data['fam'] = $this->M_Stock_Days->family_store_detail2($store,$fam);
                }

                // $data_ip = $this->db->get_where('tb_instruksi_pembayaran',['no_instruksi'])
                $username =  $this->data_session['username'];

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleArrayHead = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];



                $sheet->mergeCells('A1:A3');
                $sheet->mergeCells('B1:B3');
                $sheet->mergeCells('C1:C3');
                $sheet->mergeCells('D1:D3');
                $sheet->mergeCells('E1:E3');
                $sheet->mergeCells('F1:G1');


                $sheet->setCellValue('A1', 'No')->getStyle('A1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('B1', 'Family')->getStyle('B1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('C1', 'Family Name')->getStyle('B1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('D1', 'Store Code')->getStyle('C1')->applyFromArray($styleArray);
                $sheet->setCellValue('E1', 'Store Name')->getStyle('D1')->applyFromArray($styleArray);
                $sheet->setCellValue('F1', 'Stock Days (day)')->getStyle('E1')->applyFromArray($styleArray);
                $sheet->setCellValue('F2', 'Stock Day Min')->getStyle('E2')->applyFromArray($styleArray);
                $sheet->setCellValue('G2', 'Stock Day Max')->getStyle('F2')->applyFromArray($styleArray);
                $sheet->setCellValue('F3', 'Less than')->getStyle('E3')->applyFromArray($styleArray);
                $sheet->setCellValue('G3', 'more than')->getStyle('F3')->applyFromArray($styleArray);

                $sheet->setCellValue('I1', 'Nilai dari kolom Stock Day Min tidak boleh lebih besar dari nilai kolom Stock Day Max');
               
                $kolom = 4;
                $nomor = 1;
                foreach ($data['fam'] as $proses) {
                    $sheet
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $proses->famcode)
                        ->setCellValue('C' . $kolom, $proses->fam_name)
                        ->setCellValue('D' . $kolom, $data['store']->store_code)
                        ->setCellValue('E' . $kolom, $data['store']->store_name);
                        
                    $sheet->getStyle('A' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('B' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('C' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('D' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('E' . $kolom)->applyFromArray($styleArray);
                    
                    $kolom++;
                    $nomor++;
                }
                $date_now = date('Y-m-dH:i:s');
                
                $writer = new Xlsx($spreadsheet);
                $filename =  'family_stockdays'.$store.$cat.$date_now;
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
            }


            function upload_fam_stockdays(){
                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);

                $extention = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $file_name = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_FILENAME));
                $filename =  str_replace(" ", "_", $file_name);
                $filename =  date('ymdhis') . '~' . str_replace(".", "_", $filename) . '.' . $extention;
                $path_name = 'Assets/Upload_excels/';

                $upload_file = $_FILES['file']['name'];
                if ($upload_file) {
                    $config['allowed_types'] = 'xls|xlsx';
                    $config['upload_path'] = $path_name;
                    $config['file_name'] =  $filename;

                    $this->load->library('upload', $config);

                    // jika Berhasil upload
                    if ($this->upload->do_upload('file')) {
                        $inserts = $this->import_fam_stockdays($path_name, $filename,$cat);
                        if ($inserts['falgs'] == '1') {
                            $arr_res = [
                                'msg' => $inserts['msg'],
                                'value' => '1',
                                'token' => $this->token
                            ];
                            echo json_encode($arr_res);
                        } 
                        elseif ($inserts['falgs'] == '4') {
                            // echo $inserts['msg'];
                            $arr_res = [
                                'msg' => $inserts['msg'],
                                'value' => '4',
                                'token' => $this->token
                            ];
                            echo json_encode($arr_res);
                        } else {
                            $arr_res = [
                                'msg' => $inserts['msg'],
                                'value' => '0',
                                'token' => $this->token
                            ];
                            echo json_encode($arr_res);
                            // echo $inserts['msg'];
                        }
                    } else {
                        $arr_res = [
                            'msg' => $this->upload->display_errors(),
                            'value' => 'z',
                            'token' => $this->token
                        ];
                        echo json_encode($arr_res);
                        // echo $this->upload->display_errors();
                    }
                }
            }

            function import_fam_stockdays($path_name, $filename,$cat)
            {
                // Get the current date/time and convert to an Excel date/time
                $dateTimeNow = time();
                $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($dateTimeNow);
                $table_template = 'template_fam_sd';
                $path_xlsx = FCPATH . $path_name . $filename;
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($path_xlsx);
        
                $d = $spreadsheet->getSheet(0)->toArray();
        
                $validasi_template = $this->validasi_template_fam_stockdays($d, $table_template);
                $validasi_sesuai_cat_and_store = false;
                $validasi_value = false;
                unset($d[0]);
                unset($d[1]);
                unset($d[2]);
        
                $id_log = array();
                $row_nothing = array();
                $row_validasi_sesuai_cat_and_store = array();
                $row_validasi_value = array();
                // var_dump($validasi_template);die;
                if ($validasi_template) {
                    $flag_return = [
                        'falgs' => '2',
                        'msg' => 'Upload Gagal, File yang diupload tidak sesuai template'
                    ];
                    return $flag_return;
                } else {
                    $datas = array();
                    foreach ($d as $key =>  $t) {
                        // var_dump(substr($t[0], 1, 1));
                        // var_dump($t[0], $t[1], substr($t[0], 1, 1)== '1');
                        // check item per store
                        if (isset($t[1]) && isset($t[3]) && isset($t[5]) && isset($t[6])) {
                            $store_t = "$t[3]";
                            $famcode_t = "$t[1]";
                            $array_where = [
                                'famcode' => "$t[1]",
                                'str_code' => "$t[3]"
                            ];
        
                            $check_fam_store = $this->M_Stock_Days->family_detail($cat,$store_t,$famcode_t);
                            // var_dump($check_fam_store);
                            // var_dump($array_where);
    
                            if ($check_fam_store) {
    
                                $check_fam_store2 = $this->M_Stock_Days->ou_trshd_fam_detail($store_t,$famcode_t);
                                if ($check_fam_store2) {
                                    $this->M_Stock_Days->ou_trshd_fam_delete($store_t,$famcode_t);
                                    // $this->db->where($array_where);
                                    // $this->db->delete('item_unorderable');
                                }

                                $get_store = $this->db->get_where('store', ['store_code' =>$store_t])->row();
                                $data["famcode"] = $famcode_t;
                                $data["fam_name"] = $check_fam_store->fam_name;
                                $data["str_code"] = $store_t;
                                $data["str_name"] = $get_store->store_name;
                                $data["stock_day_min"] = $t[5];
                                $data["stock_day_max"] = $t[6];
                                $data["updated_by"] =  $this->data_session['username'];
                                $data["updated_date"] = date('Y-m-d');
                                array_push($datas, $data);
                            } else {
                                $validasi_value = true;
                                array_push($row_nothing, $key);
                            }
                            
                        }
                    }
                    // die;
        
                    if (count($datas) == 0) {
                        if ($validasi_value) {
                            $explode_nothing = implode(',', $row_nothing);
                            $flag_return = [
                                'falgs' => '4',
                                'msg' => "Upload Gagal, terdapat data fail di baris $explode_nothing"
                            ];
                        }  else {
                            $flag_return = [
                                'falgs' => '4',
                                'msg' => 'Upload Gagal, Data dalam file kosong atau terdapat kesalahan data pada excel'
                            ];
                        }
        
                        return $flag_return;
                    } else {
                        // $inserts = $this->db->insert_batch('item_unorderable', $datas);
                        $inserts = $this->M_Stock_Days->ou_trshd_fam_insert_batch($datas);
                        if ($inserts) {
                            if ($validasi_value) {
                                $explode_nothing = implode(',', $row_nothing);
                                $flag_return = [
                                    'falgs' => '1',
                                    'msg' => "Berhasil, dan terdapat data fail di baris $explode_nothing"
                                ];
                            }  else {
                                $flag_return = [
                                    'falgs' => '1',
                                    'msg' => 'Berhasil '.count($datas)
                                ];
                            }
                            
                            return  $flag_return;
                        } else {
                            $flag_return = [
                                'falgs' => '0',
                                'msg' => 'data gagal disimpan'
                            ];
                            return $flag_return;
                        }
                    }
                }
            }


            public function validasi_template_fam_stockdays($header, $table_template)
            {
                $count = count($header[0]);
                $template_excel = $this->db->get($table_template)->result_array();
                $validate = false;
                if ($count == count($template_excel)) {
                    $validate = true;
                }
                


                foreach ($template_excel as $key => $value) {
                    // var_dump($header[$key] == $value['template_header']);
                    // var_dump($header[$key],' - ',$value['template_header']);
                    if ($header[0][$key] == $value['template_header']) {
                        $validate = false;
                    } else {
                        return true;
                    }
                    // var_dump($validate);
                }
                // var_dump($validate);die;
                // die;
                return $validate;
            }



            function modal_upload_subfam_stock_day(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $subfam = $this->input->post('subfam',TRUE);

                $data['store'] = $store;
                $data['biu'] = $biu;
                $data['dept'] = $dept;
                $data['cat'] = $cat;
                $data['fam'] = $fam;
                $data['subfam'] = $subfam;

                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $data['token'] = $this->token;
                $html_modal = $this->load->view('Modal/Modal_upload_subfam_stock_day', $data, TRUE);
                echo $html_modal;
            }

            function export_to_excel_subfam(){
                $store = $this->input->get('store',TRUE);
                $biu = $this->input->get('biu',TRUE);
                $dept = $this->input->get('dept',TRUE);
                $cat = $this->input->get('cat',TRUE);
                $fam = $this->input->get('fam',TRUE);
                $subfam = $this->input->get('subfam',TRUE);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $this->M_Stock_Days->family($cat,$store);
                if($subfam == 'all_subfam'){
                    $data['subfam'] = $this->M_Stock_Days->subfamily($fam,$store);
                }else{
                    $data['subfam'] = $this->M_Stock_Days->subfamily_detail2($fam,$store,$subfam);
                }
                // var_dump($data['subfam']);die;

                $username =  $this->data_session['username'];

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleArrayHead = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];



                $sheet->mergeCells('A1:A3');
                $sheet->mergeCells('B1:B3');
                $sheet->mergeCells('C1:C3');
                $sheet->mergeCells('D1:D3');
                $sheet->mergeCells('E1:E3');
                $sheet->mergeCells('F1:G1');


                $sheet->setCellValue('A1', 'No')->getStyle('A1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('B1', 'Sub Family')->getStyle('B1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('C1', 'Sub Family Name')->getStyle('B1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('D1', 'Store Code')->getStyle('C1')->applyFromArray($styleArray);
                $sheet->setCellValue('E1', 'Store Name')->getStyle('D1')->applyFromArray($styleArray);
                $sheet->setCellValue('F1', 'Stock Days (day)')->getStyle('E1')->applyFromArray($styleArray);
                $sheet->setCellValue('F2', 'Stock Day Min')->getStyle('E2')->applyFromArray($styleArray);
                $sheet->setCellValue('G2', 'Stock Day Max)')->getStyle('F2')->applyFromArray($styleArray);
                $sheet->setCellValue('F3', 'Less than')->getStyle('E3')->applyFromArray($styleArray);
                $sheet->setCellValue('G3', 'more than')->getStyle('F3')->applyFromArray($styleArray);

                $sheet->setCellValue('I1', 'Nilai dari kolom Stock Day Min tidak boleh lebih besar dari nilai kolom Stock Day Max');
               
                $kolom = 4;
                $nomor = 1;
                foreach ($data['subfam'] as $proses) {
                    $sheet
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $proses->subfamcode)
                        ->setCellValue('C' . $kolom, $proses->subfam_name)
                        ->setCellValue('D' . $kolom, $data['store']->store_code)
                        ->setCellValue('E' . $kolom, $data['store']->store_name);
                        
                    $sheet->getStyle('A' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('B' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('C' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('D' . $kolom)->applyFromArray($styleArray);
                    $sheet->getStyle('E' . $kolom)->applyFromArray($styleArray);
                    
                    $kolom++;
                    $nomor++;
                }
                $date_now = date('Y-m-dH:i:s');
                
                $writer = new Xlsx($spreadsheet);
                $filename =  'subfamily_stockdays'.$store.$fam.$date_now;
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
            }

            function upload_subfam_stockdays(){
                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $subfam = $this->input->post('subfam',TRUE);

                $extention = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $file_name = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_FILENAME));
                $filename =  str_replace(" ", "_", $file_name);
                $filename =  date('ymdhis') . '~' . str_replace(".", "_", $filename) . '.' . $extention;
                $path_name = 'Assets/Upload_excels/';

                $upload_file = $_FILES['file']['name'];
                if ($upload_file) {
                    $config['allowed_types'] = 'xls|xlsx';
                    $config['upload_path'] = $path_name;
                    $config['file_name'] =  $filename;

                    $this->load->library('upload', $config);

                    // jika Berhasil upload
                    if ($this->upload->do_upload('file')) {
                        $inserts = $this->import_subfam_stockdays($path_name, $filename,$fam,$subfam);
                        if ($inserts['falgs'] == '1') {
                            $arr_res = [
                                'msg' => $inserts['msg'],
                                'value' => '1',
                                'token' => $this->token
                            ];
                            echo json_encode($arr_res);
                        } 
                        elseif ($inserts['falgs'] == '4') {
                            // echo $inserts['msg'];
                            $arr_res = [
                                'msg' => $inserts['msg'],
                                'value' => '4',
                                'token' => $this->token
                            ];
                            echo json_encode($arr_res);
                        } else {
                            $arr_res = [
                                'msg' => $inserts['msg'],
                                'value' => '0',
                                'token' => $this->token
                            ];
                            echo json_encode($arr_res);
                            // echo $inserts['msg'];
                        }
                    } else {
                        $arr_res = [
                            'msg' => $this->upload->display_errors(),
                            'value' => 'z',
                            'token' => $this->token
                        ];
                        echo json_encode($arr_res);
                        // echo $this->upload->display_errors();
                    }
                }
            }



            function import_subfam_stockdays($path_name, $filename,$fam,$subfam)
            {
                // Get the current date/time and convert to an Excel date/time
                $dateTimeNow = time();
                $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($dateTimeNow);
                $table_template = 'template_subfam_sd';
                $path_xlsx = FCPATH . $path_name . $filename;
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($path_xlsx);
        
                $d = $spreadsheet->getSheet(0)->toArray();
        
                $validasi_template = $this->validasi_template_subfam_stockdays($d, $table_template);
                $validasi_sesuai_cat_and_store = false;
                $validasi_value = false;
                unset($d[0]);
                unset($d[1]);
                unset($d[2]);
        
                $id_log = array();
                $row_nothing = array();
                $row_validasi_sesuai_cat_and_store = array();
                $row_validasi_value = array();

                if ($validasi_template) {
                    $flag_return = [
                        'falgs' => '2',
                        'msg' => 'Upload Gagal, File yang diupload tidak sesuai template'
                    ];
                    return $flag_return;
                } else {
                    $datas = array();
                    foreach ($d as $key =>  $t) {
                        // var_dump(substr($t[0], 1, 1));
                        // var_dump($t[0], $t[1], substr($t[0], 1, 1)== '1');
                        // check item per store
                        if (isset($t[1]) && isset($t[3]) && isset($t[5]) && isset($t[6])) {
                            $store_t = "$t[3]";
                            $subfamcode_t = "$t[1]";
                            $array_where = [
                                'subfamcode' => "$t[1]",
                                'str_code' => "$t[3]"
                            ];
        
                            $check_subfam_store = $this->M_Stock_Days->subfamily_detail($fam,$store_t,$subfamcode_t);
                            $cat = substr($fam,0,3);
                            $check_fam_store = $this->M_Stock_Days->family_detail($cat,$store_t,$fam);
                            // var_dump($check_fam_store);die;
                            // var_dump($array_where);
    
                            if ($check_subfam_store) {
    
                                $check_subfam_store2 = $this->M_Stock_Days->ou_trshd_subfam_detail($store_t,$subfamcode_t);
                                if ($check_subfam_store2) {
                                    $this->M_Stock_Days->ou_trshd_subfam_delete($store_t,$subfamcode_t);
                                    // $this->db->where($array_where);
                                    // $this->db->delete('item_unorderable');
                                }

                                $get_store = $this->db->get_where('store', ['store_code' =>$store_t])->row();
                                $data["famcode"] = $fam;
                                $data["fam_name"] = $check_fam_store->fam_name;
                                $data["subfamcode"] = $subfamcode_t;
                                $data["subfam_name"] = $check_subfam_store->subfam_name;
                                $data["str_code"] = $store_t;
                                $data["str_name"] = $get_store->store_name;
                                $data["stock_day_min"] = $t[5];
                                $data["stock_day_max"] = $t[6];
                                $data["updated_by"] =  $this->data_session['username'];
                                $data["updated_date"] = date('Y-m-d');
                                array_push($datas, $data);
                            } else {
                                $validasi_value = true;
                                array_push($row_nothing, $key);
                            }
                            
                        }
                    }
                    // die;
        
                    if (count($datas) == 0) {
                        if ($validasi_value) {
                            $explode_nothing = implode(',', $row_nothing);
                            $flag_return = [
                                'falgs' => '4',
                                'msg' => "Upload Gagal, terdapat data fail di baris $explode_nothing"
                            ];
                        }  else {
                            $flag_return = [
                                'falgs' => '4',
                                'msg' => 'Upload Gagal, Data dalam file kosong atau terdapat kesalahan data pada excel'
                            ];
                        }
        
                        return $flag_return;
                    } else {
                        // $inserts = $this->db->insert_batch('item_unorderable', $datas);
                        $inserts = $this->M_Stock_Days->ou_trshd_subfam_insert_batch($datas);
                        if ($inserts) {
                            if ($validasi_value) {
                                $explode_nothing = implode(',', $row_nothing);
                                $flag_return = [
                                    'falgs' => '1',
                                    'msg' => "Berhasil, dan terdapat data fail di baris $explode_nothing"
                                ];
                            }  else {
                                $flag_return = [
                                    'falgs' => '1',
                                    'msg' => 'Berhasil '.count($datas)
                                ];
                            }
                            
                            return  $flag_return;
                        } else {
                            $flag_return = [
                                'falgs' => '0',
                                'msg' => 'data gagal disimpan'
                            ];
                            return $flag_return;
                        }
                    }
                }
            }


            public function validasi_template_subfam_stockdays($header, $table_template)
            {
                $count = count($header[0]);
                $template_excel = $this->db->get($table_template)->result_array();
                $validate = false;
                if ($count == count($template_excel)) {
                    $validate = true;
                }
                


                foreach ($template_excel as $key => $value) {
                    // var_dump($header[$key] == $value['template_header']);
                    // var_dump($header[$key],' - ',$value['template_header']);
                    if ($header[0][$key] == $value['template_header']) {
                        $validate = false;
                    } else {
                        return true;
                    }
                    // var_dump($validate);
                }
                // var_dump($validate);die;
                // die;
                return $validate;
            }


            
        }