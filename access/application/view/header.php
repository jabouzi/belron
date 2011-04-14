<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=header("Cache-Control: no-cache, must-revalidate")?>
<title>BERLRON CANADA</title>

<script type="text/javascript" src="<?=url::base()?>public/scripts/jquery.js"></script>
<script type="text/javascript" src="<?=url::base()?>public/scripts/belron.js"></script>
<script src="<?=url::base()?>public/scripts/jquery-tools-min.js"></script>
<link href="<?=url::base()?>public/styles/style.css" rel="stylesheet" type="text/css" />

</head>

<?$redirect_url = url::get_redirect_url();?>
<?$home_url = url::get_home();?>
<?url::change_language();?>
<body>
<div id="header_full">
    <div id="header_960">
        <? if (session::get('store_type') == 'Lebeau'):?>
            <div id="logo"><img src="<?=url::base()?>public/styles/images/Lebeau_Logo.gif" alt="" border="0" /></div>
        <? elseif (session::get('store_type') == 'Speedy'):?>
            <div id="logo"><img src="<?=url::base()?>public/styles/images/Logo_Speedy.gif" alt="" border="0" /></div>
        <?else:?>
            <div id="logo"><img src="<?=url::base()?>public/styles/images/logo.gif" alt="" border="0" /></div>
        <?endif?>
        
        <div id="header_menu">
            <? $user = session::get('user'); ?>
            <? $usertype = session::get('user_type'); ?>            
            <? $hasalist = session::get('wishlist'); ?>            
            <? if ($user):?>
                <? if ($usertype == 1):?>
                    <a href="<?=url::page('admin/admin_index')?>"><?=gettext("Home")?></a>
                    <a href="<?=url::page('admin/make_order')?>"><?=gettext("Make an order")?></a>
                    <a class="modalInput" rel="#login"  href="#"><?=gettext("Admin")?></a>
                <? elseif ($usertype == 2):?>
                    <a href="<?=url::page('orders/lists/0')?>"><?=gettext("Home")?></a>
                    <a href="<?=url::page('orders/make_order')?>"><?=gettext("Make an order")?></a>
                <? else:?>                    
                    <?if ($hasalist):?>
                        <a href="<?=url::page('wishlist/confirm')?>"><?=gettext("My order")?></a>
                    <?endif?>
                    <a href="<?=url::page('categories')?>"><?=gettext("Categories")?></a>
                    <a href="<?=url::page('orders/historique')?>"><?=gettext("History")?></a>
                <?endif?>                
                <a href="<?=url::page('login/logout')?>"><?=gettext("Logout")?></a>
            <?else:?>
                <?if ($admin):?>
                    <a href="<?=url::page('login/userlogin')?>"><?=gettext("Admin")?></a>
                <?endif?>
            <?endif?>
            <form name="form" method="post" action="<?=url::page('admin/change_language')?>">
            <select name="lang" onchange="form.submit();">
                <option value='en' <? if (session::get('lang') == 'en'){echo 'selected';}?>><?=gettext("English")?></option>
                <option value='fr' <? if (session::get('lang') == 'fr'){echo 'selected';}?>><?=gettext("French")?></option>
            </select>    
            <?if ($redirect_url[1] != 'admin/change_language'):?>
                <input type="hidden" name="redirect_url" value="<?=$redirect_url[1]?>" />
            <?endif?>
            </form>
        </div>
    </div>
</div>

