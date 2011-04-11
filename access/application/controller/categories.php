<?php

class categories_controller
{
    private $categories;
    function __construct() 
    {        
        $this->categories = load::model('categories');  
        load::library('ipinfodb');      
    }    
    
    public function index()
    {        
        if ($this->is_logged(session::get('user')))
        {           
            $userLocation = $this->get_user_location();                   
            $lang =  session::get('lang');
            if (!$lang) 
            {
                session::set('lang','en');
                $userLocation = $this->get_user_location();                
                if ($userLocation['RegionName'] == 'Quebec')
                {
                    session::set('lang','fr');
                }
            }
            $categories = $this->get_categories(session::get('lang'));
            load::view('header');
            load::view('categories',array('categories' => $categories));
            load::view('footer');    
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }   
    
    private function get_categories($lang)
    {
        return $this->categories->get_categories($lang);
    }    
    
    function is_logged($user)
    {        
        return ($user != false);
    }
    
    private function get_user_location()
    {
        if(!$_COOKIE["geolocation"]){
            $ipinfodb = new ipinfodb;
            $ipinfodb->setKey('9e399207f85522c328d415081c607f9f18563342bd38c4a014c95800b92fff8b');
         
            $visitorGeolocation = $ipinfodb->getGeoLocation($_SERVER['REMOTE_ADDR']);
            if ($visitorGeolocation['Status'] == 'OK') {
                $data = base64_encode(serialize($visitorGeolocation));
                setcookie("geolocation", $data, time()+3600*24); 
            }
        }else{
            $visitorGeolocation = unserialize(base64_decode($_COOKIE["geolocation"]));
        }
         
        return $visitorGeolocation;
    }    
}
