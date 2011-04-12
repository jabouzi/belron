<?php

class categories_model
{
    public function get_all()
    {
        $table = db('access_category');
        $data = $table->select('*')->execute();
        return $data;
    }    
    
    public function get_category($id)
    {
        $table = db('access_category');
        $data = $table->select('*')->where('id','=',$id)->execute();   
        return $data;
    }    
    
    public function update($id, $data)
    {
        $table = db('access_category');
        $res = $table->update(array(                       
            'name_fr' => $data['name_fr'],
            'name_en' => $data['name_en'], 
            'image_file' => $data['image_file'],
            'active' => $data['active'],                       
        ))->where('id','=',$id)->execute();      

        return $res;
    }
    
    public function insert($data)
    {
        $table = db('access_category');
        $res = $table->insert(array(                       
            'name_fr' => $data['name_fr'],
            'name_en' => $data['name_en'], 
            'image_file' => $data['image_file'],
            'active' => $data['active'],                       
        )); 

        return $res;
    }
}













 
