<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class RMS extends CI_Model
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

        public function get_filtered_data($limit, $offset, $filter) {
            // Customize this query based on your needs
            $this->db_metabase->select('*');
            $this->db_metabase->from('dashboard_rms_temporary_v2');

            // Apply filters
            if (!empty($filter['item_code'])) {
                $this->db_metabase->where("CAST(item_code AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['item_code']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['values'])) {
                $this->db_metabase->where("CAST(values AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['values']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['cat_name'])) {
                $this->db_metabase->where("CAST(cat_name AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['cat_name']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['salesdate'])) {
                $this->db_metabase->where("CAST(salesdate AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['salesdate']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['sub_fam_code'])) {
                $this->db_metabase->where("CAST(sub_fam_code AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['sub_fam_code']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['sub_fam_code'])) {
                $this->db_metabase->where("CAST(sub_fam_code AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['sub_fam_code']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['suppid'])) {
                $this->db_metabase->where("CAST(suppid AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['suppid']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['territory'])) {
                $this->db_metabase->where("CAST(territory AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['territory']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['sitecode'])) {
                $this->db_metabase->where("CAST(sitecode AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['sitecode']) . "%'", NULL, FALSE);
                // $this->db_metabase->like('sitecode', $filter['sitecode'], 'both');
            }
            if (!empty($filter['div_name'])) {
                $this->db_metabase->where("CAST(div_name AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['div_name']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['supp_type'])) {
                $this->db_metabase->where("CAST(supp_type AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['supp_type']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['dept_name'])) {
                $this->db_metabase->where("CAST(dept_name AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['dept_name']) . "%'", NULL, FALSE);
            }
            if (!empty($filter['fam_name'])) {
                $this->db_metabase->where("CAST(fam_name AS TEXT) LIKE '%" . $this->db_metabase->escape_like_str($filter['fam_name']) . "%'", NULL, FALSE);
            }

            
            // Add more filters as needed
            // $this->db_metabase->like('div_name', 'FMCG', 'both');

            $this->db_metabase->limit($limit, $offset);
            $query = $this->db_metabase->get();
            return $query->result();
        }

        public function count_all_data_rms()
        {
            return $this->db_metabase->count_all('dashboard_rms_temporary_v2');
        }
    }
