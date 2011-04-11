<?php

class stores_model
{    
    public function get_distinct()
    {
        $data = db::query("SELECT DISTINCT name FROM access_stores WHERE cart_active =1");
        return $data;
    } 
    
    public function get_all_distinct()
    {
        $data = db::query("SELECT * FROM access_stores GROUP BY name");
        return $data;
    } 
    
    public function get_all()
    {
        $table = db('access_stores');
        $data = $table->select('*')->where('cart_active','=','1')->execute();
        return $data;
    } 
    
    public function get($store_id)
    {
        $table = db('access_stores');
        $data = $table->select('*')->where('store_id','=',$store_id)->execute();
        return $data;
    } 
    
    public function get_store_dm_id($store_id)
    {
        $table = db('access_stores');
        $data = $table->select('dm_id')->where('store_id','=',$store_id)->execute();
        return $data;
    }    
    
    public function update_address($store_id, $address)
    {
        $table = db('access_stores');
        $table->update($address)->where('store_id','=',$store_id)->execute();
    }
    
    public function check_login($username, $password)
    {
        $table = db('access_stores');
        $data = $table->count()->where('name','=',$username)->clause('AND')->where('store_id','=',$password)->execute();
        return $data;
    }
    
    public function add_store($data)
    {
        $table = db('access_stores');
        $res = $table->insert(array(            
            'store_id' => $data['store_id'], 
            'name' => $data['name'], 
            'address' => $data['address'], 
            'city' => $data['city'], 
            'postal_code' => $data['postal_code'], 
            'province' => $data['province'], 
            'phone' => $data['phone'], 
            'fax' => $data['fax'], 
            'manager_or_owner' => $data['manager_or_owner'], 
            'dm_id' => $data['dm_id'],
            'cart_active' => $data['cart_active'],
        ));      

        return $res;
    }
    
    public function delete_store($id)
    {
        $table = db('access_stores');
        $table->delete()->where('id','=',$id)->execute();
    }
    
    public function update_store($id, $data)
    {
        $table = db('access_stores');
        $res = $table->update(array(            
            'store_id' => $data['store_id'], 
            'name' => $data['name'], 
            'address' => $data['address'], 
            'city' => $data['city'], 
            'postal_code' => $data['postal_code'], 
            'province' => $data['province'], 
            'phone' => $data['phone'], 
            'fax' => $data['fax'], 
            'manager_or_owner' => $data['manager_or_owner'], 
            'dm_id' => $data['dm_id'],
            'cart_active' => $data['cart_active'], 
        ))->where('id','=',$id)->execute();
        
        return $res;
    }
}
