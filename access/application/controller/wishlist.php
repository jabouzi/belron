<?php

class wishlist_controller
{
    private $wishlist;
    
    function __construct() 
    {        
        $this->wishlist = load::model('wishlist');     
        load::library('phpmailer');
    }    
    
    public function add($store_id, $product_id)
    {
        if (isAjax())
        {
            session::set('wishlist',true);
            $list = $this->wishlist->get(session::get('user'));
            if (empty($list))
            {
                $wish_list['items'][] = $product_id;
                $wish_list['quantity'][$product_id] = 1;
                $data = $this->wishlist->insert(serialize($wish_list), $store_id);
                echo 1;
            }
            else
            {
                $wish_list = unserialize($list[0]->wish_list);
                if (!in_array($product_id ,$wish_list['items']))
                {
                    $wish_list['items'][] = $product_id;
                    $wish_list['quantity'][$product_id] = 1;                   
                    $this->wishlist->update(serialize($wish_list), $store_id);   
                    echo 1;
                }
            }            
        }
        else echo 0; 
    }
    
    public function remove($store_id, $products)
    {
        if (isAjax())
        {            

            $products_ids = explode(',',$products);
            $list = $this->wishlist->get(session::get('user'));
            if (!empty($list))
            {
                $wish_list = unserialize($list[0]->wish_list);
                foreach($products_ids as $product_id)
                {
                    $wish_list['items'] = $this->remove_item_by_value($wish_list['items'],$product_id);            
                    unset($wish_list['quantity'][$product_id]);
                }
                if (count($wish_list['items']) == 0 and count($wish_list['quantity']) == 0)
                {                
                    session::delete('wishlist');
                    $this->wishlist->delete($store_id);  
                }
                else
                {
                   $this->wishlist->update(serialize($wish_list), $store_id);   
                }
                echo 1;
            }  
            else echo 0; 
        }
    }
    
    public function display($store_id)
    {
        $product = load::model('products');  
        if (isAjax())
        {
            $list = $this->wishlist->get(session::get('user'));
            if (!empty($list))
            {
                $lang = array('fr' => 'quantité', 'en' => 'quantity');
                $wish_list = unserialize($list[0]->wish_list);
                $result = '';
                foreach($wish_list['items'] as $item)
                {
                    $prod_data = $product->get_products_by_id($item);
                    foreach($prod_data as $data)
                    {
                        $result[] = array('id',$data->id,'description',utf8_encode($data->description),'dimension',utf8_encode($data->dimension),'name',utf8_encode($data->name),$lang[session::get('lang')],$wish_list['quantity'][$data->id]);
                    }
                }
                echo json_encode($result);
            }  
            else echo 0; 
        }
        else echo 0; 
    }
    
    public function confirm()
    {
        if (is_logged(session::get('user')))
        { 
            $product = load::model('products');  
            $store = load::model('stores');  
            $prods_id = input::post('product_id');
            $list = $this->wishlist->get(session::get('user'));
            
            $rows = get_quantities();
            
            if (!empty($list))
            {
                $wish_list = unserialize($list[0]->wish_list);       

                foreach($wish_list['items'] as $key => $item)
                {
                    $prod_data = $product->get_products_by_id($item);
                    $wish_list['items'][$key] = $prod_data;
                } 

                load::view('header');
                load::view('confirm',array('wish_list' => $wish_list, 'prods_id' => $prods_id, 'store' => $store->get(session::get('user')), 'rows' => $rows));
                load::view('footer');
            }     
        }  
        else
        {
            url::redirect('login/storelogin');
        }
    }
    
    public function order()
    {
        $store = load::model('stores');
        $order = load::model('orders');
        $user = load::model('users');
        if (is_logged(session::get('user')))
        { 
            $saved_wish_list = array();
            $producs_qtys = input::post('product_qty');
            $products_ids = input::post('product_id');            
            $products_removed = input::post('checkbox_supprim');
                        
            foreach($producs_qtys as $key => $product_qty)
            {
                if (!in_array($products_ids[$key],$products_removed))
                {
                    if ($product_qty != '') 
                    {                        
                        $saved_wish_list['items'][] = $products_ids[$key];
                        $saved_wish_list['quantity'][$products_ids[$key]] = $product_qty;
                        $saved_wish_list['shipping'] = array(input::post('radio_shipping'),'shipping0' => input::post('hidden_shipping0'),'shipping1' => input::post('hidden_shipping1'),'shipping2' => input::post('hidden_shipping2'), 'total' => input::post('hidden_total'));
                    }
                }
            }            

            $dm_id = $store->get_store_dm_id(session::get('user'));
            $dm_infos = $user->get_user_infos($dm_id[0]->dm_id);
            
            if (!empty($saved_wish_list))
            {
                $last_id = $order->insert(serialize($saved_wish_list), session::get('user'), $dm_id[0]->dm_id, input::post('hidden_total'));
            }
            
            session::delete('wishlist');
            $this->wishlist->delete(session::get('user'));
            
            if (!empty($saved_wish_list))
            {
                $address = array('address' => input::post('address'), 
                                'city' => input::post('city'), 
                                'postal_code' => input::post('postal_code'), 
                                'province' => input::post('province'), 
                                'phone' => input::post('phone'),
                                'fax' => input::post('fax'));
                
                $store->update_address(session::get('user'),$address);
                     
                $mailer = new phpmailer();
                $mailer->IsSendmail();
                $mailer->From = 'noreply@domain.com';
                $mailer->FromName = 'Name';
                $mailer->Subject = 'Order approved';
                $email_message = "<a href='".url::base()."login/userlogin/".$last_id."/'>Click ici </a>";
                $mailer->MsgHTML($email_message);
                $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
                //$mailer->AddAddress($dm_infos[0]->email, $dm_infos[0]->first_name.' '.$dm_infos[0]->family_name);
                $mailer->Send();
            }
            
            url::redirect('wishlist/confirmation/'.$dm_id[0]->dm_id);
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }
    
    public function confirmation($dm_id)
    {
        $user = load::model('users');
        $dm_infos = $user->get_user_infos($dm_id);
        load::view('header');
        load::view('store_confirmation',array('dm_infos' => $dm_infos));
        load::view('footer');
    }
    
    function remove_item_by_value($array, $val) 
    {
        if (empty($array) || !is_array($array)) return array();
        if (!in_array($val, $array)) return $array;

        foreach($array as $key => $value) {
            if ($value == $val) unset($array[$key]);
        }

        return $array;
    }    
}
