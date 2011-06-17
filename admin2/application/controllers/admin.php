<?php

class Admin extends CI_Controller
{    

    function __construct() 
    {
        parent::__construct();    
        $this->load->model('users_model');           
    }    

    
    function index()
    {        
        if (isset($this->session->userdata['admin']))
        {            
            $data['admin'] = 1;   
            $data['admin'] = 1;                       

            $data['lang'] = $this->session->userdata['lang'];
            $data['store_type'] = 'Belron';
            $this->load->view('header',$data);
            $this->load->view('page-index');
            $this->load->view('footer');
        }
        else
        {
            header('location: '.ADMIN);
        }
    }    
    
    public function login($uname,$pwd)
    {
        if (isAjax())
        {
            if ($uname == 'mmayrand@belroncanada.com' && $pwd == 'mayrand')
            {
                if (!isset($this->session->userdata['admin']))
                {
                    $this->session->set_userdata(array('lang' => 'fr'));
                    $this->session->set_userdata(array('admin' => 'admin'));
                    $user = $this->users_model->get_name($uname);
                    $this->session->set_userdata('name',$user[0]->first_name.' '.$user[0]->family_name);  
                }
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
        $this->session->unset_userdata('admin');  
        $this->session->unset_userdata('lang');  
        $this->session->unset_userdata('name');  
        header('location: '.ADMIN);
    }
    
    function change_language()
    {
        $this->session->set_userdata(array('lang' => 'en'));   
        if ($_POST['lang'] == 'fr') $this->session->set_userdata(array('lang' => 'fr'));        
        redirect($_POST['redirect_url']);   
    }    
}
