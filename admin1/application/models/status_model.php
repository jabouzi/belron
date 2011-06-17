<?php

class Status_model extends CI_Model 
{        
    public function get($order_id)
    {
        $this->db->select('*');
        $this->db->where('order_id',$order_id);        
        $data = $this->db->get('access_orders_status');       
        return $data->result();
    }    
    
    public function insert($order_id, $status, $code_verif)
    {
        $this->db->insert('access_orders_status',array(            
            'order_id' => $order_id,
            'status' => $status,            
            'code_verif' => $code_verif,
        ));
        return $this->db->insert_id();
    }
        
    public function update($order_id, $status, $code_verif)
    {
        $this->db->where('order_id',$order_id);
        $this->db->update('access_orders_status',array('code_verif' => $code_verif, 'status' => $status));
    }   
}
