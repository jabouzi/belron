<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit user"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_user" method="post" action="<?=base_url()."usersmanager/update/"; ?>">
            <?$phone = deformat_phone($user[0]->phone);?>
            <?$pcode = deformat_postalcode($user[0]->postal_code);?>
            <?$provinces = provinces();?>
            <?$checked = ""; if ($user[0]->active == '1') :?>
                <?$checked = "checked"?>
            <?endif?>
            <div>            
            <div class="Produit_liste_item"><label><?=gettext("Position")?> : </label><input type='text' id='position' name='position' value="<?=$user[0]->position?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Last name")?> : </label><input type='text' id='family_name' name='family_name' value="<?=$user[0]->family_name?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("First name")?> : </label><input type='text' id='first_name' name='first_name' value="<?=$user[0]->first_name?>"  /></div>
            <div class="Produit_liste_item"><label><?=gettext("Address")?> : </label><input type='text' id='address' name='address' value="<?=$user[0]->address?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("City")?> : </label><input type='text' id='town' name='town' value="<?=$user[0]->town?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Province")?> : </label><input type="button" class="modalInput" rel="#provinces" value="<?=gettext("Add provinces to supervise")?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Postal code")?> : </label><input type='text' id='postal_code1' name='postal_code1' size="3" maxlength="3" value="<?=$pcode[0]?>" onkeyup="jump('postal_code1', 'postal_code2', '3')"/>
                                                                                         <input type='text' id='postal_code2' name='postal_code2' size="3" maxlength="3" value="<?=$pcode[1]?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Phone")?> : </label><input type='text' id='phone1' name='phone1' value="<?=$phone[0]?>" size="3" maxlength="3" onkeyup="jump('phone1', 'phone2', '3')"/>
                                                                                   <input type='text' id='phone2' name='phone2' value="<?=$phone[1]?>" size="3" maxlength="3" onkeyup="jump('phone2', 'phone3', '3')"/>
                                                                                   <input type='text' id='phone3' name='phone3' value="<?=$phone[2]?>" size="4" maxlength="4" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Email")?> : </label><input type='text' id='email' name='email' value="<?=$user[0]->email?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Password")?> : </label><input type='password' id='password' name='password' value="<?=$user[0]->family_name?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Active")?> : </label><input type="checkbox" value="1" id="active" name="active" <?=$checked?>/></div>
            <input type='hidden' id='id' name='id' value="<?=$user[0]->id?>" />
            <input type='hidden' id='lang' name='lang' value="<?=$lang?>" />
            </div>
            <input type="button" class="modalInput" rel="#permission" value="<?=gettext("Add managers to supervise")?>" />
            <input type="button" class="modalInput" rel="#stores" value="<?=gettext("Add stores to supervise")?>" />
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Update user infos")?>" onclick="add_update_user()"/>
            
            <div class="modal2" id="permission">
                <div id="Page_haut">
                    <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
                    <h1><?= gettext("Managers to supervise"); ?></h1>
                </div>                
                <div id="liste_overflow">                
                    <?foreach($users as $user_item):?>
                        <? if ($user_item->id != $user[0]->id):?>
                            <?$checked = '';?>
                            <?if (count($user_permissions)):?>
                                <?if (in_array($user_item->id,$user_permissions)):?>
                                    <?$checked = 'checked';?>
                                <?endif?>
                            <?endif?>
                            <div class="Produit_liste_item"><input type='text' id='<?=$user_item->id?>' value="<?=$user_item->first_name?> <?=$user_item->family_name?>" readonly />
                            <input type="checkbox" value="<?=$user_item->id?>" id="active" name="user-permissions[]" <?=$checked?>/></div>            
                        <?endif?>
                    <?endforeach?>
                <br/>
                </div>
                <input type="button" id="overlay_close" name="submit_update" value="<?=gettext("Add managers to supervise")?>" onclick="close_overlay()"/>
            </div>

            <div class="modal2" id="stores">
                <div id="Page_haut">
                    <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
                    <h1><?= gettext("Stores to supervise"); ?></h1>
                </div>    
                <div id="liste_overflow">       
                    <?foreach($stores as $store_item):?>
                        <?$checked = '';?>
                        <?if (count($store_permissions)):?>
                            <?if (in_array($store_item,$store_permissions)):?>
                                <?$checked = 'checked';?>
                            <?endif?>
                        <?endif?>
                        <div class="Produit_liste_item"><input type='text' id='<?=$store_item?>' value="<?=$store_item?>" readonly />
                        <input type="checkbox" value="<?=$store_item?>" id="active" name="store-permissions[]" <?=$checked?>/></div>            
                    <?endforeach?>
                <br/>
                </div>
                <input type="button" id="overlay_close" name="submit_update" value="<?=gettext("Add stores to supervise")?>" onclick="close_overlay()"/>
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
            
        </form>
    </div>
</div>
