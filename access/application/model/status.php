<?php

class status_model
{        
    public function get($order_id)
    {
        $table = db('access_orders_status');       
        $data = $table->select('*')->where('order_id','=',$order_id)->execute();        
        return $data;
    }    
    
    public function insert($order_id, $status, $code_verif)
    {
        $table = db('access_orders_status');
        $row = $table->insert(array(            
            'order_id' => $order_id,
            'status' => $status,            
            'code_verif' => $code_verif,
        ));
        return $row->id;
    }
        
    public function update($order_id, $status, $code_verif)
    {
        $table = db('access_orders_status');
        $table->update(array('code_verif' => $code_verif, 'status' => $status))->where('order_id','=',$order_id)->execute();
    }   
}
