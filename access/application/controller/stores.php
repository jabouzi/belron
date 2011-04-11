<?php

class stores_controller
{
    private $stores;
    function __construct() 
    {        
        $this->stores = load::model('stores');    
        load::library('decrypt');
    }    
    
    public function add()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            $res = $this->stores->add_store($_POST);            
        }
        else
        {
            echo 'Error';
        }
    }   
    
    public function update()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            $this->stores->update_store($_POST['id'],$_POST);
        }
    }   
    
    public function delete()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            foreach($_POST['stores_ids'] as $store_id)
            {
                $this->stores->delete_store($store_id);
            }
        }
    }    
}
