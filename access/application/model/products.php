<?php

class products_model
{
    public function get_all()
    {
        return db('access_products')->all();
    }
    
    public function get_products_by_category($cat_id,$lang,$brand)
    {
        $sql = "SELECT access_products.id, access_products.name, access_products.description 
                FROM access_products, access_category 
                WHERE access_products.category_id = access_category.id 
                AND access_category.id = '{$cat_id}'
                AND (access_products.brand = '{$brand}' OR brand = 'Generic' OR brand = '')
                ";        
        $data = db::query($sql);
        return $data;
    }
    
    public function get_products_by_groups($cat_id,$lang)
    {
        $sql = "SELECT distinct access_products.group_id
                FROM access_products, access_category
                WHERE access_products.category_id = access_category.id 
                AND access_category.id = '{$cat_id}'
                AND access_products.lang = '{$lang}'";
        $data = db::query($sql);
        return $data;
    }
    
    public function get_products_by_group_id($group_id)
    {
        $table = db('access_products');       
        $data = $table->select('*')->where('group_id','=',$group_id)->execute();        
        return $data;
    }
    
    public function get_products_by_id($id)
    {
        $table = db('access_products');       
        $data = $table->select('*')->where('id','=',$id)->execute();        
        return $data;
    }
    
    public function get_product_price($id, $quatity)
    {
        $table = db('access_products');       
        $data = $table->select($quatity)->where('id','=',$id)->execute();        
        return $data;
    }
    
    public function get_product_dimensions($id)
    {
        $table = db('access_products');       
        $data = $table->select('dimension')->where('id','=',$id)->execute();        
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
            'note' => $data['note'],
            'impression_materiel' => $data['impression_materiel'],
            'prix_1_vinyle_opaque' => $data['prix_1_vinyle_opaque'],
            'prix_2_vinyles_opaque' => $data['prix_2_vinyles_opaque'],
            'prix_3_vinyles_opaque' => $data['prix_3_vinyles_opaque'],
            'prix_4_vinyles_opaque' => $data['prix_4_vinyles_opaque'],
            'prix_5_vinyles_opaque' => $data[' '],
            'prix_1_papier_photo' => $data['prix_1_papier_photo'],
            'prix_2_papiers_photo' => $data['prix_2_papiers_photo'],
            'prix_3_papiers_photo' => $data['prix_3_papiers_photo'],
            'prix_4_papiers_photo' => $data['prix_4_papiers_photo'],
            'prix_5_papiers_photo' => $data['prix_5_papiers_photo'],
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
            'id' => $data['id'],
            'group_id' => $data['group_id'],
            'category_id' => $data['category_id'], 
            'name' => $data['name'],
            'lang' => $data['lang'],
            'description' => $data['description'],
            'dimension' => $data['dimension'],
            'note' => $data['note'],
            'impression_materiel' => $data['impression_materiel'],
            'prix_1_vinyle_opaque' => $data['prix_1_vinyle_opaque'],
            'prix_2_vinyles_opaque' => $data['prix_2_vinyles_opaque'],
            'prix_3_vinyles_opaque' => $data['prix_3_vinyles_opaque'],
            'prix_4_vinyles_opaque' => $data['prix_4_vinyles_opaque'],
            'prix_5_vinyles_opaque' => $data[' '],
            'prix_1_papier_photo' => $data['prix_1_papier_photo'],
            'prix_2_papiers_photo' => $data['prix_2_papiers_photo'],
            'prix_3_papiers_photo' => $data['prix_3_papiers_photo'],
            'prix_4_papiers_photo' => $data['prix_4_papiers_photo'],
            'prix_5_papiers_photo' => $data['prix_5_papiers_photo'],
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
        ))->where('id','=',$id)->execute();
        
        return $res;
    }
}
