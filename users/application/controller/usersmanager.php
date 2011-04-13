<?php

class usersmanager_controller
{

    private $user;
    private $users;
    private $errors;
    
    function __construct() 
    {
        $this->users = load::model('users');     
        $this->stores = load::model('stores');     
        $this->permissions = load::model('permissions');     
        load::library('pagination');  
        load::library('encrypt');
    }    
    
    public function index()
    {       
        if (is_logged(session::get('user')))
        {            
            url::redirect('usersmanager/lists');
        }
        else
        {
            header('location: '.HOME_URL);
        }
    }
    
    public function login($uname,$pwd)
    {
        if (isAjax())
        {
            if ($uname == 'admin' && $pwd == 'admin')
            {
                session::set('lang','fr');
                session::set('user',$uname);
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
        session::delete('user');  
        header('location: '.HOME_URL);
    }
    
    public function lists($current_page = 1, $number = 'all', $sort = 1, $type = 1)
    {       
        if (is_logged(session::get('user')))
        {
            $numbers = array();
            $rows = get_sort_rows();
            $total_users = $this->users->count();
            
            if ($total_users > 5 and $total_users < 10) $numbers = array(5,'all');
            if ($total_users > 10 and $total_users < 20) $numbers = array(5,10,'all');
            if ($total_users > 20 and $total_users < 50) $numbers = array(5,10,20,'all');
            if ($total_users > 50 and $total_users < 100) $numbers = array(5,10,20,50,'all');
            if ($total_users > 100) $numbers = array(5,10,20,50,100,'all');
            
            $unbr = $number;
            if ($number == 'all') $unbr = $total_users;
            

            $page = new pagination($total_users,$current_page,$unbr);
            $sort_order = 'ASC';
            if ($type == 0) $sort_order = 'DESC';
            $users = $this->users->get_all_page($page->limit,$page->min,$rows[$sort],$sort_order);
            load::view('header');
            load::view('users-index',array('users' => $users, 'page' => $page, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'numbers' => $numbers, 'number' => $number, 'total_users' => $total_users));
            load::view('footer'); 
        }
        else
        {
            url::redirect('usersmanager/index');
        }
    }
    
    public function edit($user_id)
    {
        if (is_logged(session::get('user')))
        { 
            $user = $this->users->get_user_infos($user_id);
            load::view('header');
            load::view('edit-user',array('user' => $user, 'user_permissions' => $this->get_user_permissions($user_id), 
                        'store_permissions' => $this->get_store_permissions($user_id),
                        'stores' => $this->stores->get_all_active($user[0]->province),'users' => $this->users->get_all_active()));
            load::view('footer'); 
        }
        else
        {
            url::redirect('usersmanager/index');
        }    
    }
    
    public function update()
    {  
        if (is_logged(session::get('user')))
        {
            $data['id'] = mysql_escape_string(input::post('id'));
            $data['position'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('position')))));
            $data['family_name'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('family_name')))));
            $data['first_name'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('first_name')))));
            $data['address'] = mysql_escape_string(utf8_decode(input::post('address')));
            $data['town'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('town')))));
            $data['province'] = mysql_escape_string(utf8_decode(input::post('province')));
            $data['postal_code'] = mysql_escape_string(strtoupper(format_postalcode(input::post('postal_code1'),input::post('postal_code2'))));
            $data['phone'] = mysql_escape_string(format_phone(input::post('phone1'),input::post('phone2'),input::post('phone3')));
            $data['email'] = mysql_escape_string(utf8_decode(strtolower(input::post('email'))));
            $data['password'] = md5(input::post('password'));
            $user_permissions = input::post('user-permissions');
            $store_permissions = input::post('store-permissions');
            
            $res = $this->users->update_user($data['id'],$data);
            
            $this->permissions->delete_user_permissions($data['id']);
            $this->permissions->delete_store_permissions($data['id']);
            
            $this->add_user_permissions($data['id'],$user_permissions);
            $this->add_store_permissions($data['id'],$store_permissions);
                        
            $url = HOME_URL."users/update/";            
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            url::redirect('usersmanager/confirm_update');
        }
        else
        {
            url::redirect('usersmanager/index');
        }     
    }
    
    public function add()
    {
        if (is_logged(session::get('user')))
        { 
            load::view('header');
            load::view('add-user',array('user_permissions' => $this->get_user_permissions($user_id), 
                        'store_permissions' => $this->get_store_permissions($user_id),
                        'stores' => $this->stores->get_all_active(),'users' => $this->users->get_all_active()));
            load::view('footer'); 
        }
        else
        {
            url::redirect('usersmanager/index');
        } 
    }
    
    public function insert()
    {   
        if (is_logged(session::get('user')))
        {      
            $positions = get_positions();
            $data['position'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('position')))));
            $data['family_name'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('family_name')))));
            $data['first_name'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('first_name')))));
            $data['address'] = mysql_escape_string(utf8_decode(input::post('address')));
            $data['town'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('town')))));
            $data['province'] = mysql_escape_string(utf8_decode(input::post('province')));
            $data['postal_code'] = mysql_escape_string(strtoupper(format_postalcode(input::post('postal_code1'),input::post('postal_code2'))));
            $data['phone'] = mysql_escape_string(format_phone(input::post('phone1'),input::post('phone2'),input::post('phone3')));
            $data['email'] = mysql_escape_string(utf8_decode(strtolower(input::post('email'))));
            $data['password'] = md5(input::post('password'));
            
            $res = $this->users->add_user($data);
            
            $url = HOME_URL."users/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            url::redirect('usersmanager/confirm_insert');
        }
        else
        {
            url::redirect('usersmanager/index');
        }
    }
    
    public function delete($users)
    {
        if (isAjax())
        {
            $users_ids = explode(',',$users);
            foreach($users_ids as $user_id)
            {
                $this->users->delete_user($user_id);
            }
            
            $url = HOME_URL."users/delete/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            $data['users_ids'] = $users_ids;
            send_post_data($url,$data);
            echo 1;
        }
        else echo 0;
    }
    
    public function get_user_permissions($id)
    {
        $user_permissions = $this->permissions->get_user_permissions($id);
        foreach($user_permissions as $user)
        {
            $data = $this->users->get_user_infos($user->user);
            $users[] = $data[0]->id;
        }
        return $users;
    }
    
    public function get_store_permissions($id)
    {
        $store_permissions = $this->permissions->get_store_permissions($id);        
        foreach($store_permissions as $store)
        {
            $data = $this->stores->get_store_infos($store->store);
            $stores[] = $data[0]->store_id;
        }
        return $stores;
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
    
    public function add_user_permissions($user,$user_permissions)
    {
        foreach($user_permissions as $permissions)
        {
            $data['superviser'] = $user;
            $data['user'] = $permissions;
            $this->permissions->update_user_permissions($data);
        }
    }
    
    public function add_store_permissions($user,$store_permissions)
    {
        foreach($store_permissions as $permissions)
        {        
            $data['superviser'] = $user;
            $data['store'] = $permissions;
            $this->permissions->update_store_permissions($data);
        }
    }
}
