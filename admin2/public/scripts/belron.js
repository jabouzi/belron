var root_url = "/belron_pp/admin2";

$(document).ready(function() {
    
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
                $.get(root_url+"/usersmanager/login/"+$("#uname").val()+'/'+$("#pwd").val()+'/',{uid : String((new Date()).getTime()).replace(/\D/gi, '') },
                    function(ok) {
                        if (ok != 0)
                        {
                            $(window.location).attr('href', root_url+'/usersmanager/lists/');
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

function close_overlay()
{
    $('.close').click();
}

function change_usersmanager_page()
{
    $(window.location).attr('href', root_url+'/usersmanager/lists/'+$("#users_page").val()+'/'+$("#users_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_usersmanager_page()
{
    $(window.location).attr('href', root_url+'/usersmanager/lists/1/'+$("#users_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_usersmanager_type()
{
    $(window.location).attr('href', root_url+'/usersmanager/lists/1/'+$("#users_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function change_usersmanager_number()
{
    $(window.location).attr('href', root_url+'/usersmanager/lists/1/'+$("#users_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function add_update_user()
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
    else document.forms["edit_user"].submit();   
    
    $('#type').val();
    
}

function jump(curElm, nextElm, length)
{
    if($("#"+curElm).val().length == length) $("#"+nextElm).focus();
}

function change_storesmanager_page()
{
    $(window.location).attr('href', root_url+'/storesmanager/lists/'+$("#stores_page").val()+'/'+$("#stores_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_storesmanager_page()
{
    $(window.location).attr('href', root_url+'/storesmanager/lists/1/'+$("#stores_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function sort_storesmanager_type()
{
    $(window.location).attr('href', root_url+'/storesmanager/lists/1/'+$("#stores_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function change_storesmanager_number()
{
    $(window.location).attr('href', root_url+'/storesmanager/lists/1/'+$("#stores_number").val()+'/'+$("#sort_page").val()+'/'+$("#sort_type").val());
}

function add_update_store()
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
    else document.forms["edit_store"].submit();   
    
    $('#type').val();
    
}
