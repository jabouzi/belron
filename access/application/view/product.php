<?$prod = $product[0];?>
<?
$product_image = url::base().'public/images/products/166x253/0.jpg';
if (is_file(url::root().'public/images/products/166x253/'.$prod->id.'.jpg'))  $product_image = url::base().'public/images/products/166x253/'.$prod->id.'.jpg';
?>  
<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
          <div id="Breadcrumb"><a href="#"></a></div>
          <h1><?=utf8_encode($prod->category)?></h1>
        </div>
        <div id="Collone1">
            <div id="Produit_photo"><img src="<?=$product_image?>" alt="" width="166" height="253" border="0"  rel="#GrandeImage"/></div>
          
        <div id="Produit_texte">
            <h2><?=utf8_encode($prod->name)?></h2>
            <p><?=utf8_encode($prod->description)?><br />
            <?=utf8_encode($prod->dimension)?></p>
            <p><?=utf8_encode($prod->impression_materiel)?></p>
            <a href="javascript:;" id="Produit_bouton" onclick="add_product_to_wish_list('<?=$store_id?>','<?=$prod->id?>')"><?=gettext("Add to cart")?></a>
            <div id="bouton_fin"></div>
        </div>
    </div>
    
    <div id="Collone2">
        <div id="Panier">   
            
            <h2><?=gettext("Shopping cart")?></h2>
            <div class="Boite">
            <form action="<?=url::page('wishlist/confirm'); ?>" method="post" id="wish_form">
                <ul id="wish_list">                    
                </ul>
                <input type="submit" class="commande" name="confirm_wishlist" onclick="confirm_wishlist(); return false;" value="<?=gettext("Order now")?>" />
            </form>         
            <input type="hidden" value="<?=$store_id?>" id="store_id">
            </div>            
        </div>        
    </div>    
    
</div>

<? $popup_image = url::base().'public/images/products/250x381/0.jpg';?>
<? if (is_file(url::root().'public/images/products/250x381/'.$prod->id.'.jpg'))  $popup_image = url::base().'public/images/products/250x381/'.$prod->id.'.jpg';?>
<div class="simple_overlay" id="GrandeImage">
<img src="<?=$popup_image?>" alt="" />
</div>
<script>
$(document).ready(function() {
$("img[rel]").overlay();
});
</script>
