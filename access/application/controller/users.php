<?php

class users_controller
{
    private $users;
    function __construct() 
    {        
        $this->users = load::model('users');    
        load::library('decrypt');
    }    
    
    public function add()
    {        
        $array2 = class_decrypt::keycalc1('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray1($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring1($array, $array2))
        {
            $res = $this->users->add_user($_POST);            
        }
        else
        {
            echo 'Error';
        }
    }   
    
    public function update()
    {        
        $array2 = class_decrypt::keycalc1('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray1($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring1($array, $array2))
        {
            $this->users->update_user($_POST['id'],$_POST);
        }
    }   
    
    public function delete()
    {        
        $array2 = class_decrypt::keycalc1('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray1($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring1($array, $array2))
        {
            foreach($_POST['users_ids'] as $user_id)
            {
                $this->users->delete_user($user_id);
            }
        }
    }   
    
    
}
