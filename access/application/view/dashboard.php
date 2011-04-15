<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Orders list"); ?></h1>
        </div>    
        
        <div>
        <?if ($total > 1):?>
            <label><?=gettext("Page number")?></label>
            <select id="orders_page" onchange="change_dashboard_page()">
                <?for($t = 1; $t <= $total; $t++) :?>
                    <?$selected = "";?>
                    <? if ($t == $current_page):?>
                            <?$selected = "selected";?>
                    <?endif?>
                    <option value="<?=$t?>" <?=$selected?>><?=$t?></option>
                <?endfor?>
            </select>
        <?else :?>
            <input type="hidden" id="orders_page" value="1"/>
        <?endif?>
        
        <label><?=gettext("Sort by")?></label>
        <?$items = get_sort_items()?>
        <select id="sort_page" onchange="sort_dashboard_page()">
            <?foreach($items as $key => $item) :?>
                <?$selected = "";?>
                <? if ($key == $sort):?>
                        <?$selected = "selected";?>
                <?endif?>
                <option value="<?=$key?>" <?=$selected?>><?=gettext($item)?></option>
            <?endforeach?>
        </select>
        
        <label><?=gettext("Sort order")?></label>
        <?$selected = "";?>
        <? if ($type == 0):?>
                <?$selected0 = "selected";?>
                <?$selected1 = "";?>
        <? else:?>
                <?$selected0 = "";?>
                <?$selected1 = "selected";?>
        <?endif?>
        <select id="sort_type" onchange="sort_dashboard_type()">            
                <option value="0" <?=$selected0?>><?=gettext("DESC")?></option>
                <option value="1" <?=$selected1?>><?=gettext("ASC")?></option>
        </select>
        </div>
        
        <br />
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=url::page("admin/dashboard/{$page->prev_page()}/{$sort}/"); ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=url::page("admin/dashboard/{$page->next_page()}/{$sort}/"); ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
        
        <table width="100%" border="0" cellspacing="5" cellpadding="2" class="table_commande">
            <tr>
                <th scope="col"><?=gettext("Store")?></th>
                <th scope="col"><?=gettext("Reservation date")?></th>
                <th scope="col"><?=gettext("Approval date")?></th>
                <th scope="col"><?=gettext("Approved by")?></th>
                <th scope="col"><?=gettext("Number")?></th>
                <th scope="col"><?=gettext("Total in")?> $</th>
                <th scope="col"><?=gettext("Status")?></th>
                <th scope="col" width="50"><?=gettext("Actions")?></th>
            </tr>
            
            <?foreach ($orders as $key => $order):?>
                <?$order_detail = unserialize($order->wish_list);?>
                <?if (count($order_detail)):?>                    
                    <? $class_approved = 'order_approuved'; $approved = gettext("approved");?>
                    <? if (!$order->approved):?>
                        <? $class_approved = 'order_cancelled'; $approved = gettext("not approved"); ?>
                    <?endif?>
                    <?if ($order->pos == ''):?>
                        <? $class_approved = 'order_cancelled'; $approved = gettext("no pos"); ?>     
                    <?endif?>
                    <?if ($orders_status[$order->id] != '0' && $orders_status[$order->id] !=NULL ):?>
                        <? $class_approved = 'order_approuved'; $approved = gettext($orders_status[$order->id]); ?>   
                    <?endif?>
                <?$tr_class = 'outline'?>
                    <?if ($key % 2) $tr_class = ''?>
                <tr class="<?=$tr_class?>">
                    <td><?=$order->store_id?></td>
                    <td><?=$order->save_data?></td>
                    <td><?=$order->approve_date?></td>                    
                    <td><?=$users[$key][0].' '.$users[$key][1]?></td>                    
                    <td><?=$order->id?></td>
                    <td><?=printf("%01.1f",$order_detail['shipping']['total'])?></td>
                    <td><span class="<?=$class_approved?>"><?=$approved?></span></td> 
                    <?if (!$order->approved):?>
                        <td><a href="<?=url::base()?>orders/approve_order/<?=$order->id?>"><?=gettext("Approve")?></a></td>   
                    <?else:?>
                        <td></td>
                    <?endif?>  
                    <?if (count(unserialize($order->wish_list))):?>                
                        <td><a href="<?=url::base()?>orders/lists/<?=$order->id?>"><?=gettext("Details")?></a></td>
                    <?endif?>
                </tr>
                <?endif?>
            <?endforeach?>
        </table>
        
        <div class="admin_pagination">
            <? if($page->prev()): ?>
                <a href="<?=url::page("admin/dashboard/{$page->prev_page()}/{$sort}/"); ?>"><?=gettext("Previous")?></a>
            <? endif; ?>
            
            <? if($page->next()): ?>
                <a href="<?=url::page("admin/dashboard/{$page->next_page()}/{$sort}/"); ?>"><?=gettext("Next")?></a>
            <? endif; ?>
        </div>
    </div>
</div>
