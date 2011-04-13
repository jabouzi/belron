<?php

class storesmanager_controller
{

    private $stores;
    private $user;
    private $users;
    private $errors;
    
    function __construct() 
    {
        $this->stores = load::model('stores');     
        $this->users = load::model('users'); 
        $this->permissions = load::model('permissions');      
        load::library('pagination');  
        load::library('encrypt');
    }    
    
    public function index()
    {        
        load::view('header');
        load::view('page-index');
        load::view('footer');        
    }
    
    public function login($uname,$pwd)
    {
        if (isAjax())
        {
            if ($uname == 'admin' && $pwd == 'admin')
            {
                session::set('lang','fr');
                session::set('store',$uname);
                echo 1;
            }    
            else
            {
                echo 0;
            }        
        }
        else
        {
            echo 0;
        }
    }
    
    public function logout()
    {
        session::delete('store');  
        header('location: '.HOME_URL);
    }
    
    public function lists($current_page = 1, $number = 'all', $sort = 0, $type = 1)
    {       
        if (is_logged(session::get('store')))
        {
            $numbers = array();
            $rows = get_sort_rows();
            $total_stores = $this->stores->count();
            
            if ($total_stores > 5 and $total_stores < 10) $numbers = array(5,'all');
            if ($total_stores > 10 and $total_stores < 20) $numbers = array(5,10,'all');
            if ($total_stores > 20 and $total_stores < 50) $numbers = array(5,10,20,'all');
            if ($total_stores > 50 and $total_stores < 100) $numbers = array(5,10,20,50,'all');
            if ($total_stores > 100) $numbers = array(5,10,20,50,100,'all');
            
            $unbr = $number;
            if ($number == 'all') $unbr = $total_stores;
            

            $page = new pagination($total_stores,$current_page,$unbr);
            $sort_order = 'ASC';
            if ($type == 0) $sort_order = 'DESC';
            $stores = $this->stores->get_all_page($page->limit,$page->min,$rows[$sort],$sort_order);
            load::view('header');
            load::view('stores-index',array('stores' => $stores, 'page' => $page, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'numbers' => $numbers, 'number' => $number, 'total_stores' => $total_stores));
            load::view('footer'); 
        }
        else
        {
            url::redirect('storesmanager/index');
        }
    }
    
    public function edit($id)
    {
        if (is_logged(session::get('store')))
        { 
            $store = $this->stores->get_store_infos($id);
            $admin_permissions = $this->get_store_permissions($store[0]->store_id);
            $users = $this->users->get_managers($store[0]->province);
            $admins = array();
            foreach($users as $user)
            {
                $admins[] =  $this->users->get_name($user->email);
            }
            load::view('header');
            load::view('edit-store',array('store' => $store, 'admins' => $admins, 'admin_permissions' => $admin_permissions));
            load::view('footer'); 
        }
        else
        {
            url::redirect('storesmanager/index');
        }    
    }
    
    public function update()
    {  
        if (is_logged(session::get('store')))
        {      
            $data['id'] = mysql_escape_string(input::post('id'));
            $data['store_id'] = mysql_escape_string(input::post('store_id'));
            $data['name'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('name')))));
            $data['address'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('address')))));
            $data['city'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('city')))));
            $data['postal_code'] = mysql_escape_string(strtoupper(format_postalcode(input::post('postal_code1'),input::post('postal_code2'))));
            $data['province'] = mysql_escape_string(utf8_decode(input::post('province')));
            $data['phone'] = mysql_escape_string(format_phone(input::post('phone1'),input::post('phone2'),input::post('phone3')));
            $data['fax'] = mysql_escape_string(format_phone(input::post('fax1'),input::post('fax2'),input::post('fax3')));
            $data['manager_or_owner'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('manager_name')))));
            $data['dm_id'] = mysql_escape_string(input::post('dm_id'));
            $data['cart_active'] = mysql_escape_string(input::post('checkbox_cart_active'));            
            $store_permissions = input::post('user-permissions');     
            
            $res = $this->stores->update_store($data['id'],$data);       
           
            $this->permissions->delete_store_permissions($data['store_id']);

            $this->add_store_permissions($data['store_id'],$store_permissions);
            
            
            
            $url = HOME_URL."stores/update/";           
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            url::redirect('storesmanager/confirm_update');
        }
        else
        {
            url::redirect('storesmanager/index');
        }     
    }
    
    public function add()
    {
        if (is_logged(session::get('store')))
        {
            load::view('header');
            load::view('add-store');
            load::view('footer'); 
        }
        else
        {
            url::redirect('storesmanager/index');
        } 
    }
    
    public function insert()
    {   
        if (is_logged(session::get('store')))
        {      
            $data['store_id'] = mysql_escape_string(input::post('store_id'));
            $data['name'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('name')))));
            $data['address'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('address')))));
            $data['city'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('city')))));
            $data['postal_code'] = mysql_escape_string(strtoupper(format_postalcode(input::post('postal_code1'),input::post('postal_code2'))));
            $data['province'] = mysql_escape_string(utf8_decode(input::post('province')));
            $data['phone'] = mysql_escape_string(format_phone(input::post('phone1'),input::post('phone2'),input::post('phone3')));
            $data['fax'] = mysql_escape_string(format_phone(input::post('fax1'),input::post('fax2'),input::post('fax3')));
            $data['manager_or_owner'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('manager_name')))));
            $data['dm_id'] = mysql_escape_string(input::post('dm_id'));
            $data['cart_active'] = mysql_escape_string(input::post('checkbox_cart_active'));
            
            $res = $this->stores->add_store($data);
            
            $url = HOME_URL."stores/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            url::redirect('storesmanager/confirm_insert');
        }
        else
        {
            url::redirect('storesmanager/index');
        }
    }
    
    public function delete($stores)
    {
        if (isAjax())
        {
            $stores_ids = explode(',',$stores);
            foreach($stores_ids as $store_ids)
            {
                $this->stores->delete_store($store_ids);
            }
            
            $url = HOME_URL."stores/delete/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            $data['stores_ids'] = $stores_ids;
            send_post_data($url,$data);
            echo 1;
        }
        else echo 0;
    }
    
    public function confirm_update()
    {
        load::view('header');
        load::view('update-confirm');
        load::view('footer'); 
    }
    
    public function confirm_insert()
    {
        load::view('header');
        load::view('add-confirm');
        load::view('footer'); 
    }
    
    public function change_language()
    {
        session::set('lang','en');   
        if (input::post('lang') == 'fr') session::set('lang','fr');        

        url::redirect(input::post('redirect_url'));   
    }    
    
    public function get_store_permissions($id)
    {
        $store_permissions = $this->permissions->get_store_permissions($id);        
        foreach($store_permissions as $store)
        {
            $supervisers[] = $store->superviser;
        }
        return $supervisers;
    }
    
    public function add_store_permissions($store,$store_permissions)
    {
        foreach($store_permissions as $permissions)
        {        
            $data['superviser'] = $permissions;
            $data['store'] = $store;
            $this->permissions->update_store_permissions($data);
        }
    }
}
