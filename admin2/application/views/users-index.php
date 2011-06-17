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
                <a href="<?=base_url()."usersmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$type}/"; ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=base_url()."usersmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$type}/"; ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
        
        <table id="users_table" width="100%" border="0" cellspacing="5" cellpadding="2" class="tablesorter table_commande">
            <thead>
                <tr>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Position")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Last name")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("First name")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Address")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("City")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Phone")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Email")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Active")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Creation date")?></th>
                    <th style="cursor:pointer;" scope="col"><?=gettext("Modification date")?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($users as $key => $user):?>
                    <?$ids[] = $user->id;?>                
                    <?$tr_class = 'odd'?>
                        <?if ($key % 2) $tr_class = 'even'?>
                    <tr class="<?=$tr_class?>">
                        <td><?=$user->position?></td>
                        <td><?=$user->family_name?></td>
                        <td><?=$user->first_name?></td>
                        <td><?=$user->address?></td>                    
                        <td><?=$user->town?></td> 
                        <td><?=$user->phone?></td>                    
                        <td><?=$user->email?></td>        
                        <?$active = 'Not active'; if ($user->active):?>
                            <?$active = 'Active';?>
                        <?endif?>
                        <td><?=$active?></td>         
                        <td><?=$user->date_created?></td>
                        <td><?=$user->date_modif?></td>     
                        <td><a href="<?=base_url()."usersmanager/edit/{$user->id}/"?>"><?=gettext("Edit")?></a></td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
                
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=base_url()."usersmanager/lists/{$page->prev_page()}/{$number}/{$sort}/{$current}/"; ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=base_url()."usersmanager/lists/{$page->next_page()}/{$number}/{$sort}/{$current}/"; ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
    </div>
</div>
