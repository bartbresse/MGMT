<div class="row">
    <div class="col-md-12">
        <section class="widget">
            Data builder 
            <div id="databuilder">
                <fieldset style="width:100%;">
                    <div class="control-group" id="titelgroup">
                        <label class="control-label" for="titel">Entiteit
                            <ul id="titelerror" class="parsley-errors-list"></ul>
                        </label>
                        <div class="controls form-group">
                            <div><?php echo $form->render("entity"); ?></div>
                        </div>
                    </div>
                    <div class="control-group" id="titelgroup">
                        <label class="control-label" for="titel">Aantal
                            <ul id="titelerror" class="parsley-errors-list"></ul>
                        </label>
                        <div class="controls form-group">
                            <div><?php echo $form->render("find"); ?></div>
                        </div>
                    </div>
                    <div class="control-group" id="titelgroup">
                        <label class="control-label" for="titel">Arguments
                            <ul id="titelerror" class="parsley-errors-list"></ul>
                        </label>
                        <div class="controls form-group">
                            <div><?php echo $form->render("arguments"); ?></div>
                        </div>
                    </div>
                </fieldset>
                <div class="form-actions" class="pull-right">
                    <button type="submit" onclick="addDataBuilderEntity();" class="btn btn-primary">Add</button>
                </div>
            </div>
            <div id="databuilderentityselection" style="">

            </div>
            <div id="databuildertemplate">



            </div>
            <script>
                function addDataBuilderEntity() 
                {
                    html = '<div class="control-group" id="titelgroup"><section><table id="entity" class="">';

                    html += '<input type="hidden" value="'+$('#entity').val()+'" class="entity-choice-control" id="entity"/>';
                    html += '<input type="hidden" value="'+$('#find').val()+'" class="entity-choice-control" id="find"/>';
                    html += '<input type="hidden" value="'+$('#arguments').val()+'" class="entity-choice-control" id="arguments"/>';

                    data = saveData({class:'data-builder-control',action:'<?=$this->url->get('documenttemplate/selectcolumns');?>',goto:false});

                    for(i=0;i<data.columns.length;i++)
                    {
                       html += '<tr><td>'+data.columns[i][0]+':'+data.columns[i][1]+'</td><td><input checked="checked" type="checkbox" class="entity-choice-control" id="'+data.columns[i][0]+':'+data.columns[i][1]+'" /></td></tr>'; 
                    }

                    html += '</table></section></div>';

                    html += '<div class="form-actions" class="pull-right"><button type="submit" onclick="generateDataBuilderEntity();" class="btn btn-primary">Add</button></div>';

                    $('#databuilder').hide();
                    $('#databuilderentityselection').show();
                    $('#databuilderentityselection').html(html);
                }

                function generateDataBuilderEntity()
                {
                    var data = saveData({class:'entity-choice-control',action:'<?=$this->url->get('documenttemplate/addEntity');?>',goto:false});

                    data.html += '<div class="form-actions" class="pull-right">';
                    data.html += ' <button type="submit" onclick="resetDataBuilder();" class="btn btn-primary">Reset</button>';
                    data.html += '</div>';

                    $('#databuildertemplate').html(data.html);
                    $('#databuildertemplate').show();
                    $('#databuilderentityselection').hide();
                }

                function resetDataBuilder()
                {
                    $('#databuildertemplate').hide();    
                    $('#databuilder').show();
                }
            </script>  
        </section>
    </div>
</div>