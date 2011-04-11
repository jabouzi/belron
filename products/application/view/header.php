<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=header("Cache-Control: no-cache, must-revalidate")?>
<title>BERLRON CANADA</title>

<script type="text/javascript" src="<?=url::base()?>public/scripts/jquery.js"></script>
<script type="text/javascript" src="<?=url::base()?>public/scripts/jquery-tools-min.js"></script>
<script type="text/javascript" src="<?=url::base()?>public/scripts/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=url::base()?>public/scripts/belron.js"></script>
<link href="<?=url::base()?>public/styles/style.css" rel="stylesheet" type="text/css" />

</head>

<?$redirect_url = url::get_redirect_url();?>
<?$home_url = url::get_home();?>
<?url::change_language();?>
<? $user = session::get('user'); ?>
<body>
<div id="header_full">
    <div id="header_960">
        <div id="logo"><img src="<?=url::base()?>public/styles/images/logo.gif" alt="" border="0" /></div>
        
        <div id="header_menu">                          
                <? if (!$user):?>
                <a href="#" class="modalInput" rel="#login"><?=gettext("Login")?></a>  
                <? endif?>
                <? if ($user):?>    
                <a href="<?=HOME_URL?>admin/admin_index"><?=gettext("Home")?></a>
                <a href="<?=url::page('productsmanager/logout')?>"><?=gettext("Logout")?></a>
                <a href="<?=url::page('productsmanager/add')?>"><?=gettext("Add")?></a>            
                <a href="<?=url::page('productsmanager/lists')?>"><?=gettext("List")?></a>       
                <? endif?>     
            <form name="form" method="post" action="<?=url::page('productsmanager/change_language')?>">
            <select name="lang" onchange="form.submit();">
                <option value='en' <? if (session::get('lang') == 'en'){echo 'selected';}?>><?=gettext("English")?></option>
                <option value='fr' <? if (session::get('lang') == 'fr'){echo 'selected';}?>><?=gettext("French")?></option>
            </select>    
            <?if ($redirect_url[1] != 'productsmanager/change_language'):?>
                <input type="hidden" name="redirect_url" value="<?=$redirect_url[1]?>" />
            <?endif?>
            </form>
        </div>
    </div>
</div>

