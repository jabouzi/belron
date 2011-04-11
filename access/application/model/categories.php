<?php

class categories_model
{
    public function get_all()
    {
        return db('access_category')->all();
    }
    
    public function get_categories($lang)
    {
        $table = db('access_category');       
        $data = $table->select('id', 'name_'.$lang)->execute();  
        return $data;
    }
    
    public function get_category($id)
    {
        $table = db('access_category');       
        $data = $table->select('*')->where('id','=',$id)->execute();        
        return $data;
    }
}
