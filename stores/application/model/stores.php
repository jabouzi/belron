<?php

class stores_model
{
    public function count()
    {
        $table = db('access_stores');       
        return $table->count()->execute();
    }
    
    public function get_all_distinct()
    {
        $table = db('access_stores');
        $data = $table->select('*')->execute();
        return $data;
    }
    
    public function get_all()
    {
        $table = db('access_stores');
        $data = $table->distinct('store_id');
        return $data;
    }
    
    public function get_all_page($limit, $offset, $order_by, $type)
    {
        //var_dump($limit, $offset, $order_by, $type);
        $table = db('access_stores');
        $orders = $table->select('*')->order_by($order_by, $type)->limit($limit)->offset($offset)->execute();
        //var_dump($orders);
        return $orders;
    }    
    
    public function get_store_infos($id)
    {
        $table = db('access_stores');
        $data = $table->select('*')->where('id','=',$id)->execute();
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













 
