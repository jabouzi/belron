<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Speedy ordering website")?></h1>
        </div>     
		<div class="float_left"><a href="http://php2.groupimage.net/belron_pp/access/admin/dashboard/"><img src="<?=url::base()?>public/styles/images/admin_choice.gif" width="460" height="232" alt="" /></a></div>
		<div class="float_right"><a href="http://php2.groupimage.net/belron_pp/users/"><img src="<?=url::base()?>public/styles/images/admin_users_choice.gif" width="460" height="232" alt="" /></a></div>
		<div class="float_right"><a href="http://php2.groupimage.net/belron_pp/products/"><img src="<?=url::base()?>public/styles/images/admin_products_choice.gif" width="460" height="232" alt="" /></a></div>
    </div>
</div>

<div class="modal" id="login">
    <h2><?=gettext("Login form")?></h2>
    <br />
    <div id="error"></div>
    <p>
      <?=gettext("To close it click a button or use the ESC key.")?>
    </p>
    <br />

   <form id="login_form">
      <div><label><?=gettext("Username")?>: </label><input type="text" name="uname" id="uname" required="required"/><br /></div>
      <div><label><?=gettext("Password")?>: </label><input type="password" name="pwd" id="pwd" required="required"/><br /></div>
      <br />
      <div><button type="button"><?=gettext("Login")?></button>
      <button type="button" class="close"><?=gettext("Cancel")?></button></div>
   </form>
   <br />
</div>
