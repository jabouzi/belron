<?php

class Stores extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();         
        $this->load->model('stores_model');    
        $this->load->library('class_decrypt');
    }    
    
    function add()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            $res = $this->stores_model->add_store($_POST);            
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
            $this->stores_model->update_store($_POST['id'],$_POST);
        }
    }   
    
    function delete()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            foreach($_POST['stores_ids'] as $store_id)
            {
                $this->stores_model->delete_store($store_id);
            }
        }
    }    
}
