<?php

class users_model
{
    public function get_managers($province)
    {
        $table = db('access_users');
        $data = $table->select('*')->where('province','=',$province)->execute();
        return $data;
    }    
    
    public function get_name($email)
    {
        $table = db('access_users');
        $data = $table->select('id','first_name','family_name')->where('email','=',$email)->execute();
        return $data;
    }
    
    public function get_user_infos($id)
    {
        $table = db('access_users');
        $data = $table->select('*')->where('id','=',$id)->execute();
        return $data;
    }
    
}













 
