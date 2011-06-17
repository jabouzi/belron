<?php

function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

function get_quantities()
{
    $quantities = array( 
               'prix_2_vinyles_opaque' => '2 vinyles opaque',
               'prix_3_vinyles_opaque' => '3 vinyles opaque', 
               'prix_4_vinyles_opaque' => '4 vinyles opaque', 
               'prix_5_vinyles_opaque' => '5 vinyles opaque', 
               'prix_1_papier_photo' => '1 papier photo', 
               'prix_2_papiers_photo' => '2 papiers photo', 
               'prix_3_papiers_photo' => '3 papiers photo', 
               'prix_4_papiers_photo' => '4 papiers photo', 
               'prix_5_papiers_photo' => '5 papiers photo', 
               'prix_pour_1' => '1',
               'prix_pour_2' => '2', 
               'prix_pour_3' => '3', 
               'prix_pour_4' => '4', 
               'prix_pour_5' => '5', 
               'prix_pour_20' => '20',
               'prix_pour_50' => '50', 
               'prix_pour_100' => '100', 
               'prix_pour_150' => '150', 
               'prix_pour_250' => '250', 
               'prix_pour_500' => '500', 
               'prix_pour_1000' => '1000',
            ); 
    return $quantities;
}

function get_sort_items()
{
    return array("Store", "Reservation date", "Approval date", "Approved by", "Number", "Total", "Status");
}

function get_sort_rows()
{
     return array("store_id", "save_data", "approve_date", "changed_by", "access_orders.id", "total_cost", "approved");
}

function get_sort_rows2()
{
     return array("store_id", "save_data", "approve_date", "changed_by", "id", "total_cost", "approved");
}

function get_product_items()
{
    return array
    (
        'Product id',
        'Category id',  
        'Brand',
        'Name', 
        'Language', 
        'Description',
        'Dimension', 
        'Impression materiel',
        'Date created',
        'Date modified',
    );
}

function get_product_rows()
{
     return array
     (
        'id',
        'category_id', 
        'brand', 
        'name', 
        'lang', 
        'description',
        'dimension', 
        'impression_materiel',
        'date_created',
        'date_modif',
    );
}

function get_user_items()
{
    return array('Position', 'Lastname', 'Firstname', 'Address', 'Town', 'Province', 'Postal code', 'Phone'
                    , 'Email', 'Active','Date created','Date modified');
}

function get_user_rows()
{
     return array('position', 'family_name', 'first_name', 'address', 'town', 'province', 'postal_code', 'phone'
                    , 'email', 'active','date_created','date_modif');
}

function get_store_items()
{
    return array('Store id', 'Name', 'Address', 'City', 'Postal code', 'Province', 'Phone'
                    , 'Fax', 'Manager', 'Cart', 'Date created','Date modified');
}

function get_store_rows()
{
     return array('store_id', 'name', 'address', 'city', 'postal_code', 'province', 'phone'
                    , 'fax', 'manager_or_owner', 'cart_active','date_created','date_modif');
}

function get_positions()
{
    return array('Regional D.', 'Director', 'Ops support', 'SDT', 'VP');
}

function get_types()
{
    return array('DM', 'ADMIN');
}

function format_phone($phone1,$phone2,$phone3)
{
    return "{$phone1}.{$phone2}.{$phone3}";
}

function deformat_phone($phone)
{
    $number = explode('.',$phone);
    return $number;
}

function format_postalcode($code1,$code2)
{
    return "{$code1} {$code2}";
}

function deformat_postalcode($code)
{
    $postacode = explode(' ',$code);
    return $postacode;
}

function provinces()
{
    return array("AB" => "ALBERTA" , 
                 "BC" => "BRITISH COLUMBIA", 
                 "MB" => "MANITOBA", 
                 "NB" => "NEW BRUNSWICK", 
                 "NT" => "NORTHWEST TERRITORIES",
                 "NS" => "NOVA SCOTIA",
                 "NU" => "NUNAVUT",
                 "ON" => "ONTARIO",
                 "PE" => "PRINCE EDWARD ISLAND",
                 "QC" => "QUEBEC",
                 "SK" => "SASKATCHEWAN",
                 "YT" => "YUKON");
} 

function get_redirect_url()
{
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $params   = $_SERVER['REQUEST_URI'];
    $currentUrl = $protocol . '://' . $host . $params;    
    $redirect_url = explode(base_url(),$currentUrl);
    
    return $redirect_url;
}

function change_language($lang)
{
    $language = 'en_CA';
    if ($lang == 'fr') $language = 'fr_CA';       
    
    putenv("LANG=$language"); 
    setlocale(LC_ALL, $language);
    
    $domain = 'messages';
    bindtextdomain($domain, ROOT."application/language/locale");
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
}

function send_post_data($url,$data)
{
    require_once 'HTTP/Request2.php';
    $request = new HTTP_Request2($url);
    $request->setMethod(HTTP_Request2::METHOD_POST)->addPostParameter($data);
    return $request->send();
}
