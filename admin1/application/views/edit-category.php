<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Edit category"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <form id="edit_product" method="post" action="<?=base_url()."productsmanager/update_category/"; ?>">
            <?$checked = ""; if ($category[0]->active == '1') :?>
                <?$checked = "checked"?>
            <?endif?>
            <div>                
                <div class="Produit_liste_item"><label><?=gettext("Name fr")?> : </label><input type='text' id='name_fr' name='name_fr' value="<?=$category[0]->name_fr?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Name en")?> : </label><input type='text' id='name_en' name='name_en' value="<?=$category[0]->name_en?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Image")?> : </label><input type='text' id='image_file' name='image_file' value="<?=$category[0]->image_file?>" /></div>
                <div class="Produit_liste_item"><label><?=gettext("Active")?> : </label><input type="checkbox" value="1" id="active" name="active" <?=$checked?>/></div>
            <br/>
            <input type='hidden' id='id' name='id' value="<?=$category[0]->id?>" />
            </div>
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Update category")?>" onclick="add_update_category()"/>
        </form>
    </div>
</div>
