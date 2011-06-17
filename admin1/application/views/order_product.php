
<?$prod = $product[0];?>
<?
$product_image = base_url().'public/images/products/166x253/0.jpg';
if (is_file(ROOT.'public/images/products/166x253/'.$prod->id.'.jpg'))  $product_image = base_url().'public/images/products/166x253/'.$prod->id.'.jpg';
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
            <p><?=utf8_encode($prod->description)?></p>            
        </div>
    </div>    
</div>

<? $popup_image = base_url().'public/images/products/250x381/0.jpg';?>
<? if (is_file(ROOT.'public/images/products/250x381/'.$prod->id.'.jpg'))  $popup_image = base_url().'public/images/products/250x381/'.$prod->id.'.jpg';?>
<div class="simple_overlay" id="GrandeImage">
<img src="<?=$popup_image?>" alt="" />
</div>
<script>
// What is $(document).ready ? See: http://flowplayer.org/tools/documentation/basics.html#document_ready
$(document).ready(function() {
$("img[rel]").overlay();
});
</script>

