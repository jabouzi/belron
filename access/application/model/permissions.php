<?php

class permissions_model
{
    public function get_user_permissions($user_id)
    {
        $table = db('access_user_permissions');
        $data = $table->select('*')->where('superviser','=',$user_id)->execute();
        return $data;
    }
    
    public function get_store_permissions($user_id)
    {
        $table = db('access_store_permissions');
        $data = $table->select('*')->where('superviser','=',$user_id)->execute();
        return $data;
    }   
    
    public function get_store_supervisers($store_id)
    {
        $table = db('access_store_permissions');
        $data = $table->count()->where('store','=',$store_id)->execute();
        return $data;
    }
    
    public function get_user_supervisers($user_id)
    {
        $table = db('access_user_permissions');
        $data = $table->count()->where('user','=',$user_id)->execute();
        return $data;
    }
    
    public function get_store_supervisers_names($store_id)
    {
        $table = db('access_store_permissions');
        $data = $table->select('*')->where('store','=',$store_id)->execute();
        return $data;
    }
    
    public function get_user_supervisers_names($user_id)
    {
        $table = db('access_user_permissions');
        $data = $table->select('*')->where('user','=',$user_id)->execute();
        return $data;
    }
}













 
