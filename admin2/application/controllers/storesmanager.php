<?php

class Storesmanager extends CI_Controller
{    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('stores_model');     
        $this->load->model('users_model'); 
        $this->load->model('permissions_model');      
        $this->load->library('class_pagination');  
        $this->load->library('class_encrypt');
    }    
    
    public function index()
    {        
        redirect('storesmanager/lists');
    }    
    
    public function lists($current_page = 1, $number = 'all', $sort = 0, $type = 1)
    {       
        if (isset($this->session->userdata['admin']))
        {
            $numbers = array();
            $rows = get_store_rows();
            $total_stores = $this->stores_model->count();
            
            if ($total_stores > 5 and $total_stores < 10) $numbers = array(5,'all');
            if ($total_stores > 10 and $total_stores < 20) $numbers = array(5,10,'all');
            if ($total_stores > 20 and $total_stores < 50) $numbers = array(5,10,20,'all');
            if ($total_stores > 50 and $total_stores < 100) $numbers = array(5,10,20,50,'all');
            if ($total_stores > 100) $numbers = array(5,10,20,50,100,'all');
            
            $unbr = $number;
            if ($number == 'all') $unbr = $total_stores;
            

            $page = new class_pagination($total_stores,$current_page,$unbr);
            $sort_order = 'ASC';
            if ($type == 0) $sort_order = 'DESC';
            $stores = $this->stores_model->get_all_page($page->limit,$page->min,$rows[$sort],$sort_order);
            $data['lang'] = $this->session->userdata['lang'];
            $this->load->view('store_header',$data);
            $this->load->view('stores-index',array('stores' => $stores, 'page' => $page, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'numbers' => $numbers, 'number' => $number, 'total_stores' => $total_stores));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('storesmanager/index');
        }
    }
    
    public function edit($id)
    {
        if (isset($this->session->userdata['admin']))
        { 
            $store = $this->stores_model->get_store_infos($id);
            $admin_permissions = $this->get_store_permissions($store[0]->store_id);
            $users = $this->users_model->get_managers($store[0]->province);
            $admins = array();
            foreach($users as $user)
            {
                $admins[] =  $this->users_model->get_user_infos($user->superviser);
            }
            $data['lang'] = $this->session->userdata['lang'];
            $this->load->view('store_header',$data);
            $this->load->view('edit-store',array('store' => $store, 'admins' => $admins, 'admin_permissions' => $admin_permissions));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('storesmanager/index');
        }    
    }
    
    public function update()
    {  
        if (isset($this->session->userdata['admin']))
        {      
            $data['id'] = $_POST['id'];
            $data['store_id'] = $_POST['store_id'];
            $data['name'] = utf8_decode(ucwords(strtolower($_POST['name'])));
            $data['address'] = utf8_decode(ucwords(strtolower($_POST['address'])));
            $data['city'] = utf8_decode(ucfirst(strtolower($_POST['city'])));
            $data['postal_code'] = strtoupper(format_postalcode($_POST['postal_code1'],$_POST['postal_code2']));
            $data['province'] = utf8_decode($_POST['province']);
            $data['phone'] = format_phone($_POST['phone1'],$_POST['phone2'],$_POST['phone3']);
            $data['fax'] = format_phone($_POST['fax1'],$_POST['fax2'],$_POST['fax3']);
            $data['manager_or_owner'] = utf8_decode(ucwords(strtolower($_POST['manager_name'])));
            $data['cart_active'] = $_POST['checkbox_cart_active'];            
            $store_permissions = $_POST['user-permissions'];     
            
            $res = $this->stores_model->update_store($data['id'],$data);       
           
            $this->permissions_model->delete_store_permissions($data['store_id']);

            $this->add_store_permissions($data['store_id'],$store_permissions);
            
            
            
            /*$url = ADMIN."stores/update/";           
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);
            
            $url = ADMIN."stores/update_store_permissions/";           
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);*/

            redirect('storesmanager/confirm_update');
        }
        else
        {
            redirect('storesmanager/index');
        }     
    }
    
    public function add()
    {
        if (isset($this->session->userdata['admin']))
        {
            $this->load->view('store_header');
            $this->load->view('add-store');
            $this->load->view('footer'); 
        }
        else
        {
            redirect('storesmanager/index');
        } 
    }
    
    public function insert()
    {   
        if (isset($this->session->userdata['admin']))
        {      
            $data['store_id'] = $_POST['store_id'];
            $data['name'] = utf8_decode(ucwords(strtolower($_POST['name'])));
            $data['address'] = utf8_decode(ucwords(strtolower($_POST['address'])));
            $data['city'] = utf8_decode(ucfirst(strtolower($_POST['city'])));
            $data['postal_code'] = strtoupper(format_postalcode($_POST['postal_code1'],$_POST['postal_code2']));
            $data['province'] = utf8_decode($_POST['province']);
            $data['phone'] = format_phone($_POST['phone1'],$_POST['phone2'],$_POST['phone3']);
            $data['fax'] = format_phone($_POST['fax1'],$_POST['fax2'],$_POST['fax3']);
            $data['manager_or_owner'] = utf8_decode(ucwords(strtolower($_POST['manager_name'])));
            $data['cart_active'] = $_POST['checkbox_cart_active'];
            
            $res = $this->stores_model->add_store($data);
            
            /*$url = ADMIN."stores/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);    */        
            

            redirect('storesmanager/confirm_insert');
        }
        else
        {
            redirect('storesmanager/index');
        }
    }
    
    public function delete($stores)
    {
        if (isAjax())
        {
            $stores_ids = explode(',',$stores);
            foreach($stores_ids as $store_ids)
            {
                $this->stores_model->delete_store($store_ids);
            }
            
            $url = ADMIN."stores/delete/";
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
        $data['lang'] = $this->session->userdata['lang'];
        $this->load->view('store_header',$data);
        $this->load->view('update-confirm2');
        $this->load->view('footer'); 
    }
    
    public function confirm_insert()
    {
        $data['lang'] = $this->session->userdata['lang'];
        $this->load->view('store_header',$data);
        $this->load->view('add-confirm2');
        $this->load->view('footer'); 
    }
    
    public function change_language()
    {
        $this->session->set_userdata[array('lang' => 'en')];   
        if ($_POST['lang'] == 'fr') $this->session->set_userdata[array('lang' => 'fr')];        

        redirect($_POST['redirect_url']);   
    }    
    
    public function get_store_permissions($id)
    {
        $store_permissions = $this->permissions_model->get_store_permissions($id);   
        $supervisers = array();    
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
            $this->permissions_model->update_store_permissions($data);
        }
    }
}
