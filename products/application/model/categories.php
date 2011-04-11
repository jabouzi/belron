<?php

class categories_model
{
    public function get_all()
    {
        $table = db('access_category');
        $data = $table->select('*')->execute();
        return $data;
    }    
}













 
