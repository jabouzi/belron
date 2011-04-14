<?php

function is_logged($user)
{        
    return ($user != false);
}

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
