<?php

class Wishlist extends CI_Controller
{   
    function __construct() 
    {
        parent::__construct();         
        $this->load->model('wishlist_model');    
        $this->load->model('products_model');  
        $this->load->model('stores_model');  
        $this->load->model('users_model');  
        $this->load->model('orders_model');  
        $this->load->model('status_model');  
        $this->load->model('permissions_model');  
        $this->load->library('phpmailer');
    }    
    
    function add($store_id, $product_id)
    {
        if (isAjax())
        {
            $this->session->set_userdata('wishlist',true);
            $list = $this->wishlist_model->get($this->session->userdata['user']);
            if (empty($list))
            {
                $wish_list['items'][] = $product_id;
                $wish_list['quantity'][$product_id] = 1;
                $data = $this->wishlist_model->insert(serialize($wish_list), $store_id);
                echo 1;
            }
            else
            {
                $wish_list = unserialize($list[0]->wish_list);
                if (!in_array($product_id ,$wish_list['items']))
                {
                    $wish_list['items'][] = $product_id;
                    $wish_list['quantity'][$product_id] = 1;                   
                    $this->wishlist_model->update(serialize($wish_list), $store_id);   
                    echo 1;
                }
            }            
        }
        else echo 0; 
    }
    
    function remove($store_id, $products)
    {
        if (isAjax())
        {            

            $products_ids = explode(',',$products);
            $list = $this->wishlist_model->get($this->session->userdata['user']);
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
                    $this->session->unset_userdata('wishlist');
                    $this->wishlist_model->delete($store_id);  
                }
                else
                {
                   $this->wishlist_model->update(serialize($wish_list), $store_id);   
                }
                echo 1;
            }  
            else echo 0; 
        }
    }
    
    function display($store_id)
    {         
        if (isAjax())
        {
            $list = $this->wishlist_model->get($this->session->userdata['user']);
            if (!empty($list))
            {                
                $lang = array('fr' => 'quantité', 'en' => 'quantity');
                $wish_list = unserialize($list[0]->wish_list);
                $result = '';
                foreach($wish_list['items'] as $item)
                {
                    $prod_data = $this->products_model->get_products_by_id($item);
                    foreach($prod_data as $data)
                    {
                        $result[] = array('id',$data->id,'description',$data->description,'dimension',$data->dimension,'name',$data->name,$lang[$this->session->userdata['lang']],$wish_list['quantity'][$data->id]);
                    }
                }
                echo json_encode($result);
            }  
            else echo 0; 
        }
        else echo 0; 
    }
    
    function confirm()
    {
        if (isset($this->session->userdata['user']))
        { 
            if (!isset($this->session->userdata['product_id']))
            {
                $prods_id = $_POST['product_id'];
                $this->session->set_userdata(array('product_id' => $prods_id));
            }
            else
            {
                $prods_id = $this->session->userdata['product_id'];                
            }
            
            $list = $this->wishlist_model->get($this->session->userdata['user']);
            
            $rows = get_quantities();
            
            if (!empty($list))
            {
                $wish_list = unserialize($list[0]->wish_list);       

                foreach($wish_list['items'] as $key => $item)
                {
                    $prod_data = $this->products_model->get_products_by_id($item);
                    $wish_list['items'][$key] = $prod_data;
                } 
                
                $stores = array();
                
                if ($this->session->userdata['user_type'] < 3)
                {
                    $stores = $this->get_stores_supevised($this->users_model->get_id($this->session->userdata['user']));
                }                
                
                if ($this->session->userdata['lang'] == 'fr')
                {
                    $data['lang'] = 'fr';
                    $data['store_type'] = 'Lebeau';
                    $data_view['lang'] = 'fr';
                }
                else
                {
                    $data['lang'] = 'en';
                    $data['store_type'] = 'Speedy';
                    $data_view['lang'] = 'en';
                }
                
                $data['admin'] = 0;   
                $data['user'] = 1;
                $data['usertype'] = $this->session->userdata['user_type'];
                
                $this->load->view('header',$data);
                $this->load->view('confirm',array('store_id' => $this->session->userdata['user'], 'wish_list' => $wish_list, 'prods_id' => $prods_id, 
                                    'store' => $this->stores_model->get($this->session->userdata['user']), 
                                    'rows' => $rows, 'stores' => $stores, 'user_type' => $this->session->userdata['user_type']));
                $this->load->view('footer');
            }     
        }  
        else
        {
            redirect('login/storelogin');
        }
    }
    
    function order()
    {
        if (isset($this->session->userdata['user']))
        { 
            $saved_wish_list = array();
            $products_removed = array();
            $producs_qtys = $_POST['product_qty'];
            $products_ids = $_POST['product_id'];     
            if (isset($_POST['checkbox_supprim'])) $products_removed = $_POST['checkbox_supprim'];
                        
            foreach($producs_qtys as $key => $product_qty)
            {
                if (!in_array($products_ids[$key],$products_removed))
                {
                    if ($product_qty != '') 
                    {                        
                        $saved_wish_list['items'][] = $products_ids[$key];
                        $saved_wish_list['quantity'][$products_ids[$key]] = $product_qty;
                        $saved_wish_list['shipping'] = array($_POST['radio_shipping'],'shipping0' => $_POST['hidden_shipping0'],'shipping1' => $_POST['hidden_shipping1'],'shipping2' => $_POST['hidden_shipping2'], 'total' => $_POST['hidden_total']);
                    }
                }
            }
            
            if (!empty($saved_wish_list))
            {
                if ($this->session->userdata['user_type'] == 3)
                {
                    $stores[] = $this->session->userdata['user'];
                    $approved = '';
                    $date_approval = '0000-00-00 00:00:00';
                    if (!$this->has_store_superviser())
                    {
                        $approved = 0;
                        $date_approval = date('Y-m-d H:i:s');
                    }
                    $order_id[] = $this->orders_model->insert(serialize($saved_wish_list), '', $this->session->userdata['user'], $approved, $_POST['hidden_total'], $date_approval);
                    if (!empty($saved_wish_list))
                    {
                        $address = array('address' => $_POST['address'], 
                                        'city' => $_POST['city'], 
                                        'postal_code' => $_POST['postal_code'], 
                                        'province' => $_POST['province'], 
                                        'phone' => $_POST['phone'],
                                        'fax' => $_POST['fax']);
                        
                        $this->stores_model->update_address($this->session->userdata['user'],$address);                
                    }
                }
                else
                {
                    $stores = $_POST['store-orders'];
                    $approved = '';
                    $date_approval = '0000-00-00 00:00:00';  
                    $approved_by = '';

                    if (!$this->has_user_superviser())
                    {                                           
                        $approved = '1';
                        $date_approval = date('Y-m-d H:i:s');   
                        $approved_by = $this->session->userdata['user']; 
                    }                     
                           
                    foreach($stores as $store)
                    {
                        $order_id[] = $this->orders_model->insert(serialize($saved_wish_list), $approved_by, $store, $approved, $_POST['hidden_total'], $date_approval);
                    }
                    
                }
            }           
            
            $this->session->unset_userdata('wishlist');
            $this->wishlist_model->delete($this->session->userdata['user']);            
        
            $this->session->set_userdata('stores_ids',serialize($stores));
            $this->session->set_userdata('orders_ids',serialize($order_id));
                        
            redirect('wishlist/pos/'.$order_id[0]);
        }
        else
        {
            redirect('login/storelogin');
        }
    }
    
    function confirmation()
    {        
        if (isset($this->session->userdata['orders_ids']))
        {
            if ($this->session->userdata['lang'] == 'fr')
            {                
                $data['store_type'] = 'Lebeau';  
            }
            else
            {
                $data['store_type'] = 'Speedy';  
            }
            
            $data['orders'] = unserialize($this->session->userdata['orders_ids']);
            
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('stores_ids');
            $this->session->unset_userdata('orders_ids');
            
            $data['user'] = 1;         
            $data['lang'] = $this->session->userdata['lang'];
            $data['usertype'] = $this->session->userdata['user_type'];
            if ($this->session->userdata['user_type'] == 3)
            {
                $this->load->view('header',$data);
            }
            else
            {
                $this->load->view('admin-header',$data);
            }
            
            $this->load->view('store_confirmation',$data);
            $this->load->view('footer');
        }
        else
        {
            if ($this->session->userdata['user_type'] == 3)
            {
                redirect('categories');
            }
            else
            {
                redirect('admin/dashboard');
            }
        }
    }
    
    function emptycart()
    {
        if (isAjax())
        { 
            $this->wishlist_model->delete($this->session->userdata['user']);
            $this->session->unset_userdata('wishlist');
            if (!isset($this->session->userdata['wishlist'])) echo 1;
            else echo 0;
        }
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
    
    function pos($id)
    {
        if (isset($this->session->userdata['user']))
        {
            $order = $this->orders_model->get($id);  
            
            $order_list = unserialize($order[0]->wish_list);
            
            $items = $order_list['items'];
            $prices = $order_list['quantity'];
            $shipping = $order_list['shipping'][$order_list['shipping'][0]];                
            foreach($items as $key => $item)
            {
                $prod = $this->products_model->get_products_by_id($item);         
                $price = $this->products_model->get_product_price($item, $prices[$item]); 
                $price = get_object_vars($price[0]);  
                $products_list[] = array($prod[0]->name,$prod[0]->description,$price[$prices[$item]]);
            }
            
            $stores_ids = unserialize($this->session->userdata['stores_ids']);
			$orders_ids = unserialize($this->session->userdata['orders_ids']);			
                          
            $store_id = $order[0]->store_id;
            $total_cost = $order[0]->total_cost;
            $total_price =  floatval($total_cost)*count($stores_ids);
            if ($this->session->userdata['lang'] == 'fr')
            {                    
                $data['store_type'] = 'Lebeau';
            }
            else
            {
                 $data['store_type'] = 'Speedy';
            }
                
            $data['admin'] = 0;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];            
            $data['lang'] = $this->session->userdata['lang'];            
            $data_data['lang'] = $this->session->userdata['lang'];            
            $view_data['id'] = $id;
            $view_data['products_list'] = $products_list;
            $view_data['shipping'] = $shipping; 
            $view_data['store_id'] = $store_id;
            $view_data['total_cost'] = $total_cost;
            $view_data['total_price'] = $total_price;
            $view_data['stores_ids'] = $stores_ids;
            $view_data['orders_ids'] = $orders_ids;                           
            $view_data['sups1'] = $this->get_user_superviser();
            $view_data['sups2'] = $this->get_store_superviser($stores_ids);
            $view_data['session_id'] = $this->session->userdata('session_id');
            $this->load->view('header',$data);
            $this->load->view('order_pos',$view_data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login/storelogin');
        }       
    }
    
    function approve_pos($id)
    {
        if (isset($this->session->userdata['user']))
        {            
            $pos = $_POST['pos'];
            
            $has_superviser = false;
            if ($this->session->userdata['user_type'] == 3)
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
                    $this->orders_model->add_pos($key,mysql_escape_string($item));
                 }
            }
            else
            {
                foreach($pos as $key => $item)
				{
					$this->orders_model->approve_pos($key,mysql_escape_string($item));
                    $verif_code = md5(time());
                    $this->status_model->insert($key,0,$verif_code);
                    
                    $mailer = new phpmailer();
                    $mailer->IsSendmail();
                    $mailer->From = 'noreply@domain.com';
                    $mailer->FromName = 'Belron admin';
                    $mailer->Subject = utf8_decode('Nouvelle commande ajoutée');
                    $email_message = "Une nouvelle commande vient d'être ajoutée <br/> 
                        Pour confirmer la receprion :<a href='".base_url()."orders/recieved/".$key."/".$verif_code."/'>" .base_url()."orders/recieved/".$key."/".$verif_code."/ </a><br/>
                        Pour confirmer le traitement :<a href='".base_url()."orders/submit/".$key."/".$verif_code."/'>" .base_url()."orders/submit/".$key."/".$verif_code."/ </a><br/>
                        Pour confirmer l'envoie :<a href='".base_url()."orders/send/".$key."/".$verif_code."/'>" .base_url()."orders/send/".$key."/".$verif_code."/ </a>";
                    $mailer->MsgHTML($email_message);
                    $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
                    $mailer->Send();
				}
                
            }
            redirect('wishlist/confirmation/');
        }
        else
        {
            redirect('login/storelogin');
        }
    }
    
    private function has_store_superviser()
    {
        return $this->permissions_model->get_store_supervisers($this->session->userdata['user']);
    }
    
    private function has_user_superviser()
    {  
        return $this->permissions_model->get_user_supervisers($this->users_model->get_id($this->session->userdata['user']));
    }
    
    private function get_store_superviser($stores_ids)
    {
        foreach($stores_ids as $store_id)
        {
            $sups = $this->permissions_model->get_store_supervisers_names($store_id);
            $supervisers = array();
            foreach($sups as $sup)
            {            
                $data = $this->users_model->get_user_infos($sup->superviser);
                $supervisers[$data[0]->id] = $data[0]->first_name .' ' . $data[0]->family_name;
            }
        }
        
        return $supervisers;
    }
    
    private function get_user_superviser()
    { 
        $sups = $this->permissions_model->get_user_supervisers_names($this->users_model->get_id($this->session->userdata['user']));
        $supervisers = array();
        foreach($sups as $sup)
        {            
            $data = $this->users_model->get_user_infos($sup->superviser);
            $supervisers[] = $data[0]->first_name .' ' . $data[0]->family_name;
        }
        
        return $supervisers;
    }
    
    function get_stores_supevised($user_id)
    {
        if ($this->session->userdata['user_type'] == 2)
        {
            $datas = $this->permissions_model->get_store_permissions($user_id);            
            foreach($datas as $data)
            {
                $stores[] = $data->store;
            }
        }
        else if ($this->session->userdata['user_type'] == 1)
        {
            $datas = $this->stores_model->get_all();     
            foreach($datas as $data)
            {
                $stores[] = $data->store_id;
            }
        }
        return $stores;
    }        
}
