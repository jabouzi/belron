<?php

class login_controller
{
    private $stores;
    private $user;
    private $errors;
    private $user_type;
    
    function __construct() 
    {        
        $this->stores = load::model('stores');       
        $this->users = load::model('users');  
        load::library('ipinfodb');       
    }    
    
    public function index()
    {        
        session::set('lang','en');
        $userLocation = $this->get_user_location();
        if ($userLocation['RegionName'] == 'Quebec')
        {
            session::set('lang','fr');
        }
        
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
                url::redirect('admin');
            }
        }
        else
        {
            url::redirect('login/adminlogin');
        }
    }
    
    public function adminlogin()
    {
        load::view('header',array('admin' => 1));
        load::view('admin_index');
        load::view('footer');
    }
    
    public function storelogin()
    { 
        $this->errors = array();
        load::view('header',array('admin' => 0));
        load::view('storelogin',array('stores'=>$this->get_stores_list()));
        load::view('footer');
    }
    
    public function userlogin($id = '')
    { 
        $this->errors = array();
        load::view('header',array('admin' => 0));
        load::view('userlogin',array('id' => $id));
        load::view('footer');
    }
    
    public function storeloginaction()
    {        
        $errors_lang1 = array('fr' => 'Il faut choisir un magasin', 'en' => 'You must select a store');
        $errors_lang2 = array('fr' => 'Il faut taper le mot de passe', 'en' => 'You must type your password');
        $errors_lang3 = array('fr' => 'Le mot de passe est invalide', 'en' => 'Your password is invalid');
        if (input::post('store_name') == '0') $this->errors[] = $errors_lang1[session::get('lang')];
        if (input::post('password') == '') $this->errors[] = $errors_lang2[session::get('lang')];
        if (!$this->store_login_valid(strtolower(input::post('store_name')),strtolower(input::post('password')))) $this->errors[1] = $errors_lang3[session::get('lang')];
        if (empty($this->errors))
        {
            session::set('lang','fr');
            session::set('store_type','Lebeau');
            if (input::post('store_name') == 'Speedy Glass')
            {
                session::set('lang','en');
                session::set('store_type','Speedy');
            }
            session::set('user_type',3);
            session::set('user',input::post('password'));
            session::set('wishlist',$this->has_wishlist());
            url::redirect('categories');
        }
        else
        {
            load::view('header');
            load::view('storelogin',array('stores'=>$this->get_stores_list(),'store_selected'=>input::post('store_name'),'errors'=>$this->errors));
            load::view('footer');
        }
    }
    
    public function userloginaction($id = '')
    {        
        $errors_lang1 = array('fr' => 'Il faut taper le nom d\'usager', 'en' => 'You must type your username');
        $errors_lang2 = array('fr' => 'Il faut taper le mot de passe', 'en' => 'You must type your password');
        $errors_lang3 = array('fr' => 'Le mot de passe est invalide', 'en' => 'Your password is invalid');
        if (input::post('username') == '') $this->errors[] = $errors_lang1[session::get('lang')];
        if (input::post('password') == '') $this->errors[] = $errors_lang2[session::get('lang')];
        if (!$this->user_login_valid(strtolower(input::post('username')),strtolower(input::post('password')))) $this->errors[1] = $errors_lang3[session::get('lang')];
        if (empty($this->errors))
        {            
            session::set('user_type',$this->get_user_type());
            session::set('user',input::post('username'));            
            session::set('user_id',$this->users->get_id(input::post('username'))); 
            $province = $this->users->get_province(input::post('username'));
            session::set('wishlist',$this->has_wishlist());
            
            session::set('lang','fr');
            session::set('store_type','Lebeau');
            if ($province[0]->province != 'QC')
            {
                session::set('lang','en');
                session::set('store_type','Speedy');
            }           

            if ($this->get_user_type() == 2)
            {                
                if ($id != '') url::redirect('orders/lists/'.$id);
                else url::redirect('orders/lists/0');                
            }
            else
            {
                url::redirect('admin');
            }
        }
        else
        {            
            load::view('header');
            load::view('userlogin',array('errors'=>$this->errors));
            load::view('footer');            
        }
    }
    
    public function logout()
    {
        session::delete('user');
        session::delete('user_id');
        session::delete('user_type');
        session::delete('wishlist');
        session::delete('store_type');
        url::redirect('login');
    }
    
    private function store_login_valid($username,$password)
    {
        return $this->stores->check_login($username,$password);             
    }
    
    private function get_user_type()
    {
        return $this->user_type;
    }
    
    private function user_login_valid($username,$password)
    {
        $user = $this->users->check_login($username,$password);
        if ($user['valid']) $this->user_type = $user['type'][0]->type;
        return $user['valid'];        
    }
    
    private function get_stores_list()
    {
        $stores_list = $this->stores->get_distinct();  
        return $stores_list;
    }
    
    private function has_wishlist()
    {
        $wishlist = load::model('wishlist');  
        $store_id = session::get('user');
        if (!$wishlist->get($store_id)) return false;
        return true;
    }
    
    private function get_user_location()
    {
        if(!$_COOKIE["geolocation"]){
            $ipinfodb = new ipinfodb;
            $ipinfodb->setKey('9e399207f85522c328d415081c607f9f18563342bd38c4a014c95800b92fff8b');
         
            $visitorGeolocation = $ipinfodb->getGeoLocation($_SERVER['REMOTE_ADDR']);
            if ($visitorGeolocation['Status'] == 'OK') {
                $data = base64_encode(serialize($visitorGeolocation));
                setcookie("geolocation", $data, time()+3600*24*7); 
            }
        }else{
            $visitorGeolocation = unserialize(base64_decode($_COOKIE["geolocation"]));
        }
         
        return $visitorGeolocation;
    }    
}
