<form id="order_wish_list" method="post" action="<?=base_url()?>wishlist/order">    
    <div id="Container_Principale_full">
        <div id="Container_Principale_960">
            <div id="Page_haut">
                <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
                <h1><?=gettext("Order confirmation")?>
                <?/*<? if ($user_type == 1):?>
                    <a href="/admin/admin_index"><?=gettext("Back")?></a>
                <? elseif ($user_type == 2):?>
                    <a href="/orders/lists/0"><?=gettext("Back")?></a>
                <? else:?>                
                    <a href="/categories"><?=gettext("Back")?></a>
                <?endif?>*/?>
                </h1>
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
                <?foreach($wish_list['items'] as $item):?>
                    <?            
                    $ids[] = $item[0]->id;
                    $products = get_object_vars($item[0]);  
                    $product_image = base_url().'public/images/products/70x70/10001.jpg';
                    if (is_file(ROOT.'public/images/products/70x70/'.$item[0]->id.'.jpg'))  $product_image =  base_url().'public/images/products/70x70/'.$item[0]->id.'.jpg';
                    ?>                
                    
                    <div class="Produit_liste_item" id="<?=$item[0]->id?>" style="clear:both; float:none;">
                        <div class="suprime"><input type="checkbox" value="<?=$item[0]->id?>" id="checkbox_<?=$item[0]->id?>" name="checkbox_supprim[]" /></div>
                        <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" /></div>                
                        <div class="desc_conf">
                            <h3><?=$item[0]->name?></h3>
                            <p><?=$item[0]->description?><br />                                   
                            #<?=$item[0]->id?> :                    
                            <?=$item[0]->dimension?> 
                            </p>
                        </div>         
                        <div class="quantite_confirm">
                            <label>      
                            <select name="product_qty[]" id="select_<?=$item[0]->id?>" onchange="get_product_price('<?=$item[0]->id?>')">    
                                <option value="0"><?=gettext("-------")?></option>                            
                                <?foreach($rows as $key => $row) :?>                           
                                    <? if ($products[$key] > 0) :?>
                                        <option value="<?=$key?>"><?=$row?></option>
                                    <?endif?>                                
                                <?endforeach?>                                
                            </select>   
                            <input type="hidden" value="<?=$item[0]->id?>" name="product_id[]"></p>    
                        </div>    
                        <div class="price">   
                            <span id="price_<?=$item[0]->id?>"></span>    
                            <input type="hidden" id="hidden_price_<?=$item[0]->id?>" value="0">
                            <input type="hidden" id="shipping0_<?=$item[0]->id?>" value="0">
                            <input type="hidden" id="shipping1_<?=$item[0]->id?>" value="0">
                            <input type="hidden" id="shipping2_<?=$item[0]->id?>" value="0">
                        </div>              
                    </div>
                <?endforeach?>

                <input type="button" name="update_order" onclick='remove_from_wish_list(<?=json_encode($ids)?>,"<?=$store_id?>")' value="<?=gettext("Remove")?>" />
                <input style="float:right" type="button" id="getShipping" onclick='get_shipping(<?=json_encode($ids)?>)' value="<?=gettext("Get shipping rates")?>" />
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
                                <div id="shpgtype0"><span id="shipping0"></span><span style="color:#ff0000;display:none" id="date0"> <?=gettext("Delivery within 30 days")?></span></div>
                                <div id="shpgtype1"><span id="shipping1"></span><span style="color:#ff0000;display:none" id="date1"> <?=gettext("Delivery within 2 weeks")?></span></div>
                                <div id="shpgtype2"><span id="shipping2"></span><span style="color:#ff0000;display:none" id="date2"> <?=gettext("Delivery as soon as possible")?></span></div>
                            </div>
                            <input type="hidden" id="hidden_shipping0" name="hidden_shipping0" value="0">
                            <input type="hidden" id="hidden_shipping1" name="hidden_shipping1" value="0">
                            <input type="hidden" id="hidden_shipping2" name="hidden_shipping2" value="0">
                        </td>
                        <td class="total">
                            <div id="total">                        
                            </div>  
                            <input type="hidden" id="hidden_total" name="hidden_total" value="0">
                        </td>
                    </tr>
                </table>
                <div style="color:#ff0000"><strong>*<?=gettext("The delivery fees may change")?></strong></div>
                <div style="color:#ff0000"><strong>*<?=gettext("Print delay not included")?></strong></div>
                <div style="color:#ff0000"><strong>*<?=gettext("Tax not included")?></strong></div>
            </div>        
        
            <div id="Collone2">            
                <h2><?=gettext("Store address")?></h2>
                <div class="Boite">   
                    <div class="boite_confirm">         
                        <?if ($user_type == 3 && isset($wish_list)):?>
                            <p><?=gettext("Address")?><br /> <input type="text" value="<?=$store[0]->address?>" name="address"> </p>   
                            <p><?=gettext("City")?><br /> <input type="text" value="<?=$store[0]->city?>" name="city"> </p>   
                            <p><?=gettext("Postal Code")?><br /><input type="text" value="<?=$store[0]->postal_code?>" name="postal_code"> </p>   
                            <p><?=gettext("Province")?><br /> <input type="text" value="<?=$store[0]->province?>" name="province"> </p>   
                            <p><?=gettext("Tel")?><br /> <input type="text" value="<?=$store[0]->phone?>" name="phone"> </p>   
                            <p><?=gettext("Fax")?><br /> <input type="text" value="<?=$store[0]->fax?>" name="fax"></p>   
                        <?else:?>
                            <?foreach($stores as $store_item):?>            
                                <div class="Produit_liste_item"><input type="checkbox" value="<?=$store_item?>" id="active" name="store-orders[]" />
                                <input type='text' id='<?=$store_item?>' value="<?=$store_item?>" readonly style="width: 80px;" />
                                </div>            
                            <?endforeach?>
                        <?endif?> 
                    </div>                   
                    <input type="button" class="commande" name="orderWishlist" value="<?=gettext("Confirm the order")?>" onclick='order_wishlist(<?=json_encode($ids)?>)'/>
                    <input type="button" class="commande" name="confirm_wishlist" value="<?=gettext("Empty cart")?>" onclick="cancel_order()" />
                    <input type="hidden" value="<?//=$store[0]->id?>" id="store_id" >
                    <input type="hidden" value="<?=$lang?>" >
                </div>            
            </div>            
        </div>    
    </div>
</form>

<script>
$(document).ready(function() {
$("select").val(0);
});
</script>
