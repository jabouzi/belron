<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?= gettext("Order P.O (P.O.S)"); ?></h1>
        </div>
        <div id="error">               
        </div>     
        <form id="edit_user" name="pos_form" method="post" action="<?=base_url()."wishlist/approve_pos/".$id; ?>">
            <div>       
                <table border="0" cellpadding="0" cellspacing="5" class="table_order">
                    <?php foreach($orders_ids as $key => $order): ?>    
                        <tr <?php if ($key % 2 == 0){ echo 'class="outline"';} ?>>
                            <td><label><?=gettext("P.O.")?> : </label><input type='text' id='pos_<?=$key?>' name='pos[<?=$order?>]' value="" /></td>
                            <td> <?=gettext("store")?> # : <?=$stores_ids[$key]?>
                            <input type='hidden' id='store_id' name='store_id' value="<?=$stores_ids[$key]?>"/></td>
                        </tr>
                    <?php endforeach ?>
                        <tr>
                            <td>
                                <label><?=gettext("Shipping")?> <?=gettext("By store")?> : </label>$<?=$shipping?>
                                <input type='hidden' id='shipping' name='shipping' value="<?=$shipping?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?=gettext("Total")?> <?=gettext("By store")?> : </label><?=$total_cost?>
                                <input type='hidden' id='total' name='total' value="<?=$total_cost?>" />
                            </td>
                        </tr>
						<?php $i = 0; ?>
                        <?php foreach($products_list as $product): ?>
						<tr <?php if ($i % 2 == 0){ echo 'class="outline"';} ?>>
                            <td>
                                <label><?=gettext("Products")?> : </label><?=$product[0]?>
                                <input type='hidden' id='total' name='total' value="<?=$product[0]?>" />
                            </td>
                            <td>
                                <label><?=gettext("Description")?> : </label><?=$product[0]?>
                                <input type='hidden' id='total' name='total' value="<?=$product[1]?>" />
                            </td>
                            <td>
                                <label><?=gettext("Price")?> : </label>$<?=$product[0]?>
                                <input type='hidden' id='total' name='total' value="<?=$product[2]?>" />
                            </td>
                        </tr>
					<?php $i++; ?>	
                    <?php endforeach ?>
                    <tr>
                        <td>
                            <label><?=gettext("Grand total")?> : </label>$<?=$total_price?>
                            <input type='hidden' id='total' name='total' value="<?=$total_price?>" />
                        </td>
                    </tr>
                </table>
            </div>
            <input type='hidden' id='lang' name='lang' value="<?=$lang?>" />
            <input type='hidden' id='count_pos' value="<?=count($orders_ids)?>" />
            <input type="button" class="commande" name="submit_update" value="<?=gettext("Confirm order")?>" onclick="submit_pos()"/>
            <?/*<a href="<?=base_url().'wishlist/pdf'?>"><?=gettext("Download the invoice")?><img src="<?=base_url();?>public/images/pdf-logo.jpg" width="40" height="40"/></a> */?>           
        </form>
        <div style="color:#ff0000; padding-top: 40px;"><strong>*<?=gettext("The delivery fees may change")?></strong></div>
        <div style="color:#ff0000"><strong>*<?=gettext("Tax not included")?></strong></div>
        <?php if (count($sups1)): ?>
            <label><b><?=gettext("Approval can be made by") ?> : </b></label><br />
             <?php foreach($sups1 as $sup):?>
                <?=$sup?><br />
             <?php endforeach ?>
         <?php endif?>
         <?php if (count($sups2)): ?>      
             <?php if (!count($sups1)): ?>
                <label><b><?=gettext("Approval can be made by") ?> : </b></label><br />
             <?php endif ?>
             <?php foreach($sups2 as $sup): ?>
                <?=$sup ?><br />
             <?php endforeach ?>
         <?php endif ?>
    </div>
</div>
