<?php

class Stores_model extends CI_Model 
{    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    public function get_distinct()
    {
        $data = $this->db->query("SELECT DISTINCT name FROM access_stores WHERE cart_active = 1");
        return $data->result();
    } 
    
    public function get_all_distinct()
    {
        $data = $this->db->query("SELECT * FROM access_stores GROUP BY name");
        return $data->result();
    } 
    
    public function get_all()
    {
        $this->db->where('cart_active','1');
        $data = $this->db->get('access_stores');
        return $data->result();
    } 
    
    public function get($store_id)
    {
        $this->db->where('store_id',$store_id);
        $data = $this->db->get('access_stores');
        return $data->result();
    } 
    
    public function get_store_dm_id($store_id)
    {
        $this->db->select('dm_id');
        $this->db->where('store_id',$store_id);
        $data = $this->db->get('access_stores');
        return $data->result();
    }    
    
    public function get_name($store_id)
    {
        $this->db->select('name');
        $this->db->select('address');
        $this->db->select('city');
        $this->db->where('store_id',$store_id);
        $data = $this->db->get('access_stores');
        return $data->result();
    }
    
    public function update_address($store_id, $address)
    {
        $this->db->where('store_id',$store_id);
        $this->db->update('access_stores',$address);
    }
    
    public function check_login($username, $password)
    {        
        $this->db->where('name',$username);
        $this->db->where('store_id',$password);
        $this->db->where('cart_active','1');
        $this->db->from('access_stores');
        return $this->db->count_all_results();
    }
    
    public function add_store($data)
    {
        $this->db->insert('access_stores',array(            
            'store_id' => $data['store_id'], 
            'name' => $data['name'], 
            'address' => $data['address'], 
            'city' => $data['city'], 
            'postal_code' => $data['postal_code'], 
            'province' => $data['province'], 
            'phone' => $data['phone'], 
            'fax' => $data['fax'], 
            'manager_or_owner' => $data['manager_or_owner'], 
            'cart_active' => $data['cart_active'],
            'date_created' => date('Y-m-d H:i:s'),
        ));      

        return $this->db->insert_id();
    }
    
    public function delete_store($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('access_stores');
    }
    
    public function update_store($id, $data)
    {
        $this->db->where('id',$id);
        $this->db->update('access_stores',array(            
            'store_id' => $data['store_id'], 
            'name' => $data['name'], 
            'address' => $data['address'], 
            'city' => $data['city'], 
            'postal_code' => $data['postal_code'], 
            'province' => $data['province'], 
            'phone' => $data['phone'], 
            'fax' => $data['fax'], 
            'manager_or_owner' => $data['manager_or_owner'], 
            'cart_active' => $data['cart_active'], 
            'date_modif' => date('Y-m-d H:i:s'),  
        ));
    }
}
