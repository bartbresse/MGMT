<?php ?> 


<div class="wrap">

<div class="content container">
	<div class="row">
		<div class="col-md-12"> 
		    <h2 class="page-title">Genereer front-end<small></small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" style="background-color:#fff;">
		<header>
			<h4>
			   <i class="fa fa-file"></i>
			   Maak hier de Controllers,Views en entiteiten aan voor het front-end.
			</h4>
		</header>
		
		<br />
		<p>Dit formulier representeert de pagina structuur: iedere controller heeft views en iedere view geeft een aantal entiteiten weer.</p>
		<p>De args kolom respresenteert de database argumenten: <a target="_blank" href="http://docs.phalconphp.com/en/latest/reference/models.html#finding-records">Finding records</a></p>
		<br />
		<ul>
			<li><b>Single</b> find of findfirst? </li>
			<li><b>Struct</b> Detail pagina van een entiteit, er kunnen dus meerdere entiteit zijn. De eerste entiteit is de structure entiteit</li>
			<li><b>Login</b> heeft de pagina een ingelogde gebruiker nodig? </li>
		</ul>
		<br />
		<table class="table table-bordered">
		<tr>
			<th>Controller</th>
			<th>View</th>
			<th>Entity</th>
			<th>Args</th>
			<th style="width:20px;"><span class="letter">Single</span></th>
			<th style="width:20px;"><span class="letter">Struct</span></th>
			<th ><span class="letter">Login</span></th>
			<th style="width:20px;"></th>
		<tr>
		<?php 
		$cc3=0;$cc2=0;$cc4=1000;
		
			foreach($controllers as $controller)
			{
				if(count($controllers) > 0){
				?><tr><td colspan="3"><?=$controller->title;?></td><td></td><td></td><td></td><td></td><td></td></tr><?
				foreach($controller->Controllerview as $view)
				{
					$notablex[$cc3] = $notables;
					?><tr><input type="hidden" value="<?=$view->id;?>" class="view-control<?=$cc4;?>" id="viewid"/>
						  <input type="hidden" value="<?=$controller->id;?>" class="view-control<?=$cc4;?>" id="controllerid"/>	
						  <td>&nbsp;</td><td colspan="2"><input class="form-control view-control<?=$cc4;?>" type="text" value="<?=$view->title;?>" id="view" /></td>
						  <td></td><td></td>
						  <td><input type="checkbox" value="" id="struct" <? if($view->struct == 1){ echo 'checked = "checked"'; } ?>  class="view-control<?=$cc4;?>  form-control" /></td>
						  <td><input type="checkbox" value="" id="login" <? if($view->login == 1){ echo 'checked = "checked"'; } ?> class="view-control<?=$cc4;?>  form-control" /></td>
						  <td><button onclick="save({goto:'<?=$this->url->get('settings/page');?>',class:'view-control<?=$cc4;?>',action:'<?=$this->url->get('process/addview');?>'});return false;">save</button></td>
					  </tr><?
						$cc4++;
						foreach($view->Entity as $entity)
						{
							$notablex[$cc4] = $notables;
							array_push($notablex[$cc3],$entity->title);
						
							?><tr><td>&nbsp;</td>
								  <td>&nbsp;</td>	
								  <td>
									<input type="hidden" class="entity-control<?=$cc4;?>" id="id"  value="<?=$view->id;?>" />
									<input type="hidden" class="entity-control<?=$cc4;?>" id="entityid"  value="<?=$entity->id;?>" />
									<select class="entity-control<?=$cc4;?> form-control" id="entity">
										<? foreach($tables as $table){ 									
										if(!in_array($table['Tables_in_'.DATABASENAME],$notablex[$cc4])){ ?>
										<option <? if($entity->title == $table['Tables_in_'.DATABASENAME]){ echo 'selected'; } ?> value="<?=$table['Tables_in_'.DATABASENAME];?>"><?=$table['Tables_in_'.DATABASENAME];?></option>
										<? }} ?>
									</select>
								  </td>
								  <td><textarea class="entity-control<?=$cc4;?> form-control" id="args"><?=$entity->args;?></textarea></td>
								  <td><input type="checkbox" id="single" value="" <? if($entity->single == 1){ echo 'checked'; } ?> class="entity-control<?=$cc4;?> form-control"/></td>
								  <td></td>
								  <td><button onclick="save({goto:'<?=$this->url->get('settings/page');?>',class:'entity-control<?=$cc4;?>',action:'<?=$this->url->get('process/addentity');?>'});return false;">save</button></td>
								  <td><button onclick="save({goto:'<?=$this->url->get('settings/page');?>',class:'entity-control<?=$cc4;?>',action:'<?=$this->url->get('process/deleteentity');?>'});return false;">delete</button></td></tr><?	
								$cc4++;	
						}			
						?><tr><td>&nbsp;</td><td>&nbsp;</td>
							<td>add new entity
								<input type="hidden" class="entity-control<?=$cc3;?>" id="id"  value="<?=$view->id;?>" />
								<select class="entity-control<?=$cc3;?> form-control" id="entity">
									<? foreach($tables as $table){ 
									if(!in_array($table['Tables_in_'.DATABASENAME],$notablex[$cc3])){ ?>
									<option value="<?=$table['Tables_in_'.DATABASENAME];?>"><?=$table['Tables_in_'.DATABASENAME];?></option>
									<? }} ?>
								</select>
							</td>
							<td><textarea id="args" class="entity-control<?=$cc3;?> form-control"></textarea></td></td>
							<td><input type="checkbox" id="single" value="1" class="entity-control<?=$cc3;?> form-control"/></td>					
							<td></td>
							<td></td>
							<td><button onclick="save({goto:'<?=$this->url->get('settings/page');?>',class:'entity-control<?=$cc3;?>',action:'<?=$this->url->get('process/addentity');?>'});return false;">add</button></td>									
						</tr><?
								$cc3++;
				} 
					?><tr><td>&nbsp;</td>
									<td colspan="2">
									add new view 
									<input id="controllerid" class="view-control<?=$cc2;?>" type="hidden" value="<?=$controller->id; ?>"/>							
									<input id="view" class="view-control<?=$cc2;?> form-control" type="text" value="" /></td>
									<td></td>
									<td></td>
									<td><input type="checkbox" class="view-control<?=$cc2;?> form-control" id="struct" /></td>
									<td><input type="checkbox" class="view-control<?=$cc2;?> form-control" id="login" /></td>
									<td><button onclick="save({goto:'<?=$this->url->get('settings/page');?>',class:'view-control<?=$cc2;?>',action:'<?=$this->url->get('process/addview');?>'});return false;">add</button></td>				
					</tr><?
					$cc2++;	
			}
		}	    ?><tr>
					<td colspan="3">title
						<input type="text" value="" class="controller-control form-control" id="title" />
					</td>
					<td>controllername<input type="text" value="" class="controller-control form-control" id="controllername" /></td>
					<td></td>
					<td></td>
					<td></td>
<td><button onclick="save({goto:'<?=$this->url->get('settings/page');?>',class:'controller-control',action:'<?=$this->url->get('process/addcontroller');?>'});return false;">add</button></td>	
				  </tr><?
		?>
		<tr><td colspan="7">HEADER / FOOTER</td></tr>
		<?  $cc3++;
			$notablex[$cc3] = $notables;
			$menu = Controllerview::findFirst('title = "HEADERFOOTER"');		
			if(isset($view))
			{ 	
				foreach($view->Entity as $entity)
				{
					?><tr><td>&nbsp;</td><td>&nbsp;</td><td><?=$entity->title;?></td>
						  <td><textarea class="form-control"><?=$entity->args;?></textarea></td>
						  <td><input type="checkbox" value="" <? if($entity->single == 1){ echo 'checked'; } ?> class="form-control"/></td><td></td><td></td></tr><?
							array_push($notablex[$cc3],$entity->title);
				}
			}			
			?><tr><td>&nbsp;</td><td>&nbsp;</td>
					<td>add new entity
						<input type="hidden" class="entity-control<?=$cc3;?>" id="id"  value="<?=$view->id;?>" />
						<select class="entity-control<?=$cc3;?> form-control" id="entity">
							<? foreach($tables as $table){ 
							if(!in_array($table['Tables_in_'.DATABASENAME],$notablex[$cc3])){ ?>
							<option value="<?=$table['Tables_in_'.DATABASENAME];?>"><?=$table['Tables_in_'.DATABASENAME];?></option>
							<? }} ?>
						</select>
					</td>
					<td><textarea id="args" class="entity-control<?=$cc3;?> form-control"></textarea></td></td>					
					<td>
						<input type="checkbox" id="single" value="1" class="entity-control<?=$cc3;?> form-control"/>
					</td>
					<td></td>
					<td></td>								
<td><button onclick="save({goto:'<?=$this->url->get('process/addentity');?>',class:'entity-control<?=$cc3;?>',action:'<?=$this->url->get('process/addentity');?>'});return false;">add</button></td>									
							</tr><? $cc3++;	?>
		</table>
		<div class="clearfix">
			<div class="pull-right">
					<button class="btn btn-primary" onclick="save({action:'<?=$this->url->get('process/initfrontend');?>',goto:'index/index',class:'form-control',});return false;">
						Genereer frontend
					</button>
					<button class="btn btn-primary" onclick="go('<?php echo  $this->url->get('settings/entity'); ?>');">
						Genereer entiteiten 
					</button>
				</div>
			</div>
		</div>
	</div>

</div>
</div>
