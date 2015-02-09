<div class="wrap">
 <div class="content container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Nieuwe entiteit<small></small></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <section class="widget">
                    <header>
                        <h4>
                            <i class="fa fa-file"></i>
                            Maak hier een nieuw entiteit aan
                        </h4>
                    </header>
                    <div class="body">
						<div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
						<fieldset>
							<div>
								<input type="hidden" value="<?=$count;?>" class="form-control" id="count" />
								<div class="control-group" id="titelgroup">
									<label class="control-label" for="titel">Table titel
										<ul id="titelerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("titel"); ?></div>
									</div>
								</div>
								<div class="control-group" id="aliasgroup">
									<label class="control-label" for="alias">Table alias
										<ul id="aliaserror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("alias"); ?></div>
									</div>
								</div>
							</div>	
							
							<div class="control-group" id="aliasgroup">
								<label class="control-label" for="alias">Table columns
									<ul id="aliaserror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<table>
										<thead>
											<th>Type</th>
											<th>Name</th>
											<th>Null</th>
											<th>Primary</th>
											<th>Unique</th>
										</thead>
										<tbody>
											<? for($i=0;$i<$count;$i++){ ?>
											<tr>
												<td><?=$form->render('column'.$i);?></td>
												<td><?=$form->render('name'.$i);?></td>
												<td><?=$form->render('nullcolumn'.$i);?></td>
												<td><?=$form->render('primarycolumn'.$i);?></td>
												<td><?=$form->render('uniquecolumn'.$i);?></td>
											</tr>
											<? } ?>
										</tbody>
									</table>
									<button type="submit" onclick="addrow('');" class="btn btn-primary" style="float:right;">Add column</button>
								</div>
							</div>
						</fieldset>               
						<div class="form-actions" class="pull-right">
							<button type="submit" onclick="save({class:'form-control',action:'<?=$this->url->get('settings/addentity');?>',goto:'<?=$this->url->get('user/index');?>'});" class="btn btn-primary">Opslaan</button>
							<?/*<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('user/add');?>',goto:'<?=$this->url->get('user/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
									<button type="button" onclick="go('<?=$this->url->get('user/index');?>');" class="btn btn-default">Cancel</button>*/?>
						</div>
					</div>
				</div>
                </section>
				<script>
				
					num = 0;
				
					function addrow(id)
					{
						$.ajax({
							url: '<?=$this->url->get('settings/getnewrow');?>',
							type: 'POST',
							dataType: 'json',
							data: {id:id,num:num},
							async:false,
							success: function(data)
							{
								
							}
						});
					}
				
					function loadfield(id,num)
					{
						$.ajax({
							url: '<?=$this->url->get('settings/getfield');?>',
							type: 'POST',
							dataType: 'json',
							data: {id:id,num:num},
							async:false,
							success: function(data)
							{
								if(data.bindingtitle == 1)
								{		
									
								//	alert('name'+num);
									$('#name'+num).val(data.name);
									$('#name'+num).attr('readonly','readonly');
								}
								else
								{
									$('#name'+num).removeAttr('readonly');
									$('#name'+num).val('');
								}
							}
						});							
					}
				
				
					$('.select-type').change(function(){
						id = $(this).attr('id');
						n = id.split("column");
						num = n[1];
						
						val = $(this).val();
						loadfield(val,num);
					
						
						
						
						
					});
				</script>
            </div>
        </div>
    </div>
</div>

</div>

