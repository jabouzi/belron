<?php

class Products_model extends CI_Model 
{
    public function get_all()
    {
        return $this->db->get('access_products')->all();
    }
    
    public function count()
    {
        return $this->db->count_all('access_products');
    }
    
    public function get_products_by_category($cat_id,$lang,$brand)
    {
        $sql = "SELECT access_products.id, access_products.name, access_products.description 
                FROM access_products, access_category 
                WHERE access_products.category_id = access_category.id 
                AND access_category.id = '{$cat_id}'
                AND (access_products.brand = '{$brand}' OR brand = 'Generic' OR brand = '')
                ";        
        $data = $this->db->query($sql);
        return $data->result();
    }
    
    public function get_products_by_groups($cat_id,$lang)
    {
        $sql = "SELECT distinct access_products.group_id
                FROM access_products, access_category
                WHERE access_products.category_id = access_category.id 
                AND access_category.id = '{$cat_id}'
                AND access_products.lang = '{$lang}'";
        $data = $this->db->query($sql);
        return $data->result();
    }
    
    public function get_product($id)
    {
        $sql = "SELECT *
                FROM access_products
                WHERE access_products.id = '{id}'";
        $data = $this->db->query($sql);
        
        return $data->result();
    }
    
    public function get_products_by_id($id)
    {       
        $this->db->where('id',$id);        
        $data = $this->db->get('access_products');        
        return $data->result();
    }
    
    public function get_product_price($id, $quantity)
    {
        $this->db->select($quantity);
        $this->db->where('id',$id);        
        $data = $this->db->get('access_products');       
        return $data->result();
    }
    
    public function get_product_dimensions($id)
    {
        $this->db->select('dimension');
        $this->db->where('id',$id);        
        $data = $this->db->get('access_products');       
        return $data->result();
    }
    
    public function get_all_page($limit, $offset, $order_by, $type)
    {
        $this->db->order_by($order_by, $type);
        $data = $this->db->get('access_products',$limit,$offset); 
        return $data->result();
    }    
    
    public function get_product_infos($id)
    {
        $this->db->where('id',$id);  
        $data = $this->db->get('access_products');
        return $data->result();
    }    
    
    public function get_last_products_id($cat_id)
    {
        $sql = "SELECT access_products.id
                FROM access_products, access_category 
                WHERE access_products.category_id = access_category.id 
                AND access_category.id = '{$cat_id}'
                ORDER BY access_products.id DESC";
        $data = $this->db->query($sql);        
        return $data->result();
    }
    
    public function add_product($data)
    {
        $this->db->insert('access_products',array(                       
            'id' => $data['id'],
            'category_id' => $data['category'], 
            'brand' => $data['brand'], 
            'name' => $data['name'],
            'lang' => $data['lang'],
            'description' => $data['description'],
            'dimension' => $data['dimension'],
            'impression_materiel' => $data['impression_materiel'],             
            'date_created' => date('Y-m-d H:i:s'),                  
        ));      

        return $this->db->insert_id();
    }
    
    public function delete_product($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('access_products');
    }
    
    public function update_product($id, $data)
    {
        $this->db->where('id',$id);
        $this->db->update('access_products',array(          
            'name' => $data['name'],
            'brand' => $data['brand'],
            'lang' => $data['lang'],
            'description' => $data['description'],
            'dimension' => $data['dimension'],
            'impression_materiel' => $data['impression_materiel'],            
            'prix_pour_1' => $data['prix_pour_1'],
            'prix_pour_2' => $data['prix_pour_2'],
            'prix_pour_3' => $data['prix_pour_3'],
            'prix_pour_4' => $data['prix_pour_4'],
            'prix_pour_5' => $data['prix_pour_5'],
            'prix_pour_20' => $data['prix_pour_20'],
            'prix_pour_50' => $data['prix_pour_50'],
            'prix_pour_100' => $data['prix_pour_100'],
            'prix_pour_150' => $data['prix_pour_150'],
            'prix_pour_250' => $data['prix_pour_250'],
            'prix_pour_500' => $data['prix_pour_500'],
            'prix_pour_1000' => $data['prix_pour_1000'], 
            'active' => $data['active'], 
            'date_modif' => date('Y-m-d H:i:s'), 
        ));       
    }
}
