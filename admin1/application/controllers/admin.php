<?php

class Admin extends CI_Controller
{    
    function __construct() 
    {
        parent::__construct();          
        $this->load->model('stores_model');       
        $this->load->model('orders_model');       
        $this->load->model('users_model');          
        $this->load->model('status_model');    
        $this->load->model('products_model');    
        $this->load->model('permissions_model');    
        $this->load->library('class_pagination');  
    }    
    
    function index()
    {        
        if (isset($this->session->userdata['user']))
        {
            if ($this->session->userdata['user_type'] == 3)
            {                
                redirect('categories');
            }            
            else if ($this->session->userdata['user_type'] == 2)
            {
                redirect('admin/dashboard');
            }
            else
            {
                redirect('admin/admin_index');
            }
        }
        else
        {
             redirect('login/storelogin');
        }
    }    
    
    function admin_index()
    {
        if (isset($this->session->userdata['user']))
        {
            $data['admin'] = 1;   
            $data['user'] = 1;                    
            
            $data['lang'] = $this->session->userdata['lang'];
            $data['usertype'] = 0;
            $this->load->view('admin-header',$data);
            $this->load->view('page-index');
            $this->load->view('footer');
        }
        else
        {
             redirect('login/storelogin');
        }
    }
    
    function dashboard($current_page = 1, $sort = 0, $type = 0, $number = 'all', $user_sup = 0)
    {        
        if (isset($this->session->userdata['user']))
        {
            if  ($this->session->userdata['user_type'] == 3)
            {
                redirect('categories');
            }
            else
            {
                $numbers = array();                
                $total_orders = $this->orders_model->count();
                
                if ($total_orders > 5 and $total_orders < 10) $numbers = array(5,'all');
                if ($total_orders > 10 and $total_orders < 20) $numbers = array(5,10,'all');
                if ($total_orders > 20 and $total_orders < 50) $numbers = array(5,10,20,'all');
                if ($total_orders > 50 and $total_orders < 100) $numbers = array(5,10,20,50,'all');
                if ($total_orders > 100) $numbers = array(5,10,20,50,100,'all');
                
                $unbr = $number;
                if ($number == 'all') $unbr = $total_orders;            

                $page = new class_pagination($total_orders,$current_page,$unbr);
                
                $sort_order = 'DESC';
                if ($type == 1) $sort_order = 'ASC';
                $users_supervised[0] = '--';
                if  ($this->session->userdata['user_type'] == 1)
                {
                    $rows = get_sort_rows2();
                    $orders = $this->orders_model->order_page($page->limit,$page->min,$rows[$sort],$sort_order);  
                }
                else
                {   $rows = get_sort_rows();
                    $supervised = $this->get_users_supevised($this->users_model->get_id($this->session->userdata['user']));                    
                    foreach($supervised as $item)
                    {
                        $data = $this->users_model->get_user_infos($item);
                        $users_supervised[$item] = $data[0]->first_name . ' ' .$data[0]->family_name;
                    }
                    if ($user_sup == 0)
                    {
                        $user_id = $this->users_model->get_id($this->session->userdata['user']);
                    }
                    else
                    {
                        $user_id = $user_sup;
                    }
                    $orders = $this->orders_model->order_manager_page($user_id,$page->limit,$page->min,$rows[$sort],$sort_order);  
                }
                $users = array();
                foreach($orders as $key => $order)
                {                    
                    $status_list = array('0','Recieved','In treatment','Send','Problem','Cancelled');
                    $name = $this->users_model->get_name($order->changed_by);
                    if (count($name))
                    {
                        $users[$key][] = $name[0]->first_name;
                        $users[$key][] = $name[0]->family_name;
                    }
                    else
                    {
                        $users[$key][] = '';
                        $users[$key][] = '';
                    }
                    $status = $this->status_model->get($order->id);
                    if (count($status))
                    {
                        $orders_status[$order->id] = $status_list[$status[0]->status]; 
                    }
                    else
                    {
                        $orders_status[$order->id] = '';
                    }
                } 
                           
                $data['lang'] = $this->session->userdata['lang'];
                $data['admin'] = 1;   
                $data['user'] = 1;
                $data['usertype'] = $this->session->userdata['user_type'];
                
                $this->load->view('admin-header',$data);                                    
                $this->load->view('dashboard',array('orders' => $orders, 'page' => $page, 'users' => $users, 'current' => $current_page, 
                            'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'orders_status' => $orders_status, 'user_sup' =>  $user_sup,
                            'users_supervised' => $users_supervised,'numbers' => $numbers, 'number' => $number, 'supervised' => $this->has_user_superviser()));
                $this->load->view('footer');
            }            
        }       
        else
        {
             redirect('login/storelogin');
        }
    }
    
    function order($id)
    {
        if ($this->session->userdata['user_type'] == 1)
        {
            $order = $this->orders_model->get($id);
            $order_list = unserialize($order[0]->wish_list);  
            $stores_supervised = $this->get_all_stores();                                       
        }
        else
        {
            $order = $this->orders_model->get($id);
            $order_list = unserialize($order[0]->wish_list);    
            $stores_supervised = $this->get_stores_supevised($this->session->userdata['user_id']);            
        }     
        
        foreach($order_list['items'] as $key => $item)
        {                        
            $product_price = $this->products_model->get_product_price($item, $order_list['quantity'][$item]);
            $price = get_object_vars($product_price[0]);
            $prod_data = $this->products_model->get_products_by_id($item);
            $order_list['items'][$key] = $prod_data;
            $order_list['price'][$item] = $price[$order_list['quantity'][$item]];
        }

        $data['user'] = 1;
        $data['usertype'] = $this->session->userdata['user_type'];                                        
        $data['lang']  = $this->session->userdata['lang'];                                        
        $status = $this->status_model->get($id);
        $this->load->view('admin-header',$data);                   
        if ($order[0]->approved || $this->has_user_superviser())
        {
            $approved = $this->orders_model->is_approved($id);
            $this->load->view('order_approved',array('order' => $order_list, 'store' => $this->stores_model->get($order[0]->store_id), 
            'pos' => $order[0]->pos, 'user_type' => $this->session->userdata['user_type'], 'status' => $status[0]->status,
            'order_id' => $id, 'rows' => get_quantities(), 'stores_supervised' => $stores_supervised, 'approved' => $approved[0]->approved, 'supervised' => $this->has_user_superviser()));
        }
        else
        {
            $this->load->view('order',array('order' => $order_list, 'store' => $this->stores_model->get($order[0]->store_id), 'order_id' => $id, 'rows' => get_quantities(),'supervised' => $this->has_user_superviser()));
        }
        $this->load->view('footer');
    }
    
    function get_all_stores()
    {
        $datas = $this->stores_model->get_all();
        foreach($datas as $data)
        {
            $stores[] = $data->store_id;
        }
        return $stores;
    }
    
    private function has_store_superviser()
    {
        return $this->permissions_model->get_store_supervisers($this->session->userdata['user']);
    }
    
    private function has_user_superviser()
    {
        return $this->permissions_model->get_user_supervisers($this->users_model->get_id($this->session->userdata['user']));
    }
    
    function get_users_supevised($user_id)
    {
        $datas = $this->permissions_model->get_user_permissions($user_id);
        $users = array();
        foreach($datas as $data)
        {            
            $users[] = $data->user;
        }
        
        return $users;
    }
    
    function get_stores_supevised($user_id)
    {
        $datas = $this->permissions_model->get_store_permissions($user_id);
        $stores = array();
        foreach($datas as $data)
        {
            $stores[] = $data->store;
        }
        return $stores;
    }    
    
    function change_language()
    {
        $this->session->set_userdata(array('lang' => 'en'));   
        if ($_POST['lang'] == 'fr') $this->session->set_userdata(array('lang' => 'fr'));        
        
        redirect($_POST['redirect_url']);   
    }    
    
    function make_order()
    {    
        if ($this->session->userdata['user'] == 2 && !count($this->get_stores_supevised($this->users_model->get_id($this->session->userdata['user']))))
        {
            redirect('admin/dashboard');
        }
        redirect('categories');

    }
}
