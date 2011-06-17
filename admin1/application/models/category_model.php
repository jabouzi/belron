<?php

class Category_model extends CI_Model 
{
    public function get_all()
    {
        $this->db->where('active','1'); 
        $data = $this->db->get('access_category');
        return $data->result();
    }
    
    public function get_categories($lang)
    {        
        $this->db->select('id', 'name_'.$lang);
        $this->db->where('active','1');  
        $data = $this->db->get('access_category'); 
        return $data->result();
    }
    
    public function get_category($id)
    {
        $this->db->where('id',$id);        
        $data = $this->db->get('access_category');       
        return $data->result();
    }
    
    public function get_all_active()
    {
        $this->db->where('active','1');
        $data = $this->db->get('access_category'); 
        return $data->result();
    }    
    
    public function update($id, $data)
    {
        $this->db->where('id',$id);
        $this->db->update('access_category',array(                       
            'name_fr' => $data['name_fr'],
            'name_en' => $data['name_en'], 
            'image_file' => $data['image_file'],
            'active' => $data['active'],                       
        ));
    }
    
    public function insert($data)
    {
        $this->db->insert('access_category',array(                       
            'name_fr' => $data['name_fr'],
            'name_en' => $data['name_en'], 
            'image_file' => $data['image_file'],
            'active' => $data['active'],                       
        )); 

        return $this->db->insert_id();
    }
}
