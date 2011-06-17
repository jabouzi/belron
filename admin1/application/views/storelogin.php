<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
              <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
              <h1><?=gettext("Welcome")?> </h1>
        </div>        
        <div id="Collone1">
        <img src="<?=base_url()?>public/styles/images/img_accueil.jpg" alt="" class="accueil" />
            <p><?//=gettext("Intoduction")?></p>            
        </div>      
        <div id="error">
        <?if (isset($errors)):?>
            <?foreach($errors as $error):?>
                <?=$error.'<br />'?>
            <?endforeach?>
        <?endif?>          
        </div>   
        <div id="Collone2">            
        <h2><?=gettext("Store")?></h2>
            <div class="Boite" id="Magasin">
                <form id="form_login" action="<?=base_url()?>login/storeloginaction" method="post">
                    <label><?=gettext("Store")?> :</label> 
                        <select name="store_name">
                            <option value=0><?=gettext("Select a store")?></option>';
                            <?foreach($stores as $store) :?>
                                <?$selected = "";?>
                                <? if ($store_selected == $store->name) :?>
                                        <?$selected = "selected";?>
                                <?endif?>
                                <option value="<?=$store->name?>" <?=$selected?>><?=$store->name?></option>
                            <?endforeach?>
                        </select>
                       <label><?=gettext("Password")?> : </label>
                       <input name="password" type="password" />
                       <input type="submit" name="Soumettre" id="Soumettre" value="<?=gettext("Submit")?>" />
                </form> 
                <br />                   
                <div>
                    <label><a href="<?=base_url()?>login/userlogin"><?=gettext("Admin")?></a></label>
                    <label><a href="<?=base_url()?>login"><?=gettext("Home")?></a></label>
                </div> 
            </div>
        </div>   
    </div> 
</div>
