<?php

class products_model
{
    public function count()
    {
        $table = db('access_products');       
        return $table->count()->execute();
    }
    
    public function get_all()
    {
        $table = db('access_products');
        $data = $table->select('*')->execute();
        return $data;
    }
    
    public function get_all_distinct()
    {
        $table = db('access_products');
        $data = $table->distinct('id');
        return $data;
    }
    
    public function get_all_page($limit, $offset, $order_by, $type)
    {
        $table = db('access_products');
        $products = $table->select('*')->order_by($order_by, $type)->limit($limit)->offset($offset)->execute();        
        return $products;
    }    
    
    public function get_product_infos($id)
    {
        $table = db('access_products');
        $data = $table->select('*')->where('id','=',$id)->execute();   
        return $data;
    }    
    
    public function get_last_products_id($cat_id)
    {
        $sql = "SELECT access_products.id
                FROM access_products, access_category 
                WHERE access_products.category_id = access_category.id 
                AND access_category.id = '{$cat_id}'
                ORDER BY access_products.id DESC";
        $data = db::query($sql);
        return $data;
    }
    
    public function add_product($data)
    {
        $table = db('access_products');
        $res = $table->insert(array(                       
            'id' => $data['id'],
            'group_id' => $data['group_id'],
            'category_id' => $data['category_id'], 
            'name' => $data['name'],
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
            'date_created' => date('Y-m-d H:i:s'),         
        ));      

        return $res;
    }
    
    public function delete_product($id)
    {
        $table = db('access_products');
        $table->delete()->where('id','=',$id)->execute();
    }
    
    public function update_product($id, $data)
    {
        $table = db('access_products');
        $res = $table->update(array(            
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
            
        ))->where('id','=',$id)->execute();
        
        return $res;
    }
}













 
