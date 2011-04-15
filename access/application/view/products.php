<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Add a product")?></h1>
        </div>        
        <div id="Collone1">
            <?foreach($products_list as $products):?>                
                <?
                $product_image = url::base().'public/images/products/70x70/0.jpg';
                $rel_image = url::base().'public/images/products/250x381/0.jpg';
                if (is_file(url::root().'public/images/products/70x70/'.$products[0]->id.'.jpg')) 
                {
                    $product_image =  url::base().'public/images/products/70x70/'.$products[0]->id.'.jpg';
                    $rel_image = url::base().'public/images/products/250x381/'.$products[0]->id.'.jpg';
                }
                ?>               
                <div class="Produit_liste_item">
                    <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" rel="#img_<?=$products[0]->id?>" /></div>                
                    <div class="desc"><?=utf8_encode($products[0]->description)?> <br />                                 
                    <h3 id="h3_<?=$products[0]->group_id?>"><?=utf8_encode($products[0]->name)?></h3>
                    <p id="p_<?=$products[0]->group_id?>">Dimensions : <?=utf8_encode($products[0]->dimension)?><br />                      
                    </div>                
                    <div class="ajouter">
                        <label></label>
                        <a href="javascript:;"  onclick="add_product_to_wish_list('<?=$store_id?>','<?=$products[0]->id?>')"><?=gettext("Add to cart")?></a>
                    </div>
                </div>
                <div class="simple_overlay" id="img_<?=$products[0]->id?>">
                <img src="<?=$rel_image?>">
                </div>
            <?endforeach?>
        </div> 
        
        <div id="Collone2">
            <div id="Panier">                
                <h2><?=gettext("Shopping cart")?></h2>
                <div class="Boite">
                <form action="<?=url::page('wishlist/confirm'); ?>" method="post" id="wish_form">
                    <ul id="wish_list">                    
                    </ul>
                    <?if (!session::get('wishlist')):?>
                        <?$display = "display:none"?>
                    <?endif?>
                    <span  style="<?=$display?>" id="display_button">
                        <input type="submit" class="commande" name="confirm_wishlist" onclick="confirm_wishlist(); return false;" value="<?=gettext("Order now")?>" />
                    </span>
                </form>         
                <input type="hidden" value="<?=$store_id?>" id="store_id">
                <input type="hidden" value="<?=session::get('lang')?>" id="lang">
                </div>            
            </div>        
        </div> 
    </div>    
</div>


<script>
$(document).ready(function() {
$("img[rel]").overlay();
});
</script>

