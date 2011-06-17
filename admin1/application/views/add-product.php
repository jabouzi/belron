<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Add product"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_product" method="post" action="<?=base_url()."productsmanager/insert/"; ?>" enctype="multipart/form-data">
            <div>
                <div class="Produit_liste_item"><label><?=gettext("Category")?> : </label>                    
                    <select id='category' name='category' onChange="show_form()">
                        <option value="0"><?=gettext("Select category")?></option>
                        <?foreach($categories as $key => $categorie) :?>                            
                            <option value="<?=$key?>"><?=$categorie[$lang]?></option>
                        <?endforeach?>            
                    </select>
                </div>             
                <!--<span id="product_span" style="display:none">-->
                <div class="Produit_liste_item"><label><?=gettext("Brand")?> : </label>
                    <select id='brand' name='brand' >                            
                            <option value="Lebeau">LeBeau</option>
                            <option value="Speedy">Speedy</option>
                            <option value="Generic"><?=gettext("Generic")?></option>
                    </select>
                </div>
                </div>                    
                <div class="Produit_liste_item"><label><?=gettext("product id")?> : </label><input type='text' id='product_id' name='product_id' value="" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Name")?> : </label><input type='text' id='name' name='name' value="" /></div>            
                <div class="Produit_liste_item"><label><?=gettext("Language")?> : </label>                    
                    <select id='lang' name='lang'  >
                        <?foreach($language as $key => $lang) :?>                            
                            <option value="<?=$key?>"><?=$lang?></option>
                        <?endforeach?>            
                    </select>
                </div>
                <div class="Produit_liste_item"><label><?=gettext("Description")?> : </label><input type='text' id='description' name='description' value="" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Dimension")?> : </label>
                <select id='dimension' name='dimension'>
                    <?$dimensions = get_prod_dimensions();?>
                    <?foreach($dimensions as $dimension) :?>                            
                        <option value="<?=$dimension?>"><?=$dimension?></option>
                    <?endforeach?>  
                </select>
                </div>
                <div class="Produit_liste_item"><label><?=gettext("Print")?> : </label><input type='text' id='impression_materiel' name='impression_materiel' value="" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Product photo")?> : </label><input name="product_photo" type="file" /><br />
                <div class="Produit_liste_item"><label><?=gettext("Product vectoriel")?> : </label><input name="product_vectoriel" type="file" /><br />
                <!--</span>-->  
            <br/>
            <input type='hidden' id='language' name='language' value='<?=$lang?>' />
            </div>
            <input type="button" class="commande" name="submit_update" id="product_add_submit" value="<?=gettext("Add product")?>" onclick="add_update_product()"/>
            
        </form>
    </div>
</div>
