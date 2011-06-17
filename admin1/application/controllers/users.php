<?php

class Users extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();      
        $this->load->model('users_model');    
        $this->load->library('class_encrypt');
        $this->load->library('class_decrypt');
    }    
    
    function add()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring($array, $array2))
        {
            $res = $this->users_model->add_user($_POST);            
        }
        else
        {
            echo 'Error';
        }
    }   
    
    function update()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            $this->users_model->update_user($_POST['id'],$_POST);
        }
        
        $myFile = "log.log";
        $fh = fopen($myFile, 'w') or die("can't open file");
        fwrite($fh, print_r($_POST,true));
        fwrite($fh, class_decrypt::transformstring($array, $array2));
        fclose($fh);

    }   
    
    function delete()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            foreach($_POST['users_ids'] as $user_id)
            {
                $this->users_model->delete_user($user_id);
            }
        }
    }   
    
    
}
