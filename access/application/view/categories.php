<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Select a category")?></h1>
        </div>              
        <?foreach($categories as $key => $category):?>                
            <div class="Categorie_l"><a href="<?=url::base()?>products/products/<?=$category->id?>/"><img src="<?=url::base()?>public/images/categories/cat_<?=$key+1?>.jpg" alt="<?=utf8_encode($category->{'name_'.session::get('lang')})?>" width="220" height="172" border="0" /></a>
                <div class="soutitre"><a href="<?=url::base()?>products/products/<?=$category->id?>/" title="<?=utf8_encode($category->category_name)?>"><?=utf8_encode($category->{'name_'.session::get('lang')})?></a></div>
            </div>                           
        <?endforeach?>        
    </div>
</div>
