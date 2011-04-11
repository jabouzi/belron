
<!-- open :: Body de la page -->
<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Access to your ressources")?></h1>
        </div>     
		<div class="float_left"><a href="<?=url::page('login/userlogin'); ?>"><img src="<?=url::base()?>public/styles/images/Landing_Tonik_<?=session::get('lang')?>.jpg" width="460" height="232" alt="" /></a></div>
		<div class="float_right"><a href="#"><img src="<?=url::base()?>public/styles/images/Landing_unisync_<?=session::get('lang')?>.jpg" width="460" height="232" alt="" /></a></div>
    </div>
</div>
