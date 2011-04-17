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
            $user = load::model('users');  
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
                
                $stores = array();
                
                if (session::get('user_type') < 3)
                {
                    $stores = $this->get_stores_supevised($user->get_id(session::get('user')));
                }                

                load::view('header');
                load::view('confirm',array('wish_list' => $wish_list, 'prods_id' => $prods_id, 'store' => $store->get(session::get('user')), 'rows' => $rows, 'stores' => $stores));
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
            
            if (!empty($saved_wish_list))
            {
                if (session::get('user_type') == 3)
                {
                    $stores[] = session::get('user');
                    $approved = '';
                    $date_approval = '0000-00-00 00:00:00';
                    if (!$this->has_store_superviser())
                    {
                        $approved = 0;
                        $date_approval = date('Y-m-d H:i:s');
                    }
                    $order_id[] = $order->insert(serialize($saved_wish_list), '', session::get('user'), $approved, input::post('hidden_total'), $date_approval);
                    if (!empty($saved_wish_list))
                    {
                        $address = array('address' => input::post('address'), 
                                        'city' => input::post('city'), 
                                        'postal_code' => input::post('postal_code'), 
                                        'province' => input::post('province'), 
                                        'phone' => input::post('phone'),
                                        'fax' => input::post('fax'));
                        
                        $store->update_address(session::get('user'),$address);                
                    }
                }
                else
                {
                    $stores = input::post('store-orders');
                    $approved = '';
                    $date_approval = '0000-00-00 00:00:00';  
                    $approved_by = '';

                    if (!$this->has_user_superviser())
                    {                                           
                        $approved = '1';
                        $date_approval = date('Y-m-d H:i:s');   
                        $approved_by = session::get('user'); 
                    }                     
                           
                    foreach($stores as $store)
                    {
                        $order_id[] = $order->insert(serialize($saved_wish_list), $approved_by, $store, $approved, input::post('hidden_total'), $date_approval);
                    }
                    
                }
            }           
            
            session::delete('wishlist');
            $this->wishlist->delete(session::get('user'));            
        
            session::set('stores_ids',serialize($stores));
            session::set('orders_ids',serialize($order_id));
                        
            url::redirect('wishlist/pos/'.$order_id[0]);
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
    
    public function remove_item_by_value($array, $val) 
    {
        if (empty($array) || !is_array($array)) return array();
        if (!in_array($val, $array)) return $array;

        foreach($array as $key => $value) {
            if ($value == $val) unset($array[$key]);
        }

        return $array;
    }    
    
    public function pos($id)
    {
        if (is_logged(session::get('user')))
        {
            $orders = load::model('orders');
            $products = load::model('products');
            $order = $orders->get($id);  
            
            $order_list = unserialize($order[0]->wish_list);
            
            $items = $order_list['items'];
            $prices = $order_list['quantity'];
            $shipping = $order_list['shipping'][$order_list['shipping'][0]];      
            foreach($items as $key => $item)
            {
                $prod = $products->get_products_by_id($item);         
                $price = $products->get_product_price($item, $prices[$item]); 
                $price = get_object_vars($price[0]);  
                
                $products_list[] = array($prod[0]->name,$prod[0]->description,$price[$prices[$item]]);
            }
            
            $stores_ids = unserialize(session::get('stores_ids'));
			$orders_ids = unserialize(session::get('orders_ids'));			
                          
            $store_id = $order[0]->store_id;
            $total_cost = $order[0]->total_cost;
           
            load::view('header');
            load::view('order_pos',array('id' => $id, 'products_list' => $products_list,'shipping' => $shipping, 
						'store_id' => $store_id, 'total_cost' => $total_cost, 'stores_ids' => $stores_ids, 'orders_ids' => $orders_ids,
                        'sups1' => $this->get_user_superviser(), 'sups2' => $this->get_store_superviser($stores_ids) ));
            load::view('footer');
        }
        else
        {
            url::redirect('login/storelogin');
        }       
    }
    
    public function approve_pos($id)
    {
        if (is_logged(session::get('user')))
        {
            session::delete('stores_ids');
			session::delete('orders_ids');
            $orders = load::model('orders');
            $status = load::model('status');
            $pos = input::post('pos');
            
            $has_superviser = false;
            if (session::get('user_type') == 3)
            {
                if ($this->has_store_superviser()) $has_superviser = true;
            }
            else
            {
                if ($this->has_user_superviser()) $has_superviser = true;
            }
            
            if ($has_superviser)
            {
                
                 foreach($pos as $key => $item)
                 {
                    $orders->add_pos($key,mysql_escape_string($item));
                 }
            }
            else
            {
                foreach($pos as $key => $item)
				{
					$orders->approve_pos($key,mysql_escape_string($item));
                    $verif_code = md5(time());
                    $status->insert($key,0,$verif_code);
                    
                    $mailer = new phpmailer();
                    $mailer->IsSendmail();
                    $mailer->From = 'noreply@domain.com';
                    $mailer->FromName = 'Belron admin';
                    $mailer->Subject = utf8_decode('Nouvelle commande ajoutée');
                    $email_message = "Une nouvelle commande vient d'être ajoutée <br/> 
                        Pour confirmer la receprion :<a href='".url::base()."orders/recieved/".$key."/".$verif_code."/'>" .url::base()."orders/recieved/".$key."/".$verif_code."/ </a><br/>
                        Pour confirmer le traitement :<a href='".url::base()."orders/submit/".$key."/".$verif_code."/'>" .url::base()."orders/submit/".$key."/".$verif_code."/ </a><br/>
                        Pour confirmer l'envoie :<a href='".url::base()."orders/send/".$key."/".$verif_code."/'>" .url::base()."orders/send/".$key."/".$verif_code."/ </a>";
                    $mailer->MsgHTML($email_message);
                    $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
                    $mailer->Send();
				}
                
            }
            url::redirect('wishlist/confirmation/');
        }
        else
        {
            url::redirect('login/storelogin');
        }
    }
    
    private function has_store_superviser()
    {
        $permissions = load::model('permissions');
        return $permissions->get_store_supervisers(session::get('user'));
    }
    
    private function has_user_superviser()
    {
        $user = load::model('users');
        $permissions = load::model('permissions');       
        return $permissions->get_user_supervisers($user->get_id(session::get('user')));
    }
    
    private function get_store_superviser($stores_ids)
    {
        foreach($stores_ids as $store_id)
        {
            $permissions = load::model('permissions');
            $user = load::model('users');
            $sups = $permissions->get_store_supervisers_names($store_id);
            foreach($sups as $sup)
            {            
                $data = $user->get_user_infos($sup->superviser);
                $supervisers[$data[0]->id] = $data[0]->first_name .' ' . $data[0]->family_name;
            }
        }
        
        return $supervisers;
    }
    
    private function get_user_superviser()
    {
        $user = load::model('users');
        $permissions = load::model('permissions');       
        $sups = $permissions->get_user_supervisers_names($user->get_id(session::get('user')));
        foreach($sups as $sup)
        {            
            $data = $user->get_user_infos($sup->superviser);
            $supervisers[] = $data[0]->first_name .' ' . $data[0]->family_name;
        }
        
        return $supervisers;
    }
    
    public function get_stores_supevised($user_id)
    {
        if (session::get('user_type') == 2)
        {
            $permissions = load::model('permissions');
            $datas = $permissions->get_store_permissions($user_id);            
            foreach($datas as $data)
            {
                $stores[] = $data->store;
            }
        }
        else if (session::get('user_type') == 1)
        {
            $stores_data = load::model('stores');
            $datas = $stores_data->get_all();     
            foreach($datas as $data)
            {
                $stores[] = $data->store_id;
            }
        }
        return $stores;
    }
}
