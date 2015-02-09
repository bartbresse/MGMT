<div class="wrap">
 <div class="content container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Backend management<small></small></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="widget">
                    <header>
                        <h4>
                           <i class="fa fa-file-alt"></i>
                           Selecteer hieronder de te managen entiteiten in het backend.
                        </h4>
                    </header>
					<div class="body">
						<script>
							function entity()
							{
								val = $('.form-control').val();
								html = '<tr><input type="hidden" value="'+val+'" class="entity-control" id="'+val+'" /><td colspan="2">'+val+'<input type="hidden" value="'+val+'" class="entity-control" name="cat_'+val+'" /></td><td><input type="checkbox"  class="entity-control" id="file_'+val+'" value="file"/></td></tr>';
								$('#table').prepend(html);
							}
						</script>
						<table class="table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>Entiteit</th>
									<th>Alias</th>
									<th>Clearance</th>
									<th></th>
									<th>Multiple file upload</th>
									<th>Contains widgets</th>
									<th colspan="2">Additional functions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td><input type="checkbox" id="entity" /></td>
									<td><input type="checkbox" id="file" /></td>
									<td><input type="checkbox" id="widget" /></td>
									<td>new/edit</td>
									<td>view</td>
									<td>icon</td>
								</tr>
								<? foreach($tables as $table)
								   {
								   ?>
									<tr>
										<td><?=$table->titel;?></td>
										<td><input type="text" class="entity-control" id="alias_<?=$table->titel;?>" value="<?=$table->alias;?>" /></td>
										<td><input type="text" class="entity-control" id="clearance_<?=$table->titel;?>" value="<?=$table->clearance;?>"  /></td>
										<td><input type="checkbox"  class="entity-control entity" id="<?=$table->titel;?>" value=""/></td>
										<td><input type="checkbox"  class="entity-control file" id="file_<?=$table->titel;?>" value=""/></td>
										<td><input type="checkbox"  class="entity-control widget" id="widget_<?=$table->titel;?>" value=""/></td>
										<td>
											<select multiple="" id="functions_<?=$table->titel;?>_new" class="entity-control tag-multiple-select"  data-placeholder="Kies extra functionaliteit hier">
												<? $neweditarray = unserialize($table->newedit);
													if(!is_array($neweditarray)){ $neweditarray = array(); }
													foreach($functions as $function){ 			
													?>
													<option <? if(in_array($function['entity'].'_'.$table->titel.'_new_'.$function['template'],$neweditarray)){ echo 'selected="selected"'; } ?>  value="<?=$function['entity'].'_'.$table->titel;?>_new_<?=$function['template'];?>"><?=$function['titel'];?></option>
												<? } ?>
											</select>
										</td>
										<td>
											<select multiple="" id="functions_<?=$table->titel;?>_view" class="entity-control tag-multiple-select"  data-placeholder="Kies extra functionaliteit hier">
												<? $viewarray = unserialize($table->view);
													if(!is_array($viewarray)){ $viewarray = array(); }
													foreach($functions as $function){ ?>
													<option <? if(in_array($function['entity'].'_'.$table->titel.'_view_'.$function['template'],$viewarray)){ echo 'selected="selected"'; } ?> value="<?=$function['entity'].'_'.$table->titel;?>_view_<?=$function['template'];?>"><?=$function['titel'];?></option>
												<? } ?>
											</select>
										</td>
										<td>
											<select id="functions_<?=$table->titel;?>_icon" class="entity-control tag-multiple-select"  data-placeholder="Kies extra functionaliteit hier">
												<? foreach($icons as $icon){ ?>
													<option value="<?=$function['entity'].'_'.$table->titel;?>_<?=$icon;?>"><?=$function['titel'];?></option> 
												<? } ?>
											</select>
										</td>
									</tr>
								<? } ?>
							</tbody>
						</table>
						<script type="text/javascript">
							
								$('#entity').on('click', function () {
									$('.entity').prop('checked', this.checked);
								});
								$('#file').on('click', function () {
									$('.file').prop('checked', this.checked);
								});
								$('#widget').on('click', function () {
									$('.widget').prop('checked', this.checked);
								});
							
							 $(".tag-multiple-select").chosen({ width: '100%' });
						</script>
						<button onclick="save({action:'<?php echo  $this->url->get(); ?>process/init',goto:'<?php echo $this->url->get(); ?>',class:'entity-control',});return false;">
							GO
						</button>
					</div>
                </section>
            </div>
        </div>
    </div>
</div>

</div>

