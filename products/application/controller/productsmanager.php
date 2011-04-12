<?php

class productsmanager_controller
{

    private $products;
    private $user;
    private $users;
    private $errors;
    
    function __construct() 
    {
        $this->products = load::model('products');     
        $this->users = load::model('users');     
        $this->categories = load::model('categories');     
        load::library('pagination');  
        load::library('encrypt');
    }    
    
    public function index()
    {       
        if (is_logged(session::get('user')))
        {
            url::redirect('productsmanager/lists');
        }
        else
        {
            header('location: '.HOME_URL);
        }
    }
    
    public function login($uname,$pwd)
    {
        if (isAjax())
        {
            if ($uname == 'admin' && $pwd == 'admin')
            {
                session::set('lang','fr');
                session::set('product',$uname);
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
        session::delete('user');  
        header('location: '.HOME_URL);
    }
    
    public function lists($current_page = 1, $number = 10, $sort = 0, $type = 1)
    {       
        if (is_logged(session::get('user')))
        {
            $numbers = array();
            $rows = get_sort_rows();
            $total_products = $this->products->count();

            if ($total_products > 5 and $total_products < 10) $numbers = array(5,'all');
            if ($total_products > 10 and $total_products < 20) $numbers = array(5,10,'all');
            if ($total_products > 20 and $total_products < 50) $numbers = array(5,10,20,'all');
            if ($total_products > 50 and $total_products < 100) $numbers = array(5,10,20,50,'all');
            if ($total_products > 100) $numbers = array(5,10,20,50,100,'all');
            
            $unbr = $number;
            if ($number == 'all') $unbr = $total_products;            

            $page = new pagination($total_products,$current_page,$unbr);
            $sort_order = 'ASC';
            if ($type == 0) $sort_order = 'DESC';
            $products = $this->products->get_all_page($page->limit,$page->min,$rows[$sort],$sort_order);            
            
            load::view('header');
            load::view('products-index',array('products' => $products, 'page' => $page, 'current' => $current_page, 'total' => $page->total(), 'sort' => $sort, 'type' => $type, 'numbers' => $numbers, 'number' => $number, 'total_products' => $total_products, 'categories' => $this->get_categories()));
            load::view('footer'); 
        }
        else
        {
            url::redirect('productsmanager/index');
        }
    }
    
    public function edit($id)
    {
        if (is_logged(session::get('user')))
        { 
            $lang = array('fr' => 'fr', 'en' => 'en');
            $product = $this->products->get_product_infos($id);            
            load::view('header');
            load::view('edit-product',array('product' => $product, 'categories' => $this->get_categories(), 'language' => $lang));
            load::view('footer'); 
        }
        else
        {
            url::redirect('productsmanager/index');
        }    
    }
    
    public function update()
    {  
        if (is_logged(session::get('user')))
        {      
            $data['id'] = mysql_escape_string(input::post('product_id'));
            $data['group_id'] = mysql_escape_string(input::post('group_id')); 
            $data['category_id'] = mysql_escape_string(input::post('category'));  
            $data['name'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('name'))))); 
            $data['lang'] = mysql_escape_string(input::post('lang')); 
            $data['description'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('description')))));
            $data['dimension'] = mysql_escape_string(utf8_decode(input::post('dimension'))); 
            $data['impression_materiel'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('impression_materiel'))))); 
            $data['prix_1_vinyle_opaque'] = mysql_escape_string(input::post('prix_1_vinyle_opaque')); 
            $data['prix_2_vinyles_opaque'] = mysql_escape_string(input::post('prix_2_vinyles_opaque')); 
            $data['prix_3_vinyles_opaque'] = mysql_escape_string(input::post('prix_3_vinyles_opaque')); 
            $data['prix_4_vinyles_opaque'] = mysql_escape_string(input::post('prix_4_vinyles_opaque')); 
            $data['prix_5_vinyles_opaque'] = mysql_escape_string(input::post('prix_5_vinyles_opaque')); 
            $data['prix_1_papier_photo'] = mysql_escape_string(input::post('prix_1_papier_photo')); 
            $data['prix_2_papiers_photo'] = mysql_escape_string(input::post('prix_2_papiers_photo')); 
            $data['prix_3_papiers_photo'] = mysql_escape_string(input::post('prix_3_papiers_photo')); 
            $data['prix_4_papiers_photo'] = mysql_escape_string(input::post('prix_4_papiers_photo')); 
            $data['prix_5_papiers_photo'] = mysql_escape_string(input::post('prix_5_papiers_photo')); 
            $data['prix_pour_1'] = mysql_escape_string(input::post('prix_pour_1')); 
            $data['prix_pour_2'] = mysql_escape_string(input::post('prix_pour_2')); 
            $data['prix_pour_3'] = mysql_escape_string(input::post('prix_pour_3')); 
            $data['prix_pour_4'] = mysql_escape_string(input::post('prix_pour_4')); 
            $data['prix_pour_5'] = mysql_escape_string(input::post('prix_pour_5'));
            $data['prix_pour_20'] = mysql_escape_string(input::post('prix_pour_20'));
            $data['prix_pour_50'] = mysql_escape_string(input::post('prix_pour_50')); 
            $data['prix_pour_100'] = mysql_escape_string(input::post('prix_pour_100')); 
            $data['prix_pour_150'] = mysql_escape_string(input::post('prix_pour_150')); 
            $data['prix_pour_250'] = mysql_escape_string(input::post('prix_pour_250')); 
            $data['prix_pour_500'] = mysql_escape_string(input::post('prix_pour_500')); 
            $data['prix_pour_1000'] = mysql_escape_string(input::post('prix_pour_1000'));
            
            $res = $this->products->update_product(input::post('id'),$data);
            
            $url = HOME_URL."products/update/";
            $data['id'] = input::post('id');
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            url::redirect('productsmanager/confirm_update');
        }
        else
        {
            url::redirect('productsmanager/index');
        }     
    }
    
    public function add()
    {
        if (is_logged(session::get('user')))
        {           
            $lang = array('fr' => 'fr', 'en' => 'en');
            load::view('header');
            load::view('add-product',array('categories' => $this->get_categories(), 'language' => $lang));
            load::view('footer'); 
        }
        else
        {
            url::redirect('productsmanager/index');
        } 
    }
    
    public function insert()
    {   
        if (is_logged(session::get('user')))
        {      
            $data['id'] = mysql_escape_string(input::post('product_id'));
            $data['group_id'] = mysql_escape_string(input::post('group_id')); 
            $data['category_id'] = mysql_escape_string(input::post('category'));  
            $data['name'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('name'))))); 
            $data['lang'] = mysql_escape_string(input::post('lang')); 
            $data['description'] = mysql_escape_string(utf8_decode(ucwords(strtolower(input::post('description')))));
            $data['dimension'] = mysql_escape_string(utf8_decode(input::post('dimension'))); 
            $data['impression_materiel'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('impression_materiel'))))); 
            $data['prix_1_vinyle_opaque'] = mysql_escape_string(input::post('prix_1_vinyle_opaque')); 
            $data['prix_2_vinyles_opaque'] = mysql_escape_string(input::post('prix_2_vinyles_opaque')); 
            $data['prix_3_vinyles_opaque'] = mysql_escape_string(input::post('prix_3_vinyles_opaque')); 
            $data['prix_4_vinyles_opaque'] = mysql_escape_string(input::post('prix_4_vinyles_opaque')); 
            $data['prix_5_vinyles_opaque'] = mysql_escape_string(input::post('prix_5_vinyles_opaque')); 
            $data['prix_1_papier_photo'] = mysql_escape_string(input::post('prix_1_papier_photo')); 
            $data['prix_2_papiers_photo'] = mysql_escape_string(input::post('prix_2_papiers_photo')); 
            $data['prix_3_papiers_photo'] = mysql_escape_string(input::post('prix_3_papiers_photo')); 
            $data['prix_4_papiers_photo'] = mysql_escape_string(input::post('prix_4_papiers_photo')); 
            $data['prix_5_papiers_photo'] = mysql_escape_string(input::post('prix_5_papiers_photo')); 
            $data['prix_pour_1'] = mysql_escape_string(input::post('prix_pour_1')); 
            $data['prix_pour_2'] = mysql_escape_string(input::post('prix_pour_2')); 
            $data['prix_pour_3'] = mysql_escape_string(input::post('prix_pour_3')); 
            $data['prix_pour_4'] = mysql_escape_string(input::post('prix_pour_4')); 
            $data['prix_pour_5'] = mysql_escape_string(input::post('prix_pour_5'));
            $data['prix_pour_20'] = mysql_escape_string(input::post('prix_pour_20'));
            $data['prix_pour_50'] = mysql_escape_string(input::post('prix_pour_50')); 
            $data['prix_pour_100'] = mysql_escape_string(input::post('prix_pour_100')); 
            $data['prix_pour_150'] = mysql_escape_string(input::post('prix_pour_150')); 
            $data['prix_pour_250'] = mysql_escape_string(input::post('prix_pour_250')); 
            $data['prix_pour_500'] = mysql_escape_string(input::post('prix_pour_500')); 
            $data['prix_pour_1000'] = mysql_escape_string(input::post('prix_pour_1000'));
            
            $res = $this->products->add_product($data);
            
            $url = HOME_URL."products/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);

            url::redirect('productsmanager/confirm_insert');
        }
        else
        {
            url::redirect('productsmanager/index');
        }
    }
    
    public function delete($products)
    {
        if (isAjax())
        {
            $products_ids = explode(',',$products);
            foreach($products_ids as $product_id)
            {
                $this->products->delete_product($product_id);
            }
            
            $url = HOME_URL."products/delete/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            $data['products_ids'] = $products_ids;
            send_post_data($url,$data);
            echo 1;
        }
        else echo 0;
    }
    
    public function categories()
    {
        if (is_logged(session::get('user')))
        { 
            $categories = $this->categories->get_all();
            load::view('header');
            load::view('categories',array('categories' => $categories));
            load::view('footer'); 
        }
        else
        {
            url::redirect('productsmanager/index');
        }
    }
    
    public function update_category()
    {
        if (is_logged(session::get('user')))
        {
            $data['id'] = mysql_escape_string(input::post('id'));
            $data['name_fr'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('name_fr'))))); 
            $data['name_en'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('name_en'))))); 
            $data['image_file'] = mysql_escape_string(input::post('image_file')); 
            $data['active'] = mysql_escape_string(input::post('active'));
            
            $res = $this->categories->update($data['id'], $data);           

            /*$url = HOME_URL."products/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);*/

            url::redirect('productsmanager/confirm_update');
        }
        else
        {
            url::redirect('productsmanager/index');
        }
    }
    
    public function add_category()
    {
        if (is_logged(session::get('user')))
        {              
            $data['name_fr'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('name_fr'))))); 
            $data['name_en'] = mysql_escape_string(utf8_decode(ucfirst(strtolower(input::post('name_en'))))); 
            $data['image_file'] = mysql_escape_string(input::post('image_file')); 
            $data['active'] = mysql_escape_string(input::post('active')); 
            
            $res = $this->categories->insert($data);
            
            /*$url = HOME_URL."products/add/";
            $array2 = class_encrypt::keycalc('58hdlDMwol1hhWqAdtap');
            $array = class_encrypt::stringtoarray('4kbTOdrqyysumEu7q0nBTkmjuzfkey');
            $data['post_key'] = class_encrypt::transformstring($array, $array2);
            send_post_data($url,$data);*/

            url::redirect('productsmanager/confirm_insert');
        }
        else
        {
            url::redirect('productsmanager/index');
        }
    }
    
    public function edit_category($id)
    {
        if (is_logged(session::get('user')))
        {              
            $category = $this->categories->get_category($id);            
            load::view('header');
            load::view('edit-category',array('category' => $category));
            load::view('footer'); 
        }
        else
        {
            url::redirect('productsmanager/index');
        }
    }
    
    public function confirm_update()
    {
        load::view('header');
        load::view('update-confirm');
        load::view('footer'); 
    }
    
    public function confirm_insert()
    {
        load::view('header');
        load::view('add-confirm');
        load::view('footer'); 
    }
    
    public function change_language()
    {
        session::set('lang','en');   
        if (input::post('lang') == 'fr') session::set('lang','fr');        

        url::redirect(input::post('redirect_url'));   
    }    
    
    private function get_categories()
    {
        $categories = $this->categories->get_all(); 
        foreach ($categories as $categorie)
        {
            $return_array[$categorie->id] = array('fr' => $categorie->name_fr, 'en' => $categorie->name_en);
        }
        
        return $return_array;
    }
}
