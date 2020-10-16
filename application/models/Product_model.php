<?php
class Product_model extends CI_Model {
    function get_data($table) {
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get();
        return $query->row_array();
    }

    function get_all_data($table){
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result_array();
    }

    function insert_data($data,$table_name) {
        $result["resp"] = $this->db->insert($table_name, $data);
        if($result["resp"]){
            return $this->db->insert_id();
        }else{
            return $result;
        }
    }

    function get_data_by_cols($table,$cols,$cond,$limit = "",$offset = "") {
        $this->db->select($cols);
        $this->db->from($table);
        $this->db->where($cond);
        if($limit != ""){
            $this->db->limit($limit,$offset);
        }
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result_array(); 
    }
   
}