<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit user"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_user" method="post" action="<?=url::page("usersmanager/update/"); ?>">
            <div>            
            <div class="Produit_liste_item">         
            <?$items = get_positions()?>
            <label><?=gettext("Position")?> : </label>
            <select id="position" name="position">
                <?foreach($items as $key => $item) :?>  
                    <?$selected = "";?>
                    <? if ($user[0]->position == $item) :?>
                            <?$selected = "selected";?>
                    <?endif?>                  
                    <option value="<?=$key?>" <?=$selected?>><?=$item?></option>
                <?endforeach?>
            </select>
            </div>
            <?$phone = deformat_phone($user[0]->phone);?>
            <?$pcode = deformat_postalcode($user[0]->postal_code);?>
            <?$provinces = provinces();?>
            <div class="Produit_liste_item"><label><?=gettext("Last name")?> : </label><input type='text' id='family_name' name='family_name' value="<?=utf8_encode($user[0]->family_name)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("First name")?> : </label><input type='text' id='first_name' name='first_name' value="<?=utf8_encode($user[0]->first_name)?>"  /></div>
            <div class="Produit_liste_item"><label><?=gettext("Address")?> : </label><input type='text' id='address' name='address' value="<?=utf8_encode($user[0]->address)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("City")?> : </label><input type='text' id='town' name='town' value="<?=utf8_encode($user[0]->town)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Province")?> : 
            </label><select id="province" name="province">
                <?foreach($provinces as $key => $province) :?>     
                    <?$selected = "";?>
                    <? if ($user[0]->province == $key) :?>
                            <?$selected = "selected";?>
                    <?endif?>                
                    <option value="<?=$key?>" <?=$selected?>><?=gettext($province)?></option>
                <?endforeach?>
            </select></div>
            <div class="Produit_liste_item"><label><?=gettext("Postal code")?> : </label><input type='text' id='postal_code1' name='postal_code1' size="3" maxlength="3" value="<?=$pcode[0]?>" onkeyup="jump('postal_code1', 'postal_code2', '3')"/>
                                                                                         <input type='text' id='postal_code2' name='postal_code2' size="3" maxlength="3" value="<?=$pcode[1]?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Phone")?> : </label><input type='text' id='phone1' name='phone1' value="<?=$phone[0]?>" size="3" maxlength="3" onkeyup="jump('phone1', 'phone2', '3')"/>
                                                                                   <input type='text' id='phone2' name='phone2' value="<?=$phone[1]?>" size="3" maxlength="3" onkeyup="jump('phone2', 'phone3', '3')"/>
                                                                                   <input type='text' id='phone3' name='phone3' value="<?=$phone[2]?>" size="4" maxlength="4" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Email")?> : </label><input type='text' id='email' name='email' value="<?=utf8_encode($user[0]->email)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Password")?> : </label><input type='password' id='password' name='password' value="<?=$user[0]->password?>" /></div>
            <input type='hidden' id='id' name='id' value="<?=$user[0]->id?>" />
            <input type='hidden' id='lang' name='lang' value="<?=session::get('lang')?>" />
            </div>
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Update user")?>" onclick="add_update_user()"/>
        </form>
    </div>
</div>
