<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit store"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_store" method="post" action="<?=url::page("storesmanager/insert/"); ?>">
            <div>            
            <?$provinces = provinces();?>
            <div class="Produit_liste_item"><label><?=gettext("Store id")?> : </label><input type='text' id='store_id' name='store_id' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Name")?> : </label><input type='text' id='name' name='name' value=""  /></div>
            <div class="Produit_liste_item"><label><?=gettext("Address")?> : </label><input type='text' id='address' name='address' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("City")?> : </label><input type='text' id='city' name='city' value="" /></div>
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
            <div class="Produit_liste_item"><label><?=gettext("Postal code")?> : </label><input type='text' id='postal_code1' name='postal_code1' size="3" maxlength="3" value="" onkeyup="jump('postal_code1', 'postal_code2', '3')"/>
                                                                                         <input type='text' id='postal_code2' name='postal_code2' size="3" maxlength="3" value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Phone")?> : </label><input type='text' id='phone1' name='phone1' value="" size="3" maxlength="3" onkeyup="jump('phone1', 'phone2', '3')"/>
                                                                                   <input type='text' id='phone2' name='phone2' value="" size="3" maxlength="3" onkeyup="jump('phone2', 'phone3', '3')"/>
                                                                                   <input type='text' id='phone3' name='phone3' value="" size="4" maxlength="4" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Fax")?> : </label><input type='text' id='fax1' name='fax1' value="" size="3" maxlength="3" onkeyup="jump('fax1', 'fax2', '3')"/>
                                                                                   <input type='text' id='fax2' name='fax2' value="" size="3" maxlength="3" onkeyup="jump('fax2', 'fax3', '3')"/>
                                                                                   <input type='text' id='fax3' name='fax3' value="" size="4" maxlength="4" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Manager")?> : </label><input type='text' id='manager_name' name='manager_name' value="" /><a href="#" class="modalInput" rel="#managers"><?=gettext("Select a manager")?></a>  </div>
            <div class="Produit_liste_item"><label><?=gettext("Cart active")?> : </label><input type="checkbox" value="1" id="checkbox_cart_active" name="checkbox_cart_active"/></div>
            <br/>
            <input type='hidden' id='id' name='id' value="" />
            <input type='hidden' id='dm_id' name='dm_id' value="" />
            <input type='hidden' id='lang' name='lang' value='<?=session::get('lang')?>' />
            </div>
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Add store")?>" onclick="add_update_store()"/>
        </form>
    </div>
</div>

<div class="modal" id="managers">
    </label><select id="manager" name="manager">
        <?foreach($admins as $key => $admin) :?>                
            <option value="<?=$admin[0]->id?>" <?=$selected?>><?=utf8_encode($admin[0]->first_name)?> <?=utf8_encode($admin[0]->family_name)?></option>
        <?endforeach?>
    </select>
    <div><button type="button" class="close"><?=gettext("Ok")?></button></div>
</div>
