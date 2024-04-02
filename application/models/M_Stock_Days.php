<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Stock_Days extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db_metabase = $this->load->database('db_metabase', TRUE);
    }
    
    public function get_data_rms($limit, $offset) {
        $query = $this->db_metabase->get('dashboard_rms_temporary_v2', $limit, $offset);
        return $query->result();
    }

    public function get_all_data_rms() {
        $query = $this->db_metabase->get('dashboard_rms_temporary_v2');
        return $query->result();
    }

    public function count_all_data_rms() {
        return $this->db_metabase->count_all('dashboard_rms_temporary_v2');
    }

    public function biu(){
        return $this->db_metabase->query('SELECT biu_code, business_unit_name FROM db_inv_270523 GROUP BY biu_code, business_unit_name')->result();
    }

    public function biu_store($store){
        return $this->db_metabase->query("SELECT biu_code, business_unit_name FROM db_inv_270523 WHERE str_code = '$store' GROUP BY biu_code, business_unit_name")->result();
    }
    public function biu_store_detail($store,$biu){
        return $this->db_metabase->query("SELECT biu_code, business_unit_name FROM db_inv_270523 WHERE str_code = '$store' AND biu_code = '$biu' GROUP BY biu_code, business_unit_name")->row();
    }

    public function dept($biu,$store){
        return $this->db_metabase->query("SELECT dept_code, department_name FROM db_inv_270523 WHERE biu_code = '$biu' AND str_code = '$store' GROUP BY dept_code, department_name")->result();
    }

    public function dept_detail($biu,$store,$dept){
        return $this->db_metabase->query("SELECT dept_code, department_name FROM db_inv_270523 WHERE biu_code = '$biu' AND str_code = '$store' AND dept_code = '$dept' GROUP BY dept_code, department_name")->row();
    }

    public function maping(){
        // return $this->db_metabase->query("SELECT * FROM public.tables");die;
        $this->db_metabase->limit(1);
        return $this->db_metabase->get("db_inv_270523")->result();
    }

    public function categoty($dept,$store){
        return $this->db_metabase->query("SELECT catcode, cat_name FROM db_inv_270523 WHERE dept_code = '$dept' AND str_code = '$store' GROUP BY catcode, cat_name")->result();
    }
    public function categoty_detail($dept,$store,$cat){
        return $this->db_metabase->query("SELECT catcode, cat_name FROM db_inv_270523 WHERE dept_code = '$dept' AND str_code = '$store' AND catcode = '$cat' GROUP BY catcode, cat_name")->row();
    }

    public function family($cat,$store){
        return $this->db_metabase->query("SELECT famcode, fam_name FROM db_inv_270523 WHERE catcode = '$cat' AND str_code = '$store' GROUP BY famcode, fam_name")->result();
    }

    public function family_perstore($store){
        return $this->db_metabase->query("SELECT famcode, fam_name FROM db_inv_270523 WHERE str_code = '$store' GROUP BY famcode, fam_name")->result();
    }

    public function family_detail($cat,$store,$fam){
        return $this->db_metabase->query("SELECT famcode, fam_name FROM db_inv_270523 WHERE catcode = '$cat' AND str_code = '$store' AND famcode = '$fam' GROUP BY famcode, fam_name")->row();
    }

    public function family_store_detail($store,$fam){
        return $this->db_metabase->query("SELECT famcode, fam_name FROM db_inv_270523 WHERE catcode = '$cat' AND str_code = '$store' AND famcode = '$fam' GROUP BY famcode, fam_name")->row();
    }

    public function family_store_detail2($store,$fam){
        return $this->db_metabase->query("SELECT famcode, fam_name FROM db_inv_270523 WHERE catcode = '$cat' AND str_code = '$store' AND famcode = '$fam' GROUP BY famcode, fam_name")->result();
    }

    public function subfamily($fam,$store){
        return $this->db_metabase->query("SELECT subfamcode, subfam_name FROM db_inv_270523 WHERE famcode = '$fam' AND str_code = '$store' GROUP BY subfamcode, subfam_name")->result();
    }
    public function subfamily_detail($fam,$store,$subfam){
        return $this->db_metabase->query("SELECT subfamcode, subfam_name FROM db_inv_270523 WHERE famcode = '$fam' AND str_code = '$store' AND subfamcode = '$subfam' GROUP BY subfamcode, subfam_name")->row();
    }
    public function subfamily_detail2($fam,$store,$subfam){
        return $this->db_metabase->query("SELECT subfamcode, subfam_name FROM db_inv_270523 WHERE famcode = '$fam' AND str_code = '$store' AND subfamcode = '$subfam' GROUP BY subfamcode, subfam_name")->result();
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
