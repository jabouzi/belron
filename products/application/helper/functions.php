<?php

function is_logged($user)
{        
    return ($user != false);
}

function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

function isPost()
{
    return ($_SERVER['REQUEST_METHOD'] == 'POST');
}

function get_sort_items()
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

function get_sort_rows()
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

function send_post_data($url,$data)
{
    require_once 'HTTP/Request2.php';
    $request = new HTTP_Request2($url);
    $request->setMethod(HTTP_Request2::METHOD_POST)->addPostParameter($data);
    return $request->send();
}

/* 
 function get_sort_items()
{
    return array
    (
        'Product id',
        'Group id', 
        'Category id',  
        'Name', 
        'Language', 
        'Description',
        'Dimension', 
        'Impression materiel', 
        'Prix 1 vinyle opaque', 
        'Prix 2 vinyles opaque', 
        'Prix 3 vinyles opaque', 
        'Prix 4 vinyles opaque', 
        'Prix 5 vinyles opaque', 
        'Prix 1 papier photo', 
        'Prix 2 papiers photo', 
        'Prix 3 papiers photo', 
        'Prix 4 papiers photo', 
        'Prix 5 papiers photo', 
        'Prix pour 1', 
        'Prix pour 2', 
        'Prix pour 3', 
        'Prix pour 4', 
        'Prix pour 5', 
        'Prix pour 20 ',
        'Prix pour 50', 
        'Prix pour 100', 
        'Prix pour 150', 
        'Prix pour 250', 
        'Prix pour 500', 
        'Prix pour 1000',
    );
}

function get_sort_rows()
{
     return array
     (
        'id',
        'group_id', 
        'category_id',  
        'name', 
        'lang', 
        'description',
        'dimension', 
        'impression_materiel', 
        'prix_1_vinyle_opaque', 
        'prix_2_vinyles_opaque', 
        'prix_3_vinyles_opaque', 
        'prix_4_vinyles_opaque', 
        'prix_5_vinyles_opaque', 
        'prix_1_papier_photo', 
        'prix_2_papiers_photo', 
        'prix_3_papiers_photo', 
        'prix_4_papiers_photo', 
        'prix_5_papiers_photo', 
        'prix_pour_1', 
        'prix_pour_2', 
        'prix_pour_3', 
        'prix_pour_4', 
        'prix_pour_5', 
        'prix_pour_20 ',
        'prix_pour_50', 
        'prix_pour_100', 
        'prix_pour_150', 
        'prix_pour_250', 
        'prix_pour_500', 
        'prix_pour_1000',
    );
}
*/ 
 
