<?php

class products_controller
{
    private $products;
    function __construct() 
    {        
        $this->products = load::model('products');  
        load::library('decrypt');  
    }    
    
    public function products($cat_id)
    {        
        if (is_logged(session::get('user')))
        {            
            $products = $this->products->get_products_by_category($cat_id,session::get('lang'),session::get('store_type'));
            $product_list = array();
            foreach($products as $product)
            {
                $product_list[] = $this->products->get_products_by_id($product->id);
            }    
            load::view('header');
            load::view('products',array('products_list' => $product_list, 'store_id' => session::get('user')));
            load::view('footer');    
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }   
    
    public function product($id)
    {        
        if (is_logged(session::get('user')))
        {            
            load::view('header');
            load::view('product',array('product' => $this->get_product($id), 'store_id' => session::get('user')));
            load::view('footer');    
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }   
    
    public function get_product_price($id, $quantity)
    {
        if (isAjax())
        {  
            $product = $this->products->get_product_price($id, $quantity);
            $product = get_object_vars($product[0]);
            echo $product[$quantity];
        }
    }
    
    public function get_product_shipping($id, $order_quantity)
    {
        if (isAjax())
        {  
            $rows = get_quantities();
            
            $dim = $this->products->get_product_dimensions($id);
            $temp1 = array('"','Fermé','Ouvert','Open','Closed', ',');
            $temp2 = array('','','','','','.');
            $dimensions = explode('x',str_replace($temp1,$temp2,$dim[0]->dimension));
            define('CP_SERVER', 'sellonline.canadapost.ca');
            define('CP_PORT', 30000);
            define('MERCHANT_CPCID', 'CPC_GROUPE_VSRG');
            //define('MERCHANT_CPCID', 'CPC_ERLIK');

            load::library('minixml/minixml.inc');
            load::library('canadapost');
            $cp = new CanadaPost(session::get('lang'));
            $cp->addItem($quantity = 1, $weight = intval($rows[$order_quantity])/100, $length = 1, $width = floatval($dimensions[0]), $height = floatval($dimensions[1]), $description = 'Test');
            $cp->getQuote('Montréal', 'Québec', 'Canada', 'H1P 2X8');

            if ($cp->error_message) {
                echo json_encode(array('error',$cp->error_message));
            }
            else
            {
                $shipping_methods = $cp->shipping_methods;
                if (session::get('lang') == 'fr') $shipping_methods[1]['name'] = "Colis accélérés ";
                echo json_encode($shipping_methods);      
            }
        }      
    }
    
    private function get_products($cat_id,$lang)
    {
        return $this->products->get_products_by_category($cat_id,$lang);
    }    
    
    private function get_product($id)
    {
        return $this->products->get_products_by_id($id);
    }   
     
    public function get_product_infos($id)
    {
        if (isAjax())
        { 
            $data = $this->products->get_products_by_id($id);
            $infos[] = $data[0]->id;
            $infos[] = utf8_encode($data[0]->name);
            $infos[] = utf8_encode($data[0]->description);
            $infos[] = utf8_encode($data[0]->dimension);
            echo json_encode($infos);
        }
    }    
    
    public function add()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring($array, $array2))
        {
            $res = $this->products->add_product($_POST);            
        }        
    }   
    
    public function update()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring($array, $array2))
        {
            $this->stores->update_product($_POST['id'],$_POST);
        }
    }   
    
    public function delete()
    {        
        $array2 = class_decrypt::keycalc('58hdlDMwol1hhWqAdtap');
        $array = class_decrypt::stringtoarray($_POST['post_key']);
        if ('4kbTOdrqyysumEu7q0nBTkmjuzfkey' == decrypt::transformstring($array, $array2))
        {
            foreach($_POST['stores_ids'] as $store_id)
            {
                $this->stores->delete_product($store_id);
            }
        }
    }    
}
