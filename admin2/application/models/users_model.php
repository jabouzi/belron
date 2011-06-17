<?php

class Users_model extends CI_Model 
{
    public function count()
    {
        return $this->db->count_all('access_users');
    }    
    
    public function get_all_page($limit, $offset, $order_by, $type)
    {
        $this->db->order_by($order_by, $type);
        $data = $this->db->get('access_users',$limit,$offset);
        return $data->result();
    }
    
    public function get_all_active()
    {
        $this->db->where('type','2');
        $this->db->where('active','1');
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function get_all()
    {
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function get_all_type2()
    {
        $this->db->where('type','2');
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function get_id($email)
    {        
        $this->db->select('id');
        $this->db->where('email',$email);
        $data = $this->db->get('access_users')->result();
        if (count($data)) return $data[0]->id;
        else return false;
    }
    
    public function get_type($email)
    {        
        $this->db->select('type');
        $this->db->where('email',$email);
        return $this->db->get('access_users')->result();
    }
    
    public function get_province($email)
    {        
        $this->db->select('province');
        $this->db->where('email',$email);
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function get_name($email)
    {
        $this->db->select('family_name');
        $this->db->select('first_name');
        $this->db->where('email',$email);
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function get_user_infos($id)
    {
        $this->db->where('id',$id);
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function check_login($username, $password)
    {       
        $this->db->where('email',$username);
        $this->db->where('password',$password);
        $this->db->from('access_users');
        return $this->db->count_all_results();
    }
    
    public function get_managers($province)
    {
        $this->db->where('province',$province);
        $data = $this->db->get('access_user_provinces');
        return $data->result();
    }    
    
    public function add_user($data)
    {
        $this->db->insert('access_users', array(            
            'position' => $data['position'], 
            'family_name' => $data['family_name'], 
            'first_name' => $data['first_name'], 
            'address' => $data['address'], 
            'town' => $data['town'], 
            'postal_code' => $data['postal_code'], 
            'phone' => $data['phone'], 
            'email' => $data['email'], 
            'password' => $data['password'], 
            'active' => $data['active'], 
            'type' => '2',
            'date_created' => date('Y-m-d H:i:s'),
        ));      

        return $this->db->insert_id();
    }
    
    public function delete_user($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('access_users_copy');
    }
    
    public function update_user($id, $data)
    {
        $this->db->where('id',$id);     
        $this->db->update('access_users',array(            
            'position' => $data['position'], 
            'family_name' => $data['family_name'], 
            'first_name' => $data['first_name'], 
            'address' => $data['address'], 
            'town' => $data['town'], 
            'postal_code' => $data['postal_code'], 
            'phone' => $data['phone'], 
            'email' => $data['email'], 
            'password' => $data['password'], 
            'active' => $data['active'], 
            'date_modif' => date('Y-m-d H:i:s'), 
        ));   
       
    }
}
