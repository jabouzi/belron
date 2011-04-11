<?php

class orders_model
{
    public function get_all()
    {
        return db('access_orders')->all();
    }
    
    public function get($id)
    {
        $table = db('access_orders');       
        $data = $table->select('*')->where('id','=',$id)->execute();        
        return $data;
    }    
    
    public function count()
    {
        $table = db('access_orders');       
        return $table->count()->execute();
    }
    
    public function get_manager_orders($manager_id)
    {
        $table = db('access_orders');       
        $data = $table->select('*')->where('manager','=',$manager_id)->execute();        
        return $data;
    }      
    
    public function get_by_store_id($store_id)
    {
        $table = db('access_orders');       
        $data = $table->select('*')->where('store_id','=',$store_id)->execute();        
        return $data;
    }
    
    public function get_by_user_id($user_id)
    {
        $table = db('access_orders');       
        $data = $table->select('*')->where('manager','=',$user_id)->execute();        
        return $data;
    }
    
    public function insert($list, $store_id, $dm_id, $total_cost)
    {
        $table = db('access_orders');
        $row = $table->insert(array(            
            'store_id' => $store_id,
            'wish_list' => $list,
            'manager' => $dm_id,
            'save_data' => date('Y-m-d H:i:s'),
            'total_cost' =>  $total_cost
        ));
        return $row->id;
    }
    
    public function update($list, $id, $user_id)
    {
        $table = db('access_orders');
        $table->update(array('wish_list' => $list, 'changed_by' => $user_id))->where('id','=',$id)->execute();
    }
    
    public function approve($list, $id, $approved, $user, $total_cost)
    {
        $table = db('access_orders');
        $table->update(array('wish_list' => $list, 'approved' => $approved, 'changed_by' => $user, 'approve_date' => date('Y-m-d H:i:s'), 'total_cost' =>  $total_cost))->where('id','=',$id)->execute();
    }    
    
    public function delete($id)
    {
        $table = db('access_orders');
        $table->delete()->where('id','=',$id)->execute();
    }
    
    public function order_page($limit,$offset,$order_by, $type)
    {
        $table = db('access_orders');
        $orders = $table->select('*')->order_by($order_by, $type)->limit($limit)->offset($offset)->execute();
        return $orders;
    }
    
    public function manager_count($manager_id)
    {
        $table = db('access_orders');       
        return $table->count()->where('manager','=',$manager_id)->execute();
    }
    
    public function order_manager_page($manager_id,$limit,$offset,$order_by, $type)
    {
        $table = db('access_orders');
        $orders = $table->select('*')->where('manager','=',$manager_id)->order_by($order_by, $type)->limit($limit)->offset($offset)->execute();
        return $orders;
    }
    
    public function store_count($store_id)
    {
        $table = db('access_orders');       
        return $table->count()->where('store_id','=',$store_id)->execute();
    }
    
    public function get_by_store_id_page($store_id,$limit,$offset,$order_by, $type)
    {
        $table = db('access_orders');       
        $data = $table->select('*')->where('store_id','=',$store_id)->order_by($order_by, $type)->limit($limit)->offset($offset)->execute();
        return $data;
    }
    
}
