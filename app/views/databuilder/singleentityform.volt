

<div class="control-group" id="boekinggroup">
    <label class="control-label" for="boeking">Entiteit
        <ul id="boekingerror" class="parsley-errors-list"></ul>
    </label>
    <div class="controls form-group">
        <div><?php echo $form->render("type"); ?></div>
        
        <div><?php echo $form->render("entity"); ?></div>
        <div id="arguments">
            
        </div>
        <script>
            function argumentForm()
            {
               if($('#type').val() == 0)
               {
                   loadform({id:'arguments',action:'<?=$this->url->get('databuilder/argumentselectionform');?>',data:{id:'arguments',type:$('#type').val(),entity:$('#entity').val()}});
               }
               else
               {
                   loadform({id:'arguments',action:'<?=$this->url->get('databuilder/argumentsselectionform');?>',data:{id:'arguments',type:$('#type').val(),entity:$('#entity').val()}});
               } 
            }
            
            $('#type').change(function(){
               argumentForm();
            });
            $('#entity').change(function(){
               argumentForm();
            });
        </script>
        
        
    </div>
</div>