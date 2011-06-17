<?php

class Orders_model extends CI_Model 
{
    public function get_all()
    {
        return $this->db->get('access_orders');
    }
    
    public function get($id)
    {
        $this->db->where('id',$id);        
        $data = $this->db->get('access_orders');       
        return $data->result();
    }    
    
    public function count()
    {     
        return $this->db->count_all('access_orders');
    }
    
    public function get_manager_orders($manager_id)
    {
        $this->db->where('manager',$manager_id);        
        $data = $this->db->get('access_orders');       
        return $data->result();
    }      
    
    public function get_by_store_id($store_id)
    {
        $this->db->where('store_id',$store_id);        
        $data = $this->db->get('access_orders');       
        return $data->result();
    }
    
    public function get_by_user_id($user_id)
    {
        $this->db->where('manager',$user_id);        
        $data = $this->db->get('access_orders');       
        return $data->result();
    }
    
    public function insert($list, $approved_by, $store_id, $approved, $total_cost, $approve_date)
    {
        $this->db->insert('access_orders',array(            
            'store_id' => $store_id,
            'wish_list' => $list,
            'changed_by' => $approved_by,
            'approved' => $approved,
            'save_data' => date('Y-m-d H:i:s'),
            'approve_date' => $approve_date,
            'total_cost' =>  $total_cost
        ));
        return $this->db->insert_id();
    }
    
    public function duplicate($list, $store_id, $approved_by, $total_cost, $approved, $approve_date)
    {
        $this->db->insert('access_orders',array(            
            'store_id' => $store_id,
            'wish_list' => $list,
            'changed_by' => $approved_by,
            'save_data' => date('Y-m-d H:i:s'),
            'approve_date' => date('Y-m-d H:i:s'),
            'approved' => $approved,
            'total_cost' =>  $total_cost,
        ));
        return $this->db->insert_id();
    }
    
        
    public function update($list, $id, $user_id)
    {
        $this->db->where('id',$id);
        $this->db->update('access_orders',array('wish_list' => $list, 'changed_by' => $user_id));
    }
    
    public function approve($list, $id, $approved, $user, $total_cost)
    {
        $this->db->where('id',$id);
        $this->db->update('access_orders',array('wish_list' => $list, 'approved' => $approved, 'changed_by' => $user, 'approve_date' => date('Y-m-d H:i:s'), 'total_cost' =>  $total_cost));
    }    
    
    public function approve_direct($id, $approved, $user)
    {        
        $this->db->where('id',$id);
        $this->db->update('access_orders',array('approved' => $approved, 'changed_by' => $user, 'approve_date' => date('Y-m-d H:i:s')));
    }    
    
    public function add_pos($id, $pos)
    {
        $this->db->where('id',$id);
        $this->db->update('access_orders',array('pos' => $pos));
    }
    
    public function approve_pos($id, $pos)
    {
        $this->db->where('id',$id);
        $this->db->update('access_orders',array('pos' => $pos, 'approved' => '1'));
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('access_orders');
    }
    
    public function order_page($limit,$offset,$order_by, $type)
    {
        $this->db->order_by($order_by, $type);
        $orders = $this->db->get('access_orders',$limit,$offset);
        return $orders->result();
    }
    
    public function manager_count($id)
    {
        $sql = "SELECT COUNT(access_orders.id) as count FROM access_orders, access_store_permissions WHERE superviser = {$id} AND access_orders.store_id = store";
        $orders = $this->db->query($sql);
        return $orders->result();
    }
    
    public function order_manager_page($id,$limit,$offset,$order_by, $type)
    {
        $sql = "SELECT * FROM access_store_permissions, access_orders  WHERE access_orders.store_id = access_store_permissions.store AND access_store_permissions.superviser = {$id} ORDER BY {$order_by} {$type} LIMIT {$limit} OFFSET {$offset} ";
        $orders = $this->db->query($sql);   
        return $orders->result();
    }
    
    public function store_count($store_id)
    {
        $this->db->where('store_id',$store_id);
        return $this->db->count_all_results('access_orders');              
    }
    
    public function get_by_store_id_page($store_id,$limit,$offset,$order_by, $type)
    {
        $this->db->where('store_id',$store_id);
        $this->db->order_by($order_by, $type);
        $data = $this->db->get('access_orders',$limit,$offset);       
        return $data->result();
    }
    
    public function is_approved($id)
    {
        $this->db->select('approved');
        $this->db->where('id',$id);
        return $this->db->get('access_orders')->result();       
    }
}
