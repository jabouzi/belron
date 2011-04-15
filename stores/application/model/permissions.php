<?php

class permissions_model
{
    public function get_store_permissions($store_id)
    {
        $table = db('access_store_permissions');
        $data = $table->select('*')->where('store','=',$store_id)->execute();
        return $data;
    }    
    
    public function update_store_permissions($data)
    {
        $table = db('access_store_permissions');
        $res = $table->insert(array(            
            'superviser' => $data['superviser'], 
            'store' => $data['store'],             
        ));  
    }
    
    public function delete_store_permissions($store_id)
    {
        $table = db('access_store_permissions');
        $data = $table->delete()->where('store','=',$store_id)->execute();
    }
}













 
