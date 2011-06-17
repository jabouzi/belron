<?php

class Permissions_model extends CI_Model 
{
    public function get_user_permissions($user_id)
    {
        $this->db->where('superviser',$user_id);
        $data = $this->db->get('access_user_permissions');
        return $data->result();
    }
    
    public function get_store_permissions($store_id)
    {
        $this->db->where('store',$store_id);
        $data = $this->db->get('access_store_permissions');
        return $data->result();
    }   
    
    public function get_user_stores($user_id)
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
    
    public function update_store_permissions($data)
    {
        $this->db->insert('access_store_permissions',array(            
            'superviser' => $data['superviser'], 
            'store' => $data['store'],             
        ));  
    }
    
    public function update_user_permissions($data)
    {
        $this->db->insert('access_user_permissions',array(            
            'superviser' => $data['superviser'], 
            'user' => $data['user'],             
        ));  
    }
    
    public function delete_store_permissions($id)
    {
        $this->db->where('store',$id);
        $this->db->delete('access_store_permissions');
    }
    
    public function delete_user_stores($id)
    {
        $this->db->where('superviser',$id);
        $this->db->delete('access_store_permissions');
    }
    
    public function delete_user_permissions($id)
    {
        $this->db->where('superviser',$id);
        $this->db->delete('access_user_permissions');
    }
    
    public function get_user_provinces($id)
    {
        $this->db->where('superviser',$id);
        $data = $this->db->get('access_user_provinces');
        return $data->result();
    }
    
    public function update_user_provinces($data)
    {
        $this->db->insert('access_user_provinces',array(            
            'superviser' => $data['superviser'], 
            'province' => $data['province'],             
        ));  
    }
    
    public function delete_user_provinces($id)
    {
        $this->db->where('superviser',$id);
        $this->db->delete('access_user_provinces');
    }
}
