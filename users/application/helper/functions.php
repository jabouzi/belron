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
    return array('Position', 'Lastname', 'Firstname', 'Address', 'Town', 'Province', 'Postal code', 'Phone'
                    , 'Email', 'Active','Date created','Date modified');
}

function get_sort_rows()
{
     return array('position', 'family_name', 'first_name', 'address', 'town', 'province', 'postal_code', 'phone'
                    , 'email', 'active','date_created','date_modif',);
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

function send_post_data($url,$data)
{
    require_once 'HTTP/Request2.php';
    $request = new HTTP_Request2($url);
    $request->setMethod(HTTP_Request2::METHOD_POST)->addPostParameter($data);
    return $request->send();
}
