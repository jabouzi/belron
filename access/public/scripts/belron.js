var root_url = "/belron_pp/access";
var products_url = "/belron_pp/stores";

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
                $.get(products_url+"/storesmanager/login/"+$("#uname").val()+'/'+$("#pwd").val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
                    function(ok) {
                        if (ok != 0)
                        {
                            $(window.location).attr('href', products_url+'/storesmanager/lists/');
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
                }                
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
                    }
                }
                
                if (!loading_notfinished(prods))
                {
                    $("#wait").html('');
                    $("#getShipping").attr("disabled", "");
                    var total0 = 0;
                    var total1 = 0;
                    var total2 = 0;
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
                        $("#shipping0").html("<input type='radio' name='radio_shipping' value='shipping0' onclick='get_sum(["+prods+"])'/> Colis standard: $"+parseFloat(total0).toFixed(2));
                        $("#shipping1").html("<input type='radio' name='radio_shipping' value='shipping1' onclick='get_sum(["+prods+"])'/> Colis accélérés: $"+parseFloat(total1).toFixed(2));
                        $("#shipping2").html("<input type='radio' name='radio_shipping' value='shipping2' onclick='get_sum(["+prods+"])'/> Messageries prioritaires: $"+parseFloat(total2).toFixed(2));
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
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_dashboard_page()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_dashboard_type()
{
    $(window.location).attr('href', root_url+'/admin/dashboard/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function change_history_page()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_history_page()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_history_type()
{
    $(window.location).attr('href', root_url+'/orders/historique/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function change_orders_page()
{
    $(window.location).attr('href', root_url+'/orders/lists/0/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_orders_page()
{
    $(window.location).attr('href', root_url+'/orders/lists/0/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_orders_type()
{
    $(window.location).attr('href', root_url+'/orders/lists/0/'+$("#orders_page").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
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
