

  <div><?php echo $form->render("argument"); ?></div>
  
<script>
    
    $('#argument').change(function(){
    
        data = loadEntity({action:'<?=$this->url->get('databuilder/singleentity');?>',id:'pagina-control',data:{entity:'<?=$entity;?>',entityid:$(this).val()}});      
    
    });  
</script>