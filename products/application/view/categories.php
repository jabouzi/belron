<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Categories list"); ?></h1>
        </div>
        <div id="error">               
        </div>   
        <table id="products_table" width="100%" border="0" cellspacing="5" cellpadding="2" class="tablesorter table_commande">
            <thead>
                <tr>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Category id")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Name fr")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Name en")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Image file")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Active")?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($categories as $key => $category):?>
                    <?$ids[] = $category->id;?>                
                    <?$tr_class = 'odd'?>
                        <?if ($key % 2) $tr_class = 'even'?>
                    <tr class="<?=$tr_class?>">
                        <td><?=utf8_encode($category->id)?></td>
                        <td><?=utf8_encode($category->name_fr)?></td>         
                        <td><?=utf8_encode($category->name_en)?></td>               
                        <td><?=utf8_encode($category->image_file)?></td>               
                        <?$active = 'Not active'; if ($category->active):?>
                            <?$active = 'Active';?>
                        <?endif?>
                        <td><?=$active?></td>   
                        <td><a href="<?=url::page("productsmanager/edit_category/{$category->id}/")?>"><?=gettext("Edit")?></a></td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
        <input type="button" class="modalInput" rel="#add-category" value="<?=gettext("Add category")?>" />
    </div>
</div>

<div class="modal" id="add-category">
    <div id="Page_haut">
        <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
        <h1><?= gettext("Add category"); ?></h1>
    </div>
    <form id="edit_product" method="post" action="<?=url::page("productsmanager/add_category/"); ?>">        
        <div>                
            <div class="Produit_liste_item"><label><?=gettext("Name fr")?> : </label><input type='text' id='name_fr' name='name_fr' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Name en")?> : </label><input type='text' id='name_en' name='name_en' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Image")?> : </label><input type='text' id='image_file' name='image_file' value="" /></div>
            <div class="Produit_liste_item"><label><?=gettext("Active")?> : </label><input type="checkbox" value="1" id="active" name="active" /></div>
        <br/>
        </div>
        <input type="button" class="commande" name="submit_update" value="<?=gettext("Add category")?>" onclick="add_update_category()"/>
    </form>    
</div>
