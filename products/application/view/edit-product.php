<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit product"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_product" method="post" action="<?=url::page("productsmanager/update/"); ?>">
            <div>            
                <div class="Produit_liste_item"><label><?=gettext("Category")?> : </label><input type='text' id='category' name='category'  value="<?=$categories[$product[0]->category_id][session::get('lang')]?>"  readonly /></div>                    
                <div class="Produit_liste_item"><label><?=gettext("Brand")?> : </label><input type='text' id='brand' name='brand'  value="<?=$product[0]->brand?>"  readonly /></div>                    
                <div class="Produit_liste_item"><label><?=gettext("product id")?> : </label><input type='text' id='product_id' name='product_id' value="<?=$product[0]->id?>" readonly /></div>
                <div class="Produit_liste_item"><label><?=gettext("Name")?> : </label><input type='text' id='name' name='name' value="<?=utf8_encode($product[0]->name)?>" /></div>            
                <div class="Produit_liste_item"><label><?=gettext("Language")?> : </label>                    
                    <select id='language' name='language'  >
                        <?foreach($language as $key => $lang) :?>
                            <?$selected = "";?>
                            <? if ($key == $product[0]->lang):?>
                                    <?$selected = "selected";?>
                            <?endif?>
                            <option value="<?=$key?>" <?=$selected?>><?=$lang?></option>
                        <?endforeach?>            
                    </select>
                </div>
                <div class="Produit_liste_item"><label><?=gettext("Description")?> : </label><input type='text' id='description' name='description' value="<?=utf8_encode($product[0]->description)?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Dimension")?> : </label><input type='text' id='dimension' name='dimension' value="<?=$product[0]->dimension?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Impression materiel")?> : </label><input type='text' id='impression_materiel' name='impression_materiel' value="<?=utf8_encode($product[0]->impression_materiel)?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 1")?> : </label><input type='text' id='prix_pour_1' name='prix_pour_1' value="<?=$product[0]->prix_pour_1?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 2")?> : </label><input type='text' id='prix_pour_2' name='prix_pour_2' value="<?=$product[0]->prix_pour_2?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 3")?> : </label><input type='text' id='prix_pour_3' name='prix_pour_3' value="<?=$product[0]->prix_pour_3?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 4")?> : </label><input type='text' id='prix_pour_4' name='prix_pour_4' value="<?=$product[0]->prix_pour_4?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 5")?> : </label><input type='text' id='prix_pour_5' name='prix_pour_5' value="<?=$product[0]->prix_pour_5?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 20")?> : </label><input type='text' id='prix_pour_20'  name='prix_pour_20' value="<?=$product[0]->prix_pour_20?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 50")?> : </label><input type='text' id='prix_pour_50' name='prix_pour_50'  value="<?=$product[0]->prix_pour_50?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 100")?> : </label><input type='text' id='prix_pour_100' name='prix_pour_100' value="<?=$product[0]->prix_pour_100?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 150")?> : </label><input type='text' id='prix_pour_150' name='prix_pour_150' value="<?=$product[0]->prix_pour_150?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 250")?> : </label><input type='text' id='prix_pour_250' name='prix_pour_250' value="<?=$product[0]->prix_pour_250?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 500")?> : </label><input type='text' id='prix_pour_500' name='prix_pour_500' value="<?=$product[0]->prix_pour_500?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Prix pour 1000")?> : </label><input type='text' id='prix_pour_1000' name='prix_pour_1000' value="<?=$product[0]->prix_pour_1000?>" /></div>
            <br/>
            <input type='hidden' id='id' name='id' value="<?=$product[0]->id?>" />
            <input type='hidden' id='lang' name='lang' value="<?=session::get('lang')?>" />
            </div>
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Update product")?>" onclick="add_update_product()"/>
        </form>
    </div>
</div>
