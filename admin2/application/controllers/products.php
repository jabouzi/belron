<?php


class Products extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();         
        $this->load->model('products_model');    
        $this->load->library('class_decrypt');
    }   
    
    public function add()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            $this->products_model->add_product($_POST);            
        }        
    }   
      
    
    public function update()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            $this->products_model->update_product($_POST['id'],$_POST);
        }
    }   
    
    public function delete()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == class_decrypt::transformstring($array, $array2))
        {
            foreach($_POST['products_ids'] as $product_id)
            {
                $this->products_model->delete_product($product_id);
            }
        }
    }    
}
