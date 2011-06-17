    <div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Order details")?> # <?=$order_id?></h1>
        </div>
        <div id="Collone1">
            <div class="order_title">
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
            <?endforeach?>
            <?  
                if ($order['shipping'][0] == 'shipping0') $typeshpng = 'Colis standard';
                else if ($order['shipping'][0] == 'shipping0') $typeshpng = 'Colis accélérés';
                else $typeshpng = 'Messageries prioritaires';
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_shipping">
                    <tr>
                        <td style="text-align: center; width: 603px; height: 20px;">
                            <?=gettext("Shipping rate")?>
                        </td>
                        <td style="text-align: center;">
                            <?=gettext("Total")?>
                        </td>
                    </tr>
                    <tr>
                        <td class="Produit_shipping">
                            <div id="shpgtype0"><span id="shipping0"><?=$typeshpng?>: $<?=$order['shipping'][$order['shipping'][0]]?></span></div>   
                        </td>
                        <td class="total">
                            <div id="total"><span id="total_shipping">$<?=printf("%01.1f",$order['shipping']['total'])?></span></div>   
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
                <input type="hidden" value="<?=$store[0]->id?>" id="store_id">
            </div>            
        </div>
    </div>
  
</div>
</form>
