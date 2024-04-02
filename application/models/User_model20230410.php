<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    function save_flow($save)
    {
        $this->db->insert('flow_of_request', $save);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function get_flow($where = null)
    {
        //$this->db->select('*');
        $this->db->select('ci_last_regenerate, COUNT(*) as jmlh');
        $this->db->from('flow_of_request');
        $this->db->where($where);
        // echo $this->db->get_compiled_select();die;
        /*

         $where['ci_last_regenerate'] = '1663052813';
         $where['path'] = '/welcome/signin';
         $where['content_type'] = 'POST';

         */

        $query = $this->db->get();
        $data = $query->row(); //This method returns the single result
        //$data = $query->result(); //This method returns the query
        // result as an array of objects
        return $data;
    }

    public function get_user($username)
    {
        return $this->db->get_where('tb_user_transmart', ['username' => $username])->row();
    }

    public function update_last_login($username)
    {
        $this->db->set('last_login', 'NOW()', FALSE);
        $this->db->where('username', $username);
        $this->db->update('tb_user_transmart');
    }

    public function menu($jabatan)
    {
        return $this->db->query("SELECT
            `tb_menu_transmart`.*,
            `tb_access_role_menu`.`view`,
            c.id_menu AS `childs` 
        FROM
            `tb_menu_transmart`
            LEFT JOIN `tb_access_role_menu` ON `tb_access_role_menu`.`id_menu` = `tb_menu_transmart`.`id_menu`
            LEFT JOIN `tb_menu_transmart` AS `c` ON `c`.`parent` = `tb_menu_transmart`.`id_menu` 
        WHERE
            `tb_menu_transmart`.`is_menu` = 'Yes' 
            AND `tb_access_role_menu`.`id_role` = '$jabatan' 
            AND `tb_access_role_menu`.`view` = '1' 
        GROUP BY
            `tb_menu_transmart`.`id_menu` 
        ORDER BY
            `tb_menu_transmart`.`position` ASC")->result_array();
    }

    public function get_parent_name($type)
    {
        $this->db->select('*');
        $this->db->where('type', $type);
        $this->db->where('is_menu', 'Yes');
        $this->db->order_by("position", "asc");
        // $this->db->group_by('menu.id_menu');
        return $this->db->get('tb_menu_transmart');
        //    
    }

    public function get_level_detail()
    {
        // $this->db->select('*');
        // $this->db->where('id_level',$id_level);
        // $this->db->where('id_menu',$id_menu);
        return $this->db->get('tb_access_role_menu');
    }

    function get_trms($table, $select)
    {
        $select_to = implode(',', $select);
        $this->db->select($select_to);
        return $this->db->get($table);
    }

    public function level_user_where($id)
    {
        return $this->db->get_where('tb_user_role', ['role_id' => $id])->row();
    }
    public function get_level_user()
    {
        return $this->db->get('tb_user_role')->result_array();
    }

    public function get_parent()
    {
        // $this->db->select('parent');
        // $this->db->group_by('parent');
        // $this->db->order_by("position", "asc");
        // return $this->db->get('tb_menu_trdansmart');

        return $this->db->query("SELECT a.`parent`,a.position,a.id_menu,b.menu_name,b.position
        FROM `tb_menu_transmart` a
        LEFT JOIN tb_menu_transmart b on a.parent = b.id_menu
        GROUP BY a.`parent`
        ORDER BY b.type, b.position ASC");
    }

    public function get_menu_name($parent,$role_id)
    {
        $this->db->select('*');
        $this->db->join('tb_access_role_menu', 'tb_access_role_menu.id_menu = tb_menu_transmart.id_menu', 'left');
        $where = "(parent = '$parent' OR parent is NULL) AND is_menu = 'Yes'";
        $this->db->where($where);
        $this->db->where('tb_access_role_menu.id_role',$role_id);
        $this->db->order_by("position", "asc");
        return $this->db->get('tb_menu_transmart');
    }
    public function get_parent2($parent)
    {
        $this->db->select('menu_name,id_menu');
        $this->db->where('id_menu', $parent);
        $this->db->where('is_menu', 'Yes');
        $this->db->order_by("position", "asc");
        return $this->db->get('tb_menu_transmart');
    }
    public function get_menu_name2($parent,$role_id)
    {
        $this->db->select('*');
        $this->db->join('tb_access_role_menu', 'tb_access_role_menu.id_menu = tb_menu_transmart.id_menu', 'inner');
        $this->db->where('parent', $parent);
        $this->db->where('is_menu', 'Yes');
        $this->db->where('tb_access_role_menu.id_role',$role_id);
        $this->db->order_by('tb_menu_transmart.position', 'ASCENDING');
        return $this->db->get('tb_menu_transmart');
    }

    function where_trms($kode, $table, $field)
    {
        return $this->db->get_where($table, [$field => $kode]);
    }

    function save_trms($data, $table)
    {
        $save = $this->db->insert($table, $data);
        if ($save) {
            return true;
        } else {
            return false;
        }
    }

    function delete_trms($kode_key, $table, $field)
    {
        $this->db->where($field, $kode_key);
        $delete = $this->db->delete($table);
        if ($delete) {
            return true;
        } else {
            return false;
        }
    }


    function get_iframe($id_menu, $id_role)
    {
        return $this->db->query("SELECT * FROM tb_role_iframe as a LEFT JOIN tb_menu_transmart as b on a.id_menu = b.id_menu LEFT JOIN tb_iframe as c on c.id_iframe = a.id_iframe LEFT JOIN tb_user_role as d on a.id_role = d.role_id WHERE a.id_menu = '$id_menu' AND id_role = '$id_role'")->row();
    }

    public function html_menu($menus)
    {
        $url = base_url();
        // var_dump($url);die;
        $html_menu = "";
        foreach ($menus as $menu) :
            if ($menu['type'] == '0' && (is_null($menu['parent']) || $menu['parent'] == '') && !is_null($menu['childs'])) :
                $html_menu .= "<li class='nav-group'>
                    <a class='nav-link nav-group-toggle'>
                        $menu[menu_name]
                    </a>
                    <ul class='nav-group-items'>";
                foreach ($menus as $child) :
                    if ($child['parent'] == $menu['id_menu'] && is_null($child['childs'])) :
                        $html_menu .=       "<li class='nav-item' style='margin-left: -40px;'><a href='$url$child[file]' class='nav-link'>$child[menu_name]  </a></li>";
                    elseif ($child['parent'] == $menu['id_menu'] && !is_null($child['childs']) && $child['type'] == '1') :
                        $html_menu .=       "<li class='nav-group' style='margin-left: -40px;'>
                                    <a class='nav-link nav-group-toggle'>
                                        $child[menu_name] 
                                    </a>";

                        foreach ($menus as $child2) {
                            if ($child2['parent'] == $child['id_menu']) :

                                if (!is_null($child2['childs'])) :
                                    $html_menu .=                   "<ul class='nav-group-items'>
                                                    <li class='nav-group ms-1'>
                                                        <a class='nav-link nav-group-toggle'>
                                                            $child2[menu_name] 
                                                        </a>";
                                    foreach ($menus as $child3) :
                                        if ($child3['parent'] == $child2['id_menu'] && $child3['type'] == '3') :
                                            $html_menu .=                              "<ul class='nav-group-items'>
                                                                    <a href='$url$child3[file]' class='nav-link'>
                                                                        <li class='nav-item ms-1'> $child3[menu_name] </li>
                                                                    </a>
                                                                </ul>";
                                        endif;
                                    endforeach;
                                    $html_menu .=                   "</li>
                                                </ul>";
                                else :
                                    $html_menu .=                "<ul class='nav-group-items'>
                                                    <a href='$url$child2[file]' class='nav-link'>
                                                        <li class='nav-item ms-1'> $child2[menu_name] </li>
                                                    </a>
                                                </ul>";
                                endif;
                            endif;
                        }
                        $html_menu .=    "</li>";
                    endif;

                endforeach;
                $html_menu .=  "</ul>
                </li>";
            elseif ($menu['type'] == '0' && (is_null($menu['parent']) || $menu['parent'] == '')) :
                $logic = $menu['id_menu'] == '162' ? 'mt-auto' : ''; 
                $html_menu .=    "<li class='nav-item $logic'><a class='nav-link' href='$url$menu[file]'>$menu[menu_name] </a> </li>";
            endif;
        endforeach;

        return $html_menu;
    }
}
