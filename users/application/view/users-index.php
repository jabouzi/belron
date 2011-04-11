<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("users list"); ?></h1>
        </div>    
        
        <div>
        <?if ($total > 1):?>
            <label><?=gettext("Page number")?></label>
            <select id="users_page" onchange="change_usersmanager_page()">
                <?for($t = 1; $t <= $total; $t++) :?>
                    <?$selected = "";?>
                    <? if ($t == $current):?>
                            <?$selected = "selected";?>
                    <?endif?>
                    <option value="<?=$t?>" <?=$selected?>><?=$t?></option>
                <?endfor?>
            </select>
        <?else :?>
            <input type="hidden" id="users_page" value="1"/>
        <?endif?>
        
        <label><?=gettext("Sort by")?></label>
        <?$items = get_sort_items()?>
        <select id="sort_page" onchange="sort_usersmanager_page()">
            <?foreach($items as $key => $item) :?>
                <?$selected = "";?>
                <? if ($key == $sort):?>
                        <?$selected = "selected";?>
                <?endif?>
                <option value="<?=$key?>" <?=$selected?>><?=gettext($item)?></option>
            <?endforeach?>
        </select>
        
        <label><?=gettext("Sort user")?></label>
        <?$selected = "";?>
        <? if ($type == 0):?>
                <?$selected0 = "selected";?>
                <?$selected1 = "";?>
        <? else:?>
                <?$selected0 = "";?>
                <?$selected1 = "selected";?>
        <?endif?>
        <select id="sort_type" onchange="sort_usersmanager_type()">            
                <option value="0" <?=$selected0?>><?=gettext("DESC")?></option>
                <option value="1" <?=$selected1?>><?=gettext("ASC")?></option>
        </select>        
         
        <label><?=gettext("Display count")?></label>
        <select id="users_number" onchange="change_usersmanager_number()">
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
                <a href="<?=url::page("usersmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$type}/"); ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=url::page("usersmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$type}/"); ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
        
        <table id="users_table" width="100%" border="0" cellspacing="5" cellpadding="2" class="tablesorter table_commande">
            <thead>
                <tr>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Remove")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Position")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Lastname")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Firstname")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Address")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Town")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Province")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Postal code")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Phone")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Email")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Action")?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($users as $key => $user):?>
                    <?$ids[] = $user->id;?>                
                    <?$tr_class = 'odd'?>
                        <?if ($key % 2) $tr_class = 'even'?>
                    <tr class="<?=$tr_class?>">
                        <td><input type="checkbox" value="<?=$user->id?>" id="checkbox_<?=$user->id?>" name="checkbox_supprim[]" /></td>
                        <td><?=utf8_encode($user->position)?></td>
                        <td><?=utf8_encode($user->family_name)?></td>
                        <td><?=utf8_encode($user->first_name)?></td>
                        <td><?=utf8_encode($user->address)?></td>                    
                        <td><?=utf8_encode($user->town)?></td>                    
                        <td><?=utf8_encode($user->province)?></td>                    
                        <td><?=utf8_encode($user->postal_code)?></td>                    
                        <td><?=utf8_encode($user->phone)?></td>                    
                        <td><?=utf8_encode($user->email)?></td>                    
                        <td><a href="<?=url::page("usersmanager/edit/{$user->id}/")?>"><?=gettext("Edit")?></a></td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
        
        <input type="button" id="rem_users" onclick='remove_users(<?=json_encode($ids)?>)' value="<?=gettext("Remove users")?>" />
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=url::page("usersmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$current}/"); ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=url::page("usersmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$current}/"); ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
    </div>
</div>
