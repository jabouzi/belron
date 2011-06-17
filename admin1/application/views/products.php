<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Add a product")?></h1>
        </div>        
        <div id="Collone1">
            <?foreach($products_list as $products):?>                
                <?
                $product_image = base_url().'public/images/products/70x70/0.jpg';
                $rel_image = base_url().'public/images/products/250x381/0.jpg';
                if (is_file(ROOT.'public/images/products/70x70/'.$products->id.'.jpg')) 
                {
                    $product_image =  base_url().'public/images/products/70x70/'.$products->id.'.jpg';
                    $rel_image = base_url().'public/images/products/250x381/'.$products->id.'.jpg';
                }
                ?>               
                <div class="Produit_liste_item">
                    <div class="thumb"><img src="<?=$product_image?>" alt="" width="70" height="69" rel="#img_<?=$products->id?>" /></div>                
                    <div class="desc"><?=$products->description?> <br />                                 
                    <h3 id="h3_<?=$products->id?>">#<?=$products->id?></h3>
                    <h3 id="h3_<?=$products->id?>"><?=$products->name?></h3>
                    <p id="p_<?=$products->id?>">Dimensions : <?=$products->dimension?><br />                      
                    </div>                
                    <div class="ajouter">
                        <label></label>
                        <a href="javascript:;"  onclick="add_product_to_wish_list('<?=$store_id?>','<?=$products->id?>')"><?=gettext("Add to cart")?></a>
                    </div>
                </div>
                <div class="simple_overlay" id="img_<?=$products->id?>">
                <img src="<?=$rel_image?>">
                </div>
            <?endforeach?>
        </div> 
        
        <div id="Collone2">
            <div id="Panier">                
                <h2><?=gettext("Shopping cart")?></h2>
                <div class="Boite">
                <form action="<?=base_url();?>wishlist/confirm" method="post" id="wish_form">
                    <ul id="wish_list">                    
                    </ul>
                    <span  style="display:none" id="display_button">
                        <input type="submit" class="commande" name="confirm_wishlist" value="<?=gettext("Order now")?>" />
                    </span>
                    <span  style="display:none" id="display_empty">
                        <input type="button" class="commande" name="confirm_wishlist" value="<?=gettext("Empty cart")?>" onclick="empty_cart()" />
                    </span>
                </form>         
                <input type="hidden" value="<?=$store_id?>" id="store_id">
                <input type="hidden" value="<?=$lang?>">
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

