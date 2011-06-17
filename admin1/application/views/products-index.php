<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("products list"); ?></h1>
        </div>    
        
        <div>
        <?if ($total > 1):?>
            <label><?=gettext("Page number")?></label>
            <select id="products_page" onchange="change_productsmanager_page()">
                <?for($t = 1; $t <= $total; $t++) :?>
                    <?$selected = "";?>
                    <? if ($t == $current):?>
                            <?$selected = "selected";?>
                    <?endif?>
                    <option value="<?=$t?>" <?=$selected?>><?=$t?></option>
                <?endfor?>
            </select>
        <?else :?>
            <input type="hidden" id="products_page" value="1"/>
        <?endif?>
        
        <label><?=gettext("Sort by")?></label>
        <?$items = get_product_items()?>
        <select id="sort_page" onchange="sort_productsmanager_page()">
            <?foreach($items as $key => $item) :?>
                <?$selected = "";?>
                <? if ($key == $sort):?>
                        <?$selected = "selected";?>
                <?endif?>
                <option value="<?=$key?>" <?=$selected?>><?=gettext($item)?></option>
            <?endforeach?>
        </select>
        
        <label><?=gettext("Sort product")?></label>
        <?$selected = "";?>
        <? if ($type == 0):?>
                <?$selected0 = "selected";?>
                <?$selected1 = "";?>
        <? else:?>
                <?$selected0 = "";?>
                <?$selected1 = "selected";?>
        <?endif?>
        <select id="sort_type" onchange="sort_productsmanager_type()">            
                <option value="0" <?=$selected0?>><?=gettext("DESC")?></option>
                <option value="1" <?=$selected1?>><?=gettext("ASC")?></option>
        </select>        
         
        <label><?=gettext("Display count")?></label>
        <select id="products_number" onchange="change_productsmanager_number()">
            <?for($i = 0; $i < count($numbers); $i++) :?>
                <?$selected = "";?>
                <? if ($number == $numbers[$i]):?>
                        <?$selected = "selected";?>
                <?endif?>
                <option value="<?=$numbers[$i]?>" <?=$selected?>><?=gettext($numbers[$i])?></option>
            <?endfor?>            
        </select>
        </div>
        
        
        <br />
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=base_url()."productsmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$type}/"; ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=base_url()."productsmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$type}/"; ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
        
        <table id="products_table" width="100%" border="0" cellspacing="5" cellpadding="2" class="tablesorter table_commande">
            <thead>
                <tr>
                    <th style="cursor:pointer;" scope="col"><?=gettext("product id")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Category")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Brand")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Name")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Language")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Description")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Dimension")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Print")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Active")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Creation date")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Modification date")?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($products as $key => $product):?>
                    <? if (array_key_exists($product->category_id,$categories)) :?>
                        <?$ids[] = $product->id;?>                
                        <?$tr_class = 'odd'?>
                            <?if ($key % 2) $tr_class = 'even'?>
                        <tr class="<?=$tr_class?>">
                            <td><?=$product->id?></td>
                            <td><?=$categories[$product->category_id][$lang]?></td>           
                            <td><?=$product->brand?></td>               
                            <td><?=$product->name?></td>               
                            <td><?=$product->lang?></td>               
                            <td><?=$product->description?></td>             
                            <td><?=$dimensions[$product->dimension]?></td>
                            <td><?=$product->impression_materiel?></td>
                            <?$active = 'Not active'; if ($product->active):?>
                                <?$active = 'Active';?>
                            <?endif?>
                            <td><?=$active?></td> 
                            <td><?=$product->date_created?></td>
                            <td><?=$product->date_modif?></td>
                            <td><a href="<?=base_url()."productsmanager/edit/{$product->id}/"?>"><?=gettext("Edit")?></a></td>
                        </tr>
                    <?endif?>
                <?endforeach?>
            </tbody>
        </table>
        
        <input type="button" id="rem_products" onclick='remove_products(<?=json_encode($ids)?>)' value="<?=gettext("Remove products")?>" />
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=base_url()."productsmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$current}/"; ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=base_url()."productsmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$current}/"; ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
    </div>
</div>
