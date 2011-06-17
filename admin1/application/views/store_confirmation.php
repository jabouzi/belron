<div id="Container_Principale_full">
    <div id="Container_Principale_960">
        <div id="Page_haut">
            <div id="Breadcrumb"><a href="#">&nbsp;</a></div>
            <h1><?=gettext("Confirmation")?></h1>
        </div>              
        <div>
        <b><?=gettext("Your order has been submitted with success")?></b>
        </div>    
        <br />   
        <div>
            <?foreach($orders as $order):?>
                <div class="approuved_pdf"><a href="<?=base_url().'orders/invoice/'.$order?>" target="_blank" ><img src="<?=base_url();?>public/images/pdf.png" width="40" height="40"/></a> <a href="<?=base_url().'wishlist/invoice/'.$order?>" target="_blank" ><?=gettext("Download the invoice")?> #<?=$order?></a></div>            
            <?endforeach?>

        <?if ($usertype == 3):?>
            <b><a href="<?=base_url()?>categories"><?=gettext("Go back to home page")?></a></b>
        <?else:?>
            <b><a href="<?=base_url()?>admin/dashboard"><?=gettext("Go back to home page")?></a></b>
        <?endif?>        
        </div>
    </div>
</div>
