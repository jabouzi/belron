<?php

class wishlist_model
{    
    public function get($store_id)
    {
        $table = db('access_wishlists');       
        $data = $table->select('*')->where('store_id','=',$store_id)->execute();        
        return $data;
    }
    
    public function insert($list, $store_id)
    {
        $table = db('access_wishlists');
        $data = $table->insert(array(
            'store_id' => $store_id,
            'wish_list' => $list
        ));      
        return $data;
    }
    
    public function update($list, $store_id)
    {
        $table = db('access_wishlists');
        $table->update(array('wish_list' => $list))->where('store_id','=',$store_id)->execute();
    }
    
    public function delete($store_id)
    {
        $table = db('access_wishlists');
        $table->delete()->where('store_id','=',$store_id)->execute();
    }
}
