<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Stock_Qty extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db_metabase = $this->load->database('db_metabase', TRUE);
    }

    function testing(){
        return $this->db_metabase->list_fields('maint_stock_qty');
    }

    function items($store,$input_like,$sub_fams,$biu,$dept,$cat,$fam){
        if(isset($store)){
            $store_condition = " AND str_code = '$store' ";
        }else{
            $store_condition = "";
        }

        if(isset($biu)){
            $biu_condition = " AND SUBSTRING(itemcode, 1, 1) = '$biu' ";
        }else{
            $biu_condition = "";
        }
        if(isset($dept)){
            $dept_condition = " AND SUBSTRING(itemcode, 1, 2) = '$dept' ";
        }else{
            $dept_condition = "";
        }
        if(isset($cat)){
            $cat_condition = " AND SUBSTRING(itemcode, 1, 3) = '$cat' ";
        }else{
            $cat_condition = "";
        }
        if(isset($fam)){
            $fam_condition = " AND SUBSTRING(itemcode, 1, 4) = '$fam' ";
        }else{
            $fam_condition = "";
        }
        if(isset($sub_fams)){
            $sub_fams_condition = " AND SUBSTRING(itemcode, 1, 5) = '$sub_fams' ";
        }else{
            $sub_fams_condition = "";
        }


        $querys = "SELECT itemcode,item_name FROM db_inv_270523 WHERE (itemcode LIKE '%$input_like%' OR item_name LIKE '%$input_like%') $store_condition $sub_fams_condition $biu_condition $dept_condition $cat_condition $fam_condition LIMIT 5";
        $excute = $this->db_metabase->query($querys)->result_array();
        // var_dump($querys);
        return $excute;
    }

    public function items2($store,$input_like,$sub_fams,$biu,$dept,$cat,$fam){
        if(isset($store)){
            $store_condition = " AND str_code = '$store' ";
        }else{
            $store_condition = "";
        }

        if(isset($biu)){
            $biu_condition = " AND SUBSTRING(itemcode, 1, 1) = '$biu' ";
        }else{
            $biu_condition = "";
        }
        if(isset($dept)){
            $dept_condition = " AND SUBSTRING(itemcode, 1, 2) = '$dept' ";
        }else{
            $dept_condition = "";
        }
        if(isset($cat)){
            $cat_condition = " AND SUBSTRING(itemcode, 1, 3) = '$cat' ";
        }else{
            $cat_condition = "";
        }
        if(isset($fam)){
            $fam_condition = " AND SUBSTRING(itemcode, 1, 4) = '$fam' ";
        }else{
            $fam_condition = "";
        }
        if(isset($sub_fams)){
            $sub_fams_condition = " AND SUBSTRING(itemcode, 1, 5) = '$sub_fams' ";
        }else{
            $sub_fams_condition = "";
        }


        $querys = "SELECT str_code,str_name, itemcode,item_name FROM db_inv_270523 WHERE (itemcode LIKE '%$input_like%' OR item_name LIKE '%$input_like%') $store_condition $sub_fams_condition $biu_condition $dept_condition $cat_condition $fam_condition";
        $excute = $this->db_metabase->query($querys)->result_array();
        // var_dump($querys);
        return $excute;
    }


    public function item_detail($itemcode,$store){
        return $this->db_metabase->query("SELECT itemcode, item_name FROM db_inv_270523 WHERE itemcode = '$itemcode' AND str_code = '$store' GROUP BY itemcode, item_name")->row();
    }

    public function maint_stock_qty($limit,$start,$store,$sub_fam,$item_code){
        if($item_code == '' OR $item_code == 'SELECT ITEM'){
            $this->db_metabase->where('SUBSTRING("item_code", 1, 5) =',$sub_fam);
            $this->db_metabase->where('store_code',$store);
        }else{
            $this->db_metabase->where('store_code',$store);
            $this->db_metabase->where('item_code',$item_code);
        }

        $limit_cond = $limit != -1 ?? $this->db_metabase->limit($limit,$start);
        return $this->db_metabase->get('maint_stock_qty')->result();
    }

    public function maint_stock_qty_count($store,$sub_fam,$item_code){
        if($item_code == ''){
            $this->db_metabase->where('SUBSTRING("item_code", 1, 5) =',$sub_fam);
            $this->db_metabase->where('store_code',$store);
        }else{
            $this->db_metabase->where('store_code',$store);
            $this->db_metabase->where('item_code',$item_code);
        }

        return $this->db_metabase->get('maint_stock_qty')->result();
    }

    public function maint_stock_qty_detail($str_code,$item_code){
        $this->db_metabase->where('store_code',$str_code);
        $this->db_metabase->where('item_code',$item_code);
        return $this->db_metabase->get('maint_stock_qty')->row();
    }

    public function maint_stock_qty_insert($data_insert){
        return $this->db_metabase->insert("maint_stock_qty",$data_insert);
    }

    function maint_stock_qty_delete($store_code,$item_code){
        $this->db_metabase->where([
            'store_code'=>$store_code,
            'item_code'=>$item_code
        ]);
        return $this->db_metabase->delete("maint_stock_qty");
    }

    function maint_stock_qty_update($data_update,$store_code,$itemcode){
        $this->db_metabase->where([
            'store_code'=>$store_code,
            'item_code'=>$itemcode,
        ]);
        return $this->db_metabase->update("maint_stock_qty",$data_update);
    }

    function maint_stock_qty_insert_batch($data_insert){
        return $this->db_metabase->insert_batch('maint_stock_qty', $data_insert);
    }

    public function ou_trshd_fam($limit,$start,$store,$fam,$cat){
        if($fam == 'all_fam'){
            $this->db_metabase->where([
                'str_code'=>$store
            ]);
        }else{
            $this->db_metabase->where([
                'str_code'=>$store,
                'famcode'=>$fam
            ]);
        }
        $limit_cond = $limit != -1 ?? $this->db_metabase->limit($limit,$start);
        return $this->db_metabase->get("ou_trshd_fam")->result();

    }

    public function ou_trshd_fam_count($store,$fam,$cat){
        if($fam == 'all_fam'){
            $this->db_metabase->where([
                'str_code'=>$store
            ]);
        }else{
            $this->db_metabase->where([
                'str_code'=>$store,
                'famcode'=>$fam
            ]);
        }

        $excute =  $this->db_metabase->get("ou_trshd_fam")->result();
        
        return $excute;

    }

    function ou_trshd_fam_detail($store,$fam){
        return $this->db_metabase->get_where("ou_trshd_fam",["str_code"=>$store,"famcode"=>$fam])->row();
    }

    function ou_trshd_fam_insert($data_insert){
        return $this->db_metabase->insert("ou_trshd_fam",$data_insert);
    }
    
    function ou_trshd_fam_update($data_update,$str_code,$famcode){
        $this->db_metabase->where([
            'str_code'=>$str_code,
            'famcode'=>$famcode
        ]);
        return $this->db_metabase->update("ou_trshd_fam",$data_update);
    }

    function ou_trshd_fam_delete($str_code,$famcode){
        $this->db_metabase->where([
            'str_code'=>$str_code,
            'famcode'=>$famcode
        ]);
        return $this->db_metabase->delete("ou_trshd_fam");
    }

    function ou_trshd_subfam($limit,$start,$store,$sub_fam,$fam){
        if($sub_fam == 'all_subfam'){
            $this->db_metabase->where([
                'str_code'=>$store,
                'famcode'=>$fam
            ]);
        }else{
            $this->db_metabase->where([
                'str_code'=>$store,
                'subfamcode'=>$sub_fam,
                'famcode'=>$fam
            ]);
        }
        $limit_cond = $limit != -1 ?? $this->db_metabase->limit($limit,$start);
        return $this->db_metabase->get("ou_trshd_subfam")->result();
    }

    function ou_trshd_subfam_count($store,$sub_fam,$fam){
        if($sub_fam == 'all_subfam'){
            $this->db_metabase->where([
                'str_code'=>$store,
                'famcode'=>$fam
            ]);
        }else{
            $this->db_metabase->where([
                'str_code'=>$store,
                'subfamcode'=>$sub_fam,
                'famcode'=>$fam
            ]);
        }
        return $this->db_metabase->get("ou_trshd_subfam")->result();
    }

    function ou_trshd_subfam_detail($store,$subfam){
        return $this->db_metabase->get_where("ou_trshd_subfam",["str_code"=>$store,"subfamcode"=>$subfam])->row();
    }

    function ou_trshd_subfam_insert($data_insert){
        return $this->db_metabase->insert("ou_trshd_subfam",$data_insert);
    }
    
    function ou_trshd_subfam_update($data_update,$str_code,$subfamcode){
        $this->db_metabase->where([
            'str_code'=>$str_code,
            'subfamcode'=>$subfamcode
        ]);
        return $this->db_metabase->update("ou_trshd_subfam",$data_update);
    }

    function ou_trshd_subfam_delete($str_code,$subfamcode){
        $this->db_metabase->where([
            'str_code'=>$str_code,
            'subfamcode'=>$subfamcode
        ]);
        return $this->db_metabase->delete("ou_trshd_subfam");
    }

    function ou_trshd_fam_insert_batch($data_insert){
        return $this->db_metabase->insert_batch('ou_trshd_fam', $data_insert);
    }
    function ou_trshd_subfam_insert_batch($datas){
        return $this->db_metabase->insert_batch('ou_trshd_subfam', $datas);
    }



}
