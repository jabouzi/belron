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
    
    public function get_all_active($province)
    {
        $table = db('access_stores');
        $data = $table->select('*')->where('cart_active','=','1')->clause('AND')->where('province','=',$province)->execute();
        return $data;
    }   
    
    public function get_store_infos($id)
    {
        $table = db('access_stores');
        $data = $table->select('*')->where('store_id','=',$id)->execute();
        return $data;
    }
}













 
