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
        <?$items = get_sort_items()?>
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
                <a href="<?=url::page("productsmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$type}/"); ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=url::page("productsmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$type}/"); ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
        
        <table id="products_table" width="100%" border="0" cellspacing="5" cellpadding="2" class="tablesorter table_commande">
            <thead>
                <tr>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Remove")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("product id")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Group id")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Category")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Name")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Language")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Description")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Dimension")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Impression materiel")?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($products as $key => $product):?>
                    <?$ids[] = $product->id;?>                
                    <?$tr_class = 'odd'?>
                        <?if ($key % 2) $tr_class = 'even'?>
                    <tr class="<?=$tr_class?>">
                        <td><input type="checkbox" value="<?=$product->id?>" id="checkbox_<?=$product->id?>" name="checkbox_supprim[]" /></td>
                        <td><?=utf8_encode($product->id)?></td>
                        <td><?=utf8_encode($product->group_id)?></td>
                        <td><?=utf8_encode($categories[$product->category_id][session::get('lang')])?></td>           
                        <td><?=utf8_encode($product->name)?></td>               
                        <td><?=utf8_encode($product->lang)?></td>               
                        <td><?=utf8_encode($product->description)?></td>             
                        <td><?=utf8_encode($product->dimension)?></td>
                        <td><?=utf8_encode($product->impression_materiel)?></td>
                        <td><a href="<?=url::page("productsmanager/edit/{$product->id}/")?>"><?=gettext("Edit")?></a></td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
        
        <input type="button" id="rem_products" onclick='remove_products(<?=json_encode($ids)?>)' value="<?=gettext("Remove products")?>" />
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=url::page("productsmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$current}/"); ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=url::page("productsmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$current}/"); ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
    </div>
</div>
