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
    
    public function update_user_permissions($data)
    {
        $table = db('access_user_permissions');
        $res = $table->insert(array(            
            'superviser' => $data['superviser'], 
            'user' => $data['user'],             
        ));      

        return $res;
    }
    
    public function update_store_permissions($data)
    {
        $table = db('access_store_permissions');
        $res = $table->insert(array(            
            'superviser' => $data['superviser'], 
            'store' => $data['store'],             
        ));  
    }  
    
    public function delete_user_permissions($user_id)
    {
        $table = db('access_user_permissions');
        $data = $table->delete()->where('superviser','=',$user_id)->execute();
    }
    
    public function delete_store_permissions($user_id)
    {
        $table = db('access_store_permissions');
        $data = $table->delete()->where('superviser','=',$user_id)->execute();
    }

}













 
