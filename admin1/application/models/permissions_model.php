<?php

class Permissions_model extends CI_Model 
{
    public function get_user_permissions($user_id)
    {
        $this->db->where('superviser',$user_id);
        $data = $this->db->get('access_user_permissions');
        return $data->result();
    }
    
    public function get_store_permissions($user_id)
    {
        $this->db->where('superviser',$user_id);
        $data = $this->db->get('access_store_permissions');
        return $data->result();
    }   
    
    public function get_store_supervisers($store_id)
    {
        $this->db->where('store',$store_id);
        $this->db->from('access_store_permissions');
        return $this->db->count_all_results();
    }
    
    public function get_user_supervisers($user_id)
    {
        $this->db->where('user',$user_id);
        $this->db->from('access_user_permissions');
        return $this->db->count_all_results();
    }
    
    public function get_store_supervisers_names($store_id)
    {
        $this->db->where('store',$store_id);
        $data = $this->db->get('access_store_permissions');
        return $data->result();
    }
    
    public function get_user_supervisers_names($user_id)
    {
        $this->db->where('user',$user_id);
        $data = $this->db->get('access_user_permissions');
        return $data->result();
    }
}
