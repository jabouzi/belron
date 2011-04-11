<div id="footer_full">
  <div id="footer_960">
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
</body>
</html>
