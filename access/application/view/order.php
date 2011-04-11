<?//var_dump($order);?>
<?//var_dump($store);?>
<form id="order_wish_list" method="post" action="<?=url::page('orders/approve'); ?>">    
    <div id="Container_Principale_full">
        <div id="Container_Principale_960">
            <div id="Page_haut">
                <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
                <h1><?=gettext("Order confirmation")?></h1>
            </div>
            <div id="Collone1">
                <div id="error">          
                </div>   
                <div class="order_title">
                    <div class="surpime_title"><?=gettext("Remove")?></div>
                    <div class="desc_title"><?=gettext("Description")?></div>
                    <div class="quantite_title"><?=gettext("Quantity")?></div>
                    <div class="price_title"><?=gettext("Price")?></div>
                </div>
                <?$ids = array();?>
                <?foreach($order['items'] as $item):?>
                    <?            
                    $ids[] = $item[0]->id;
                    $products = get_object_vars($item[0]);  
                    $product_image = url::base().'public/images/products/70x70/0.jpg';
                    if (is_file(url::root().'public/images/products/70x70/'.$item[0]->id.'.jpg'))  $product_image =  url::base().'public/images/products/70x70/'.$item[0]->id.'.jpg';
                    ?>                
                    <div class="Produit_liste_item" id="<?=$item[0]->id?>">
                        <div class="suprime"><input type="checkbox" value="<?=$item[0]->id?>" id="checkbox_<?=$item[0]->id?>" name="checkbox_supprim[]" /></div>
                        <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" /></div>                
                        <div class="desc_conf">
                            <h3><?=utf8_encode($item[0]->name)?></h3>
                            <p><?=utf8_encode($item[0]->description)?><br />
                            <a href="<?=url::base()?>products/product/<?=$item[0]->id?>"><?=gettext("More info")?></a></p>
                        </div>         
                        <div class="quantite_confirm">
                            <select name="product_qty[]" id="select_<?=$item[0]->id?>" onchange="get_product_price('<?=$item[0]->id?>')">    
                                <option value="0"><?=gettext("-------")?></option>                            
                                <?foreach($rows as $key => $row) :?>                           
                                    <? if ($products[$key] > 0) :?>     
                                        <?$selected = '';?>                                  
                                        <? if ($order['quantity'][$item[0]->id] == $key) :?>
                                                <?$selected = 'selected="selected"';?>
                                        <?endif?>                                        
                                        <option <?=$selected?> value="<?=$key?>"><?=$row?></option>                                                                       
                                    <?endif?>                                
                                <?endforeach?>                                
                            </select>   
                            <input type="hidden" value="<?=$item[0]->id?>" name="product_id[]"></p>    
                        </div>    
                        <div class="price">   
                            <span id="price_<?=$item[0]->id?>">$<?=$order['price'][$item[0]->id]?></span>    
                            <input type="hidden" id="hidden_price_<?=$item[0]->id?>" value="<?=$order['price'][$item[0]->id]?>">
                            <input type="hidden" id="shipping0_<?=$item[0]->id?>" value="0">
                            <input type="hidden" id="shipping1_<?=$item[0]->id?>" value="0">
                            <input type="hidden" id="shipping2_<?=$item[0]->id?>" value="0">
                        </div>              
                    </div>
                <?endforeach?>    
                <input type="button" id="update" onclick='remove_from_order_list(<?=json_encode($ids)?>,<?=$order_id?>)' value="<?=gettext("Remove")?>" />                 
                <input style="float:right" type="button" id="getShipping" onclick='get_shipping(<?=json_encode($ids)?>)' value="<?=gettext("Get shipping rates")?>" />
                <?$checked0 = '';?>
                <?$checked1 = '';?>
                <?$checked2 = '';?>
                <?if ($order['shipping'][0] == 'shipping0'):?>
                    <?$checked0 = 'CHECKED="CHECKED"';?>
                <?else: $checked0 = '';?>
                <?endif?>
                
                <?if ($order['shipping'][0] == 'shipping1'):?>
                    <?$checked1 = 'CHECKED="CHECKED"';?>
                <?else: $checked1 = '';?>
                <?endif?>
                
                <?if ($order['shipping'][0] == 'shipping2'):?>
                    <?$checked2 = 'CHECKED="CHECKED"';?>
                <?else: $checked2 = '';?>
                <?endif?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_shipping">
                    <tr>
                        <td style="text-align: center; width: 603px; height: 20px;">
                            <?=gettext("Select a rate")?>
                        </td>
                        <td style="text-align: center;">
                            <?=gettext("Total")?>
                        </td>
                    </tr>
                    <tr>
                        <td class="Produit_shipping">
                            <div id="shipping_choice">
                                <div id="wait">                        
                                </div>
                                <div id="shpgtype0"><span id="shipping0"><input type='radio' onclick='get_sum(<?=json_encode($ids)?>)' name='radio_shipping' value='shipping0' <?=$checked0?> > Colis standard: $<?=printf("%01.1f",$order['shipping']['shipping0'])?></span></div>
                                <div id="shpgtype1"><span id="shipping1"><input type='radio' onclick='get_sum(<?=json_encode($ids)?>)' name='radio_shipping' value='shipping1' <?=$checked1?> > Colis accélérés: $<?=printf("%01.1f",$order['shipping']['shipping1'])?></span></div>
                                <div id="shpgtype2"><span id="shipping2"><input type='radio' onclick='get_sum(<?=json_encode($ids)?>)' name='radio_shipping' value='shipping2' <?=$checked2?> > Messageries prioritaires: $<?=printf("%01.1f",$order['shipping']['shipping2'])?></span></div>
                            </div>
                            <input type="hidden" id="hidden_shipping0" name="hidden_shipping0" value="<?=$order['shipping']['shipping0']?>">
                            <input type="hidden" id="hidden_shipping1" name="hidden_shipping1" value="<?=$order['shipping']['shipping1']?>">
                            <input type="hidden" id="hidden_shipping2" name="hidden_shipping2" value="<?=$order['shipping']['shipping2']?>">
                        </td>
                        <td class="total">
                            <div id="total">     
                            $<?=printf("%01.1f",$order['shipping']['total'])?>                   
                            </div>  
                            <input type="hidden" id="hidden_total" name="hidden_total" value="<?=printf("%01.1f",$order['shipping']['total'])?>">  
                         </td>
                    </tr>
                </table>
            </div>
        
        
            <div id="Collone2">            
                <h2><?=gettext("Store address")?></h2>
                <div class="Boite">   
                    <div class="boite_confirm">         
                        <?if (isset($order)):?>
                            <p><?=gettext("Address")?><br /> <input type="text" value="<?=$store[0]->address?>" name="address"> </p>   
                            <p><?=gettext("City")?><br /> <input type="text" value="<?=$store[0]->city?>" name="city"> </p>   
                            <p><?=gettext("Postal Code")?><br /><input type="text" value="<?=$store[0]->postal_code?>" name="postal_code"> </p>   
                            <p><?=gettext("Province")?><br /> <input type="text" value="<?=$store[0]->province?>" name="province"> </p>   
                            <p><?=gettext("Tel")?><br /> <input type="text" value="<?=$store[0]->phone?>" name="phone"> </p>   
                            <p><?=gettext("Fax")?><br /> <input type="text" value="<?=$store[0]->fax?>" name="fax"></p>   
                        <?endif?> 
                    </div>                   
                    <input type="button" class="commande" name="orderWishlist" value="<?=gettext("Approve the order")?>" onclick='order_wishlist(<?=json_encode($ids)?>)'/>
                    <input type="hidden" value="<?=$order_id?>" name="order_id">
                    <input type="hidden" value="<?=session::get('lang')?>" id="lang">
                    <input type="hidden" value="0" id="count_removed">
                </div>            
            </div>
        </div>    
    </div>
</form>
