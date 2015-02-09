<div>
<?
foreach($selects as $relation)
{
    ?>
    <div><?=$relation;?></div>    
    <?
}
?>
</div>
<script>
    <? foreach($relationnames as $name){ ?>
        $('#<?=$name;?>').change(function(){
            data = loadEntity({action:'<?=$this->url->get('databuilder/entityselection');?>',id:'pagina-control',data:{entity:'<?=$entity;?>',argument:'<?=$name;?>',entityid:$(this).val()}}); 
        });
    <? } ?> 
</script>
<div><?php echo $form->render("argumentstextfield"); ?></div>