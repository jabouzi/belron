<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Order P.O (P.O.S)"); ?></h1>
        </div>
        <div id="error">               
        </div>     
        <form id="edit_user" name="pos_form" method="post" action="<?=url::page("wishlist/approve_pos/".$id); ?>">  	
            <div>       
            <?foreach($orders_ids as $key => $order):?>    
            <div class="Produit_liste_item"><label><?=gettext("P.O.")?> : </label><input type='text' id='pos_<?=$key?>' name='pos[<?=$order?>]' value="" />
																		<input type='text' id='store_id' name='store_id' value="<?=$stores_ids[$key]?>" readonly /></div>
            <?endforeach?>
            <div class="Produit_liste_item"><label><?=gettext("Shipping")?> : </label><input type='text' id='shipping' name='shipping' value="<?=utf8_encode($shipping)?>" readonly /></div>
            <div class="Produit_liste_item"><label><?=gettext("Total")?> : </label><input type='text' id='total' name='total' value="<?=utf8_encode($total_cost)?>"  readonly /></div>
            <?foreach($products_list as $product):?>
                <div class="Produit_liste_item"><label><?=gettext("Products")?> : </label><input type='text' id='name' name='name' value="<?=utf8_encode($product[0])?>"   readonly  />
                <input type='text' id='description' name='description' value="<?=utf8_encode($product[1])?>"   readonly  /><input type='text' id='price' name='price' value="$<?=utf8_encode($product[2])?>"   readonly  /></div>
            <?endforeach?>
            </div>
            <input type='hidden' id='lang' name='lang' value="<?=session::get('lang')?>" />
            <input type='hidden' id='count_pos' value="<?=count($orders_ids)?>" />
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Update order")?>" onclick="submit_pos()"/>
        </form>
        <?if (count($sups1)):?>
            <label><b><?=gettext("Approval can be made by")?> : </b></label><br />
            <?foreach($sups1 as $sup):?>
                <?=$sup?><br />
            <?endforeach?>
        <?endif?>
        <?if (count($sups2)):?>            
            <?foreach($sups2 as $sup):?>
                <?=$sup?><br />
            <?endforeach?>
        <?endif?>

    </div>
</div>

