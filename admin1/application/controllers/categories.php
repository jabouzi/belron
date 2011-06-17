<?php

class Categories extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();        
        $this->load->model('category_model');  
        $this->load->library('ipinfodb');      
    }    
    
    function index()
    {        
        if (isset($this->session->userdata['user']))
        {           
            $userLocation = $this->get_user_location();                   
            $lang =  $this->session->userdata['lang'];            
            
            if ($this->session->userdata['lang'] == 'fr')
            {
                $data['store_type'] = 'Lebeau';
            }
            else
            {
                $data['store_type'] = 'Speedy';
            }            
            
            $categories = $this->category_model->get_all();
            foreach($categories as $category)
            {
                $cats[$category->id]['fr'] = $category->name_fr;
                $cats[$category->id]['en'] = $category->name_en;
            }
            $data_view['categories'] = $cats;             
 
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];
            $data['lang'] = $this->session->userdata['lang'];
            $data_view['lang'] = $this->session->userdata['lang'];                                  
            if ($this->session->userdata['user_type'] == 3)
            {
                $this->load->view('header',$data);
            }
            else
            {
                $this->load->view('admin-header',$data);
            }
            $this->load->view('categories',$data_view);
            $this->load->view('footer');    
        }
        else
        {
            redirect('login/storelogin');
        }
    }   
    
    private function get_categories($lang)
    {
        return $this->category->get_categories($lang);
    }
    
    private function get_user_location()
    {
        if(!@$_COOKIE["geolocation"]){
            $ipinfodb = new ipinfodb;
            $ipinfodb->setKey('9e399207f85522c328d415081c607f9f18563342bd38c4a014c95800b92fff8b');
         
            $visitorGeolocation = $ipinfodb->getGeoLocation($_SERVER['REMOTE_ADDR']);
            if ($visitorGeolocation['Status'] == 'OK') {
                $data = base64_encode(serialize($visitorGeolocation));
                setcookie("geolocation", @$data, time()+3600*24); 
            }
        }else{
            $visitorGeolocation = unserialize(base64_decode($_COOKIE["geolocation"]));
        }        
        
        return $visitorGeolocation;
    }    
}
