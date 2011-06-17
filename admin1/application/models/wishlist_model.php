<?php

class Wishlist_model extends CI_Model 
{    
    public function get($store_id)
    {
        $this->db->select('*');
        $this->db->where('store_id',$store_id);        
        $data = $this->db->get('access_wishlists');       
        return $data->result();
    }
    
    public function get_by_id($id)
    {
        $this->db->select('*');
        $this->db->where('id',$id);        
        $data = $this->db->get('access_wishlists');       
        return $data->result();
    }
    
    public function insert($list, $store_id)
    {
        $this->db->insert('access_wishlists',array(
            'store_id' => $store_id,
            'wish_list' => $list
        ));      
    }
    
    public function update($list, $store_id)
    {
        $this->db->where('store_id',$store_id);
        $this->db->update('access_wishlists',array('wish_list' => $list));
    }
    
    public function delete($store_id)
    {
        $this->db->where('store_id',$store_id);
        $this->db->delete('access_wishlists');
    }
}
