    <div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Order details")?> # <?=$order_id?>
            <? if ($user_type == 1):?>
                <a href="<?=base_url()?>admin/dashboard"><?=gettext("Back")?></a>
            <? elseif ($user_type == 2):?>
                <a href="<?=base_url()?>admin/dashboard"><?=gettext("Back")?></a>
            <? else:?>                
                <a href="<?=base_url()?>categories"><?=gettext("Back")?></a>
            <?endif?>
            </h1>
        </div>
        <div id="Collone1">
            <div class="order_title">
            	<div class="surpime_title">&nbsp;</div>
                <div class="desc_title"><?=gettext("Description")?></div>
                <div class="quantite_title"><?=gettext("Quantity")?></div>
                <div class="price_title"><?=gettext("Price")?></div>
            </div>
            <?foreach($order['items'] as $item):?>
                <?
                $product_image = base_url().'public/images/products/70x70/0.jpg';
                if (is_file(ROOT.'public/images/products/70x70/'.$item[0]->id.'.jpg'))  $product_image =  base_url().'public/images/products/70x70/'.$item[0]->id.'.jpg';
                ?>                  
                <div class="Produit_liste_item">
                    <div class="suprime">&nbsp;</div>
                    <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" /></div>                
                    <div class="desc_conf">
                    <h3><?=$item[0]->name?></h3>
                    <p><?=$item[0]->description?><br />                                   
                    #<?=$item[0]->id?> :                    
                    <?=$item[0]->dimension?>                        
                    </p>
                    </div>         
                    <div class="quantite_confirm"> 
                        <span><?=$rows[$order['quantity'][$item[0]->id]]?></span>    
                    </div>   
                    <div class="price">   
                        <span>$<?=$order['price'][$item[0]->id]?></span>   
                    </div>
                </div>
            <?php endforeach?>
            <?  
                if ($order['shipping'][0] == 'shipping0') $typeshpng = 'Colis standard';
                else if ($order['shipping'][0] == 'shipping0') $typeshpng = 'Colis accélérés';
                else $typeshpng = 'Messageries prioritaires';
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_shipping">
                    <tr>
                        <td style="text-align: center; width: 603px; height: 20px; padding-bottom: 10px;">
                            <?=gettext("Shipping rate")?>
                        </td>
                        <td style="text-align: center; padding-bottom: 10px;">
                            <?=gettext("Total")?>
                        </td>
                    </tr>
                    <tr>
                        <td class="Produit_shipping">
                            <div class="shipping_approuved" id="shpgtype0"><span id="shipping0"><?=$typeshpng?>: $<?=$order['shipping'][$order['shipping'][0]]?></span></div>
                        </td>
                        <td>
                            <div class="total_approuved"><span id="total_shipping">$<?=printf("%01.1f",$order['shipping']['total'])?></span></div>   
                        </td>
                    </tr>
                </table>
        </div>
    
        <div id="Collone2">            
            <h2><?=gettext("Store address")?></h2>
            <div class="Boite">   
                <div class="boite_confirm">         
                    <?if (isset($order)):?>
                    <p><?=gettext("Adress")?> : <?=$store[0]->address?></p>   
                    <p><?=gettext("City")?> : <?=$store[0]->city?></p>   
                    <p><?=gettext("Postal Code")?> : <?=$store[0]->postal_code?></p>   
                    <p><?=gettext("Province")?> : <?=$store[0]->province?></p>   
                    <p><?=gettext("Tel")?> : <?=$store[0]->phone?></p>   
                    <p><?=gettext("Fax")?> : <?=$store[0]->fax?></p>   
                <?endif?>
                </div>                   
                <input type="hidden" value="<?=$store[0]->store_id?>" id="store_id">
            </div> 
            <?if ($approved):?>
                <?if ($pos != ''):?>
                    <div class="approuved_pdf"><a href="<?=base_url().'wishlist/invoice/'.$order_id?>" target="_blank" ><img src="<?=base_url();?>public/images/pdf.png" width="40" height="40"/></a> <a href="<?=base_url().'orders/invoice/'.$order_id?>" target="_blank" ><?=gettext("Download the invoice")?></a></div>            
                <?endif?>
                <?if ($user_type == 3):?>
                    <form id="edit_user" method="post" action="<?=base_url()."orders/order_again/".$order_id; ?>">
                        <input type="hidden" value="<?=$store[0]->store_id?>" name="store-orders[]" /></div>
                        <input type="submit" class="modalInput" value="<?=gettext("Order again")?>" />
                    </form>            
                <?else:?>
                    <?if (!$supervised):?>
                        <input type="button" class="modalInput" rel="#stores" value="<?=gettext("Order again")?>" />
                    <?endif?>
                <?endif?>
            <?endif?>
        <?if ($status < 2 && $pos != ''):?>
            <input type="button" class="modalInput" rel="#problems" value="<?=gettext("Problem")?>" />
            <input type="button" class="modalInput" rel="#cancel" value="<?=gettext("Cancel")?>" />
        <?endif?>
        </div>       
    </div>  
</div>

<div class="modal2" id="stores">
    <div id="Page_haut">
        <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
        <h1><?= gettext("Add stores"); ?></h1>
    </div>    
    <form id="edit_user" method="post" action="<?=base_url()."orders/order_again/".$order_id; ?>">
    <div id="liste_overflow">        
        <?foreach($stores_supervised as $store_item):?>            
            <div class="Produit_liste_item"><input type='text' id='<?=$store_item?>' value="<?=$store_item?>" readonly />
            <input type="checkbox" value="<?=$store_item?>" id="active" name="store-orders[]" /></div>            
        <?endforeach?>
    <br/>
    </div>
    <input type="submit" name="submit_update" value="<?=gettext("Add stores")?>"/>
    </form>
</div>

<div class="modal2" id="problems">
    <div id="Page_haut">
        <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
        <h1><?= gettext("Problem"); ?></h1>
    </div>    
    <form id="edit_user" method="post" action="<?=base_url()."orders/request_problem/".$order_id; ?>">
    <div id="liste_overflow" >        
        <label><?=gettext("Order not recieved")?></label><input type="checkbox" name="not_recieved" value="1"/>
        <?foreach($order['items'] as $item):?>
                <?
                $product_image = base_url().'public/images/products/70x70/0.jpg';
                if (is_file(ROOT.'public/images/products/70x70/'.$item[0]->id.'.jpg'))  $product_image =  base_url().'public/images/products/70x70/'.$item[0]->id.'.jpg';
                ?>                  
                <div class="Produit_liste_item">
                    <div class="suprime">&nbsp;</div>
                    <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" /></div>                
                    <div class="desc_conf">
                        <h3><?=$item[0]->name?></h3>
                        <p><?=$item[0]->description?><br /></p>
                    </div>         
                    <div class="quantite_confirm"> 
                        <span><?=gettext("quantity")?> : <?=$rows[$order['quantity'][$item[0]->id]]?></span>                            
                    </div>   
                    <div class="price">   
                        <span><?=gettext("price")?> : $<?=$order['price'][$item[0]->id]?></span>   
                    </div>
                    <br />
                    <div class="price">    
                        <span><?=gettext("reason")?> : </span>   
                        <input type="text" name="problems[]" value="" />   
                    </div>                    
                </div>
            <?endforeach?>
            <div class="quantite_confirm"> 
                <span><?=gettext("summary")?>:</span>                            
            </div> 
            <div class="price">     
                <textarea name="problems[]"></textarea>  
            </div>
    <br/>
    </div>
    <input type="submit" name="submit_update" value="<?=gettext("Submit problem")?>"/>
    </form>
</div>

<div class="modal2" id="cancel">
    <div id="Page_haut">
        <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
        <h1><?= gettext("Cancel"); ?></h1>
    </div>    
    <form id="edit_user" method="post" action="<?=base_url()."orders/request_cancel/".$order_id; ?>">
    <div id="liste_overflow">        
        <?foreach($order['items'] as $item):?>
                <?
                $product_image = base_url().'public/images/products/70x70/0.jpg';
                if (is_file(ROOT.'public/images/products/70x70/'.$item[0]->id.'.jpg'))  $product_image =  base_url().'public/images/products/70x70/'.$item[0]->id.'.jpg';
                ?>                  
                <div class="Produit_liste_item">
                    <div class="suprime">&nbsp;</div>
                    <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" /></div>                
                    <div class="desc_conf">
                    <h3><?=$item[0]->name?></h3>
                    <p><?=$item[0]->description?><br /></p>
                    </div>         
                    <div class="quantite_confirm"> 
                        <span><?=gettext("quantity")?> : <?=$rows[$order['quantity'][$item[0]->id]]?></span>                            
                    </div>   
                    <div class="price">   
                        <span><?=gettext("price")?> : $<?=$order['price'][$item[0]->id]?></span>   
                    </div>
                    <br />
                    <div class="price">    
                        <span><?=gettext("reason")?> : </span>   
                        <input type="text" name="problems[]" value="" />   
                    </div>                    
                </div>
            <?endforeach?>
            <div class="quantite_confirm"> 
                <span><?=gettext("summary")?>:</span>                            
            </div> 
            <div class="price">     
                <textarea name="problems[]"></textarea>  
            </div>
    <br/>
    </div>
    <input type="submit" name="submit_update" value="<?=gettext("Submit cancel")?>"/>
    </form>
</div>
