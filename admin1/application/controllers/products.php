<?php

class Products extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();        
        $this->load->model('products_model');  
        $this->load->library('class_decrypt');  
        @$this->load->library('canadapost');  
        @$this->load->helper('minixml/minixml.inc');          
    }    
    
    function lists($cat_id)
    {        
        if (isset($this->session->userdata['user']))
        {                                
            if ($this->session->userdata['lang'] == 'fr')
            {                
                $data['store_type'] = 'Lebeau';
            }
            else
            {
                $data['store_type'] = 'Speedy';
            }
            
            $products = $this->products_model->get_products_by_category($cat_id,$this->session->userdata['lang'],$data['store_type']);      
            
            $data['admin'] = 0;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];            
            $data['lang'] = $this->session->userdata['lang'];
            $data_view['lang'] = $this->session->userdata['lang'];
            $data_view['products_list'] = $products;
            $data_view['store_id'] = $this->session->userdata['user'];
            $data_view['wishlist'] = array();
            if (isset($this->session->userdata['wishlist'])) $data_view['wishlist'] = $this->session->userdata['wishlist'];   
            if ($this->session->userdata['user_type'] == 3)
            {
                $this->load->view('header',$data);
            }
            else
            {
                $this->load->view('admin-header',$data);
            }
            $this->load->view('products',$data_view);
            $this->load->view('footer');    
        }
        else
        {
            redirect('login/storelogin');
        }
    }   
    
    function product($id)
    {        
        if (isset($this->session->userdata['user']))
        {            
            $this->load->view('header');
            $this->load->view('product',array('product' => $this->get_product($id), 'store_id' => $this->session->userdata['user']));
            $this->load->view('footer');    
        }
        else
        {
            redirect('login/storelogin');
        }
    }   
    
    function get_product_price($id, $quantity)
    {
        if (isAjax())
        {  
            $product = $this->products_model->get_product_price($id, $quantity);
            $product = get_object_vars($product[0]);
            echo $product[$quantity];
        }
    }
    
    function get_product_shipping($id, $order_quantity)
    {
        if (isAjax())
        {  
            $rows = get_quantities();
            
            $dim = $this->products_model->get_product_dimensions($id);
            $temp1 = array('"','Fermé','Ouvert','Open','Closed', ',');
            $temp2 = array('','','','','','.');
            $dimensions = explode('x',str_replace($temp1,$temp2,$dim[0]->dimension));
            define('CP_SERVER', 'sellonline.canadapost.ca');
            define('CP_PORT', 30000);
            define('MERCHANT_CPCID', 'CPC_GROUPE_VSRG');
            //define('MERCHANT_CPCID', 'CPC_ERLIK');
            $cp = new CanadaPost($this->session->userdata['lang']);
            $cp->addItem($quantity = 1, $weight = intval($rows[$order_quantity])/100, $length = 1, $width = floatval($dimensions[0]), $height = floatval($dimensions[1]), $description = 'Test');
            $cp->getQuote('Montréal', 'Québec', 'Canada', 'H1P 2X8');

            if ($cp->error_message) {
                echo json_encode(array('error',$cp->error_message));
            }
            else
            {
                $shipping_methods = $cp->shipping_methods;
                if ($this->session->userdata['lang'] == 'fr') $shipping_methods[1]['name'] = "Colis accélérés ";
                echo json_encode($shipping_methods);      
            }
        }      
    }
    
    private function get_products($cat_id,$lang)
    {
        return $this->products_model->get_products_by_category($cat_id,$lang);
    }    
    
    private function get_product($id)
    {
        return $this->products_model->get_products_by_id($id);
    }   
     
    function get_product_infos($id)
    {
        if (isAjax())
        { 
            $data = $this->products_model->get_products_by_id($id);
            $infos[] = $data[0]->id;
            $infos[] = $data[0]->name;
            $infos[] = $data[0]->description;
            $infos[] = $data[0]->dimension;
            echo json_encode($infos);
        }
    }        
}
