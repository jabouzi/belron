<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Select a category")?></h1>
        </div>        
        <?foreach($categories as $key => $category):?>
            <div class="Categorie_l"><a href="<?=base_url()?>products/lists/<?=$key?>/"><img src="<?=base_url()?>public/images/categories/cat_<?=$key?>.jpg" alt="" width="220" height="172" border="0" /></a>
                <div class="soutitre"><a href="<?=base_url()?>products/lists/<?=$key?>/" title="<?=$category[$lang]?>"><?=$category[$lang]?></a></div>
            </div>                           
        <?endforeach?>        
    </div>
</div>
