var root_url = "/belron_pp/products";

$(document).ready(function() {
    
    var triggers = $(".modalInput").overlay({
        mask: {
            color: '#ebecff',
            loadSpeed: 200,
            opacity: 0.9
        },

        closeOnClick: false
    });    
    
    $("#products_table").tablesorter({widgets: ['zebra']}); 

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
                $.get(root_url+"/productsmanager/login/"+$("#uname").val()+'/'+$("#pwd").val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
                    function(ok) {
                        if (ok != 0)
                        {
                            $(window.location).attr('href', root_url+'/productsmanager/lists/');
                        }
                        else
                        {
                            if ($("#lang").val() == 'fr') $("#error").html("Le nom d'usager ou le mot de passe est invalide");
                            else $("#error").html("The productname or the password are invalid");
                        }
                    }
                );
            }
            else $("#error").html(error);
        }    
    });
    
    var buttons2 = $("#managers button").click(function(e) {
        $("#manager_name").val($("#manager option[value='"+$("#manager").val()+"']").text());
        $("#dm_id").val($("#manager").val()); 
    });
});

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
    else document.forms["edit_category"].submit();   
    
    $('#type').val();
    
}

function jump(curElm, nextElm, length)
{
    if($("#"+curElm).val().length == length) $("#"+nextElm).focus();
}

function remove_products(products)
{
    var products2remove = [];
    for(var i = 0; i < products.length; i++)
    {
        if ($("#checkbox_"+products[i]).attr("checked"))
        {
            products2remove.push(products[i]);
        }
    }

    $.get(root_url+"/productsmanager/delete/"+products2remove+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
        function(data) {
            if (data != 0)
            {
                $(window.location).attr('href', root_url+'/productsmanager/lists/'+$("#products_page").val()+'/'+$("#products_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
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
