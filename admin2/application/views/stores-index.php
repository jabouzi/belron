<?//var_dump($stores);exit;?>

<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Stores list"); ?></h1>
        </div>    
        
        <div>
        <?if ($total > 1):?>
            <label><?=gettext("Page number")?></label>
            <select id="stores_page" onchange="change_storesmanager_page()">
                <?for($t = 1; $t <= $total; $t++) :?>
                    <?$selected = "";?>
                    <? if ($t == $current):?>
                            <?$selected = "selected";?>
                    <?endif?>
                    <option value="<?=$t?>" <?=$selected?>><?=$t?></option>
                <?endfor?>
            </select>
        <?else :?>
            <input type="hidden" id="stores_page" value="1"/>
        <?endif?>
        
        <label><?=gettext("Sort by")?></label>
        <?$items = get_sort_items()?>
        <select id="sort_page" onchange="sort_storesmanager_page()">
            <?foreach($items as $key => $item) :?>
                <?$selected = "";?>
                <? if ($key == $sort):?>
                        <?$selected = "selected";?>
                <?endif?>
                <option value="<?=$key?>" <?=$selected?>><?=gettext($item)?></option>
            <?endforeach?>
        </select>
        
        <label><?=gettext("Sort store")?></label>
        <?$selected = "";?>
        <? if ($type == 0):?>
                <?$selected0 = "selected";?>
                <?$selected1 = "";?>
        <? else:?>
                <?$selected0 = "";?>
                <?$selected1 = "selected";?>
        <?endif?>
        <select id="sort_type" onchange="sort_storesmanager_type()">            
                <option value="0" <?=$selected0?>><?=gettext("DESC")?></option>
                <option value="1" <?=$selected1?>><?=gettext("ASC")?></option>
        </select>        
         
        <label><?=gettext("Display count")?></label>
        <select id="stores_number" onchange="change_storesmanager_number()">
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
                <a href="<?=base_url()."storesmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$type}/"; ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=base_url()."storesmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$type}/"; ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
        
        <table id="stores_table" width="100%" border="0" cellspacing="5" cellpadding="2" class="tablesorter table_commande">
            <thead>
                <tr>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Store id")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Name")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Address")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("City")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Province")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Postal code")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Phone")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Fax")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Manager")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Cart")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Creation date")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Modification date")?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($stores as $key => $store):?>
                    <?$ids[] = $store->id;?>                
                    <?$tr_class = 'odd'?>
                        <?if ($key % 2) $tr_class = 'even'?>
                    <tr class="<?=$tr_class?>">
                        <td><?=$store->store_id?></td>
                        <td><?=$store->name?></td>
                        <td><?=$store->address?></td>                    
                        <td><?=$store->city?></td>                    
                        <td><?=$store->province?></td>                    
                        <td><?=$store->postal_code?></td>                    
                        <td><?=$store->phone?></td>                    
                        <td><?=$store->fax?></td>                    
                        <td><?=$store->manager_or_owner?></td>                         
                        <?$active = 'Not active'; if ($store->cart_active):?>
                            <?$active = 'Active';?>
                        <?endif?>
                        <td><?=$active?></td>   
                        <td><?=$store->date_created?></td>
                        <td><?=$store->date_modif?></td>  
                        <td><a href="<?=base_url()."storesmanager/edit/{$store->store_id}/"?>"><?=gettext("Edit")?></a></td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>       
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=base_url()."storesmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$current}/"; ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=base_url()."storesmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$current}/"; ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
    </div>
</div>
