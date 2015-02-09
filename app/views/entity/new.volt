<div class="wrap">
	 <div class="content container">
		    {{ javascript_include('js/entity.js') }}
			<div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title"><?=$lang->translate('Nieuwe entiteit');?></h2>
		        </div>
		    </div>
		    <div class="row">
				 <div class="col-md-12">
                                <section class="widget" id="mgmt-table-container">
                                    <input type="hidden" value="<? if(isset($_GET['id'])){ echo $_GET['id']; } ?>" id="entityid" class="form-control" />
                                    <div>
                                        
                                        
                                        <label class="control-label" for=""><?=$t->_("module");?>
                                            <ul id="nameerror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                            <div><?php echo $form->render("module"); ?></div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="namegroup">
                                        <label class="control-label" for=""><?=$t->_("name");?>*
                                            <ul id="nameerror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                            <div><?php echo $form->render("name"); ?></div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="commentgroup">
                                        <label class="control-label" for=""><?=$t->_("comment");?>
                                            <ul id="commenterror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                           <div><?php echo $form->render("comment"); ?></div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="aliasgroup">
                                        <label class="control-label" for=""><?=$t->_("alias");?>*
                                            <ul id="aliaserror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                            <div><?php echo $form->render("alias"); ?></div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="newtextgroup">
                                        <label class="control-label" for=""><?=$t->_("newtext");?>*
                                            <ul id="aliaserror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                            <div><?php echo $form->render("newtext"); ?></div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="clearancegroup">
                                        <label class="control-label" for=""><?=$t->_("clearance");?>*
                                            <ul id="clearanceerror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                            <div><?php echo $form->render("clearance"); ?></div>
                                        </div>
                                    </div>
                                    
                                    <?/*
                                    <div class="control-group" id="clearancegroup">
                                        <label class="control-label" for=""><?=$lang->translate("Is a new module ");?>
                                            <ul id="clearanceerror" class="parsley-errors-list"></ul>
                                        </label>
                                        <div class="controls form-group">
                                            <div><?php echo $form->render("ismodule"); ?></div>
                                        </div>
                                    </div>
                                    */?>
                                    
                                    
                                    
                                    <input type="hidden" value="0" id="form-num" class="form-control" />
                                    <p>De ID kolom word automatisch toegevoegd.</p>
                                    <table class="table">
                                            <thead>
                                                    <th><i class="fa fa-trash"> </i></th>
                                                    <th>Show</th>
                                                    <th>MGMT Type</th>
                                                    <th>Name</th>
                                                    <th>Alias</th>
                                                    <th>Type</th>
                                                    <th>Length</th>
                                                    <th>Default</th>
                                                    <th>Null</th>
                                                    <th>Unique</th>
                                                    <th>Comments</th>
                                            </thead>
                                            <tbody id="line-table">
                                                    <tr id="0" class="form-line">
                                                            <td></td>
                                                            <td><input type="checkbox" class="form-control" readonly id="show0" /></td>
                                                            <td>id<input type="hidden" value="id" id="mgmttype0" class="form-control" /></td>
                                                            <td><input type="text" value="id" readonly class="form-control copytoalias" id="name0" /></td>
                                                            <td><input type="text" value="id" readonly class="form-control" id="alias0" /></td>
                                                            <td>VARCHAR<input class="form-control" value="VARCHAR" id="type0" type="hidden"/></td>
                                                            <td><input type="text" class="form-control" value="36" readonly id="length0" /></td>
                                                            <td><input type="text" class="form-control" value="" readonly id="default0" /></td>
                                                            <td><input type="checkbox" class="form-control" value="0" readonly id="null0" /></td>
                                                            <td><input type="checkbox" class="form-control" value="1" readonly id="unique0" /></td>
                                                            <td><input type="text" class="form-control" value="" readonly id="comments0" /></td>
                                                    </tr>
                                                    <? 	if(isset($_GET['id']))
                                                        {
                                                            $cc=1;
                                                            foreach($lines as $line)
                                                            {
                                                                if($line->name != 'id')
                                                                {
                                                                    ?>
                                                                    <tr id="<?=$cc;?>" class="form-line">
                                                                        <td> <i class="fa fa-trash-o fa-2x" onclick="deleteLine('<?=$cc;?>');"></i> &nbsp;&nbsp;</td>
                                                                        <td><input type="checkbox" class="form-control" id="show<?=$cc;?>" value="" <? if($line->show == 1){ echo 'checked'; }?> /></td>
                                                                        <td>
                                                                            <select class="form-control mgmttype" id="mgmttype<?=$cc;?>">
                                                                                    <option value=""></option> 
                                                                                    <?	$keys = array_keys($mgmtoptions);	
                                                                                            foreach($keys as $key)
                                                                                            {
                                                                              ?><option value="<?=$key;?>" <? if($line->mgmttype == $key){ echo 'selected'; } ?> ><?=$mgmtoptions[$key];?></option><?																
                                                                                            }
                                                                                    ?>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="text" value="<?=$line->name;?>" class="form-control copytoalias" id="name<?=$cc;?>" /></td>
                                                                        <td><input type="text" value="<?=$line->alias;?>" class="form-control" id="alias<?=$cc;?>" /></td>
                                                                        <td>
                                                                            <select class="form-control" id="type<?=$cc;?>" value="">
                                                                                    <?  foreach($options as $option)
                                                                                            {
                                                                                                    $type = explode('(',$line->type);

                                                                              ?><option value="<?=$option;?>" <? if(strtoupper($type[0]) == $option){ echo 'selected';} ?>><?=$option;?></option><?
                                                                                            } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="text" class="form-control" id="length<?=$cc;?>" value="<? if($line->length > 0){ echo $line->length; }?>" /></td>
                                                                        <td><input type="text" class="form-control" id="default<?=$cc;?>" value="<? if($line->default > 0){ echo $line->default; }?>"/></td>
                                                                        <td><input type="checkbox" class="form-control" id="null<?=$cc;?>" <? if($line->null == 1){ echo 'checked';}?>/></td>
                                                                        <td><input type="checkbox" class="form-control" id="unique<?=$cc;?>" <? if($line->unique == 1){ echo 'checked';}?> value="<?=$line->unique;?>"/></td>
                                                                        <td><input type="text" class="form-control" id="comments<?=$cc;?>" value="<?=$line->comments;?>"/></td>
                                                                    </tr>
                                                                    <? 
                                                                    $cc++;
                                                                }
                                                            }
                                                            ?><script>line = <?=$cc-1;?>;$('#form-num').val(line);</script><?
                                                        } ?>
                                            </tbody>
                                    </table>
                                    <script>
                                            $('.copytoalias').change(function(){

                                                    num = $(this).attr('id').split('name');

                                                    $('#alias'.num[1]).val($(this).val());
                                            });
                                    </script>
                                    <div>
                                            <button type="button" id="addline" class="btn btn-primary">Add row</button>
                                    </div>
                                    <h3>Relaties</h3>
                                    <div style="width:300px;">
                                            This entity 
                                            <select style="width:300px;" id="relationtype">
                                                    <option value="HasOne">HasOne</option>
                                                    <option value="HasMany">HasMany</option>
                                                    <option value="BelongsTo">BelongsTo</option>
                                                    <option value="hasManyToMany">hasManyToMany</option>							
                                            </select>
                                            relations to
                                            <?=$form->render('entities');?>
                                    </div>
                                    <button type="button" onclick="addrelation();" class="btn btn-primary">Add</button>	
                                    <table class="table" id="relations">
                                            <thead>
                                                <th></th>
                                                <th>Relatie</th>
                                                <th>Entiteit</th>
                                            </thead>
                                            <tbody id="relations-body">
                                                <? $cc=0;
                                                foreach($relations as $relation){
                                                ?>
                                                <tr id="relation<?=$cc;?>">
                                                    <td><i class="fa fa-trash-o fa-2x" onclick="deleteRelation('<?=$cc;?>');"></i>&nbsp;&nbsp;</td>
                                                    <td><input type="hidden" id="relationtype<?=$cc;?>" class="form-control" value="<?=$relation->relationtype;?>"/><?=$relation->relationtype;?></td>
                                                    <td><input type="hidden" id="relationentity<?=$cc;?>" class="form-control" value="<?=$relation->toname;?>"/><?=$relation->toname;?></td>
                                                </tr>	
                                                <? $cc++;
                                                }?>
                                            </tbody>
                                    </table>
                                    <script>
                                            function deleteRelation(cc)
                                            {
                                                    $('#relation'+cc).remove();
                                            }

                                            cc = <?=$cc;?>;
                                            function addrelation()
                                            {
                                                type = $('#relationtype').val();
                                                entity = $('#entities').val();
                                                $('#relations-body').append('<tr id="relation<?=$cc;?>"><td><i class="fa fa-trash-o fa-2x" onclick="deleteRelation(\''+cc+'\');"></i>&nbsp;&nbsp;</td><td><input type="hidden" id="relationtype'+cc+'" class="form-control" value="'+type+'"/>'+type+'</td><td><input type="hidden" id="relationentity'+cc+'" class="form-control" value="'+entity+'"/>'+entity+'</td></tr>');
                                                cc++;


                                            }	
                                    </script>	
                                    
                                    
                                    <?/*
                                    
                                    <h3>Widgets</h3>	
                                    <div style="width:300px;">
                                            <select style="width:300px;" id="widgettype">
                                                    <option value="Certificaten">Certificaten</option>
                                                    <option value="Klanten">Klanten</option>
                                                    <option value="Email">Email</option>
                                                    <option value="Files">Files</option>	
                                                    <option value="Custom">Custom</option>									
                                            </select>

                                            <select style="width:300px;" id="widgetwidth">
                                                    <option value="4">4</option>
                                                    <option value="6">6</option>
                                                    <option value="8">8</option>
                                                    <option value="12">12</option>								
                                            </select>

                                    </div>
                                    <button type="button" onclick="addWidget();" class="btn btn-primary">Add</button>
                                    <table class="table" id="widgets">
                                            <thead>
                                                    <th>Widget</th>
                                                    <th>Breedte</th>
                                                    <th>Beschrijving</th>
                                            </thead>
                                            <tbody id="widgets-body">

                                            </tbody>
                                    </table>
                                    <script>
                                            function addWidget()
                                            {
                                                    widgettype = $('#widgettype').val();
                                                    widgetwidth = $('#widgetwidth').val();
                                                    $('#widgets-body').append('<tr><td>'+widgettype+'</td><td>'+widgetwidth+'</td><td></td></tr>');
                                                    cc++;
                                            }	
                                    </script>	
                                    <br /><br />
                                    
                                    */?>
                                    <div>
                                            <button type="button" onclick="save({ class:'form-control',action:'<?=$this->url->get('entity/add');?>',goto:'<?=$this->url->get('entity');?>'});" class="btn btn-primary">Opslaan</button>
                                    </div>
                                </section>
                                <script>
                                        $('.mgmttype').change(function(){
                                                id = $(this).attr('id');
                                                setline(id,$(this).val());
                                        });
                                </script>
                        </div>
		    </div>
		</div>
	</div>
</div>
