<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit store"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_store" method="post" action="<?=url::page("storesmanager/update/"); ?>">
            <div>            
            <?$phone = deformat_phone($store[0]->phone);?>
            <?$fax = deformat_phone($store[0]->fax);?>
            <?$pcode = deformat_postalcode($store[0]->postal_code);?>
            <?$provinces = provinces();?>
            <?$checked = ""; if ($store[0]->cart_active == '1') :?>
                <?$checked = "checked"?>
            <?endif?>
            <div class="Produit_liste_item"><label><?=gettext("Store id")?> : </label><input type='text' id='store_id' name='store_id' value="<?=utf8_encode($store[0]->store_id)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Name")?> : </label><input type='text' id='name' name='name' value="<?=utf8_encode($store[0]->name)?>"  /></div>
            <div class="Produit_liste_item"><label><?=gettext("Address")?> : </label><input type='text' id='address' name='address' value="<?=utf8_encode($store[0]->address)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("City")?> : </label><input type='text' id='city' name='city' value="<?=utf8_encode($store[0]->city)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Province")?> : 
            </label><select id="province" name="province">
                <?foreach($provinces as $key => $province) :?>     
                    <?$selected = "";?>
                    <? if ($store[0]->province == $key) :?>
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
            <div class="Produit_liste_item"><label><?=gettext("Fax")?> : </label><input type='text' id='fax1' name='fax1' value="<?=$fax[0]?>" size="3" maxlength="3" onkeyup="jump('fax1', 'fax2', '3')"/>
                                                                                   <input type='text' id='fax2' name='fax2' value="<?=$fax[1]?>" size="3" maxlength="3" onkeyup="jump('fax2', 'fax3', '3')"/>
                                                                                   <input type='text' id='fax3' name='fax3' value="<?=$fax[2]?>" size="4" maxlength="4" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Manager")?> : </label><input type='text' id='manager_name' name='manager_name' value="<?=utf8_encode($store[0]->manager_or_owner)?>" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Cart active")?> : </label><input type="checkbox" value="1" id="checkbox_cart_active" name="checkbox_cart_active" <?=$checked?>/></div>
            <br/>
            <input type='hidden' id='id' name='id' value="<?=$store[0]->id?>" />
            <input type='hidden' id='lang' name='lang' value="<?=session::get('lang')?>" />
            </div>
            <input type="button" class="modalInput" rel="#supervisers" value="<?=gettext("Add supervisers")?>" />
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Update store")?>" onclick="add_update_store()"/>
            
            <div class="modal" id="supervisers">
                <div id="Page_haut">
                    <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
                    <h1><?= gettext("Add supervisers"); ?></h1>
                </div>                
                <div>                
                    <?foreach($admins as $admin):?>
                        <? if ($admin[0]->id != $user[0]->id):?>
                            <?$checked = '';?>
                            <?if (in_array($admin[0]->id,$admin_permissions)):?>
                                <?$checked = 'checked';?>
                            <?endif?>
                            <div class="Produit_liste_item"><input type='text' id='<?=$admin[0]->id?>' value="<?=utf8_encode($admin[0]->first_name)?> <?=utf8_encode($admin[0]->family_name)?>" readonly />
                            <input type="checkbox" value="<?=$admin[0]->id?>" id="active" name="user-permissions[]" <?=$checked?>/></div>            
                        <?endif?>
                    <?endforeach?>
                <br/>
                </div>
                <input type="button" class="close" name="submit_update" value="<?=gettext("Add supervisers")?>" />
            </div>
            
        </form>
    </div>
</div>
