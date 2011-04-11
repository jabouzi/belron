<?php

class admin_controller
{
    private $stores;
    private $user;
    private $orders;
    private $errors;
    private $user_type;
    
    function __construct() 
    {        
        $this->stores = load::model('stores');       
        $this->orders = load::model('orders');       
        $this->users = load::model('users');     
        load::library('pagination');  
    }    
    
    public function index()
    {        
        if (is_logged(session::get('user')))
        {
            if (session::get('user_type') == 3)
            {                
                url::redirect('categories');
            }
            else if(session::get('user_type') == 2)
            {
                url::redirect('orders/lists/0');
            }
            else
            {
                url::redirect('admin/admin_index');
            }
        }
        else
        {
             url::redirect('login/storelogin');
        }
    }    
    
    public function admin_index()
    {
        load::view('header');
        load::view('page-index');
        load::view('footer');
    }
    
    public function dashboard($current_page = 1, $sort = 0, $type = 0)
    {        
        if (is_logged(session::get('user')))
        {
            if (session::get('user_type') == 1)
            {
                $rows = get_sort_rows();
                $total_orders = $this->orders->count();
                $page = new pagination($total_orders,$current_page,10);
                $sort_order = 'DESC';
                if ($type == 1) $sort_order = 'ASC';
                $orders = $this->orders->order_page($page->limit,$page->min,$rows[$sort],$sort_order);  
                $users = array();
                foreach($orders as $key => $order)
                {                    
                    $name = $this->users->get_name($order->changed_by);
                    $users[$key][] = $name[0]->first_name;
                    $users[$key][] = $name[0]->family_name;
                }                             
                load::view('header');
                load::view('dashboard',array('orders' => $orders, 'page' => $page, 'users' => $users, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type));
                load::view('footer');
            }
            else if (session::get('user_type') == 2)
            {
                url::redirect('orders/lists/0');
            }
            else
            {
                url::redirect('categories');
            }
        }       
        else
        {
             url::redirect('login/storelogin');
        }
    }
    
    public function change_language()
    {
        session::set('lang','en');   
        if (input::post('lang') == 'fr') session::set('lang','fr');        
        
        url::redirect(input::post('redirect_url'));   
    }    
}
