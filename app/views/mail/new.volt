<div class="wrap">
    <div class="content container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title"><?=$lang->translate('Nieuwe email');?></h2>
            </div>
        </div>
        <?=$this->partial('databuilder/singleentitybuilder');?>
        <div class="row">
            <div class="col-md-12">
                <section class="widget">
                    <?=$this->partial("mail/create");?>
                </section>
            </div>	
        </div>
        
        <script>          
            $(function(){
                
                

                <? if(isset($_GET['entity']) && isset($_GET['entityid'])){ $data = ",data:{entity:'".$_GET['entity']."',entityid:'".$_GET['entityid']."'}"; }else{ $data = ',data:{id:""}'; } ?>
                data = loadEntity({action:'<?=$this->url->get('databuilder/singleentity');?>',id:'pagina-control'<?=$data;?>});
                
                
            });                    
                         
        </script>
        
    </div>		
</div>