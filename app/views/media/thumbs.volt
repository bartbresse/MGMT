{{ content() }}
<div class="wrap">
	 <div class="content container">
		    <div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title">Thumbs<small></small></h2>
		        </div>
		    </div>
		    <div class="row">
		        <div class="col-md-12">
		            <section class="widget" id="mgmt-table-container">
		                <header> 		
		                    <h4>
		                        <i class="fa fa-file-alt"></i>
		                     
		                    </h4>
		                </header>
		                <div class="body">
		               <? if(isset($thumbs->items) && count($thumbs->items) > 0){ ?>
		                    <table id="datatable-table" class="table table-striped">
		                        <thead class="table table-striped table-editable no-margin">
		            			   <tr>
									<?
									$cc = 0;
									foreach($columns as $column)
									{
										//voeg een column id toe om de sort te laten werken
										?><th><a id="<?=$column;?>" class="<? if(isset($post) && $post['key'] == $column){ echo $post['order']; } ?>"><?=$lang->translate($column);?></a></th><? 
										$cc++;
									}
									?>
									<th>Categorie</th>
									<th>bewerken</th>
								   </tr>
		                        </thead>
		                        <tbody class="table table-striped table-editable no-margin">
								<?	foreach($thumbs->items as $bericht)
									{
									?><tr id="<?=$bericht->id;?>"><?	
										foreach($columns as $column)
										{
											 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$bericht->$column;?></td><? }
										}
										?>
										<td id="bewerken" style="width:100px;">
											<a href="<?php echo  $this->url->get("bericht/view&id=".$bericht->id);?>"><i class="fa fa-search"></i></a>
											<a href="<?php echo  $this->url->get('bericht/edit&id='.$bericht->id);?>"><i class="fa fa-edit"></i></a>
											<a onclick="del('<?=$bericht->id;?>','bericht');"><i class="fa fa-trash-o"></i></a>
										</td>
									 </tr><?		
									}
								
								?>
		                        </tbody>
		                    </table>
					  	 <? } //end if entities
							else
							{
							?>
							<table>
								<tr><td> Er zijn nog geen rijen gevonden. </td></tr>
							</table>
							<?
							} ?>
							
							
							<table id="datatable-table" class="table">
								<tr>
									<th>titel</th>
									<th>x</th>
									<th>y</th>
									<th>crop</th>
								</tr>
								<tr>
									<td><input id="titel" class="form-control" type="text" /></td>
									<td><input id="x" class="form-control" type="text" /></td>
									<td><input id="y" class="form-control" type="text" /></td>
									<td>
										<select id="crop" class="form-control">
											<option value="1">Ja</option>
											<option value="0">Nee</option>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="0"><button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('media/addthumb');?>',goto:'<?=$this->url->get('media/thumbs');?>'});" class="btn btn-primary">Opslaan</button></td>
								</tr>
							</table>
							
		                </div>
						<div class="clearfix">		
							<div class="pull-right">
								<? if(isset($_GET['id'])){ $id = '&categoryid='.$_GET['id']; }else{ $id ='';} ?>
								<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('bericht/new'.$id);?>');">
		                            <i class="fa fa-plus"></i>
		                            Nieuw
		                        </button>
							</div>		
							<?=$this->partial("table/export");?> 
							<div>
							

		                    </div>
						</div>
		            </section>
		        </div>
				<?=$this->partial("table/selectcolumns");?> 
		    </div>
		</div>
		
		
	</div>
</div>


