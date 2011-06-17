<?php

class Orders extends CI_Controller
{
    function __construct() 
    {
        parent::__construct();      
        $this->load->model('orders_model');    
        $this->load->model('stores_model');   
        $this->load->model('permissions_model');    
        $this->load->model('status_model');    
        $this->load->model('users_model');    
        $this->load->model('products_model');
        $this->load->library('phpmailer'); 
        $this->load->library('class_pagination');
    }    
    
    function detail($id)
    {
        if (isset($this->session->userdata['user']))
        {            
            $order =  $this->orders_model->get($id); 
            if ($order[0]->store_id == $this->session->userdata['user'])
            {
                $order = $this->orders_model->get($id);
                $order_list = unserialize($order[0]->wish_list);                           
                foreach($order_list['items'] as $key => $item)
                {                        
                    $product_price = $this->products_model->get_product_price($item, $order_list['quantity'][$item]);
                    $price = get_object_vars($product_price[0]);
                    $prod_data = $this->products_model->get_products_by_id($item);
                    $order_list['items'][$key] = $prod_data;
                    $order_list['price'][$item] = $price[$order_list['quantity'][$item]];
                } 
                
                $approved = $this->orders_model->is_approved($id);
                
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
                $status = $this->status_model->get($id);
                $this->load->view('header',$data);                   
                $this->load->view('order_approved',array('order' => $order_list, 'store' => $this->stores_model->get($order[0]->store_id), 'pos' => $order[0]->pos, 
                            'order_id' => $id, 'rows' => get_quantities(), 'approved' => $approved[0]->approved, 'status' => $status[0]->status, 
                            'user_type' => $this->session->userdata['user_type']));
                $this->load->view('footer');  
            }
            else
            {
                redirect('login/storelogin');
            }
        }
        else
        {
            redirect('login/storelogin');
        }
    }
    
    function historique($current_page = 1, $sort = 0, $type = 0, $number = 'all')
    {
        if (isset($this->session->userdata['user']))
        {            
            if ($this->session->userdata['user_type'] == 3)
            {
                $rows = get_sort_rows2();
                $total_orders = $this->orders_model->store_count($this->session->userdata['user']);
                $sort_order = 'DESC';
                if ($type == 1) $sort_order = 'ASC';
                
                if ($total_orders > 5 and $total_orders < 10) $numbers = array(5,'all');
                if ($total_orders > 10 and $total_orders < 20) $numbers = array(5,10,'all');
                if ($total_orders > 20 and $total_orders < 50) $numbers = array(5,10,20,'all');
                if ($total_orders > 50 and $total_orders < 100) $numbers = array(5,10,20,50,'all');
                if ($total_orders > 100) $numbers = array(5,10,20,50,100,'all');
                
                $unbr = $number;
                if ($number == 'all') $unbr = $total_orders;            

                $page = new class_pagination($total_orders,$current_page,$unbr);
                
                $orders = $this->orders_model->get_by_store_id_page($this->session->userdata['user'],$page->limit,$page->min,$rows[$sort],$sort_order);   
                $users = array();
                foreach($orders as $key => $order)
                {
                    $status_list = array('0','Recieved (2/4)','In treatment (3/4)','Send (4/4)','Problem','Cancelled');
                    $name = $this->users_model->get_name($order->changed_by);
                    $users[$key][] = $name[0]->first_name;
                    $users[$key][] = $name[0]->family_name;
                    $status = $this->status_model->get($order->id);
                    $orders_status[$order->id] = $status_list[$status[0]->status];
                }                        
            }           
             
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
            $data_view['lang'] = $this->session->userdata['lang']; 

            if (isset($this->session->userdata['wishlist'])) $data_view['wishlist'] = $this->session->userdata['wishlist'];   
            
            $this->load->view('header',$data);
            $this->load->view('historique',array('orders' => $orders,'page' => $page, 'users' => $users, 'current' => $current_page, 
                        'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'orders_status' => $orders_status,
                        'numbers' => $numbers, 'number' => $number));
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
            $this->load->view('order_product',array('product' => $this->products_model->get_products_by_id($id), 'store_id' => $this->session->userdata['user']));
            $this->load->view('footer');    
        }
        else
        {
            redirect('login/storelogin');
        }
    }   
    
    function approve()
    {
        if (isset($this->session->userdata['user']))
        {               
            $saved_wish_list = array();
            $producs_qtys = $_POST['product_qty'];
            $products_ids = $_POST['product_id'];
            $products_removed = $_POST['checkbox_supprim'];  

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
            
            $user_id = $this->users_model->get_id($this->session->userdata['user']);
            $this->orders_model->approve(serialize($saved_wish_list), $_POST['order_id'], '1', $this->session->userdata['user'], $_POST['hidden_total']);            
            
            $address = array('address' => $_POST['address'], 
                            'city' => $_POST['city'], 
                            'postal_code' => $_POST['postal_code'], 
                            'province' => $_POST['province'], 
                            'phone' => $_POST['phone'],
                            'fax' => $_POST['fax']);
                            
            if (!empty($saved_wish_list))
            {
                $this->stores_model->update_address($this->session->userdata['user'],$address);                
            }
            
            redirect('orders/confirmation');
        }  
        else
        {
            redirect('login/storelogin');
        }
    }
    
    function approve_order($id)
    {
        $res = $this->orders_model->approve_direct($id, '1', $this->session->userdata['user']);
        
        $verif_code = md5(time());
        $this->status_model->insert($id,0,$verif_code);
        
        $mailer = new phpmailer();
        $mailer->IsSendmail();
        $mailer->From = 'noreply@domain.com';
        $mailer->FromName = 'Belron admin';
        $mailer->Subject = utf8_decode('Nouvelle commande ajoutée');
        $email_message = "Une nouvelle commande vient d'être ajoutée <br/> 
            Pour confirmer la receprion :<a href='".base_url()."orders/recieved/".$id."/".$verif_code."/'>" .base_url()."orders/recieved/".$id."/".$verif_code."/ </a><br/>
            Pour confirmer le traitement :<a href='".base_url()."orders/submit/".$id."/".$verif_code."/'>" .base_url()."orders/submit/".$id."/".$verif_code."/ </a><br/>
            Pour confirmer l'envoie :<a href='".base_url()."orders/send/".$id."/".$verif_code."/'>" .base_url()."orders/send/".$id."/".$verif_code."/ </a>";
        $mailer->MsgHTML($email_message);
        $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
        $mailer->Send();
        
        redirect('orders/confirmation');
    }
    
    function order_again($id)
    {           
		if (isset($this->session->userdata['user']))
        { 
			$stores = $_POST['store-orders'];     
			if (!empty($stores))
			{
				$order = $this->orders_model->get($id);
				if ($this->session->userdata['user_type'] == 3)
				{					 
                    $approved = '';
                    $date_approval = '0000-00-00 00:00:00';
                    if (!$this->has_store_superviser())
                    {
                        $approved = '1';
                        $date_approval = date('Y-m-d H:i:s');
                    }
                    $order_id[] = $this->orders_model->duplicate($order[0]->wish_list, $this->session->userdata['user'], '', $order[0]->total_cost, $approved, $approve_date);
				}
				else
				{
                    $approved = '';
                    $date_approval = '0000-00-00 00:00:00';
                    if (!$this->has_user_superviser())
                    {
                        $approved = '1';
                        $date_approval = date('Y-m-d H:i:s');   
                    }         
                            
                    foreach($stores as $store)
                    {
                        $order_id[] = $this->orders_model->duplicate($order[0]->wish_list, $store, $this->session->userdata['user'], $order[0]->total_cost, $approved, $approve_date);
                    }
                    
				}                
			  
				$this->session->set_userdata('stores_ids',serialize($stores));
				$this->session->set_userdata('orders_ids',serialize($order_id));
				
				redirect("wishlist/pos/".$order_id[0]);
			}
			else
			{
				redirect("orders/lists/0");
			}
		}
		else
		{
			redirect('login/storelogin');
		}
    }
    
    function confirmation()
    {       
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
        $this->load->view('store_confirmation');
        $this->load->view('footer');
    }
    
    function request_confirmation()
    {
        
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
        $this->load->view('request_confirmation');
        $this->load->view('footer');
    }
    
    function status_confirmation()
    {          
        $data['user'] = 1;        
        $data['lang'] = 'fr';
        $data['usertype'] = 1;
        $this->load->view('admin-header',$data);
        $this->load->view('status_confirmation');
        $this->load->view('footer');
    }
    
    function remove($id, $products)
    {
        if (isAjax())
        {           
            $products_ids = explode(',',$products);
            $list = $this->orders_model->get($id);
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
                    $wish_list = array();
                    $this->orders_model->approve(serialize($wish_list), $id, '1', $this->session->userdata['user']); 
                }
                else
                {
                    $this->orders_model->update(serialize($wish_list), $id, $this->session->userdata['user']);   
                }
                echo 1;
            }  
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
    
    private function has_store_superviser()
    {
        return $this->permissions_model->get_store_supervisers($this->session->userdata['user']);
    }
    
    private function has_user_superviser()
    {
        return $this->permissions_model->get_user_supervisers($this->users_model->get_id($this->session->userdata['user']));
    }
    
    function get_users_supevised($user_id)
    {
        $datas = $this->permissions_model->get_user_permissions($user_id);
        foreach($datas as $data)
        {            
            $users[] = $data->user;
        }
        
        return $users;
    }
    
    function get_stores_supevised($user_id)
    {
        $datas = $this->permissions_model->get_store_permissions($user_id);
        foreach($datas as $data)
        {
            $stores[] = $data->store;
        }
        return $stores;
    }    
    
    function recieved($order_id, $code_verif)
    {
        $order_status = $this->status_model->get($order_id);
        if ($order_status[0]->code_verif == $code_verif)
        {
            $this->status_model->update($order_id,'1',$code_verif);
        }
        
        redirect('orders/status_confirmation');
    }
    
    function submit($order_id, $code_verif)
    {
        $order_status = $this->status_model->get($order_id);
        if ($order_status[0]->code_verif == $code_verif)
        {
            $this->status_model->update($order_id,'2',$code_verif);
        }
        
        redirect('orders/status_confirmation');
    }
    
    function send($order_id, $code_verif)
    {
        $order_status = $this->status_model->get($order_id);
        if ($order_status[0]->code_verif == $code_verif)
        {
            $this->status_model->update($order_id,'3',$code_verif);
        }
        
        redirect('orders/status_confirmation');
    }
    
    function problem($order_id, $code_verif)
    {
        $order_status = $this->status_model->get($order_id);
        if ($order_status[0]->code_verif == $code_verif)
        {
            $this->status_model->update($order_id,'4',$code_verif);
        }  
        
        redirect('orders/status_confirmation');      
    }    
    
    function cancel($order_id, $code_verif)
    {
        $order_status = $this->status_model->get($order_id);
        if ($order_status[0]->code_verif == $code_verif)
        {
            $this->status_model->update($order_id,'5',$code_verif);
        }
        
        redirect('orders/status_confirmation');
    }
    
    function request_problem($order_id)
    {
        $order_status = $this->status_model->get($order_id);        
        $problems = $_POST['problems'];  
        $mailer = new phpmailer();
        $mailer->IsSendmail();
        $mailer->From = 'noreply@domain.com';
        $mailer->FromName = 'Belron admin';
        $mailer->Subject = utf8_decode('Problème pour une demande');
        $email_message = "Une signalisation d'un problème pour une demande : <br/><br/>"; 
        if ($_POST['not_recieved'] == 1)    
        {
            $email_message .= "Je n'ai pas reçu ma commande : <br/><br/>"; 
        }
        foreach($problems as $problem)
        {
            $email_message .= $problem."<br/>"; 
        }
        $email_message .= "<br/>Pour confirmer le problème :<a href='".base_url()."orders/problem/".$order_id."/".$order_status[0]->code_verif."/'>" .base_url()."orders/problem/".$order_id."/".$order_status[0]->code_verif."/ </a><br/>";
        $mailer->MsgHTML($email_message);
        $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
        $mailer->Send();
        
        redirect('orders/request_confirmation');
        
    }    
    
    function request_cancel($order_id)
    {
        $order_status = $this->status_model->get($order_id);
        $problems = $_POST['problems'];  
        $mailer = new phpmailer();
        $mailer->IsSendmail();
        $mailer->From = 'noreply@domain.com';
        $mailer->FromName = 'Belron admin';
        $mailer->Subject = utf8_decode('Demande pour annuler pour une demande');
        $email_message = "Une demande pour annuler une demande : <br/><br/>";
        foreach($problems as $problem)
        {
            $email_message .= $problem."<br/>"; 
        }
        $email_message .= "Pour confirmer l'annulation :<a href='".base_url()."orders/cancel/".$order_id."/".$order_status[0]->code_verif."/'>" .base_url()."orders/cancel/".$order_id."/".$order_status[0]->code_verif."/ </a><br/>";
        $mailer->MsgHTML($email_message);
        $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
        $mailer->Send();
        
        redirect('orders/request_confirmation');
    }   
    
    function invoice($order_id)
	{
        if (isset($this->session->userdata['user']))
        {
            $this->load->library('cezpdf');
            $this->load->helper('pdf');   
            
            $names_fr = array('Numéro','Description','Prix','Quantité','Commande','Livraison','Totale','Frais de livraisons peuvent changer', 'Taxe non inluse','Produits');
            $names_en = array('Number','Description','Price','Quantity','Order','Shipping','Total','Delivery fees may change', 'Tax not included','Products');
            $col_lan_names = array('fr' => $names_fr, 'en' => $names_en);
            
            $order = $this->orders_model->get($order_id);
            $wishlist = unserialize($order[0]->wish_list); 
            $quantities = get_quantities();

            $shipping = $wishlist['shipping'][$wishlist['shipping'][0]];
            foreach($wishlist['items'] as $item)
            {
                $product = $this->products_model->get_products_by_id($item);
                $price = $this->products_model->get_product_price($item, $wishlist['quantity'][$item]);
                $price = get_object_vars($price[0]);
                $quantity = $quantities[$wishlist['quantity'][$item]];   
                $db_data[] = array('id' => $product[0]->id, 'description' => utf8_decode($product[0]->name).' : '.utf8_decode($product[0]->description), 'price' => '$'.$price[$wishlist['quantity'][$item]], 'quantity' => $quantity);
            }        
                
            $col_names = array(
                'id' => utf8_decode($col_lan_names[$this->session->userdata['lang']][0]),
                'description' => $col_lan_names[$this->session->userdata['lang']][1],
                'price' => $col_lan_names[$this->session->userdata['lang']][2],
                'quantity' => utf8_decode($col_lan_names[$this->session->userdata['lang']][3]),
            );

            $this->cezpdf->addText(5, $this->cezpdf->y,20,"<b>BELRON CANADA</b>");
            $this->cezpdf->addText(5, $this->cezpdf->y-20,15,date("Y-m-d"));
            $this->cezpdf->addText(5, $this->cezpdf->y-40,10,"{$col_lan_names[$this->session->userdata['lang']][4]} # : {$order[0]->id}");
            $this->cezpdf->addText(5, $this->cezpdf->y-50,10,"P.O.S : {$order[0]->pos}");
            $this->cezpdf->addText(5, $this->cezpdf->y-70,10,"{$col_lan_names[$this->session->userdata['lang']][5]}* : $".$shipping);
            $this->cezpdf->addText(5, $this->cezpdf->y-80,10,"{$col_lan_names[$this->session->userdata['lang']][6]}** : $".$order[0]->total_cost);
            $this->cezpdf->addText(5, $this->cezpdf->y-90,5,"*<i>{$col_lan_names[$this->session->userdata['lang']][7]}</i>");
            $this->cezpdf->addText(5, $this->cezpdf->y-95,5,"**<i>{$col_lan_names[$this->session->userdata['lang']][8]}</i>");
            $this->cezpdf->ezSetY($this->cezpdf->y-110);
            $this->cezpdf->ezTable($db_data, $col_names, $col_lan_names[$this->session->userdata['lang']][9], array('width'=>550));
            $this->cezpdf->ezStream();
        }
        else
        {
            redirect('login/storelogin');
        }  
	}

}
