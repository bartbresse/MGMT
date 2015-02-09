{{ content() }}
<div class="wrap">
	 <div class="content container">
		    <div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title">Standaard e-mails<small></small></h2>
		        </div>
		    </div>
		    <div class="row">
		        <div class="col-md-12">
		            <section class="widget" id="mgmt-table-container">
					
					
		                <header>
							<? if(count($messages->items) > 39){ ?>
							<div class="pull-right">
								<ul class="pagination no-margin number-of-rows">
									<li class="active">
										<a>20</a>
									</li>
									 <li>
										<a>40</a>
									</li>
									 <li>
										<a>80</a>
									</li>
								</ul>
							</div>
							<? } ?>			       		
		                    <h4>
		                        <i class="fa fa-file-alt"></i>
		                     
		                    </h4>
		                </header>
		                <div class="body">
		               <? if(isset($messages->items) && count($messages->items) > 0){ ?>
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
									<th>bewerken</th>
								   </tr>
		                        </thead>
		                        <tbody class="table table-striped table-editable no-margin">
								<?
									if(count($messages) > 0){
										foreach($messages->items as $message)
										{
										?><tr id="<?=$message->id;?>"><?	
											foreach($columns as $column)
												{
													 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$message->$column;?></td><? }
												}
											?>
											<td id="bewerken" style="width:100px;">
												<?  /*	
												if(in_array('edit',$actions))
												{ ?><a href="<?php echo  $this->url->get("message/view&id=".$message->id);?>"><i class="fa fa-search"></i></a><? }
												if(in_array('edit',$actions))
												{ ?><a href="<?php echo  $this->url->get('message/edit&id='.$message->id);?>"><i class="fa fa-edit"></i></a><? }
												if(in_array('delete',$actions))
												{ ?><a onclick="del('<?=$message->id;?>','message');"><i class="fa fa-trash-o"></i></a><?	} 
												*/ ?>
												<a href="<?php echo  $this->url->get("message/view&id=".$message->id);?>"><i class="fa fa-search"></i></a>
												<a href="<?php echo  $this->url->get('message/edit&id='.$message->id);?>"><i class="fa fa-edit"></i></a>
												<a onclick="del('<?=$message->id;?>','message');"><i class="fa fa-trash-o"></i></a>
										    </td>
										 </tr><?		
										}
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
		                </div>
						<div class="clearfix">		
							<div class="pull-right">
								<? if(isset($_GET['id'])){ $id = '&categoryid='.$_GET['id']; }else{ $id ='';} ?>
								<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('message/new'.$id);?>');">
		                            <i class="fa fa-plus"></i>
		                            Nieuw
		                        </button>
							</div>		
							<?=$this->partial("table/export");?> 	
							<div>
	<? 	$controller = 'messages';
		$model = 'message';	?>
		<?=$this->partial("table/pagination");?> 


		                    </div>
						</div>
						
						
						
		            </section>
		        </div>
				<?=$this->partial("table/selectcolumns");?> 
		    </div>
		</div>
	</div>
</div>




<?/* if($auth['clearance'] > 800){ ?>
<div  style="position:absolute;margin-top:10px;top:400px;">
	<nav id="sidebar" style="position:relative;" class="sidebar nav-collapse collapse hidden-phone-landscape ">
		<?php $entity = explode('/',$_GET['_url']); ?>	
		<input type="hidden" class="column-select" id="entity" value="<?=$entity[1];?>"  />       
		 <ul id="side-nav"  class="side-nav">
		    <li><a id="column-select-show">
			   <i class="fa fa-cog"></i>
	  		   <span class="name">Selecteer kolommen</span>
		    </a></li>
		</ul>
		<ul id="side-nav" style="display:none;" class="side-nav column-select-slide">
		    <?	foreach($allcolumns as $allcolumn)
				{
				?><li class="panel" style="margin-left:20px;">
						<table>
							<tr><td style="width:100px;"><?=$lang->translate($allcolumn);?></td><td><input class="column-select" style="width:25px;"	<? if(in_array($allcolumn,$columns)){ echo 'checked="checked"'; } ?> value="1" class="" id="<?=$allcolumn;?>" type="checkbox"/> </td></tr>
						</table>
				   </li><?
				} ?>
		</ul>
	</nav>
<!--	<nav id="sidebar" style="position:relative;margin-top:10px;" class="sidebar nav-collapse collapse hidden-phone-landscape ">
		<?php $entity = explode('/',$_GET['_url']); ?>	
		<input type="hidden" class="column-select" id="entity" value="<?=$entity[1];?>"  />    
		<ul id="side-nav"  class="side-nav">
		    <li><a id="column-select-show2">
			   <i class="fa fa-cog"></i>
	  		   <span class="name">Instellingen</span>
		    </a></li>
		</ul>
		<ul id="side-nav" style="display:none;" class="side-nav column-select-slide2">
			<li class="panel"><a><input value="1" class="column-select" id="" type="checkbox"/> Export </a></li>
			<li class="panel"><a><input value="1" class="column-select" id="" type="checkbox"/> Inline edit </a></li>
			<li class="panel"><a><input value="1" class="column-select" id="" type="checkbox"/> sort</a></li>
		</ul>
	</nav>-->
</div>
<? }*/ ?>
