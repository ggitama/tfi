<?php
        defined('BASEPATH') or exit('No direct script access allowed');

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        use PhpOffice\PhpSpreadsheet\Style\Border;
        use PhpOffice\PhpSpreadsheet\Style\Color;
        
        class Stock_Qty extends CI_Controller
        {
            public function __construct()
            {
                parent::__construct();
                $this->load->model('User_model');
                $this->load->model('M_Stock_Days');
                $this->load->model('M_Stock_Qty');
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

                //  echo "<pre>";
                //  print_r($this->M_Stock_Qty->testing());die;
                
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
                $data['title'] = 'Stock Qty';
                $data['menu_header'] = 'Dashboard';
                $data['main_menu'] = 'Stock Qty';
        
                $this->load->view('template_dashboard/Header_v', $data);
                $this->load->view('Parameter/Stock_Qty_v', $data);
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

            // GET DATA ITEM 
            public function data_items()
            {
                // $val = $this->input->get('val', TRUE);
                $store = $this->input->get('store', TRUE);
                $biu = $this->input->get('biu', TRUE);
                $dept = $this->input->get('dept', TRUE);
                $cat = $this->input->get('cat', TRUE);
                $fam = $this->input->get('fam', TRUE);
                $sub_fams = $this->input->get('val', TRUE);
                $inputan = $this->input->get('term', TRUE);
                // var_dump($store);

                if (isset($inputan['term'])) {
                    $input_like = $inputan['term'];
                } else {
                    $input_like = '';
                }

                $items = $this->M_Stock_Qty->items($store,$input_like,$sub_fams,$biu,$dept,$cat,$fam);
                // var_dump($items);

                $arr_res = [
                    'token' => $this->token,
                    'res' => json_encode($items),
                ];
                // var_dump($arr_res);
                echo json_encode($items);

            }

            function modal_add_item_stock_qty(){
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
                $data['sub_fam'] = $this->M_Stock_Days->subfamily_detail($fam,$store,$sub_fam);

                

                $items = $this->M_Stock_Qty->items2($store,"",$sub_fam,$biu,$dept,$cat,$fam);
                // if($fam == 'all_fam'){
                //     $fams = $this->M_Stock_Days->family($cat,$store);
                //     $data['fams'] = $fams;
                // }else{
                //     $data['fams'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);
                // }

                $data['items'] = $items;

                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Stock_Qty_Item', $data, TRUE);
                echo $html_modal;
            }

            function modal_edit_item_stock_qty(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('str_code',TRUE);
                $item_code = $this->input->post('item_code',TRUE);

                $item = $this->M_Stock_Qty->item_detail($item_code,$store);
                $data['item'] = $item;

                $biu = substr($item_code,0,1);
                $dept = substr($item_code,0,2);
                $cat = substr($item_code,0,3);
                $fam = substr($item_code,0,4);
                $sub_fam = substr($item_code,0,5);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);
                $data['sub_fam'] = $this->M_Stock_Days->subfamily_detail($fam,$store,$sub_fam);

                $data_maint_stock_qty = $this->M_Stock_Qty->maint_stock_qty_detail($store,$item_code);
                $data['stock_qty'] = $data_maint_stock_qty->stock_qty;


                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Edit_Stock_Qty', $data, TRUE);
                echo $html_modal;
            }

            function delete_modal_item_stock_qty(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('str_code',TRUE);
                $item_code = $this->input->post('item_code',TRUE);

                $item = $this->M_Stock_Qty->item_detail($item_code,$store);
                $data['item'] = $item;


                $biu = substr($item_code,0,1);
                $dept = substr($item_code,0,2);
                $cat = substr($item_code,0,3);
                $fam = substr($item_code,0,4);
                $sub_fam = substr($item_code,0,5);

                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();
                $data['biu'] = $this->M_Stock_Days->biu_store_detail($store,$biu);
                $data['dept'] = $this->M_Stock_Days->dept_detail($biu,$store,$dept);
                $data['cat'] = $this->M_Stock_Days->categoty_detail($dept,$store,$cat);
                $data['fam'] = $this->M_Stock_Days->family_detail($cat,$store,$fam);
                $data['sub_fam'] = $this->M_Stock_Days->subfamily_detail($fam,$store,$sub_fam);

                $data_maint_stock_qty = $this->M_Stock_Qty->maint_stock_qty_detail($store,$item_code);
                $data['stock_qty'] = $data_maint_stock_qty->stock_qty;


                $data['token'] = $this->token;
        
                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $html_modal = $this->load->view('Modal/Modal_Delete_Stock_Qty', $data, TRUE);
                echo $html_modal;
            }

            function List_query_item(){
                $limit = $this->input->post('length', TRUE);
                $start = $this->input->post('start', TRUE);
                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sub_fam = $this->input->post('sub_fam',TRUE);
                $item_code = $this->input->post('item_code',TRUE);
                // $search = $this->input->post('search', TRUE)['value'];
                // $iframe_name_w = $search == '' ? "" : " AND iframe_name LIKE '%" . $this->db->escape_like_str($search) . "%' ESCAPE '!'";
                $data_item = $this->M_Stock_Qty->maint_stock_qty($limit,$start,$store,$sub_fam,$item_code);
                $data_item_all = $this->M_Stock_Qty->maint_stock_qty_count($store,$sub_fam,$item_code);
                
                // var_dump($data_item;die;
                // $query =     "SELECT * FROM tb_iframe WHERE 1 $iframe_name_w ";
        
                // $count_query = $this->db->query($query)->result_array();
        
                // $query_exec = $this->db->query($query . $limit_cond, array((int)$limit, (int)$start))->result_array();
        
                $res_data = array(
                    'draw' =>  $this->input->post('draw', TRUE), // Ini dari datatablenya    
                    'recordsTotal' => count($data_item_all),
                    'recordsFiltered' => count($data_item_all),
                    'data' => $data_item,
                    'token' => $this->security->get_csrf_hash()
                );
        
                echo json_encode($res_data);
            }

           
            function edit_sqItem(){
                $username =  $this->data_session['username'];
                $storecode = $this->input->post('str_code',TRUE);
                $itemcode = $this->input->post('itemcode',TRUE);
                $stock_qty = $this->input->post('stock_qty',TRUE);
                // var_dump($stock_qty);die;

                 // check stock days fam store sudah ada atau belum
                 $data_update = [
                    'stock_qty' => (int)$stock_qty,
                    'updateddate' => date('Y-m-d'),
                    'updatedby' => $username
                 ];

                 $update = $this->M_Stock_Qty->maint_stock_qty_update($data_update,$storecode,$itemcode);
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

            function delete_sqItem(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $itemcode = $this->input->post('itemcode',TRUE);

                $delete = $this->M_Stock_Qty->maint_stock_qty_delete($str_code,$itemcode);
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


            function saveQtyItem(){
                $username =  $this->data_session['username'];
                $str_code = $this->input->post('str_code',TRUE);
                $str_name = $this->input->post('str_name',TRUE);
                $item_code = $this->input->post('item',TRUE);
                $qty = $this->input->post('qty',TRUE);

                $item_name = $this->M_Stock_Qty->item_detail($item_code,$str_code)->item_name;
                // var_dump($item_name);die;

                // check stock days fam store sudah ada atau belum
                $data_maint_stock_qty = $this->M_Stock_Qty->maint_stock_qty_detail($str_code,$item_code);
                if($data_maint_stock_qty){
                    $msg = 'Data Sudah ada Harap Update/Edit data tersebut';
                    $value = 0;
                }else{
                    $data_insert = [
                        'store_code' => $str_code,
                        'store_name' => $str_name,
                        'item_code' => $item_code,
                        'item_name' => $item_name,
                        'stock_qty' => (int)$qty,
                        'updateddate' => date('Y-m-d'),
                        'updatedby' => $username
                    ];
                    $insert = $this->M_Stock_Qty->maint_stock_qty_insert($data_insert);
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

            function modal_upload_item_stock_qty(){
                $modal = $this->input->post('modal', TRUE);
                $id = $this->input->post('id', TRUE);

                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sub_fam = $this->input->post('sub_fam',TRUE);

                $data['store'] = $store;
                $data['biu'] = $biu;
                $data['dept'] = $dept;
                $data['cat'] = $cat;
                $data['fam'] = $fam;
                $data['sub_fam'] = $sub_fam;

                $data['modal_title'] = $modal;
                $data['id'] = $id;
                $data['token'] = $this->token;
                $html_modal = $this->load->view('Modal/modal_upload_item_stock_qty', $data, TRUE);
                echo $html_modal;
            }



            public function export_to_excel_stock_qty()
            {
                $store = $this->input->get('store',TRUE);
                $biu = $this->input->get('biu',TRUE);
                $dept = $this->input->get('dept',TRUE);
                $cat = $this->input->get('cat',TRUE);
                $fam = $this->input->get('fam',TRUE);
                $sub_fam = $this->input->get('subfam',TRUE);


                $data['store'] = $this->db->query("SELECT * FROM store WHERE store_code = '$store'")->row();

                $items = $this->M_Stock_Qty->items2($store,"",$sub_fam,$biu,$dept,$cat,$fam);
                
                
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




                $sheet->setCellValue('A1', 'No')->getStyle('A1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('B1', 'Store Code')->getStyle('B1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('C1', 'Store Name')->getStyle('C1')->applyFromArray($styleArrayHead);
                $sheet->setCellValue('D1', 'Item Code')->getStyle('D1')->applyFromArray($styleArray);
                $sheet->setCellValue('E1', 'Item Name')->getStyle('E1')->applyFromArray($styleArray);
                $sheet->setCellValue('F1', 'Stock Qty')->getStyle('F1')->applyFromArray($styleArray);

                $sheet->setCellValue('I1', 'Nilai dari kolom Stock Day Min tidak boleh lebih besar dari nilai kolom Stock Day Max');
               
                $kolom = 2;
                $nomor = 1;
                foreach ($items as $proses) {
                    $sheet
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $proses['str_code'])
                        ->setCellValue('C' . $kolom, $proses['str_name'])
                        ->setCellValue('D' . $kolom, $proses['itemcode'])
                        ->setCellValue('E' . $kolom, $proses['item_name']);
                        
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
                $filename =  'stock_item'.$store.$sub_fam.$date_now;
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
            }


            function upload_item_stock_qty(){
                $store = $this->input->post('store',TRUE);
                $biu = $this->input->post('biu',TRUE);
                $dept = $this->input->post('dept',TRUE);
                $cat = $this->input->post('cat',TRUE);
                $fam = $this->input->post('fam',TRUE);
                $sub_fam = $this->input->post('sub_fam',TRUE);

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
                        $inserts = $this->import_item_stock_qty($path_name, $filename,$sub_fam);
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

            function import_item_stock_qty($path_name, $filename,$sub_fam)
            {
                // Get the current date/time and convert to an Excel date/time
                $dateTimeNow = time();
                $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($dateTimeNow);
                $table_template = 'template_item_stock_qty';
                $path_xlsx = FCPATH . $path_name . $filename;
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($path_xlsx);
        
                $d = $spreadsheet->getSheet(0)->toArray();
        
                $validasi_template = $this->validasi_template_fam_stockdays($d, $table_template);
                $validasi_sesuai_cat_and_store = false;
                $validasi_value = false;
                unset($d[0]);
        
                $id_log = array();
                $row_nothing = array();
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
                        if (isset($t[1]) && isset($t[3]) && isset($t[5])) {
                            $store_t = "$t[1]";
                            $itemcode_t = "$t[3]";
                            $array_where = [
                                'str_code' => "$t[1]",
                                'str_name' => "$t[3]"
                            ];
        
                            $check_item_s_q = $this->M_Stock_Qty->item_detail($itemcode_t,$store_t);
                            // var_dump($check_item_s_q);
                            // var_dump($array_where);
    
                            if ($check_item_s_q) {
    
                                $check_item_s_q2 = $this->M_Stock_Qty->maint_stock_qty_detail($store_t,$itemcode_t);
                                if ($check_item_s_q2) {
                                    $this->M_Stock_Qty->maint_stock_qty_delete($store_t,$itemcode_t);
                                    // $this->db->where($array_where);
                                    // $this->db->delete('item_unorderable');
                                }

                                $get_store = $this->db->get_where('store', ['store_code' =>$store_t])->row();
                                $data["store_code"] = $store_t;
                                $data["store_name"] = $get_store->store_name;
                                $data["item_code"] = $itemcode_t;
                                $data["item_name"] = $check_item_s_q->item_name;
                                $data["stock_qty"] = $t[5];
                                $data["updatedby"] =  $this->data_session['username'];
                                $data["updateddate"] = date('Y-m-d');
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
                        $inserts = $this->M_Stock_Qty->maint_stock_qty_insert_batch($datas);
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



            
        }