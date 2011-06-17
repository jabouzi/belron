<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=header("Cache-Control: no-cache, must-revalidate")?>
<title>BELRON CANADA</title>

<script type="text/javascript" src="<?=base_url()?>public/scripts/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>public/scripts/belron.js"></script>
<script type="text/javascript" src="<?=base_url()?>public/scripts/jquery.tablesorter.js"></script>
<script src="<?=base_url()?>public/scripts/jquery-tools-min.js"></script>
<link href="<?=base_url()?>public/styles/style.css" rel="stylesheet" type="text/css" />

</head>
<?$redirect_url = get_redirect_url();?>
<?change_language($lang);?>
<body>
<div id="header_full">
    <div id="header_960">        
            <div id="logo"><img src="<?=base_url()?>public/styles/images/logo.gif" alt="" border="0" /></div>        
        
        <div id="header_menu">
            <div id="welcom_name"><?=gettext("Welcome")?> <span><?=$this->session->userdata['name']?></span></div>
            <a href="<?=ADMIN?>"><?=gettext("Home")?></a>                    
            <a href="<?=base_url()?>admin/logout"><?=gettext("Logout")?></a>                    
            <form name="form" method="post" action="<?=base_url()?>admin/change_language">
            <select name="lang" onchange="form.submit();">
                <option value='en' <? if ($lang == 'en'){echo 'selected';}?>><?=gettext("English")?></option>
                <option value='fr' <? if ($lang == 'fr'){echo 'selected';}?>><?=gettext("French")?></option>
            </select>    
            <?if ($redirect_url[1] != base_url().'admin/change_language'):?>
                <input type="hidden" name="redirect_url" value="<?=$redirect_url[1]?>" />
            <?endif?>
            </form>
        </div>
    </div>
</div>

