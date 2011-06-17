<?php

class Login extends CI_Controller
{   
    function __construct() 
    {
        parent::__construct();        
        $this->load->model('stores_model');       
        $this->load->model('users_model');  
        $this->load->model('wishlist_model');  
        $this->load->library('ipinfodb');       
    }    
    
    function index()
    {        
        if (!isset($this->session->userdata['lang']))
        {
            $this->session->set_userdata(array('lang' =>'en'));
            $userLocation = $this->get_user_location();
            if ($userLocation['RegionName'] == 'Quebec')
            {
                $this->session->set_userdata(array('lang' =>'fr'));
            }
        }
        
        if (isset($this->session->userdata['user']))
        {            
            if ($this->session->userdata['user_type'] == 3)
            {                
                redirect('categories');
            }            
            else
            {
                redirect('admin');
            }
        }
        else
        {
            redirect('login/loginindex');
        }
    }
    
    function loginindex()
    {
        if (!isset($this->session->userdata['lang']))
        {
            $this->session->set_userdata(array('lang' =>'en'));
            $userLocation = $this->get_user_location();
            if ($userLocation['RegionName'] == 'Quebec')
            {
                $this->session->set_userdata(array('lang' =>'fr'));
            }
        }
        
        $data['admin'] = 1;   
        $data['user'] = 0;   
                    
        $data['lang'] = $this->session->userdata['lang'];        
        $data['store_type'] = 'Belron';               

        $this->load->view('admin-header',$data);
        $this->load->view('admin_index');
        $this->load->view('footer');
    }
    
    function storelogin()
    { 
        if (!isset($this->session->userdata['lang']))
        {
            $this->session->set_userdata(array('lang' =>'en'));
            $userLocation = $this->get_user_location();
            if ($userLocation['RegionName'] == 'Quebec')
            {
                $this->session->set_userdata(array('lang' =>'fr'));
            }
        }
        
        $data['admin'] = 0;   
        $data['user'] = 0;   
                    
        if ($this->session->userdata['lang'] =='fr')
        {
            $data['store_type'] = 'Lebeau';
        }
        else
        {
            $data['store_type'] = 'Speedy';
        }       
        
        $data['lang'] = $this->session->userdata['lang'];        
        $data_view['stores'] = $this->get_stores_list();
        $this->load->view('header',$data);
        $this->load->view('storelogin',$data_view);
        $this->load->view('footer');
    }
    
    function userlogin($id = '')
    { 
        if (!isset($this->session->userdata['lang']))
        {
            $this->session->set_userdata('lang','en');
            $userLocation = $this->get_user_location();
            if ($userLocation['RegionName'] == 'Quebec')
            {
                $this->session->set_userdata(array('lang' =>'fr'));
            }
        }
        
        $data['admin'] = 0;   
        $data['user'] = 0;                     
          
        $data['store_type'] = 'Belron';
        $data['usertype'] = 0;
        $data['lang'] = $this->session->userdata['lang'];
        $data_view['stores'] = $this->get_stores_list();
        $this->load->view('admin-header',$data);
        $this->load->view('userlogin',array('id' => $id));
        $this->load->view('footer');
    }
    
    function storeloginaction()
    {        
        $errors_lang1 = array('fr' => 'Il faut choisir un magasin', 'en' => 'You must select a store');
        $errors_lang2 = array('fr' => 'Il faut taper le mot de passe', 'en' => 'You must type your password');
        $errors_lang3 = array('fr' => 'Le mot de passe est invalide', 'en' => 'Your password is invalid');
        if ($_POST['store_name'] == '0') $this->errors[] = $errors_lang1[$this->session->userdata['lang']];
        if ($_POST['password'] == '') $this->errors[] = $errors_lang2[$this->session->userdata['lang']];
        if (!$this->store_login_valid(strtolower($_POST['store_name']),strtolower($_POST['password']))) $this->errors[1] = $errors_lang3[$this->session->userdata['lang']];
        if (empty($this->errors))
        {            
            if ($_POST['store_name'] == 'Speedy Glass')
            {
                $data = array('lang' => 'en', 'store_type' => 'Speedy');
                $this->session->set_userdata($data);
            }
            else
            {
                $data = array('lang' => 'fr', 'store_type' => 'Lebeau');
                $this->session->set_userdata($data);
            }
            $this->session->set_userdata('user_type',3);
            $this->session->set_userdata('user',$_POST['password']);
            $this->session->set_userdata('wishlist',$this->has_wishlist());
            $store = $this->stores_model->get_name($_POST['password']);
            $this->session->set_userdata('name',$store[0]->name.' ('.$store[0]->address.' '.$store[0]->city.')');
            redirect('categories');
        }
        else
        {
            $data['admin'] = 0;   
            $data['user'] = 0;   
                        
            if ($this->session->userdata['lang'] =='fr')
            {
                $data['store_type'] = 'Lebeau';
            }
            else
            {
                $data['store_type'] = 'Speedy';
            }       
            
            $data['lang'] = $this->session->userdata['lang'];        
            $data_view['stores'] = $this->get_stores_list();
            $this->load->view('header',$data);
            $this->load->view('storelogin',array('stores'=>$this->get_stores_list(),'store_selected'=>$_POST['store_name'],'errors'=>$this->errors));
            $this->load->view('footer');
        }
    }
    
    function userloginaction($id = '')
    {        
        $errors_lang1 = array('fr' => 'Il faut taper le nom d\'usager', 'en' => 'You must type your username');
        $errors_lang2 = array('fr' => 'Il faut taper le mot de passe', 'en' => 'You must type your password');
        $errors_lang3 = array('fr' => 'Le mot de passe est invalide', 'en' => 'Your password is invalid');
        if ($_POST['username'] == '') $this->errors[] = $errors_lang1[$this->session->userdata['lang']];
        if ($_POST['password'] == '') $this->errors[] = $errors_lang2[$this->session->userdata['lang']];
        if (!$this->user_login_valid(strtolower($_POST['username']),strtolower($_POST['password']))) $this->errors[1] = $errors_lang3[$this->session->userdata['lang']];
        if (empty($this->errors))
        {            
            $province = $this->users_model->get_province($_POST['username']);             
            $data['user_type'] = $this->get_user_type($_POST['username']);
            $data['user'] = $_POST['username'];
            $data['user_id'] = $this->users_model->get_id($_POST['username']);
            $data['wishlist'] = $this->has_wishlist();       
            $user = $this->users_model->get_name($_POST['username']);
            $this->session->set_userdata('name',$user[0]->first_name.' '.$user[0]->family_name);     
            $this->session->set_userdata($data);            
            redirect('admin');
        }
        else
        {            
            $data['admin'] = 0;   
            $data['user'] = 0;                     
              
            $data['store_type'] = 'Belron';
            $data['usertype'] = 0;
            $data['lang'] = $this->session->userdata['lang'];
            $data_view['stores'] = $this->get_stores_list();
            $this->load->view('admin-header',$data);
            $this->load->view('userlogin',array('errors'=>$this->errors));
            $this->load->view('footer');            
        }
    }
    
    function logout()
    {        
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_type');
        $this->session->unset_userdata('wishlist');
        $this->session->unset_userdata('store_type');        
        $this->session->unset_userdata('name');        
        redirect('login/loginindex');        
    }
    
    private function store_login_valid($username,$password)
    {
        return $this->stores_model->check_login($username,$password);             
    }
    
    private function get_user_type($email)
    {
        $data = $this->users_model->get_type($email);
        return $data[0]->type;
    }
    
    private function user_login_valid($username,$password)
    {
        $user = $this->users_model->check_login($username,$password);
        return $user;        
    }
    
    private function get_stores_list()
    {
        $stores_list = $this->stores_model->get_distinct();  
        return $stores_list;
    }
    
    private function has_wishlist()
    {        
        $store_id = $this->session->userdata['user'];
        if (!$this->wishlist_model->get($store_id)) return false;
        return true;
    }
    
    private function get_user_location()
    {
        if(!@$_COOKIE["geolocation"]){
            $ipinfodb = new ipinfodb;
            $ipinfodb->setKey('9e399207f85522c328d415081c607f9f18563342bd38c4a014c95800b92fff8b');
         
            $visitorGeolocation = $ipinfodb->getGeoLocation($_SERVER['REMOTE_ADDR']);
            if ($visitorGeolocation['Status'] == 'OK') {
                base64_encode(serialize($visitorGeolocation));
                setcookie("geolocation", @$data, time()+3600*24*7); 
            }
        }else{
            $visitorGeolocation = unserialize(base64_decode($_COOKIE["geolocation"]));
        }
         
        return $visitorGeolocation;
    }   

}
