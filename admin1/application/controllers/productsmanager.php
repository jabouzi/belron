<?php

class Productsmanager extends CI_Controller
{
    
    function __construct() 
    {
        parent::__construct();     
        $this->load->model('products_model');     
        $this->load->model('users_model');     
        $this->load->model('category_model');     
        $this->load->library('class_pagination');  
        $this->load->library('class_encrypt');
        $this->load->library('phpmailer'); 
    }    
    
    public function index()
    {       
        if (isset($this->session->userdata['user']))
        {
            redirect('productsmanager/lists');
        }
        else
        {
            redirect('admin/index');
        }
    }
    
    public function login($uname,$pwd)
    {
        if (isAjax())
        {
            if ($uname == 'admin' && $pwd == 'admin')
            {
                $this->session->set_userdata[array('lang' => 'fr')];
                $this->session->set_userdata[array('product' => $uname)];
                echo 1;
            }    
            else
            {
                echo 0;
            }        
        }
        else
        {
            echo 0;
        }
    }
    
    public function logout()
    {
        $this->session->unset_userdata['user'];  
        redirect('admin/index');
    }
    
    public function lists($current_page = 1, $number = 'all', $sort = 0, $type = 1)
    {       
        if (isset($this->session->userdata['user']))
        {
            $numbers = array();
            $rows = get_product_rows();
            $total_products = $this->products_model->count();

            if ($total_products > 5 and $total_products < 10) $numbers = array(5,'all');
            if ($total_products > 10 and $total_products < 20) $numbers = array(5,10,'all');
            if ($total_products > 20 and $total_products < 50) $numbers = array(5,10,20,'all');
            if ($total_products > 50 and $total_products < 100) $numbers = array(5,10,20,50,'all');
            if ($total_products > 100) $numbers = array(5,10,20,50,100,'all');
            
            $unbr = $number;
            if ($number == 'all') $unbr = $total_products;     
            
            $dimensions_text = get_dimensions_text();       
            $dimensions_val = get_dimensions_val();     
            
            foreach($dimensions_val as $key => $dimension)  
            {
                $dimensions[$dimension] = $dimensions_text[$key];
            }

            $page = new class_pagination($total_products,$current_page,$unbr);
            $sort_order = 'ASC';
            if ($type == 0) $sort_order = 'DESC';
            $products = $this->products_model->get_all_page($page->limit,$page->min,$rows[$sort],$sort_order);            
            
            if ($this->session->userdata['lang'] == 'fr')
            {
                $data['lang'] = 'fr';
            }
            else
            {
                $data['lang'] = 'en';
            }
            
            $data['store_type'] = 'Belron';  
            $data['admin'] = 1;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];
            
            $this->load->view('prod_header',$data); 
            $this->load->view('products-index',array('products' => $products, 'page' => $page, 'current' => $current_page, 
                                'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'numbers' => $numbers, 'dimensions' => $dimensions,
                                'number' => $number, 'total_products' => $total_products, 'categories' => $this->get_categories()));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('productsmanager/index');
        }
    }
    
    public function edit($id)
    {
        if (isset($this->session->userdata['user']))
        { 
            $lang = array('fr' => 'fr', 'en' => 'en');
            $product = $this->products_model->get_product_infos($id);     
            if ($this->session->userdata['lang'] == 'fr')
            {
                $data['lang'] = 'fr';
            }
            else
            {
                $data['lang'] = 'en';
            }
            
            $data['store_type'] = 'Belron';  
            $data['admin'] = 1;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];
            
            $this->load->view('prod_header',$data); 
            $this->load->view('edit-product',array('product' => $product, 'categories' => $this->get_categories(), 'language' => $lang));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('productsmanager/index');
        }    
    }
    
    public function update()
    {  
        if (isset($this->session->userdata['user']))
        {
            $data['name'] = ucfirst(strtolower($_POST['name'])); 
            $data['brand'] = ucfirst(strtolower($_POST['brand'])); 
            $data['lang'] = $_POST['lang']; 
            $data['description'] = ucwords(strtolower($_POST['description']));
            $data['dimension'] = $_POST['dimension']; 
            $data['impression_materiel'] = ucfirst(strtolower($_POST['impression_materiel']));             
            $data['prix_pour_1'] = $_POST['prix_pour_1']; 
            $data['prix_pour_2'] = $_POST['prix_pour_2']; 
            $data['prix_pour_3'] = $_POST['prix_pour_3']; 
            $data['prix_pour_4'] = $_POST['prix_pour_4']; 
            $data['prix_pour_5'] = $_POST['prix_pour_5'];
            $data['prix_pour_20'] = $_POST['prix_pour_20'];
            $data['prix_pour_50'] = $_POST['prix_pour_50']; 
            $data['prix_pour_100'] = $_POST['prix_pour_100']; 
            $data['prix_pour_150'] = $_POST['prix_pour_150']; 
            $data['prix_pour_250'] = $_POST['prix_pour_250']; 
            $data['prix_pour_500'] = $_POST['prix_pour_500']; 
            $data['prix_pour_1000'] = $_POST['prix_pour_1000'];
            $data['active'] = $_POST['active'];
            
            move_uploaded_file($_FILES['product_photo']['tmp_name'], UPLOAD.$_POST['id'].'_'.basename( $_FILES['product_photo']['name']));
            move_uploaded_file($_FILES['product_vectoriel']['tmp_name'], UPLOAD.$_POST['id'].'_'.basename( $_FILES['product_vectoriel']['name']));
            
            $res = $this->products_model->update_product($_POST['id'],$data);
            
            $url = ADMIN2."products/update/";
            $data['id'] = $_POST['id'];
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            redirect('productsmanager/confirm_update');
        }
        else
        {
            redirect('productsmanager/index');
        }     
    }
    
    public function add()
    {
        if (isset($this->session->userdata['user']))
        {           
            $lang = array('fr' => 'fr', 'en' => 'en');
            if ($this->session->userdata['lang'] == 'fr')
            {
                $data['lang'] = 'fr';
            }
            else
            {
                $data['lang'] = 'en';
            }
            
            $data['store_type'] = 'Belron';  
            $data['admin'] = 1;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];
            
            $this->load->view('prod_header',$data); 
            $this->load->view('add-product',array('categories' => $this->get_categories(), 'language' => $lang));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('productsmanager/index');
        } 
    }
    
    public function insert()
    {   
        if (isset($this->session->userdata['user']))
        {      
            $data['id'] = $_POST['product_id'];
            $data['category_id'] = $_POST['category'];  
            $data['brand'] = $_POST['brand'];  
            $data['name'] = ucfirst(strtolower($_POST['name'])); 
            $data['lang'] = $_POST['lang']; 
            $data['description'] = ucwords(strtolower($_POST['description']));
            $data['dimension'] = $_POST['dimension']; 
            $data['impression_materiel'] = ucfirst(strtolower($_POST['impression_materiel']));             
                        
            move_uploaded_file($_FILES['product_photo']['tmp_name'], UPLOAD.$data['id'].'_'.basename( $_FILES['product_photo']['name']));
            move_uploaded_file($_FILES['product_vectoriel']['tmp_name'], UPLOAD.$data['id'].'_'.basename( $_FILES['product_vectoriel']['name']));
            
            $res = $this->products_model->add_product($data);
            
           /* $url = ADMIN2."products/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);*/
            
            $mailer = new phpmailer();
            $mailer->IsSendmail();
            $mailer->From = 'noreply@belron.com';
            $mailer->FromName = 'Admin';
            $mailer->Subject = utf8_decode('Nouveau produit ajouté');
            $email_message = "<a href='".base_url()."productsmanager/edit/".$data['id']."/'>Produit # ".$data['id']."</a>";             
            $mailer->MsgHTML($email_message);
            $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
            $mailer->Send();

            redirect('productsmanager/confirm_insert');
        }
        else
        {
            redirect('productsmanager/index');
        }
    }
    
    public function delete($products)
    {
        if (isAjax())
        {
            $products_ids = explode(',',$products);
            foreach($products_ids as $product_id)
            {
                $this->products_model->delete_product($product_id);
            }     

            echo 1;
        }
        else echo 0;
    }
    
    public function categories()
    {
        if (isset($this->session->userdata['user']))
        { 
            $categories = $this->category_model->get_all();
            if ($this->session->userdata['lang'] == 'fr')
            {
                $data['lang'] = 'fr';
            }
            else
            {
                $data['lang'] = 'en';
            }
            
            $data['store_type'] = 'Belron';  
            $data['admin'] = 1;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];
            
            $this->load->view('prod_header',$data); 
            $this->load->view('prod_categories',array('categories' => $categories));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('productsmanager/index');
        }
    }
    
    public function update_category()
    {
        if (isset($this->session->userdata['user']))
        {
            $data['id'] = $_POST['id'];
            $data['name_fr'] = ucfirst(strtolower($_POST['name_fr'])); 
            $data['name_en'] = ucfirst(strtolower($_POST['name_en'])); 
            $data['image_file'] = $_POST['image_file']; 
            $data['active'] = $_POST['active'];
            
            $res = $this->category_model->update($data['id'], $data);           

            $url = ADMIN2."products/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            redirect('productsmanager/confirm_update');
        }
        else
        {
            redirect('productsmanager/index');
        }
    }
    
    public function add_category()
    {
        if (isset($this->session->userdata['user']))
        {              
            $data['name_fr'] = ucfirst(strtolower($_POST['name_fr'])); 
            $data['name_en'] = ucfirst(strtolower($_POST['name_en'])); 
            $data['image_file'] = $_POST['image_file']; 
            $data['active'] = $_POST['active']; 
            
            $res = $this->category_model->insert($data);
            
            $url = ADMIN2."products/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            redirect('productsmanager/confirm_insert');
        }
        else
        {
            redirect('productsmanager/index');
        }
    }
    
    public function edit_category($id)
    {
        if (isset($this->session->userdata['user']))
        {              
            $category = $this->category_model->get_category($id);            
            if ($this->session->userdata['lang'] == 'fr')
            {
                $data['lang'] = 'fr';
            }
            else
            {
                $data['lang'] = 'en';
            }
            
            $data['store_type'] = 'Belron';  
            $data['admin'] = 1;   
            $data['user'] = 1;
            $data['usertype'] = $this->session->userdata['user_type'];
            
            $this->load->view('prod_header',$data); 
            $this->load->view('edit-category',array('category' => $category));
            $this->load->view('footer'); 
        }
        else
        {
            redirect('productsmanager/index');
        }
    }
    
    public function confirm_update2()
    {
        if ($this->session->userdata['lang'] == 'fr')
        {
            $data['lang'] = 'fr';
        }
        else
        {
            $data['lang'] = 'en';
        }
        
        $data['store_type'] = 'Belron';  
        $data['admin'] = 1;   
        $data['user'] = 1;
        $data['usertype'] = $this->session->userdata['user_type'];
        
        $this->load->view('prod_header',$data); 
        $this->load->view('update-confirm2');
        $this->load->view('footer'); 
    }
    
    public function confirm_update()
    {
        if ($this->session->userdata['lang'] == 'fr')
        {
            $data['lang'] = 'fr';
        }
        else
        {
            $data['lang'] = 'en';
        }
        
        $data['store_type'] = 'Belron';  
        $data['admin'] = 1;   
        $data['user'] = 1;
        $data['usertype'] = $this->session->userdata['user_type'];
        
        $this->load->view('prod_header',$data); 
        $this->load->view('update-confirm');
        $this->load->view('footer'); 
    }
    
    public function confirm_insert()
    {
        if ($this->session->userdata['lang'] == 'fr')
        {
            $data['lang'] = 'fr';
        }
        else
        {
            $data['lang'] = 'en';
        }
        
        $data['store_type'] = 'Belron';  
        $data['admin'] = 1;   
        $data['user'] = 1;
        $data['usertype'] = $this->session->userdata['user_type'];
        
        $this->load->view('prod_header',$data); 
        $this->load->view('add-confirm');
        $this->load->view('footer'); 
    }
    
    public function confirm_cat_insert()
    {
        if ($this->session->userdata['lang'] == 'fr')
        {
            $data['lang'] = 'fr';
        }
        else
        {
            $data['lang'] = 'en';
        }
        
        $data['store_type'] = 'Belron';  
        $data['admin'] = 1;   
        $data['user'] = 1;
        $data['usertype'] = $this->session->userdata['user_type'];
        
        $this->load->view('prod_header',$data); 
        $this->load->view('add-confirm2');
        $this->load->view('footer'); 
    }
    
    public function category_active($cat_id)
    {
        $category = $this->category_model->get_category($cat_id); 
        return $category[0]->active;
    }
    
    public function generate_product_id($cat_id)
    {
        if (isAjax())
        {
            $products = $this->products_model->get_last_products_id($cat_id);
            if ($products[0]->id == 0) echo $cat_id * 10000 + 1;
            else echo $products[0]->id + 1;
        }
        else
        {
            echo 0;
        }
    }
    
    private function get_categories()
    {
        $categories = $this->category_model->get_all_active(); 
        foreach ($categories as $categorie)
        {
            $return_array[$categorie->id] = array('fr' => $categorie->name_fr, 'en' => $categorie->name_en);
        }
        
        return $return_array;
    }
    
    function request_price()
    {
        $mailer = new phpmailer();
        $mailer->IsSendmail();
        $mailer->From = 'noreply@domain.com';
        $mailer->FromName = 'Belron admin';
        $mailer->Subject = utf8_decode('Demande pour pour revision de prix : #').$_POST['id'];
        $email_message = "Une demande pour revision de prix : #{$_POST['id']}<br/><br/>";
        foreach($problems as $problem)
        {
            $email_message .= $_POST['price_request']."<br/>"; 
        }
        $email_message .= "La page du produit : <a href='".base_url()."productsmanager/edit/".$_POST['id']."/'>" .base_url()."productsmanager/edit/".$_POST['id']."/ </a><br/>";
        $mailer->MsgHTML($email_message);
        $mailer->AddAddress('skander.jabouzi@groupimage.com', 'Skander Jabouzi');
        $mailer->Send();
        
        redirect('orders/request_confirmation');
    }   
}
