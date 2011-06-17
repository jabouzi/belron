<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Add user"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_user" method="post" action="<?=base_url()."usersmanager/insert/"; ?>">
            <?$provinces = provinces();?>
            <div>            
            <div class="Produit_liste_item"><label><?=gettext("Position")?> : </label><input type='text' id='position' name='position' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Last name")?> : </label><input type='text' id='family_name' name='family_name' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("First name")?> : </label><input type='text' id='first_name' name='first_name' value=""  /></div>
            <div class="Produit_liste_item"><label><?=gettext("Address")?> : </label><input type='text' id='address' name='address' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("City")?> : </label><input type='text' id='town' name='town' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Province")?> : </label><input type="button" class="modalInput" rel="#provinces" value="<?=gettext("Add provinces to supervise")?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Postal code")?> : </label><input type='text' id='postal_code1' name='postal_code1' size="3" maxlength="3" value="" onkeyup="jump('postal_code1', 'postal_code2', '3')"/>
                                                                                         <input type='text' id='postal_code2' name='postal_code2' size="3" maxlength="3" value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Phone")?> : </label><input type='text' id='phone1' name='phone1' value="" size="3" maxlength="3" onkeyup="jump('phone1', 'phone2', '3')"/>
                                                                                   <input type='text' id='phone2' name='phone2' value="" size="3" maxlength="3" onkeyup="jump('phone2', 'phone3', '3')"/>
                                                                                   <input type='text' id='phone3' name='phone3' value="" size="4" maxlength="4" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Email")?> : </label><input type='text' id='email' name='email' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Password")?> : </label><input type='password' id='password' name='password' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Active")?> : </label><input type="checkbox" value="1" id="active" name="active"/></div>
            <input type='hidden' id='lang' name='lang' value='<?=$lang?>' />
            </div>            
            
            <div class="modal2" id="provinces">
                <div id="Page_haut">
                    <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
                    <h1><?= gettext("Provinces to supervise"); ?></h1>
                </div>    
                <div id="liste_overflow">   
                    <?foreach($provinces as $key => $province):?>
                        <?$checked = '';?>
                        <?if (count($user_provinces)):?>
                            <?if (in_array($key,$user_provinces)):?>
                                <?$checked = 'checked';?>
                            <?endif?>
                        <?endif?>
                        <div class="Produit_liste_item"><input type='text' id='<?=$key?>' value="<?=$province?>" readonly />
                        <input type="checkbox" value="<?=$key?>" id="active" name="user_provinces[]" <?=$checked?>/></div>            
                    <?endforeach?>
                <br/>
                </div>
                <input type="button" id="overlay_close" name="submit_update" value="<?=gettext("Add provinces to supervise")?>" onclick="close_overlay()"/>
            </div>
            
            <input type="button" class="commande" name="submit_add" value="<?=gettext("Save user infos")?>" onclick="add_update_user()"/>
        </form>
    </div>
</div>
