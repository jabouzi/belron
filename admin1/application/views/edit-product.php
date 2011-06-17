<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit product"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_product" method="post" action="<?=base_url()."productsmanager/update/"; ?>" enctype="multipart/form-data">
            <?$checked = ""; if ($product[0]->active == '1') :?>
                <?$checked = "checked"?>
            <?endif?>
            <div>            
                <div class="Produit_liste_item"><label><?=gettext("Category")?> : </label><input type='text' id='category' name='category'  value="<?=$categories[$product[0]->category_id][$lang]?>"  readonly /></div>                    
                <div class="Produit_liste_item"><label><?=gettext("product id")?> : </label><input type='text' id='product_id' name='product_id' value="<?=$product[0]->id?>" readonly /></div>
                <div class="Produit_liste_item"><label><?=gettext("Brand")?> : </label>                                                   
                    <select id='brand' name='brand'>                            
                            <option value="Lebeau" <?if ('Lebeau' == $product[0]->brand) echo 'selected';?>>LeBeau</option>
                            <option value="Speedy" <?if ('Speedy' == $product[0]->brand) echo 'selected';?>>Speedy</option>
                            <option value="Generic" <?if ('Generic' == $product[0]->brand) echo 'selected';?>><?=gettext("Generic")?></option>
                    </select>
                </div>
                <div class="Produit_liste_item"><label><?=gettext("Name")?> : </label><input type='text' id='name' name='name' value="<?=$product[0]->name?>" /></div>            
                <div class="Produit_liste_item"><label><?=gettext("Language")?> : </label>                    
                    <select id='lang' name='lang'  >
                        <?foreach($language as $key => $lang) :?>
                            <?$selected = "";?>
                            <? if ($key == $product[0]->lang):?>
                                    <?$selected = "selected";?>
                            <?endif?>
                            <option value="<?=$key?>" <?=$selected?>><?=$lang?></option>
                        <?endforeach?>            
                    </select>
                </div>
                <div class="Produit_liste_item"><label><?=gettext("Description")?> : </label><input type='text' id='description' name='description' value="<?=$product[0]->description?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Dimension")?> : </label>
                    <select id='dimension' name='dimension'>
                        <?$dimensions = get_dimensions_text();?>
                        <?$dimensions_val = get_dimensions_val();?>
                        <?foreach($dimensions as $key => $dimension) :?>  
                            <?$selected = "";?>
                            <? if ($dimensions_val[$key] == $product[0]->dimension):?>
                                    <?$selected = "selected";?>
                            <?endif?>                          
                            <option value="<?=$dimensions_val[$key]?>" <?=$selected?>><?=$dimension?></option>
                        <?endforeach?>  
                    </select>
                </div>
                <div class="Produit_liste_item"><label><?=gettext("Print")?> : </label><input type='text' id='impression_materiel' name='impression_materiel' value="<?=$product[0]->impression_materiel?>" /></div>
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
                <div class="Produit_liste_item"><label><?=gettext("Active")?> : </label><input type="checkbox" value="1" id="active" name="active" <?=$checked?>/></div>
                <div class="Produit_liste_item"><label><?=gettext("Product photo")?> : </label><input name="product_photo" type="file" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Product vectoriel")?> : </label><input name="product_vectoriel" type="file" /></div>
                <div class="Produit_liste_item"><img src="<?=base_url().'public/images/products/250x381/'.$product[0]->id.'.jpg'?>"></div>
            <br/>
            <input type='hidden' id='id' name='id' value="<?=$product[0]->id?>" />
            <input type='hidden' id='language' name='language' value="<?=$lang?>" />            
            <div class="Produit_liste_item"><input type="button" class="commande" name="submit_update" value="<?=gettext("Update product")?>" onclick="add_update_product()" style="dipslay:none"/></div>
            </div>
        </form>
       <div class="Produit_liste_item"><input type="button" class="modalInput" rel="#request" value="<?=gettext("Request price revision")?>" /></div>
    </div>
</div>

<div class="modal2" id="request">
    <div id="Page_haut">
        <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
        <h1><?= gettext("Price revision"); ?></h1>
    </div>    
    <form id="edit_user" method="post" action="<?=base_url()?>productsmanager/request_price">
    <div id="liste_overflow">        
        <label><?=gettext("Type your request")?>: </label>
      <textarea name="price_request"></textarea>
      <input type='hidden' id='id' name='id' value="<?=$product[0]->id?>" />
    </div>
    <input type="submit" name="submit_update" value="<?=gettext("Submit request")?>"/>
    </form>
</div>
