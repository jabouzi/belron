<?php

class orders_controller
{
    private $orders;
    private $stores;
    function __construct() 
    {        
        $this->orders = load::model('orders');    
        $this->stores = load::model('stores');   
        $this->permissions = load::model('permissions');    
        load::library('phpmailer'); 
        load::library('pagination');
    }    
    
    public function index()
    {        
        $this->lists();
    } 
    
    public function lists($id = 0 ,$current_page = 1, $sort = 0, $type = 0)
    {
        if (is_logged(session::get('user')) || session::get('user_type') == 3)
        {
            if ($id == 0)
            {
                if (session::get('user_type') == 1)
                {
                    url::redirect('admin/dashboard');               
                }
                else
                {
                    $rows = get_sort_rows();
                    $user = load::model('users'); 
                    $user_id = $user->get_id(session::get('user'));
                    $total_orders = $this->orders->manager_count($user_id);                               
                    $page = new pagination($total_orders,$current_page,10);
                    $sort_order = 'DESC';
                    if ($type == 1) $sort_order = 'ASC';
                    $orders = $this->orders->order_manager_page($user_id,$page->limit,$page->min,$rows[$sort],$sort_order);        
                    $users = array();
                    foreach($orders as $key => $order)
                    {                    
                        $name = $user->get_name($order->changed_by);
                        $users[$key][] = $name[0]->first_name;
                        $users[$key][] = $name[0]->family_name;
                    }   
                }
                load::view('header');
                load::view('orders',array('orders' => $orders, 'page' => $page, 'users' => $users, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type));
                load::view('footer');
            }
            else if ($id > 0)
            {
                if (session::get('user_type') == 1)
                {
                    $order = $this->orders->get($id);
                    $order_list = unserialize($order[0]->wish_list);  
                    $stores_supervised = $this->get_all_stores();                                       
                }
                else
                {
                    $order = $this->orders->get($id);
                    $order_list = unserialize($order[0]->wish_list);    
                    $stores_supervised = $this->get_stores_supevised(session::get('user_id'));            
                }         
                
                if (empty($order_list))
                {                    
                    url::redirect('orders/lists/0');
                }
                else
                {
                    $product = load::model('products');                    
                    foreach($order_list['items'] as $key => $item)
                    {                        
                        $product_price = $product->get_product_price($item, $order_list['quantity'][$item]);
                        $price = get_object_vars($product_price[0]);
                        $prod_data = $product->get_products_by_id($item);
                        $order_list['items'][$key] = $prod_data;
                        $order_list['price'][$item] = $price[$order_list['quantity'][$item]];
                    }
                    
                    load::view('header');
                    if ($order[0]->approved)
                    {
                        
                        load::view('order_approved',array('order' => $order_list, 'store' => $this->stores->get($order[0]->store_id), 'order_id' => $id, 'rows' => get_quantities(), 'stores_supervised' => $stores_supervised));
                    }
                    else
                    {
                        load::view('order',array('order' => $order_list, 'store' => $this->stores->get($order[0]->store_id), 'order_id' => $id, 'rows' => get_quantities()));
                    }
                    load::view('footer');
                }                     
            }            
        }
        else
        {
            url::redirect('login/userlogin');
        }
    }    
    
    public function detail($id)
    {
        if (is_logged(session::get('user')))
        {            
            $order =  $this->orders->get($id); 
            if ($order[0]->store_id == session::get('user'))
            {
                $order = $this->orders->get($id);
                $order_list = unserialize($order[0]->wish_list);   
                $product = load::model('products');        
                $approved = $this->orders->is_approved($id);            
                foreach($order_list['items'] as $key => $item)
                {                        
                    $product_price = $product->get_product_price($item, $order_list['quantity'][$item]);
                    $price = get_object_vars($product_price[0]);
                    $prod_data = $product->get_products_by_id($item);
                    $order_list['items'][$key] = $prod_data;
                    $order_list['price'][$item] = $price[$order_list['quantity'][$item]];
                } 
                load::view('header');                    
                load::view('order_approved',array('order' => $order_list, 'store' => $this->stores->get($order[0]->store_id), 'order_id' => $id, 'rows' => get_quantities(), 'approved' => $approved[0]->approved));
                load::view('footer');  
            }
            else
            {
                url::redirect('login/storelogin');
            }
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }
    
    public function historique($current_page = 1, $sort = 0, $type = 0)
    {
        if (is_logged(session::get('user')))
        {            
            if (session::get('user_type') == 3)
            {
                $rows = get_sort_rows();
                $user = load::model('users');   
                $total_orders = $this->orders->store_count(load::model('users'));
                $page = new pagination($total_orders,$current_page,10);
                $sort_order = 'DESC';
                if ($type == 1) $sort_order = 'ASC';
                $orders = $this->orders->get_by_store_id_page(session::get('user'),$page->limit,$page->min,$rows[$sort],$sort_order);   
                $users = array();
                foreach($orders as $key => $order)
                {
                    $name = $user->get_name($order->changed_by);
                    $users[$key][] = $name[0]->first_name;
                    $users[$key][] = $name[0]->family_name;
                }                        
            }
            load::view('header');
            load::view('historique',array('orders' => $orders,'page' => $page, 'users' => $users, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type));
            load::view('footer');    
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }
    
    public function product($id)
    {        
        if (is_logged(session::get('user')))
        {            
            $product = load::model('products');
            load::view('header');
            load::view('order_product',array('product' => $product->get_products_by_id($id), 'store_id' => session::get('user')));
            load::view('footer');    
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }   
    
    public function approve()
    {
        if (is_logged(session::get('user')))
        {   
            $user = load::model('users');
            $saved_wish_list = array();
            $producs_qtys = input::post('product_qty');
            $products_ids = input::post('product_id');
            $products_removed = input::post('checkbox_supprim');  

            foreach($producs_qtys as $key => $product_qty)
            {                
                if (!in_array($products_ids[$key],$products_removed))
                {
                    if ($product_qty != '') 
                    {
                        $saved_wish_list['items'][] = $products_ids[$key];
                        $saved_wish_list['quantity'][$products_ids[$key]] = $product_qty;
                        $saved_wish_list['shipping'] = array(input::post('radio_shipping'),'shipping0' => input::post('hidden_shipping0'),'shipping1' => input::post('hidden_shipping1'),'shipping2' => input::post('hidden_shipping2'), 'total' => input::post('hidden_total'));
                    }
                }
            }
            
            $user_id = $user->get_id(session::get('user'));
            $this->orders->approve(serialize($saved_wish_list), input::post('order_id'), '1', session::get('user'), input::post('hidden_total'));            
            
            $address = array('address' => input::post('address'), 
                            'city' => input::post('city'), 
                            'postal_code' => input::post('postal_code'), 
                            'province' => input::post('province'), 
                            'phone' => input::post('phone'),
                            'fax' => input::post('fax'));
                            
            if (!empty($saved_wish_list))
            {
                $this->stores->update_address(session::get('user'),$address);
                
                $mailer = new phpmailer();
                $mailer->IsSendmail();
                $mailer->From = 'noreply@domain.com';
                $mailer->FromName = 'Name';
                $mailer->Subject = 'Order approved';
                $email_message = "<a href='".url::base()."login/userlogin/".input::post('order_id')."/'>Click ici</a>";             
                $mailer->MsgHTML($email_message);
                $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
                $mailer->Send();
            }
            
            url::redirect('orders/confirmation');
        }  
        else
        {
            url::redirect('login/storelogin');
        }
    }
    
    public function approve_order($id)
    {
        $res = $this->orders->approve_direct($id, '1', session::get('user'));
        url::redirect('orders/lists/0');
    }
    
    public function order_again($id)
    {                
        $order = $this->orders->get($id);
        if (session::get('user_type') == 3)
        {
             $order_id = $this->orders->duplicate($order[0]->wish_list, session::get('user'), '', $order[0]->total_cost);
        }
        else
        {
            $stores = input::post('store-orders');
            foreach($stores as $store)
            {
                $order_id = $this->orders->duplicate($order[0]->wish_list, $store, session::get('user'), $order[0]->total_cost);
            }
        }
        
        url::redirect("wishlist/pos/".$order_id);
    }
    
    public function confirmation()
    {
        load::view('header');
        load::view('store_confirmation');
        load::view('footer');
    }
    
    public function remove($id, $products)
    {
        if (isAjax())
        {           
            $products_ids = explode(',',$products);
            $list = $this->orders->get($id);
            if (!empty($list))
            {
                $wish_list = unserialize($list[0]->wish_list); 
                foreach($products_ids as $product_id)
                {                              
                    $wish_list['items'] = $this->remove_item_by_value($wish_list['items'],$product_id);            
                    unset($wish_list['quantity'][$product_id]);
                }
                if (count($wish_list['items']) == 0 and count($wish_list['quantity']) == 0)
                {                
                    $wish_list = array();
                    $this->orders->approve(serialize($wish_list), $id, '1', session::get('user')); 
                }
                else
                {
                    $this->orders->update(serialize($wish_list), $id, session::get('user'));   
                }
                echo 1;
            }  
            else echo 0; 
        }
    }
    
    function remove_item_by_value($array, $val) 
    {
        if (empty($array) || !is_array($array)) return array();
        if (!in_array($val, $array)) return $array;

        foreach($array as $key => $value) {
            if ($value == $val) unset($array[$key]);
        }

        return $array;
    }    
    
    public function get_users_supevised($user_id)
    {
        $datas = $this->permissions->get_user_permissions($user_id);
        foreach($datas as $data)
        {
            $users[] = $data->user;
        }
        
        return $users;
    }
    
    public function get_stores_supevised($user_id)
    {
        $datas = $this->permissions->get_store_permissions($user_id);
        foreach($datas as $data)
        {
            $stores[] = $data->store;
        }
        return $stores;
    }
    
    public function get_all_stores()
    {
        $datas = $this->stores->get_all();
        foreach($datas as $data)
        {
            $stores[] = $data->store_id;
        }
        return $stores;
    }
    
    public function make_order()
    {
        url::redirect('categories');
    }
}
