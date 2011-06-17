<?php

class Users_model extends CI_Model 
{
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
    
    public function get_province($email)
    {        
        $this->db->select('province');
        $this->db->where('email',$email);
        $data = $this->db->get('access_users');
        return $data->result();
    }
    
    public function get_name($email)
    {
        $this->db->select('first_name','family_name');
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
    
    public function add_user($data)
    {
        $this->db->insert('access_users_copy', array(            
            'position' => $data['position'], 
            'family_name' => $data['family_name'], 
            'first_name' => $data['first_name'], 
            'address' => $data['address'], 
            'town' => $data['town'], 
            'province' => $data['province'], 
            'postal_code' => $data['postal_code'], 
            'phone' => $data['phone'], 
            'email' => $data['email'], 
            'password' => $data['password'], 
            'type' => $data['type'],
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
        $this->db->update('access_users_copy',array(            
            'position' => $data['position'], 
            'family_name' => $data['family_name'], 
            'first_name' => $data['first_name'], 
            'address' => $data['address'], 
            'town' => $data['town'], 
            'province' => $data['province'], 
            'postal_code' => $data['postal_code'], 
            'phone' => $data['phone'], 
            'email' => $data['email'], 
            'password' => $data['password'], 
        ));   
       
    }
}
