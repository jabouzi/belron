<?php

class Usersmanager extends CI_Controller
{   
    function __construct() 
    {
        parent::__construct();  
        $this->load->model('users_model');     
        $this->load->model('stores_model');     
        $this->load->model('permissions_model');     
        $this->load->library('class_pagination');  
        $this->load->library('class_encrypt');
    }    
    
    public function index()
    {        
        redirect('usersmanager/lists');        
    }       
        
    public function lists($current_page = 1, $number = 'all', $sort = 1, $type = 1)
    {       
        if (isset($this->session->userdata['admin']))
        {
            $numbers = array();
            $rows = get_user_rows();
            $total_users = $this->users_model->count();
            
            if ($total_users > 5 and $total_users < 10) $numbers = array(5,'all');
            if ($total_users > 10 and $total_users < 20) $numbers = array(5,10,'all');
            if ($total_users > 20 and $total_users < 50) $numbers = array(5,10,20,'all');
            if ($total_users > 50 and $total_users < 100) $numbers = array(5,10,20,50,'all');
            if ($total_users > 100) $numbers = array(5,10,20,50,100,'all');
            
            $unbr = $number;
            if ($number == 'all') $unbr = $total_users;
            

            $page = new class_pagination($total_users,$current_page,$unbr);
            $sort_order = 'ASC';
            if ($type == 0) $sort_order = 'DESC';
            $users = $this->users_model->get_all_page($page->limit,$page->min,$rows[$sort],$sort_order);
            $data['lang'] = $this->session->userdata['lang'];
            $this->load->view('user_header',$data);
            $this->load->view('users-index',array('users' => $users, 'page' => $page, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'numbers' => $numbers, 'number' => $number, 'total_users' => $total_users));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('usersmanager/index');
        }
    }
    
    public function edit($user_id)
    {
        if (isset($this->session->userdata['admin']))
        {             
            $user = $this->users_model->get_user_infos($user_id);
            $data['lang'] = $this->session->userdata['lang'];
            $this->load->view('user_header',$data);
            $this->load->view('edit-user',array('user' => $user, 'user_permissions' => $this->get_user_permissions($user_id), 
                        'store_permissions' => $this->get_store_permissions($user_id), 'user_provinces' => $this->get_user_provinces($user_id),
                        'stores' => $this->get_all_stores($user_id),'users' => $this->users_model->get_all_active()));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('usersmanager/index');
        }    
    }
    
    public function update()
    {  
        if (isset($this->session->userdata['admin']))
        {
            $data['id'] = $_POST['id'];
            $data['position'] = ucfirst(strtolower($_POST['position']));
            $data['family_name'] = ucfirst(strtolower($_POST['family_name']));
            $data['first_name'] = ucfirst(strtolower($_POST['first_name']));
            $data['address'] = $_POST['address'];
            $data['town'] = ucfirst(strtolower($_POST['town']));
            $data['postal_code'] = strtoupper(format_postalcode($_POST['postal_code1'],$_POST['postal_code2']));
            $data['phone'] = format_phone($_POST['phone1'],$_POST['phone2'],$_POST['phone3']);
            $data['email'] = strtolower($_POST['email']);
            $data['password'] = $_POST['password'];
            $data['active'] = $_POST['active'];            
            $user_permissions = $_POST['user-permissions'];
            $store_permissions = $_POST['store-permissions'];
            $user_provinces = $_POST['user_provinces'];            
            
            $this->users_model->update_user($data['id'],$data);
            
            $this->permissions_model->delete_user_permissions($data['id']);
            $this->permissions_model->delete_user_stores($data['id']);
            $this->permissions_model->delete_user_provinces($data['id']);
            
            $this->add_user_permissions($data['id'],$user_permissions);
            $this->add_store_permissions($data['id'],$store_permissions);
            $this->add_user_provinces($data['id'],$user_provinces);                        
            
            /*$url = ADMIN."users/update/";            
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);
            
            $url = ADMIN."users/update_update_permissions/";            
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);
            
            $url = ADMIN."users/update_store_permissions/";            
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);
            
            $url = ADMIN."users/update_user_provinces/";            
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);*/

            redirect('usersmanager/confirm_update');
        }
        else
        {
            redirect('usersmanager/index');
        }     
    }
    
    public function add()
    {
        if (isset($this->session->userdata['admin']))
        { 
            $data['lang'] = $this->session->userdata['lang'];
            $this->load->view('user_header',$data);
            $this->load->view('add-user');
            $this->load->view('footer'); 
        }
        else
        {
            redirect('usersmanager/index');
        } 
    }
    
    public function insert()
    {   
        if (isset($this->session->userdata['admin']))
        {      
            $positions = get_positions();
            $data['position'] = ucfirst(strtolower($_POST['position']));
            $data['family_name'] = ucfirst(strtolower($_POST['family_name']));
            $data['first_name'] = ucfirst(strtolower($_POST['first_name']));
            $data['address'] = $_POST['address'];
            $data['town'] = ucfirst(strtolower($_POST['town']));
            $data['postal_code'] = strtoupper(format_postalcode($_POST['postal_code1'],$_POST['postal_code2']));
            $data['phone'] = format_phone($_POST['phone1'],$_POST['phone2'],$_POST['phone3']);
            $data['email'] = strtolower($_POST['email']);
            $data['password'] = $_POST['password'];
            $data['active'] = $_POST['active'];
            $data2['user_provinces'] = $_POST['user_provinces'];
            
            $data2['id'] = $this->users_model->add_user($data);
            $this->add_user_provinces($id,$data2['user_provinces']);
            
            /*$url = ADMIN."users/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);
            
            $url = ADMIN."users/update_user_provinces/";            
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data2);*/

            redirect('usersmanager/confirm_insert');
        }
        else
        {
            redirect('usersmanager/index');
        }
    }
    
    public function delete($users)
    {
        if (isAjax())
        {
            $users_ids = explode(',',$users);
            foreach($users_ids as $user_id)
            {
                $this->users_model->delete_user($user_id);
            }
            
            $url = ADMIN."users/delete/";
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
        $user_permissions = $this->permissions_model->get_user_permissions($id);
        $users = array();
        foreach($user_permissions as $user)
        {
            $data = $this->users_model->get_user_infos($user->user);
            $users[] = $data[0]->id;
        }
        return $users;
    }
    
    public function get_store_permissions($id)
    {
        $store_permissions = $this->permissions_model->get_user_stores($id);  
        $stores = array();
        foreach($store_permissions as $store)
        {                
            $data = $this->stores_model->get_store_infos($store->store);
            $stores[] = $data[0]->store_id;
        }
        return $stores;
    }
    
    public function get_user_provinces($id)
    {
        $user_provinces = $this->permissions_model->get_user_provinces($id);
        $provinces = array();
        foreach($user_provinces as $province)
        {
            $provinces[] = $province->province;
        }
        return $provinces;
    }
    
    public function get_all_stores($id)
    {
        $user_provinces = $this->permissions_model->get_user_provinces($id);
        $all_stores = array();
        foreach($user_provinces as $province)
        {
            $stores = $this->stores_model->get_all_active($province->province);
            foreach($stores as $store)
            {
               $all_stores[] =  $store->store_id;
            }
        }
        
        return $all_stores;
    }
    
    public function confirm_update()
    {
        $data['lang'] = $this->session->userdata['lang'];
        $this->load->view('user_header',$data);
        $this->load->view('update-confirm');
        $this->load->view('footer'); 
    }
    
    public function confirm_insert()
    {
        $data['lang'] = $this->session->userdata['lang'];
        $this->load->view('user_header',$data);
        $this->load->view('add-confirm');
        $this->load->view('footer'); 
    }
    
    public function add_user_permissions($user,$user_permissions)
    {
        foreach($user_permissions as $permissions)
        {
            $data['superviser'] = $user;
            $data['user'] = $permissions;
            $this->permissions_model->update_user_permissions($data);
        }
    }
    
    public function add_store_permissions($user,$store_permissions)
    {
        foreach($store_permissions as $permissions)
        {        
            $data['superviser'] = $user;
            $data['store'] = $permissions;
            $this->permissions_model->update_store_permissions($data);
        }
    }
    
    public function add_user_provinces($user,$user_provinces)
    {
        foreach($user_provinces as $province)
        {        
            $data['superviser'] = $user;
            $data['province'] = $province;
            $this->permissions_model->update_user_provinces($data);
        }
    }
}
