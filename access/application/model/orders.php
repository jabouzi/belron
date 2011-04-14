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
    
    public function insert($list, $store_id, $approved, $total_cost, $approve_date)
    {
        $table = db('access_orders');
        $row = $table->insert(array(            
            'store_id' => $store_id,
            'wish_list' => $list,
            'approved' => $approved,
            'save_data' => date('Y-m-d H:i:s'),
            'approve_date' => $approve_date,
            'total_cost' =>  $total_cost
        ));
        return $row->id;
    }
    
    public function duplicate($list, $store_id, $approved_by, $total_cost, $approved, $approve_date)
    {
        $table = db('access_orders');
        $row = $table->insert(array(            
            'store_id' => $store_id,
            'wish_list' => $list,
            'changed_by' => $approved_by,
            'save_data' => date('Y-m-d H:i:s'),
            'approve_date' => date('Y-m-d H:i:s'),
            'approved' => $approved,
            'total_cost' =>  $total_cost,
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
    
    public function approve_direct($id, $approved, $user)
    {        
        $table = db('access_orders');
        $res = $table->update(array('approved' => $approved, 'changed_by' => $user, 'approve_date' => date('Y-m-d H:i:s')))->where('id','=',$id)->execute();
        return $res;
    }    
    
    public function add_pos($id, $pos)
    {
        $table = db('access_orders');
        $table->update(array('pos' => $pos))->where('id','=',$id)->execute();
    }
    
    public function approve_pos($id, $pos)
    {
        $table = db('access_orders');
        $table->update(array('pos' => $pos, 'approved' => '1'))->where('id','=',$id)->execute();
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
    
    public function manager_count($id)
    {
        $sql = "SELECT COUNT(access_orders.id) as count FROM access_orders, access_store_permissions WHERE superviser = {$id} AND access_orders.store_id = store";
        $orders = db::query($sql);
        return $orders;
    }
    
    public function order_manager_page($id,$limit,$offset,$order_by, $type)
    {
        $sql = "SELECT * FROM access_store_permissions, access_orders  WHERE access_orders.store_id = access_store_permissions.store AND access_store_permissions.superviser = {$id} ORDER BY {$order_by} {$type} LIMIT {$limit} OFFSET {$offset} ";
        $orders = db::query($sql);        
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
    
    public function is_approved($id)
    {
        $table = db('access_orders');       
        return $table->select('approved')->where('id','=',$id)->execute();
    }
}
