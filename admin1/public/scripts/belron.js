var root_url = "/belron_pp/admin1";
var admin2_url = "http://php2.groupimage.net/belron_pp/admin2";

$(document).ready(function() {
   if ($('#wish_list').length)
   {
       display_wish_list($('#store_id').val())
   }
   
   var triggers = $(".modalInput").overlay({

        mask: {
            color: '#ebecff',
            loadSpeed: 200,
            opacity: 0.9
        },

        closeOnClick: false
    });

    //$("#users_table").tablesorter({widgets: ['zebra']}); 

    var buttons = $("#login button").click(function(e) {
        
        var en = ["You must type your username<br />","You must type your password<br />"];
        var fr = ["Le nom d'usager est obligatoire<br />","Le mot de passe est obligatoire<br />"];
        
        var submit = buttons.index(this) === 0;
        if (submit)
        {            
            var error = '';
            if ($("#uname").val() == '') 
            {
                if ($("#lang").val() == 'fr') error += fr[0];
                else error += en[0];
            }
            if ($("#pwd").val() == '') 
            {
                if ($("#lang").val() == 'fr') error += fr[1];
                else error += en[1];
            }
            if (error == '')
            {
                $.get(admin2_url+"/admin/login/"+$("#uname").val()+'/'+$("#pwd").val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
                    function(ok) {
                        if (ok != 0)
                        {
                            $(window.location).attr('href', admin2_url+'/admin/');
                        }
                        else
                        {
                            if ($("#lang").val() == 'fr') $("#error").html("Le nom d'usager ou le mot de passe est invalide");
                            else $("#error").html("The username or the password are invalid");
                        }
                    }
                );
            }
            else $("#error").html(error);
        }    
    });
   
});


function add_product_to_wish_list(store_id, product_id)
{
    $.get(root_url+"/wishlist/add/"+store_id+'/'+product_id+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(add_result) {
            if (add_result != 0)
            {
                $("#display_button").show();
                display_wish_list(store_id);
                location.reload();
            }
        }
    );
}

function display_wish_list(store_id)
{
    $.getJSON(root_url+"/wishlist/display/"+store_id+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(list_data) {            
            if (list_data != 0)
            {
                $('#wish_list').html('');
                for(var item = 0; item < list_data.length; item++)
                {                    
                    var data = list_data[item];
                    innerHtml = '<li>'+data[0]+': '+data[1]+'<br />';
                    innerHtml += data[2]+': '+data[3]+'<br />';
                    innerHtml += data[4]+': '+data[5]+'<br />';
                    innerHtml += data[6]+': '+data[7]+'<br />';
                    innerHtml += data[8]+': '+data[9]+'</li>';
                    innerHtml += '<p><input type="hidden" name="product_id[]" width="3" value="'+data[1]+'"></p>';
                    $('#wish_list').append(innerHtml);  
                    $("#display_button").show();                 
                    $("#display_empty").show();                 
                }                
            }
            else
            {
                $("#display_button").hide();
            }
        }
    );
}

function remove_from_wish_list(products, store_id)
{                  
    products_list = [];    
    for (var i = 0; i < products.length; i++)
    {
        products_list[i] = products[i];             
    }   
    for (var i = 0; i < products.length; i++)
    {
        if (!$("#checkbox_"+products[i]).attr("checked"))
        {              
            products.splice(i,1);
            i--;
        }         
    }    
    var removed = 0;
    $.get(root_url+"/wishlist/remove/"+store_id+'/'+products+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(remove_result) {
            if (remove_result != 0)
            {
                for (var i = 0; i < products.length; i++)
                {
                    if ($("#checkbox_"+products[i]).attr("checked"))
                    {              
                        removed++          
                        $("#"+products[i]).hide();  
                    }         
                }   
                if (products_list.length == removed) $(window.location).attr('href', root_url+'/');
                else $(window.location).attr('href', root_url+'/wishlist/confirm');
            }
        }
    );
}

function remove_from_order_list(products, order_id)
{    
    products_list = [];    
    for (var i = 0; i < products.length; i++)
    {
        products_list[i] = products[i];             
    }   
    for (var i = 0; i < products.length; i++)
    {
        if (!$("#checkbox_"+products[i]).attr("checked"))
        {              
            products.splice(i,1);
            i--;
        }         
    }    
    var removed = 0;             
    $.post(root_url+"/orders/remove/"+order_id+'/'+products+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(remove_result) {
            if (remove_result != 0)
            {
                for (var i = 0; i < products.length; i++)
                {
                    if ($("#checkbox_"+products[i]).attr("checked"))
                    {              
                        removed++          
                        $("#"+products[i]).hide();  
                    }         
                }   
               if (products_list.length == removed) $(window.location).attr('href', root_url+'/');
               else $(window.location).attr('href', root_url+'/orders/lists/'+order_id);
            }
        }
    );
}

function get_product_price(product_id)
{
    $.get(root_url+"/products/get_product_price/"+product_id+'/'+$("#select_"+product_id).val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(price) {
            if (price != 0)
            {
               $("#price_"+product_id).html('$'+price);
               $("#hidden_price_"+product_id).val(price);
            }
        }
    );
}

function get_product_shipping(product_id,prods)
{    
    $("#wait").html('<img src="'+root_url+'/public/images/loadinfo.gif">');
    $.getJSON(root_url+"/products/get_product_shipping/"+product_id+'/'+$("#select_"+product_id).val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(shipping) {            
            if (shipping != 0)
            {                
                if (shipping[0] == 'error')                     
                {
                    $("#shipping0"+"_"+product_id).val(1);
                    $("#shipping1"+"_"+product_id).val(1);
                    $("#shipping2"+"_"+product_id).val(1);
                }
                else       
                {
                    for (var i = 0; i < shipping.length; i++)
                    {                    
                        $("#shipping"+i+"_"+product_id).val(parseFloat(shipping[i].rate).toFixed(2));
                        $("#shipping"+i+"_"+product_id).val(parseFloat(shipping[i].rate).toFixed(2));
                    }
                }
                
                if (!loading_notfinished(prods))
                {
                    $("#wait").html('');
                    $("#getShipping").attr("disabled", "");
                    var total0 = 0;
                    var total1 = 0;
                    var total2 = 0;
                    var date0 = '';
                    var date1 = '';  
                    var date2 = '';
                    for(var i = 0; i < prods.length; i++)
                    {
                        if (!$("#checkbox_"+prods[i]).attr("checked"))
                        {
                            total0 += parseFloat($("#shipping0_"+prods[i]).val());
                            total1 += parseFloat($("#shipping1_"+prods[i]).val());
                            total2 += parseFloat($("#shipping2_"+prods[i]).val());                                
                        }                  
                    }
                    
                    if (shipping[0] == 'error') 
                    {
                        $("#shipping0").html(shipping[1]);                    
                    }
                    else
                    {
                        $("#shipping0").html("<input type='radio' name='radio_shipping' value='shipping0' onclick='get_sum(["+prods+"])'/> "+shipping[0].name+" : $"+parseFloat(total0).toFixed(2));
                        $("#shipping1").html("<input type='radio' name='radio_shipping' value='shipping1' onclick='get_sum(["+prods+"])'/> "+shipping[1].name+" : $"+parseFloat(total1).toFixed(2));
                        $("#shipping2").html("<input type='radio' name='radio_shipping' value='shipping2' onclick='get_sum(["+prods+"])'/> "+shipping[2].name+" : $"+parseFloat(total2).toFixed(2));
                        
                        var delivery_text = 'Estimation de livraison le';
                        if ($('#lang').val() == 'en') delivery_text = 'Delevry estimation for ';
                        
                        $("#date0").show();
                        $("#date1").show();
                        $("#date2").show();
                    }                    
                    $("#hidden_shipping0").val(parseFloat(total0).toFixed(2));
                    $("#hidden_shipping1").val(parseFloat(total1).toFixed(2));
                    $("#hidden_shipping2").val(parseFloat(total2).toFixed(2));
                }                
            }
        }
    );
}

function get_shipping(prods)
{
    $("#shipping0").html('');
    $("#shipping1").html('');
    $("#shipping2").html('');
    $("#date0").hide();
    $("#date1").hide();
    $("#date2").hide();
    var langen = ['You must select a product quantity for the product #', 'You can\'t get shipping rates'];
    var langfr = ['Aucune quantité d\'un produit n\'a été sélectionnée pour le produit #', 'Vous ne pouvez pas obtenir les frais de livraisons'];
    var errors = '';
    var errs_num = 0;
    var checked_num = 0;
    for(var i = 0; i < prods.length; i++)
    {
        if ($("#select_"+prods[i]).val() == '0')
        {
            if (!$("#checkbox_"+prods[i]).attr("checked"))
            {
                var nb = i + 1;
                errs_num++;   
                if ($("#lang").val() == 'fr')
                    errors += langfr[0]+' '+nb+'<br />';     
                else errors += langen[0]+' '+nb+'<br />';    
            } 
            else 
            {
                checked_num++;
            }
        }
        else
        {
            if ($("#checkbox_"+prods[i]).attr("checked"))
            {
                checked_num++;
            } 
        }                    
    }
    
    if (checked_num == prods.length)
    {
        if ($("#lang").val() == 'fr')
            errors += langfr[1]+'<br />';     
        else errors += langen[1]+'<br />';
        $("#error").html(errors);
    }
    else if (errs_num) $("#error").html(errors);
    else
    {
        $("#error").html('');
        $("#getShipping").attr("disabled", "disabled");
        for(var i = 0; i < prods.length; i++)
        {
            if ($("#select_"+prods[i]).val() != '0') get_product_shipping(prods[i],prods);        
        }
    }
}

function get_sum(prods)
{
    var shipping = $("input:radio[@name=radio_shipping]:checked").val();
    var total = parseFloat($("#hidden_"+shipping).val());
    for(var i = 0; i < prods.length; i++)
    {
        if (!$("#checkbox_"+prods[i]).attr("checked"))
        {
            total += parseFloat($("#hidden_price_"+prods[i]).val());                 
        }                  
    }   
    $("#total").html("$"+total.toFixed(2)); 
    $("#hidden_total").val(total.toFixed(2)); 
}

function save_wishlist()
{
    document.forms["wish_list"].submit();
}

function empty_cart()
{
     $.post(root_url+"/wishlist/emptycart/",{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(data) {
            if (data)
            {
                location.reload();
            }
        }
    );
}

function cancel_order()
{
     $.post(root_url+"/wishlist/emptycart/",{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(data) {
            if (data)
            {
                $(window.location).attr('href', root_url+'/categories');
            }
        }
    );
}

function order_wishlist(prods)
{
    $langen = ['You must select a product quantity for the product #','You must select a shipping rate'];
    $langfr = ['Aucune quantité d\'un produit n\'a été sélectionnée pour le produit #','Aucune livraison n\'a été sélectionnée'];
    $errors = '';
    $errs_num = 0;
    for(var i = 0; i < prods.length; i++)
    {
        if ($("#select_"+prods[i]).val() == '0')
        {
            if (!$("#checkbox_"+prods[i]).attr("checked"))
            {
                var nb = i + 1;
                $errs_num++;   
                if ($("#lang").val() == 'fr')
                    $errors += $langfr[0]+' '+nb+'<br />';     
                else $errors += $langen[0]+' '+nb+'<br />';     
            }
        }                  
    }  
    
    if (!$("input:radio[@name=radio_shipping]:checked").val())
    {
        $errs_num++;   
        if ($("#lang").val() == 'fr')
                $errors += $langfr[1]+'<br />';     
            else $errors += $langen[1]+'<br />'; 
    }
    if ($errs_num) $("#error").html($errors);
    else 
    {
        $("#error").html('');
        document.forms["order_wish_list"].submit();
    }
}

function submit_pos()
{
    var error = 0;
    for(var i = 0; i < $("#count_pos").val(); i++)
    {
		if ($("#pos_"+i).val() == '')
		{            
			error++;			
		}     
		if (error)
		{
			if ($("#lang").val() == 'fr')
				errors = "Il faut entrer le(s) P.O.S<br />";     
			else errors = "You must type the P.O.S (es)<br />";  
			$("#error").html(errors);   
		}
		else 
		{
			$("#error").html('');
			document.forms["pos_form"].submit();
		}
	}
}

function submit_dashboard(form)
{
    $('#'+form).attr('action',root_url+'/admin/dashboard/'+$('#user_name').val()+'/'+$('#store_name').val()+'/');
    document.forms[form].submit();    
}

function loading_notfinished(prods)
{
    var ok = 0;
    for(var i = 0; i < prods.length; i++)
    {
        if ($("#select_"+prods[i]).val() != '0')
        {
            if (!parseFloat($("#shipping0_"+prods[i]).val())) ok++;
            if (!parseFloat($("#shipping1_"+prods[i]).val())) ok++;
            if (!parseFloat($("#shipping2_"+prods[i]).val())) ok++;
        }
    }
    return ok;
}

function change_dashboard_page()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function sort_dashboard_page()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function sort_dashboard_type()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function change_dashboard_number()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function sort_users_page()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val()+'/'+$("#user_page").val());
}

function change_history_page()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function sort_history_page()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function sort_history_type()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function change_history_number()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#sort_number").val());
}

function change_orders_page()
{
    $(window.location).attr('href', root_url+'/orders/lists/0/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#user_page").val());
}

function sort_orders_page()
{
    $(window.location).attr('href', root_url+'/orders/lists/0/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#user_page").val());
}

function sort_orders_type()
{
    $(window.location).attr('href', root_url+'/orders/lists/0/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val()+'/'+$("#user_page").val());
}

function change_productsmanager_page()
{
    $(window.location).attr('href', root_url+'/productsmanager/lists/'+$("#products_page").val()+'/'+$("#products_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_productsmanager_page()
{
    $(window.location).attr('href', root_url+'/productsmanager/lists/1/'+$("#products_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_productsmanager_type()
{
    $(window.location).attr('href', root_url+'/productsmanager/lists/1/'+$("#products_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function change_productsmanager_number()
{
    $(window.location).attr('href', root_url+'/productsmanager/lists/1/'+$("#products_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function get_product_infos(id)
{
    $.getJSON(root_url+"/products/get_product_infos/"+$('#product_group_'+id).val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(data) {
            if (data != 0)
            {
                var text = 'More info';
                if ($('lang').val() == 'fr') text = 'Plus d\'infos';
                $('#h3_'+id).html(data[1]);
                $('#p_'+id).html('Dimensions : '+data[3]+'<br /><a href='+root_url+'"/products/product/'+data[0]+'">'+text+'<a><br>');
            }
        }
    );
}


function show_form()
{
    if ($('#category').val() == '0')
    {
        ;
    }
    else
    {
        $('#product_span').show();
        $('#product_add_submit').show();
        $.get(root_url+"/productsmanager/generate_product_id/"+$('#category').val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(data) {
            if (data != 0)
            {
                $('#product_id').val(data);
            }
        }
    );
    }
}

function add_update_product()
{    
    var en = ['You must enter a family name', 'You must enter a first name', 'You must enter an address', 'You must enter a city',
              'You must enter a province', 'You must a postal code', 'You must enter a phone number', 'You must enter an email', 
              'You must enter a password'];
    fr = [];
    var errors = '';
    if ($('#family_name').val() == '') errors += en[0] + '<br />';
    if ($('#first_name').val() == '') errors += en[1] + '<br />';
    if ($('#address').val() == '') errors += en[2] + '<br />';
    if ($('#town').val() == '') errors += en[3] + '<br />';
    if ($('#province').val() == '') errors += en[4] + '<br />';
    if ($('#postal_code1').val() == '') errors += en[5] + '<br />';
    if ($('#postal_code2').val() == '') errors += en[5] + '<br />';
    if ($('#phone1').val() == '') errors += en[6] + '<br />';
    if ($('#phone2').val() == '') errors += en[6] + '<br />';
    if ($('#phone3').val() == '') errors += en[6] + '<br />';
    if ($('#email').val() == '') errors += en[7] + '<br />';
    if ($('#password').val() == '') errors += en[8] + '<br />';
    
    if (errors != '') $('#error').html(errors);
    else document.forms["edit_product"].submit();   
    
    $('#type').val();
    
}

function add_update_category()
{    
    var en = ['You must enter a family name', 'You must enter a first name', 'You must enter an address', 'You must enter a city',
              'You must enter a province', 'You must a postal code', 'You must enter a phone number', 'You must enter an email', 
              'You must enter a password'];
    fr = [];
    var errors = '';
    if ($('#family_name').val() == '') errors += en[0] + '<br />';
    if ($('#first_name').val() == '') errors += en[1] + '<br />';
    if ($('#address').val() == '') errors += en[2] + '<br />';
    if ($('#town').val() == '') errors += en[3] + '<br />';
    if ($('#province').val() == '') errors += en[4] + '<br />';
    if ($('#postal_code1').val() == '') errors += en[5] + '<br />';
    if ($('#postal_code2').val() == '') errors += en[5] + '<br />';
    if ($('#phone1').val() == '') errors += en[6] + '<br />';
    if ($('#phone2').val() == '') errors += en[6] + '<br />';
    if ($('#phone3').val() == '') errors += en[6] + '<br />';
    if ($('#email').val() == '') errors += en[7] + '<br />';
    if ($('#password').val() == '') errors += en[8] + '<br />';
    
    if (errors != '') $('#error').html(errors);
    else document.forms["edit_product"].submit();   
    
    $('#type').val();
    
}

